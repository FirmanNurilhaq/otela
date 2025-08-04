<header class="bg-white shadow-sm">
  <div class="mx-auto max-w-screen-xl px-4 sm:px-6 lg:px-8">
    <div class="flex h-16 items-center justify-between">
      <!-- Logo -->
      <div class="flex items-center gap-3">
        <a href="<?= base_url('beranda') ?>" class="flex items-center gap-2 text-teal-900 font-semibold text-lg active:scale-95 transition duration-150">
          <span class="text-teal-600 font-bold text-xl tracking-wide group-hover:opacity-200 transition">
            Otela
          </span>
        </a>
      </div>

      <!-- Desktop Navigation -->
      <nav class="hidden md:block">
        <ul class="flex items-center gap-6 text-sm">
          <li>
            <a class="text-gray-700 hover:text-teal-600 active:scale-95 transition duration-150"
              href="<?= base_url('beranda') ?>">Dashboard</a>
          </li>

          <?php if (session('user')['role'] === 'pelanggan'): ?>
            <li>
              <a class="text-gray-700 hover:text-teal-600 active:scale-95 transition duration-150"
                href="<?= base_url('produk') ?>">Katalog Produk</a>
            </li>
            <li>
              <a class="text-gray-700 hover:text-teal-600 active:scale-95 transition duration-150"
                href="<?= base_url('pemesanan') ?>">Pesan Produk</a>
            </li>
            <li>
              <a class="text-gray-700 hover:text-teal-600 active:scale-95 transition duration-150"
                href="<?= base_url('histori') ?>">Histori</a>
            </li>
            <li>
              <a class="text-gray-700 hover:text-teal-600 active:scale-95 transition duration-150"
                href="<?= base_url('cek-resi') ?>">Cek Resi</a>
            </li>
          <?php else: ?>
            <li>
              <a class="text-gray-700 hover:text-teal-600 active:scale-95 transition duration-150"
                href="<?= base_url('stok') ?>">Stok Bahan</a>
            </li>
            <?php if (session('user')['role'] !== 'pemilik'): ?>
              <li>
                <a class="text-gray-700 hover:text-teal-600 active:scale-95 transition duration-150"
                  href="<?= base_url('pemesanan') ?>">Pemesanan</a>
              </li>
            <?php endif; ?>
            <li>
              <a class="text-gray-700 hover:text-teal-600 active:scale-95 transition duration-150"
                href="<?= base_url('produk') ?>">Produk</a>
            </li>
            <li><a href="<?= base_url('promo') ?>" class="text-gray-600 hover:text-teal-600 font-semibold">Promo</a></li>
            <li>
              <a class="text-gray-700 hover:text-teal-600 active:scale-95 transition duration-150"
                href="<?= base_url('cek-resi') ?>">Cek Resi</a>
            </li>
            <li>
              <a class="text-gray-700 hover:text-teal-600 active:scale-95 transition duration-150"
                href="<?= base_url('laporan') ?>">Laporan</a>
            </li>
          <?php endif; ?>
        </ul>
      </nav>

      <!-- User Dropdown with Animation -->
      <div class="relative hidden md:inline-flex text-sm text-gray-700">
        <div class="inline-flex items-center gap-2 rounded-md border border-gray-300 bg-white shadow-sm px-3 py-2 cursor-pointer hover:bg-gray-50 transition"
          id="user-dropdown-toggle">
          <span><?= session('user')['nama_lengkap'] ?? 'Pengguna' ?> (<?= session('user')['role'] ?? 'Role' ?>)</span>
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500" fill="none"
            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
          </svg>
        </div>

        <div id="user-dropdown-menu"
          class="absolute right-0 top-full mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 z-50 opacity-0 scale-95 pointer-events-none transform transition-all duration-200 origin-top-right">

          <a href="<?= base_url('logout') ?>"
            class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 transition">
            Logout
          </a>
        </div>
      </div>

      <!-- Mobile Menu Toggle -->
      <button id="menu-toggle"
        class="md:hidden block text-gray-700 focus:outline-none active:scale-95 transition duration-150">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
          stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
    </div>
  </div>

  <!-- Mobile Navigation -->
  <div id="mobile-menu" class="md:hidden hidden px-4 pb-4">
    <ul class="flex flex-col gap-2 text-sm mt-2 border-t pt-2 border-gray-200">
      <li>
        <a class="block py-1 text-gray-700 hover:text-teal-600 active:scale-95 transition duration-150"
          href="<?= base_url('beranda') ?>">Dashboard</a>
      </li>

      <?php if (session('user')['role'] === 'pelanggan'): ?>
        <li>
          <a class="block py-1 text-gray-700 hover:text-teal-600 active:scale-95 transition duration-150"
            href="<?= base_url('produk') ?>">Produk</a>
        </li>
        <li>
          <a class="block py-1 text-gray-700 hover:text-teal-600 active:scale-95 transition duration-150"
            href="<?= base_url('pemesanan') ?>">Pesan Produk</a>
        </li>
        <li>
          <a class="block py-1 text-gray-700 hover:text-teal-600 active:scale-95 transition duration-150"
            href="<?= base_url('histori') ?>">Histori</a>
        </li>
      <?php else: ?>
        <li>
          <a class="block py-1 text-gray-700 hover:text-teal-600 active:scale-95 transition duration-150"
            href="<?= base_url('stok') ?>">Stok Bahan</a>
        </li>
        <?php if (session('user')['role'] !== 'pemilik'): ?>
          <li>
            <a class="block py-1 text-gray-700 hover:text-teal-600 active:scale-95 transition duration-150"
              href="<?= base_url('pemesanan') ?>">Pemesanan</a>
          </li>
        <?php endif; ?>
        <li>
          <a class="block py-1 text-gray-700 hover:text-teal-600 active:scale-95 transition duration-150"
            href="<?= base_url('produk') ?>">Produk</a>
        </li>
        <li>
          <a class="block py-1 text-gray-700 hover:text-teal-600 active:scale-95 transition duration-150"
            href="<?= base_url('cek-resi') ?>">Cek Resi</a>
        </li>
        <li>
          <a class="block py-1 text-gray-700 hover:text-teal-600 active:scale-95 transition duration-150"
            href="<?= base_url('laporan') ?>">Laporan</a>
        </li>
      <?php endif; ?>

      <li>
        <a class="block py-1 text-red-500 hover:text-red-700 active:scale-95 transition duration-150"
          href="<?= base_url('logout') ?>">Logout</a>
      </li>
      <li class="text-sm text-gray-400">
        <?= session('user')['nama_lengkap'] ?? 'Pengguna' ?> (<?= session('user')['role'] ?? 'Role' ?>)
      </li>
    </ul>
  </div>
</header>

<!-- Toggle Script -->
<script>
  // Toggle mobile nav
  document.getElementById('menu-toggle').addEventListener('click', () => {
    const menu = document.getElementById('mobile-menu');
    menu.classList.toggle('hidden');
  });

  // Dropdown animation logic
  const userToggle = document.getElementById('user-dropdown-toggle');
  const userMenu = document.getElementById('user-dropdown-menu');

  document.addEventListener('click', function(e) {
    const isClickInside = userToggle.contains(e.target);

    if (isClickInside) {
      userMenu.classList.toggle('opacity-0');
      userMenu.classList.toggle('scale-95');
      userMenu.classList.toggle('pointer-events-none');
    } else {
      userMenu.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
    }
  });
</script>