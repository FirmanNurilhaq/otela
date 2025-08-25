<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php
// Set default empty arrays jika variabel tidak ada
$produksi = $produksi ?? [];
$siap_kirim = $siap_kirim ?? [];
$pesanan_berjalan = $pesanan_berjalan ?? [];
$promoAktif = $promoAktif ?? null; // Pastikan variabel promoAktif ada
?>

<main class="max-w-screen-xl mx-auto px-4 py-6">
  <h1 class="text-xl md:text-2xl font-semibold mb-6">Selamat Datang, <?= esc(session('user')['nama_lengkap']) ?>!</h1>

  <?php if (session()->getFlashdata('success')): ?>
    <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
      <?= session()->getFlashdata('success') ?>
    </div>
  <?php elseif (session()->getFlashdata('error')): ?>
    <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
      <?= session()->getFlashdata('error') ?>
    </div>
  <?php endif; ?>


  <?php if (session('user')['role'] === 'pemilik'): ?>

    <section class="mb-10">
      <h2 class="text-lg md:text-xl font-semibold mb-3">Pesanan dalam Produksi</h2>
      <div class="overflow-x-auto rounded shadow bg-white">
        <table class="min-w-full text-sm text-gray-700">
          <thead class="bg-gray-100 text-left whitespace-nowrap">
            <tr>
              <th class="px-4 py-3">Tanggal</th>
              <th class="px-4 py-3">Nama Pembeli</th>
              <th class="px-4 py-3">Detail Produk</th>
              <th class="px-4 py-3">Total Harga</th>
              <th class="px-4 py-3 text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <?php if (empty($produksi)): ?>
              <tr>
                <td colspan="5" class="text-center py-4 text-gray-500">Tidak ada pesanan dalam produksi.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($produksi as $row): ?>
                <tr class="hover:bg-gray-50">
                  <td class="px-4 py-3 align-top"><?= esc(date('d M Y, H:i', strtotime($row['tanggal']))) ?></td>
                  <td class="px-4 py-3 align-top"><?= esc($row['nama_lengkap']) ?></td>
                  <td class="px-4 py-3 align-top">
                    <?php if (!empty($row['items'])): ?>
                      <ul class="list-disc list-inside space-y-1">
                        <?php foreach ($row['items'] as $item): ?>
                          <li><?= esc($item['nama_produk']) ?> (<?= esc($item['ukuran']) ?>gr) - <strong><?= esc($item['jumlah']) ?> pcs</strong></li>
                        <?php endforeach; ?>
                      </ul>
                    <?php endif; ?>
                  </td>
                  <td class="px-4 py-3 align-top">Rp<?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                  <td class="px-4 py-3 text-center align-top">
                    <a href="<?= site_url('pemesanan/ubahStatus/' . $row['id_pemesanan'] . '/siap-kirim') ?>" class="inline-block px-3 py-1 text-xs font-medium bg-yellow-400 text-white rounded hover:bg-yellow-500 transition">Siap Kirim</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>

    <section>
      <h2 class="text-lg md:text-xl font-semibold mb-3">Antrian Pengiriman</h2>
      <div class="overflow-x-auto rounded shadow bg-white">
        <table class="min-w-full text-sm text-gray-700">
          <thead class="bg-gray-100 text-left whitespace-nowrap">
            <tr>
              <th class="px-4 py-3">Tanggal</th>
              <th class="px-4 py-3">Nama Pembeli</th>
              <th class="px-4 py-3">Detail Produk</th>
              <th class="px-4 py-3">Total Harga</th>
              <th class="px-4 py-3 text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <?php if (empty($siap_kirim)): ?>
              <tr>
                <td colspan="5" class="text-center py-4 text-gray-500">Belum ada pesanan yang siap dikirim.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($siap_kirim as $row): ?>
                <tr class="hover:bg-gray-50">
                  <td class="px-4 py-3 align-top"><?= esc(date('d M Y, H:i', strtotime($row['tanggal']))) ?></td>
                  <td class="px-4 py-3 align-top"><?= esc($row['nama_lengkap']) ?></td>
                  <td class="px-4 py-3 align-top">
                    <?php if (!empty($row['items'])): ?>
                      <ul class="list-disc list-inside space-y-1">
                        <?php foreach ($row['items'] as $item): ?>
                          <li><?= esc($item['nama_produk']) ?> (<?= esc($item['ukuran']) ?>gr) - <strong><?= esc($item['jumlah']) ?> pcs</strong></li>
                        <?php endforeach; ?>
                      </ul>
                    <?php endif; ?>
                  </td>
                  <td class="px-4 py-3 align-top">Rp<?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                  <td class="px-4 py-3 text-center align-top">
                    <button
                      data-id="<?= $row['id_pemesanan'] ?>"
                      data-kurir="<?= esc($row['ongkir_layanan']) ?>"
                      class="inputResiBtn inline-block px-3 py-1 text-xs font-medium bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                      Input Resi
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>

  <?php elseif (session('user')['role'] === 'pelanggan'): ?>

    >>>>>>> ff275ed (Updated Revisi Last)
    <div class="mb-6 text-right">
      <a href="<?= site_url('pemesanan') ?>"
        class="inline-block px-5 py-2.5 bg-teal-600 text-white text-sm font-medium rounded-lg shadow-sm transition hover:scale-105 hover:shadow-md hover:bg-teal-700">
        + Buat Pesanan Baru
      </a>
    </div>

    <section class="mb-10">
      <h2 class="text-lg md:text-xl font-semibold mb-3">Pesanan Anda yang Sedang Berjalan</h2>
      <div class="overflow-x-auto rounded shadow bg-white">
        <table class="min-w-full text-sm text-gray-700">
          <thead class="bg-gray-100 text-left whitespace-nowrap">
            <tr>
              <th class="px-4 py-3">Tanggal Pesan</th>
              <th class="px-4 py-3">Detail Produk</th>
              <th class="px-4 py-3 text-right">Rincian Biaya</th>
              <th class="px-4 py-3 text-center">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <?php if (empty($pesanan_berjalan)): ?>
              <!-- AWAL PERUBAHAN: Tampilan untuk pelanggan baru -->
              <tr>
                <td colspan="4" class="text-center p-8 md:p-12">
                  <div class="max-w-lg mx-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-16 w-16 text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <h3 class="mt-4 text-2xl font-bold text-gray-800">Rasakan Renyahnya Keripik Khas Sumedang!</h3>
                    <p class="mt-2 text-gray-600">
                      Selamat datang di Otela! Kami adalah UMKM lokal dari Sumedang yang menghadirkan keripik singkong premium dengan aneka rasa yang menggugah selera. Setiap gigitan adalah perpaduan sempurna antara renyah dan bumbu khas yang akan membuatmu ketagihan.
                    </p>
                    <p class="mt-4 font-medium text-gray-700">
                      Siap untuk petualangan rasa? Klik tombol <strong class="text-teal-600">'+ Buat Pesanan Baru'</strong> di atas untuk mulai memilih keripik favoritmu!
                    </p>
                  </div>
                </td>
              </tr>
              <!-- AKHIR PERUBAHAN -->
            <?php else: ?>
              <?php foreach ($pesanan_berjalan as $row): ?>
                <tr class="hover:bg-gray-50">
                  <td class="px-4 py-3 align-top whitespace-nowrap"><?= esc(date('d M Y', strtotime($row['tanggal']))) ?></td>
                  <td class="px-4 py-3 align-top">
                    <?php if (!empty($row['items'])): ?>
                      <ul class="list-disc list-inside space-y-1">
                        <?php foreach ($row['items'] as $item): ?>
                          <li>
                            <?= esc($item['nama_produk']) ?> (<?= esc($item['ukuran']) ?>gr) - <strong><?= esc($item['jumlah']) ?> pcs</strong>
                          </li>
                        <?php endforeach; ?>
                      </ul>
                    <?php endif; ?>
                  </td>
                  <td class="px-4 py-3 align-top text-right">
                    <div class="flex flex-col">
                      <span>Subtotal: Rp<?= number_format(($row['total_harga'] + $row['diskon']) - $row['ongkir_biaya'], 0, ',', '.') ?></span>
                      <?php if ($row['diskon'] > 0): ?>
                        <span class="text-green-600">Diskon: -Rp<?= number_format($row['diskon'], 0, ',', '.') ?></span>
                      <?php endif; ?>
                      <span>Ongkir: Rp<?= number_format($row['ongkir_biaya'], 0, ',', '.') ?></span>
                      <span class="font-bold border-t mt-1 pt-1">Total: Rp<?= number_format($row['total_harga'], 0, ',', '.') ?></span>
                    </div>
                  </td>
                  <td class="px-4 py-3 text-center align-top">
                    <?php
                    $statusClass = 'bg-gray-100 text-gray-800';
                    if ($row['status'] == 'produksi') {
                      $statusClass = 'bg-blue-100 text-blue-800';
                    } elseif ($row['status'] == 'siap kirim') {
                      $statusClass = 'bg-yellow-100 text-yellow-800';
                    }
                    ?>
                    <span class="px-3 py-1 text-xs font-medium rounded-full <?= $statusClass ?>">
                      <?= ucwords(esc($row['status'])) ?>
                    </span>

                    <?php if (!empty($row['estimasi_selesai'])): ?>
                      <p class="text-xs text-gray-500 mt-2 italic">
                        Estimasi selesai:<br>
                        <span class="font-semibold"><?= date('d M Y', strtotime($row['estimasi_selesai'])) ?></span>
                      </p>
                    <?php endif; ?>
                    <<<<<<< HEAD=======<?php
                                        $namaUser = session('user')['nama_lengkap'];
                                        $tanggalPesan = date('d M Y', strtotime($row['tanggal']));

                                        // Membangun daftar item
                                        $itemDetails = "";
                                        if (!empty($row['items'])) {
                                          foreach ($row['items'] as $item) {
                                            // PHP_EOL adalah karakter baris baru agar rapi di WhatsApp
                                            $itemDetails .= "- " . esc($item['nama_produk']) . " (" . esc($item['ukuran']) . "gr) - " . esc($item['jumlah']) . " pcs" . PHP_EOL;
                                          }
                                        }

                                        // Membuat pesan default yang lebih detail
                                        $pesanWhatsApp = "Halo Otela, saya " . $namaUser . "." . PHP_EOL . PHP_EOL;
                                        $pesanWhatsApp .= "Saya ingin bertanya mengenai pesanan saya pada tanggal " . $tanggalPesan . " dengan rincian sebagai berikut:" . PHP_EOL;
                                        $pesanWhatsApp .= $itemDetails;

                                        // Buat link WhatsApp
                                        $linkWhatsApp = "https://wa.me/6282144696226?text=" . urlencode(trim($pesanWhatsApp));
                                        ?>

                      <a href="<?= $linkWhatsApp ?>" target="_blank" class="mt-3 inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1.5 fill-current" viewBox="0 0 448 512">
                        <path d="M380.9 97.1C339 55.1 283.2 32 224.3 32 100.3 32 0 132.3 0 256c0 45 11.7 88.7 34 127.3L2.3 480l100.7-31.7c36.6 19.7 77.6 30.1 121.3 30.1 123.9 0 224.3-100.3 224.3-224.3 0-59-23-114.8-65.1-156.9zM224.3 438.7c-38.5 0-76.1-10.4-108.9-30.1l-7.8-4.6-59.8 18.8 19.6-58.1-5.1-8.1C43.2 325.3 32 291.4 32 256c0-106.1 86.2-192.3 192.3-192.3 51.3 0 99.5 20 135.8 56.3 36.2 36.2 56.3 84.5 56.3 135.8 0 106.1-86.2 192.3-192.4 192.3zm101.1-138.4c-5.5-2.8-32.7-16.1-37.8-17.9-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.5-14.3 17.9-17.5 21.6-3.2 3.7-6.4 4.2-11.9 1.4-32.5-16.2-53.7-28.9-75.2-65.3-5.7-9.8 5.7-9.1 16.2-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9s-19.4 19-19.4 46.3 19.9 53.7 22.7 57.3c2.8 3.7 39.2 59.7 95 83.7 13.3 5.7 23.6 9.1 31.7 11.7 13.3 4.2 25.4 3.6 35 2.2 10.7-1.6 32.7-13.4 37.3-26.3 4.6-13 4.6-24.1 3.2-26.3-1.3-2.1-5.1-3.4-10.6-6.2z" />
                      </svg>
                      Tanya Pesanan
                      </a>
                      >>>>>>> ff275ed (Updated Revisi Last)
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  <?php endif; ?>
</main>


