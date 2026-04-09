-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 09, 2026 at 04:44 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ukk_3_reifanazkia`
--

-- --------------------------------------------------------

--
-- Table structure for table `aspirasi`
--

CREATE TABLE `aspirasi` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `kelas` varchar(50) NOT NULL,
  `id_categori` int NOT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `pesan` text NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `status` enum('menunggu','diproses','selesai','ditolak') DEFAULT 'menunggu',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `aspirasi`
--

INSERT INTO `aspirasi` (`id`, `user_id`, `nama_lengkap`, `kelas`, `id_categori`, `judul`, `pesan`, `foto`, `status`, `created_at`) VALUES
(39, 12, 'Reifan azkia', 'XII RPL 4', 5, 'Penerangan Parkir', 'Lampu di area parkir motor belakang redup, mohon diganti demi keamanan.', '1775706989_4155160562.jpg', 'selesai', '2026-04-09 03:56:29'),
(40, 10, 'Siti Aminah', 'XII RPL 3', 3, 'Akses Buku Digital', 'Mohon diperbanyak koleksi buku digital di perpustakaan sekolah.', '1775707248_2876249b-2b28-4896-96c5-684c984e7a77.jpg', 'menunggu', '2026-04-09 04:00:47');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `nama_kategori`, `created_at`) VALUES
(1, 'Fasilitas', '2026-01-22 01:38:51'),
(3, 'Pembelajaran', '2026-03-27 17:31:27'),
(5, 'Keamanan Sekolah', '2026-04-09 03:03:24');

-- --------------------------------------------------------

--
-- Table structure for table `progress_perbaikan`
--

CREATE TABLE `progress_perbaikan` (
  `id` int NOT NULL,
  `aspirasi_id` int NOT NULL,
  `keterangan` text NOT NULL,
  `estimasi_selesai` date DEFAULT NULL,
  `tanggal` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `progress_perbaikan`
--

INSERT INTO `progress_perbaikan` (`id`, `aspirasi_id`, `keterangan`, `estimasi_selesai`, `tanggal`, `foto`) VALUES
(5, 39, 'Kami sedang membenarkan lampu yang mati pada area parkiran', '2026-04-10', '2026-04-09 04:17:57', '1775708277_download (2).jpg');

-- --------------------------------------------------------

--
-- Table structure for table `umpan_balik`
--

CREATE TABLE `umpan_balik` (
  `id` int NOT NULL,
  `aspirasi_id` int NOT NULL,
  `tanggapan` text NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `umpan_balik`
--

INSERT INTO `umpan_balik` (`id`, `aspirasi_id`, `tanggapan`, `created_at`) VALUES
(12, 39, 'Kami akan segera memperbaikinya, trimakasih atas aspirasi yang telah anda sampaikan', '2026-04-09 11:39:16'),
(13, 39, 'Lampu pada arena parkir sudah di perbaiki, trimkasih', '2026-04-09 11:39:50');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `nis` int NOT NULL,
  `nama_lengkap` varchar(50) NOT NULL,
  `email` varchar(55) NOT NULL,
  `kelas` varchar(50) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nis`, `nama_lengkap`, `email`, `kelas`, `username`, `password`, `role`) VALUES
(5, 123456, 'admin', 'admin@gmail.com', 'xii rpl 2', 'admin', '$2y$10$vi/mlpxtu8N5aGLYtnyOu.pd27UE7EQcZtgXA9Ezir7uAKrOWGLeK', 'admin'),
(6, 123456, 'user', 'user@gmail.com', 'xii rpl 2', 'user', '$2y$10$Vh9DaTLhmoWr7uDwjPeqMeD7GD3SPOBpR0aFk2zng8.8bsbfak8kS', 'user'),
(10, 39494955, 'Siti Aminah', 'siti@gmail.com', 'XII RPL 3', 'sitiaminah', '$2y$10$xTtS.PMrmfH.kdCPgB8nten6ib5ps7k0aZMmjxgmD4X49yyZy8wem', 'user'),
(11, 34516789, 'Budi Santoso', 'budi@gmail.com', 'XII RPL 3', 'budisantoso', '$2y$10$I5GQ/bdjpeklf4omTluTA.jqMTdBlphbZ9wd4Hbt2HTbbdkAugLsu', 'user'),
(12, 3456178, 'Reifan azkia', 'reifanazkia@gmail.com', 'XII RPL 4', 'reifanazkia', '$2y$10$Si7NFpSA9Vj7CnDi.MNf/OQywgZJK/PQPMf71cLqRj5KXwwLI3NNO', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aspirasi`
--
ALTER TABLE `aspirasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_aspirasi_kategori` (`id_categori`),
  ADD KEY `fk_user` (`user_id`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `progress_perbaikan`
--
ALTER TABLE `progress_perbaikan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aspirasi_id` (`aspirasi_id`);

--
-- Indexes for table `umpan_balik`
--
ALTER TABLE `umpan_balik`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aspirasi_id` (`aspirasi_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aspirasi`
--
ALTER TABLE `aspirasi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `progress_perbaikan`
--
ALTER TABLE `progress_perbaikan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `umpan_balik`
--
ALTER TABLE `umpan_balik`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `aspirasi`
--
ALTER TABLE `aspirasi`
  ADD CONSTRAINT `fk_aspirasi_kategori` FOREIGN KEY (`id_categori`) REFERENCES `kategori` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `progress_perbaikan`
--
ALTER TABLE `progress_perbaikan`
  ADD CONSTRAINT `progress_perbaikan_ibfk_1` FOREIGN KEY (`aspirasi_id`) REFERENCES `aspirasi` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `umpan_balik`
--
ALTER TABLE `umpan_balik`
  ADD CONSTRAINT `umpan_balik_ibfk_1` FOREIGN KEY (`aspirasi_id`) REFERENCES `aspirasi` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
