<?php

namespace App\Controllers;

use App\Models\UlasanModel;
use App\Models\RiwayatTransaksiModel;

class Ulasan extends BaseController
{
    protected $ulasanModel;
    protected $riwayatModel;

    public function __construct()
    {
        $this->ulasanModel = new UlasanModel();
        $this->riwayatModel = new RiwayatTransaksiModel();
    }

    // Menampilkan halaman daftar ulasan (hanya untuk pemilik)
    public function index()
    {
        // Pastikan ada user yang login
        if (!session()->has('user')) {
            return redirect()->to('/login')->with('error', 'Anda harus login untuk melihat halaman ini.');
        }

        $role = session('user')['role'];
        $title = '';

        // Membangun query dasar
        $ulasanQuery = $this->ulasanModel
            ->select('ulasan.*, user.nama_lengkap, riwayat_transaksi.order_id')
            ->join('user', 'user.id_user = ulasan.id_user', 'left')
            ->join('riwayat_transaksi', 'riwayat_transaksi.id_riwayat = ulasan.id_riwayat', 'left');

        if ($role === 'pemilik') {
            // Pemilik bisa melihat semua ulasan
            $title = 'Daftar Semua Ulasan Pelanggan';
            // Tidak ada filter tambahan
        } elseif ($role === 'pelanggan') {
            // Pelanggan hanya bisa melihat ulasan dengan rating 5
            $title = 'Ulasan Terbaik dari Pelanggan';
            $ulasanQuery->where('ulasan.rating', 5);
        } else {
            // Jika ada role lain yang tidak diizinkan
            return redirect()->to('/beranda')->with('error', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        $data = [
            'title' => $title,
            'ulasan' => $ulasanQuery->orderBy('ulasan.created_at', 'DESC')->findAll()
        ];

        return view('ulasan/index', $data);
    }

    // Menampilkan form untuk menambah ulasan (hanya untuk pelanggan)
    public function tambah($id_riwayat)
    {
        if (!session()->has('user') || session('user')['role'] !== 'pelanggan') {
            return redirect()->to('/beranda')->with('error', 'Hanya pelanggan yang bisa memberi ulasan.');
        }

        $transaksi = $this->riwayatModel->where([
            'id_riwayat' => $id_riwayat,
            'id_user' => session('user')['id_user']
        ])->first();

        if (!$transaksi) {
            return redirect()->to('/histori')->with('error', 'Transaksi tidak ditemukan.');
        }

        $ulasanAda = $this->ulasanModel->where('id_riwayat', $id_riwayat)->first();
        if ($ulasanAda) {
            return redirect()->to('/histori')->with('error', 'Anda sudah memberikan ulasan untuk transaksi ini.');
        }

        $data = [
            'title' => 'Beri Ulasan',
            'transaksi' => $transaksi
        ];

        return view('ulasan/tambah', $data);
    }

    // Menyimpan ulasan baru dari form
    public function simpan()
    {
        if (!session()->has('user') || session('user')['role'] !== 'pelanggan') {
            return redirect()->to('/beranda')->with('error', 'Aksi tidak valid.');
        }

        $id_riwayat = $this->request->getPost('id_riwayat');

        $rules = [
            'rating' => 'required|in_list[1,2,3,4,5]',
            'komentar' => 'required|max_length[1000]'
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $transaksi = $this->riwayatModel->where([
            'id_riwayat' => $id_riwayat,
            'id_user' => session('user')['id_user']
        ])->first();
        if (!$transaksi) {
            return redirect()->to('/histori')->with('error', 'Transaksi tidak valid.');
        }

        $this->ulasanModel->save([
            'id_riwayat' => $id_riwayat,
            'id_user' => session('user')['id_user'],
            'rating' => $this->request->getPost('rating'),
            'komentar' => $this->request->getPost('komentar')
        ]);

        return redirect()->to('/histori')->with('success', 'Terima kasih! Ulasan Anda telah kami terima.');
    }
}
