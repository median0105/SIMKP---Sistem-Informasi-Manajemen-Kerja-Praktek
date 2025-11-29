<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KerjaPraktek extends Model
{
    use HasFactory;

    protected $table = 'kerja_praktek';

    // app/Models/KerjaPraktek.php

    protected $fillable = [
        'mahasiswa_id',
        'tempat_magang_id',
        'judul_kp',
        'pilihan_tempat',
        'tempat_magang_sendiri',
        'alamat_tempat_sendiri',
        'kontak_tempat_sendiri',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'file_laporan',
        'file_revisi',
        'acc_seminar',
        'tanggal_seminar',
        'acc_pembimbing_lapangan',
        'acc_pembimbing_laporan',
        'penilaian_detail',
        'nilai_akhir',
        'keterangan_penilaian',
        'lulus_ujian',
        'perlu_responsi',
        'catatan_dosen',
        'catatan_pengawas',
        'bidang_usaha_sendiri',
        'email_perusahaan_sendiri',
        'telepon_perusahaan_sendiri',
        'kuota_mahasiswa_sendiri',
        'deskripsi_perusahaan_sendiri',
        'instansi_verified',

        // Seminar registration fields
        'pendaftaran_seminar',
        'tanggal_daftar_seminar',
        'acc_pendaftaran_seminar',
        'jadwal_seminar',
        'ruangan_seminar',
        'catatan_seminar',

        // ✅ Tambahan:
        'ipk_semester',
        'semester_ke',
        'file_krs',
        'file_proposal',
        'penilaian_pengawas',
        'rata_rata_pengawas',
        'penilaian_dosen',
        'rata_rata_dosen',
        'rata_rata_seminar',
        'instansi_verified',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'tanggal_seminar' => 'datetime',
        'pendaftaran_seminar' => 'boolean',
        'tanggal_daftar_seminar' => 'datetime',
        'acc_pendaftaran_seminar' => 'boolean',
        'jadwal_seminar' => 'datetime',
        'pilihan_tempat' => 'integer',
        'acc_seminar' => 'boolean',
        'acc_pembimbing_lapangan' => 'boolean',
        'acc_pembimbing_laporan' => 'boolean',
        'lulus_ujian' => 'boolean',
        'perlu_responsi' => 'boolean',
        'penilaian_detail' => 'array',
        'nilai_akhir' => 'decimal:2',

        // ✅ Tambahan:
        'ipk_semester' => 'decimal:2',
        'semester_ke'  => 'integer',
        'penilaian_pengawas' => 'array',
        'rata_rata_pengawas' => 'decimal:2',
        'penilaian_dosen' => 'array',
        'rata_rata_dosen' => 'decimal:2',
        'rata_rata_seminar' => 'decimal:2',
        'instansi_verified' => 'boolean',
    ];



    const STATUS_PENGAJUAN = 'pengajuan';
    const STATUS_DISETUJUI = 'disetujui';
    const STATUS_SEDANG_KP = 'sedang_kp';
    const STATUS_SELESAI   = 'selesai';
    const STATUS_DITOLAK   = 'ditolak';

    protected $appends = ['display_status', 'grade'];

    // Relationships
    public function mahasiswa()       { return $this->belongsTo(User::class, 'mahasiswa_id'); }
    public function tempatMagang()    { return $this->belongsTo(TempatMagang::class); }
    public function bimbingan()       { return $this->hasMany(Bimbingan::class); }
    public function kegiatan()        { return $this->hasMany(Kegiatan::class); }
    public function kuisioner()       { return $this->hasOne(Kuisioner::class); }
    public function dosenPembimbing() { return $this->hasMany(DosenPembimbing::class, 'kerja_praktek_id'); }
    public function dosenAkademik()   { return $this->hasOne(DosenPembimbing::class, 'kerja_praktek_id')->where('jenis_pembimbingan', 'akademik'); }
    public function dosenLapangan()   { return $this->hasMany(DosenPembimbing::class, 'kerja_praktek_id')->where('jenis_pembimbingan', 'lapangan'); }
    public function notifikasi()      { return $this->hasMany(Notifikasi::class); }
    public function sertifikatPembimbing() { return $this->hasMany(SertifikatPembimbing::class); }

    // Helpers
    public function isDuplicateTitle()
    {
        // Check for exact matches regardless of tempat_magang
        $exactDuplicate = static::where('judul_kp', $this->judul_kp)
            ->where('id', '!=', $this->id)
            ->exists();

        if ($exactDuplicate) {
            return true;
        }

        // Check for similar titles (80% similarity) regardless of tempat_magang
        $allTitles = static::where('id', '!=', $this->id)
            ->pluck('judul_kp')
            ->toArray();

        foreach ($allTitles as $existingTitle) {
            $similarity = $this->calculateSimilarity($this->judul_kp, $existingTitle);
            if ($similarity >= 80) {
                return true;
            }
        }

        return false;
    }

    public function getDuplicateInfo()
    {
        $duplicates = [];

        // Get all existing titles except current one
        $allKPs = static::where('id', '!=', $this->id)
            ->with(['mahasiswa', 'tempatMagang'])
            ->get();

        foreach ($allKPs as $kp) {
            $similarity = $this->calculateSimilarity($this->judul_kp, $kp->judul_kp);

            if ($similarity >= 50) { // Show similarities 50% and above
                $duplicates[] = [
                    'id' => $kp->id,
                    'judul_kp' => $kp->judul_kp,
                    'mahasiswa' => $kp->mahasiswa->name ?? 'N/A',
                    'npm' => $kp->mahasiswa->npm ?? 'N/A',
                    'tempat_magang' => $kp->tempatMagang->nama_perusahaan ?? ($kp->tempat_magang_sendiri ?? 'N/A'),
                    'similarity' => round($similarity, 1),
                    'status' => $kp->status
                ];
            }
        }

        // Sort by similarity descending
        usort($duplicates, function($a, $b) {
            return $b['similarity'] <=> $a['similarity'];
        });

        return $duplicates;
    }

    private function calculateSimilarity($str1, $str2)
    {
        $str1 = strtolower(trim($str1));
        $str2 = strtolower(trim($str2));

        if ($str1 === $str2) {
            return 100;
        }

        $len1 = strlen($str1);
        $len2 = strlen($str2);

        if ($len1 === 0 || $len2 === 0) {
            return 0;
        }

        // Calculate Levenshtein distance
        $matrix = [];
        for ($i = 0; $i <= $len1; $i++) {
            $matrix[$i] = [$i];
        }
        for ($j = 0; $j <= $len2; $j++) {
            $matrix[0][$j] = $j;
        }

        for ($i = 1; $i <= $len1; $i++) {
            for ($j = 1; $j <= $len2; $j++) {
                $cost = ($str1[$i - 1] === $str2[$j - 1]) ? 0 : 1;
                $matrix[$i][$j] = min(
                    $matrix[$i - 1][$j] + 1,     // deletion
                    $matrix[$i][$j - 1] + 1,     // insertion
                    $matrix[$i - 1][$j - 1] + $cost // substitution
                );
            }
        }

        $distance = $matrix[$len1][$len2];
        $maxLen = max($len1, $len2);

        return (1 - $distance / $maxLen) * 100;
    }

    public function perluResponsi()
    {
        if (!$this->tanggal_mulai) return false;
        return $this->tanggal_mulai->diffInMonths(now()) > 12;
    }

    public function canRegisterSeminar()
    {
        return $this->acc_pembimbing_laporan && $this->file_laporan && !$this->pendaftaran_seminar;
    }

    public function canTakeExam()
    {
        return $this->acc_pendaftaran_seminar && $this->jadwal_seminar;
    }

    // App\Models\KerjaPraktek.php

public static function rejectedCountFor(int $mahasiswaId): int
{
    return static::where('mahasiswa_id', $mahasiswaId)
        ->where('status', self::STATUS_DITOLAK)
        ->count();
}

    public function getDisplayStatusAttribute()
    {
        if ($this->status === self::STATUS_SELESAI && !$this->lulus_ujian) {
            return 'tidak_lulus';
        }
        return $this->status;
    }

    public function getGradeAttribute()
    {
        if (!$this->nilai_akhir) {
            return '-';
        }

        $nilai = $this->nilai_akhir;

        if ($nilai >= 85) {
            return 'A';
        } elseif ($nilai >= 75) {
            return 'B';
        } elseif ($nilai >= 65) {
            return 'C';
        } elseif ($nilai >= 55) {
            return 'D';
        } else {
            return 'E';
        }
    }

    // Relationship with Dosen Penguji
    public function dosenPenguji()
    {
        return $this->hasMany(DosenPenguji::class);
    }

}
