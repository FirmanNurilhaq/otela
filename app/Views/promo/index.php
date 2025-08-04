<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<main class="max-w-screen-xl mx-auto px-4 py-10">
    <!-- Notifikasi -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php elseif (session()->getFlashdata('error')): ?>
        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Kelola Promo</h1>
        <a href="<?= base_url('promo/tambah') ?>" class="inline-flex items-center gap-2 rounded-md bg-teal-600 px-5 py-2 text-sm font-medium text-white shadow-sm hover:bg-teal-700 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Promo Baru
        </a>
    </div>

    <div class="overflow-x-auto rounded shadow bg-white">
        <table class="min-w-full text-sm text-gray-700">
            <thead class="bg-gray-100 text-left whitespace-nowrap">
                <tr>
                    <th class="px-4 py-3">Nama Promo</th>
                    <th class="px-4 py-3">Tipe</th>
                    <th class="px-4 py-3">Deskripsi</th>
                    <th class="px-4 py-3">Diskon</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($promos)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-6 text-gray-500">Belum ada promo yang dibuat.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($promos as $promo): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium"><?= esc($promo['nama_promo']) ?></td>
                            <td class="px-4 py-3">
                                <?php if ($promo['tipe_promo'] === 'kuantitas_kelipatan'): ?>
                                    Kelipatan Kuantitas
                                <?php else: ?>
                                    Potongan Langsung
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-xs max-w-xs truncate" title="<?= esc($promo['deskripsi_promo']) ?>"><?= esc($promo['deskripsi_promo']) ?></td>
                            <td class="px-4 py-3">Rp<?= number_format($promo['nilai_diskon'], 0, ',', '.') ?></td>
                            <td class="px-4 py-3 text-center">
                                <?php if ($promo['status'] === 'aktif'): ?>
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Aktif</span>
                                <?php else: ?>
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">Tidak Aktif</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex justify-center items-center space-x-4">
                                    <!-- --- AWAL PERUBAHAN: Tombol menjadi Toggle Switch --- -->
                                    <a href="<?= base_url('promo/toggleStatus/' . $promo['id_promo']) ?>"
                                        class="relative inline-flex items-center h-6 rounded-full w-11 transition-colors duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 <?= $promo['status'] === 'aktif' ? 'bg-teal-500' : 'bg-gray-300' ?>"
                                        title="<?= $promo['status'] === 'aktif' ? 'Klik untuk Nonaktifkan' : 'Klik untuk Aktifkan' ?>">
                                        <span class="sr-only">Toggle Status</span>
                                        <span class="inline-block w-4 h-4 transform bg-white rounded-full transition-transform duration-300 ease-in-out <?= $promo['status'] === 'aktif' ? 'translate-x-6' : 'translate-x-1' ?>"></span>
                                    </a>
                                    <!-- --- AKHIR PERUBAHAN --- -->

                                    <a href="<?= base_url('promo/edit/' . $promo['id_promo']) ?>" class="text-gray-500 hover:text-blue-600" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <a href="<?= base_url('promo/hapus/' . $promo['id_promo']) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus promo ini?')" class="text-gray-500 hover:text-red-600" title="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?= $this->endSection() ?>