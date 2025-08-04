<?php
$koneksi = new mysqli('localhost', 'root', '', 'otela');

if ($koneksi->connect_error) {
    echo "❌ Koneksi gagal: " . $koneksi->connect_error;
} else {
    echo "✅ Koneksi berhasil ke MySQL!";
}
