-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 14, 2025 at 12:28 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `otela`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_pemesanan`
--

CREATE TABLE `detail_pemesanan` (
  `id_detail` int(11) NOT NULL,
  `id_pemesanan` int(11) DEFAULT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `subtotal` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan`
--

CREATE TABLE `pemesanan` (
  `id_pemesanan` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nama_pembeli` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `no_telp` varchar(20) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `id_produk` int(11) NOT NULL,
  `harga` int(11) DEFAULT NULL,
  `total_harga` int(11) DEFAULT NULL,
  `status` enum('produksi','siap kirim','selesai') DEFAULT 'produksi'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pemesanan`
--

INSERT INTO `pemesanan` (`id_pemesanan`, `id_user`, `nama_pembeli`, `email`, `no_telp`, `tanggal`, `id_produk`, `harga`, `total_harga`, `status`) VALUES
(1, 3, 'a', 'a@a', '1', '2025-06-11', 6, 21000, 525000, 'produksi'),
(2, 3, 'a', 'a@a', '1', '2025-06-11', 2, 18000, 306000, 'produksi'),
(3, 3, 'a', 'a@a', '1', '2025-06-11', 4, 20000, 400000, 'produksi');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(11) NOT NULL,
  `nama_produk` varchar(100) DEFAULT NULL,
  `ukuran` int(11) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `nama_produk`, `ukuran`, `harga`) VALUES
(1, 'Keripik Otela Original', 500, 10000),
(2, 'Keripik Otela Original', 1000, 18000),
(3, 'Keripik Otela Pedas', 500, 12000),
(4, 'Keripik Otela Pedas', 1000, 20000),
(5, 'Keripik Otela Coklat', 500, 13000),
(6, 'Keripik Otela Coklat', 1000, 21000);

-- --------------------------------------------------------

--
-- Table structure for table `resep_produk`
--

CREATE TABLE `resep_produk` (
  `id_produk` int(11) NOT NULL,
  `id_bahan` int(11) NOT NULL,
  `jumlah` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resep_produk`
--

INSERT INTO `resep_produk` (`id_produk`, `id_bahan`, `jumlah`) VALUES
(1, 1, 500),
(1, 2, 150),
(1, 3, 50),
(1, 4, 100),
(2, 1, 1000),
(2, 2, 250),
(2, 3, 100),
(2, 4, 200),
(3, 1, 500),
(3, 2, 150),
(3, 3, 50),
(3, 4, 100),
(3, 6, 150),
(4, 1, 1000),
(4, 2, 250),
(4, 3, 100),
(4, 4, 200),
(4, 6, 200),
(5, 1, 500),
(5, 4, 80),
(5, 5, 200),
(6, 1, 500),
(6, 4, 100),
(6, 5, 400);

-- --------------------------------------------------------

--
-- Table structure for table `stokbahan`
--

CREATE TABLE `stokbahan` (
  `id_bahan` int(50) NOT NULL,
  `nama_bahan` varchar(50) NOT NULL,
  `jumlah` int(255) NOT NULL,
  `status` enum('Tersedia','Hampir Habis','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stokbahan`
--

INSERT INTO `stokbahan` (`id_bahan`, `nama_bahan`, `jumlah`, `status`) VALUES
(1, 'Singkong', 70500, 'Tersedia'),
(2, 'Daun Bawang', 750, 'Hampir Habis'),
(3, 'Cikur', 6300, 'Tersedia'),
(4, 'Garam', 100, 'Hampir Habis'),
(5, 'Coklat', 2001, 'Tersedia'),
(6, 'Cabai', 6000, 'Tersedia'),
(8, 'matcha', 8000, 'Tersedia');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(255) NOT NULL,
  `nama_lengkap` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `no_telp` int(20) NOT NULL,
  `role` enum('pemilik','pelanggan') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `nama_lengkap`, `email`, `password`, `no_telp`, `role`) VALUES
(1, 'man', 'man@mail.com', '202cb962ac59075b964b07152d234b70', 123, 'pemilik'),
(2, 'man', 'man@mail.com', '202cb962ac59075b964b07152d234b70', 123, 'pemilik'),
(3, 'a', 'a@a', '0cc175b9c0f1b6a831c399e269772661', 1, 'pelanggan'),
(4, 'z', 'z@z', 'fbade9e36a3f36d3d676c1b808451dd7', 1, 'pelanggan');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_pemesanan`
--
ALTER TABLE `detail_pemesanan`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_pemesanan` (`id_pemesanan`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indexes for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD PRIMARY KEY (`id_pemesanan`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`);

--
-- Indexes for table `resep_produk`
--
ALTER TABLE `resep_produk`
  ADD PRIMARY KEY (`id_produk`,`id_bahan`),
  ADD KEY `id_bahan` (`id_bahan`);

--
-- Indexes for table `stokbahan`
--
ALTER TABLE `stokbahan`
  ADD PRIMARY KEY (`id_bahan`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_pemesanan`
--
ALTER TABLE `detail_pemesanan`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pemesanan`
--
ALTER TABLE `pemesanan`
  MODIFY `id_pemesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `stokbahan`
--
ALTER TABLE `stokbahan`
  MODIFY `id_bahan` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_pemesanan`
--
ALTER TABLE `detail_pemesanan`
  ADD CONSTRAINT `detail_pemesanan_ibfk_1` FOREIGN KEY (`id_pemesanan`) REFERENCES `pemesanan` (`id_user`),
  ADD CONSTRAINT `detail_pemesanan_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`);

--
-- Constraints for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD CONSTRAINT `pemesanan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `pemesanan_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`);

--
-- Constraints for table `resep_produk`
--
ALTER TABLE `resep_produk`
  ADD CONSTRAINT `resep_produk_ibfk_1` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`),
  ADD CONSTRAINT `resep_produk_ibfk_2` FOREIGN KEY (`id_bahan`) REFERENCES `stokbahan` (`id_bahan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
