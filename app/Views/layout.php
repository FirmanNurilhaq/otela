<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <title><?= $title ?? 'Otela' ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-800">

  <!-- Header -->
  <?= $this->include('header') ?>

  <!-- Konten halaman -->
  <main class="max-w-6xl mx-auto p-6">
    <?= $this->renderSection('content') ?>
  </main>

  <!-- Footer -->
  <?php

  if (session()->has('user') && session('user')['role'] === 'pelanggan') :
  ?>
    <footer class="border-t pt-6 pb-4 text-center text-sm text-gray-600 bg-gray-100">
      <div class="flex justify-center items-center gap-2">
        <span>Hubungi kami:</span>
        <a href="https://wa.me/6282144696226" target="_blank" class="flex items-center text-teal-600 hover:text-teal-700 font-semibold">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1 fill-current" viewBox="0 0 448 512">
            <path d="M380.9 97.1C339 55.1 283.2 32 224.3 32 100.3 32 0 132.3 0 256c0 45 11.7 88.7 34 127.3L2.3 480l100.7-31.7c36.6 19.7 77.6 30.1 121.3 30.1 123.9 0 224.3-100.3 224.3-224.3 0-59-23-114.8-65.1-156.9zM224.3 438.7c-38.5 0-76.1-10.4-108.9-30.1l-7.8-4.6-59.8 18.8 19.6-58.1-5.1-8.1C43.2 325.3 32 291.4 32 256c0-106.1 86.2-192.3 192.3-192.3 51.3 0 99.5 20 135.8 56.3 36.2 36.2 56.3 84.5 56.3 135.8 0 106.1-86.2 192.3-192.4 192.3zm101.1-138.4c-5.5-2.8-32.7-16.1-37.8-17.9-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.5-14.3 17.9-17.5 21.6-3.2 3.7-6.4 4.2-11.9 1.4-32.5-16.2-53.7-28.9-75.2-65.3-5.7-9.8 5.7-9.1 16.2-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9s-19.4 19-19.4 46.3 19.9 53.7 22.7 57.3c2.8 3.7 39.2 59.7 95 83.7 13.3 5.7 23.6 9.1 31.7 11.7 13.3 4.2 25.4 3.6 35 2.2 10.7-1.6 32.7-13.4 37.3-26.3 4.6-13 4.6-24.1 3.2-26.3-1.3-2.1-5.1-3.4-10.6-6.2z" />
          </svg>
          0821-4469-6226 (WhatsApp)
        </a>
      </div>
    </footer>
  <?php endif; ?>

</body>

</html>