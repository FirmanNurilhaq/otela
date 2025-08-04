<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Beranda Otela</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
  </style>
</head>
<body class="relative h-screen overflow-hidden">

  <!-- Background Gambar -->
  <div class="absolute inset-0">
    <img src="https://arthanugrahacom.b-cdn.net/wp-content/uploads/2022/08/Resep-Keripik-Singkong-1140x675.png"
         alt="Background" class="w-full h-full object-cover brightness-75">
  </div>

  <!-- Overlay Konten -->
  <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center px-4">
    <div class="text-center text-white max-w-2xl">
      <h1 class="text-4xl md:text-5xl font-bold mb-4 drop-shadow-md">
        Selamat Datang di <span class="text-teal-300">Otela</span>
      </h1>
      <p class="text-lg md:text-xl mb-8 text-white/90">
        Platform pengelolaan stok dan pemesanan <br> khusus untuk bisnis keripik singkong Anda.
      </p>
      <div class="flex justify-center gap-4 flex-wrap">
        <a href="<?= base_url('register') ?>"
           class="px-6 py-3 bg-teal-500 hover:bg-teal-600 text-white font-semibold rounded-md shadow transition">
          Daftar Sekarang
        </a>
        <a href="<?= base_url('login') ?>"
           class="px-6 py-3 bg-white/20 hover:bg-white/30 text-white font-semibold rounded-md shadow transition">
          Masuk
        </a>
      </div>
    </div>
  </div>

</body>
</html>
