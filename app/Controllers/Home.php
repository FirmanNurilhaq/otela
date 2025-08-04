<?php

namespace App\Controllers;

use Config\Database;
use App\Models\PromoModel; // 1. Tambahkan model promo

class Home extends BaseController
{
    public function index()
    {
        $session = session();

        if (!$session->has('user')) {
            return redirect()->to('/login');
        }

        $user = $session->get('user');
        $data = [];
        $db = Database::connect();

        // 2. Siapkan model promo
        $promoModel = new PromoModel();

        if ($user['role'] === 'pemilik') {
            // Logika untuk Pemilik (Admin) - Tidak diubah
            $getOrdersByStatus = function ($status) use ($db) {
                $pemesanan = $db->table('pemesanan')
                    ->select('pemesanan.*, user.nama_lengkap')
                    ->join('user', 'user.id_user = pemesanan.id_user', 'left')
                    ->where('pemesanan.status', $status)
                    ->orderBy('pemesanan.tanggal', 'asc')
                    ->get()
                    ->getResultArray();

                foreach ($pemesanan as $key => $order) {
                    $details = $db->table('detail_pemesanan')
                        ->select('detail_pemesanan.*, produk.nama_produk, produk.ukuran')
                        ->join('produk', 'produk.id_produk = detail_pemesanan.id_produk', 'left')
                        ->where('detail_pemesanan.id_pemesanan', $order['id_pemesanan'])
                        ->get()
                        ->getResultArray();

                    $pemesanan[$key]['items'] = $details;
                }
                return $pemesanan;
            };

            $data['produksi'] = $getOrdersByStatus('produksi');
            $data['siap_kirim'] = $getOrdersByStatus('siap kirim');

            // 3. Ambil semua data promo untuk tabel CRUD di beranda pemilik
            $data['promos'] = $promoModel->orderBy('status', 'ASC')->findAll();
        } elseif ($user['role'] === 'pelanggan') {
            // LOGIKA UNTUK PELANGGAN
            $pemesanan = $db->table('pemesanan')
                ->select('pemesanan.*')
                ->where('pemesanan.id_user', $user['id_user'])
                ->whereIn('pemesanan.status', ['produksi', 'siap kirim'])
                ->orderBy('pemesanan.tanggal', 'DESC')
                ->get()
                ->getResultArray();

            // Untuk setiap pesanan, ambil detail item produknya
            foreach ($pemesanan as $key => $order) {
                $details = $db->table('detail_pemesanan')
                    ->select('detail_pemesanan.*, produk.nama_produk, produk.ukuran')
                    ->join('produk', 'produk.id_produk = detail_pemesanan.id_produk', 'left')
                    ->where('detail_pemesanan.id_pemesanan', $order['id_pemesanan'])
                    ->get()
                    ->getResultArray();

                $pemesanan[$key]['items'] = $details;
            }
            $data['pesanan_berjalan'] = $pemesanan;

            // 4. Ambil data promo yang aktif untuk ditampilkan di modal promo pelanggan
            $data['promoAktif'] = $promoModel->where('status', 'aktif')->first();
        }

        return view('beranda', $data);
    }
}
