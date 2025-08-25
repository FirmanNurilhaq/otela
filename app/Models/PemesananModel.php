<?php

namespace App\Models;

use CodeIgniter\Model;

class PemesananModel extends Model
{
    protected $table = 'pemesanan';
    protected $primaryKey = 'id_pemesanan';
    protected $allowedFields = [
        'order_id',
        'id_user',
        'tanggal',
        'total_harga',
        'status',
        'email_status',
        'Alamat',
        'Provinsi',
        'Kota',
        'ongkir_biaya',
        'ongkir_layanan',
        'estimasi_selesai',
        'diskon',
        'id_promo'
    ];
}
