<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Laporan Pendapatan - <?= esc($periode) ?></title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 20px auto;
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

    .btn-print {
      background-color: #14b8a6;
      color: white;
      padding: 8px 16px;
      border: none;
      border-radius: 6px;
      font-size: 14px;
      cursor: pointer;
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

    /* Gaya untuk Ringkasan Keuangan */
    .summary-container {
      margin-top: 40px;
      page-break-inside: avoid;
    }

    .summary-table {
      width: 50%;
      margin-left: auto;
      margin-right: 0;
      border: none;
    }

    .summary-table th,
    .summary-table td {
      border: none;
      padding: 6px 10px;
    }

    .summary-table .total-row th,
    .summary-table .total-row td {
      border-top: 2px solid #333;
      font-weight: bold;
    }

    .profit {
      color: #15803d;
    }

    .loss {
      color: #b91c1c;
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
  <div class="no-print action-bar">
    <span></span> <button onclick="window.print()" class="btn-print">🖨 Cetak</button>
  </div>

  <h1>Laporan Keuangan</h1>
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
      <?php if (empty($laporan)): ?>
        <tr>
          <td colspan="4" style="text-align:center; padding: 20px;">Tidak ada transaksi pada periode ini.</td>
        </tr>
      <?php else: ?>
        <?php foreach ($laporan as $row): ?>
          <tr>
            <td><?= date('d M Y', strtotime($row['tanggal_selesai'])) ?></td>
            <td><?= esc($row['nama_lengkap']) ?></td>
            <td>
              <?php $items = json_decode($row['detail_items'], true);
              if (is_array($items)): ?>
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
  </table>

  <div class="summary-container">
    <table class="summary-table">
      <tbody>
        <tr>
          <th>Total Pendapatan</th>
          <td class="text-right">Rp<?= number_format($total_pendapatan, 0, ',', '.') ?></td>
        </tr>
        <tr>
          <th>Modal Usaha</th>
          <td class="text-right">- Rp<?= number_format($modal, 0, ',', '.') ?></td>
        </tr>
        <tr class="total-row">
          <th><?= $keuntungan >= 0 ? 'Keuntungan' : 'Kerugian' ?></th>
          <td class="text-right <?= $keuntungan >= 0 ? 'profit' : 'loss' ?>">
            Rp<?= number_format($keuntungan, 0, ',', '.') ?>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

</body>

</html>