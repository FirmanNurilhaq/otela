<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

  <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-xl shadow-lg">
    <div class="mb-6">
      <a href="<?= base_url('/') ?>"
        class="inline-flex items-center gap-2 rounded-md bg-teal-100 px-5 py-2 text-sm font-medium text-teal-800 shadow-sm transition-all duration-200 hover:scale-105 hover:shadow-md hover:bg-teal-600 hover:text-white">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-teal-800 group-hover:text-white" fill="none"
          viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M15 19l-7-7 7-7" />
        </svg>

      </a>
    </div>

    <div class="text-center">
      <h2 class="text-2xl font-bold text-teal-600">Selamat Datang</h2>
      <p class="text-gray-500 text-sm">Silakan login untuk melanjutkan</p>
    </div>

    <?php if (session()->getFlashdata('message')) : ?>
      <div class="bg-blue-100 border border-blue-300 text-blue-700 px-4 py-2 rounded relative text-sm">
        <?= session()->getFlashdata('message') ?>
      </div>
    <?php endif; ?>


    <form action="<?= base_url('cek-login') ?>" method="post" class="space-y-4">
      <?= csrf_field() ?>
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" id="email" name="email" required
          class="mt-1 w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm" />
      </div>

      <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input type="password" id="password" name="password" required
          class="mt-1 w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm" />
      </div>

      <button type="submit"
        class="w-full py-2 px-4 bg-teal-600 text-white rounded-md hover:bg-teal-700 shadow-md transition transform hover:scale-[1.02]">
        Login
      </button>
    </form>

    <div class="text-center text-sm pt-2">
      Belum punya akun?
      <a href="<?= base_url('register') ?>" class="text-teal-600 hover:underline hover:text-teal-800 transition">
        Daftar di sini
      </a>
    </div>

</body>

</html>