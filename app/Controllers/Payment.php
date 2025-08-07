<?php

namespace App\Controllers;

// Tambahkan model yang relevan
use App\Models\PemesananModel;
use App\Models\DetailPemesananModel;
use App\Models\ProdukModel;
use App\Models\UserModel;
use App\Models\ResepProdukModel;
use App\Models\StokModel;

class Payment extends BaseController
{
    public function __construct()
    {
        // Konfigurasi Midtrans
        \Midtrans\Config::$serverKey = getenv('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = (bool)getenv('MIDTRANS_PRODUCTION');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
    }

    public function bayar($id_pemesanan)
    {
        $pemesananModel = new PemesananModel();
        $detailPemesananModel = new DetailPemesananModel();
        $userModel = new UserModel();
        $produkModel = new ProdukModel();

        // 1. Ambil data pesanan utama (master)
        $pemesanan = $pemesananModel->find($id_pemesanan);
        if (!$pemesanan || $pemesanan['status'] !== 'pending') {
            return redirect()->to('/')->with('error', 'Transaksi tidak ditemukan atau sudah diproses.');
        }

        // 2. Ambil semua detail item untuk pesanan ini
        $detailItems = $detailPemesananModel->where('id_pemesanan', $id_pemesanan)->findAll();
        if (empty($detailItems)) {
            return redirect()->to('/')->with('error', 'Detail item untuk transaksi ini tidak ditemukan.');
        }

        // 3. Ambil data user
        $user = $userModel->find($pemesanan['id_user']);

        // 4. Siapkan 'item_details' untuk Midtrans dari semua produk di keranjang
        $item_details_midtrans = [];
        foreach ($detailItems as $item) {
            $produk = $produkModel->find($item['id_produk']);
            $item_details_midtrans[] = [
                'id'       => $item['id_produk'],
                'price'    => $item['harga_saat_pesan'],
                'quantity' => $item['jumlah'],
                'name'     => $produk ? ($produk['nama_produk'] . ' (' . $produk['ukuran'] . 'gr)') : 'Produk Tidak Ditemukan',
            ];
        }

        // 5. Tambahkan ongkos kirim sebagai item terpisah
        if (isset($pemesanan['ongkir_biaya']) && $pemesanan['ongkir_biaya'] > 0) {
            $item_details_midtrans[] = [
                'id'       => 'ONGKIR-' . $pemesanan['order_id'],
                'price'    => $pemesanan['ongkir_biaya'],
                'quantity' => 1,
                'name'     => 'Ongkos Kirim (' . esc($pemesanan['ongkir_layanan']) . ')',
            ];
        }

        // 6. Tambahkan DISKON sebagai item dengan harga negatif
        if (isset($pemesanan['diskon']) && $pemesanan['diskon'] > 0) {
            $item_details_midtrans[] = [
                'id'       => 'PROMO-5K',
                'price'    => -$pemesanan['diskon'], // Harga dibuat negatif
                'quantity' => 1,
                'name'     => 'Diskon Promo',
            ];
        }

        // 7. Siapkan parameter untuk Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $pemesanan['order_id'],
                'gross_amount' => $pemesanan['total_harga'], // gross_amount sudah final
            ],
            'item_details' => $item_details_midtrans,
            'customer_details' => [
                'first_name' => $user['nama_lengkap'],
                'email'      => $user['email'],
                'phone'      => $user['no_telp'],
                'shipping_address' => [
                    'first_name' => $user['nama_lengkap'],
                    'address'    => $pemesanan['Alamat'],
                    'city'       => $pemesanan['Kota'],
                    'postal_code' => '', // Bisa ditambahkan jika ada
                    'phone'      => $user['no_telp'],
                    'country_code' => 'IDN'
                ]
            ],
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        $data = [
            'snapToken' => $snapToken,
            'pemesanan' => $pemesanan
        ];

        return view('payment/pay', $data);
    }

