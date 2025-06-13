-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 13, 2025 at 09:45 AM
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
  `gambar_url` varchar(255) DEFAULT 'https://placehold.co/600x400/e2e8f0/e2e8f0?text=Gambar',
  `kuota_harian` int(11) DEFAULT NULL COMMENT 'Kuota tiket per hari, NULL berarti tidak terbatas',
  `jadwal_operasional` text DEFAULT NULL COMMENT 'Menyimpan jadwal dalam format JSON'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `objek_wisata`
--

INSERT INTO `objek_wisata` (`id`, `nama_wisata`, `deskripsi`, `lokasi`, `harga_tiket`, `gambar_url`, `kuota_harian`, `jadwal_operasional`) VALUES
(1, 'Candi Borobudur', 'Candi Buddha terbesar di dunia, sebuah mahakarya arsitektur yang megah dan penuh sejarah.', 'Magelang, Jawa Tengah', 50000.00, 'https://cpanel-blog.smsperkasa.com/wp-content/uploads/2023/09/Tata-Letak-dan-Bentuk-Candi-Borobudur-1024x683.jpg', 500, '{\"days\":\"setiap_hari\",\"open\":\"08:00\",\"close\":\"17:00\"}'),
(2, 'Pantai Parangtritis', 'Pantai yang terkenal dengan mitos Nyi Roro Kidul dan pemandangan sunset yang memukau.', 'Bantul, Yogyakarta', 10000.00, 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c0/Parangtritis_Beach_2011_2.JPG/960px-Parangtritis_Beach_2011_2.JPG', 100, '{\"days\":\"sabtu_minggu\",\"open\":\"08:00\",\"close\":\"17:00\"}'),
(3, 'Gunung Bromo', 'Gunung berapi aktif yang menawarkan pemandangan kawah dan lautan pasir yang spektakuler saat matahari terbit.', 'Probolinggo, Jawa Timur', 220000.00, 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/44/Mount_Bromo_East_Java.jpg/960px-Mount_Bromo_East_Java.jpg', 100, '{\"days\":\"sabtu_minggu\",\"open\":\"08:00\",\"close\":\"17:00\"}'),
(4, 'Raja Ampat', 'Raja Ampat merupakan salah satu objek wisata Indonesia yang mendunia dan diakui Unesco.', 'Raja Ampat, Papua Barat', 250000.00, 'https://akcdn.detik.net.id/community/media/visual/2021/11/08/piaynemo-dan-telaga-bintang-raja-ampat-5_169.jpeg?w=700&q=90', 200, '{\"days\":\"setiap_hari\",\"open\":\"08:00\",\"close\":\"17:00\"}');

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

--
-- Dumping data for table `pemesanan`
--

INSERT INTO `pemesanan` (`id`, `user_id`, `wisata_id`, `kode_booking`, `tanggal_kunjungan`, `jumlah_tiket`, `total_harga`, `status_pembayaran`, `tanggal_pemesanan`) VALUES
(1, 3, 1, 'TIX-0C4F22', '2025-06-16', 3, 150000.00, 'paid', '2025-06-12 11:00:16'),
(2, 1, 2, 'TIX-60F5DF', '2025-06-22', 1, 10000.00, 'paid', '2025-06-12 11:01:58'),
(3, 3, 2, 'TIX-D62FA8', '2025-06-15', 2, 20000.00, 'paid', '2025-06-12 13:29:17'),
(4, 4, 2, 'TIX-8369D8', '2025-06-14', 1, 10000.00, 'paid', '2025-06-12 13:35:52'),
(5, 4, 4, 'TIX-57CCA3', '2025-06-29', 1, 250000.00, 'paid', '2025-06-12 13:36:05'),
(6, 3, 1, 'TIX-B8B4AD', '2025-06-13', 1, 50000.00, 'paid', '2025-06-12 15:42:51'),
(7, 3, 3, 'TIX-9F3F7B', '2025-06-21', 1, 220000.00, 'paid', '2025-06-12 15:43:05'),
(8, 3, 1, 'TIX-34125E', '2025-06-13', 5, 250000.00, 'paid', '2025-06-12 15:43:47'),
(9, 3, 1, 'TIX-F6C785', '2025-06-18', 1, 50000.00, 'paid', '2025-06-12 17:25:51'),
(10, 3, 1, 'TIX-BB0119', '2025-06-15', 5, 250000.00, 'paid', '2025-06-13 07:04:59');

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
(2, 'dinasjogja', '$2y$10$er5QegEPxQpAcXGyvN7jKOWeFXp4rd4Fg7Kx4C6g1kAoZ1JZ3osyW', 'Dinas jogja', 'dinas', '2025-06-11 15:46:35'),
(3, 'ryanesok', '$2y$10$O5UOcF5x/7kDj3rDbDSWneNQn9u6Re5pVIPl4auCEKgDae6yiM7Oq', 'Maulana Zakki ', 'user', '2025-06-12 11:00:01'),
(4, 'dummy1', '$2y$10$o8oa0qy.cR4QSB1vVCCr3eSSAATA/E0X8rHjoMoO9d7j0tnfz1bjm', 'dummy1', 'user', '2025-06-12 13:35:05'),
(5, 'Galih', '$2y$10$cbLy/xs7axDMZnmpSchRfeXB07V6ReZYgkelaTKP23ZVlQa/FsuZO', 'Galih', 'user', '2025-06-13 07:07:51');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pemesanan`
--
ALTER TABLE `pemesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `rating_ulasan`
--
ALTER TABLE `rating_ulasan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
