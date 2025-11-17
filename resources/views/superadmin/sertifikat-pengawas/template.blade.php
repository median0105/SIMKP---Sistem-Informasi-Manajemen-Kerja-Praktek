<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat Pengawas - {{ $sertifikat->nomor_sertifikat }}</title>
    <style>
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }
        
        body {
            width: 297mm;
            height: 210mm;
            margin: 0;
            padding: 0;
            position: relative;
            font-family: "Times New Roman", Times, serif;
            overflow: hidden;
        }
        
        .background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
        
        .content {
            position: relative;
            z-index: 1;
            width: 100%;
            height: 100%;
        }

        /* VERSI FLEKSIBEL - MUDAH DISESUAIKAN */

        /* Level 1: Nomor Sertifikat */
        .level-1 {
            position: absolute;
            top: 68mm; /* SESUAIKAN: Di bawah "PENGHARGAAN" */
            left: 50%;
            transform: translateX(-50%);
            font-size: 14pt;
            font-weight: bold;
            color: #000000;
            text-align: center;
            width: 80%;
        }

        /* Level 2: Nama Pengawas */
        .level-2 {
            position: absolute;
            top: 85mm; /* SESUAIKAN: Di bawah garis pertama */
            left: 50%;
            transform: translateX(-50%);
            font-size: 34pt;
            font-weight: bold;
            color: #000000;
            text-align: center;
            width: 80%;
        }

        /* Level 3: Deskripsi */
        .level-3 {
            position: absolute;
            top: 110mm; /* SESUAIKAN: Di bawah nama */
            left: 50%;
            transform: translateX(-50%);
            font-size: 14pt;
            color: #000000;
            text-align: center;
            width: 65%;
            line-height: 1.6;
        }

        /* Level 4: Kaprodi */
        .level-4 {
            position: absolute;
            bottom: 25mm; /* SESUAIKAN: Di bawah garis kedua */
            left: 50%;
            transform: translateX(-50%);
            font-size: 14pt;
            font-weight: bold;
            color: #000000;
            text-align: center;
            width: 80%;
        }

        /* Level 5: NIP */
        .level-5 {
            position: absolute;
            bottom: 20mm; /* SESUAIKAN: Di bawah kaprodi */
            left: 50%;
            transform: translateX(-50%);
            font-size: 12pt;
            color: #000000;
            text-align: center;
            width: 80%;
        }

        /* Level 6: Tanggal */
        .level-6 {
            position: absolute;
            bottom: 65mm; /* SESUAIKAN: Paling bawah */
            left: 50%;
            transform: translateX(-50%);
            font-size: 11pt;
            color: #000000;
            text-align: center;
            width: 80%;
        }
    </style>
</head>
<body>
    <img src="data:image/png;base64,{{ $templateBase64 }}" class="background" alt="Template Sertifikat">
    
    <div class="content">
        <!-- Level 1: Nomor Sertifikat -->
        <div class="level-1">
            NO. {{ $sertifikat->nomor_sertifikat }}/SIMKP/SI/FT/UNIB/{{ $sertifikat->tahun_ajaran }}
        </div>
        
        <!-- Level 2: Nama Pengawas -->
        <div class="level-2">
            {{ $sertifikat->nama_pengawas }}
        </div>
        
        <!-- Level 3: Deskripsi -->
        <div class="level-3">
            Atas Kontribusi dan Bimbingan yang telah diberikan selama program kerja Praktik 
            Tahun Ajaran {{ $sertifikat->tahun_ajaran }}, sehingga seluruh kegiatan dapat 
            berjalan dengan lancar dan memberikan manfaat bagi Mahasiswa
        </div>
        
        <!-- Level 4: Kaprodi -->
        <div class="level-4">
            {{ $sertifikat->nama_kaprodi }}
        </div>
        
        <!-- Level 5: NIP -->
        <div class="level-5">
            NIP. {{ $sertifikat->nip_kaprodi }}
        </div>
        
        <!-- Level 6: Tanggal -->
        <div class="level-6">
            Bengkulu, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
        </div>
    </div>
</body>
</html>