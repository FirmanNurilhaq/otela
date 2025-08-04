<?php

namespace App\Controllers;

// Pastikan BaseController di-use
use CodeIgniter\Controller;

class CekResi extends BaseController
{
    /**
     * Menampilkan halaman cek resi.
     * SEKARANG: Bisa menerima nomor resi (awb) dari parameter GET.
     */
    public function index()
    {
        // Ambil nomor resi (awb) dan kurir dari parameter GET
        $awb = $this->request->getGet('awb');
        $kurir = $this->request->getGet('kurir');

        // Kirim data ke view untuk diisi otomatis
        $data['awb_prefill'] = $awb ? esc($awb, 'html') : '';
        $data['kurir_prefill'] = $kurir ? esc($kurir, 'html') : '';

        $data['hasil'] = null;
        $data['error_api'] = null;

        return view('cek_resi', $data);
    }

    public function lacakPaket()
    {
        $courier = $this->request->getPost('courier');
        $awb = $this->request->getPost('awb');

        if (empty($courier) || empty($awb)) {
            return redirect()->to('/cek-resi')->with('error', 'Kurir dan Nomor Resi harus diisi!');
        }

        $apiKey = getenv('BINDERBYTE_API_KEY');
        if (!$apiKey) {
            return redirect()->to('/cek-resi')->with('error', 'API Key tidak dikonfigurasi!');
        }

        $client = \Config\Services::curlrequest();

        $apiUrl = 'https://api.binderbyte.com/v1/track';
        $data = [];
        // Kirim kembali data input agar form tidak kosong
        $data['awb_prefill'] = esc($awb, 'html');
        $data['kurir_prefill'] = esc($courier, 'html');

        try {
            $response = $client->request('GET', $apiUrl, [
                'query' => ['api_key' => $apiKey, 'courier' => $courier, 'awb' => $awb],
                'timeout' => 30
            ]);
            $body = $response->getBody();
            $result = json_decode($body, true);

            if (isset($result['status']) && $result['status'] == 200) {
                $data['hasil'] = $result['data'] ?? null;
            } else {
                $data['error_api'] = $result['message'] ?? 'Nomor resi tidak ditemukan atau terjadi kesalahan.';
            }
        } catch (\Exception $e) {
            $data['error_api'] = 'Gagal melacak paket. Pastikan nomor resi dan pilihan kurir sudah benar, lalu coba lagi.';
        }

        return view('cek_resi', $data);
    }
}