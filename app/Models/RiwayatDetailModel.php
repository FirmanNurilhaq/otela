<?php

namespace App\Models;

use CodeIgniter\Model;

class RiwayatDetailPemesananModel extends Model
{
    protected $table            = 'riwayat_detail_pemesanan';
    protected $primaryKey       = 'id_detail';
    protected $allowedFields    = [
        'id_detail',
        'id_pemesanan',
        'id_produk',
        'jumlah',
        'harga_saat_pesan',
        'subtotal'
    ];
}
