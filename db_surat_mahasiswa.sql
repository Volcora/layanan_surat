-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 02 Agu 2026 pada 17.25
-- Versi server: 10.4.25-MariaDB
-- Versi PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_surat_mahasiswa`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajuan_surat`
--

CREATE TABLE `pengajuan_surat` (
  `id_pengajuan` int(11) NOT NULL,
  `id_mahasiswa` int(11) DEFAULT NULL,
  `jenis_surat` varchar(100) DEFAULT NULL,
  `no_telepon` varchar(20) DEFAULT NULL,
  `keterangan` text NOT NULL,
  `nomor_surat` varchar(100) DEFAULT NULL,
  `status` enum('diajukan','diproses','menunggu_kaprodi','revisi_admin','acc_kaprodi','selesai','ditolak') DEFAULT 'diajukan',
  `tanggal_pengajuan` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `riwayat_draf`
--

CREATE TABLE `riwayat_draf` (
  `id_riwayat` int(11) NOT NULL,
  `id_pengajuan` int(11) DEFAULT NULL,
  `versi` int(11) DEFAULT NULL,
  `file_draf` varchar(255) DEFAULT NULL,
  `catatan_revisi` text DEFAULT NULL,
  `tanggal_unggah` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('mahasiswa','admin','kaprodi') NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `nim` varchar(25) DEFAULT NULL,
  `nip` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `role`, `nama_lengkap`, `nim`, `nip`) VALUES
(1, 'admin', 'admin', 'admin', 'admin 1', NULL, '112718005'),
(2, 'mhs', 'mhs', 'mahasiswa', 'mhs 1', '10523016', NULL),
(3, 'kaprodi', 'kaprodi', 'kaprodi', 'kaprodi 1', NULL, '122716001');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `pengajuan_surat`
--
ALTER TABLE `pengajuan_surat`
  ADD PRIMARY KEY (`id_pengajuan`),
  ADD KEY `id_mahasiswa` (`id_mahasiswa`);

--
-- Indeks untuk tabel `riwayat_draf`
--
ALTER TABLE `riwayat_draf`
  ADD PRIMARY KEY (`id_riwayat`),
  ADD KEY `id_pengajuan` (`id_pengajuan`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `pengajuan_surat`
--
ALTER TABLE `pengajuan_surat`
  MODIFY `id_pengajuan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT untuk tabel `riwayat_draf`
--
ALTER TABLE `riwayat_draf`
  MODIFY `id_riwayat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `pengajuan_surat`
--
ALTER TABLE `pengajuan_surat`
  ADD CONSTRAINT `pengajuan_surat_ibfk_1` FOREIGN KEY (`id_mahasiswa`) REFERENCES `users` (`id_user`);

--
-- Ketidakleluasaan untuk tabel `riwayat_draf`
--
ALTER TABLE `riwayat_draf`
  ADD CONSTRAINT `riwayat_draf_ibfk_1` FOREIGN KEY (`id_pengajuan`) REFERENCES `pengajuan_surat` (`id_pengajuan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
