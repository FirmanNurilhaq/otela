<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\RiwayatTransaksiModel;
use App\Models\UserModel;

class Laporan extends Controller
{
    /**
     * Menampilkan halaman utama laporan (dasbor).
     */
    public function index()
    {
        $riwayatModel = new RiwayatTransaksiModel();

        // Ambil filter dari URL
        $filterBulan = $this->request->getGet('bulan') ?? date('Y-m');
        $filterSort = $this->request->getGet('sort') ?? 'terbaru';
        $filterModal = $this->request->getGet('modal') ?? 0; // Ambil nilai modal, default 0

        // Tentukan tanggal awal dan akhir bulan
        $tanggalAwal = $filterBulan . '-01';
        $tanggalAkhir = date('Y-m-t', strtotime($tanggalAwal));

        // Query untuk detail riwayat
        $builder = $riwayatModel
            ->select('riwayat_transaksi.*, user.nama_lengkap')
            ->join('user', 'user.id_user = riwayat_transaksi.id_user', 'left')
            ->where('tanggal_selesai >=', $tanggalAwal . ' 00:00:00')
            ->where('tanggal_selesai <=', $tanggalAkhir . ' 23:59:59');

        if ($filterSort === 'terlama') {
            $builder->orderBy('tanggal_selesai', 'ASC');
        } else {
            $builder->orderBy('tanggal_selesai', 'DESC');
        }
        $data['riwayat'] = $builder->findAll();

        // Hitung total pendapatan untuk periode ini
        $totalPendapatanResult = $riwayatModel
            ->selectSum('total_harga', 'total')
            ->where('tanggal_selesai >=', $tanggalAwal . ' 00:00:00')
            ->where('tanggal_selesai <=', $tanggalAkhir . ' 23:59:59')
            ->get()->getRow();
        $data['total_pendapatan'] = $totalPendapatanResult->total ?? 0;

        // Siapkan data untuk grafik
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
        $data['filter_modal'] = $filterModal; // Kirim nilai modal kembali ke view
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
        $modal = $this->request->getGet('modal') ?? 0; // Ambil modal dari URL

        if (!$filterBulan) {
            return redirect()->to('/laporan')->with('error', 'Bulan untuk mencetak laporan harus dipilih.');
        }

        $tanggalAwal = $filterBulan . '-01';
        $tanggalAkhir = date('Y-m-t', strtotime($tanggalAwal));

        $data['laporan'] = $riwayatModel
            ->select('riwayat_transaksi.*, user.nama_lengkap')
            ->join('user', 'user.id_user = riwayat_transaksi.id_user', 'left')
            ->where('tanggal_selesai >=', $tanggalAwal . ' 00:00:00')
            ->where('tanggal_selesai <=', $tanggalAkhir . ' 23:59:59')
            ->orderBy('tanggal_selesai', 'ASC')
            ->findAll();

        // Hitung total pendapatan dan untung/rugi untuk halaman cetak
        $totalPendapatanResult = $riwayatModel
            ->selectSum('total_harga', 'total')
            ->where('tanggal_selesai >=', $tanggalAwal . ' 00:00:00')
            ->where('tanggal_selesai <=', $tanggalAkhir . ' 23:59:59')
            ->get()->getRow();

        $totalPendapatan = $totalPendapatanResult->total ?? 0;
        $keuntungan = $totalPendapatan - $modal;

        $data['total_pendapatan'] = $totalPendapatan;
        $data['modal'] = $modal;
        $data['keuntungan'] = $keuntungan;
        $data['periode'] = date('F Y', strtotime($filterBulan . '-01'));

        return view('laporan_print', $data);
    }
}
