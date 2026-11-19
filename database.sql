CREATE DATABASE IF NOT EXISTS volunteerhub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE volunteerhub;
CREATE TABLE users (
 user_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, nama VARCHAR(120) NOT NULL,
 email VARCHAR(160) NOT NULL UNIQUE, password_hash VARCHAR(255) NOT NULL,
 role ENUM('volunteer','organisasi','admin') NOT NULL DEFAULT 'volunteer',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
CREATE TABLE volunteers (
 user_id BIGINT UNSIGNED PRIMARY KEY, domisili VARCHAR(150) DEFAULT NULL,
 skill TEXT DEFAULT NULL, minat TEXT DEFAULT NULL, bio TEXT DEFAULT NULL,
 FOREIGN KEY(user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB;
CREATE TABLE organizations (
 user_id BIGINT UNSIGNED PRIMARY KEY, nama_organisasi VARCHAR(160) NOT NULL,
 deskripsi TEXT, alamat VARCHAR(255), status_verifikasi ENUM('Menunggu','Terverifikasi','Ditolak') DEFAULT 'Menunggu',
 FOREIGN KEY(user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB;
CREATE TABLE events (
 kegiatan_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, organisasi_id BIGINT UNSIGNED NOT NULL,
 nama_kegiatan VARCHAR(180) NOT NULL, kategori VARCHAR(80) NOT NULL, lokasi VARCHAR(180) NOT NULL,
 tanggal DATETIME NOT NULL, durasi_jam DECIMAL(5,2) NOT NULL, kuota INT UNSIGNED NOT NULL,
 deadline_pendaftaran DATETIME NOT NULL, persyaratan TEXT, deskripsi TEXT,
 status ENUM('Menunggu Moderasi','Dipublikasikan','Ditangguhkan','Selesai') DEFAULT 'Menunggu Moderasi',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY(organisasi_id) REFERENCES organizations(user_id)
) ENGINE=InnoDB;
CREATE TABLE registrations (
 pendaftaran_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, volunteer_id BIGINT UNSIGNED NOT NULL,
 kegiatan_id BIGINT UNSIGNED NOT NULL, tanggal_daftar TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 status ENUM('Menunggu Verifikasi','Diterima','Ditolak') DEFAULT 'Menunggu Verifikasi',
 UNIQUE KEY uniq_signup(volunteer_id,kegiatan_id),
 FOREIGN KEY(volunteer_id) REFERENCES volunteers(user_id) ON DELETE CASCADE,
 FOREIGN KEY(kegiatan_id) REFERENCES events(kegiatan_id) ON DELETE CASCADE
) ENGINE=InnoDB;
CREATE TABLE attendances (
 absensi_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, pendaftaran_id BIGINT UNSIGNED NOT NULL UNIQUE,
 waktu_check_in DATETIME NOT NULL, status_kehadiran ENUM('Hadir','Tidak Hadir') DEFAULT 'Hadir',
 FOREIGN KEY(pendaftaran_id) REFERENCES registrations(pendaftaran_id) ON DELETE CASCADE
) ENGINE=InnoDB;
CREATE TABLE certificates (
 sertifikat_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, pendaftaran_id BIGINT UNSIGNED NOT NULL UNIQUE,
 nomor_sertifikat VARCHAR(80) NOT NULL UNIQUE, tanggal_terbit DATE NOT NULL,
 FOREIGN KEY(pendaftaran_id) REFERENCES registrations(pendaftaran_id) ON DELETE CASCADE
) ENGINE=InnoDB;
CREATE TABLE contribution_history (
 volunteer_id BIGINT UNSIGNED PRIMARY KEY, jumlah_kegiatan INT UNSIGNED DEFAULT 0,
 total_jam DECIMAL(9,2) DEFAULT 0, jumlah_sertifikat INT UNSIGNED DEFAULT 0,
 FOREIGN KEY(volunteer_id) REFERENCES volunteers(user_id) ON DELETE CASCADE
) ENGINE=InnoDB;
-- Setelah aplikasi dijalankan, buat akun admin melalui SQL:
-- INSERT INTO users(nama,email,password_hash,role) VALUES('Administrator','admin@volunteerhub.local', '$2y$10$REPLACE_WITH_PASSWORD_HASH', 'admin');
