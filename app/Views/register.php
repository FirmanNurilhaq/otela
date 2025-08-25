<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">
  <div class="w-full max-w-md bg-white p-6 rounded-lg shadow-md">
    <div class="mb-6">
      <a href="<?= site_url('/') ?>" class="inline-flex items-center gap-2 text-sm font-medium text-teal-800 hover:text-teal-600">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali
      </a>
    </div>
    <h2 class="text-2xl font-bold text-center text-teal-600 mb-6">Daftar Akun Baru</h2>

    <!-- Blok untuk menampilkan pesan error validasi -->
    <?php
    $errors = session()->getFlashdata('errors');
    if ($errors) : ?>
      <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
        <p class="font-bold">Terjadi Kesalahan:</p>
        <ul class="list-disc list-inside">
          <?php foreach ($errors as $error) : ?>
            <li><?= esc($error) ?></li>
          <?php endforeach ?>
        </ul>
      </div>
    <?php endif; ?>

    <form action="<?= site_url('register') ?>" method="post" class="space-y-4">
      <?= csrf_field() ?>

      <div>
        <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
        <input type="text" id="nama_lengkap" name="nama_lengkap" value="<?= old('nama_lengkap') ?>" required class="w-full px-4 py-2 border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent" />
      </div>

      <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input type="email" id="email" name="email" value="<?= old('email') ?>" required class="w-full px-4 py-2 border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent" />
      </div>

      <div>
        <label for="no_telp" class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
        <input type="tel" id="no_telp" name="no_telp" value="<?= old('no_telp') ?>" required class="w-full px-4 py-2 border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent" />
      </div>

      <div>
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <input type="password" id="password" name="password" required class="w-full px-4 py-2 border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent" />
      </div>

      <div>
        <label for="konfirmasi_password" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
        <input type="password" id="konfirmasi_password" name="konfirmasi_password" required class="w-full px-4 py-2 border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent" />
      </div>

      <button type="submit" class="w-full bg-teal-600 text-white rounded-md py-2 font-medium hover:bg-teal-700 transition duration-200">
        Daftar
      </button>
    </form>

    <div class="mt-4 text-center text-sm text-gray-600">
      Sudah punya akun?
      <a href="<?= site_url('login') ?>" class="text-teal-600 hover:underline">Masuk di sini</a>
    </div>
  </div>
</body>

</html>