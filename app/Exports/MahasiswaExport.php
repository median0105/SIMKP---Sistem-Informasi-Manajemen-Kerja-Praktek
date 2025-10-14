<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MahasiswaExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = User::query();

        // Hanya tampilkan user yang memiliki NPM (mahasiswa)
        $query->whereNotNull('npm')->where('npm', '!=', '');

        if (isset($this->filters['role'])) {
            $query->where('role', $this->filters['role']);
        }

        if (isset($this->filters['is_active'])) {
            $query->where('is_active', $this->filters['is_active']);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'NPM',
            'Nama',
            'Email',
            'Role',
            'Phone',
            'Status Aktif',
            'Tanggal Dibuat',
        ];
    }

    public function map($user): array
    {
        return [
            $user->npm ?? '',
            $user->name ?? '',
            $user->email ?? '',
            $user->role ?? '',
            $user->phone ?? '',
            $user->is_active ? 'Aktif' : 'Nonaktif',
            $user->created_at ? $user->created_at->format('d/m/Y') : '',
        ];
    }
}
