<?php

namespace App\Controllers;

use App\Models\RiwayatTransaksiModel;

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

        // Ambil semua data riwayat milik pengguna yang login, diurutkan dari yang terbaru
        $data['histori'] = $riwayatModel
            ->where('id_user', $user['id_user'])
            ->orderBy('tanggal_selesai', 'DESC')
            ->findAll();

        return view('histori', $data);
    }
}
