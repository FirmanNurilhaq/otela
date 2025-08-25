<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Verifikasi Akun Otela</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .header h1 {
            color: #1e3a8a;
            /* Warna biru tua */
            margin: 0;
        }

        .content {
            padding: 20px 0;
        }

        .content p {
            margin: 0 0 15px;
        }

        .button-container {
            text-align: center;
            margin: 30px 0;
        }

        .button {
            display: inline-block;
            padding: 14px 28px;
            font-size: 16px;
            font-weight: bold;
            color: #ffffff;
            background-color: #15803d;
            /* Warna hijau tua */
            text-decoration: none;
            border-radius: 5px;
        }

        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }

        .link {
            color: #15803d;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Satu Langkah Lagi!</h1>
        </div>
        <div class="content">
            <h3>Halo, <?= esc($nama_lengkap) ?>!</h3>
            <p>Terima kasih telah mendaftar di Otela. Untuk menyelesaikan proses pendaftaran dan mengaktifkan akun Anda, silakan klik tombol di bawah ini:</p>

            <div class="button-container">
                <a href="<?= $link_verifikasi ?>" class="button">Aktivasi Akun Saya</a>
            </div>

            <p>Jika tombol di atas tidak berfungsi, silakan salin dan tempel URL berikut ke browser Anda:</p>
            <p><a href="<?= $link_verifikasi ?>" class="link"><?= $link_verifikasi ?></a></p>

            <p>Link aktivasi ini hanya berlaku selama <strong>1 jam</strong>.</p>

            <p>Jika Anda tidak merasa mendaftar di situs kami, Anda bisa mengabaikan email ini.</p>
        </div>
        <div class="footer">
            <p>&copy; <?= date('Y') ?> Otela. Semua Hak Cipta Dilindungi.</p>
        </div>
    </div>
</body>

</html>