<div align="center">

![E-SATU Branding](public/icons/navbar.png)

</div>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel" alt="Laravel 12">
  <img src="https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php" alt="PHP 8.3">
  <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Bootstrap-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap">
</p>

**E-SATU** adalah aplikasi web inovatif yang dikembangkan oleh **Pengadilan Negeri Mandailing Natal** untuk memodernisasi proses pendaftaran surat kuasa. Dengan asas **Satu Pintu, Satu Klik, Urusan Kuasa Jadi Praktis.**, aplikasi ini memungkinkan advokat dan masyarakat untuk mendaftarkan surat kuasa secara Digital dari mana saja dan kapan saja.

---

### 📜 Daftar Isi

- [Tentang Proyek](#-tentang-proyek)
- [Fitur Utama](#-fitur-utama)
- [Teknologi yang Digunakan](#-teknologi-yang-digunakan)
- [Panduan Instalasi](#-panduan-instalasi)
- [Alur Penggunaan](#-alur-penggunaan)
- [Kontribusi](#-kontribusi)
- [Lisensi](#-lisensi)
- [Ucapan Terima Kasih](#-ucapan-terima-kasih)

---

### 🌟 Tentang Proyek

Proyek E-SATU hadir sebagai solusi untuk meningkatkan efektivitas pelayanan legalisasi pendaftaran surat kuasa di Pengadilan Negeri Mandailing Natal. Proses yang sebelumnya dilakukan secara manual, memakan waktu, dan mengharuskan kehadiran fisik kini telah ditransformasikan menjadi layanan digital yang lebih cepat, praktis, dan efisien.

Tujuan utama dari aplikasi ini adalah:
- **Memudahkan** pengguna dalam mendaftarkan surat kuasa.
- **Mempercepat** proses verifikasi dan legalisasi oleh petugas.
- **Meringankan** biaya dan waktu yang dibutuhkan oleh para pencari keadilan.
- **Meningkatkan** transparansi dan akuntabilitas dalam pelayanan publik.

---

### ✨ Fitur Utama

- **Pendaftaran Online**: Pengguna dapat mengajukan pendaftaran surat kuasa baru dengan mengisi formulir dan mengunggah dokumen pendukung secara online.
- **Otentikasi Pengguna**: Sistem login yang aman dengan registrasi mandiri, aktivasi akun melalui email, dan opsi login menggunakan **Google Socialite**.
- **PWA (Progressive Web App)**: Mendukung instalasi aplikasi di perangkat mobile dan desktop untuk akses yang lebih cepat dan notifikasi pembaruan real-time.
- **Manajemen Profil Lengkap**: Pengguna dapat melengkapi dan memperbarui data profil, foto, serta mengubah password.
- **Pembayaran Digital**: Integrasi dengan sistem pembayaran **QRIS** untuk biaya pendaftaran yang transparan dan mudah.
- **Verifikasi oleh Petugas**: Panel administrasi khusus bagi petugas untuk me-review, memverifikasi, menyetujui, atau menolak pengajuan surat kuasa.
- **Notifikasi Status**: Pengguna mendapatkan informasi real-time mengenai status pengajuan mereka (Menunggu, Disetujui, Ditolak).
- **Cetak Barcode**: Setelah disetujui, sistem akan menghasilkan barcode pendaftaran Digital yang sah untuk digunakan.
- **Audit Trail**: Pencatatan setiap aktivitas penting pengguna untuk keamanan dan jejak audit.
- **Testimoni Pengguna**: Fitur bagi pengguna untuk memberikan ulasan dan rating terhadap layanan.
- **Manajemen Konten Dinamis**: Halaman depan yang informatif dengan data pejabat struktural dan informasi aplikasi yang dapat dikelola oleh admin.

---

### 🚀 Teknologi yang Digunakan

Proyek ini dibangun menggunakan tumpukan teknologi modern dan andal:

| Kategori | Teknologi |
| :--- | :--- |
| **Framework Backend** | [Laravel 12](https://laravel.com/) |
| **Bahasa Pemrograman** | [PHP 8.3](https://www.php.net/) |
| **Database** | [MySQL](https://www.mysql.com/) |
| **CSS Framework** | [Bootstrap](https://getbootstrap.com/) (Tema Hijau Khusus) |
| **JavaScript** | [jQuery](https://jquery.com/) & AJAX |
| **Paket Utama** | |
| &nbsp; &nbsp; ↳ Tabel Data | [Yajra DataTables](https://yajrabox.com/docs/laravel-datatables/master) |
| &nbsp; &nbsp; ↳ Otentikasi Sosial | [Laravel Socialite](https://laravel.com/docs/12.x/socialite) |
| &nbsp; &nbsp; ↳ Keamanan Form | [Mews Captcha](https://github.com/mewebstudio/captcha) (Custom Compatibility) |
| &nbsp; &nbsp; ↳ Cetak PDF | [Barryvdh DomPDF](https://github.com/barryvdh/laravel-dompdf) |
| &nbsp; &nbsp; ↳ QR Code | [Simple QrCode](https://github.com/SimpleSoftwareIO/simple-qrcode) |
| &nbsp; &nbsp; ↳ Caching | [Redis (Predis)](https://github.com/predis/predis) |

---

### 🛠️ Panduan Instalasi

Ikuti langkah-langkah berikut untuk menjalankan proyek ini di lingkungan lokal Kamu.

### Prasyarat
- PHP >= 8.3
- Composer
- Database (MySQL/MariaDB)
- Redis (opsional, untuk caching)

#### Langkah-langkah Instalasi

1.  **Clone Repository**
    ```bash
    git clone https://github.com/qorichairawan17/esatu.git
    cd esatu
    ```

2.  **Install Dependensi PHP**
    ```bash
    composer install
    ```

3.  **Konfigurasi Lingkungan (.env)**
    - Salin file `.env.example` menjadi `.env`.
      ```bash
      cp .env.example .env
      ```
    - Buka file `.env` dan atur koneksi database Kamu (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).
    - Atur `APP_URL` sesuai dengan URL lokal Kamu.
    - Konfigurasi `MAIL_*` untuk fitur aktivasi akun melalui email.
    - Konfigurasi `GOOGLE_CLIENT_ID` dan `GOOGLE_CLIENT_SECRET` untuk login Google.

4.  **Generate Kunci Aplikasi**
    ```bash
    php artisan key:generate
    ```

5.  **Jalankan Migrasi & Seeder**
    ```bash
    php artisan migrate --seed
    ```

6.  **Buat Symbolic Link**
    ```bash
    php artisan storage:link
    ```

7.  **Jalankan Server Development**
    ```bash
    php artisan serve
    ```

Aplikasi sekarang seharusnya sudah bisa diakses di `http://127.0.0.1:8000`.

---

### ⚙️ Alur Penggunaan

#### 👤 Alur Pengguna
1.  **Registrasi**: Membuat akun baru melalui form pendaftaran.
2.  **Aktivasi Akun**: Mengklik link aktivasi yang dikirimkan ke email.
3.  **Login**: Masuk ke sistem menggunakan email dan password atau akun Google.
4.  **Lengkapi Profil**: Mengisi data diri lengkap sebelum dapat mengajukan surat kuasa.
5.  **Ajukan Surat Kuasa**: Mengisi detail surat kuasa dan mengunggah dokumen yang diperlukan.
6.  **Lakukan Pembayaran**: Membayar biaya pendaftaran melalui QRIS dan mengunggah bukti bayar.
7.  **Tunggu Verifikasi**: Menunggu petugas memverifikasi data dan pembayaran.
8.  **Unduh Barcode**: Jika disetujui, unduh barcode pendaftaran Digital. Jika ditolak, perbaiki data sesuai catatan dari petugas.

#### 👮 Alur Admin/Petugas
1.  **Login**: Masuk ke panel administrator.
2.  **Lihat Pengajuan**: Meninjau daftar surat kuasa yang masuk.
3.  **Verifikasi**: Memeriksa kelengkapan dan keabsahan dokumen serta bukti pembayaran.
4.  **Setujui/Tolak**: Memberikan status persetujuan atau penolakan dengan menyertakan alasan jika ditolak.

---

### 🤝 Kontribusi & Penggunaan

Proyek ini adalah perangkat lunak dengan hak milik **(proprietary software)**. Penggunaan, modifikasi, dan distribusi kode sumber hanya diizinkan dengan persetujuan tertulis dari pemilik hak cipta.

---

### 📄 Lisensi

Hak Cipta © 2026 Pengadilan Negeri Mandailing Natal
---
