<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
  <div id="popup-alert"
    role="alert"
    class="fixed top-6 left-1/2 -translate-x-1/2 z-50 max-w-sm w-full rounded-md border border-teal-300 bg-teal-50 p-4 shadow-lg transition-opacity duration-500">
    <div class="flex items-start gap-4">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
        stroke-width="1.5" stroke="currentColor" class="size-6 text-teal-600">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <div class="flex-1">
        <strong class="font-medium text-teal-800">Berhasil</strong>
        <p class="mt-0.5 text-sm text-teal-700">
          <?= session()->getFlashdata('success') ?>
        </p>
      </div>
      <button onclick="document.getElementById('popup-alert').remove();"
        class="-m-3 rounded-full p-1.5 text-teal-500 hover:bg-teal-100 hover:text-teal-700">
        <span class="sr-only">Tutup</span>
        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none"
          viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>
  </div>
<?php endif; ?>

<main class="max-w-screen-md mx-auto px-4 py-10">
  <div class="bg-white p-6 rounded-lg shadow-md">
    <div class="mb-4">
      <a href="<?= base_url('stok') ?>"
        class="inline-flex items-center gap-2 rounded-md bg-gray-300 px-5 py-2 text-sm font-medium text-gray-800 shadow-sm transition hover:scale-105 hover:shadow-md hover:bg-gray-500 hover:text-white">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali
      </a>
    </div>

    <h2 class="text-2xl font-bold text-center text-teal-600 mb-6">Restock Bahan</h2>

    <form action="<?= base_url('stok/updateRestock') ?>" method="post">

      <?= csrf_field() ?> <div class="flex flex-col items-center gap-4">
        <div class="flex gap-8 flex-col md:flex-row">
          <div class="flex flex-col items-center">
            <label for="id_bahan" class="mb-1 text-sm font-medium text-gray-700">Pilih Bahan</label>
            <select name="id_bahan" id="id_bahan"
              class="w-52 rounded-md border-2 border-gray-400 px-3 py-2 shadow-sm focus:ring-teal-500 focus:border-teal-500"
              required>
              <?php foreach ($bahan as $row): ?>
                <option value="<?= $row['id_bahan'] ?>"><?= $row['nama_bahan'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="flex flex-col items-center">
            <label for="jumlah" class="mb-1 text-sm font-medium text-gray-700">Tambahkan Jumlah (Gr)</label>
            <div class="flex items-center gap-1">
              <button type="button" onclick="updateJumlah(-1)"
                class="size-10 leading-10 text-gray-600 border border-gray-400 rounded hover:opacity-75">
                &minus;
              </button>
              <input type="number" name="jumlah" id="jumlah" value="1"
                class="h-10 w-16 text-center rounded-sm border-2 border-gray-300 focus:ring-teal-500 focus:border-teal-500"
                required />
              <button type="button" onclick="updateJumlah(1)"
                class="size-10 leading-10 text-gray-600 border border-gray-400 rounded hover:opacity-75">
                &plus;
              </button>
            </div>
          </div>
        </div>

        <button type="submit"
          class="mt-6 inline-flex items-center px-6 py-2 rounded-md bg-teal-500 text-white text-sm font-medium transition hover:scale-105 hover:shadow-md hover:bg-teal-600">
          Restock
        </button>
      </div>
    </form>
  </div>
</main>

<script>
  function updateJumlah(amount) {
    const input = document.getElementById('jumlah');
    let current = parseInt(input.value) || 0;
    let next = current + amount;
    if (next < 1) next = 1;
    input.value = next;
  }

  window.addEventListener("DOMContentLoaded", () => {
    const alertBox = document.getElementById('popup-alert');
    if (alertBox) {
      setTimeout(() => alertBox.remove(), 3000);
    }
  });
</script>

<?= $this->endSection() ?>