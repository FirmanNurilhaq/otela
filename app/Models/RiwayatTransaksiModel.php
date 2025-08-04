<?php

namespace App\Models;

use CodeIgniter\Model;

class RiwayatTransaksiModel extends Model
{
    protected $table            = 'riwayat_transaksi';
    protected $primaryKey       = 'id_riwayat';
    protected $allowedFields    = [
        'id_pemesanan_asli',
        'order_id',
        'id_user',
        'tanggal_pesan',
        'detail_items', // Kolom JSON kita
        'total_harga',
        'tanggal_selesai',
        'resi',
        'kurir'
    ];
}
