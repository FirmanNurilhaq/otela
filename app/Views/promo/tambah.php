<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<main class="max-w-screen-md mx-auto px-4 py-10">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-center text-teal-600 mb-6">Tambah Promo Baru</h2>

        <form method="post" action="<?= base_url('promo/simpan') ?>" class="space-y-6">
            <?= csrf_field() ?>

            <div>
                <label for="nama_promo" class="block text-sm font-medium text-gray-700">Nama Promo (Internal)</label>
                <input type="text" name="nama_promo" id="nama_promo" class="mt-1 w-full px-4 py-2 border rounded-md" placeholder="Contoh: Promo Gajian Juli" required>
            </div>

            <div>
                <label for="judul_promo" class="block text-sm font-medium text-gray-700">Judul Promo (Untuk Pelanggan)</label>
                <input type="text" name="judul_promo" id="judul_promo" class="mt-1 w-full px-4 py-2 border rounded-md" placeholder="Contoh: PROMO GAJIAN!" required>
            </div>

            <div>
                <label for="deskripsi_promo" class="block text-sm font-medium text-gray-700">Deskripsi Promo</label>
                <textarea name="deskripsi_promo" id="deskripsi_promo" rows="3" class="mt-1 w-full px-4 py-2 border rounded-md" placeholder="Contoh: Potongan langsung Rp 10.000 untuk semua pesanan!" required></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Tipe Promo</label>
                <div class="mt-2 space-y-2">
                    <div class="flex items-center gap-x-3">
                        <input id="tipe_kuantitas" name="tipe_promo" type="radio" value="kuantitas_kelipatan" class="h-4 w-4 border-gray-300 text-teal-600 focus:ring-teal-600" checked>
                        <label for="tipe_kuantitas" class="block text-sm font-medium leading-6 text-gray-900">Diskon Kuantitas Berkelipatan</label>
                    </div>
                    <div class="flex items-center gap-x-3">
                        <input id="tipe_potongan" name="tipe_promo" type="radio" value="potongan_langsung" class="h-4 w-4 border-gray-300 text-teal-600 focus:ring-teal-600">
                        <label for="tipe_potongan" class="block text-sm font-medium leading-6 text-gray-900">Potongan Harga Langsung</label>
                    </div>
                </div>
            </div>

            <div id="syarat_kuantitas_wrapper">
                <label for="syarat_kuantitas" class="block text-sm font-medium text-gray-700">Syarat Kuantitas Minimum</label>
                <input type="number" name="syarat_kuantitas" id="syarat_kuantitas" class="mt-1 w-full px-4 py-2 border rounded-md" placeholder="Contoh: 5" value="5" required>
            </div>

            <div>
                <label for="nilai_diskon" class="block text-sm font-medium text-gray-700">Nilai Diskon (Rp)</label>
                <input type="number" name="nilai_diskon" id="nilai_diskon" class="mt-1 w-full px-4 py-2 border rounded-md" placeholder="Contoh: 5000" required>
            </div>

            <div class="flex justify-end gap-4">
                <a href="<?= base_url('promo') ?>" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Batal</a>
                <button type="submit" class="px-6 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700">Simpan Promo</button>
            </div>
        </form>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tipeKuantitasRadio = document.getElementById('tipe_kuantitas');
        const tipePotonganRadio = document.getElementById('tipe_potongan');
        const syaratKuantitasWrapper = document.getElementById('syarat_kuantitas_wrapper');
        const syaratKuantitasInput = document.getElementById('syarat_kuantitas');

        function toggleSyaratKuantitas() {
            if (tipeKuantitasRadio.checked) {
                syaratKuantitasWrapper.style.display = 'block';
                syaratKuantitasInput.required = true;
            } else {
                syaratKuantitasWrapper.style.display = 'none';
                syaratKuantitasInput.required = false;
            }
        }

        tipeKuantitasRadio.addEventListener('change', toggleSyaratKuantitas);
        tipePotonganRadio.addEventListener('change', toggleSyaratKuantitas);

        // Initial check
        toggleSyaratKuantitas();
    });
</script>

<?= $this->endSection() ?>