<?php

namespace App\Controllers;

// Models
use App\Models\PemesananModel;
use App\Models\DetailPemesananModel;
use App\Models\RiwayatTransaksiModel;
use App\Models\ProdukModel;
use App\Models\UserModel;
use App\Models\ResepProdukModel;
use App\Models\StokModel;
use App\Models\PromoModel; // 1. Pastikan PromoModel sudah ditambahkan

class Pemesanan extends BaseController
{
    private $binderByteApiKey;
    private $komerceApiKey;
    private $originCityId;
    private $originCityName;

    public function __construct()
    {
        $this->binderByteApiKey = getenv('BINDERBYTE_API_KEY');
        $this->komerceApiKey = getenv('KOMERCE_API_KEY');
        $this->originCityId = '55'; // ID Kota Asal (SUMEDANG)
        $this->originCityName = 'SUMEDANG';
    }

    // --- FUNGSI ASLI ANDA (Tidak Diubah) ---
    private function _getVirtualIngredientStock(): array
    {
        $stokModel = new StokModel();
        $resepModel = new ResepProdukModel();
        $keranjang = session()->get('keranjang') ?? [];
        $stokAsliDb = $stokModel->findAll();
        $stokVirtual = [];
        foreach ($stokAsliDb as $stok) {
            $stokVirtual[$stok['id_bahan']] = $stok['jumlah'];
        }
        if (!empty($keranjang)) {
            foreach ($keranjang as $item) {
                $resep = $resepModel->where('id_produk', $item['id_produk'])->findAll();
                foreach ($resep as $bahan) {
                    if (isset($stokVirtual[$bahan['id_bahan']])) {
                        $jumlahKurangi = $bahan['jumlah'] * $item['jumlah'];
                        $stokVirtual[$bahan['id_bahan']] -= $jumlahKurangi;
                    }
                }
            }
        }
        return $stokVirtual;
    }

    private function _hitungStokTersedia(int $id_produk, array $stokVirtual): int
    {
        $resepModel = new ResepProdukModel();
        $resep = $resepModel->where('id_produk', $id_produk)->findAll();
        $maxPesan = PHP_INT_MAX;
        if (empty($resep)) {
            return 0;
        }
        foreach ($resep as $item) {
            $stokBahan = $stokVirtual[$item['id_bahan']] ?? 0;
            if ($item['jumlah'] > 0) {
                if ($stokBahan > 0) {
                    $maxPerBahan = floor($stokBahan / $item['jumlah']);
                    $maxPesan = min($maxPesan, $maxPerBahan);
                } else {
                    return 0;
                }
            }
        }
        return ($maxPesan === PHP_INT_MAX) ? 0 : $maxPesan;
    }

    public function index()
    {
        $produkModel = new ProdukModel();
        $promoModel = new PromoModel(); // Ambil data promo

        $produkData = $produkModel->orderBy('bestseller', 'DESC')->findAll();
        $stokVirtual = $this->_getVirtualIngredientStock();
        foreach ($produkData as &$produk) {
            $produk['stok_tersedia'] = $this->_hitungStokTersedia($produk['id_produk'], $stokVirtual);
        }
        $data['produk'] = $produkData;
        $data['keranjang'] = session()->get('keranjang') ?? [];
        $data['promoAktif'] = $promoModel->where('status', 'aktif')->first(); // Kirim promo aktif ke view

        return view('pemesanan_form', $data);
    }

