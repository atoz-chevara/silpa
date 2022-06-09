-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 09 Jun 2022 pada 11.54
-- Versi server: 10.4.18-MariaDB
-- Versi PHP: 7.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_silpa`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `apbk`
--

CREATE TABLE `apbk` (
  `idd_evaluasi` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `kd_satker` varchar(100) NOT NULL,
  `idd_tahapan` int(100) NOT NULL,
  `tahun_anggaran` varchar(100) NOT NULL,
  `idd_wilayah` int(100) NOT NULL,
  `file_01` varchar(200) NOT NULL,
  `file_02` varchar(200) NOT NULL,
  `file_03` varchar(200) NOT NULL,
  `file_04` varchar(200) NOT NULL,
  `file_05` varchar(200) NOT NULL,
  `file_06` varchar(200) NOT NULL,
  `file_07` varchar(200) NOT NULL,
  `file_08` varchar(200) NOT NULL,
  `file_09` varchar(200) NOT NULL,
  `file_10` varchar(200) NOT NULL,
  `file_11` varchar(200) NOT NULL,
  `file_12` varchar(200) NOT NULL,
  `file_13` varchar(200) NOT NULL,
  `file_14` varchar(200) NOT NULL,
  `file_15` varchar(200) NOT NULL,
  `file_16` varchar(200) NOT NULL,
  `file_17` varchar(200) NOT NULL,
  `file_18` varchar(200) NOT NULL,
  `file_19` varchar(200) NOT NULL,
  `file_20` varchar(200) NOT NULL,
  `file_21` varchar(200) NOT NULL,
  `file_22` varchar(200) NOT NULL,
  `file_23` varchar(200) NOT NULL,
  `file_24` varchar(200) NOT NULL,
  `status` int(11) NOT NULL,
  `idd_user` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `apbkp`
--

CREATE TABLE `apbkp` (
  `idd_evaluasi` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `kd_satker` varchar(100) NOT NULL,
  `idd_tahapan` int(100) NOT NULL,
  `tahun_anggaran` varchar(100) NOT NULL,
  `idd_wilayah` int(100) NOT NULL,
  `file_01` varchar(200) NOT NULL,
  `file_02` varchar(200) NOT NULL,
  `file_03` varchar(200) NOT NULL,
  `file_04` varchar(200) NOT NULL,
  `file_05` varchar(200) NOT NULL,
  `file_06` varchar(200) NOT NULL,
  `file_07` varchar(200) NOT NULL,
  `file_08` varchar(200) NOT NULL,
  `file_09` varchar(200) NOT NULL,
  `file_10` varchar(200) NOT NULL,
  `file_11` varchar(200) NOT NULL,
  `file_12` varchar(200) NOT NULL,
  `file_13` varchar(200) NOT NULL,
  `file_14` varchar(200) NOT NULL,
  `file_15` varchar(200) NOT NULL,
  `file_16` varchar(200) NOT NULL,
  `file_17` varchar(200) NOT NULL,
  `file_18` varchar(200) NOT NULL,
  `file_19` varchar(200) NOT NULL,
  `file_20` varchar(200) NOT NULL,
  `file_21` varchar(200) NOT NULL,
  `file_22` varchar(200) NOT NULL,
  `file_23` varchar(200) NOT NULL,
  `file_24` varchar(200) NOT NULL,
  `status` int(11) NOT NULL,
  `idd_user` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `evaluasi`
--

CREATE TABLE `evaluasi` (
  `idd_evaluasi` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `kd_satker` varchar(100) NOT NULL,
  `idd_tahapan` int(100) NOT NULL,
  `tahun_anggaran` varchar(100) NOT NULL,
  `idd_wilayah` int(100) NOT NULL,
  `surat_pengantar` varchar(200) NOT NULL,
  `rpjmd` varchar(200) NOT NULL,
  `rkpk` varchar(200) NOT NULL,
  `skd_rkuappas` varchar(200) NOT NULL,
  `kua` varchar(200) NOT NULL,
  `ppas` varchar(200) NOT NULL,
  `skd_rqanun` varchar(200) NOT NULL,
  `nota_keuangan` varchar(200) NOT NULL,
  `pengantar_nota` varchar(200) NOT NULL,
  `risalah_sidang` varchar(200) NOT NULL,
  `bap_apbk` varchar(200) NOT NULL,
  `rq_apbk` varchar(200) NOT NULL,
  `rp_penjabaran` varchar(200) NOT NULL,
  `jadwal_proses` varchar(200) NOT NULL,
  `sinkron_kebijakan` varchar(200) NOT NULL,
  `konsistensi_program` varchar(200) NOT NULL,
  `alokasi_pendidikan` varchar(200) NOT NULL,
  `alokasi_kesehatan` varchar(200) NOT NULL,
  `alokasi_belanja` varchar(200) NOT NULL,
  `bak_kegiatan` varchar(200) NOT NULL,
  `softcopy_rka` varchar(200) NOT NULL,
  `otsus` varchar(200) NOT NULL,
  `qanun_perbup` varchar(200) NOT NULL,
  `tindak_apbkp` varchar(200) NOT NULL,
  `status` int(11) NOT NULL,
  `idd_user` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `evaluators`
--

CREATE TABLE `evaluators` (
  `idd_evaluator` int(100) NOT NULL,
  `nip` varchar(200) NOT NULL,
  `nama_lengkap` varchar(200) NOT NULL,
  `alamat` text NOT NULL,
  `wilayah` int(100) NOT NULL,
  `idd_user` int(100) NOT NULL,
  `no_telepon` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `evaluators`
--

INSERT INTO `evaluators` (`idd_evaluator`, `nip`, `nama_lengkap`, `alamat`, `wilayah`, `idd_user`, `no_telepon`) VALUES
(1, '19830223 200901 1 003', 'Andrew Vilas, SE', '-', 1, 2, '08126900377'),
(2, '19900805 201206 1 001', 'Agustia Ferrira , S.STP, M.Si', '-', 1, 3, '085277366230');

-- --------------------------------------------------------

--
-- Struktur dari tabel `levels`
--

CREATE TABLE `levels` (
  `id_level` int(11) NOT NULL,
  `level_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `levels`
