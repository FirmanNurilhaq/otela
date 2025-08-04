<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Laporan Pendapatan - <?= esc($periode) ?></title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 40px auto;
      max-width: 900px;
      color: #333;
    }

    h1,
    h2 {
      text-align: center;
      margin-bottom: 0;
    }

    h2 {
      margin-top: 4px;
      font-weight: normal;
      color: #555;
    }

    .action-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .btn-back {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 8px 16px;
      background-color: #d1d5db;
      color: #1f2937;
      border-radius: 6px;
      font-size: 14px;
      text-decoration: none;
      transition: 0.2s;
      font-weight: 500;
    }

    .btn-back:hover {
      background-color: #6b7280;
      color: #fff;
      transform: scale(1.03);
    }

    .btn-print {
      background-color: #14b8a6;
      color: white;
      padding: 8px 16px;
      border: none;
      border-radius: 6px;
      font-size: 14px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .btn-print:hover {
      background-color: #0d9488;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 30px;
      font-size: 14px;
    }

    th,
    td {
      border: 1px solid #ccc;
      padding: 10px;
      vertical-align: top;
    }

    th {
      background-color: #f0fdfa;
      text-align: left;
      font-weight: 600;
    }

    .text-right {
      text-align: right;
    }

    ul {
      margin: 0;
      padding-left: 18px;
    }

    tfoot th {
      background-color: #f9fafb;
      font-weight: bold;
    }

    @media print {
      .no-print {
        display: none !important;
      }

      body {
        margin: 0;
        font-size: 12px;
      }

      table {
        font-size: 12px;
      }
    }
  </style>
</head>

<body>
  <div class="no-print action-bar flex items-center gap-2">
    <a href="<?= base_url('laporan') ?>" class="btn-back flex items-center gap-1 text-sm font-medium text-gray-700 hover:text-teal-700 transition">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
        viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M15 19l-7-7 7-7" />
      </svg>
      Kembali
    </a>

    <button onclick="window.print()"
      class="btn-print inline-flex items-center justify-center bg-teal-600 text-white text-sm font-medium px-4 py-2 rounded-md shadow-md hover:bg-teal-700 focus:outline-none transition transform hover:scale-105 active:scale-95">
      🖨 Cetak
    </button>
  </div>


  <h1>Laporan Pendapatan</h1>
  <h2>Periode: <?= esc($periode) ?></h2>

  <table>
    <thead>
      <tr>
        <th>Tanggal</th>
        <th>Nama Pembeli</th>
        <th>Detail Pesanan</th>
        <th class="text-right">Total Harga</th>
      </tr>
    </thead>
    <tbody>
      <?php $grand_total = 0; ?>
      <?php if (empty($laporan)): ?>
        <tr>
          <td colspan="4" style="text-align:center; padding: 20px;">Tidak ada transaksi pada periode ini.</td>
        </tr>
      <?php else: ?>
        <?php foreach ($laporan as $row): ?>
          <?php $grand_total += $row['total_harga']; ?>
          <tr>
            <td><?= date('d M Y', strtotime($row['tanggal_selesai'])) ?></td>
            <td><?= esc($row['nama_lengkap']) ?></td>
            <td>
              <?php $items = json_decode($row['detail_items'], true); ?>
              <?php if (is_array($items)): ?>
                <ul>
                  <?php foreach ($items as $item): ?>
                    <li><?= esc($item['nama_produk']) ?> (x<?= esc($item['jumlah']) ?>)</li>
                  <?php endforeach; ?>
                </ul>
              <?php endif; ?>
            </td>
            <td class="text-right">Rp<?= number_format($row['total_harga'], 0, ',', '.') ?></td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
    <tfoot>
      <tr>
        <th colspan="3" class="text-right">Total Pendapatan</th>
        <th class="text-right">Rp<?= number_format($grand_total, 0, ',', '.') ?></th>
      </tr>
    </tfoot>
  </table>
</body>

</html>