    private function _validasiStokKeranjang(array $keranjang)
    {
        if (empty($keranjang)) {
            return true;
        }
        $resepModel = new ResepProdukModel();
        $stokModel = new StokModel();
        $bahanDibutuhkan = [];
        foreach ($keranjang as $item) {
            $resep = $resepModel->where('id_produk', $item['id_produk'])->findAll();
            if (empty($resep)) {
                return "Produk '{$item['nama_produk']}' tidak memiliki resep yang valid.";
            }
            foreach ($resep as $bahan) {
                $jumlahTotalBahan = $bahan['jumlah'] * $item['jumlah'];
                if (isset($bahanDibutuhkan[$bahan['id_bahan']])) {
                    $bahanDibutuhkan[$bahan['id_bahan']] += $jumlahTotalBahan;
                } else {
                    $bahanDibutuhkan[$bahan['id_bahan']] = $jumlahTotalBahan;
                }
            }
        }
        foreach ($bahanDibutuhkan as $id_bahan => $jumlahDibutuhkan) {
            $stok = $stokModel->find($id_bahan);
            if (!$stok || $stok['jumlah'] < $jumlahDibutuhkan) {
                $namaBahan = $stok ? $stok['nama_bahan'] : "ID Bahan: {$id_bahan}";
                return "Stok bahan '{$namaBahan}' tidak mencukupi untuk memenuhi semua pesanan di keranjang Anda.";
            }
        }
        return true;
    }

