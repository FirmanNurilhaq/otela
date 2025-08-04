<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<main class="max-w-screen-lg mx-auto px-4 py-10">
    <div class="bg-white p-6 md:p-8 rounded-lg shadow-md">

        <div class="mb-4">
        <a href="<?= base_url('beranda') ?>"
            class="inline-flex items-center gap-2 rounded-md bg-gray-300 px-5 py-2 text-sm font-medium text-gray-800 shadow-sm transition hover:scale-105 hover:shadow-md hover:bg-gray-500 hover:text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke Beranda
        </a>
        </div>


        <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">Lacak Paket Anda 🚚</h2>
        <p class="text-gray-500 mb-6">Didukung oleh BinderByte API</p>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
                <p><?= session()->getFlashdata('error') ?></p>
            </div>
        <?php elseif (isset($error_api)) : ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
                <p><?= esc($error_api) ?></p>
            </div>
        <?php endif; ?>

        <form action="<?= url_to('CekResi::lacakPaket') ?>" method="post" class="space-y-4">
            <?= csrf_field() ?>
            <div>
                <label for="courier" class="block mb-2 text-sm font-medium text-gray-700">Pilih Kurir</label>
                <select name="courier" id="courier" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-400" required>
                    <option value="jne" <?= ($kurir_prefill ?? '') == 'jne' ? 'selected' : '' ?>>JNE</option>
                    <option value="pos" <?= ($kurir_prefill ?? '') == 'pos' ? 'selected' : '' ?>>POS Indonesia</option>
                </select>
            </div>
            <div>
                <label for="awb" class="block mb-2 text-sm font-medium text-gray-700">Nomor Resi (AWB)</label>
                <input type="text" name="awb" id="awb" placeholder="Masukkan nomor resi di sini" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-400" value="<?= $awb_prefill ?? '' ?>" required>
            </div>
            <button type="submit"
                class="w-full px-4 py-3 text-sm font-medium text-white bg-teal-600 rounded-lg shadow-sm transition hover:scale-105 hover:shadow-md hover:bg-teal-700">
                Lacak Paket
            </button>

        </form>

        <?php if (isset($hasil) && !empty($hasil['history'])) : ?>
            <div class="mt-8 border-t pt-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Detail Pengiriman</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6 p-4 border border-gray-200 rounded-lg bg-gray-50">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Nomor Resi:</p>
                        <p class="text-base font-semibold text-gray-900"><?= esc($hasil['summary']['awb']) ?></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Kurir:</p>
                        <p class="text-base font-semibold text-gray-900 uppercase"><?= esc($hasil['summary']['courier']) ?></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Status:</p>
                        <p class="text-base font-semibold text-green-600"><?= esc($hasil['summary']['status']) ?></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Tanggal Update Terakhir:</p>
                        <p class="text-base font-semibold text-gray-900"><?= date('d M Y, H:i', strtotime($hasil['summary']['date'])) ?></p>
                    </div>
                </div>

                <h4 class="text-lg font-bold text-gray-800 mb-4">Riwayat Perjalanan</h4>
                <ol class="relative border-l border-gray-300 ml-3">
                    <?php foreach ($hasil['history'] as $history) : ?>
                        <li class="mb-8 ml-6">
                            <span class="absolute flex items-center justify-center w-6 h-6 bg-teal-100 rounded-full -left-3 ring-8 ring-white">
                                <svg class="w-3.5 h-3.5 text-teal-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                            </span>
                            <time class="block mb-2 text-sm font-normal leading-none text-gray-500"><?= date('l, d F Y - H:i', strtotime($history['date'])) ?></time>
                            <p class="text-base font-normal text-gray-800"><?= esc($history['desc']) ?></p>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </div>
        <?php endif; ?>

    </div>
</main>

<?= $this->endSection() ?>
