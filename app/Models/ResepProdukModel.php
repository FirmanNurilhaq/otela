<?php

namespace App\Models;

use CodeIgniter\Model;

class ResepProdukModel extends Model
{
    protected $table = 'resep_produk';
    // Catatan: Primary Key di database Anda adalah composite (id_produk, id_bahan).
    // Namun untuk operasi kita (menggunakan `where()`), ini tidak masalah.
    protected $primaryKey = null;
    protected $allowedFields = ['id_produk', 'id_bahan', 'jumlah']; // 'jumlah_bahan' diperbaiki menjadi 'jumlah'
}
