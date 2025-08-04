<!DOCTYPE html>
<html>
<head>
    <title>Hasil Pelacakan</title>
</head>
<body class="container mt-5">
    <h2>Hasil Pelacakan</h2>

    <?php if ($hasil): ?>
        <div>
            <?= $hasil ?>
        </div>
    <?php else: ?>
        <p>Detail tidak ditemukan. Mungkin nomor resi atau kurir salah.</p>
    <?php endif; ?>

    <a href="<?= base_url('cek-resi') ?>">Cek Resi Lain</a>
</body>
</html>
