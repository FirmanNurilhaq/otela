<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pembayaran Pesanan</title>
  <script type="text/javascript"
    src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="SB-Mid-client-c8PocpVgw4BYfBR0"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      flex-direction: column;
      background-color: #f4f4f4;
      margin: 0;
    }

    .container {
      text-align: center;
      padding: 40px;
      border-radius: 10px;
      background-color: white;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    h1 {
      color: #333;
    }

    p {
      color: #666;
      font-size: 1.2em;
    }
  </style>
</head>

<body>

  <div class="container">
    <h1>Menyiapkan Pembayaran...</h1>
    <p>Total Tagihan: <strong>Rp <?= number_format($pemesanan['total_harga'], 0, ',', '.') ?></strong></p>
    <p>Silakan tunggu, jendela pembayaran akan segera muncul.</p>
    <button id="pay-button" style="display: none;">Bayar Sekarang</button>
  </div>

  <script type="text/javascript">
    // Ambil tombol bayar
    var payButton = document.getElementById('pay-button');

    // Fungsi untuk memulai pembayaran
    function startPayment() {
      snap.pay('<?= $snapToken ?>', {
        onSuccess: function(result) {
          /* Anda bisa tambahkan pesan sukses atau redirect */
          alert("Pembayaran berhasil!");
          console.log(result);
          window.location.href = "<?= base_url('beranda') ?>"; // Arahkan ke halaman riwayat pesanan
        },
        onPending: function(result) {
          /* Pelanggan belum menyelesaikan pembayaran */
          alert("Menunggu pembayaran Anda!");
          console.log(result);
          window.location.href = "<?= base_url('beranda') ?>";
        },
        onError: function(result) {
          /* Terjadi kesalahan */
          alert("Pembayaran gagal!");
          console.log(result);
          window.location.href = "<?= base_url('beranda') ?>";
        },
        onClose: function() {
          /* Pelanggan menutup popup tanpa menyelesaikan pembayaran */
          alert('Anda menutup jendela pembayaran sebelum selesai.');
          window.location.href = "<?= base_url('pemesanan') ?>"; // Arahkan kembali ke form pemesanan
        }
      });
    }

    // Langsung panggil fungsi pembayaran saat halaman dimuat
    // Ini akan membuat popup langsung muncul
    startPayment();

    // Sebagai fallback, tambahkan event listener ke tombol
    payButton.addEventListener('click', startPayment);
  </script>

</body>

</html>