    public function tambahKeKeranjang()
    {
        $id_produk = $this->request->getPost('id_produk');
        $jumlahDitambahkan = (int) $this->request->getPost('jumlah');
        if ($jumlahDitambahkan < 1) {
            return redirect()->back()->with('error', 'Jumlah tidak valid.');
        }

        $keranjang = session()->get('keranjang') ?? [];
        $keranjangHipotetis = $keranjang;

        if (isset($keranjangHipotetis[$id_produk])) {
            $keranjangHipotetis[$id_produk]['jumlah'] += $jumlahDitambahkan;
        } else {
            $produkModel = new ProdukModel();
            $produk = $produkModel->find($id_produk);
            if (!$produk) {
                return redirect()->back()->with('error', 'Produk tidak ditemukan.');
            }
            $keranjangHipotetis[$id_produk] = [
                'id_produk' => $produk['id_produk'],
                'nama_produk' => $produk['nama_produk'],
                'ukuran' => $produk['ukuran'],
                'harga' => $produk['harga'],
                'jumlah' => $jumlahDitambahkan,
            ];
        }

        $validasi = $this->_validasiStokKeranjang($keranjangHipotetis);
        if ($validasi !== true) {
            return redirect()->back()->with('error', $validasi);
        }

        session()->set('keranjang', $keranjangHipotetis);
        return redirect()->to('/pemesanan')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function hapusItemKeranjang($id_produk)
    {
        $keranjang = session()->get('keranjang') ?? [];
        if (isset($keranjang[$id_produk])) {
            unset($keranjang[$id_produk]);
        }
        session()->set('keranjang', $keranjang);
        return redirect()->to('/pemesanan/keranjang')->with('success', 'Item berhasil dihapus.');
    }

    public function tampilKeranjang()
    {
        $promoModel = new PromoModel();
        $data = [
            'title'     => 'Keranjang Belanja',
            'keranjang' => session()->get('keranjang') ?? [],
            'promoAktif' => $promoModel->where('status', 'aktif')->first() // Kirim promo aktif
        ];
        return view('keranjang', $data);
    }

    public function checkout()
    {
        // 1. Validasi Input Form Checkout
        $validation = \Config\Services::validation();
        $validation->setRules([
            'alamat'         => 'required|string|max_length[255]',
            'provinsi_nama'  => 'required|string',
            'kota_nama'      => 'required|string',
            'ongkir_biaya'   => 'required|numeric',
            'ongkir_layanan' => 'required|string',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('error', $validation->listErrors());
        }

        // 2. Ambil data dari session dan POST
        $keranjang = session()->get('keranjang');
        $user = session('user');

        if (empty($keranjang) || !$user) {
            return redirect()->to('/pemesanan')->with('error', 'Keranjang kosong atau Anda belum login.');
        }

        // 3. Validasi stok sekali lagi sebelum proses
        $validasiStok = $this->_validasiStokKeranjang($keranjang);
        if ($validasiStok !== true) {
            return redirect()->to('/pemesanan/keranjang')->with('error', $validasiStok);
        }

        // --- AWAL PERUBAHAN LOGIKA PROMO DINAMIS ---
        $promoModel = new PromoModel();
        $promoAktif = $promoModel->where('status', 'aktif')->first();

        $totalHargaProduk = 0;
        $totalKuantitas = 0;
        foreach ($keranjang as $item) {
            $totalHargaProduk += $item['harga'] * $item['jumlah'];
            $totalKuantitas += $item['jumlah'];
        }

        $jumlahDiskon = 0;
        $idPromoDigunakan = null;

        if ($promoAktif) {
            $idPromoDigunakan = $promoAktif['id_promo'];

            // Cek tipe promo dan hitung diskon
            switch ($promoAktif['tipe_promo']) {
                case 'kuantitas_kelipatan':
                    if ($totalKuantitas >= $promoAktif['syarat_kuantitas']) {
                        $kelipatan = floor($totalKuantitas / $promoAktif['syarat_kuantitas']);
                        $jumlahDiskon = $kelipatan * $promoAktif['nilai_diskon'];
                    }
                    break;

                case 'potongan_langsung':
                    $jumlahDiskon = $promoAktif['nilai_diskon'];
                    break;
            }
        }

        $ongkirBiaya = (float) $this->request->getPost('ongkir_biaya');
        $ongkirLayanan = $this->request->getPost('ongkir_layanan');
        $totalHargaKeseluruhan = ($totalHargaProduk - $jumlahDiskon) + $ongkirBiaya;
        // --- AKHIR PERUBAHAN LOGIKA PROMO DINAMIS ---

        $pemesananModel = new PemesananModel();
        $dataPemesanan = [
            'order_id'       => 'OTELA-' . time() . '-' . $user['id_user'],
            'id_user'        => $user['id_user'],
            'tanggal'        => date('Y-m-d H:i:s'),
            'diskon'         => $jumlahDiskon,
            'id_promo'       => $idPromoDigunakan,
            'total_harga'    => $totalHargaKeseluruhan,
            'status'         => 'pending',
            'email_status'   => 'menunggu',
            'Alamat'         => $this->request->getPost('alamat'),
            'Provinsi'       => $this->request->getPost('provinsi_nama'),
            'Kota'           => $this->request->getPost('kota_nama'),
            'ongkir_biaya'   => $ongkirBiaya,
            'ongkir_layanan' => $ongkirLayanan,
        ];

        $id_pemesanan = $pemesananModel->insert($dataPemesanan, true);

        $detailPemesananModel = new DetailPemesananModel();
        foreach ($keranjang as $item) {
            $dataDetail = [
                'id_pemesanan'     => $id_pemesanan,
                'id_produk'        => $item['id_produk'],
                'jumlah'           => $item['jumlah'],
                'harga_saat_pesan' => $item['harga'],
                'subtotal'         => $item['harga'] * $item['jumlah'],
            ];
            $detailPemesananModel->insert($dataDetail);
        }

        session()->remove('keranjang');
        return redirect()->to('/payment/bayar/' . $id_pemesanan);
    }

    // ... (Sisa fungsi tidak diubah) ...
    public function getMaxJumlah($id_produk)
    {
        $stokVirtual = $this->_getVirtualIngredientStock();
        return $this->response->setJSON(['max' => $this->_hitungStokTersedia($id_produk, $stokVirtual)]);
    }

    public function ubahStatus($id, $status)
    {
        $status = str_replace('-', ' ', $status);
        $pemesananModel = new PemesananModel();

        if ($status === 'selesai') {
            return redirect()->to(base_url('beranda'))->with('error', 'Aksi tidak valid.');
        }

        $pemesananModel->update($id, ['status' => $status]);

        if ($status === 'siap kirim') {
            $pesanan = $pemesananModel->find($id);
            if ($pesanan) {
                $this->_sendReadyToShipEmail($pesanan);
            }
        }

        return redirect()->to(base_url('beranda'))->with('success', 'Status pesanan berhasil diubah.');
    }

    public function selesaikanPesanan()
    {
        if (strtolower($this->request->getMethod()) !== 'post') {
            $method = strtoupper($this->request->getMethod());
            return redirect()->to(base_url('beranda'))->with('error', "Akses tidak valid. Metode request yang diterima: {$method}, seharusnya POST.");
        }

        $id_pemesanan = $this->request->getPost('id_pemesanan');
        $nomor_resi = $this->request->getPost('nomor_resi');
        $kurir = $this->request->getPost('kurir');

        if (empty($id_pemesanan) || empty($nomor_resi) || empty($kurir)) {
            return redirect()->back()->with('error', 'Kurir dan Nomor resi tidak boleh kosong.');
        }

        $pemesananModel = new PemesananModel();
        $detailPemesananModel = new DetailPemesananModel();
        $riwayatTransaksiModel = new RiwayatTransaksiModel();
        $produkModel = new ProdukModel();
        $db = \Config\Database::connect();

        try {
            $db->transBegin();

            $pesanan = $pemesananModel->find($id_pemesanan);
            if (!$pesanan) {
                throw new \Exception('Pesanan tidak ditemukan.');
            }

            $detailPesanan = $detailPemesananModel->where('id_pemesanan', $id_pemesanan)->findAll();
            if (empty($detailPesanan)) {
                throw new \Exception('Detail pesanan tidak ditemukan.');
            }

            $itemsForJson = [];
            foreach ($detailPesanan as $item) {
                $produk = $produkModel->find($item['id_produk']);
                $itemsForJson[] = [
                    'nama_produk' => ($produk ? $produk['nama_produk'] : 'Produk Dihapus') . ' (' . ($produk ? $produk['ukuran'] : 'N/A') . 'gr)',
                    'jumlah' => $item['jumlah'],
                    'harga' => $item['harga_saat_pesan'],
                    'subtotal' => $item['subtotal']
                ];
            }

            $dataRiwayat = [
                'id_pemesanan_asli' => $pesanan['id_pemesanan'],
                'order_id'          => $pesanan['order_id'],
                'id_user'           => $pesanan['id_user'],
                'tanggal_pesan'     => $pesanan['tanggal'],
                'detail_items'      => json_encode($itemsForJson),
                'total_harga'       => $pesanan['total_harga'],
                'resi'              => $nomor_resi,
                'kurir'             => $kurir,
                'tanggal_selesai'   => date('Y-m-d H:i:s')
            ];

            $riwayatTransaksiModel->insert($dataRiwayat);
            $detailPemesananModel->where('id_pemesanan', $id_pemesanan)->delete();
            $pemesananModel->delete($id_pemesanan);

            if ($db->transStatus() === false) {
                $db->transRollback();
                return redirect()->to(base_url('beranda'))->with('error', 'Gagal memindahkan pesanan. Transaksi dibatalkan.');
            } else {
                $db->transCommit();
                $this->_sendShippingEmail($dataRiwayat);
                return redirect()->to(base_url('beranda'))->with('success', 'Pesanan telah selesai dan dipindahkan ke riwayat.');
            }
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error saat memindahkan pesanan: ' . $e->getMessage());
            return redirect()->to(base_url('beranda'))->with('error', 'Terjadi kesalahan sistem.');
        }
    }

    private function _sendShippingEmail(array $dataRiwayat)
    {
        $userModel = new UserModel();
        $user = $userModel->find($dataRiwayat['id_user']);
        if (!$user) return;

        $apiKey = getenv('BREVO_API_KEY');
        if (empty($apiKey)) {
            log_message('error', 'Brevo API Key not set.');
            return;
        }

        $htmlContent = "<html><body><h1>Pesanan Anda Telah Dikirim!</h1><p>Halo <strong>" . esc($user['nama_lengkap']) . "</strong>,</p><p>Pesanan Anda dengan nomor <strong>" . esc($dataRiwayat['order_id']) . "</strong> telah kami serahkan ke pihak ekspedisi.</p><p>Anda dapat melacak posisi paket Anda menggunakan nomor resi berikut:</p><h2 style='text-align:center; background-color:#f0f0f0; padding:10px; border-radius:5px;'>" . esc($dataRiwayat['resi']) . "</h2><p>Terima kasih telah berbelanja di Otela!</p><p>Salam hangat,<br><strong>Tim Otela</strong></p></body></html>";

        $data = [
            "sender" => ["name" => "Toko Otela", "email" => "renaamelianti8@gmail.com"],
            "to" => [["email" => $user['email'], "name" => $user['nama_lengkap']]],
            "subject" => "Pesanan Dikirim! Lacak Paket Anda (Order: " . $dataRiwayat['order_id'] . ")",
            "htmlContent" => $htmlContent,
        ];

        try {
            $client = \Config\Services::curlrequest();
            $client->request('POST', "https://api.brevo.com/v3/smtp/email", ['headers' => ["accept" => "application/json", "api-key" => $apiKey, "content-type" => "application/json"], 'json' => $data, 'timeout' => 5]);
        } catch (\Exception $e) {
            log_message('error', '[BREVO] Shipping email failed: ' . $e->getMessage());
        }
    }

    private function _sendReadyToShipEmail(array $pesanan)
    {
        $userModel = new UserModel();
        $pemesananModel = new PemesananModel();
        $user = $userModel->find($pesanan['id_user']);
        if (!$user) {
            log_message('error', 'User not found for ready to ship email. Pesanan ID: ' . $pesanan['id_pemesanan']);
            return;
        }

        $apiKey = getenv('BREVO_API_KEY');
        if (empty($apiKey)) {
            log_message('error', 'Brevo API Key is not set in .env file.');
            $pemesananModel->update($pesanan['id_pemesanan'], ['email_status' => 'Gagal Kirim (Siap Kirim): No API Key']);
            return;
        }

        $url = "https://api.brevo.com/v3/smtp/email";
        $headers = ["accept" => "application/json", "api-key" => $apiKey, "content-type" => "application/json"];
        $htmlContent = "<html><body><h1>Pesanan Anda Siap Dikirim!</h1><p>Halo <strong>" . esc($user['nama_lengkap']) . "</strong>,</p><p>Kabar baik! Pesanan Anda dengan nomor <strong>" . esc($pesanan['order_id']) . "</strong> telah selesai kami produksi dan siap untuk dikirim.</p><p>Kami akan segera menyerahkannya ke pihak ekspedisi. Mohon tunggu informasi nomor resi pengiriman selanjutnya.</p><p>Terima kasih telah berbelanja di Otela!</p><p>Salam hangat,<br><strong>Tim Otela</strong></p></body></html>";

        $data = [
            "sender" => ["name" => "Toko Otela", "email" => "renaamelianti8@gmail.com"],
            "to" => [["email" => $user['email'], "name" => $user['nama_lengkap']]],
            "subject" => "Pesanan Siap Dikirim! (Order: " . $pesanan['order_id'] . ")",
            "htmlContent" => $htmlContent,
        ];

        try {
            $client = \Config\Services::curlrequest();
            $client->request('POST', $url, ['headers' => $headers, 'json' => $data, 'timeout' => 5]);
        } catch (\Exception $e) {
            log_message('error', '[BREVO] Ready to ship email failed: ' . $e->getMessage());
        }
    }

    public function getProvinces()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403, 'Forbidden');
        }

