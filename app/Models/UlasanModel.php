<?php

namespace App\Models;

use CodeIgniter\Model;

class UlasanModel extends Model
{
    protected $table            = 'ulasan';
    protected $primaryKey       = 'id_ulasan';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    // Sesuaikan dengan nama kolom di tabel ulasan Anda
    protected $allowedFields    = ['id_riwayat', 'id_user', 'rating', 'komentar'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // Tidak ada kolom updated_at
}
