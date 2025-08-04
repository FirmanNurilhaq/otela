<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php $stokbahan = $stokbahan ?? []; ?>

<main class="max-w-screen-xl mx-auto px-4 py-8">
  <div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-center text-teal-600 mb-6">Data Stok Bahan</h2>

    <!-- POPUP ALERT -->
    <?php if (session()->getFlashdata('success')): ?>
      <div
        id="popup-alert"
        role="alert"
        class="fixed top-6 left-1/2 -translate-x-1/2 z-50 max-w-sm w-full rounded-md border border-gray-300 bg-white p-4 shadow-lg transition-opacity duration-500"
      >
        <div class="flex items-start gap-4">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
            stroke-width="1.5" stroke="currentColor" class="h-6 w-6 text-green-600">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <div class="flex-1">
            <strong class="font-medium text-gray-900">Berhasil</strong>
            <p class="mt-0.5 text-sm text-gray-700"><?= session()->getFlashdata('success') ?></p>
          </div>
          <button onclick="document.getElementById('popup-alert').remove()"
            class="-m-2 rounded-full p-1.5 text-gray-500 hover:bg-gray-100 hover:text-gray-700"
            aria-label="Dismiss alert">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
              viewBox="0 0 24 24" stroke-width="1.5"
              stroke="currentColor" class="h-5 w-5">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    <?php endif; ?>

    <!-- Tambah Bahan Button -->
    <div class="mb-6">
      <div class="flex justify-end mb-4">
       <a href="<?= base_url('stok/tambah') ?>"
        class="inline-flex items-center gap-2 rounded-md bg-teal-600 px-6 py-2 text-sm font-medium text-white shadow-sm transition hover:scale-105 hover:shadow-md hover:bg-teal-700"
        >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Tambah Bahan Baru
        </a>

      </div>

      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm text-center">
          <thead class="bg-gray-100 text-gray-700">
            <tr>
              <th class="px-4 py-2">Nama Bahan</th>
              <th class="px-4 py-2">Jumlah</th>
              <th class="px-4 py-2">Status</th>
              <th class="px-4 py-2">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <?php foreach ($stokbahan as $bahan): ?>
              <tr class="hover:bg-gray-50">
                <td class="px-4 py-2"><?= esc($bahan['nama_bahan']) ?></td>
                <td class="px-4 py-2"><?= esc($bahan['jumlah']) ?></td>
                <td class="px-4 py-2">
                  <?php if ($bahan['status'] === 'Hampir Habis'): ?>
                    <span class="inline-block px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">
                      <?= esc($bahan['status']) ?>
                    </span>
                  <?php else: ?>
                    <span class="inline-block px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">
                      <?= esc($bahan['status']) ?>
                    </span>
                  <?php endif; ?>
                </td>
                <td class="px-4 py-2">
                  <a
                    href="<?= base_url('stok/restock') ?>"
                    class="inline-flex items-center gap-2 rounded-sm border border-gray-600 px-4 py-2 text-sm font-medium text-gray-600 transition hover:scale-105 hover:shadow-md focus:ring-2 focus:outline-none"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v6h6M20 20v-6h-6M4 10a8 8 0 0114.31-5.34M20 14a8 8 0 01-14.31 5.34" />
                    </svg>
                    Restock
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</main>

<!-- Auto hide popup -->
<script>
  setTimeout(() => {
    const popup = document.getElementById('popup-alert');
    if (popup) {
      popup.classList.add('opacity-0');
      setTimeout(() => popup.remove(), 500);
    }
  }, 4000);
</script>

<?= $this->endSection() ?>
