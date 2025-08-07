<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<main class="max-w-screen-md mx-auto px-4 py-10">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-center text-teal-600 mb-2">Beri Ulasan</h2>
        <p class="text-center text-gray-600 mb-6">Untuk pesanan: <strong><?= esc($transaksi['order_id']) ?></strong></p>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
                <p class="font-bold">Terdapat kesalahan:</p>
                <ul>
                    <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                        <li>- <?= esc($error) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= base_url('ulasan/simpan') ?>" class="space-y-6">
            <?= csrf_field() ?>
            <input type="hidden" name="id_riwayat" value="<?= esc($transaksi['id_riwayat']) ?>">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Rating Anda</label>
                <div class="flex items-center justify-center space-x-2 rating-stars">
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                        <input type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>" class="hidden" required />
                        <label for="star<?= $i ?>" class="cursor-pointer text-gray-300 text-4xl transition-colors hover:text-yellow-400" title="<?= $i ?> Bintang">★</label>
                    <?php endfor; ?>
                </div>
            </div>

            <div>
                <label for="komentar" class="block text-sm font-medium text-gray-700">Komentar Anda</label>
                <textarea name="komentar" id="komentar" rows="5" class="mt-1 w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Bagaimana pengalaman Anda dengan produk kami?" required><?= old('komentar') ?></textarea>
            </div>

            <div class="flex justify-end gap-4">
                <a href="<?= base_url('histori') ?>" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Batal</a>
                <button type="submit" class="px-6 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700">Kirim Ulasan</button>
            </div>
        </form>
    </div>
</main>

<style>
    /* Membuat bintang dari kanan ke kiri */
    .rating-stars {
        direction: rtl;
    }
    /* Memberi warna pada bintang yang dipilih dan yang di-hover */
    .rating-stars input:checked ~ label,
    .rating-stars label:hover,
    .rating-stars label:hover ~ label {
        color: #facc15; /* Tailwind yellow-400 */
    }
</style>

<?= $this->endSection() ?>
