<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', function () {
    return view('landing');
});

// --- RUTE AUTENTIKASI (DIPERBAIKI) ---
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::saveRegister'); // FIX: Diubah dari 'save-register'
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::cekLogin');       // FIX: Diubah dari 'cek-login'
$routes->get('logout', 'Auth::logout');
$routes->get('auth/verifikasi/(:segment)', 'Auth::verifikasiEmail/$1');

// --- RUTE BERANDA ---
$routes->get('beranda', 'Home::index');

// --- RUTE STOK BAHAN ---
$routes->get('stok', 'Stok::index');
$routes->get('stok/tambah', 'Stok::tambah');
$routes->post('stok/simpan', 'Stok::simpan');
$routes->get('stok/restock', 'Stok::restock');
$routes->post('stok/updateRestock', 'Stok::updateRestock');

// --- RUTE PRODUK ---
$routes->get('produk', 'Produk::index');
$routes->get('produk/tambah', 'Produk::tambah');
$routes->post('produk/simpan', 'Produk::simpan');
$routes->get('produk/edit/(:num)', 'Produk::edit/$1');
$routes->post('produk/update/(:num)', 'Produk::update/$1');
$routes->get('produk/hapus/(:num)', 'Produk::hapus/$1');
$routes->get('produk/salin/(:num)', 'Produk::salin/$1');

// --- RUTE PROMO (CRUD) ---
$routes->get('promo', 'Promo::index');
$routes->get('promo/tambah', 'Promo::tambah');
$routes->post('promo/simpan', 'Promo::simpan');
$routes->get('promo/edit/(:num)', 'Promo::edit/$1');
$routes->post('promo/update/(:num)', 'Promo::update/$1');
$routes->get('promo/hapus/(:num)', 'Promo::hapus/$1');
$routes->get('promo/toggleStatus/(:num)', 'Promo::toggleStatus/$1');

// --- RUTE CEK RESI ---
$routes->get('cek-resi', 'CekResi::index');
$routes->post('cek-resi/lacak', 'CekResi::lacakPaket');

// --- RUTE LAINNYA ---
$routes->get('laporan', 'Laporan::index');
$routes->get('laporan/print', 'Laporan::print');
$routes->get('histori', 'Histori::index');

// --- RUTE PEMBAYARAN (PAYMENT) ---
$routes->get('payment/bayar/(:num)', 'Payment::bayar/$1');
$routes->post('payment/notifikasi', 'Payment::notifikasi');
$routes->get('pesanan/status/(:num)', 'Pemesanan::status/$1');

// --- RUTE ULASAN ---
$routes->get('ulasan', 'Ulasan::index');
$routes->get('ulasan/tambah/(:num)', 'Ulasan::tambah/$1');
$routes->post('ulasan/simpan', 'Ulasan::simpan');

// --- RUTE PEMESANAN ---
$routes->get('pemesanan', 'Pemesanan::index');
$routes->get('pemesanan/max-jumlah/(:num)', 'Pemesanan::getMaxJumlah/$1');
$routes->get('pemesanan/keranjang', 'Pemesanan::tampilKeranjang');
$routes->post('pemesanan/keranjang/tambah', 'Pemesanan::tambahKeKeranjang');
$routes->get('pemesanan/keranjang/hapus/(:num)', 'Pemesanan::hapusItemKeranjang/$1');
$routes->get('pemesanan/ubahStatus/(:num)/(:any)', 'Pemesanan::ubahStatus/$1/$2');
$routes->post('pemesanan/selesaikanPesanan', 'Pemesanan::selesaikanPesanan');
$routes->post('pemesanan/checkout', 'Pemesanan::Checkout');

// --- RUTE AJAX ONGKIR ---
$routes->post('pemesanan/get-provinces', 'Pemesanan::getProvinces');
$routes->post('pemesanan/get-cities', 'Pemesanan::getCities');
$routes->post('pemesanan/cek-ongkir', 'Pemesanan::cekOngkir');
