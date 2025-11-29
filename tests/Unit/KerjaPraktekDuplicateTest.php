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

    public function test_exact_duplicate_regardless_of_tempat_magang()
    {
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $tempatMagang1 = TempatMagang::factory()->create();
        $tempatMagang2 = TempatMagang::factory()->create();

        // Create first KP
        $kp1 = KerjaPraktek::create([
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

        $this->assertTrue($kp2->isDuplicateTitle());
    }

    public function test_no_duplicate_with_different_titles()
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

        // Create second KP with different title
        $kp2 = new KerjaPraktek([
            'mahasiswa_id' => $mahasiswa->id,
            'tempat_magang_id' => $tempatMagang2->id,
            'judul_kp' => 'Analisis Data Mining',
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

    public function test_duplicate_notification_in_superadmin_view()
    {
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $tempatMagang1 = TempatMagang::factory()->create();
        $tempatMagang2 = TempatMagang::factory()->create();

        // Create two KP with same title
        KerjaPraktek::create([
            'mahasiswa_id' => $mahasiswa->id,
            'tempat_magang_id' => $tempatMagang1->id,
            'judul_kp' => 'Pengembangan Sistem Informasi',
            'status' => 'pengajuan'
        ]);

        KerjaPraktek::create([
            'mahasiswa_id' => $mahasiswa->id,
            'tempat_magang_id' => $tempatMagang2->id,
            'judul_kp' => 'Pengembangan Sistem Informasi',
            'status' => 'pengajuan'
        ]);

        // Test that duplicate titles are detected
        $duplicateTitles = KerjaPraktek::select('judul_kp')
            ->groupBy('judul_kp')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('judul_kp');

        $this->assertCount(1, $duplicateTitles);
        $this->assertEquals('Pengembangan Sistem Informasi', $duplicateTitles->first());
    }

    public function test_similarity_percentage_display()
    {
        // Create test data
        $mahasiswa1 = User::factory()->create(['role' => 'mahasiswa']);
        $mahasiswa2 = User::factory()->create(['role' => 'mahasiswa']);
        $tempatMagang1 = TempatMagang::factory()->create();
        $tempatMagang2 = TempatMagang::factory()->create();

        // Create KP with similar titles
        $kp1 = KerjaPraktek::factory()->create([
            'mahasiswa_id' => $mahasiswa1->id,
            'tempat_magang_id' => $tempatMagang1->id,
            'judul_kp' => 'Pengembangan Sistem Informasi Akademik'
        ]);

        $kp2 = KerjaPraktek::factory()->create([
            'mahasiswa_id' => $mahasiswa2->id,
            'tempat_magang_id' => $tempatMagang2->id,
            'judul_kp' => 'Pengembangan Sistem Informasi Akademik Universitas'
        ]);

        // Test getDuplicateInfo method
        $duplicates = $kp1->getDuplicateInfo();

        $this->assertNotEmpty($duplicates);
        $this->assertArrayHasKey('similarity', $duplicates[0]);
        $this->assertIsFloat($duplicates[0]['similarity']);
        $this->assertGreaterThanOrEqual(50, $duplicates[0]['similarity']);
        $this->assertLessThanOrEqual(100, $duplicates[0]['similarity']);
    }
}
