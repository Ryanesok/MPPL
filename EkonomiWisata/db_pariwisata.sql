-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 11, 2025 at 06:01 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_pariwisata`
--

-- --------------------------------------------------------

--
-- Table structure for table `objek_wisata`
--

CREATE TABLE `objek_wisata` (
  `id` int(11) NOT NULL,
  `nama_wisata` varchar(100) NOT NULL,
  `deskripsi` text NOT NULL,
  `lokasi` varchar(150) NOT NULL,
  `harga_tiket` decimal(10,2) NOT NULL,
  `gambar_url` varchar(255) DEFAULT 'https://placehold.co/600x400/e2e8f0/e2e8f0?text=Gambar'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `objek_wisata`
--

INSERT INTO `objek_wisata` (`id`, `nama_wisata`, `deskripsi`, `lokasi`, `harga_tiket`, `gambar_url`) VALUES
(1, 'Candi Borobudur', 'Candi Buddha terbesar di dunia, sebuah mahakarya arsitektur yang megah dan penuh sejarah.', 'Magelang, Jawa Tengah', 50000.00, 'https://images.unsplash.com/photo-1596422846543-75c6114120e4?q=80&w=1935&auto=format&fit=crop'),
(2, 'Pantai Parangtritis', 'Pantai yang terkenal dengan mitos Nyi Roro Kidul dan pemandangan sunset yang memukau.', 'Bantul, Yogyakarta', 10000.00, 'https://images.unsplash.com/photo-1589667439328-3e4b36c4de2c?q=80&w=1932&auto=format&fit=crop'),
(3, 'Gunung Bromo', 'Gunung berapi aktif yang menawarkan pemandangan kawah dan lautan pasir yang spektakuler saat matahari terbit.', 'Probolinggo, Jawa Timur', 220000.00, 'https://images.unsplash.com/photo-1590240586931-64e7970d4f3b?q=80&w=2070&auto=format&fit=crop');

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan`
--

CREATE TABLE `pemesanan` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `wisata_id` int(11) NOT NULL,
  `kode_booking` varchar(20) NOT NULL,
  `tanggal_kunjungan` date NOT NULL,
  `jumlah_tiket` int(11) NOT NULL,
  `total_harga` decimal(12,2) NOT NULL,
  `status_pembayaran` enum('paid','pending') NOT NULL DEFAULT 'paid',
  `tanggal_pemesanan` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rating_ulasan`
--

CREATE TABLE `rating_ulasan` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `wisata_id` int(11) NOT NULL,
  `rating` tinyint(1) NOT NULL,
  `ulasan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `role` enum('user','admin','dinas') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama_lengkap`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$cGOsPMekPxrZNgZmDaDCnOgJwZzybwLIC9UvHGlmxVyUkQ/Ndxg6C', 'Administrasi', 'admin', '2025-06-11 15:43:40'),
(2, 'dinasjogja', '$2y$10$er5QegEPxQpAcXGyvN7jKOWeFXp4rd4Fg7Kx4C6g1kAoZ1JZ3osyW', 'Dinas jogja', 'dinas', '2025-06-11 15:46:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `objek_wisata`
--
ALTER TABLE `objek_wisata`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_booking` (`kode_booking`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `wisata_id` (`wisata_id`);

--
-- Indexes for table `rating_ulasan`
--
ALTER TABLE `rating_ulasan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `wisata_id` (`wisata_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `objek_wisata`
--
ALTER TABLE `objek_wisata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pemesanan`
--
ALTER TABLE `pemesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rating_ulasan`
--
ALTER TABLE `rating_ulasan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD CONSTRAINT `pemesanan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `pemesanan_ibfk_2` FOREIGN KEY (`wisata_id`) REFERENCES `objek_wisata` (`id`);

--
-- Constraints for table `rating_ulasan`
--
ALTER TABLE `rating_ulasan`
  ADD CONSTRAINT `rating_ulasan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `rating_ulasan_ibfk_2` FOREIGN KEY (`wisata_id`) REFERENCES `objek_wisata` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