--

INSERT INTO `levels` (`id_level`, `level_name`) VALUES
(-2, 'Anonymous'),
(1, 'Admiral'),
(2, 'Evaluator'),
(3, 'Satker'),
(4, 'Operator'),
(5, 'Petugas');

-- --------------------------------------------------------

--
-- Struktur dari tabel `permissions`
--

CREATE TABLE `permissions` (
  `table_name` varchar(100) NOT NULL,
  `id_level` int(11) NOT NULL,
  `permission` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `permissions`
--

INSERT INTO `permissions` (`table_name`, `id_level`, `permission`) VALUES
('{8FB2C16F-E090-4B20-9B83-115D69E60354}levels', -2, 0),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}permissions', -2, 0),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}users', -2, 0),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}levels', 1, 511),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}permissions', 1, 511),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}users', 1, 511),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}evaluators', 1, 511),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}satkers', 1, 511),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}tahapan', 1, 511),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}wilayah', 1, 511),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}evaluasi', 1, 511),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}news.php', 1, 511),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}apbk', 1, 511),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}apbkp', 1, 511),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}pertanggungjawaban', 1, 511),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}rapbk', 1, 511),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}levels', 2, 0),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}permissions', 2, 0),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}users', 2, 300),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}evaluators', 2, 364),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}satkers', 2, 0),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}tahapan', 2, 0),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}wilayah', 2, 0),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}evaluasi', 2, 0),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}news.php', 2, 0),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}apbk', 2, 360),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}apbkp', 2, 360),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}pertanggungjawaban', 2, 360),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}rapbk', 2, 360),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}levels', 3, 0),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}permissions', 3, 0),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}users', 3, 364),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}evaluators', 3, 0),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}satkers', 3, 364),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}tahapan', 3, 0),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}wilayah', 3, 0),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}evaluasi', 3, 0),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}news.php', 3, 0),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}apbk', 3, 367),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}apbkp', 3, 367),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}pertanggungjawaban', 3, 367),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}rapbk', 3, 367);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pertanggungjawaban`
--

