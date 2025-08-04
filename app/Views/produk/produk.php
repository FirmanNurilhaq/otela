<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<main class="max-w-screen-xl mx-auto px-4 py-10">
  <?php if (session()->getFlashdata('success')): ?>
  <?php endif; ?>

  <div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">Daftar Produk</h1>
    <?php if (session('user')['role'] !== 'pelanggan') : ?>
      <a href="<?= base_url('produk/tambah') ?>" class="inline-flex items-center gap-2 rounded-md bg-teal-600 px-5 py-2 text-sm font-medium text-white shadow-sm hover:scale-105 hover:shadow-md hover:bg-teal-700">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Tambah Produk
      </a>
    <?php endif; ?>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php if (empty($produk)): ?>
      <p class="col-span-full text-center text-gray-500 py-10">Belum ada produk yang ditambahkan.</p>
    <?php else: ?>
      <?php foreach ($produk as $p): ?>
        <div class="relative bg-white border border-gray-200 rounded-lg p-5 shadow transition-transform hover:scale-105 hover:shadow-md">

          <?php if ($p['bestseller'] == 1): ?>
            <div class="absolute top-3 right-3 flex items-center gap-1 bg-gradient-to-r from-yellow-400 to-orange-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg transform transition-transform hover:scale-110">
              <span>⭐</span>
              <span>Bestseller</span>
            </div>
          <?php endif; ?>
          <?php if (!empty($p['gambar'])): ?>
            <img src="<?= base_url('uploads/produk/' . $p['gambar']) ?>" alt="<?= esc($p['nama_produk']) ?>" class="w-full h-48 object-cover rounded-md mb-4">
          <?php else: ?>
            <div class="w-full h-48 bg-gray-100 flex items-center justify-center text-gray-400 rounded-md mb-4">
              <span>Tidak ada gambar</span>
            </div>
          <?php endif; ?>

          <h3 class="text-lg font-semibold text-gray-800 mb-1"><?= esc($p['nama_produk']) ?></h3>
          <p class="text-sm text-gray-600">Ukuran: <?= esc($p['ukuran']) ?> gram</p>
          <p class="text-sm text-gray-600 mb-4">Harga: Rp<?= number_format($p['harga'], 0, ',', '.') ?></p>

          <?php if (session('user')['role'] !== 'pelanggan') : ?>
            <div class="mt-4">
              <span class="inline-flex divide-x divide-gray-300 overflow-hidden rounded border border-gray-300 bg-white shadow-sm">
                <a href="<?= base_url('produk/edit/' . $p['id_produk']) ?>" class="px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-50">✎</a>
                <a href="#" onclick="showDeleteModal('<?= base_url('produk/hapus/' . $p['id_produk']) ?>')" class="px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-50">🗑️</a>
              </span>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</main>

<?= $this->endSection() ?>