>>>>>>> ff275ed (Updated Revisi Last)
<?php if (session()->getFlashdata('show_welcome_modal')): ?>
  <div id="welcomeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl p-8 w-full max-w-md text-center transform transition-all scale-95 opacity-0 animate-fade-in-up">
      <h3 class="text-2xl font-bold text-teal-600 mb-2">Selamat Datang! 👋</h3>
      <p class="text-lg text-gray-800 mb-4">Halo, <span class="font-semibold"><?= esc(session('user')['nama_lengkap']) ?></span>!</p>
      <p class="text-gray-600 mb-6">Senang melihat Anda kembali di dashboard.</p>
      <button id="closeWelcomeModalBtn" class="px-6 py-2 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
        Lanjutkan
      </button>
    </div>
  </div>
<?php endif; ?>

<?php if (session()->has('user') && session('user')['role'] === 'pelanggan' && !session()->has('promo_modal_shown') && isset($promoAktif) && $promoAktif): ?>
  <div id="promoModal" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-[100] opacity-0 transition-opacity duration-300">
    <div class="relative bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md text-center transform scale-95 transition-all duration-300 overflow-hidden">
      <div id="confetti-container" class="absolute top-0 left-0 w-full h-full pointer-events-none"></div>

      <div class="mx-auto mb-4 w-24 h-24 flex items-center justify-center bg-gradient-to-br from-yellow-300 to-orange-400 rounded-full shadow-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-white drop-shadow-lg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
        </svg>
      </div>

      <h3 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-red-500 to-orange-500 mb-2 animate-pulse"><?= esc($promoAktif['judul_promo']) ?></h3>
      <p class="text-lg text-gray-800 font-semibold mb-6"><?= esc($promoAktif['deskripsi_promo']) ?></p>

      <div class="flex flex-col gap-3">
        <a href="<?= site_url('pemesanan') ?>" class="w-full px-6 py-3 bg-gradient-to-r from-teal-500 to-cyan-600 text-white font-bold rounded-lg shadow-lg transform transition-transform hover:scale-105 animate-pulse-slow">
          GAS, BELANJA SEKARANG!
        </a>
        <button id="closePromoModalBtn" class="w-full px-6 py-2 text-sm text-gray-500 hover:text-gray-800 transition">
          Lain Kali Aja
        </button>
      </div>
    </div>
  </div>
  <?php session()->set('promo_modal_shown', true); // Tandai bahwa modal sudah ditampilkan 
  ?>
