<?php

namespace App\Controllers;

use App\Models\PromoModel;

class Promo extends BaseController
{
    protected $promoModel;

    public function __construct()
    {
        // --- PENAMBAHAN PENGECEKAN MANUAL ---
        // Cek apakah user sudah login dan perannya adalah 'pemilik'
        if (!session()->has('user') || session('user')['role'] !== 'pemilik') {
            // Jika tidak, paksa keluar dan tampilkan error
            // Menggunakan die() untuk menghentikan eksekusi segera
            die(view('errors/html/error_403'));
        }
        // --- AKHIR PENAMBAHAN ---

        $this->promoModel = new PromoModel();
    }

    // Menampilkan halaman utama daftar promo
    public function index()
    {
        $data = [
            'title' => 'Kelola Promo',
            'promos' => $this->promoModel->findAll()
        ];
        return view('promo/index', $data);
    }

    // Menampilkan form tambah promo
    public function tambah()
    {
        $data = [
            'title' => 'Tambah Promo Baru'
        ];
        return view('promo/tambah', $data);
    }

    // Menyimpan data promo baru
    public function simpan()
    {
        $data = [
            'nama_promo'      => $this->request->getPost('nama_promo'),
            'judul_promo'     => $this->request->getPost('judul_promo'),
            'deskripsi_promo' => $this->request->getPost('deskripsi_promo'),
            'tipe_promo'      => $this->request->getPost('tipe_promo'),
            'syarat_kuantitas' => ($this->request->getPost('tipe_promo') === 'kuantitas_kelipatan') ? $this->request->getPost('syarat_kuantitas') : null,
            'nilai_diskon'    => $this->request->getPost('nilai_diskon'),
            'status'          => 'tidak_aktif',
        ];

        $this->promoModel->insert($data);
        return redirect()->to('/promo')->with('success', 'Promo baru berhasil ditambahkan.');
    }

    // Menampilkan form edit promo
    public function edit($id)
    {
        $data = [
            'title' => 'Edit Promo',
            'promo' => $this->promoModel->find($id)
        ];

        if (empty($data['promo'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Promo tidak ditemukan.');
        }

        return view('promo/edit', $data);
    }

    // Memperbarui data promo
    public function update($id)
    {
        $data = [
            'nama_promo'      => $this->request->getPost('nama_promo'),
            'judul_promo'     => $this->request->getPost('judul_promo'),
            'deskripsi_promo' => $this->request->getPost('deskripsi_promo'),
            'tipe_promo'      => $this->request->getPost('tipe_promo'),
            'syarat_kuantitas' => ($this->request->getPost('tipe_promo') === 'kuantitas_kelipatan') ? $this->request->getPost('syarat_kuantitas') : null,
            'nilai_diskon'    => $this->request->getPost('nilai_diskon'),
        ];

        $this->promoModel->update($id, $data);
        return redirect()->to('/promo')->with('success', 'Promo berhasil diperbarui.');
    }

    // Menghapus promo
    public function hapus($id)
    {
        $this->promoModel->delete($id);
        return redirect()->to('/promo')->with('success', 'Promo berhasil dihapus.');
    }

    // --- FUNGSI BARU UNTUK TOGGLE STATUS ---
    // Mengganti status promo (aktif/tidak aktif)
    public function toggleStatus($id)
    {
        $promo = $this->promoModel->find($id);

        if (!$promo) {
            return redirect()->to('/promo')->with('error', 'Promo tidak ditemukan.');
        }

        $db = \Config\Database::connect();
        $builder = $db->table('promo');

        $newStatus = ($promo['status'] === 'aktif') ? 'tidak_aktif' : 'aktif';
        $message = 'Status promo berhasil diubah menjadi ' . $newStatus . '.';

        // Jika promo akan diaktifkan, nonaktifkan dulu semua promo lain
        // untuk memastikan hanya ada satu yang aktif.
        if ($newStatus === 'aktif') {
            $builder->update(['status' => 'tidak_aktif']);
            $message = 'Promo "' . esc($promo['nama_promo']) . '" berhasil diaktifkan.';
        }

        // Update status promo yang dipilih
        $builder->where('id_promo', $id)->update(['status' => $newStatus]);

        return redirect()->to('/promo')->with('success', $message);
    }
}