        $data = [];
        try {
            $client = \Config\Services::curlrequest();
            $response = $client->request('GET', 'https://rajaongkir.komerce.id/api/v1/destination/province', [
                'headers' => [
                    'key' => $this->komerceApiKey,
                ],
                'timeout' => 20,
                'verify' => false
            ]);

            $result = json_decode($response->getBody(), true);
            log_message('debug', 'Komerce API Provinces Response: ' . json_encode($result));

            if (isset($result['meta']['code']) && $result['meta']['code'] == 200 && $result['meta']['status'] === 'success' && isset($result['data'])) {
                $data = ['status' => 200, 'data' => $result['data']];
            } else {
                $apiMessage = $result['meta']['message'] ?? 'Respons API provinsi tidak sesuai format atau data kosong.';
                $data = ['status' => ($result['meta']['code'] ?? 404), 'message' => $apiMessage];
            }
        } catch (\Exception $e) {
            log_message('error', '[Komerce API] getProvinces error: ' . $e->getMessage());
            $data = ['status' => 500, 'message' => 'Tidak dapat terhubung ke server Komerce API untuk provinsi: ' . $e->getMessage()];
        }

        return $this->response->setJSON($data);
    }

    public function getCities()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403, 'Forbidden');
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'province_id' => 'required|numeric',
        ]);
        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON(['status' => 400, 'message' => 'ID Provinsi tidak valid.']);
        }

        $provinceId = $this->request->getPost('province_id');
        $data = [];
        try {
            $client = \Config\Services::curlrequest();
            $response = $client->request('GET', "https://rajaongkir.komerce.id/api/v1/destination/city/{$provinceId}", [
                'headers' => [
                    'key' => $this->komerceApiKey,
                ],
                'timeout' => 20,
                'verify' => false
            ]);

            $result = json_decode($response->getBody(), true);
            log_message('debug', 'Komerce API Cities Response: ' . json_encode($result));

            if (isset($result['meta']['code']) && $result['meta']['code'] == 200 && $result['meta']['status'] === 'success' && isset($result['data'])) {
                $data = ['status' => 200, 'data' => $result['data']];
            } else {
                $apiMessage = $result['meta']['message'] ?? 'Respons API kota tidak sesuai format atau data kosong.';
                $data = ['status' => ($result['meta']['code'] ?? 404), 'message' => $apiMessage];
            }
        } catch (\Exception $e) {
            log_message('error', '[Komerce API] getCities error: ' . $e->getMessage());
            $data = ['status' => 500, 'message' => 'Tidak dapat terhubung ke server Komerce API untuk kota: ' . $e->getMessage()];
        }

        return $this->response->setJSON($data);
    }

    public function cekOngkir()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403, 'Forbidden');
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'destination_id' => 'required|numeric',
            'destination_name' => 'required|string',
            'weight'           => 'required|numeric',
            'courier'          => 'required|string'
        ]);
        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON(['status' => 400, 'message' => 'Input tidak valid.']);
        }

        $destinationId = $this->request->getPost('destination_id');
        $destinationName = $this->request->getPost('destination_name');
        $originId = $this->originCityId;
        $originName = $this->originCityName;
        $weightInGrams = $this->request->getPost('weight');
        $courier = $this->request->getPost('courier');
        $weightInKg = ceil($weightInGrams / 1000);
        if ($weightInKg < 1) {
            $weightInKg = 1;
        }

        $data = [];
        try {
            $client = \Config\Services::curlrequest();
            $response = $client->request('POST', 'https://rajaongkir.komerce.id/api/v1/calculate/district/domestic-cost', [
                'headers' => [
                    'key' => $this->komerceApiKey,
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => [
                    'origin'      => $originId,
                    'destination' => $destinationId,
                    'weight'      => $weightInKg,
                    'courier'     => $courier
                ],
                'timeout' => 20,
                'verify' => false
            ]);

            $result = json_decode($response->getBody(), true);
            log_message('debug', 'Komerce API Cost Response: ' . json_encode($result));

            if (isset($result['meta']['code']) && $result['meta']['code'] == 200 && $result['meta']['status'] === 'success' && isset($result['data']) && is_array($result['data'])) {
                $data = ['status' => 200, 'data' => $result['data']];
            } else {
                $apiMessage = $result['meta']['message'] ?? 'Respons API ongkir tidak sesuai format atau data biaya kosong.';
                $data = ['status' => ($result['meta']['code'] ?? 404), 'message' => $apiMessage];
            }
        } catch (\Exception $e) {
            log_message('error', '[Komerce API] Cek ongkir error: ' . $e->getMessage());
            $data = ['status' => 500, 'message' => 'Tidak dapat terhubung ke server ongkir.'];
        }

        return $this->response->setJSON($data);
    }
}
