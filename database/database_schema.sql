CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_role` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `jenis_layanan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_layanan` varchar(100) NOT NULL,
  `kode_antrian` char(1) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nip` varchar(18) NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `nip` (`nip`),
  UNIQUE KEY `username` (`username`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
);

CREATE TABLE `presensi` (
  `petugas_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `waktu_datang` time NOT NULL,
  `waktu_pulang` time DEFAULT NULL,
  PRIMARY KEY (`petugas_id`,`tanggal`),
  CONSTRAINT `presensi_ibfk_1` FOREIGN KEY (`petugas_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
);

CREATE TABLE `antrian` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nomor_antrian` varchar(10) NOT NULL,
  `jenis_layanan_id` int(11) NOT NULL,
  `waktu_ambil` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('menunggu','dipanggil','selesai','dilewati') NOT NULL DEFAULT 'menunggu',
  PRIMARY KEY (`id`),
  KEY `jenis_layanan_id` (`jenis_layanan_id`),
  CONSTRAINT `antrian_ibfk_1` FOREIGN KEY (`jenis_layanan_id`) REFERENCES `jenis_layanan` (`id`)
);

CREATE TABLE `pelayanan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `petugas_id` int(11) NOT NULL,
  `antrian_id` int(11) NOT NULL,
  `nama_pelanggan` varchar(255) NOT NULL,
  `instansi_pelanggan` varchar(255) DEFAULT NULL,
  `kontak_pelanggan` varchar(100) DEFAULT NULL,
  `path_surat_pengantar` varchar(255) DEFAULT NULL,
  `kebutuhan_pelanggan` text NOT NULL,
  `hasil_pelayanan` text NOT NULL,
  `waktu_mulai_sesi` timestamp NOT NULL,
  `waktu_selesai_sesi` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `petugas_id` (`petugas_id`),
  KEY `antrian_id` (`antrian_id`),
  CONSTRAINT `pelayanan_ibfk_1` FOREIGN KEY (`petugas_id`) REFERENCES `users` (`id`),
  CONSTRAINT `pelayanan_ibfk_2` FOREIGN KEY (`antrian_id`) REFERENCES `antrian` (`id`)
);

CREATE TABLE `survei_kepuasan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pelayanan_id` int(11) NOT NULL,
  `skor_kepuasan` tinyint(4) NOT NULL,
  `saran_masukan` text DEFAULT NULL,
  `waktu_isi` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `pelayanan_id` (`pelayanan_id`),
  CONSTRAINT `survei_kepuasan_ibfk_1` FOREIGN KEY (`pelayanan_id`) REFERENCES `pelayanan` (`id`) ON DELETE CASCADE
);

CREATE TABLE `buku_tamu_khusus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_tamu` varchar(255) NOT NULL,
  `instansi_tamu` varchar(255) DEFAULT NULL,
  `kontak_tamu` varchar(100) DEFAULT NULL,
  `keperluan` text NOT NULL,
  `waktu_kunjungan` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
);

INSERT INTO `roles` (`id`, `nama_role`) VALUES
(1, 'Admin'),
(2, 'Petugas PST');

INSERT INTO `jenis_layanan` (`id`, `nama_layanan`, `kode_antrian`) VALUES
(1, 'Perpustakaan', 'A'),
(2, 'Konsultasi Statistik', 'B'),
(3, 'Rekomendasi Statistik', 'C'),
(4, 'Pengaduan Layanan', 'D');