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
    ];



    const STATUS_PENGAJUAN = 'pengajuan';
    const STATUS_DISETUJUI = 'disetujui';
    const STATUS_SEDANG_KP = 'sedang_kp';
    const STATUS_SELESAI   = 'selesai';
    const STATUS_DITOLAK   = 'ditolak';

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
        return static::where('judul_kp', $this->judul_kp)
            ->where('id', '!=', $this->id)
            ->exists();
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


}
