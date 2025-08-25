<header class="bg-white shadow-sm">
  <div class="mx-auto max-w-screen-xl px-4 sm:px-6 lg:px-8">
    <div class="flex h-16 items-center justify-between">
      <!-- Logo -->
      <div class="flex items-center gap-3">
        <a href="<?= site_url('/') ?>" class="flex items-center gap-2 text-teal-900 font-semibold text-lg active:scale-95 transition duration-150">
          <span class="text-teal-600 font-bold text-xl tracking-wide">
            Otela
          </span>
        </a>
      </div>

      <?php if (session()->has('user')) : ?>
        <!-- ============================================= -->
        <!-- TAMPILAN JIKA PENGGUNA SUDAH LOGIN            -->
        <!-- ============================================= -->

        <!-- Desktop Navigation -->
        <nav class="hidden md:block">
          <ul class="flex items-center gap-6 text-sm">
            <li>
              <a class="text-gray-700 hover:text-teal-600 active:scale-95 transition duration-150" href="<?= site_url('beranda') ?>">Dashboard</a>
            </li>

            <?php if (session('user')['role'] === 'pelanggan') : ?>
              <!-- Menu untuk Pelanggan -->
              <li><a class="text-gray-700 hover:text-teal-600 active:scale-95 transition duration-150" href="<?= site_url('produk') ?>">Katalog Produk</a></li>
              <li><a class="text-gray-700 hover:text-teal-600 active:scale-95 transition duration-150" href="<?= site_url('pemesanan') ?>">Pesan Produk</a></li>
              <li><a class="text-gray-700 hover:text-teal-600 active:scale-95 transition duration-150" href="<?= site_url('histori') ?>">Histori</a></li>
              <li><a class="text-gray-700 hover:text-teal-600 active:scale-95 transition duration-150" href="<?= site_url('cek-resi') ?>">Cek Resi</a></li>
              <li><a href="<?= site_url('ulasan') ?>" class="py-4 px-2 text-gray-500 font-semibold hover:text-teal-600 transition duration-300">Ulasan</a></li>
            <?php else : // Untuk role 'pemilik' 
            ?>
              <!-- Menu untuk Pemilik (Sesuai Logika Asli Anda) -->
              <li><a class="text-gray-700 hover:text-teal-600 active:scale-95 transition duration-150" href="<?= site_url('stok') ?>">Stok Bahan</a></li>
              <?php if (session('user')['role'] !== 'pemilik') : ?>
                <li><a class="text-gray-700 hover:text-teal-600 active:scale-95 transition duration-150" href="<?= site_url('pemesanan') ?>">Pemesanan</a></li>
              <?php endif; ?>
              <li><a class="text-gray-700 hover:text-teal-600 active:scale-95 transition duration-150" href="<?= site_url('produk') ?>">Produk</a></li>
              <li><a href="<?= site_url('promo') ?>" class="text-gray-600 hover:text-teal-600 font-semibold">Promo</a></li>
              <li><a class="text-gray-700 hover:text-teal-600 active:scale-95 transition duration-150" href="<?= site_url('cek-resi') ?>">Cek Resi</a></li>
              <li><a class="text-gray-700 hover:text-teal-600 active:scale-95 transition duration-150" href="<?= site_url('laporan') ?>">Laporan</a></li>
              <li><a href="<?= site_url('ulasan') ?>" class="py-4 px-2 text-gray-500 font-semibold hover:text-teal-600 transition duration-300">Ulasan</a></li>
            <?php endif; ?>
          </ul>
        </nav>

        <!-- User Dropdown -->
        <div class="relative hidden md:inline-flex text-sm text-gray-700">
          <div class="inline-flex items-center gap-2 rounded-md border border-gray-300 bg-white shadow-sm px-3 py-2 cursor-pointer hover:bg-gray-50 transition" id="user-dropdown-toggle">
            <span><?= esc(session('user')['nama_lengkap']) ?> (<?= esc(session('user')['role']) ?>)</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
          </div>
          <div id="user-dropdown-menu" class="absolute right-0 top-full mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 z-50 opacity-0 scale-95 pointer-events-none transform transition-all duration-200 origin-top-right">
            <a href="<?= site_url('logout') ?>" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 transition">
              Logout
            </a>
          </div>
        </div>

      <?php else : ?>
        <!-- ============================================= -->
        <!-- TAMPILAN JIKA PENGUNJUNG BELUM LOGIN          -->
        <!-- ============================================= -->
        <div class="hidden md:block">
          <a href="<?= site_url('login') ?>" class="inline-block rounded-md bg-teal-600 px-5 py-2.5 text-sm font-medium text-white shadow transition hover:bg-teal-700">
            Login
          </a>
        </div>
      <?php endif; ?>

      <!-- Mobile Menu Toggle -->
      <button id="menu-toggle" class="md:hidden block text-gray-700 focus:outline-none active:scale-95 transition duration-150">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
    </div>
  </div>

  <!-- Mobile Navigation -->
  <div id="mobile-menu" class="md:hidden hidden px-4 pb-4">
    <ul class="flex flex-col gap-2 text-sm mt-2 border-t pt-2 border-gray-200">
      <?php if (session()->has('user')) : ?>
        <!-- Menu Mobile untuk User yang Sudah Login -->
        <li><a class="block py-1 text-gray-700 hover:text-teal-600" href="<?= site_url('beranda') ?>">Dashboard</a></li>

        <?php if (session('user')['role'] === 'pelanggan') : ?>
          <!-- Menu Mobile Pelanggan -->
          <li><a class="block py-1 text-gray-700 hover:text-teal-600" href="<?= site_url('produk') ?>">Produk</a></li>
          <li><a class="block py-1 text-gray-700 hover:text-teal-600" href="<?= site_url('pemesanan') ?>">Pesan Produk</a></li>
          <li><a class="block py-1 text-gray-700 hover:text-teal-600" href="<?= site_url('histori') ?>">Histori</a></li>
        <?php else : // Untuk 'pemilik' 
        ?>
          <!-- Menu Mobile Pemilik (Sesuai Logika Asli Anda) -->
          <li><a class="block py-1 text-gray-700 hover:text-teal-600" href="<?= site_url('stok') ?>">Stok Bahan</a></li>
          <?php if (session('user')['role'] !== 'pemilik') : ?>
            <li><a class="block py-1 text-gray-700 hover:text-teal-600" href="<?= site_url('pemesanan') ?>">Pemesanan</a></li>
          <?php endif; ?>
          <li><a class="block py-1 text-gray-700 hover:text-teal-600" href="<?= site_url('produk') ?>">Produk</a></li>
          <li><a class="block py-1 text-gray-700 hover:text-teal-600" href="<?= site_url('cek-resi') ?>">Cek Resi</a></li>
          <li><a class="block py-1 text-gray-700 hover:text-teal-600" href="<?= site_url('laporan') ?>">Laporan</a></li>
        <?php endif; ?>

        <li class="border-t pt-2 mt-2">
          <a class="block py-1 text-red-500 hover:text-red-700" href="<?= site_url('logout') ?>">Logout</a>
        </li>
        <li class="text-sm text-gray-400 mt-1">
          <?= esc(session('user')['nama_lengkap']) ?> (<?= esc(session('user')['role']) ?>)
        </li>
      <?php else : ?>
        <!-- Menu Mobile untuk Pengunjung -->
        <li><a class="block py-1 text-gray-700 hover:text-teal-600" href="<?= site_url('login') ?>">Login</a></li>
        <li><a class="block py-1 text-gray-700 hover:text-teal-600" href="<?= site_url('register') ?>">Register</a></li>
      <?php endif; ?>
    </ul>
  </div>
</header>

<!-- Toggle Script -->
<script>
  // Pastikan script tidak error jika elemen tidak ada (misal, di halaman login)
  const menuToggle = document.getElementById('menu-toggle');
  const mobileMenu = document.getElementById('mobile-menu');
  if (menuToggle && mobileMenu) {
    menuToggle.addEventListener('click', () => {
      mobileMenu.classList.toggle('hidden');
    });
  }

  const userToggle = document.getElementById('user-dropdown-toggle');
  const userMenu = document.getElementById('user-dropdown-menu');
  if (userToggle && userMenu) {
    document.addEventListener('click', function(e) {
      if (userToggle.contains(e.target)) {
        userMenu.classList.toggle('opacity-0');
        userMenu.classList.toggle('scale-95');
        userMenu.classList.toggle('pointer-events-none');
      } else {
        userMenu.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
      }
    });
  }
</script>