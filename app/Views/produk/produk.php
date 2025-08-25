<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<main class="max-w-screen-xl mx-auto px-4 py-10">
    <?php if (session()->getFlashdata('success')): ?>
    <div id="popup-alert" role="alert" class="fixed top-6 left-1/2 -translate-x-1/2 z-50 max-w-sm w-full rounded-md border border-gray-300 bg-white p-4 shadow-lg transition-opacity duration-500">
        <div class="flex items-start gap-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="flex-1">
                <strong class="font-medium text-gray-900">Berhasil</strong>
                <p class="mt-0.5 text-sm text-gray-700"><?= esc(session()->getFlashdata('success')) ?></p>
            </div>
            <button onclick="document.getElementById('popup-alert').remove();" class="-m-3 rounded-full p-1.5 text-gray-500 hover:bg-gray-50 hover:text-gray-700" type="button">
                <span class="sr-only">Tutup popup</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
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
                    <div class="absolute top-3 right-3 bg-amber-400 text-white text-xs font-bold px-2 py-1 rounded-full shadow-md">
                        Bestseller
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

<!-- Modal Konfirmasi Hapus -->
<div id="confirm-delete-modal" class="fixed inset-0 z-50 hidden bg-black/30 items-center justify-center">
    <div class="rounded-md border border-gray-300 bg-white p-5 shadow-lg w-full max-w-md">
        <div class="flex items-start gap-4">
            <div class="flex-1">
                <strong class="font-medium text-gray-900">Yakin ingin menghapus produk?</strong>
                <p class="mt-0.5 text-sm text-gray-700">Produk ini akan ditandai sebagai dihapus dan tidak akan tampil di katalog.</p>
                <div class="mt-4 flex justify-end gap-2">
                    <button onclick="hideDeleteModal()" type="button" class="rounded border border-gray-300 px-4 py-1.5 text-sm font-medium text-gray-900 hover:bg-gray-100">Batal</button>
                    <a id="confirm-delete-btn" href="#" class="rounded bg-red-600 px-4 py-1.5 text-sm font-medium text-white hover:bg-red-700">Hapus</a>
                </div>
            </div>
            <button onclick="hideDeleteModal()" class="-m-3 rounded-full p-1.5 text-gray-500 hover:bg-gray-50 hover:text-gray-700" type="button">
                <span class="sr-only">Tutup popup</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
</div>

<script>
    // Auto-dismiss popup success
    window.addEventListener("DOMContentLoaded", () => {
        const alertBox = document.getElementById('popup-alert');
        if (alertBox) {
            setTimeout(() => {
                alertBox.style.transition = 'opacity 0.5s ease';
                alertBox.style.opacity = '0';
                setTimeout(() => alertBox.remove(), 500);
            }, 3000);
        }
    });

    // --- AWAL PERBAIKAN SCRIPT MODAL ---
    function showDeleteModal(hapusUrl) {
        // Menggunakan ID yang benar dari HTML: 'confirm-delete-modal'
        const modal = document.getElementById('confirm-delete-modal');
        // Menggunakan ID yang benar dari HTML: 'confirm-delete-btn'
        const confirmBtn = document.getElementById('confirm-delete-btn');
        
        confirmBtn.setAttribute('href', hapusUrl);
        modal.classList.remove('hidden');
        modal.classList.add('flex'); // Tambahkan 'flex' untuk memunculkan modal
    }

    function hideDeleteModal() {
        const modal = document.getElementById('confirm-delete-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Tutup modal jika klik di luar area kontennya
    window.addEventListener('click', function (event) {
        const modal = document.getElementById('confirm-delete-modal');
        if (event.target === modal) {
            hideDeleteModal();
        }
    });
    // --- AKHIR PERBAIKAN SCRIPT MODAL ---
</script>

<?= $this->endSection() ?>