CREATE TABLE `pertanggungjawaban` (
  `idd_evaluasi` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `kd_satker` varchar(100) NOT NULL,
  `idd_tahapan` int(100) NOT NULL,
  `tahun_anggaran` varchar(100) NOT NULL,
  `idd_wilayah` int(100) NOT NULL,
  `surat_pengantar` varchar(200) DEFAULT NULL,
  `skd_rqanunpert` varchar(200) DEFAULT NULL,
  `rq_apbkpert` varchar(200) DEFAULT NULL,
  `bap_apbkpert` varchar(200) DEFAULT NULL,
  `risalah_sidang` varchar(200) DEFAULT NULL,
  `absen_peserta` varchar(200) DEFAULT NULL,
  `neraca` varchar(200) DEFAULT NULL,
  `lra` varchar(200) DEFAULT NULL,
  `calk` varchar(200) DEFAULT NULL,
  `lo` varchar(200) DEFAULT NULL,
  `lpe` varchar(200) DEFAULT NULL,
  `lpsal` varchar(200) DEFAULT NULL,
  `lak` varchar(200) DEFAULT NULL,
  `laporan_pemeriksaan` varchar(200) DEFAULT NULL,
  `softcopy_rqanun` varchar(200) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `idd_user` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `rapbk`
--

CREATE TABLE `rapbk` (
  `idd_evaluasi` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `kd_satker` varchar(100) NOT NULL,
  `idd_tahapan` int(100) NOT NULL,
  `tahun_anggaran` varchar(100) NOT NULL,
  `idd_wilayah` int(100) NOT NULL,
  `file_01` varchar(200) NOT NULL,
  `file_02` varchar(200) NOT NULL,
  `file_03` varchar(200) NOT NULL,
  `file_04` varchar(200) NOT NULL,
  `file_05` varchar(200) NOT NULL,
  `file_06` varchar(200) NOT NULL,
  `file_07` varchar(200) NOT NULL,
  `file_08` varchar(200) NOT NULL,
  `file_09` varchar(200) NOT NULL,
  `file_10` varchar(200) NOT NULL,
  `file_11` varchar(200) NOT NULL,
  `file_12` varchar(200) NOT NULL,
  `file_13` varchar(200) NOT NULL,
  `file_14` varchar(200) NOT NULL,
  `file_15` varchar(200) NOT NULL,
  `file_16` varchar(200) NOT NULL,
  `file_17` varchar(200) NOT NULL,
  `file_18` varchar(200) NOT NULL,
  `file_19` varchar(200) NOT NULL,
  `file_20` varchar(200) NOT NULL,
  `file_21` varchar(200) NOT NULL,
  `file_22` varchar(200) NOT NULL,
  `file_23` varchar(200) NOT NULL,
  `file_24` varchar(200) NOT NULL,
  `status` int(11) NOT NULL,
  `idd_user` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `satkers`
--

CREATE TABLE `satkers` (
  `idd_satker` int(100) NOT NULL,
  `kode_pemda` varchar(100) NOT NULL,
  `kode_satker` int(100) NOT NULL,
  `nama_satker` varchar(200) NOT NULL,
  `wilayah` int(100) NOT NULL,
  `idd_user` int(100) NOT NULL,
  `no_telepon` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `satkers`
--

INSERT INTO `satkers` (`idd_satker`, `kode_pemda`, `kode_satker`, `nama_satker`, `wilayah`, `idd_user`, `no_telepon`) VALUES
(1, '01.00', 990015, 'Provinsi Aceh', 4, 1, '082259013113'),
(2, '01.01', 990078, 'Kab. Aceh Barat', 1, 1, '082259013113'),
(3, '01.02', 990022, 'Kab. Aceh Besar', 2, 1, '082259013113'),
(4, '01.03', 990061, 'Kab. Aceh Selatan', 1, 1, '082259013113'),
(5, '01.04', 980007, 'Kab. Aceh Singkil', 3, 1, '082259013113'),
(6, '01.05', 990082, 'Kab. Aceh Tengah', 1, 1, '082259013113'),
(7, '01.06', 990099, 'Kab. Aceh Tenggara', 2, 1, '082259013113'),
(8, '01.07', 990057, 'Kab. Aceh Timur', 2, 1, '082259013113'),
(9, '01.08', 990040, 'Kab. Aceh Utara', 1, 1, '082259013113'),
(10, '01.09', 980053, 'Kab. Bireuen', 2, 1, '082259013113'),
(11, '01.10', 990036, 'Kab. Aceh Pidie', 3, 1, '082259013113'),
(12, '01.11', 993372, 'Kab. Simeulue', 1, 1, '082259013113'),
(13, '01.12', 990104, 'Kota Banda Aceh', 1, 1, '082259013113'),
(14, '01.13', 990111, 'Kota Sabang', 2, 1, '082259013113'),
(15, '01.14', 994672, 'Kota Langsa', 3, 1, '082259013113'),
(16, '01.15', 994686, 'Kota Lhokseumawe', 3, 1, '082259013113'),
(17, '01.16', 997430, 'Kab. Gayo Lues', 3, 1, '082259013113'),
(18, '01.17', 997426, 'Kab. Aceh Barat Daya', 3, 1, '082259013113'),
(19, '01.18', 997447, 'Kab. Aceh Jaya', 3, 1, '082259013113'),
(20, '01.19', 997451, 'Kab. Nagan Raya', 2, 1, '082259013113'),
(21, '01.20', 997468, 'Kab. Aceh Tamiang', 1, 1, '082259013113'),
(22, '01.21', 987532, 'Kab. Bener Meriah', 2, 1, '082259013113'),
(23, '01.22', 963311, 'Kab. Pidie Jaya', 1, 1, '082259013113'),
(24, '01.23', 963327, 'Kota Subulussalam', 3, 1, '082259013113');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tahapan`
--

CREATE TABLE `tahapan` (
  `idd_tahapan` int(100) NOT NULL,
  `nama_tahapan` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tahapan`
--

INSERT INTO `tahapan` (`idd_tahapan`, `nama_tahapan`) VALUES
(1, 'Rancangan'),
(2, 'Murni'),
(3, 'Perubahan'),
(4, 'Pertanggungjawaban');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tahun`
--

CREATE TABLE `tahun` (
  `id_tahun` varchar(100) NOT NULL,
  `tahun` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tahun`
--

INSERT INTO `tahun` (`id_tahun`, `tahun`) VALUES
('2020', '2020'),
('2021', '2021'),
('2022', '2022'),
('2023', '2023');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `idd_user` int(100) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `photo` varchar(100) NOT NULL,
  `level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`idd_user`, `username`, `password`, `email`, `photo`, `level`) VALUES
(1, 'atoz', '5f4dcc3b5aa765d61d8327deb882cf99', 'atoz.chevara@yahoo.com', 'user1-128x128.jpg', 1),
(2, 'een', '5f4dcc3b5aa765d61d8327deb882cf99', 'een@email.com', 'anonim.png', 2),
(3, 'agus', '5f4dcc3b5aa765d61d8327deb882cf99', 'agus@email.com', 'anonim(1).png', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `wilayah`
--

CREATE TABLE `wilayah` (
  `idd_wilayah` int(100) NOT NULL,
  `nama_wilayah` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `wilayah`
--

INSERT INTO `wilayah` (`idd_wilayah`, `nama_wilayah`) VALUES
(1, 'Wilayah I'),
(2, 'Wilayah II'),
(3, 'Wilayah III'),
(4, 'Provinsi');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `apbk`
--
ALTER TABLE `apbk`
  ADD PRIMARY KEY (`idd_evaluasi`);

--
-- Indeks untuk tabel `apbkp`
--
ALTER TABLE `apbkp`
  ADD PRIMARY KEY (`idd_evaluasi`);

--
-- Indeks untuk tabel `evaluasi`
--
ALTER TABLE `evaluasi`
  ADD PRIMARY KEY (`idd_evaluasi`);

--
-- Indeks untuk tabel `evaluators`
--
ALTER TABLE `evaluators`
  ADD PRIMARY KEY (`idd_evaluator`);

--
-- Indeks untuk tabel `pertanggungjawaban`
--
ALTER TABLE `pertanggungjawaban`
  ADD PRIMARY KEY (`idd_evaluasi`);

--
-- Indeks untuk tabel `rapbk`
--
ALTER TABLE `rapbk`
  ADD PRIMARY KEY (`idd_evaluasi`);

--
-- Indeks untuk tabel `satkers`
--
ALTER TABLE `satkers`
  ADD PRIMARY KEY (`idd_satker`);

--
-- Indeks untuk tabel `tahapan`
--
ALTER TABLE `tahapan`
  ADD PRIMARY KEY (`idd_tahapan`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`idd_user`);

--
-- Indeks untuk tabel `wilayah`
--
ALTER TABLE `wilayah`
  ADD PRIMARY KEY (`idd_wilayah`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `apbk`
--
ALTER TABLE `apbk`
  MODIFY `idd_evaluasi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `apbkp`
--
ALTER TABLE `apbkp`
  MODIFY `idd_evaluasi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `evaluasi`
--
ALTER TABLE `evaluasi`
  MODIFY `idd_evaluasi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `evaluators`
--
ALTER TABLE `evaluators`
  MODIFY `idd_evaluator` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `pertanggungjawaban`
--
ALTER TABLE `pertanggungjawaban`
  MODIFY `idd_evaluasi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `rapbk`
--
ALTER TABLE `rapbk`
  MODIFY `idd_evaluasi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `satkers`
--
ALTER TABLE `satkers`
  MODIFY `idd_satker` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `tahapan`
--
ALTER TABLE `tahapan`
  MODIFY `idd_tahapan` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `idd_user` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `wilayah`
--
ALTER TABLE `wilayah`
  MODIFY `idd_wilayah` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
