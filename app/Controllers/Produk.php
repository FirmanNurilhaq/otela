<?php

namespace App\Controllers;

use App\Models\ProdukModel;
use App\Models\StokModel;
use App\Models\ResepProdukModel;

class Produk extends BaseController
{
    protected $produkModel;

    public function __construct()
    {
        $this->produkModel = new ProdukModel();
    }

    // --- AWAL PERUBAHAN ---
    // Tampilkan semua produk, diurutkan berdasarkan status bestseller
    public function index()
    {
        // Menambahkan orderBy() untuk menempatkan bestseller (nilai 1) di atas
        $data['produk'] = $this->produkModel->orderBy('bestseller', 'DESC')->findAll();
        return view('produk/produk', $data);
    }
    // --- AKHIR PERUBAHAN ---

    // ... sisa controller tidak berubah ...
    public function tambah()
    {
        $stokModel = new StokModel();
        $data['bahan'] = $stokModel->findAll();
        return view('produk/tambah_produk', $data);
    }

    public function simpan()
    {
        $db = \Config\Database::connect();
        $db->transBegin();

        $gambar = $this->request->getFile('gambar');
        $namaGambar = '';

        if ($gambar && $gambar->isValid() && !$gambar->hasMoved()) {
            $namaGambar = $gambar->getRandomName();
            $gambar->move('uploads/produk/', $namaGambar);
        }

        $dataProduk = [
            'nama_produk' => $this->request->getPost('nama_produk'),
            'ukuran'      => $this->request->getPost('ukuran'),
            'harga'       => $this->request->getPost('harga'),
            'gambar'      => $namaGambar,
            'bestseller'  => $this->request->getPost('bestseller') ?? 0
        ];

        $this->produkModel->save($dataProduk);
        $idProdukBaru = $this->produkModel->getInsertID();

        $resepModel = new ResepProdukModel();
        $resepData = $this->request->getPost('resep');

        if (!empty($resepData)) {
            foreach ($resepData as $resep) {
                if (!empty($resep['id_bahan']) && !empty($resep['jumlah']) && $resep['jumlah'] > 0) {
                    $resepModel->insert([
                        'id_produk' => $idProdukBaru,
                        'id_bahan'  => $resep['id_bahan'],
                        'jumlah'    => $resep['jumlah']
                    ]);
                }
            }
        }

        if ($db->transStatus() === false) {
            $db->transRollback();
            session()->setFlashdata('error', 'Gagal menyimpan produk dan resep.');
        } else {
            $db->transCommit();
            session()->setFlashdata('success', 'Produk beserta resepnya berhasil ditambahkan.');
        }

        return redirect()->to('/produk');
    }

    public function edit($id)
    {
        $resepModel = new ResepProdukModel();
        $stokModel = new StokModel();

        $data['produk'] = $this->produkModel->find($id);
        $data['resep'] = $resepModel->where('id_produk', $id)->findAll();
        $data['bahan'] = $stokModel->findAll();

        if (empty($data['produk'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Produk tidak ditemukan.');
        }

        return view('produk/edit_produk', $data);
    }

    public function update($id)
    {
        $db = \Config\Database::connect();
        $db->transBegin();

        $produkLama = $this->produkModel->find($id);
        $namaGambar = $produkLama['gambar'] ?? '';

        $gambarBaru = $this->request->getFile('gambar');
        if ($gambarBaru && $gambarBaru->isValid() && !$gambarBaru->hasMoved()) {
            $namaBaru = $gambarBaru->getRandomName();
            $gambarBaru->move('uploads/produk/', $namaBaru);

            if (!empty($namaGambar) && file_exists('uploads/produk/' . $namaGambar)) {
                unlink('uploads/produk/' . $namaGambar);
            }

            $namaGambar = $namaBaru;
        }

        $this->produkModel->update($id, [
            'nama_produk' => $this->request->getPost('nama_produk'),
            'ukuran'      => $this->request->getPost('ukuran'),
            'harga'       => $this->request->getPost('harga'),
            'gambar'      => $namaGambar,
            'bestseller'  => $this->request->getPost('bestseller') ?? 0
        ]);

        $resepModel = new ResepProdukModel();
        $resepModel->where('id_produk', $id)->delete();

        $resepData = $this->request->getPost('resep');
        if (!empty($resepData)) {
            foreach ($resepData as $resep) {
                if (!empty($resep['id_bahan']) && !empty($resep['jumlah']) && $resep['jumlah'] > 0) {
                    $resepModel->insert([
                        'id_produk' => $id,
                        'id_bahan'  => $resep['id_bahan'],
                        'jumlah'    => $resep['jumlah']
                    ]);
                }
            }
        }

        if ($db->transStatus() === false) {
            $db->transRollback();
            session()->setFlashdata('error', 'Gagal memperbarui produk dan resep.');
        } else {
            $db->transCommit();
            session()->setFlashdata('success', 'Produk berhasil diperbarui.');
        }

        return redirect()->to('/produk');
    }

    public function hapus($id)
    {
        $db = \Config\Database::connect();
        $db->transBegin();

        $produk = $this->produkModel->find($id);
        $gambar = $produk['gambar'] ?? '';

        $resepModel = new ResepProdukModel();
        $resepModel->where('id_produk', $id)->delete();
        $this->produkModel->delete($id);

        if (!empty($gambar) && file_exists('uploads/produk/' . $gambar)) {
            unlink('uploads/produk/' . $gambar);
        }

        if ($db->transStatus() === false) {
            $db->transRollback();
            session()->setFlashdata('error', 'Gagal menghapus produk.');
        } else {
            $db->transCommit();
            session()->setFlashdata('success', 'Produk berhasil dihapus.');
        }

        return redirect()->to('/produk');
    }

    public function salin($id)
    {
        $produk = $this->produkModel->find($id);
        if ($produk) {
            unset($produk['id_produk']);
            $produk['gambar'] = null;
            $this->produkModel->insert($produk);
            session()->setFlashdata('success', 'Produk berhasil disalin.');
        } else {
            session()->setFlashdata('error', 'Produk tidak ditemukan.');
        }
        return redirect()->to('/produk');
    }
}
