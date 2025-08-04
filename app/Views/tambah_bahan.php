<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<main class="max-w-screen-md mx-auto px-4 py-10">
  <div class="bg-white p-6 rounded-lg shadow-md">

    <?php if (session()->getFlashdata('success')): ?>
      <div role="alert" class="rounded-md border border-gray-300 bg-white p-4 shadow-sm mb-6">
        <div class="flex items-start gap-4">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" class="size-6 text-green-600">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <div class="flex-1">
            <strong class="font-medium text-gray-900">Berhasil!</strong>
            <p class="mt-0.5 text-sm text-gray-700"><?= session()->getFlashdata('success') ?></p>
          </div>
          <button class="-m-3 rounded-full p-1.5 text-gray-500 transition-colors hover:bg-gray-50 hover:text-gray-700"
            type="button" aria-label="Dismiss alert" onclick="this.parentElement.parentElement.remove()">
            <span class="sr-only">Tutup</span>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
              viewBox="0 0 24 24" stroke-width="1.5"
              stroke="currentColor" class="size-5">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
      <div role="alert" class="rounded-md border border-red-200 bg-red-50 p-4 shadow-sm mb-6">
        <div class="flex items-start gap-4">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" class="size-6 text-red-600">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M12 9v2m0 4h.01M12 5.25c-3.728 0-6.75 3.022-6.75 6.75s3.022 6.75 6.75 6.75 6.75-3.022 6.75-6.75S15.728 5.25 12 5.25z" />
          </svg>
          <div class="flex-1">
            <strong class="font-medium text-red-800">Gagal!</strong>
            <p class="mt-0.5 text-sm text-red-700"><?= session()->getFlashdata('error') ?></p>
          </div>
          <button class="-m-3 rounded-full p-1.5 text-red-500 transition-colors hover:bg-red-100 hover:text-red-800"
            type="button" aria-label="Dismiss alert" onclick="this.parentElement.parentElement.remove()">
            <span class="sr-only">Tutup</span>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
              viewBox="0 0 24 24" stroke-width="1.5"
              stroke="currentColor" class="size-5">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    <?php endif; ?>

    <div class="mb-4">
      <!-- PERBAIKAN DI SINI: Mengubah 'stok_bahan' menjadi 'stok' -->
      <a href="<?= base_url('stok') ?>"
        class="inline-flex items-center gap-2 rounded-md bg-gray-300 px-5 py-2 text-sm font-medium text-gray-800 shadow-sm transition hover:scale-105 hover:shadow-md hover:bg-gray-500 hover:text-white">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali
      </a>
    </div>

    <h2 class="text-2xl font-bold text-center text-teal-600 mb-6">Tambah Bahan Baru</h2>

    <form action="<?= base_url('stok/simpan') ?>" method="post">

      <?= csrf_field() ?> <div class="flex flex-col items-center gap-4">
        <div class="flex gap-8 flex-col md:flex-row">
          <div class="flex flex-col items-center">
            <label for="nama_bahan" class="mb-1 text-sm font-medium text-gray-700">Nama Bahan</label>
            <input
              type="text"
              name="nama_bahan"
              id="nama_bahan"
              class="w-52 rounded-md border-2 border-gray-400 px-3 py-2 shadow-sm focus:ring-teal-500 focus:border-teal-500"
              required>
          </div>

          <div class="flex flex-col items-center">
            <label for="jumlah" class="mb-1 text-sm font-medium text-gray-700">Jumlah</label>
            <div class="flex items-center gap-1">
              <button type="button" onclick="updateJumlah(-1)"
                class="size-10 leading-10 text-gray-600 border border-gray-400 rounded hover:opacity-75">
                &minus;
              </button>
              <input
                type="number"
                name="jumlah"
                id="jumlah"
                value="1"
                class="h-10 w-16 text-center rounded-sm border-2 border-gray-300 focus:ring-teal-500 focus:border-teal-500 [-moz-appearance:_textfield] [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none"
                required />
              <button type="button" onclick="updateJumlah(1)"
                class="size-10 leading-10 text-gray-600 border border-gray-400 rounded hover:opacity-75">
                &plus;
              </button>
            </div>
          </div>
        </div>

        <button type="submit"
          class="mt-6 inline-flex items-center gap-2 px-6 py-2 rounded-md bg-teal-600 text-white text-sm font-medium shadow-sm transition hover:scale-105 hover:shadow-md hover:bg-teal-700">
          Simpan
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
</script>

<?= $this->endSection() ?>