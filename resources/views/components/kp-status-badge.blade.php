@props(['status' => null])

@php
    $map = [
        'pengajuan' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => 'fas fa-clock',          'label' => 'Menunggu'],
        'disetujui' => ['bg' => 'bg-blue-100',   'text' => 'text-blue-800',   'icon' => 'fas fa-check-circle',  'label' => 'Disetujui'],
        'sedang_kp' => ['bg' => 'bg-green-100',  'text' => 'text-green-800',  'icon' => 'fas fa-play-circle',   'label' => 'Sedang KP'],
        'selesai'   => ['bg' => 'bg-gray-100',   'text' => 'text-gray-800',   'icon' => 'fas fa-flag-checkered','label' => 'Selesai'],
        'ditolak'   => ['bg' => 'bg-red-100',    'text' => 'text-red-800',    'icon' => 'fas fa-times-circle',  'label' => 'Ditolak'],
    ];
    $cfg = $map[$status] ?? [
        'bg' => 'bg-gray-100', 'text' => 'text-gray-800',
        'icon' => 'fas fa-question-circle',
        'label' => ucwords(str_replace('_',' ', $status ?? 'Unknown')),
    ];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {$cfg['bg']} {$cfg['text']}"]) }}>
    <i class="{{ $cfg['icon'] }} mr-1"></i>{{ $cfg['label'] }}
</span>
