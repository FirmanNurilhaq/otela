<?php

namespace App\Controllers;

use App\Models\StokModel;
use CodeIgniter\Controller;

class Stok extends BaseController
{
    // ... (fungsi index, tambah, simpan, dll. yang sudah ada) ...
    public function index()
    {
        $model = new StokModel();
        $model->where('jumlah <=', 2000)->set(['status' => 'Hampir Habis'])->update();
        $model->where('jumlah >', 2000)->set(['status' => 'Tersedia'])->update();
        $data['stokbahan'] = $model->findAll();
        return view('stok_bahan', $data);
    }
    public function tambah()
    {
        return view('tambah_bahan');
    }
    public function simpan()
    {
        $model = new StokModel();
        $nama = $this->request->getPost('nama_bahan');
        $jumlah = (int) $this->request->getPost('jumlah'); // Pastikan integer
        $status = ($jumlah <= 2000) ? 'Hampir Habis' : 'Tersedia';
        
        $success = $model->insert([
            'nama_bahan' => $nama,
            'jumlah' => $jumlah,
            'status' => $status,
        ]);

        if ($success) {
            session()->setFlashdata('success', 'Bahan berhasil ditambahkan.');

            // PERUBAHAN: Kirim email jika statusnya 'Hampir Habis'
            if ($status === 'Hampir Habis' && session()->get('isLoggedIn')) {
                $penerimaEmail = session()->get('email'); // Asumsi email disimpan di session
                $this->kirimNotifikasiStok($nama, $jumlah, $penerimaEmail);
            }
        } else {
            session()->setFlashdata('error', 'Gagal menambahkan bahan.');
        }
        
        return redirect()->to(base_url('stok'));
    }
    public function restock()
    {
        $model = new StokModel();
        $data['bahan'] = $model->findAll();
        return view('restock_bahan', $data);
    }
    public function updateRestock()
    {
        $model = new StokModel();
        $id = $this->request->getPost('id_bahan');
        $tambahan = $this->request->getPost('jumlah');
        $bahan = $model->find($id);
        if (!$bahan) {
            session()->setFlashdata('error', 'Bahan tidak ditemukan.');
            return redirect()->to(base_url('stok_bahan'));
        }
        $jumlahBaru = $bahan['jumlah'] + $tambahan;
        $status = ($jumlahBaru <= 2000) ? 'Hampir Habis' : 'Tersedia';
        $success = $model->update($id, [
            'jumlah' => $jumlahBaru,
            'status' => $status,
        ]);
        if ($success) {
            session()->setFlashdata('success', 'Stok bahan berhasil diperbarui.');
        } else {
            session()->setFlashdata('error', 'Gagal memperbarui stok bahan.');
        }
        return redirect()->to(base_url('stok_bahan'));
    }
}
