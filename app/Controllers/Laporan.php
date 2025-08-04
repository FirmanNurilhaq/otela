<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\RiwayatTransaksiModel; // Gunakan model riwayat yang baru
use App\Models\UserModel;

class Laporan extends Controller
{
    /**
     * Menampilkan halaman utama laporan (dasbor).
     */
    public function index()
    {
        $riwayatModel = new RiwayatTransaksiModel();

        // Ambil filter dari URL, jika tidak ada, gunakan bulan & tahun saat ini
        $filterBulan = $this->request->getGet('bulan') ?? date('Y-m');
        $filterSort = $this->request->getGet('sort') ?? 'terbaru';

        // Tentukan tanggal awal dan akhir bulan untuk filter
        $tanggalAwal = $filterBulan . '-01';
        $tanggalAkhir = date('Y-m-t', strtotime($tanggalAwal));

        // Siapkan query builder untuk tabel riwayat
        $builder = $riwayatModel
            ->select('riwayat_transaksi.*, user.nama_lengkap')
            ->join('user', 'user.id_user = riwayat_transaksi.id_user', 'left')
            ->where('tanggal_selesai >=', $tanggalAwal . ' 00:00:00')
            ->where('tanggal_selesai <=', $tanggalAkhir . ' 23:59:59');

        // Terapkan sorting
        if ($filterSort === 'terlama') {
            $builder->orderBy('tanggal_selesai', 'ASC');
        } else {
            $builder->orderBy('tanggal_selesai', 'DESC');
        }

        // Ambil data untuk tabel
        $data['riwayat'] = $builder->findAll();

        // Siapkan data untuk grafik pendapatan harian dalam bulan yang dipilih
        $pendapatanHarian = $riwayatModel
            ->select("DATE_FORMAT(tanggal_selesai, '%d') as tanggal, SUM(total_harga) as pendapatan")
            ->where('tanggal_selesai >=', $tanggalAwal . ' 00:00:00')
            ->where('tanggal_selesai <=', $tanggalAkhir . ' 23:59:59')
            ->groupBy("DATE(tanggal_selesai)")
            ->orderBy('tanggal_selesai', 'ASC')
            ->findAll();

        $chartLabels = [];
        $chartData = [];
        foreach ($pendapatanHarian as $row) {
            $chartLabels[] = $row['tanggal'];
            $chartData[] = $row['pendapatan'];
        }

        $data['chart_labels'] = json_encode($chartLabels);
        $data['chart_data'] = json_encode($chartData);
        $data['filter_bulan'] = $filterBulan;
        $data['filter_sort'] = $filterSort;
        $data['periode'] = date('F Y', strtotime($filterBulan . '-01'));

        return view('laporan', $data);
    }

    /**
     * Menyiapkan data untuk halaman cetak.
     */
    public function print()
    {
        $riwayatModel = new RiwayatTransaksiModel();
        $filterBulan = $this->request->getGet('bulan');

        if (!$filterBulan) {
            return redirect()->to('/laporan')->with('error', 'Bulan untuk mencetak laporan harus dipilih.');
        }

        // Tentukan tanggal awal dan akhir bulan untuk filter
        $tanggalAwal = $filterBulan . '-01';
        $tanggalAkhir = date('Y-m-t', strtotime($tanggalAwal));

        $data['laporan'] = $riwayatModel
            ->select('riwayat_transaksi.*, user.nama_lengkap')
            ->join('user', 'user.id_user = riwayat_transaksi.id_user', 'left')
            ->where('tanggal_selesai >=', $tanggalAwal . ' 00:00:00')
            ->where('tanggal_selesai <=', $tanggalAkhir . ' 23:59:59')
            ->orderBy('tanggal_selesai', 'ASC')
            ->findAll();

        $data['periode'] = date('F Y', strtotime($filterBulan . '-01'));

        return view('laporan_print', $data);
    }
}
