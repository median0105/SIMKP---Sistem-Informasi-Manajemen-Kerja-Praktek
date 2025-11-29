<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TempatMagang;

class TempatMagangSeeder extends Seeder
{
    public function run(): void
    {
        $tempatMagang = [
            [
                'nama_perusahaan'    => 'PT Telkom Indonesia',
                'alamat'             => 'Jl. Sudirman No. 1, Bengkulu',
                'kontak_person'      => 'Budi Santoso',
                'email_perusahaan'   => 'hr@telkom.co.id',
                'telepon_perusahaan' => '021-12345678',
                'bidang_usaha'       => 'Telekomunikasi',
                'kuota_mahasiswa'    => 5,
                'deskripsi'          => 'Perusahaan telekomunikasi terbesar di Indonesia',
                'is_active'          => true,
            ],
            [
                'nama_perusahaan'    => 'Bank Mandiri',
                'alamat'             => 'Jl. Thamrin No. 5, Bengkulu',
                'kontak_person'      => 'Siti Rahayu',
                'email_perusahaan'   => 'recruitment@bankmandiri.co.id',
                'telepon_perusahaan' => '021-87654321',
                'bidang_usaha'       => 'Perbankan',
                'kuota_mahasiswa'    => 3,
                'deskripsi'          => 'Bank BUMN terpercaya dengan layanan prima',
                'is_active'          => true,
            ],
            [
                'nama_perusahaan'    => 'PLN Bengkulu',
                'alamat'             => 'Jl. Parman No. 10, Bengkulu',
                'kontak_person'      => 'Ahmad Fauzi',
                'email_perusahaan'   => 'hrd@pln.co.id',
                'telepon_perusahaan' => '0736-123456',
                'bidang_usaha'       => 'Kelistrikan',
                'kuota_mahasiswa'    => 4,
                'deskripsi'          => 'Perusahaan listrik negara cabang Bengkulu',
                'is_active'          => true,
            ],
        ];

        foreach ($tempatMagang as $t) {
            // idempotent: tidak dobel jika seeding berulang
            TempatMagang::updateOrCreate(
                ['nama_perusahaan' => $t['nama_perusahaan']],
                $t
            );
        }
    }
}
