<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<main class="max-w-screen-xl mx-auto px-4 py-10">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6">Riwayat Pesanan Anda</h1>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-gray-700">
                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="px-4 py-3">Tanggal Selesai</th>
                        <th class="px-4 py-3">Order ID</th>
                        <th class="px-4 py-3">Detail Pesanan</th>
                        <th class="px-4 py-3">No. Resi</th>
                        <th class="px-4 py-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <?php if (empty($histori)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-6 text-gray-500">Anda belum memiliki riwayat pesanan.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($histori as $row): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 align-top whitespace-nowrap"><?= date('d M Y, H:i', strtotime($row['tanggal_selesai'])) ?></td>
                                <td class="px-4 py-3 align-top whitespace-nowrap"><?= esc($row['order_id']) ?></td>
                                <td class="px-4 py-3 align-top">
                                    <?php
                                    $items = json_decode($row['detail_items'], true);
                                    if (is_array($items)):
                                    ?>
                                        <ul class="list-disc list-inside space-y-1">
                                            <?php foreach ($items as $item): ?>
                                                <li><?= esc($item['nama_produk']) ?> (x<?= esc($item['jumlah']) ?>)</li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 align-top whitespace-nowrap">
                                    <?php if (!empty($row['resi']) && !empty($row['kurir'])): ?>
                                        <?php
                                        // --- LOGIKA PENERJEMAHAN KURIR ---
                                        $kurirLengkap = strtoupper($row['kurir']);
                                        $kurirCode = '';

                                        if (str_starts_with($kurirLengkap, 'JNE')) {
                                            $kurirCode = 'jne';
                                        } elseif (str_starts_with($kurirLengkap, 'J&T') || str_starts_with($kurirLengkap, 'JNT')) {
                                            $kurirCode = 'jnt';
                                        } elseif (str_starts_with($kurirLengkap, 'SICEPAT')) {
                                            $kurirCode = 'sicepat';
                                        } elseif (str_starts_with($kurirLengkap, 'POS')) {
                                            $kurirCode = 'pos';
                                        } elseif (str_starts_with($kurirLengkap, 'ANTERAJA')) {
                                            $kurirCode = 'anteraja';
                                        } elseif (str_starts_with($kurirLengkap, 'NINJA')) {
                                            $kurirCode = 'ninja';
                                        }
                                        // Tambahkan kurir lain jika ada
                                        ?>

                                        <!-- LINK DIUBAH UNTUK MENGIRIM KODE KURIR YANG BENAR -->
                                        <a href="<?= base_url('cek-resi?kurir=' . urlencode($kurirCode) . '&awb=' . urlencode($row['resi'])) ?>"
                                            class="text-blue-600 hover:underline font-medium"
                                            title="Lacak paket ini (<?= esc($row['kurir']) ?>)">
                                            <?= esc($row['resi']) ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-gray-400">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 align-top text-right font-medium">Rp<?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?= $this->endSection() ?>