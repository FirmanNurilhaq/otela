<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<main class="max-w-screen-xl mx-auto px-4 py-10">
    <h1 class="text-2xl font-bold mb-6">Daftar Ulasan Pelanggan</h1>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left text-gray-700">
                <thead class="bg-gray-50 uppercase">
                    <tr>
                        <th scope="col" class="px-6 py-3">Tanggal</th>
                        <th scope="col" class="px-6 py-3">Order ID</th>
                        <th scope="col" class="px-6 py-3">Pelanggan</th>
                        <th scope="col" class="px-6 py-3 text-center">Rating</th>
                        <th scope="col" class="px-6 py-3">Komentar</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <?php if (empty($ulasan)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-10 text-gray-500">Belum ada ulasan yang masuk.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($ulasan as $item): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap align-top"><?= date('d M Y', strtotime($item['created_at'])) ?></td>
                                <td class="px-6 py-4 font-medium align-top"><?= esc($item['order_id']) ?></td>
                                <td class="px-6 py-4 align-top"><?= esc($item['nama_lengkap']) ?></td>
                                <td class="px-6 py-4 text-center align-top">
                                    <div class="flex justify-center text-yellow-400 text-lg">
                                        <?php for ($i = 0; $i < 5; $i++): ?>
                                            <span class="<?= $i < $item['rating'] ? 'text-yellow-400' : 'text-gray-300' ?>">★</span>
                                        <?php endfor; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs max-w-sm align-top">
                                    <p><?= esc($item['komentar']) ?></p>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?= $this->endSection() ?>
