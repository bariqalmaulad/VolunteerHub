# VolunteerHub — Aplikasi Web

Aplikasi MVP berbasis PHP 8+ dan MySQL untuk menjalankan alur VolunteerHub sesuai SRS.

## Fitur yang tersedia
- Registrasi dan login untuk Volunteer dan Organisasi; autentikasi memakai `password_hash` / `password_verify`.
- Profil volunteer (domisili, skill, minat, bio) dan profil organisasi.
- Pencarian/filter kegiatan berdasarkan nama, kategori, dan lokasi.
- Pendaftaran kegiatan dengan validasi login, duplikasi, deadline, dan kuota.
- Organisasi terverifikasi dapat membuat kegiatan; kegiatan masuk antrean moderasi admin.
- Organisasi menerima/menolak pendaftar serta melihat skill pendaftar.
- Check-in volunteer menggunakan kode yang dibagikan organisasi; validasi pendaftaran diterima dan waktu mulai.
- Penerbitan sertifikat bagi volunteer yang check-in hadir setelah kegiatan ditandai selesai.
- Riwayat kontribusi otomatis (jumlah kegiatan, jam, sertifikat).
- Admin memverifikasi organisasi, memoderasi kegiatan, melihat statistik dan mengelola pengguna.

## Instalasi lokal (XAMPP)
1. Ekstrak folder `VolunteerHub` ke `C:\xampp\htdocs\VolunteerHub`.
2. Jalankan Apache dan MySQL dari XAMPP.
3. Buka `http://localhost/phpmyadmin`, pilih tab **Import**, lalu impor `database.sql`.
4. Pastikan konfigurasi database di `config.php` sesuai instalasi lokal (default XAMPP: root tanpa password).
5. Buka `http://localhost/VolunteerHub/`.

## Membuat akun admin
Buat hash password dengan PHP CLI:
```bash
php -r "echo password_hash('Admin123!', PASSWORD_DEFAULT), PHP_EOL;"
```
Salin hasilnya, lalu jalankan SQL berikut di phpMyAdmin (ganti hash):
```sql
INSERT INTO users(nama,email,password_hash,role)
VALUES('Administrator','admin@volunteerhub.local','PASTE_HASH_DI_SINI','admin');
```
Login dengan `admin@volunteerhub.local` dan password yang kamu hash.

## Alur demo yang disarankan
1. Daftar sebagai organisasi, lalu login admin untuk verifikasi organisasi.
2. Login organisasi, buat kegiatan. Kegiatan menunggu moderasi.
3. Login admin, buka Moderasi kegiatan, lalu publikasikan.
4. Daftar akun volunteer dan mendaftar pada kegiatan.
5. Organisasi menerima pendaftar.
6. Setelah waktu kegiatan dimulai, volunteer buka `checkin.php` dan masukkan kode check-in yang tampil di halaman kelola kegiatan organisasi.
7. Organisasi tandai kegiatan selesai lalu terbitkan sertifikat.
8. Volunteer dapat melihat sertifikat melalui halaman `certificates.php`.

## Catatan implementasi
- QR Code kamera belum diaktifkan dalam MVP ini; check-in memakai kode kegiatan yang dapat dikodekan menjadi QR dari sisi organisasi. Mekanisme kode saat ini bersifat sederhana untuk demonstrasi dan perlu penguatan masa berlaku/tanda tangan sebelum deployment publik.
- Email/notifikasi eksternal, upload dokumen verifikasi organisasi, dan PDF sertifikat otomatis belum ditentukan secara rinci di SRS sehingga belum dibuat.
- Terapkan HTTPS, backup, pembatasan percobaan login, validasi lebih ketat, dan audit log sebelum aplikasi dipasang di server publik.
