<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\KerjaPraktek;
use App\Models\User;
use App\Models\TempatMagang;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KerjaPraktekDuplicateTest extends TestCase
{
    use RefreshDatabase;

    public function test_exact_duplicate_with_same_tempat_magang()
    {
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $tempatMagang = TempatMagang::factory()->create();

        // Create first KP
        $kp1 = KerjaPraktek::create([
            'mahasiswa_id' => $mahasiswa->id,
            'tempat_magang_id' => $tempatMagang->id,
            'judul_kp' => 'Pengembangan Sistem Informasi',
            'status' => 'pengajuan'
        ]);

        // Create second KP with same title and tempat_magang
        $kp2 = new KerjaPraktek([
            'mahasiswa_id' => $mahasiswa->id,
            'tempat_magang_id' => $tempatMagang->id,
            'judul_kp' => 'Pengembangan Sistem Informasi',
            'status' => 'pengajuan'
        ]);

        $this->assertTrue($kp2->isDuplicateTitle());
    }

    public function test_no_duplicate_with_different_tempat_magang()
    {
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $tempatMagang1 = TempatMagang::factory()->create();
        $tempatMagang2 = TempatMagang::factory()->create();

        // Create first KP
        KerjaPraktek::create([
            'mahasiswa_id' => $mahasiswa->id,
            'tempat_magang_id' => $tempatMagang1->id,
            'judul_kp' => 'Pengembangan Sistem Informasi',
            'status' => 'pengajuan'
        ]);

        // Create second KP with same title but different tempat_magang
        $kp2 = new KerjaPraktek([
            'mahasiswa_id' => $mahasiswa->id,
            'tempat_magang_id' => $tempatMagang2->id,
            'judul_kp' => 'Pengembangan Sistem Informasi',
            'status' => 'pengajuan'
        ]);

        $this->assertFalse($kp2->isDuplicateTitle());
    }

    public function test_similar_titles_80_percent_similarity()
    {
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $tempatMagang1 = TempatMagang::factory()->create();
        $tempatMagang2 = TempatMagang::factory()->create();

        // Create first KP
        KerjaPraktek::create([
            'mahasiswa_id' => $mahasiswa->id,
            'tempat_magang_id' => $tempatMagang1->id,
            'judul_kp' => 'Pengembangan Sistem Informasi Akademik',
            'status' => 'pengajuan'
        ]);

        // Create second KP with 80% similar title but different tempat_magang
        $kp2 = new KerjaPraktek([
            'mahasiswa_id' => $mahasiswa->id,
            'tempat_magang_id' => $tempatMagang2->id,
            'judul_kp' => 'Pengembangan Sistem Informasi Manajemen',
            'status' => 'pengajuan'
        ]);

        $this->assertTrue($kp2->isDuplicateTitle());
    }

    public function test_dissimilar_titles()
    {
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $tempatMagang1 = TempatMagang::factory()->create();
        $tempatMagang2 = TempatMagang::factory()->create();

        // Create first KP
        KerjaPraktek::create([
            'mahasiswa_id' => $mahasiswa->id,
            'tempat_magang_id' => $tempatMagang1->id,
            'judul_kp' => 'Pengembangan Aplikasi Mobile',
            'status' => 'pengajuan'
        ]);

        // Create second KP with very different title
        $kp2 = new KerjaPraktek([
            'mahasiswa_id' => $mahasiswa->id,
            'tempat_magang_id' => $tempatMagang2->id,
            'judul_kp' => 'Analisis Data Mining untuk Prediksi Penjualan',
            'status' => 'pengajuan'
        ]);

        $this->assertFalse($kp2->isDuplicateTitle());
    }

    public function test_similarity_calculation()
    {
        $kp = new KerjaPraktek();

        // Test identical strings
        $this->assertEquals(100, $kp->calculateSimilarity('test', 'test'));

        // Test similar strings
        $similarity = $kp->calculateSimilarity('Pengembangan Sistem', 'Pengembangan Aplikasi');
        $this->assertGreaterThanOrEqual(60, $similarity);

        // Test different strings
        $similarity = $kp->calculateSimilarity('Machine Learning', 'Web Development');
        $this->assertLessThan(50, $similarity);
    }
}
