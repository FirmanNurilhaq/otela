<?php

namespace App\Models;

use CodeIgniter\Model;

class StokModel extends Model
{
    protected $table = 'stokbahan';
    protected $primaryKey = 'id_bahan';
    protected $allowedFields = ['nama_bahan', 'jumlah', 'status'];
}
