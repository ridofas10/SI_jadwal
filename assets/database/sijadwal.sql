-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 10 Des 2024 pada 05.50
-- Versi server: 10.4.27-MariaDB
-- Versi PHP: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sijadwal`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `daftar_dosen`
--

CREATE TABLE `daftar_dosen` (
  `id` int(11) NOT NULL,
  `nip` varchar(255) NOT NULL,
  `nama` varchar(500) NOT NULL,
  `alamat` varchar(1000) NOT NULL,
  `bidang` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `daftar_dosen`
--

INSERT INTO `daftar_dosen` (`id`, `nip`, `nama`, `alamat`, `bidang`) VALUES
(16, '040.1.2017.011', 'Yudo Bismo Utomo', 'Bandar', 'Teknik Komputer');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal_bimbingan`
--

CREATE TABLE `jadwal_bimbingan` (
  `id` int(11) NOT NULL,
  `id_user` varchar(255) NOT NULL,
  `nama_dosen` varchar(255) NOT NULL,
  `nama_mahasiswa` varchar(255) NOT NULL,
  `npm` varchar(255) NOT NULL,
  `hari` varchar(255) NOT NULL,
  `tanggal` date NOT NULL,
  `jam` varchar(255) NOT NULL,
  `ruangan` varchar(255) NOT NULL,
  `materi` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jadwal_bimbingan`
--

INSERT INTO `jadwal_bimbingan` (`id`, `id_user`, `nama_dosen`, `nama_mahasiswa`, `npm`, `hari`, `tanggal`, `jam`, `ruangan`, `materi`, `created_at`) VALUES
(37, '13', 'Yudo Bismo Utomo', 'Ridofas Tri Sandi Fantiantoro', '20562020023', 'Selasa', '2024-12-12', '09:00', 'Kaprodi', 'Bab 1', '2024-12-09 13:01:06'),
(39, '26', 'Yudo Bismo Utomo', 'Arvin Rifky Pratomo', '20562020052', 'Senin', '2024-12-16', '11:30', 'Kaprodi', 'Bab 5 Kesimpulan', '2024-12-09 13:01:44'),
(40, '26', 'Yudo Bismo Utomo', 'Arvin Rifky Pratomo', '20562020052', 'Selasa', '2024-12-24', '10:30', 'Kaprodi', 'Jurnal', '2024-12-09 13:01:51'),
(41, '13', 'Yudo Bismo Utomo', 'Ridofas Tri Sandi Fantiantoro', '20562020023', 'Rabu', '2024-12-11', '09:30', 'Kaprodi', 'BAB 1', '2024-12-09 17:41:18'),
(42, '13', 'Yudo Bismo Utomo', 'Ridofas Tri Sandi Fantiantoro', '20562020023', 'Rabu', '2024-12-11', '10:30', 'Kaprodi', 'Bab 2', '2024-12-09 13:01:20'),
(43, '13', 'Yudo Bismo Utomo', 'Ridofas Tri Sandi Fantiantoro', '20562020023', 'Rabu', '2024-12-11', '10:00', 'Kaprodi', 'Bab 3', '2024-12-09 13:01:25'),
(46, '26', 'Yudo Bismo Utomo', 'Arvin Rifky Pratomo', '20562020052', 'Rabu', '2024-12-11', '08:00', 'Kaprodi', 'Bab 5', '2024-12-09 14:17:20'),
(47, '13', 'Yudo Bismo Utomo', 'Ridofas Tri Sandi Fantiantoro', '20562020023', 'Selasa', '2024-12-17', '10:00', 'Kaprodi', 'Penulisan Jurnal', '2024-12-10 02:08:45');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal_dosen`
--

CREATE TABLE `jadwal_dosen` (
  `id` int(11) NOT NULL,
  `id_dosen` int(11) NOT NULL,
  `nama_dosen` varchar(500) NOT NULL,
  `hari` varchar(1000) NOT NULL,
  `jam` varchar(255) NOT NULL,
  `ruangan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jadwal_dosen`
--

INSERT INTO `jadwal_dosen` (`id`, `id_dosen`, `nama_dosen`, `hari`, `jam`, `ruangan`) VALUES
(153, 16, 'Yudo Bismo Utomo', 'Senin', '08:00', 'Kaprodi'),
(154, 16, 'Yudo Bismo Utomo', 'Senin', '09:00', 'Kaprodi'),
(155, 16, 'Yudo Bismo Utomo', 'Senin', '10:00', 'Kaprodi'),
(156, 16, 'Yudo Bismo Utomo', 'Senin', '11:00', 'Kaprodi'),
(157, 16, 'Yudo Bismo Utomo', 'Selasa', '08:00', 'Kaprodi'),
(158, 16, 'Yudo Bismo Utomo', 'Selasa', '09:00', 'Kaprodi'),
(159, 16, 'Yudo Bismo Utomo', 'Selasa', '10:00', 'Kaprodi'),
(160, 16, 'Yudo Bismo Utomo', 'Selasa', '11:00', 'Kaprodi'),
(161, 16, 'Yudo Bismo Utomo', 'Rabu', '08:00', 'Kaprodi'),
(162, 16, 'Yudo Bismo Utomo', 'Rabu', '09:00', 'Kaprodi'),
(163, 16, 'Yudo Bismo Utomo', 'Rabu', '10:00', 'Kaprodi'),
(164, 16, 'Yudo Bismo Utomo', 'Rabu', '11:00', 'Kaprodi');

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id` int(11) NOT NULL,
  `nama_dosen` varchar(100) NOT NULL,
  `nama_mahasiswa` varchar(255) NOT NULL,
  `pesan` text NOT NULL,
  `status` enum('unread','read') DEFAULT 'unread',
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `notifikasi`
--

INSERT INTO `notifikasi` (`id`, `nama_dosen`, `nama_mahasiswa`, `pesan`, `status`, `tanggal`) VALUES
(26, 'Yudo Bismo Utomo', 'Arvin Rifky Pratomo', 'Jadwal bimbingan baru untuk mahasiswa Arvin Rifky Pratomo pada hari Rabu jam 10:00 telah berhasil dibuat.', 'unread', '2024-12-09 12:13:39'),
(27, 'Yudo Bismo Utomo', 'Arvin Rifky Pratomo', 'Jadwal bimbingan baru untuk mahasiswa Arvin Rifky Pratomo pada hari Senin jam 11:30 telah berhasil dibuat.', 'unread', '2024-12-09 12:21:18'),
(28, 'Yudo Bismo Utomo', 'Arvin Rifky Pratomo', 'Jadwal bimbingan baru untuk mahasiswa Arvin Rifky Pratomo pada hari Selasa jam 10:30 telah berhasil dibuat.', 'unread', '2024-12-09 12:22:05'),
(31, 'Yudo Bismo Utomo', 'Ridofas Tri Sandi Fantiantoro', 'Jadwal bimbingan baru untuk mahasiswa Ridofas Tri Sandi Fantiantoro pada hari Rabu jam 10:00 telah berhasil dibuat.', 'unread', '2024-12-09 12:41:52');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `npm` varchar(50) NOT NULL,
  `level` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama_lengkap`, `npm`, `level`, `username`, `password`) VALUES
(1, 'administrator', '123', 'admin', 'admin', '$2y$10$kdfi.KaXn389EgQxi0B0zei2Uux64SbESgxNvQM13qEt2Zsq41xOy'),
(13, 'Ridofas Tri Sandi Fantiantoro', '20562020023', 'user', 'rido', '$2y$10$ykOiS057r9TKsHUiHkGEGOGSh.0UrH5jPBOOgvamhpwQnM2b4AIfu'),
(25, 'Yudo Bismo Utomo', '040.1.2017.011', 'dosen', 'yb', '$2y$10$HYEaaBnPyV9SD.bVnacYv.3tDkWszAOBG16Qt11EIFlOaY50C5p7y'),
(26, 'Arvin Rifky Pratomo', '20562020052', 'user', 'arvin', '$2y$10$ciJAYcuku4M/SpO9SlUu5esWOwGUNBh.6DJ7gbG7khXGyY2unDBsG');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `daftar_dosen`
--
ALTER TABLE `daftar_dosen`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jadwal_bimbingan`
--
ALTER TABLE `jadwal_bimbingan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jadwal_dosen`
--
ALTER TABLE `jadwal_dosen`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `daftar_dosen`
--
ALTER TABLE `daftar_dosen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `jadwal_bimbingan`
--
ALTER TABLE `jadwal_bimbingan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT untuk tabel `jadwal_dosen`
--
ALTER TABLE `jadwal_dosen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;

--
-- AUTO_INCREMENT untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
