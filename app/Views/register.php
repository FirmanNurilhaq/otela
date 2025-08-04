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
      <!-- Tombol Kembali ke Landing -->
    <div class="mb-6">
    <a href="<?= base_url('/') ?>"
        class="inline-flex items-center gap-2 rounded-md bg-teal-100 px-5 py-2 text-sm font-medium text-teal-800 shadow-sm transition-all duration-200 hover:scale-105 hover:shadow-md hover:bg-teal-600 hover:text-white">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-teal-800 group-hover:text-white" fill="none"
             viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M15 19l-7-7 7-7"/>
        </svg>
        
    </a>
    </div>
    <h2 class="text-2xl font-bold text-center text-teal-600 mb-6">Daftar Akun Baru</h2>

    <form action="<?= base_url('save-register') ?>" method="post" class="space-y-4">
      
      <!-- FIX: Menambahkan CSRF field untuk keamanan -->
      <?= csrf_field() ?>

      <div>
        <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
        <input type="text" id="nama_lengkap" name="nama_lengkap" required
           class="w-full px-4 py-2 border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent" />
        </div>

        <div class="mt-4">
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input type="email" id="email" name="email" required
           class="w-full px-4 py-2 border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent" />
        </div>

        <div class="mt-4">
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <input type="password" id="password" name="password" required
           class="w-full px-4 py-2 border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent" />
        </div>

        <div class="mt-4">
        <label for="no_telp" class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
        <input type="number" id="no_telp" name="no_telp" required
           class="w-full px-4 py-2 border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent" />
        </div>

      <button type="submit"
              class="w-full bg-teal-600 text-white rounded-md py-2 font-medium hover:bg-teal-700 transition duration-200">
        Daftar
      </button>
    </form>

    <div class="mt-4 text-center text-sm text-gray-600">
      Sudah punya akun?
      <a href="<?= base_url('login') ?>" class="text-teal-600 hover:underline">Masuk di sini</a>
    </div>
  </div>
</body>

</html>
