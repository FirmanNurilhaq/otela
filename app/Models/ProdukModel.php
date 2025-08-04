<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdukModel extends Model
{
    protected $table = 'produk';
    protected $primaryKey = 'id_produk';
    // 'harga' ditambahkan karena digunakan saat mengambil data produk
    protected $allowedFields = ['nama_produk', 'ukuran', 'harga', 'gambar', 'bestseller'];
}
