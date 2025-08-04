<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<main class="max-w-screen-lg mx-auto px-4 py-10">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6">Keranjang Belanja</h1>
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <?php if (empty($keranjang)): ?>
            <div class="p-6 text-center">
                <p class="text-gray-600">Keranjang belanja Anda masih kosong.</p>
                <a href="<?= site_url('/pemesanan') ?>" class="mt-4 inline-block px-6 py-2 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700 transition">
                    Mulai Belanja
                </a>
            </div>
        <?php else: ?>
            <form id="form-checkout" action="<?= site_url('pemesanan/checkout') ?>" method="post">
                <?= csrf_field() ?>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-700">
                        <thead class="bg-gray-50 uppercase">
                            <tr>
                                <th scope="col" class="px-6 py-3">Produk</th>
                                <th scope="col" class="px-6 py-3">Harga</th>
                                <th scope="col" class="px-6 py-3 text-center">Jumlah</th>
                                <th scope="col" class="px-6 py-3 text-right">Subtotal</th>
                                <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total_harga_produk = 0;
                            $total_berat = 0;
                            $total_kuantitas = 0;
                            foreach ($keranjang as $item) {
                                $total_harga_produk += $item['harga'] * $item['jumlah'];
                                $total_berat += $item['ukuran'] * $item['jumlah'];
                                $total_kuantitas += $item['jumlah'];
                            }

                            // Logika hitung diskon dinamis untuk ditampilkan di ringkasan
                            $jumlah_diskon = 0;
                            if (isset($promoAktif) && $promoAktif) {
                                if ($promoAktif['tipe_promo'] === 'kuantitas_kelipatan') {
                                    if ($total_kuantitas >= $promoAktif['syarat_kuantitas']) {
                                        $kelipatan = floor($total_kuantitas / $promoAktif['syarat_kuantitas']);
                                        $jumlah_diskon = $kelipatan * $promoAktif['nilai_diskon'];
                                    }
                                } elseif ($promoAktif['tipe_promo'] === 'potongan_langsung') {
                                    $jumlah_diskon = $promoAktif['nilai_diskon'];
                                }
                            }
                            $total_setelah_diskon = $total_harga_produk - $jumlah_diskon;
                            ?>
                            <?php foreach ($keranjang as $item): ?>
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900"><?= esc($item['nama_produk']) ?> (<?= esc($item['ukuran']) ?> gr)</td>
                                    <td class="px-6 py-4">Rp<?= number_format($item['harga'], 0, ',', '.') ?></td>
                                    <td class="px-6 py-4 text-center"><?= $item['jumlah'] ?></td>
                                    <td class="px-6 py-4 text-right">Rp<?= number_format($item['harga'] * $item['jumlah'], 0, ',', '.') ?></td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="<?= site_url('pemesanan/keranjang/hapus/' . $item['id_produk']) ?>" class="font-medium text-red-600 hover:underline">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="px-6 pt-6">
                    <?php if (isset($promoAktif) && $promoAktif): ?>
                        <?php
                        if ($promoAktif['tipe_promo'] === 'kuantitas_kelipatan') {
                            if ($total_kuantitas > 0) {
                                $syarat = $promoAktif['syarat_kuantitas'];
                                $sisa_kelipatan = $total_kuantitas % $syarat;
                                if ($jumlah_diskon > 0) {
                                    echo "<div class='bg-green-100 border-l-4 border-green-500 text-green-700 p-3 mb-4 text-sm rounded-md'>Mantap! Anda mendapatkan diskon dari promo: <strong>" . esc($promoAktif['nama_promo']) . "</strong>.</div>";
                                }
                                if ($sisa_kelipatan != 0) {
                                    $sisa_untuk_promo = $syarat - $sisa_kelipatan;
                                    echo "<div class='bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-3 mb-4 text-sm rounded-md'>Ayo, tambah <strong>{$sisa_untuk_promo} produk lagi</strong> untuk mendapatkan diskon tambahan!</div>";
                                }
                            }
                        } else {
                            echo "<div class='bg-green-100 border-l-4 border-green-500 text-green-700 p-3 mb-4 text-sm rounded-md'>Anda mendapatkan promo: <strong>" . esc($promoAktif['deskripsi_promo']) . "</strong>.</div>";
                        }
                        ?>
                    <?php endif; ?>
                </div>

                <div class="p-6 bg-gray-50 border-t">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Detail Pengiriman</h2>

                    <div class="mb-4">
                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                        <textarea id="alamat" name="alamat" rows="3" class="w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500" placeholder="Contoh: Jln. Pahlawan No. 123, RT 01/RW 02, Kelurahan Sukajadi, Kecamatan Sukasari" required></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="province" class="block text-sm font-medium text-gray-700">Provinsi Tujuan</label>
                            <select id="province" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm">
                                <option value="">-- Pilih Provinsi --</option>
                            </select>
                        </div>
                        <div>
                            <label for="destination" class="block text-sm font-medium text-gray-700">Kota Tujuan</label>
                            <select id="destination" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm" disabled>
                                <option value="">-- Pilih Kota --</option>
                            </select>
                        </div>
                        <div>
                            <label for="courier" class="block text-sm font-medium text-gray-700">Kurir</label>
                            <select id="courier" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm">
                                <option value="">-- Pilih Kurir --</option>
                                <option value="jne">JNE</option>
                                <option value="pos">POS Indonesia</option>
                            </select>
                        </div>
                    </div>

                    <button type="button" id="btn-cek-ongkir" class="mt-4 w-auto inline-flex items-center gap-2 justify-center px-4 py-2 text-sm font-medium text-white bg-teal-600 rounded-md shadow-sm transition hover:scale-105 hover:shadow-md hover:bg-teal-700">
                        Cek Ongkos Kirim
                    </button>

                    <div id="hasil-ongkir" class="mt-4 space-y-2"></div>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="space-y-2">
                            <div class="flex justify-between text-base font-medium text-gray-700">
                                <p>Subtotal Produk</p>
                                <p>Rp<?= number_format($total_harga_produk, 0, ',', '.') ?></p>
                            </div>

                            <?php if ($jumlah_diskon > 0): ?>
                                <div class="flex justify-between text-base font-medium text-green-600">
                                    <p>Diskon</p>
                                    <p>- Rp<?= number_format($jumlah_diskon, 0, ',', '.') ?></p>
                                </div>
                            <?php endif; ?>
                            <div class="flex justify-between text-base font-medium text-gray-700">
                                <p>Ongkos Kirim</p>
                                <p id="ongkir-text">Rp 0</p>
                            </div>
                            <div class="flex justify-between text-lg font-bold text-gray-900">
                                <p>Grand Total</p>
                                <p id="grand-total-text">Rp<?= number_format($total_setelah_diskon, 0, ',', '.') ?></p>
                            </div>
                        </div>

                        <input type="hidden" name="provinsi_nama" id="provinsi_nama">
                        <input type="hidden" name="kota_nama" id="kota_nama">
                        <input type="hidden" name="ongkir_biaya" id="ongkir_biaya" value="0">
                        <input type="hidden" name="ongkir_layanan" id="ongkir_layanan" value="">

                        <div class="mt-6 flex flex-col sm:flex-row items-center gap-4">
                            <a href="<?= site_url('pemesanan') ?>" class="w-full sm:w-1/2 text-center bg-gray-200 text-gray-800 px-4 py-3 rounded-md text-sm font-medium shadow-sm transition hover:bg-gray-300">
                                Kembali Belanja
                            </a>
                            <button type="submit" id="btn-bayar" class="w-full sm:w-1/2 bg-teal-600 text-white px-4 py-3 rounded-md text-sm font-medium shadow-sm transition hover:scale-105 hover:shadow-md hover:bg-teal-700 disabled:bg-gray-400" disabled>
                                Lanjut ke Pembayaran
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    </div>
</main>



