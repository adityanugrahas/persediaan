-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 02, 2021 at 11:13 AM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 8.0.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `baperkrw`
--

-- --------------------------------------------------------

--
-- Table structure for table `anggaran`
--

CREATE TABLE `anggaran` (
  `id_anggaran` varchar(25) NOT NULL,
  `tahun_anggaran` varchar(10) NOT NULL,
  `akun_anggaran` varchar(20) NOT NULL,
  `ket_anggaran` varchar(150) NOT NULL,
  `pagu_anggaran` int(11) NOT NULL,
  `serapan_anggaran` int(11) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bagian`
--

CREATE TABLE `bagian` (
  `id_seksi` varchar(25) NOT NULL,
  `induk` varchar(35) NOT NULL,
  `bagian` varchar(35) NOT NULL,
  `keterangan` text NOT NULL,
  `urutan` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bagian`
--

INSERT INTO `bagian` (`id_seksi`, `induk`, `bagian`, `keterangan`, `urutan`) VALUES
('20210213112744', 'root', 'KAKANIM', 'Kepala Kantor', 0),
('210510023455', '20210213112744', 'TU', 'Sub Bag Tata Usaha', 0),
('210521030442', '20210213112744', 'TIKKIM', 'Teknologi Informasi dan Komunikasi Keimigrasian', 0),
('210521025455', '20210213112744', 'VERDOKJAL', 'Pelayanan dan Verifikasi Dokumen Perjalanan', 0),
('210521025819', '20210213112744', 'INTELDAKIM', 'Intelijen dan Penindakan Keimigrasian', 0),
('210521033523', '20210213112744', 'INTALTUSKIM', 'Izin Tinggal dan Status Keimigrasian', 0);

-- --------------------------------------------------------

--
-- Table structure for table `bmn`
--

CREATE TABLE `bmn` (
  `id_bmn` varchar(25) NOT NULL,
  `kat_bmn` varchar(25) NOT NULL,
  `kode_bmn` varchar(30) NOT NULL,
  `nama_bmn` varchar(50) NOT NULL,
  `jumlah_bmn` int(11) NOT NULL,
  `satuan` varchar(10) NOT NULL,
  `harga_satuan` int(11) NOT NULL,
  `asal_oleh` varchar(50) NOT NULL,
  `tgl_oleh` date NOT NULL,
  `bukti_oleh` varchar(50) NOT NULL,
  `harga_oleh` int(11) NOT NULL,
  `stok_minimal` int(11) NOT NULL,
  `keterangan` varchar(50) NOT NULL,
  `gambar` varchar(150) NOT NULL,
  `aktif` enum('ya','tidak','','') NOT NULL DEFAULT 'ya',
  `pengguna` varchar(100) NOT NULL,
  `lokasi` varchar(50) NOT NULL,
  `kondisi` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bmn`
--

INSERT INTO `bmn` (`id_bmn`, `kat_bmn`, `kode_bmn`, `nama_bmn`, `jumlah_bmn`, `satuan`, `harga_satuan`, `asal_oleh`, `tgl_oleh`, `bukti_oleh`, `harga_oleh`, `stok_minimal`, `keterangan`, `gambar`, `aktif`, `pengguna`, `lokasi`, `kondisi`) VALUES
('210422073904', '210421110233', 'Pngadaan', 'Laptop', 13, 'Unit', 0, 'Lp', '2021-04-22', 'BAST', 15000000, 0, '     Laptop HP', '210423210908.JPG', 'ya', '', '', ''),
('210421033809', '210421110233', 'pre2020', 'Printer Epsom ', 12, 'Unit', 0, 'Beli ', '2021-04-21', 'kwitansi', 12000000, 0, 'tetsetestes', '210421033809.jpg', 'ya', '', '', ''),
('210422014928', '210421114425', 'bmt', 'Sepeda Motor', 10, 'Unit', 0, 'Pengadaan', '2021-04-22', 'BAST tgl bayar', 30000000, 0, 'Yamaha Enmek', '210422014928.JPG', 'ya', '', '', ''),
('210423085818', '210421114425', 'Mbl', 'Toyota Kijang', 2, 'unit', 0, 'Mbl', '2021-04-23', 'BAST tgl bayar', 30000000, 0, '  ', '210423085818.jpeg', 'ya', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `bmn_dist`
--

CREATE TABLE `bmn_dist` (
  `id_dist` varchar(25) NOT NULL,
  `id_bmn` varchar(30) NOT NULL,
  `jumlah_pakai` int(11) NOT NULL,
  `jumlah_kembali` int(11) NOT NULL,
  `keterangan` varchar(50) NOT NULL,
  `seksi` varchar(30) NOT NULL,
  `pengguna` varchar(100) NOT NULL,
  `lokasi` varchar(50) NOT NULL,
  `kondisi` varchar(50) NOT NULL,
  `tgl_dist` datetime NOT NULL,
  `nama_lampiran` varchar(30) NOT NULL,
  `lampiran` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bmn_dist`
--

INSERT INTO `bmn_dist` (`id_dist`, `id_bmn`, `jumlah_pakai`, `jumlah_kembali`, `keterangan`, `seksi`, `pengguna`, `lokasi`, `kondisi`, `tgl_dist`, `nama_lampiran`, `lampiran`) VALUES
('210423121932', '210422014928', 3, 0, 'SETSGSG SGSE', '20210213113016', '20210212134326', 'Ruang TU', 'Baik', '2021-04-23 00:00:00', '', '210423121932.JPG'),
('210423112507', '210423085818', 1, 0, 'fsdfdsf', '20210213112744', '20210212134326', 'Ruang TU', 'Baik', '2021-04-23 23:24:00', '', '210423112507.png'),
('210425124931', '210422014928', 2, 0, 'dipake ucup', '20210213113305', '20210213144643', 'Parkitan', 'Baik', '2021-04-25 00:49:00', 'BAST pdf', '210425124931.pdf'),
('210425013519', '210422014928', 2, 0, 'dadadad', '20210213113207', '20210408143005', 'Ruang TU', 'Baik', '2021-04-25 01:35:00', 'BAST pdf', '210425013519.pdf'),
('210425013631', '210422014928', 0, 2, 'embalian', '20210213113207', '20210408143005', 'Parkitan', 'Baik', '2021-05-02 01:36:00', 'penyerahan', '210425013631.pdf'),
('210425022243', '210422073904', 5, 0, 'sadsdadasd', '20210213112744', '20210212134326', 'Ruang TU', 'Baik', '2021-04-25 02:22:00', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kat` varchar(20) NOT NULL,
  `editor` varchar(30) NOT NULL,
  `nama_kat` varchar(30) NOT NULL,
  `ket_kat` varchar(250) NOT NULL,
  `status` varchar(15) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kat`, `editor`, `nama_kat`, `ket_kat`, `status`) VALUES
('210524022605', '', 'Kertas', 'A4, F4, Cover, Kertas lainnya', 'ON'),
('210524024546', '', 'Penanganan Covid-19', 'Masker, Handscoon, Alat Rapid Test, Hand Sanitizer dll', 'ON'),
('210524022537', '', 'Perlengkapan Kantor Lainnya', 'Lem, Double Tip, Lakban, Bak Stempel dll', 'ON'),
('210524021730', '', 'Alat Tulis', 'Alat Tulis dan Buku', 'ON'),
('210524021832', '', 'Tinta', 'Seluruh Tinta baik printer dan Stamp', 'ON'),
('210524022002', '', 'Baterai', 'AA, AAA dll', 'ON'),
('210524021544', '20210212134326', 'Dokim', 'Seluruh Dokumen Keimigrasian Paspor, E-KITAP, perdim dll', 'ON');

-- --------------------------------------------------------

--
-- Table structure for table `kat_bmn`
--

CREATE TABLE `kat_bmn` (
  `id_kat` varchar(20) NOT NULL,
  `editor` varchar(30) NOT NULL,
  `nama_kat` varchar(30) NOT NULL,
  `ket_kat` varchar(250) NOT NULL,
  `status` varchar(15) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kat_bmn`
--

INSERT INTO `kat_bmn` (`id_kat`, `editor`, `nama_kat`, `ket_kat`, `status`) VALUES
('210421110233', '', 'Pengolah Data ', 'PC, Laptop, Printer dll', 'on'),
('210421114425', '', 'Kendaraan Operasional', 'Mobil, Motor dll.', 'on');

-- --------------------------------------------------------

--
-- Table structure for table `nota`
--

CREATE TABLE `nota` (
  `id_nota` varchar(35) NOT NULL,
  `kode_nota` varchar(35) NOT NULL,
  `jenis` varchar(20) NOT NULL,
  `id_anggaran` varchar(25) NOT NULL,
  `tanggal` datetime NOT NULL,
  `pemroses` varchar(35) NOT NULL,
  `jum_out` int(11) NOT NULL,
  `jum_in` int(11) NOT NULL,
  `keterangan` varchar(250) NOT NULL,
  `lampiran` varchar(50) NOT NULL,
  `status` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `notif`
--

CREATE TABLE `notif` (
  `id_notif` varchar(30) NOT NULL,
  `tgl_notif` datetime NOT NULL,
  `id_seksi` varchar(20) NOT NULL,
  `id_petugas` varchar(20) NOT NULL,
  `id_barang` varchar(20) NOT NULL,
  `status` varchar(1) NOT NULL,
  `keterangan` varchar(250) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id_pesanan` varchar(25) NOT NULL,
  `id_seksi` varchar(25) NOT NULL,
  `petugas` varchar(25) NOT NULL,
  `id_barang` varchar(25) NOT NULL,
  `nama_barang` varchar(50) NOT NULL,
  `ket_barang` varchar(100) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `satuan` varchar(25) NOT NULL,
  `tgl_pesan` varchar(25) NOT NULL,
  `keterangan` varchar(50) NOT NULL,
  `lampiran` varchar(50) NOT NULL,
  `jumlah_stok` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `petugas`
--

CREATE TABLE `petugas` (
  `id_petugas` varchar(25) NOT NULL,
  `users_id` varchar(20) NOT NULL,
  `pwd` varchar(20) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `seksi` varchar(20) NOT NULL,
  `jabatan` varchar(25) NOT NULL,
  `p_level` varchar(10) NOT NULL,
  `photo` varchar(30) NOT NULL,
  `ttd` varchar(30) NOT NULL,
  `last_in` varchar(30) NOT NULL,
  `last_out` varchar(30) NOT NULL,
  `urutan` int(11) NOT NULL,
  `p_status` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `petugas`
--

INSERT INTO `petugas` (`id_petugas`, `users_id`, `pwd`, `nama`, `seksi`, `jabatan`, `p_level`, `photo`, `ttd`, `last_in`, `last_out`, `urutan`, `p_status`) VALUES
('20210212134326', 'admin', 'zxcvb', 'Administrator', '210510023455', '', 'su', '20210212134326.png', '', '2021-05-27 14:36:53', '2021-05-27 14:36:05', 0, 'aktif'),
('210524015807', '198910012015032005', 'Mega0110', 'MEGAWATI', '210510023455', '', 'adm', '', '', '2021-05-24 15:26:25', '2021-05-24 15:26:10', 4, 'aktif'),
('210510023649', 'umum', 'umum', 'operator Umum', '210510023511', '', 'opr', '', '', '', '', 2, 'aktif'),
('210521034550', '198108032009122003', 'ay18', 'Faridah Irawati', '20210213112744', '', 'opr', '', '', '2021-05-27 09:29:52', '2021-05-27 13:43:53', 3, 'aktif'),
('210510023620', 'kakanim', 'kakanim', 'Winarko', '20210213112744', '', 'adm', '', '', '', '', 1, 'aktif'),
('210524020143', 'verdokjal_karawang', '36987', 'MUHAMMAD RADIFAN ALDIANSYAH', '210521025455', '', 'opr', '', '', '2021-05-27 15:22:45', '', 5, 'aktif'),
('210524020230', 'intaltuskim_karawang', '36987', 'PURWANTO', '210521033523', '', 'opr', '', '', '2021-05-27 13:51:21', '2021-05-27 13:51:54', 6, 'aktif'),
('210524020326', 'inteldakim_karawang', '36987', 'GALIH DWI PRAWITA', '210521025819', '', 'opr', '', '', '', '', 7, 'aktif'),
('210524020405', 'tikkim_karawang', '36987', 'GUNTUR WIDYANTO', '210521030442', '', 'opr', '', '', '', '', 8, 'aktif'),
('210524021212', '198710052009121003', '36987', 'DEDEN SANTA WINATA KUSUMA', '210510023455', '', 'opr', '', '', '', '', 9, 'aktif');

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE `setting` (
  `setting_id` varchar(20) NOT NULL,
  `title_head` varchar(30) NOT NULL,
  `nama_kantor` varchar(50) NOT NULL,
  `alamat_kantor` varchar(200) NOT NULL,
  `telp_kantor` varchar(15) NOT NULL,
  `email_kantor` varchar(30) NOT NULL,
  `logo_header` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`setting_id`, `title_head`, `nama_kantor`, `alamat_kantor`, `telp_kantor`, `email_kantor`, `logo_header`) VALUES
('20210212131943', 'IMIGRASI KARAWANG', 'KANTOR IMIGRASI KELAS I NON TPI KARAWANG', 'JL. JEND. AHAMAD YANI  NO 18 (BY PASS) KARAWANG', '081222311607', 'kanim2karawang@gmail.com', '210524032446-logo.png');

-- --------------------------------------------------------

--
-- Table structure for table `stok_barang`
--

CREATE TABLE `stok_barang` (
  `id_barang` varchar(25) NOT NULL,
  `kategori` varchar(25) NOT NULL,
  `nama_barang` varchar(50) NOT NULL,
  `jumlah_stok` int(11) NOT NULL,
  `satuan` varchar(10) NOT NULL,
  `harga_satuan` int(11) NOT NULL,
  `stok_minimal` int(11) NOT NULL,
  `keterangan` varchar(50) NOT NULL,
  `gambar` varchar(150) NOT NULL,
  `aktif` enum('ya','tidak','','') NOT NULL DEFAULT 'ya'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `stok_barang`
--

INSERT INTO `stok_barang` (`id_barang`, `kategori`, `nama_barang`, `jumlah_stok`, `satuan`, `harga_satuan`, `stok_minimal`, `keterangan`, `gambar`, `aktif`) VALUES
('21050701', '210524021730', 'SPIDOL UK.KECIL WARNA HIJAU', 50, '', 13200, 5, '', '', 'ya'),
('21050702', '210524021730', 'SPIDOL KECIL UK.KECIL WARNA HITAM', 30, '', 13200, 5, '', '', 'ya'),
('21050703', '210524022537', 'STABILLO BOS MERAH', 4, '', 88000, 5, '', '', 'ya'),
('21050704', '210524021730', 'PULPEN PILOT BOLLINER HITAM', 16, '', 176000, 5, '', '', 'ya'),
('21050705', '210524021730', 'SPIDOL SNOWMAN BESAR BOARDMARKER HITAM', 13, '', 99000, 5, '', '', 'ya'),
('21050706', '210524021730', 'SPIDOL SNOWMAN BESAR WATERFROOP  HITAM', 14, '', 99000, 5, '', '', 'ya'),
('21050707', '210524021730', 'PULPEN PILOT BOLLINER HIJAU', 10, '', 181500, 5, '', '', 'ya'),
('21050708', '210524022537', 'STABILLO BOS HIJAU', 9, '', 88000, 5, '', '', 'ya'),
('21050709', '210524022537', 'STABILLO BOS PINK', 10, '', 8800, 5, '', '', 'ya'),
('21050710', '210524022537', 'STABILLO BOS KUNING', 6, '', 77000, 5, '', '', 'ya'),
('21050711', '210524022537', 'STABILLO BOS BIRU', 6, '', 88000, 5, '', '', 'ya'),
('21050712', '210524021730', 'PULPEN PILOT', 48, '', 22000, 5, '', '', 'ya'),
('21050713', '210524021730', 'PULPEN KENKO K-1', 10, '', 49500, 5, '', '', 'ya'),
('21050714', '210524021730', 'PENSIL 2B', 9, '', 44000, 5, '', '', 'ya'),
('21050715', '210524021730', 'Pulpen Meja', 0, '', 0, 5, '', '', 'ya'),
('21050716', '210524021730', 'Pulpen Standard AE-7', 0, '', 0, 5, '', '', 'ya'),
('21050717', '210524021730', 'Pulpen Pilot Bolliner Biru', 16, '', 181500, 5, '', '', 'ya'),
('21050718', '210524021730', 'Pulpen Joyko JK-100', 9, '', 26400, 5, '', '', 'ya'),
('21050719', '210524021730', 'Pulpen Jel Ink Pen Biru', 4, '', 4000, 5, '', '', 'ya'),
('21050720', '210524021730', 'DRAWING PEN', 0, '', 0, 5, '', '', 'ya'),
('21050721', '210524022537', 'Pembolong Kertas Besar', 0, '', 0, 5, '', '', 'ya'),
('21050722', '210524021832', ' TINTA STEMPEL WARNA VIOLET', 35, 'pcs', 31900, 5, '', '', 'ya'),
('21050723', '210524021832', ' TINTA STEMPEL WARNA MERAH', 15, 'pcs', 31900, 5, 'sdasda', '', 'tidak'),
('21050724', '210524022537', 'PAPER CLIPS', 1, '', 22000, 5, '', '', 'ya'),
('21050725', '210524022537', 'BINDER CLIPS BESAR 260', 0, '', 0, 5, '', '', 'ya'),
('21050726', '210524022537', 'BINDER CLIPS SEDANG 200', 39, '', 12320, 5, '', '', 'ya'),
('21050727', '210524022537', 'BINDER CLIPS KECIL', 20, '', 6600, 5, '', '', 'ya'),
('21050728', '210524022537', 'STAPLER 23/6 UK.BESAR', 10, '', 104500, 5, '', '', 'ya'),
('21050729', '210524022537', 'STAPLER HD 10 UK.KECIL', 138, '', 53900, 5, '', '', 'ya'),
('21050730', '210524022537', 'ISI STAPLER UK.BESAR', 20, '', 62040, 5, '', '', 'ya'),
('21050731', '210524022537', 'ISI STAPLER UK.KECIL', 63, '', 29755, 5, '', '', 'ya'),
('21050732', '210524022537', 'TRIGONAL CLIPS', 37, '', 25300, 5, '', '', 'ya'),
('21050733', '210524022537', 'ISI CUTTER KECIL', 40, '', 32285, 5, '', '', 'ya'),
('21050734', '210524022537', 'SNELHECTER PLASTIK', 5, '', 83985, 5, '', '', 'ya'),
('21050735', '210524022537', 'DIAGONAL CLIPS', 1, '', 9983, 5, '', '', 'ya'),
('21050736', '210524021730', 'PENGHAPUS PENSIL STADLER\n', 22, '', 5500, 5, '', '', 'ya'),
('21050737', '210524022537', 'TIP EX KENKO PEN', 16, '', 60500, 5, '', '', 'ya'),
('21050738', '210524021730', 'BUKU EXPEDISI KECIL', 4, '', 37510, 5, '', '', 'ya'),
('21050739', '210524021730', 'BUKU FOLIO BERGARIS', 20, '', 71500, 5, '', '', 'ya'),
('21050740', '210524021730', 'BUKU EKSPEDISI BESAR', 7, '', 71500, 5, '', '', 'ya'),
('21050741', '210524022537', 'BOX FILE PLASTIK', 10, '', 24805, 5, '', '', 'ya'),
('21050742', '210524022537', 'MAP FILE PLASTIK', 20, '', 82500, 5, '', '', 'ya'),
('21050743', '210524022537', 'Map Dinas Warna Kuning', 36200, '', 3000000, 5, '', '', 'ya'),
('21050744', '210524022537', 'Map Dinas Warna Merah', 150, '', 3000, 5, '', '', 'ya'),
('21050745', '210524022537', 'Map Dinas Warna Hijau', 3000, '', 3000000, 5, '', '', 'ya'),
('21050746', '210524022537', 'MAP DINAS BIRU BUFFALO', 90, '', 6000, 5, '', '', 'ya'),
('21050747', '210524021730', 'PENGGARIS BAHAN BESI UK.60 cm', 19, '', 6710, 5, '', '', 'ya'),
('21050748', '210524021730', 'PENGGARIS MIKA UK. 30 CM', 42, '', 2750, 5, '', '', 'ya'),
('21050749', '210524021730', 'Penggaris Besi 30 Cm', 11, '', 6470, 5, '', '', 'ya'),
('21050750', '210524022537', 'CUTTER UK.BESAR', 7, '', 16500, 5, '', '', 'ya'),
('21050751', '210524022537', 'ISI CUTTER BESAR', 20, '', 58135, 5, '', '', 'ya'),
('21050752', '210524022537', 'CUTTER KECIL', 39, '', 5500, 5, '', '', 'ya'),
('21050753', '210524022537', 'GUNTING BESAR', 33, '', 15510, 5, '', '', 'ya'),
('21050754', '210524021730', 'RAUTAN PENSIL', 9, '', 33000, 5, '', '', 'ya'),
('21050755', '210524022537', 'GUNTING KECIL', 52, '', 6600, 5, '', '', 'ya'),
('21050756', '210524022537', 'LAKBAN BESAR HITAM', 18, '', 16500, 5, '', '', 'ya'),
('21050757', '210524022537', 'DOUBLE TAPE SEDANG', 9, '', 5170, 5, '', '', 'ya'),
('21050758', '210524022537', 'LEM KERTAS TUBE', 1, '', 94400, 5, '', '', 'ya'),
('21050759', '210524022537', 'LEM FOX BESAR', 11, '', 11550, 5, '', '', 'ya'),
('21050760', '210524022537', 'LAKBAN BESAR COKLAT / BENING', 43, '', 18700, 5, '', '', 'ya'),
('21050761', '210524022537', 'Doubletape Nachi 2\"', 69, '', 5500, 5, '', '', 'ya'),
('21050762', '210524022537', 'Pronto Mark and Notes', 30, '', 11000, 5, '', '', 'ya'),
('21050763', '210524022537', 'DOUBLETAPE NACHI 1\"', 40, '', 2750, 5, '', '', 'ya'),
('21050764', '210524022537', 'LAKBAN KECIL', 46, '', 11000, 5, '', '', 'ya'),
('21050765', '210524022537', 'LEM FOX KECIL', 9, '', 7755, 5, '', '', 'ya'),
('21050766', '210524022537', 'LEM STICK', 9, '', 49500, 5, '', '', 'ya'),
('21050767', '210524022537', 'Lakban Kertas', 14, '', 6600, 5, '', '', 'ya'),
('21050768', '210524022605', 'KERTAS HVS UKURAN A4', 110, '', 36300, 5, '', '', 'ya'),
('21050769', '210524022605', 'KERTAS HVS F4 70 GRM', 105, '', 41800, 5, '', '', 'ya'),
('21050770', '210524022605', 'SURAT PERNYATAAN PENAMBAHAN NAMA', 30, '', 82500, 5, '', '', 'ya'),
('21050771', '210524022605', 'SURAT PERNYATAAN ORANG TUA', 28, '', 82500, 5, '', '', 'ya'),
('21050772', '210524022537', 'MAP DINAS BUFALO WARNA BIRU', 0, '', 0, 5, '', '', 'ya'),
('21050773', '210524022605', 'SURAT PERNYATAAN DAN JAMINAN', 2, '', 82500, 5, '', '', 'ya'),
('21050774', '210524022605', 'TANDA TERIMA PERMOHONAN ERP/MERP NCR 2', 5, '', 22500, 5, '', '', 'ya'),
('21050775', '210524022605', 'Kertas Foto Data Print A4', 37, '', 32285, 5, '', '', 'ya'),
('21050776', '210524022605', 'Kertas Warna F4 80 gr', 5, '', 62040, 5, '', '', 'ya'),
('21050777', '210524022605', 'Kertas Warna A4 80 gr', 10, '', 60775, 5, '', '', 'ya'),
('21050778', '210524022605', 'Kertas Roll lebar 8 cm', 32, '', 22000, 5, '', '', 'ya'),
('21050779', '210524022605', 'BON BARANG BAHAN KERTAS 2', 27, '', 35750, 5, '', '', 'ya'),
('21050780', '210524022605', 'STP (SURAT TANDA TERIMA PASPOR) KERTAS NCR 3', 50, '', 41250, 5, '', '', 'ya'),
('21050781', '210524022605', 'SSP (SURAT SETORAN PAJAK) KERTAS NCR 5 RANGKAP', 5, '', 93500, 5, '', '', 'ya'),
('21050782', '210524022605', 'SURAT PERNYATAAN PASPOR KERTAS HVS 70 gr', 60, '', 82500, 5, '', '', 'ya'),
('21050783', '210524022605', 'Surat Pernyataan UU No. 6 Tahun 2011', 15, '', 121000, 5, '', '', 'ya'),
('21050784', '210524022605', 'Surat Perjalanan RI untuk WNI', 10, '', 143000, 5, '', '', 'ya'),
('21050785', '210524022605', 'KERTAS STICKER', 2, '', 33000, 5, '', '', 'ya'),
('21050786', '210524021544', 'Perdim 11', 60, '', 93500, 5, '', '', 'ya'),
('21050787', '210524022605', 'KERTAS COVER UK.21.5X33 CM', 17, '', 29755, 5, '', '', 'ya'),
('21050788', '210524022537', 'AMPLOP WARNA PUTIH UK.BESAR', 45, '', 22000, 5, '', '', 'ya'),
('21050789', '210524022537', 'AMPLOP DINAS SEDANG UKURAN FOLIO', 50, '', 800, 5, '', '', 'ya'),
('21050790', '210524022537', 'AMPLOP PUTIH UK.SEDANG', 39, '', 13200, 5, '', '', 'ya'),
('21050791', '210524022537', 'Amplop Coklat Folio', 5, '', 88000, 5, '', '', 'ya'),
('21050792', '210524022605', 'KERTAS POST IT', 19, '', 11000, 5, '', '', 'ya'),
('21050793', '210524022605', 'PLASTIK KERTAS COVER PUTIH', 44, '', 38500, 5, '', '', 'ya'),
('21050794', '210524021730', 'DATACARD D3000 S11-094 (CYAN)', 4, '', 2279700, 5, '', '', 'ya'),
('21050795', '210524021832', 'DATACARD D3000 S11-095 (MAGENTA)', 4, '', 2279700, 5, '', '', 'ya'),
('21050796', '210524021832', 'DATACARD D3000 S11-096 (YELLOW)', 4, '', 2279700, 5, '', '', 'ya'),
('21050797', '210524021832', 'DATACARD D3000 S11-097 (BLACK)', 4, '', 2251300, 5, '', '', 'ya'),
('21050798', '210524021832', 'PRINTHEAD CYAN/MAGENTA SI 1000-98', 0, '', 0, 5, '', '', 'ya'),
('21050799', '210524021832', 'PRINTHEADBLACK/YELLOW SI 1000-99', 0, '', 0, 5, '', '', 'ya'),
('21050800', '210524021832', 'Datacard Color Ribbon Kit YMCKT-KT', 0, '', 0, 5, '', '', 'ya'),
('21050801', '210524021832', 'Tinta Paspor D3000 black', 0, '', 0, 5, '', '', 'ya'),
('21050802', '210524021832', 'Tinta Paspor D3000 Magenta', 3, '', 2318600, 5, '', '', 'ya'),
('21050803', '210524021832', 'Tinta Paspor D3000 yellow', 0, '', 0, 5, '', '', 'ya'),
('21050804', '210524021832', 'Tinta Paspor D3000 cyan', 3, '', 2318600, 5, '', '', 'ya'),
('21050805', '210524022537', 'BAK STEMPEL BESAR', 20, '', 38500, 5, '', '', 'ya'),
('21050806', '210524022537', 'CAP TANGGAL', 2, '', 11000, 5, '', '', 'ya'),
('21050807', '210524022537', 'BAK STEMPEL KECIL', 34, '', 33000, 5, '', '', 'ya'),
('21050808', '210524021832', 'Tinta Epson TO6731 Black For Printer L800 Supllies', 12, '', 181500, 5, '', '', 'ya'),
('21050809', '210524021832', 'Tinta Epson TO6732 Cyan For Printer L800 Supllies', 21, '', 181500, 5, '', '', 'ya'),
('21050810', '210524021832', 'Tinta Epson TO6733 Magenta For Printer L800 Suplli', 22, '', 181500, 5, '', '', 'ya'),
('21050811', '210524021832', 'Tinta Epson TO6734 Yellow For Printer L800 Supllie', 22, '', 181500, 5, '', '', 'ya'),
('21050812', '210524021832', 'Tinta Epson TO6735 Light Cyan For Printer Epson L8', 22, '', 181500, 5, '', '', 'ya'),
('21050813', '210524021832', 'Tinta Epson TO6736 Light Magenta For Printer Epson', 22, '', 181500, 5, '', '', 'ya'),
('21050814', '210524021832', 'Tinta Epson 003 Cyan Supllies  For Printer L110', 4, '', 110000, 5, '', '', 'ya'),
('21050815', '210524021832', 'Tinta Epson 003 Yellow Supllies  For Printer L110', 4, '', 110000, 5, '', '', 'ya'),
('21050816', '210524021832', 'Tinta Epson 003 Magenta Supllies  For Printer L110', 4, '', 110000, 5, '', '', 'ya'),
('21050817', '210524021832', 'Tinta Toner HP laserjet CE285a 85A Supllies For Pr', 7, '', 1210000, 5, '', '', 'ya'),
('21050818', '210524021832', 'Toner HP C9351 A No. 21 (black)', 13, '', 231000, 5, '', '', 'ya'),
('21050819', '210524021832', 'Toner HP C 9352 No. 22 (warna)', 12, '', 253550, 5, '', '', 'ya'),
('21050820', '210524021832', 'Toner HP C94 Black', 1, '', 251685, 5, '', '', 'ya'),
('21050821', '210524021832', 'Toner HP C95 Colour', 3, '', 338895, 5, '', '', 'ya'),
('21050822', '210524021832', 'Toner Fotocopy Canon iR 2525', 1, '', 803000, 5, '', '', 'ya'),
('21050823', '210524021832', 'HP 950 Black', 1, '', 577500, 5, '', '', 'ya'),
('21050824', '210524021832', 'HP 951 Cyan', 2, '', 412500, 5, '', '', 'ya'),
('21050825', '210524021832', 'HP 951 Magenta', 6, '', 330000, 5, '', '', 'ya'),
('21050826', '210524021832', 'HP 951 Yellow', 3, '', 330000, 5, '', '', 'ya'),
('21050827', '210524021832', 'Toner HP Laserjet 78a', 2, '', 1408000, 5, '', '', 'ya'),
('21050828', '210524021832', 'TONER HP 26 A', 6, '', 2090000, 5, '', '', 'ya'),
('21050829', '210524021832', 'TONER HP LASERJET 79A', 5, '', 1100000, 5, '', '', 'ya'),
('21050830', '210524021832', 'TINTA EPSON 664 L 360 BLACK', 12, '', 121000, 5, '', '', 'ya'),
('21050831', '210524021832', 'TINTA EPSON 664 L 360 MAGENTA', 3, '', 121000, 5, '', '', 'ya'),
('21050832', '210524021832', 'TINTA EPSON 664 L 360 YELLOW', 2, '', 121000, 5, '', '', 'ya'),
('21050833', '210524021832', 'TINTA EPSON 664 L 360 CYAN', 3, '', 121000, 5, '', '', 'ya'),
('21050834', '210524021832', 'Datacard Ribbon Kit  YMCKT', 5, '', 2340000, 5, '', '', 'ya'),
('21050835', '210524021832', 'D3000 Ink Crtg, Cyan SI1000', 2, '', 1793000, 5, '', '', 'ya'),
('21050836', '210524021832', 'D3000 Ink Crtg, Magenta SI1000', 2, '', 1793000, 5, '', '', 'ya'),
('21050837', '210524021832', 'D3000 Ink Crtg, Yellow SI1000', 2, '', 1793000, 5, '', '', 'ya'),
('21050838', '210524021832', 'D3000 Ink Crtg, Pigmen Black SI1000', 2, '', 1675000, 5, '', '', 'ya'),
('21050839', '210524022002', 'Baterai AA', 0, '', 0, 5, '', '', 'ya'),
('21050840', '210524022002', 'Baterai AAA', 0, '', 0, 5, '', '', 'ya'),
('21050841', '210524022002', 'Baterai Besar', 0, '', 0, 5, '', '', 'ya'),
('21050842', '210524024546', 'Masker', 0, '', 0, 5, '', '', 'ya'),
('21050843', '210524024546', 'handscoon', 0, '', 0, 5, '', '', 'ya'),
('21050844', '210524024546', 'hand sanitizer', 0, '', 0, 5, '', '', 'ya'),
('21050845', '210524024546', 'cairan desinfektan', 0, '', 0, 5, '', '', 'ya'),
('21050846', '210524024546', 'Alat Rapid Tes', 0, '', 0, 5, '', '', 'ya'),
('21050847', '210524021544', 'SPRI/Paspor Biasa 48 Hal', 3250, '', 88990000, 5, '', '', 'ya'),
('21050848', '210524021544', 'SPRI/Paspor Biasa 24 Hal', 0, '', 0, 5, '', '', 'ya'),
('21050849', '210524021544', 'Perdim 11', 0, '', 0, 5, '', '', 'ya'),
('21050850', '210524021544', 'Perdim 23', 1000, '', 147000, 5, '', '', 'ya'),
('21050851', '210524021544', 'Perdim 24', 500, '', 147, 5, '', '', 'ya'),
('21050852', '210524021544', 'Perdim 25', 500, '', 147, 5, '', '', 'ya'),
('21050853', '210524021544', 'Perdim 27', 500, '', 147, 5, '', '', 'ya'),
('21050854', '210524021544', 'Perdim 28', 0, '', 0, 5, '', '', 'ya'),
('21050855', '210524021544', 'SPLP untuk Orang Asing', 4, '', 31000, 5, '', '', 'ya'),
('21050856', '210524022605', 'Surat Keterangan Keimigrasian', 60, '', 2640, 5, '', '', 'ya'),
('21050857', '210524021544', 'E-KITAP', 0, '', 0, 5, '', '', 'ya'),
('21050858', '210524021544', 'Kartu Afidavit', 30, '', 1750, 5, '', '', 'ya'),
('21050859', '210524021544', 'Sertifikat ABG', 350, '', 650, 5, '', '', 'ya'),
('21050860', '210524021544', 'Formulir tanda terima ABG', 1530, '', 130000, 5, '', '', 'ya'),
('21050861', '210524021544', 'Formulir Pendaftaran ABG', 1530, '', 130000, 5, '', '', 'ya'),
('21050862', '210524022537', 'Pembolong Kertas', 40, '', 12925, 5, '', '', 'ya'),
('21050863', '210524022537', 'ORDNER FILE', 5, '', 297000, 5, '', '', 'ya'),
('21050864', '210524024546', 'tisu tessa 250', 0, '', 0, 5, '', '', 'ya'),
('21050865', '210524024546', 'tissu handtowel', 0, '', 0, 5, '', '', 'ya'),
('21050866', '210524024546', 'Hand Soap', 0, '', 0, 5, '', '', 'ya'),
('21050867', '210524024546', 'Tisu Basah', 0, '', 0, 5, '', '', 'ya'),
('210524023638', '210524021544', 'Paspor 48 H', 0, 'Buku', 88990, 1000, '', '', 'tidak'),
('210524024106', '210524021544', 'Paspor 48 H', 0, 'Buku', 88990, 3250, '1000', '', 'tidak');

-- --------------------------------------------------------

--
-- Table structure for table `stok_inout`
--

CREATE TABLE `stok_inout` (
  `id_inout` varchar(25) NOT NULL,
  `jenis` varchar(10) NOT NULL,
  `kode_nota` varchar(25) NOT NULL,
  `petugas` varchar(25) NOT NULL,
  `id_seksi` varchar(25) NOT NULL,
  `tgl` datetime NOT NULL,
  `id_barang` varchar(25) NOT NULL,
  `jml_req` int(11) NOT NULL,
  `jml_in` int(11) NOT NULL,
  `jml_out` int(11) NOT NULL,
  `satuan` varchar(25) NOT NULL,
  `jml_stok` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `sumber_dana` varchar(30) NOT NULL,
  `keterangan` varchar(150) NOT NULL,
  `catatan` varchar(300) NOT NULL,
  `status` varchar(4) NOT NULL,
  `tgl_ok` datetime NOT NULL,
  `lampiran` varchar(150) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `stok_inout`
--

INSERT INTO `stok_inout` (`id_inout`, `jenis`, `kode_nota`, `petugas`, `id_seksi`, `tgl`, `id_barang`, `jml_req`, `jml_in`, `jml_out`, `satuan`, `jml_stok`, `harga`, `sumber_dana`, `keterangan`, `catatan`, `status`, `tgl_ok`, `lampiran`) VALUES
('210525114754', 'out', '210525.1152-24', '210521034550', '20210213112744', '2021-05-25 11:47:54', '21050740', 1, 0, 1, '', 7, 0, '', '-', '', '2', '2021-05-27 11:22:07', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anggaran`
--
ALTER TABLE `anggaran`
  ADD PRIMARY KEY (`id_anggaran`);

--
-- Indexes for table `bagian`
--
ALTER TABLE `bagian`
  ADD PRIMARY KEY (`id_seksi`);

--
-- Indexes for table `bmn`
--
ALTER TABLE `bmn`
  ADD PRIMARY KEY (`id_bmn`);

--
-- Indexes for table `bmn_dist`
--
ALTER TABLE `bmn_dist`
  ADD PRIMARY KEY (`id_dist`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kat`);

--
-- Indexes for table `kat_bmn`
--
ALTER TABLE `kat_bmn`
  ADD PRIMARY KEY (`id_kat`);

--
-- Indexes for table `nota`
--
ALTER TABLE `nota`
  ADD PRIMARY KEY (`id_nota`);

--
-- Indexes for table `notif`
--
ALTER TABLE `notif`
  ADD PRIMARY KEY (`id_notif`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id_pesanan`);

--
-- Indexes for table `petugas`
--
ALTER TABLE `petugas`
  ADD PRIMARY KEY (`id_petugas`);

--
-- Indexes for table `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`setting_id`);

--
-- Indexes for table `stok_barang`
--
ALTER TABLE `stok_barang`
  ADD PRIMARY KEY (`id_barang`);

--
-- Indexes for table `stok_inout`
--
ALTER TABLE `stok_inout`
  ADD PRIMARY KEY (`id_inout`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