    public function notifikasi()
    {
        $notif = new \Midtrans\Notification();

        $transactionStatus = $notif->transaction_status;
        $orderId = $notif->order_id;
        $fraudStatus = $notif->fraud_status;

        log_message('info', "[Midtrans Notif] Received notification for Order ID: {$orderId} with status: {$transactionStatus}");

        $pemesananModel = new PemesananModel();
        $pemesanan = $pemesananModel->where('order_id', $orderId)->first();

        if (!$pemesanan || $pemesanan['status'] !== 'pending') {
            log_message('warning', "[Midtrans Notif] Order ID {$orderId} not found or status is not 'pending'. Current status: " . ($pemesanan['status'] ?? 'N/A'));
            return; 
        }

        if ($transactionStatus == 'settlement' && $fraudStatus == 'accept') {
            log_message('info', "[Midtrans Notif] Payment success for Order ID: {$orderId}. Processing stock reduction.");

            // 1. Ubah status pesanan menjadi 'produksi'
            $pemesananModel->update($pemesanan['id_pemesanan'], ['status' => 'produksi']);

            // 2. Siapkan semua model yang dibutuhkan
            $detailPemesananModel = new DetailPemesananModel();
            $resepModel = new ResepProdukModel();
            $stokModel = new StokModel();
            $produkModel = new ProdukModel(); // Tambahkan model produk

            $detailItems = $detailPemesananModel->where('id_pemesanan', $pemesanan['id_pemesanan'])->findAll();

            // Loop untuk kurangi stok bahan
            foreach ($detailItems as $item) {
                $resep = $resepModel->where('id_produk', $item['id_produk'])->findAll();
                foreach ($resep as $bahan) {
                    $stokSaatIni = $stokModel->find($bahan['id_bahan']);
                    if (!$stokSaatIni) {
                        continue;
                    }
                    $jumlahLama = (int)$stokSaatIni['jumlah'];
                    $jumlahKurangi = (int)($bahan['jumlah'] * $item['jumlah']);
                    $jumlahBaru = $jumlahLama - $jumlahKurangi;

                    $statusBaru = ($jumlahBaru <= 2000) ? 'Hampir Habis' : 'Tersedia';

                    $stokModel->update($bahan['id_bahan'], [
                        'jumlah' => $jumlahBaru,
                        'status' => $statusBaru
                    ]);

                    log_message('debug', "[Stock Check] For Order ID {$orderId}, Bahan '{$stokSaatIni['nama_bahan']}': Jumlah Lama = {$jumlahLama}, Jumlah Baru = {$jumlahBaru}, Status Baru = {$statusBaru}.");

                    if ($jumlahBaru <= 2000 && $jumlahLama > 2000) {
                        log_message('critical', "[Stock Alert] Condition met for '{$stokSaatIni['nama_bahan']}'. Attempting to send email notification.");
                        $this->_kirimNotifikasiStokHabis($stokSaatIni['nama_bahan'], $jumlahBaru);
                    }
                }
            }

            // --- AWAL PERUBAHAN: Loop untuk update jumlah terjual ---
            foreach ($detailItems as $item) {
                // Menggunakan query builder untuk increment nilai secara aman
                // Parameter ketiga (false) mencegah CodeIgniter meng-escape query, sehingga operasi matematika bisa berjalan
                $produkModel->where('id_produk', $item['id_produk'])
                            ->set('jumlah_terjual', 'jumlah_terjual + ' . (int)$item['jumlah'], false)
                            ->update();
            }
            // --- AKHIR PERUBAHAN ---

            // 3. Kirim email konfirmasi ke pelanggan
            $this->_sendProductionEmail($pemesanan);

        } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
            $pemesananModel->update($pemesanan['id_pemesanan'], ['status' => 'dibatalkan']);
        }
    }

    private function _kirimNotifikasiStokHabis(string $namaBahan, int $sisaStok)
    {
        $apiKey = getenv('BREVO_API_KEY');
        if (empty($apiKey)) {
            log_message('error', '[BREVO] Stock Notification Failed: API Key not set.');
            return;
        }

        $penerimaEmail = 'renaamelianti8@gmail.com';
        $penerimaNama = 'Admin Otela';

        $htmlContent = "<html><body>
            <h1>Peringatan Stok Bahan Hampir Habis!</h1>
            <p>Halo <strong>{$penerimaNama}</strong>,</p>
            <p>Sistem mendeteksi bahwa stok untuk bahan berikut ini telah mencapai batas minimum karena ada pesanan baru dan perlu segera di-restock:</p>
            <ul style='list-style-type: none; padding: 0;'>
                <li style='margin-bottom: 10px;'><strong>Nama Bahan:</strong> " . esc($namaBahan) . "</li>
                <li><strong>Sisa Stok Saat Ini:</strong> {$sisaStok}</li>
            </ul>
            <p>Mohon untuk segera melakukan pemesanan ulang.</p>
            <p>Terima kasih,<br><strong>Sistem Notifikasi Otela</strong></p>
        </body></html>";

        $data = [
            "sender"      => ["name" => "Sistem Otela", "email" => "renaamelianti8@gmail.com"],
            "to"          => [["email" => $penerimaEmail, "name" => $penerimaNama]],
            "subject"     => "Peringatan Stok Kritis: " . esc($namaBahan),
            "htmlContent" => $htmlContent,
        ];

        try {
            $client = \Config\Services::curlrequest();
            $client->request('POST', "https://api.brevo.com/v3/smtp/email", [
                'headers' => ["accept" => "application/json", "api-key" => $apiKey, "content-type" => "application/json"],
                'json' => $data,
                'timeout' => 5
            ]);
            log_message('info', "[Stock Alert] Email notification for '{$namaBahan}' sent successfully to Brevo API.");
        } catch (\Exception $e) {
            log_message('error', '[BREVO] Stock notification email failed: ' . $e->getMessage());
        }
    }

    private function _sendProductionEmail($pemesanan)
    {
        $pemesananModel = new PemesananModel();
        $userModel = new UserModel();

        $apiKey = getenv('BREVO_API_KEY');

        if (empty($apiKey)) {
            log_message('error', 'Brevo API Key is not set in .env file.');
            $pemesananModel->update($pemesanan['id_pemesanan'], ['email_status' => 'Gagal: API Key tidak ada']);
            return;
        }

        $user = $userModel->find($pemesanan['id_user']);
        if (!$user) {
            log_message('error', 'User not found for email sending.');
            return;
        }

        $url = "https://api.brevo.com/v3/smtp/email";
        $headers = [
            "accept" => "application/json",
            "api-key" => $apiKey,
            "content-type" => "application/json",
        ];

        $htmlContent = "<html><body><h1>Terima Kasih Telah Berbelanja!</h1><p>Halo <strong>" . esc($user['nama_lengkap']) . "</strong>,</p><p>Pembayaran untuk pesanan Anda dengan nomor <strong>" . esc($pemesanan['order_id']) . "</strong> telah kami terima dan sedang kami siapkan.</p><p>Salam hangat,<br><strong>Tim Otela</strong></p></body></html>";

        $data = [
            "sender" => ["name" => "Toko Otela", "email" => "renaamelianti8@gmail.com"],
            "to" => [["email" => $user['email'], "name" => $user['nama_lengkap']]],
            "subject" => "Pesanan Diterima dan Sedang Diproduksi! (Order: " . $pemesanan['order_id'] . ")",
            "htmlContent" => $htmlContent,
        ];

        try {
            $client = \Config\Services::curlrequest();
            $response = $client->request('POST', $url, ['headers' => $headers, 'json' => $data]);

            if ($response->getStatusCode() == 201) {
                $pemesananModel->update($pemesanan['id_pemesanan'], ['email_status' => 'Email Berhasil Terkirim']);
            } else {
                $pemesananModel->update($pemesanan['id_pemesanan'], ['email_status' => 'Gagal: ' . $response->getReasonPhrase()]);
            }
        } catch (\Exception $e) {
            $pemesananModel->update($pemesanan['id_pemesanan'], ['email_status' => 'Gagal: ' . $e->getMessage()]);
            log_message('error', '[BREVO] Email sending failed with exception: ' . $e->getMessage());
        }
    }
}
