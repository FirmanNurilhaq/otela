<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Status Pesanan</title>
    <meta http-equiv="refresh" content="5">
    <style>
        body {
            font-family: sans-serif;
            text-align: center;
            padding-top: 50px;
        }

        .status {
            font-size: 1.5em;
            font-weight: bold;
        }

        .produksi {
            color: green;
        }

        .pending {
            color: orange;
        }

        .sukses {
            color: blue;
        }

        .gagal {
            color: red;
        }
    </style>
</head>

<body>
    <h1>Status Pesanan Anda: #<?= esc($pemesanan['order_id']) ?></h1>
    <p>Halaman ini akan me-refresh otomatis setiap 5 detik.</p>

    <h2>Status Pembayaran:
        <span class="status <?= esc($pemesanan['status']) === 'produksi' ? 'produksi' : 'pending' ?>">
            <?= esc(ucfirst($pemesanan['status'])) ?>
        </span>
    </h2>

    <h2>Status Pengiriman Email:
        <span class="status <?= strpos($pemesanan['email_status'], 'Berhasil') !== false ? 'sukses' : 'gagal' ?>">
            <?= esc($pemesanan['email_status']) ?>
        </span>
    </h2>

    <a href="<?= base_url('beranda') ?>">Kembali ke Beranda</a>
</body>

</html>