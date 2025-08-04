<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<main class="max-w-screen-md mx-auto px-4 py-10">
  <div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-center text-teal-600 mb-6">Tambah Produk Baru</h2>

    <form method="post" action="<?= base_url('produk/simpan') ?>" enctype="multipart/form-data" class="space-y-6">
      <?= csrf_field() ?>

      <fieldset class="border p-4 rounded-md">
        <legend class="text-lg font-semibold px-2 text-gray-700">Detail Produk</legend>

        <div class="space-y-4">
          <div>
            <label for="nama_produk" class="block text-sm font-medium text-gray-700">Nama Produk</label>
            <input type="text" name="nama_produk" id="nama_produk" class="mt-1 w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm" required>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label for="ukuran" class="block text-sm font-medium text-gray-700">Ukuran (gram)</label>
              <input type="number" name="ukuran" id="ukuran" class="mt-1 w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm" required>
            </div>
            <div>
              <label for="harga" class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
              <input type="number" name="harga" id="harga" class="mt-1 w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm" required>
            </div>
          </div>

          <div>
            <label for="gambar" class="block text-sm font-medium text-gray-700">Gambar Produk</label>
            <input type="file" name="gambar" id="gambar" accept="image/*" class="mt-1 w-full px-3 py-2 border rounded-md text-sm file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Jadikan Bestseller?</label>
            <div class="mt-2 flex items-center gap-x-6">
              <div class="flex items-center gap-x-2">
                <input id="bestseller_ya" name="bestseller" type="radio" value="1" class="h-4 w-4 border-gray-300 text-teal-600 focus:ring-teal-600">
                <label for="bestseller_ya" class="block text-sm font-medium leading-6 text-gray-900">Ya</label>
              </div>
              <div class="flex items-center gap-x-2">
                <input id="bestseller_tidak" name="bestseller" type="radio" value="0" class="h-4 w-4 border-gray-300 text-teal-600 focus:ring-teal-600" checked>
                <label for="bestseller_tidak" class="block text-sm font-medium leading-6 text-gray-900">Tidak</label>
              </div>
            </div>
          </div>
        </div>
      </fieldset>

      <fieldset class="border p-4 rounded-md">
        <legend class="text-lg font-semibold px-2 text-gray-700">Resep Produk</legend>
        <div id="resep-container" class="space-y-4"></div>
        <button type="button" onclick="tambahBahan()" class="w-full mt-4 inline-flex items-center justify-center gap-2 py-2 px-4 bg-gray-200 text-gray-700 rounded-md text-sm font-medium shadow-sm transition transform hover:scale-105 hover:shadow-md hover:bg-gray-300">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Tambah Bahan
        </button>
      </fieldset>
      <div>
        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 py-2 px-4 bg-teal-600 text-white rounded-md text-sm font-medium shadow-sm transition transform hover:scale-105 hover:shadow-md hover:bg-teal-700">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="6" />
          </svg>
          Simpan Produk
        </button>
      </div>
    </form>
  </div>
</main>

<script>
  let resepIndex = 0;
  const daftarBahan = <?= json_encode($bahan) ?>;

  function tambahBahan() {
    const container = document.getElementById('resep-container');
    const newRow = document.createElement('div');
    newRow.classList.add('flex', 'items-center', 'gap-4');
    newRow.setAttribute('id', 'resep-row-' + resepIndex);

    let selectOptions = '<option value="">-- Pilih Bahan --</option>';
    daftarBahan.forEach(b => {
      selectOptions += `<option value="${b.id_bahan}">${escapeHTML(b.nama_bahan)}</option>`;
    });

    newRow.innerHTML = `
            <div class="flex-grow">
                <select name="resep[${resepIndex}][id_bahan]" required class="block w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm">
                    ${selectOptions}
                </select>
            </div>
            <div class="w-40">
                <input type="number" name="resep[${resepIndex}][jumlah]" placeholder="Jumlah (gr)" required class="block w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm">
            </div>
            <div>
                <button type="button" onclick="hapusBahan(${resepIndex})" class="text-red-500 hover:text-red-700 p-2 rounded-full hover:bg-red-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </button>
            </div>
        `;
    container.appendChild(newRow);
    resepIndex++;
  }

  function hapusBahan(index) {
    const row = document.getElementById('resep-row-' + index);
    if (row) row.remove();
  }

  function escapeHTML(str) {
    const div = document.createElement('div');
    div.appendChild(document.createTextNode(str));
    return div.innerHTML;
  }

  document.addEventListener('DOMContentLoaded', function() {
    tambahBahan();
  });
</script>

<?= $this->endSection() ?>