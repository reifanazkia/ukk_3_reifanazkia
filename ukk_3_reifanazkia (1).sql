-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 31, 2026 at 09:24 AM
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
-- Database: `ukk_3_reifanazkia`
--

-- --------------------------------------------------------

--
-- Table structure for table `aspirasi`
--

CREATE TABLE `aspirasi` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `kelas` varchar(50) NOT NULL,
  `id_categori` int(11) NOT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `pesan` text NOT NULL,
  `status` enum('menunggu','diproses','selesai','ditolak') DEFAULT 'menunggu',
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `aspirasi`
--

INSERT INTO `aspirasi` (`id`, `user_id`, `nama_lengkap`, `kelas`, `id_categori`, `judul`, `pesan`, `status`, `foto`, `created_at`) VALUES
(20, 6, 'trtrtr', 'xii rpl 2', 1, 'grgrgrgr', 'dwdwdw', 'menunggu', '1774927345_Screenshot 2026-03-09 094327.png', '2026-03-31 03:22:25'),
(21, 6, 'rerere', 'xii rpl 2', 1, 'dedede', 'dedededede', 'menunggu', '1774927810_Screenshot (8).png', '2026-03-31 03:30:10'),
(22, 6, 'trtrtrt', 'xii rpl 2', 1, 'trtrtr', 'hyhyhyhy', 'selesai', '1774927835_Screenshot (13).png', '2026-03-31 03:30:35'),
(23, 9, 'agus laper', '12 RPL 3', 1, 'dedede', 'agus laper banget', 'menunggu', '1774932912_Screenshot 2026-03-31 084844.png', '2026-03-31 04:55:12');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `nama_kategori`, `created_at`) VALUES
(1, 'Fasilitas', '2026-01-22 01:38:51');

-- --------------------------------------------------------

--
-- Table structure for table `progress_perbaikan`
--

CREATE TABLE `progress_perbaikan` (
  `id` int(11) NOT NULL,
  `aspirasi_id` int(11) NOT NULL,
  `keterangan` text NOT NULL,
  `estimasi_selesai` date DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `tanggal` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `progress_perbaikan`
--

INSERT INTO `progress_perbaikan` (`id`, `aspirasi_id`, `keterangan`, `estimasi_selesai`, `foto`, `tanggal`) VALUES
(1, 22, 'perbaikan sedang dalam proses mesangan komponen komponen baru', '2026-04-01', '1774929271_Screenshot 2026-03-31 084844.png', '2026-03-31 03:54:31'),
(2, 23, 'sedang di pasang kan part part baru', '2026-04-11', '1774933255_Screenshot 2026-03-31 084844.png', '2026-03-31 05:00:55');

-- --------------------------------------------------------

--
-- Table structure for table `umpan_balik`
--

CREATE TABLE `umpan_balik` (
  `id` int(11) NOT NULL,
  `aspirasi_id` int(11) NOT NULL,
  `tanggapan` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `umpan_balik`
--

INSERT INTO `umpan_balik` (`id`, `aspirasi_id`, `tanggapan`, `created_at`) VALUES
(7, 22, 'akan segera di perbaiki', '2026-03-31 10:31:30'),
(8, 22, 'sedang di proses', '2026-03-31 10:31:41'),
(9, 22, 'perbaikan fasilitas telah selesai', '2026-03-31 11:34:09'),
(10, 23, 'swsws', '2026-03-31 14:09:01'),
(11, 23, 'swsws', '2026-03-31 14:09:08'),
(12, 23, 'dededee', '2026-03-31 14:09:16');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nis` int(11) NOT NULL,
  `nama_lengkap` varchar(50) NOT NULL,
  `email` varchar(55) NOT NULL,
  `kelas` varchar(50) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nis`, `nama_lengkap`, `email`, `kelas`, `username`, `password`, `role`) VALUES
(5, 123456, 'admin', 'admin@gmail.com', 'xii rpl 2', 'admin', '$2y$10$vi/mlpxtu8N5aGLYtnyOu.pd27UE7EQcZtgXA9Ezir7uAKrOWGLeK', 'admin'),
(6, 123456, 'user', 'user@gmail.com', 'xii rpl 2', 'user', '$2y$10$Vh9DaTLhmoWr7uDwjPeqMeD7GD3SPOBpR0aFk2zng8.8bsbfak8kS', 'user'),
(7, 8964839, 'ujang', 'rei@gmail.com', 'xii rpl 2', 'wawan', '$2y$10$QGNDxbid960VKAfUACp/ge9VyQYyGq/RdKWPSsMWQ.zoVILRgor66', 'user'),
(8, 896483956, 'anhar', 'anhar@gmail.com', 'xii rpl 2', 'anhar', '$2y$10$PL0xR9VQFRyY9CGsOm6neOMcrUaY7HwxDmeZAn4KxyOPMSJeAl8tG', 'user'),
(9, 102302501, 'agus laper', 'reifanazkia@gmail.com', 'xii rpl 2', 'user1', '$2y$10$h0NH/odcxh2cPxYeppYV4.IzDT/upssahDQhkSkY0faDGceZW.Bwa', 'user');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `progress_perbaikan`
--
ALTER TABLE `progress_perbaikan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `umpan_balik`
--
ALTER TABLE `umpan_balik`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
  ADD CONSTRAINT `progress_perbaikan_ibfk_1` FOREIGN KEY (`aspirasi_id`) REFERENCES `aspirasi` (`id`);

--
-- Constraints for table `umpan_balik`
--
ALTER TABLE `umpan_balik`
  ADD CONSTRAINT `umpan_balik_ibfk_1` FOREIGN KEY (`aspirasi_id`) REFERENCES `aspirasi` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
