<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\TempatMagang;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan "PT Telkom Indonesia" ada (fallback jika TempatMagangSeeder belum jalan)
        $telkom = TempatMagang::firstOrCreate(
            ['nama_perusahaan' => 'PT Telkom Indonesia'],
            [
                'alamat'             => 'Jl. Sudirman No. 1, Bengkulu',
                'kontak_person'      => 'Budi Santoso',
                'email_perusahaan'   => 'hr@telkom.co.id',
                'telepon_perusahaan' => '021-12345678',
                'bidang_usaha'       => 'Telekomunikasi',
                'kuota_mahasiswa'    => 5,
                'deskripsi'          => 'Perusahaan telekomunikasi terbesar di Indonesia',
                'is_active'          => true,
            ]
        );
        $mandiri = TempatMagang::firstOrCreate(
            ['nama_perusahaan' => 'Bank Mandiri'],
            [
                'alamat'             => 'Jl. Thamrin No. 5, Bengkulu',
                'kontak_person'      => 'Siti Rahayu',
                'email_perusahaan'   => 'recruitment@bankmandiri.co.id',
                'telepon_perusahaan' => '021-87654321',
                'bidang_usaha'       => 'Perbankan',
                'kuota_mahasiswa'    => 3,
                'deskripsi'          => 'Bank BUMN terpercaya dengan layanan prima',
                'is_active'          => true,
            ]
        );

        $pln = TempatMagang::firstOrCreate(
            ['nama_perusahaan' => 'PLN Bengkulu'],
            [
                'alamat'             => 'Jl. Parman No. 10, Bengkulu',
                'kontak_person'      => 'Ahmad Fauzi',
                'email_perusahaan'   => 'hrd@pln.co.id',
                'telepon_perusahaan' => '0736-123456',
                'bidang_usaha'       => 'Kelistrikan',
                'kuota_mahasiswa'    => 4,
                'deskripsi'          => 'Perusahaan listrik negara cabang Bengkulu',
                'is_active'          => true,
            ]
        );

        $komdigi = TempatMagang::firstOrCreate(
            ['nama_perusahaan' => 'Komdigi Bengkulu'],
            [
                'alamat'             => 'Jl. Zainul Arifin No. 12, Bengkulu',
                'kontak_person'      => 'Rangga Pratama',
                'email_perusahaan'   => 'hr@komdigi.co.id',
                'telepon_perusahaan' => '0736-998877',
                'bidang_usaha'       => 'Digital Agency / IT Consultant',
                'kuota_mahasiswa'    => 6,
                'deskripsi'          => 'Perusahaan digital dan konsultansi IT wilayah Bengkulu',
                'is_active'          => true,
            ]
        );

        // Baru: Bank BRI (Bengkulu)
        $bri = TempatMagang::firstOrCreate(
            ['nama_perusahaan' => 'Bank BRI Bengkulu'],
            [
                'alamat'             => 'Jl. Basuki Rahmat No. 3, Bengkulu',
                'kontak_person'      => 'Dina Lestari',
                'email_perusahaan'   => 'recruitment@bri.co.id',
                'telepon_perusahaan' => '021-11223344',
                'bidang_usaha'       => 'Perbankan',
                'kuota_mahasiswa'    => 4,
                'deskripsi'          => 'PT Bank Rakyat Indonesia (Persero) Tbk Kantor Wilayah Bengkulu',
                'is_active'          => true,
            ]
        );

        // Super Admin
        User::updateOrCreate(
            ['email' => 'superadmin@unib.ac.id'],
            [
                'name'              => 'Super Admin',
                'password'          => Hash::make('password123'),
                'role'              => User::ROLE_SUPERADMIN,
                'is_active'         => true,
                'email_verified_at' => now(),
            ]
        );

        // Admin Dosen
        User::updateOrCreate(
            ['email' => 'admindosen@unib.ac.id'],
            [
                'name'              => 'Dr. Admin Dosen',
                'password'          => Hash::make('password123'),
                'role'              => User::ROLE_ADMIN_DOSEN,
                'is_active'         => true,
                'email_verified_at' => now(),
            ]
        );

        // Pengawas Lapangan (umum – tetap dipertahankan)
        User::updateOrCreate(
            ['email' => 'pengawas@company.com'],
            [
                'name'              => 'Pengawas Lapangan',
                'password'          => Hash::make('password123'),
                'role'              => User::ROLE_PENGAWAS_LAPANGAN,
                'is_active'         => true,
                'email_verified_at' => now(),
                // tidak di-assign ke tempat tertentu
                'tempat_magang_id'  => null,
            ]
        );

        // Pengawas Telkom (baru) → terhubung ke PT Telkom Indonesia
        User::updateOrCreate(
            ['email' => 'pengawas.telkom@telkom.co.id'],
            [
                'name'              => 'Pengawas Telkom',
                'password'          => Hash::make('password123'), // ganti di production
                'role'              => User::ROLE_PENGAWAS_LAPANGAN,
                'is_active'         => true,
                'email_verified_at' => now(),
                'tempat_magang_id'  => $telkom->id, // kunci: asosiasi dengan Telkom
            ]
        );
        User::updateOrCreate(
            ['email' => 'pengawas.mandiri@bankmandiri.co.id'],
            [
                'name'              => 'Pengawas Bank Mandiri',
                'password'          => Hash::make('password123'),
                'role'              => User::ROLE_PENGAWAS_LAPANGAN,
                'is_active'         => true,
                'email_verified_at' => now(),
                'tempat_magang_id'  => $mandiri->id,
            ]
        );

        User::updateOrCreate(
            ['email' => 'pengawas.pln@pln.co.id'],
            [
                'name'              => 'Pengawas PLN Bengkulu',
                'password'          => Hash::make('password123'),
                'role'              => User::ROLE_PENGAWAS_LAPANGAN,
                'is_active'         => true,
                'email_verified_at' => now(),
                'tempat_magang_id'  => $pln->id,
            ]
        );

        User::updateOrCreate(
            ['email' => 'pengawas.bri@bri.co.id'],
            [
                'name'              => 'Pengawas Bank BRI',
                'password'          => Hash::make('password123'),
                'role'              => User::ROLE_PENGAWAS_LAPANGAN,
                'is_active'         => true,
                'email_verified_at' => now(),
                'tempat_magang_id'  => $bri->id,
            ]
        );

        User::updateOrCreate(
            ['email' => 'pengawas.komdigi@komdigi.co.id'],
            [
                'name'              => 'Pengawas Komdigi Bengkulu',
                'password'          => Hash::make('password123'),
                'role'              => User::ROLE_PENGAWAS_LAPANGAN,
                'is_active'         => true,
                'email_verified_at' => now(),
                'tempat_magang_id'  => $komdigi->id,
            ]
        );

        // Mahasiswa Sample
        User::updateOrCreate(
            ['email' => 'mahasiswa@student.unib.ac.id'],
            [
                'name'              => 'John Doe',
                'password'          => Hash::make('password123'),
                'npm'               => 'G1A123456',
                'role'              => User::ROLE_MAHASISWA,
                'phone'             => '08123456789',
                'is_active'         => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
