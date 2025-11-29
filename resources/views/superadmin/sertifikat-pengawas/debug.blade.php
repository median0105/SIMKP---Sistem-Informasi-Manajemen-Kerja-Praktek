<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Debug Posisi Sertifikat</title>
    <style>
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }
        
        body {
            width: 210mm;
            height: 297mm;
            margin: 0;
            padding: 0;
            position: relative;
            font-family: Arial, sans-serif;
        }
        
        .background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.7;
        }
        
        .grid {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(rgba(255,0,0,0.3) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,0,0,0.3) 1px, transparent 1px);
            background-size: 10mm 10mm;
        }
        
        .coordinate {
            position: absolute;
            font-size: 8pt;
            color: red;
            background: white;
            padding: 2px;
        }
        
        .test-text {
            position: absolute;
            background: rgba(255, 255, 0, 0.5);
            padding: 5px;
            border: 1px solid red;
            font-size: 12pt;
        }
    </style>
</head>
<body>
    <img src="data:image/png;base64,{{ $templateBase64 }}" class="background" alt="Template Sertifikat">
    <div class="grid"></div>
    
    <!-- Test positions -->
    <div class="test-text" style="top: 140mm; left: 50%; transform: translateX(-50%);">
        NAMA: {{ $sertifikat->nama_pengawas }}
    </div>
    
    <div class="test-text" style="top: 180mm; left: 50%; transform: translateX(-50%);">
        NOMOR: {{ $sertifikat->nomor_sertifikat }}
    </div>
    
    <div class="test-text" style="top: 160mm; left: 50%; transform: translateX(-50%);">
        TAHUN: {{ $sertifikat->tahun_ajaran }}
    </div>
    
    <div class="test-text" style="bottom: 50mm; left: 50%; transform: translateX(-50%);">
        KAPRODI: {{ $sertifikat->nama_kaprodi }}
    </div>
    
    <div class="test-text" style="bottom: 40mm; left: 50%; transform: translateX(-50%);">
        NIP: {{ $sertifikat->nip_kaprodi }}
    </div>
    
    <!-- Coordinate markers -->
    <div class="coordinate" style="top: 140mm; left: 10mm;">140mm</div>
    <div class="coordinate" style="top: 160mm; left: 10mm;">160mm</div>
    <div class="coordinate" style="top: 180mm; left: 10mm;">180mm</div>
    <div class="coordinate" style="bottom: 50mm; left: 10mm;">50mm</div>
    <div class="coordinate" style="bottom: 40mm; left: 10mm;">40mm</div>
    <div class="coordinate" style="bottom: 30mm; left: 10mm;">30mm</div>
</body>
</html>