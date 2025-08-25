<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'id_user';
    protected $allowedFields = [
        'nama_lengkap',
        'email',
        'password',
        'no_telp',
        'role',
        'status',
        'verification_token',
        'token_expires_at'
    ];
}
