# 🎓 SIMKP — Sistem Informasi Manajemen Kerja Praktek

![Laravel](https://img.shields.io/badge/Laravel-11.x-red?logo=laravel)
![PHP](https://img.shields.io/badge/PHP->=8.2-blue?logo=php)
![MySQL](https://img.shields.io/badge/Database-MySQL-orange?logo=mysql)
![TailwindCSS](https://img.shields.io/badge/Frontend-TailwindCSS-06B6D4?logo=tailwind-css)
![Status](https://img.shields.io/badge/Status-Development-green)

---

## 🧭 Tentang SIMKP

**SIMKP (Sistem Informasi Manajemen Kerja Praktek)** adalah platform berbasis web yang dirancang untuk mengelola seluruh proses kegiatan **Kerja Praktek (KP)** mahasiswa di lingkungan **Program Studi Sistem Informasi, Universitas Bengkulu**.

Sistem ini memfasilitasi berbagai peran seperti **Super Admin, Dosen Pembimbing, Dosen Penguji, Pembimbing Lapangan,** dan **Mahasiswa**, dalam satu ekosistem terintegrasi — mulai dari **pengajuan KP, bimbingan, seminar, hingga penilaian akhir**.

---

## ✨ Fitur Utama

### 👩‍🎓 Mahasiswa
- Pengajuan kerja praktek secara online  
- Upload proposal dan laporan  
- Melihat status ACC pembimbing  
- Pendaftaran seminar & jadwal otomatis  
- Notifikasi bimbingan dan penilaian  

### 👨‍🏫 Dosen Pembimbing
- Melihat daftar mahasiswa bimbingan  
- Memberikan persetujuan proposal/laporan  
- Menjadwalkan seminar mahasiswa  
- Memberikan nilai akhir  

### 🧑‍⚖️ Dosen Penguji
- Melihat jadwal seminar  
- Memberikan penilaian hasil presentasi  

### 🧑‍💼 Pembimbing Lapangan
- Memantau mahasiswa di tempat magang  
- Memberikan laporan penilaian harian/mingguan  

### 🧑‍💻 Super Admin
- Manajemen user (mahasiswa, dosen, pengawas)  
- Manajemen data instansi & tempat magang  
- Monitoring progres KP setiap mahasiswa  
- Pengaturan sistem dan laporan rekapitulasi  

---

## 🛠️ Teknologi yang Digunakan

| Komponen | Teknologi |
|-----------|------------|
| **Framework** | Laravel 11.x |
| **Frontend** | Tailwind CSS, Blade Template |
| **Database** | MySQL / MariaDB |
| **Authentication** | Laravel Breeze / Fortify |
| **Icons & UI** | Font Awesome 6, Flowbite, Alpine.js |
| **Export Data** | Maatwebsite/Excel |
| **Deployment** | Laravel Artisan & Vite Build |

---

## ⚙️ Cara Instalasi

1. **Clone repository ini**
   ```bash
   git clone https://github.com/median0105/SIMKP---Sistem-Informasi-Manajemen-Kerja-Praktek.git
   cd simkp
2. Install Dependensi Backend (Laravel)
   ```bash
   composer install
3. Install Dependensi Frontend (CSS & JS)
   ```bash
   npm install
   npm run build
4. Konfigurasi File .env
   ```bash
   cp .env.example .env
Lalu buka file .env dan sesuaikan pengaturan database:
    DB_DATABASE=simkp
    DB_USERNAME=root
    DB_PASSWORD=
5. Generate App Key
   ```bash
   php artisan key:generate
6. Migrasi Database
   ```bash
   php artisan migrate --seed
7. Jalankan Server
   ```bash
   php artisan serve
