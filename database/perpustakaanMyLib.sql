-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 23, 2026 at 12:44 AM
-- Server version: 8.0.46
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `perpustakaan_final`
--

-- --------------------------------------------------------

--
-- Table structure for table `eksemplar_buku`
--

CREATE TABLE `eksemplar_buku` (
  `id` int NOT NULL,
  `judul_id` int NOT NULL,
  `kode_buku` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('Tersedia','Dipinjam','Rusak','Hilang') COLLATE utf8mb4_general_ci DEFAULT 'Tersedia',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `eksemplar_buku`
--

INSERT INTO `eksemplar_buku` (`id`, `judul_id`, `kode_buku`, `status`, `created_at`) VALUES
(1, 2, '001', 'Tersedia', '2026-08-04 14:03:05'),
(3, 2, '002', 'Tersedia', '2026-08-06 04:38:34'),
(4, 2, '003', 'Tersedia', '2026-08-06 04:38:40'),
(5, 4, '010', 'Tersedia', '2026-08-09 11:27:19');

-- --------------------------------------------------------

--
-- Table structure for table `judul_buku`
--

CREATE TABLE `judul_buku` (
  `id` int NOT NULL,
  `judul` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `penulis` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `penerbit` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `tahun` year NOT NULL,
  `isbn` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kategori` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rak` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sinopsis` text COLLATE utf8mb4_general_ci,
  `cover` varchar(255) COLLATE utf8mb4_general_ci DEFAULT 'default-book.png',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `judul_buku`
--

INSERT INTO `judul_buku` (`id`, `judul`, `penulis`, `penerbit`, `tahun`, `isbn`, `kategori`, `rak`, `sinopsis`, `cover`, `created_at`) VALUES
(2, 'Seporsi Mie Ayam Sebelum Mati', 'Brian Krishna', 'Grasindo', 2024, '1231231241', 'Novel', 'A01', 'Seseorang yang ingin mengakhiri hidupnya', '6a977819ee60a.png', '2026-08-04 14:01:06'),
(4, 'contoh', 'contoh', 'contoh', 2026, '1231231', 'novel', '123123', '', '6a97782211fe0.png', '2026-08-05 01:38:22');

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id` int NOT NULL,
  `nama_kelas` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id`, `nama_kelas`, `created_at`) VALUES
(1, 'X PPLG', '2026-08-04 06:00:00'),
(2, 'XI PPLG', '2026-08-04 06:00:00'),
(4, 'XII PPLG', '2026-08-04 06:30:43'),
(5, 'X TJKT 1', '2026-08-04 06:30:52'),
(6, 'X TJKT 2', '2026-08-04 06:31:02'),
(7, 'XI TJKT 1', '2026-08-04 06:31:17'),
(8, 'XI TJKT 2', '2026-08-04 06:31:28'),
(9, 'XII TJKT 1', '2026-08-04 06:31:37'),
(10, 'XII TJKT 2', '2026-08-04 06:31:44');

-- --------------------------------------------------------

--
-- Table structure for table `reservasi`
--

CREATE TABLE `reservasi` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `eksemplar_id` int NOT NULL,
  `tanggal_reservasi` date NOT NULL,
  `status` enum('Diajukan','Disetujui','Ditolak','Selesai') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Diajukan',
  `alasan_penolakan` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservasi`
--

INSERT INTO `reservasi` (`id`, `user_id`, `eksemplar_id`, `tanggal_reservasi`, `status`, `alasan_penolakan`, `created_at`) VALUES
(2, 10, 1, '2026-08-09', 'Ditolak', 'buku sedang dipakai guru', '2026-08-09 11:32:31'),
(3, 10, 3, '2026-08-09', 'Ditolak', 'Sedang dipakai guru', '2026-08-09 11:38:26'),
(4, 10, 5, '2026-08-09', 'Disetujui', NULL, '2026-08-09 11:38:41'),
(5, 10, 4, '2026-08-09', 'Disetujui', NULL, '2026-08-09 11:56:29'),
(9, 15, 5, '2026-09-02', 'Disetujui', NULL, '2026-09-02 01:23:26');

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_peminjaman`
--

CREATE TABLE `riwayat_peminjaman` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `eksemplar_id` int NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `lama_pinjam` int DEFAULT NULL,
  `batas_kembali` date NOT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `status` enum('Dipinjam','Selesai') COLLATE utf8mb4_general_ci DEFAULT 'Dipinjam',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `riwayat_peminjaman`
--

INSERT INTO `riwayat_peminjaman` (`id`, `user_id`, `eksemplar_id`, `tanggal_pinjam`, `lama_pinjam`, `batas_kembali`, `tanggal_kembali`, `status`, `created_at`) VALUES
(2, 10, 5, '2026-08-09', NULL, '2026-08-16', '2026-08-09', 'Selesai', '2026-08-09 11:44:56'),
(3, 10, 4, '2026-08-09', 1, '2026-08-10', '2026-08-09', 'Selesai', '2026-08-09 11:57:07'),
(7, 15, 5, '2026-09-02', 2, '2026-09-04', '2026-09-02', 'Selesai', '2026-09-02 01:24:09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('admin','user') COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('Aktif','Nonaktif') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Aktif',
  `nis` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kelas_id` int DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_general_ci DEFAULT 'default.png',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `username`, `password`, `role`, `status`, `nis`, `kelas_id`, `foto`, `created_at`) VALUES
(1, 'Administrator', 'admin', '$2y$10$OWeRFQTldgW7lMZipg3CqeFvlazrhl8G1DuZs/pW7bG/poT3d/z9K', 'admin', 'Aktif', NULL, NULL, 'default.png', '2026-08-04 05:33:11'),
(10, 'user123', 'user', '$2y$10$MZOJBzfYHZrHJqGAyPXthOQxsvCZoa4j2p.X9X0XNdrYoIe9.gEJy', 'user', 'Aktif', NULL, NULL, 'default.png', '2026-08-06 04:29:27'),
(15, 'putra', 'putra', '$2y$10$f45axK36VZo30bXO0j3fVuAGnzxnbQvafDfJ/vO/MX3CReZ7ucMYS', 'user', 'Aktif', '123', 2, 'default.png', '2026-09-02 01:22:23'),
(16, 'saya', 'saya', '$2y$10$AhnozqE2t07iHxs3qGedNeGeHDJFBCen1wToLPS/djuj6h4Pdth7K', 'user', 'Aktif', '123', 2, 'default.png', '2026-09-02 01:26:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `eksemplar_buku`
--
ALTER TABLE `eksemplar_buku`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_buku` (`kode_buku`),
  ADD KEY `fk_eksemplar_judul` (`judul_id`);

--
-- Indexes for table `judul_buku`
--
ALTER TABLE `judul_buku`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservasi`
--
ALTER TABLE `reservasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_reservasi_user` (`user_id`),
  ADD KEY `fk_reservasi_eksemplar` (`eksemplar_id`);

--
-- Indexes for table `riwayat_peminjaman`
--
ALTER TABLE `riwayat_peminjaman`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pinjam_user` (`user_id`),
  ADD KEY `fk_pinjam_eksemplar` (`eksemplar_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `fk_users_kelas` (`kelas_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `eksemplar_buku`
--
ALTER TABLE `eksemplar_buku`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `judul_buku`
--
ALTER TABLE `judul_buku`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `reservasi`
--
ALTER TABLE `reservasi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `riwayat_peminjaman`
--
ALTER TABLE `riwayat_peminjaman`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `eksemplar_buku`
--
ALTER TABLE `eksemplar_buku`
  ADD CONSTRAINT `fk_eksemplar_judul` FOREIGN KEY (`judul_id`) REFERENCES `judul_buku` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reservasi`
--
ALTER TABLE `reservasi`
  ADD CONSTRAINT `fk_reservasi_eksemplar` FOREIGN KEY (`eksemplar_id`) REFERENCES `eksemplar_buku` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reservasi_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `riwayat_peminjaman`
--
ALTER TABLE `riwayat_peminjaman`
  ADD CONSTRAINT `fk_pinjam_eksemplar` FOREIGN KEY (`eksemplar_id`) REFERENCES `eksemplar_buku` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pinjam_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