<?php endif; ?>
<div id="resiModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
  <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
    <h3 class="text-xl font-semibold mb-4">Input Nomor Resi</h3>
    <form id="resiForm" action="<?= site_url('pemesanan/selesaikanPesanan') ?>" method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="id_pemesanan" id="modal_id_pemesanan">
      <input type="hidden" name="kurir" id="modal_kurir_hidden">
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Kurir</label>
        <p id="modal_kurir_display" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md text-gray-600"></p>
      </div>
      <div class="mb-4">
        <label for="nomor_resi" class="block text-sm font-medium text-gray-700 mb-1">Nomor Resi Pengiriman</label>
        <input type="text" name="nomor_resi" id="nomor_resi" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan nomor resi..." required>
      </div>
      <div class="flex justify-end space-x-3">
        <button type="button" id="closeModalBtn" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Batal</button>
        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">Simpan & Selesaikan</button>
      </div>
    </form>
  </div>
</div>

<style>
  @keyframes fade-in-up {
    from {
      opacity: 0;
      transform: scale(0.95) translateY(10px);
    }

    to {
      opacity: 1;
      transform: scale(1) translateY(0);
    }
  }

  .animate-fade-in-up {
    animation: fade-in-up 0.3s ease-out forwards;
  }

  @keyframes pulse-slow {

    0%,
    100% {
      transform: scale(1);
      box-shadow: 0 0 0 0 rgba(20, 184, 166, 0.7);
    }

    50% {
      transform: scale(1.05);
      box-shadow: 0 0 0 10px rgba(20, 184, 166, 0);
    }
  }

  .animate-pulse-slow {
    animation: pulse-slow 2s infinite;
  }

  .confetti {
    position: absolute;
    width: 8px;
    height: 16px;
    top: -20px;
    opacity: 0;
  }

  @keyframes confetti-fall {
    0% {
      top: -20px;
      opacity: 1;
      transform: rotate(0deg);
    }

    100% {
      top: 100%;
      opacity: 1;
      transform: rotate(720deg);
    }
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Logika untuk Modal Selamat Datang
    const welcomeModal = document.getElementById('welcomeModal');
    if (welcomeModal) {
      const closeWelcomeModalBtn = document.getElementById('closeWelcomeModalBtn');

      function closeWelcomeModal() {
        welcomeModal.classList.add('hidden');
      }
      closeWelcomeModalBtn.addEventListener('click', closeWelcomeModal);
      welcomeModal.addEventListener('click', function(event) {
        if (event.target === welcomeModal) {
          closeWelcomeModal();
        }
      });
    }

    // Logika untuk Modal Promo Heboh
    const promoModal = document.getElementById('promoModal');
    if (promoModal) {
      const closePromoModalBtn = document.getElementById('closePromoModalBtn');
      const modalContent = promoModal.querySelector('div > div');

      function createConfetti() {
        const container = document.getElementById('confetti-container');
        if (!container) return;
        const colors = ['#fde047', '#f97316', '#ef4444', '#22c55e', '#3b82f6'];
        for (let i = 0; i < 100; i++) {
          const confetti = document.createElement('div');
          confetti.classList.add('confetti');
          confetti.style.left = Math.random() * 100 + '%';
          confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
          confetti.style.animation = `confetti-fall ${2 + Math.random() * 3}s linear ${Math.random() * 2}s infinite`;
          container.appendChild(confetti);
        }
      }

      setTimeout(() => {
        promoModal.classList.remove('opacity-0');
        modalContent.classList.remove('scale-95');
        createConfetti();
      }, 500); // Tampilkan setelah 0.5 detik

      function closePromoModal() {
        promoModal.classList.add('opacity-0');
        setTimeout(() => {
          promoModal.style.display = 'none';
        }, 300);
      }

      closePromoModalBtn.addEventListener('click', closePromoModal);
    }

    // Logika untuk Modal Input Resi
    const resiModal = document.getElementById('resiModal');
    if (resiModal) {
      const closeModalBtn = document.getElementById('closeModalBtn');
      const resiForm = document.getElementById('resiForm');
      const modalIdPemesananInput = document.getElementById('modal_id_pemesanan');
      const modalKurirHiddenInput = document.getElementById('modal_kurir_hidden');
      const modalKurirDisplay = document.getElementById('modal_kurir_display');
      const inputResiButtons = document.querySelectorAll('.inputResiBtn');

      function openModal(idPemesanan, namaKurir) {
        modalIdPemesananInput.value = idPemesanan;
        modalKurirHiddenInput.value = namaKurir;
        modalKurirDisplay.textContent = namaKurir;
        resiModal.classList.remove('hidden');
        document.getElementById('nomor_resi').focus();
      }

      function closeModal() {
        resiModal.classList.add('hidden');
        resiForm.reset();
      }
      inputResiButtons.forEach(button => {
        button.addEventListener('click', function() {
          openModal(this.getAttribute('data-id'), this.getAttribute('data-kurir'));
        });
      });
      closeModalBtn.addEventListener('click', closeModal);
      resiModal.addEventListener('click', function(event) {
        if (event.target === resiModal) {
          closeModal();
        }
      });
    }
  });
</script>
<?= $this->endSection() ?>