-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 12 Jun 2022 pada 21.22
-- Versi server: 10.4.24-MariaDB
-- Versi PHP: 7.4.28

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `evaluators`
--

CREATE TABLE `evaluators` (
  `idd_evaluator` int(100) NOT NULL,
  `nip` varchar(200) NOT NULL,
  `nama_lengkap` varchar(200) NOT NULL,
  `alamat` mediumtext NOT NULL,
  `wilayah` int(100) NOT NULL,
  `idd_user` int(100) NOT NULL,
  `no_telepon` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `evaluators`
--

INSERT INTO `evaluators` (`idd_evaluator`, `nip`, `nama_lengkap`, `alamat`, `wilayah`, `idd_user`, `no_telepon`) VALUES
(1, '19830223 200901 1 003', 'Andrew Vilas, SE', '-', 2, 31, '08126900377'),
(2, '19900805 201206 1 001', 'Agustia Ferrira , S.STP, M.Si', '-', 2, 32, '085277366230'),
(3, '19720806 199803 1 008', 'Surianto, S. Sos', '-', 1, 26, '081362584708'),
(4, '19800619 200604 2 005', 'Hayatun Nusuf, SE', '-', 1, 27, '081973902541'),
(5, '19771228 200212 1 003', 'Mohd. Iqbal, SE, Ak', '-', 1, 28, '08126950454'),
(6, '19870112 200604 2 001', 'Nora Amalia, SE, MM', '-', 1, 29, '08116898666'),
(7, '19701231 200212 1 010', 'Syahril, SE', '-', 2, 30, '082168398735'),
(8, '19810614 200604 2 005', 'Nurmalena, SE', '-', 2, 33, '08126965372'),
(9, '19800610 200604 1 008', 'Husaini, S.Sos', '-', 3, 34, '081360049271'),
(10, '19930310 201507 1 003', 'Rizky Fitriyansyah, S.STP', '-', 3, 35, '085277441112'),
(11, '19820111 201003 2 001', 'Riska, SE', '-', 3, 36, '081360634082'),
(12, '19710507 200112 2 002', 'Asmita, SE, M.Si', '-', 3, 37, '085260228490');

-- --------------------------------------------------------

--
-- Struktur dari tabel `levels`
--

CREATE TABLE `levels` (
  `id_level` int(11) NOT NULL,
  `level_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
('{8FB2C16F-E090-4B20-9B83-115D69E60354}satkers', 2, 364),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}tahapan', 2, 256),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}wilayah', 2, 256),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}evaluasi', 2, 0),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}news.php', 2, 0),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}apbk', 2, 0),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}apbkp', 2, 0),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}pertanggungjawaban', 2, 0),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}rapbk', 2, 0),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}levels', 3, 0),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}permissions', 3, 0),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}users', 3, 364),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}evaluators', 3, 0),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}satkers', 3, 364),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}tahapan', 3, 256),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}wilayah', 3, 256),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}evaluasi', 3, 0),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}news.php', 3, 0),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}apbk', 3, 0),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}apbkp', 3, 0),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}pertanggungjawaban', 3, 0),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}rapbk', 3, 0),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}tahun', 3, 256),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}pertanggungjawaban2022', 3, 367),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}tahun', 2, 256),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}pertanggungjawaban2022', 2, 0),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}view_pertanggungjawaban_2022_ev', 2, 364),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}tahun', 1, 511),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}pertanggungjawaban2022', 1, 0),
('{8FB2C16F-E090-4B20-9B83-115D69E60354}view_pertanggungjawaban_2022_ev', 1, 511);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pertanggungjawaban`
--

CREATE TABLE `pertanggungjawaban` (
  `idd_evaluasi` int(11) NOT NULL,
  `tanggal_upload` timestamp NOT NULL DEFAULT current_timestamp(),
  `tanggal_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `kd_satker` varchar(100) NOT NULL,
  `idd_tahapan` int(100) NOT NULL,
  `tahun_anggaran` varchar(100) NOT NULL,
  `idd_wilayah` int(100) NOT NULL,
  `surat_pengantar` varchar(200) DEFAULT NULL,
  `skd_rqanunpert` varchar(200) DEFAULT NULL,
  `rqanun_apbkpert` varchar(200) DEFAULT NULL,
  `rperbup_apbkpert` varchar(200) DEFAULT NULL,
  `pbkdd_apbkpert` varchar(200) DEFAULT NULL,
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
  `status` int(11) NOT NULL,
  `idd_user` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pertanggungjawaban2022`
--

CREATE TABLE `pertanggungjawaban2022` (
  `idd_evaluasi` int(11) NOT NULL,
  `tanggal_upload` timestamp NOT NULL DEFAULT current_timestamp(),
  `tanggal_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `kd_satker` varchar(100) NOT NULL,
  `idd_tahapan` int(100) NOT NULL,
  `tahun_anggaran` varchar(100) NOT NULL,
  `surat_pengantar` varchar(200) DEFAULT NULL,
  `skd_rqanunpert` varchar(200) DEFAULT NULL,
  `rqanun_apbkpert` varchar(200) DEFAULT NULL,
  `rperbup_apbkpert` varchar(200) DEFAULT NULL,
  `pbkdd_apbkpert` varchar(200) DEFAULT NULL,
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
  `status` int(11) NOT NULL,
  `idd_user` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `pertanggungjawaban2022`
--

INSERT INTO `pertanggungjawaban2022` (`idd_evaluasi`, `tanggal_upload`, `tanggal_update`, `kd_satker`, `idd_tahapan`, `tahun_anggaran`, `surat_pengantar`, `skd_rqanunpert`, `rqanun_apbkpert`, `rperbup_apbkpert`, `pbkdd_apbkpert`, `risalah_sidang`, `absen_peserta`, `neraca`, `lra`, `calk`, `lo`, `lpe`, `lpsal`, `lak`, `laporan_pemeriksaan`, `status`, `idd_user`) VALUES
(1, '2022-06-12 18:43:16', '2022-06-12 19:19:21', '01.01', 4, '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 3);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `no_telepon` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `satkers`
--

INSERT INTO `satkers` (`idd_satker`, `kode_pemda`, `kode_satker`, `nama_satker`, `wilayah`, `idd_user`, `no_telepon`) VALUES
(1, '01.00', 990015, 'Provinsi Aceh', 4, 2, '-'),
(2, '01.01', 990078, 'Kab. Aceh Barat', 1, 3, '-'),
(3, '01.02', 990022, 'Kab. Aceh Besar', 2, 4, '-'),
(4, '01.03', 990061, 'Kab. Aceh Selatan', 1, 5, '-'),
(5, '01.04', 980007, 'Kab. Aceh Singkil', 3, 6, '-'),
(6, '01.05', 990082, 'Kab. Aceh Tengah', 1, 7, '-'),
(7, '01.06', 990099, 'Kab. Aceh Tenggara', 2, 8, '-'),
(8, '01.07', 990057, 'Kab. Aceh Timur', 2, 9, '-'),
(9, '01.08', 990040, 'Kab. Aceh Utara', 1, 10, '-'),
(10, '01.09', 980053, 'Kab. Bireuen', 2, 11, '-'),
(11, '01.10', 990036, 'Kab. Aceh Pidie', 3, 12, '-'),
(12, '01.11', 993372, 'Kab. Simeulue', 1, 13, '-'),
(13, '01.12', 990104, 'Kota Banda Aceh', 1, 14, '-'),
(14, '01.13', 990111, 'Kota Sabang', 2, 15, '-'),
(15, '01.14', 994672, 'Kota Langsa', 3, 16, '-'),
(16, '01.15', 994686, 'Kota Lhokseumawe', 3, 17, '-'),
(17, '01.16', 997430, 'Kab. Gayo Lues', 3, 18, '-'),
(18, '01.17', 997426, 'Kab. Aceh Barat Daya', 3, 19, '-'),
(19, '01.18', 997447, 'Kab. Aceh Jaya', 3, 20, '-'),
(20, '01.19', 997451, 'Kab. Nagan Raya', 2, 21, '-'),
(21, '01.20', 997468, 'Kab. Aceh Tamiang', 1, 4, '-'),
(22, '01.21', 987532, 'Kab. Bener Meriah', 2, 23, '-'),
(23, '01.22', 963311, 'Kab. Pidie Jaya', 1, 24, '-'),
(24, '01.23', 963327, 'Kota Subulussalam', 3, 25, '-');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tahapan`
--

CREATE TABLE `tahapan` (
  `idd_tahapan` int(100) NOT NULL,
  `nama_tahapan` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `email` varchar(200) DEFAULT NULL,
  `photo` varchar(100) DEFAULT NULL,
  `level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`idd_user`, `username`, `password`, `email`, `photo`, `level`) VALUES
(1, 'atoz', '5f4dcc3b5aa765d61d8327deb882cf99', 'atoz.chevara@yahoo.com', 'user1-128x128.jpg', 1),
(2, '01.00', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 3),
(3, '01.01', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 3),
(4, '01.02', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 3),
(5, '01.03', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 3),
(6, '01.04', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 3),
(7, '01.05', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 3),
(8, '01.06', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 3),
(9, '01.07', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 3),
(10, '01.08', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 3),
(11, '01.09', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 3),
(12, '01.10', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 3),
(13, '01.11', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 3),
(14, '01.12', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 3),
(15, '01.13', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 3),
(16, '01.14', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 3),
(17, '01.15', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 3),
(18, '01.16', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 3),
(19, '01.17', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 3),
(20, '01.18', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 3),
(21, '01.19', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 3),
(22, '01.20', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 3),
(23, '01.21', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 3),
(24, '01.22', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 3),
(25, '01.23', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 3),
(26, 'anto', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 2),
(27, 'atun', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 2),
(28, 'iqbal', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 2),
(29, 'nora', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 2),
(30, 'syahril', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 2),
(31, 'een', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 2),
(32, 'agus', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 2),
(33, 'lena', '696d29e0940a4957748fe3fc9efd22a3', 'user@email.com', 'anonim.png', 2),
(34, 'husaini', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 2),
(35, 'icik', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 2),
(36, 'riska', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 2),
(37, 'asmita', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 2),
(38, 'fina', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 2),
(39, 'dedi', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 2),
(40, 'budi', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 2),
(41, 'fitriani', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 2),
(42, 'misra', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 2),
(43, 'arul', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 2),
(44, 'lutfi', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 2),
(45, 'muhardiman', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 2),
(46, 'ayi', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 2),
(47, 'prayudi', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 2),
(48, 'dara', '5f4dcc3b5aa765d61d8327deb882cf99', 'user@email.com', 'anonim.png', 2);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `view_pertanggungjawaban_2022_ev`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `view_pertanggungjawaban_2022_ev` (
`idd_tahapan` int(100)
,`tahun_anggaran` varchar(100)
,`surat_pengantar` varchar(200)
,`skd_rqanunpert` varchar(200)
,`rqanun_apbkpert` varchar(200)
,`rperbup_apbkpert` varchar(200)
,`pbkdd_apbkpert` varchar(200)
,`risalah_sidang` varchar(200)
,`absen_peserta` varchar(200)
,`neraca` varchar(200)
,`lra` varchar(200)
,`calk` varchar(200)
,`lo` varchar(200)
,`lpe` varchar(200)
,`lpsal` varchar(200)
,`lak` varchar(200)
,`laporan_pemeriksaan` varchar(200)
,`status` int(11)
,`tanggal_update` timestamp
,`tanggal_upload` timestamp
,`kd_satker` varchar(100)
);

-- --------------------------------------------------------

--
-- Struktur dari tabel `wilayah`
--

CREATE TABLE `wilayah` (
  `idd_wilayah` int(100) NOT NULL,
  `nama_wilayah` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `wilayah`
--

INSERT INTO `wilayah` (`idd_wilayah`, `nama_wilayah`) VALUES
(1, 'Wilayah I'),
(2, 'Wilayah II'),
(3, 'Wilayah III'),
(4, 'Provinsi');

-- --------------------------------------------------------

--
-- Struktur untuk view `view_pertanggungjawaban_2022_ev`
--
DROP TABLE IF EXISTS `view_pertanggungjawaban_2022_ev`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_pertanggungjawaban_2022_ev`  AS SELECT `pertanggungjawaban2022`.`idd_tahapan` AS `idd_tahapan`, `pertanggungjawaban2022`.`tahun_anggaran` AS `tahun_anggaran`, `pertanggungjawaban2022`.`surat_pengantar` AS `surat_pengantar`, `pertanggungjawaban2022`.`skd_rqanunpert` AS `skd_rqanunpert`, `pertanggungjawaban2022`.`rqanun_apbkpert` AS `rqanun_apbkpert`, `pertanggungjawaban2022`.`rperbup_apbkpert` AS `rperbup_apbkpert`, `pertanggungjawaban2022`.`pbkdd_apbkpert` AS `pbkdd_apbkpert`, `pertanggungjawaban2022`.`risalah_sidang` AS `risalah_sidang`, `pertanggungjawaban2022`.`absen_peserta` AS `absen_peserta`, `pertanggungjawaban2022`.`neraca` AS `neraca`, `pertanggungjawaban2022`.`lra` AS `lra`, `pertanggungjawaban2022`.`calk` AS `calk`, `pertanggungjawaban2022`.`lo` AS `lo`, `pertanggungjawaban2022`.`lpe` AS `lpe`, `pertanggungjawaban2022`.`lpsal` AS `lpsal`, `pertanggungjawaban2022`.`lak` AS `lak`, `pertanggungjawaban2022`.`laporan_pemeriksaan` AS `laporan_pemeriksaan`, `pertanggungjawaban2022`.`status` AS `status`, `pertanggungjawaban2022`.`tanggal_update` AS `tanggal_update`, `pertanggungjawaban2022`.`tanggal_upload` AS `tanggal_upload`, `pertanggungjawaban2022`.`kd_satker` AS `kd_satker` FROM `pertanggungjawaban2022``pertanggungjawaban2022`  ;

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
-- Indeks untuk tabel `pertanggungjawaban2022`
--
ALTER TABLE `pertanggungjawaban2022`
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
  MODIFY `idd_evaluator` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `pertanggungjawaban`
--
ALTER TABLE `pertanggungjawaban`
  MODIFY `idd_evaluasi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pertanggungjawaban2022`
--
ALTER TABLE `pertanggungjawaban2022`
  MODIFY `idd_evaluasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `idd_user` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT untuk tabel `wilayah`
--
ALTER TABLE `wilayah`
  MODIFY `idd_wilayah` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
