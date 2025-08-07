<?php

namespace App\Controllers;

use App\Models\RiwayatTransaksiModel;
use App\Models\UlasanModel; // 1. Tambahkan UlasanModel

class Histori extends BaseController
{
    /**
     * Menampilkan halaman riwayat pesanan untuk pelanggan yang sedang login.
     */
    public function index()
    {
        $session = session();

        // Pastikan pengguna sudah login dan adalah pelanggan
        if (!$session->has('user') || $session->get('user')['role'] !== 'pelanggan') {
            return redirect()->to('/login');
        }

        $user = $session->get('user');
        $riwayatModel = new RiwayatTransaksiModel();
        $ulasanModel = new UlasanModel(); // 2. Buat instance UlasanModel

        // Ambil semua data riwayat milik pengguna yang login
        $histori = $riwayatModel
            ->where('id_user', $user['id_user'])
            ->orderBy('tanggal_selesai', 'DESC')
            ->findAll();

        // --- AWAL LOGIKA PENGECEKAN ULASAN ---
        $ulasanDiberikan = [];
        if (!empty($histori)) {
            // Ambil semua ID riwayat dari hasil query di atas
            $riwayatIds = array_column($histori, 'id_riwayat');
            
            // Cek di tabel ulasan, transaksi mana saja yang sudah punya ulasan
            $ulasan = $ulasanModel->whereIn('id_riwayat', $riwayatIds)->findAll();
            
            // Buat array asosiatif agar mudah dicek di view: [id_riwayat => true]
            $ulasanDiberikan = array_column($ulasan, null, 'id_riwayat');
        }
        // --- AKHIR LOGIKA PENGECEKAN ULASAN ---

        $data = [
            'title' => 'Riwayat Transaksi',
            'histori' => $histori,
            'ulasanDiberikan' => $ulasanDiberikan // 3. Kirim data ulasan ke view
        ];

        return view('histori', $data);
    }
}
