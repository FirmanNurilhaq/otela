<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<main class="max-w-screen-xl mx-auto px-4 py-10">
  <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">Laporan & Riwayat Transaksi</h1>

  <!-- Filter Controls -->
<div class="bg-white p-4 rounded-lg shadow-md mb-6 flex flex-wrap items-center justify-between gap-4">
  <form action="<?= base_url('laporan') ?>" method="get" class="flex flex-wrap items-center gap-4">
    <?= csrf_field() ?>
    
    <div>
      <label for="bulan" class="block text-sm font-medium text-gray-700">Pilih Bulan</label>
      <input type="month" id="bulan" name="bulan" value="<?= esc($filter_bulan) ?>" required
        class="mt-1 w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm" />
    </div>

    <div>
      <label for="sort" class="block text-sm font-medium text-gray-700">Urutkan</label>
      <select id="sort" name="sort" required
        class="mt-1 w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm">
        <option value="terbaru" <?= $filter_sort === 'terbaru' ? 'selected' : '' ?>>Terbaru</option>
        <option value="terlama" <?= $filter_sort === 'terlama' ? 'selected' : '' ?>>Terlama</option>
      </select>
    </div>

<button type="submit"
  class="self-end py-2 px-4 bg-teal-600 text-white rounded-md shadow-sm transition hover:scale-105 hover:shadow-md hover:bg-teal-700">
  Tampilkan
</button>

  </form>

  <button onclick="toggleModal(true)"
    class="self-end py-2 px-4 bg-gray-700 text-white rounded-md hover:bg-gray-800 shadow-md transition transform hover:scale-[1.02]">
    🖨️ Cetak Laporan
  </button>
</div>


  <!-- Grafik Pendapatan -->
  <div class="bg-white p-6 rounded-lg shadow-md mb-6">
    <h2 class="text-xl font-bold text-center text-teal-600 mb-4">Grafik Pendapatan Harian - <?= esc($periode) ?></h2>
    <canvas id="laporanChart" height="100"></canvas>
  </div>

  <!-- Tabel Riwayat Transaksi -->
  <div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-xl font-bold text-teal-600 mb-4">Detail Riwayat Transaksi</h2>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm text-gray-700">
        <thead class="bg-gray-100 text-left">
          <tr>
            <th class="px-4 py-3">Tanggal Selesai</th>
            <th class="px-4 py-3">Nama Pembeli</th>
            <th class="px-4 py-3">Detail Pesanan</th>
            <th class="px-4 py-3 text-right">Total</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          <?php if (empty($riwayat)): ?>
            <tr>
              <td colspan="4" class="text-center py-4">Tidak ada data untuk periode ini.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($riwayat as $row): ?>
              <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 align-top whitespace-nowrap"><?= date('d M Y, H:i', strtotime($row['tanggal_selesai'])) ?></td>
                <td class="px-4 py-3 align-top"><?= esc($row['nama_lengkap']) ?></td>
                <td class="px-4 py-3 align-top">
                  <?php
                  $items = json_decode($row['detail_items'], true);
                  if (is_array($items)):
                  ?>
                    <ul class="list-disc list-inside space-y-1">
                      <?php foreach ($items as $item): ?>
                        <li><?= esc($item['nama_produk']) ?> (x<?= esc($item['jumlah']) ?>)</li>
                      <?php endforeach; ?>
                    </ul>
                  <?php endif; ?>
                </td>
                <td class="px-4 py-3 align-top text-right font-medium">Rp<?= number_format($row['total_harga'], 0, ',', '.') ?></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>

<!-- Modal Cetak -->
<div id="printModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
  <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-sm">
    <h3 class="text-lg font-bold mb-4 text-teal-600">Cetak Laporan Bulanan</h3>
    <div class="mb-4">
      <label class="block mb-1">Pilih Bulan dan Tahun:</label>
      <input type="month" id="bulanCetak" class="w-full border p-2 rounded" value="<?= esc($filter_bulan) ?>" />
    </div>
    <div class="flex justify-end gap-2">
      <button onclick="toggleModal(false)" class="px-4 py-2 bg-gray-300 rounded">Batal</button>
      <button onclick="submitCetak()" class="px-4 py-2 bg-teal-600 text-white rounded">Cetak</button>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  function toggleModal(show) {
    document.getElementById('printModal').classList.toggle('hidden', !show);
    document.getElementById('printModal').classList.toggle('flex', show);
  }

  function submitCetak() {
    const bulan = document.getElementById('bulanCetak').value;
    if (!bulan) return alert('Bulan wajib diisi');
    const url = "<?= base_url('laporan/print?bulan=') ?>" + bulan;
    window.open(url, '_blank');
    toggleModal(false);
  }

  const ctx = document.getElementById('laporanChart').getContext('2d');
  const chart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: <?= $chart_labels ?>,
      datasets: [
        {
          label: 'Pendapatan (Rp)',
          type: 'bar',
          data: <?= $chart_data ?>,
          backgroundColor: 'rgba(13, 148, 136, 0.5)',
          borderColor: 'rgb(13, 148, 136)',
          borderWidth: 1,
          borderRadius: 6,
        },
        {
          label: 'Tren Pendapatan',
          type: 'line',
          data: <?= $chart_data ?>,
          fill: false,
          borderColor: 'rgba(255, 99, 132, 1)',
          backgroundColor: 'rgba(255, 99, 132, 0.2)',
          tension: 0.3,
          pointRadius: 4,
          pointHoverRadius: 6,
        }
      ]
    },
    options: {
      responsive: true,
      animation: {
        duration: 1000,
        easing: 'easeOutQuart'
      },
      plugins: {
        tooltip: {
          mode: 'index',
          intersect: false,
          callbacks: {
            label: ctx => 'Rp ' + parseInt(ctx.raw).toLocaleString('id-ID')
          }
        },
        legend: {
          position: 'top'
        }
      },
      interaction: {
        mode: 'nearest',
        axis: 'x',
        intersect: false
      },
      scales: {
        x: {
          title: {
            display: true,
            text: 'Tanggal'
          }
        },
        y: {
          beginAtZero: true,
          ticks: {
            callback: value => 'Rp ' + value.toLocaleString('id-ID')
          }
        }
      }
    }
  });
</script>

<?= $this->endSection() ?>