<script>
    document.addEventListener('DOMContentLoaded', async function() {
        <?php if (!empty($keranjang)): ?>
            const btnCekOngkir = document.getElementById('btn-cek-ongkir');
            const hasilContainer = document.getElementById('hasil-ongkir');
            const btnBayar = document.getElementById('btn-bayar');
            const checkoutForm = document.getElementById('form-checkout');

            const selectProvince = document.getElementById('province');
            const selectDestination = document.getElementById('destination');
            const selectCourier = document.getElementById('courier');
            const inputAlamat = document.getElementById('alamat');

            // --- AWAL PERUBAHAN: Gunakan variabel PHP yang sudah dihitung ---
            const totalSetelahDiskon = <?= $total_setelah_diskon ?? 0 ?>;
            const totalBerat = <?= $total_berat ?? 0 ?>;
            // --- AKHIR PERUBAHAN ---

            const formatUang = (angka) => new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(angka);

            // ... Sisa script tidak diubah (fungsi AJAX, loadProvinsi, dll) ...
            // ... Namun, fungsi yang update total akan diubah ...

            hasilContainer.addEventListener('change', function(e) {
                if (e.target.name === 'service') {
                    const biayaOngkir = parseInt(e.target.dataset.cost);
                    const namaLayanan = e.target.dataset.service;

                    // --- PERUBAHAN: Kalkulasi grand total memperhitungkan total setelah diskon ---
                    const grandTotal = totalSetelahDiskon + biayaOngkir;

                    document.getElementById('ongkir-text').textContent = formatUang(biayaOngkir);
                    document.getElementById('grand-total-text').textContent = formatUang(grandTotal);
                    document.getElementById('ongkir_biaya').value = biayaOngkir;
                    document.getElementById('ongkir_layanan').value = namaLayanan;
                    if (inputAlamat.value.trim() !== '') btnBayar.disabled = false;
                    else {
                        alert('Harap isi alamat lengkap terlebih dahulu.');
                        btnBayar.disabled = true;
                    }
                }
            });

            function resetOngkir() {
                hasilContainer.innerHTML = '';
                document.getElementById('ongkir-text').textContent = 'Rp 0';
                // --- PERUBAHAN: Saat reset, Grand Total kembali ke total setelah diskon ---
                document.getElementById('grand-total-text').textContent = formatUang(totalSetelahDiskon);
                document.getElementById('ongkir_biaya').value = 0;
                document.getElementById('ongkir_layanan').value = '';
                btnBayar.disabled = true;
            }

            // ... Sisa script lainnya (seperti loadProvinces, fetchWithCsrf, dll.) tetap sama ...
            function getSecureSiteUrl(path) {
                let baseUrl = '<?= rtrim(base_url(), '/') ?>';
                if (baseUrl.startsWith('http://')) {
                    baseUrl = 'https://' + baseUrl.substring(7);
                }
                let normalizedPath = path.replace(/^\//, '');
                return baseUrl + '/' + normalizedPath;
            }

            function updateCsrfToken(newCsrfHash) {
                if (newCsrfHash) {
                    const csrfInput = document.querySelector('input[name="<?= csrf_token() ?>"]');
                    if (csrfInput) {
                        const oldHash = csrfInput.value;
                        csrfInput.value = newCsrfHash;
                        console.log(`%cCSRF TOKEN UPDATED`, 'color: green; font-weight: bold;', `From: ${oldHash} --> To: ${newCsrfHash}`);
                    } else {
                        console.error('CSRF hidden input field not found! Cannot update token.');
                    }
                }
            }

            async function fetchWithCsrf(url, options = {}) {
                const csrfName = '<?= csrf_token() ?>';
                const csrfInput = document.querySelector(`input[name="${csrfName}"]`);
                const csrfHash = csrfInput.value;

                console.log(`%cSENDING AJAX`, 'color: blue;', `To: ${url} with CSRF Token: ${csrfHash}`);

                const defaultHeaders = {
                    "X-Requested-With": "XMLHttpRequest"
                };

                if (options.body instanceof FormData) {
                    options.body.append(csrfName, csrfHash);
                }

                const response = await fetch(url, {
                    ...options,
                    headers: {
                        ...defaultHeaders,
                        ...options.headers
                    }
                });

                const newCsrf = response.headers.get('X-CSRF-TOKEN');
                console.log(`%cRECEIVED AJAX RESPONSE`, 'color: orange;', `From: ${url}. New CSRF in header: ${newCsrf || 'None'}`);

                if (newCsrf) {
                    updateCsrfToken(newCsrf);
                }

                return response;
            }

            async function loadProvinces() {
                try {
                    const formData = new FormData();
                    const response = await fetchWithCsrf(getSecureSiteUrl('pemesanan/get-provinces'), {
                        method: 'POST',
                        body: formData
                    });
                    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                    const data = await response.json();
                    if (data.status === 200 && Array.isArray(data.data)) {
                        data.data.forEach(province => {
                            selectProvince.add(new Option(province.name, province.id));
                        });
                    } else {
                        alert('Gagal memuat daftar provinsi: ' + (data.message || 'Format data tidak valid.'));
                    }
                } catch (error) {
                    console.error('Error loading provinces:', error);
                    alert('Terjadi kesalahan saat memuat provinsi.');
                }
            }

            async function loadCities(provinceId) {
                selectDestination.innerHTML = '<option value="">Memuat Kota...</option>';
                selectDestination.disabled = true;
                try {
                    const formData = new FormData();
                    formData.append('province_id', provinceId);
                    const response = await fetchWithCsrf(getSecureSiteUrl('pemesanan/get-cities'), {
                        method: 'POST',
                        body: formData
                    });
                    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                    const data = await response.json();
                    selectDestination.innerHTML = '<option value="">-- Pilih Kota --</option>';
                    if (data.status === 200 && Array.isArray(data.data)) {
                        data.data.forEach(city => {
                            selectDestination.add(new Option(city.name, city.id));
                        });
                        selectDestination.disabled = false;
                    } else {
                        alert('Gagal memuat daftar kota: ' + (data.message || 'Format data tidak valid.'));
                    }
                } catch (error) {
                    console.error('Error loading cities:', error);
                    alert('Terjadi kesalahan saat memuat kota.');
                }
            }

            selectProvince.addEventListener('change', function() {
                const selectedProvinceId = this.value;
                const selectedProvinceName = this.value ? this.options[this.selectedIndex].text : '';
                document.getElementById('provinsi_nama').value = selectedProvinceName;
                if (selectedProvinceId) loadCities(selectedProvinceId);
                else {
                    selectDestination.innerHTML = '<option value="">-- Pilih Kota --</option>';
                    selectDestination.disabled = true;
                }
                resetOngkir();
            });

            selectDestination.addEventListener('change', function() {
                const selectedCityName = this.value ? this.options[this.selectedIndex].text : '';
                document.getElementById('kota_nama').value = selectedCityName;
                resetOngkir();
            });

            selectCourier.addEventListener('change', resetOngkir);

            btnCekOngkir.addEventListener('click', async function() {
                const courier = selectCourier.value;
                const destinationId = selectDestination.value;
                const destinationName = selectDestination.options[selectDestination.selectedIndex].text;
                if (!selectProvince.value || !destinationId || !courier) {
                    alert('Silakan pilih Provinsi, Kota Tujuan, dan Kurir.');
                    return;
                }
                hasilContainer.innerHTML = '<p class="text-gray-500">Mencari ongkir...</p>';
                btnBayar.disabled = true;
                const formData = new FormData();
                formData.append('courier', courier);
                formData.append('destination_id', destinationId);
                formData.append('destination_name', destinationName);
                formData.append('weight', totalBerat);
                try {
                    const response = await fetchWithCsrf(getSecureSiteUrl('pemesanan/cek-ongkir'), {
                        method: 'POST',
                        body: formData
                    });
                    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                    const data = await response.json();
                    if (data.status === 200 && Array.isArray(data.data) && data.data.length > 0) {
                        let html = '<p class="font-bold">Pilih jenis layanan:</p>';
                        data.data.forEach(cost => {
                            const harga = cost.cost;
                            const etd = cost.etd;
                            const namaLayanan = `${cost.name} (${cost.description})`;
                            html += `<label class="flex items-center p-3 my-1 border rounded-md hover:bg-gray-50 cursor-pointer">
                            <input type="radio" name="service" class="form-radio text-teal-600 focus:ring-teal-500" data-cost="${harga}" data-service="${namaLayanan}">
                            <div class="ml-3 text-sm">
                                <span class="font-medium text-gray-900">${namaLayanan}</span>
                                <span class="block text-gray-600">Estimasi: ${etd}</span>
                                <span class="block font-bold text-gray-900">${formatUang(harga)}</span>
                            </div>
                        </label>`;
                        });
                        hasilContainer.innerHTML = html;
                    } else {
                        hasilContainer.innerHTML = `<p class="text-red-600">${data.message || 'Layanan pengiriman tidak ditemukan.'}</p>`;
                    }
                } catch (error) {
                    console.error('Error during ongkir check:', error);
                    hasilContainer.innerHTML = `<p class="text-red-600">Terjadi kesalahan saat memeriksa ongkos kirim.</p>`;
                }
            });

            inputAlamat.addEventListener('input', function() {
                const ongkirDipilih = document.getElementById('ongkir_biaya').value > 0;
                if (this.value.trim() !== '' && ongkirDipilih) btnBayar.disabled = false;
                else btnBayar.disabled = true;
            });

            checkoutForm.addEventListener('submit', function(e) {
                const csrfInput = document.querySelector('input[name="<?= csrf_token() ?>"]');
                console.log(`%cSUBMITTING CHECKOUT FORM`, 'color: red; font-weight: bold;', `With CSRF Token: ${csrfInput.value}`);
            });

            loadProvinces();
        <?php endif; ?>
    });
</script>

<?= $this->endSection() ?>