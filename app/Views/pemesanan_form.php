<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<main class="max-w-screen-xl mx-auto px-4 py-10">
  <div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Pesan Produk Kami</h1>
    <?php
    $total_items = 0;
    if (!empty($keranjang)) {
      foreach ($keranjang as $item) {
        $total_items += $item['jumlah'];
      }
    }
    ?>
    <a href="<?= base_url('pemesanan/keranjang') ?>" id="keranjang-ikon" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700 transition">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
      </svg>
      <span>Keranjang</span>
      <?php if ($total_items > 0): ?>
        <span class="absolute -top-2 -right-2 flex items-center justify-center h-5 w-5 text-xs font-bold text-white bg-red-500 rounded-full"><?= $total_items ?></span>
      <?php endif; ?>
    </a>
  </div>

  <?php if (session()->getFlashdata('success')): ?>
    <div id="popup-alert-success" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md shadow-md" role="alert">
      <p class="font-bold">Berhasil!</p>
      <p><?= session()->getFlashdata('success') ?></p>
    </div>
  <?php endif; ?>
  <?php if (session()->getFlashdata('error')): ?>
    <div id="popup-alert-error" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md shadow-md" role="alert">
      <p class="font-bold">Gagal!</p>
      <p><?= session()->getFlashdata('error') ?></p>
    </div>
  <?php endif; ?>

  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    <?php if (empty($produk)): ?>
      <p class="col-span-full text-center text-gray-500">Belum ada produk yang tersedia.</p>
    <?php else: ?>
      <?php foreach ($produk as $p): ?>
        <div class="kartu-produk relative bg-white border border-gray-200 rounded-lg p-4 shadow-sm transition-transform duration-300 hover:scale-105 hover:shadow-md overflow-hidden">

          <?php if ($p['bestseller'] == 1): ?>
            <div class="absolute top-0 left-0">
              <div class="absolute transform -rotate-45 bg-gradient-to-r from-red-500 via-yellow-400 to-red-500 text-white text-center font-bold uppercase text-sm py-1.5 w-36 shadow-lg" style="left: -40px; top: 20px;">
                ⭐ Bestseller
              </div>
            </div>
          <?php endif; ?>

          <?php if (!empty($p['gambar']) && file_exists(FCPATH . 'uploads/produk/' . $p['gambar'])): ?>
            <img src="<?= base_url('uploads/produk/' . $p['gambar']) ?>" alt="<?= esc($p['nama_produk']) ?>" class="w-full h-48 object-cover rounded-md mb-4">
          <?php else: ?>
            <div class="w-full h-48 bg-gray-100 flex items-center justify-center text-gray-400 rounded-md mb-4">
              Tidak ada gambar
            </div>
          <?php endif; ?>

          <h3 class="text-lg font-semibold text-gray-800 mb-1"><?= esc($p['nama_produk']) ?></h3>
          <p class="text-sm text-gray-600">Ukuran: <?= esc($p['ukuran']) ?> gr</p>
          <p class="text-sm text-gray-600 mb-2">Harga: <span class="font-bold text-teal-600">Rp<?= number_format($p['harga'], 0, ',', '.') ?></span></p>
          <p class="text-xs text-gray-600 mb-3">
            Stok tersedia:
            <span class="font-bold <?= $p['stok_tersedia'] > 0 ? 'text-green-600' : 'text-red-600' ?>">
              <?= $p['stok_tersedia'] ?>
            </span> pcs
          </p>

          <div class="bg-gray-50 p-3 rounded-md">
            <?php if ($p['stok_tersedia'] > 0): ?>
              <form action="<?= base_url('pemesanan/keranjang/tambah') ?>" method="post" class="form-tambah-keranjang">
                <?= csrf_field() ?>
                <input type="hidden" name="id_produk" value="<?= $p['id_produk'] ?>">

                <div class="flex items-center justify-center gap-1 mb-3">
                  <button type="button" onclick="ubahJumlah(<?= $p['id_produk'] ?>, -1, <?= $p['stok_tersedia'] ?>)" class="w-9 h-9 flex items-center justify-center border border-gray-300 rounded hover:bg-gray-100 text-lg">&minus;</button>

                  <input type="number"
                    name="jumlah"
                    id="jumlah_<?= $p['id_produk'] ?>"
                    value="1"
                    min="1"
                    max="<?= $p['stok_tersedia'] ?>"
                    class="h-9 w-16 text-center border border-gray-300 rounded focus:ring-teal-500 focus:border-teal-500 text-sm [-moz-appearance:_textfield] [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none" />

                  <button type="button" onclick="ubahJumlah(<?= $p['id_produk'] ?>, 1, <?= $p['stok_tersedia'] ?>)" class="w-9 h-9 flex items-center justify-center border border-gray-300 rounded hover:bg-gray-100 text-lg">&plus;</button>
                </div>

                <button type="submit" class="w-full bg-teal-600 text-white py-2 rounded-md hover:bg-teal-700 transition-colors">
                  Tambah ke Keranjang
                </button>
              </form>
            <?php else: ?>
              <button type="button" class="w-full bg-gray-300 text-gray-500 py-2 rounded-md cursor-not-allowed" disabled>
                Stok Habis
              </button>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</main>

