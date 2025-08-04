<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Database;
use Exception;

class Testdb extends Controller
{
    public function index()
    {
        try {
            $db = Database::connect();
            $query = $db->query('SELECT 1');

            if ($query) {
                echo "✅ Koneksi database berhasil.";
            } else {
                echo "❌ Koneksi database gagal.";
            }
        } catch (Exception $e) {
            echo "❌ Terjadi error: " . $e->getMessage();
        }
    }
}
