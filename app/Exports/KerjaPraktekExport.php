<?php

namespace App\Exports;

use App\Models\KerjaPraktek;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class KerjaPraktekExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = KerjaPraktek::with(['mahasiswa', 'tempatMagang', 'dosenPembimbing']);

        if (isset($this->filters['start_date']) && isset($this->filters['end_date'])) {
            $query->whereBetween('created_at', [$this->filters['start_date'], $this->filters['end_date']]);
        }

        if (isset($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (isset($this->filters['tempat_magang_id'])) {
            $query->where('tempat_magang_id', $this->filters['tempat_magang_id']);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'NPM',
            'Nama Mahasiswa',
            'Judul KP',
            'Tempat Magang',
            'Dosen Pembimbing',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Status',
            'Nilai Akhir',
            'Tanggal Dibuat',
        ];
    }

    public function map($kp): array
    {
        return [
            $kp->mahasiswa->npm ?? '',
            $kp->mahasiswa->name ?? '',
            $kp->judul_kp ?? '',
            $kp->tempatMagang->nama_perusahaan ?? '',
            $kp->dosenPembimbing->first()->name ?? '',
            $kp->tanggal_mulai ? $kp->tanggal_mulai->format('d/m/Y') : '',
            $kp->tanggal_selesai ? $kp->tanggal_selesai->format('d/m/Y') : '',
            $kp->status ?? '',
            $kp->nilai_akhir ?? '',
            $kp->created_at ? $kp->created_at->format('d/m/Y') : '',
        ];
    }
}
