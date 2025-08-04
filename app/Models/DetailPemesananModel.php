<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailPemesananModel extends Model
{
    protected $table            = 'detail_pemesanan';
    protected $primaryKey       = 'id_detail';
    protected $allowedFields    = [
        'id_pemesanan',
        'id_produk',
        'jumlah',
        'harga_saat_pesan',
        'subtotal'
    ];
}