<script>
  // --- AWAL PERBAIKAN: Mengembalikan script yang hilang ---
  function ubahJumlah(id, delta, maxStok) {
    const input = document.getElementById('jumlah_' + id);
    let nilai = parseInt(input.value) || 1;
    nilai += delta;
    if (nilai < 1) nilai = 1;
    if (nilai > maxStok) nilai = maxStok;
    input.value = nilai;
  }
  // --- AKHIR PERBAIKAN ---

  document.addEventListener('DOMContentLoaded', () => {
    // Script untuk menghilangkan notifikasi
    const alerts = document.querySelectorAll('#popup-alert-success, #popup-alert-error');
    alerts.forEach(alert => {
      setTimeout(() => {
        alert.style.transition = 'opacity 0.5s ease';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
      }, 4000);
    });

    // Script untuk animasi "fly-to-cart"
    const forms = document.querySelectorAll('.form-tambah-keranjang');
    const keranjangIkon = document.getElementById('keranjang-ikon');

    forms.forEach(form => {
      form.addEventListener('submit', function(e) {
        e.preventDefault(); // Mencegah form submit secara langsung

        const kartuProduk = form.closest('.kartu-produk');
        const gambarProduk = kartuProduk.querySelector('img');

        if (!gambarProduk) {
          form.submit();
          return;
        }

        const rectGambar = gambarProduk.getBoundingClientRect();
        const duplikatGambar = gambarProduk.cloneNode(true);

        duplikatGambar.style.position = 'fixed';
        duplikatGambar.style.left = rectGambar.left + 'px';
        duplikatGambar.style.top = rectGambar.top + 'px';
        duplikatGambar.style.width = rectGambar.width + 'px';
        duplikatGambar.style.height = rectGambar.height + 'px';
        duplikatGambar.style.zIndex = '1000';
        duplikatGambar.style.transition = 'all 1s ease-in-out';
        duplikatGambar.style.borderRadius = '50%';
        document.body.appendChild(duplikatGambar);

        const rectKeranjang = keranjangIkon.getBoundingClientRect();

        requestAnimationFrame(() => {
          duplikatGambar.style.left = (rectKeranjang.left + rectKeranjang.width / 2) + 'px';
          duplikatGambar.style.top = (rectKeranjang.top + rectKeranjang.height / 2) + 'px';
          duplikatGambar.style.width = '0px';
          duplikatGambar.style.height = '0px';
          duplikatGambar.style.opacity = '0';
          duplikatGambar.style.transform = 'scale(0.2)';
        });

        setTimeout(() => {
          form.submit();
        }, 800);
      });
    });
  });
</script>

<?= $this->endSection() ?>