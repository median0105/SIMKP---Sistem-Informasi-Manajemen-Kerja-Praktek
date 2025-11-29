<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SertifikatPengawas;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class SertifikatPengawasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sertifikats = SertifikatPengawas::orderByDesc('created_at')->paginate(15);
        return view('superadmin.sertifikat-pengawas.index', compact('sertifikats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get pengawas who don't have certificates yet
        $pengawasWithCertificates = SertifikatPengawas::pluck('nama_pengawas')->toArray();

        $pengawas = \App\Models\User::where('role', 'pengawas_lapangan')
            ->where('is_active', true)
            ->whereNotIn('name', $pengawasWithCertificates)
            ->orderBy('name')
            ->get();

        return view('superadmin.sertifikat-pengawas.create', compact('pengawas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_pengawas' => 'required|string|max:255',
            'nomor_sertifikat' => 'required|string|max:255|unique:sertifikat_pengawas',
            'tahun_ajaran' => 'required|string|max:20',
            'nama_kaprodi' => 'required|string|max:255',
            'nip_kaprodi' => 'required|string|max:255',
        ]);

        SertifikatPengawas::create($request->all());

        return redirect()->route('superadmin.sertifikat-pengawas.index')
            ->with('success', 'Sertifikat pengawas berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SertifikatPengawas $sertifikatPengawa)
    {
        return view('superadmin.sertifikat-pengawas.show', compact('sertifikatPengawa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SertifikatPengawas $sertifikatPengawa)
    {
        return view('superadmin.sertifikat-pengawas.edit', compact('sertifikatPengawa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SertifikatPengawas $sertifikatPengawa)
    {
        $request->validate([
            'nama_pengawas' => 'required|string|max:255',
            'nomor_sertifikat' => 'required|string|max:255|unique:sertifikat_pengawas,nomor_sertifikat,' . $sertifikatPengawa->id,
            'tahun_ajaran' => 'required|string|max:20',
            'nama_kaprodi' => 'required|string|max:255',
            'nip_kaprodi' => 'required|string|max:255',
        ]);

        $sertifikatPengawa->update($request->all());

        return redirect()->route('superadmin.sertifikat-pengawas.index')
            ->with('success', 'Sertifikat pengawas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SertifikatPengawas $sertifikatPengawa)
    {
        // Delete file if exists
        if ($sertifikatPengawa->file_path && Storage::exists($sertifikatPengawa->file_path)) {
            Storage::delete($sertifikatPengawa->file_path);
        }

        $sertifikatPengawa->delete();

        return redirect()->route('superadmin.sertifikat-pengawas.index')
            ->with('success', 'Sertifikat pengawas berhasil dihapus.');
    }

    /**
     * Generate PDF certificate dengan template Canva
     */
    public function generate(SertifikatPengawas $sertifikatPengawa)
    {
        try {
            if (!$sertifikatPengawa) {
                return redirect()->back()->with('error', 'Data sertifikat tidak ditemukan.');
            }

            // Generate PDF dengan template background
            $pdf = $this->generatePDFWithTemplate($sertifikatPengawa);
            
            // Save PDF to storage (public disk)
            $filename = 'sertifikat-pengawas-' . $sertifikatPengawa->nomor_sertifikat . '.pdf';
            $path = 'sertifikats/' . $filename;
            Storage::disk('public')->put($path, $pdf->output());

            // Update model
            $sertifikatPengawa->update([
                'file_path' => $path,
                'is_generated' => true,
            ]);

            return redirect()->back()->with('success', 'Sertifikat berhasil Dicetak.');

        } catch (\Exception $e) {
            \Log::error('PDF Generation Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Generate PDF dengan template sebagai background
     */
    private function generatePDFWithTemplate(SertifikatPengawas $sertifikat)
    {
        // Dapatkan path template yang benar
        $templatePath = $this->getTemplatePath();
        
        if (!$templatePath) {
            throw new \Exception("Template sertifikat tidak ditemukan. Pastikan file template ada di storage/app/public/sertifikat/sertifikat.png");
        }

        // Konversi template ke base64 untuk PDF
        $templateBase64 = base64_encode(file_get_contents($templatePath));

        $pdf = Pdf::loadView('superadmin.sertifikat-pengawas.template', [
            'sertifikat' => $sertifikat,
            'templateBase64' => $templateBase64
        ])
        ->setPaper('a4', 'landscape')
        ->setOptions([
            'dpi' => 300, // Tingkatkan DPI untuk kualitas lebih baik
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'isPhpEnabled' => true,
            'defaultFont' => 'times',
            'margin-top' => 0,
            'margin-right' => 0,
            'margin-bottom' => 0,
            'margin-left' => 0,
        ]);

        return $pdf;
    }

    /**
     * Cari path template yang benar
     */
    private function getTemplatePath()
    {
        $possiblePaths = [
            storage_path('app/public/sertifikat/sertifikat.png'), // Recommended
            public_path('storage/sertifikat/sertifikat.png'),
            public_path('sertifikat/sertifikat.png'),
        ];
        
        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                \Log::info("Template found: " . $path);
                return $path;
            }
        }
        
        \Log::error("Template not found in: " . implode(', ', $possiblePaths));
        return null;
    }

    /**
     * Download PDF certificate
     */
    public function download(SertifikatPengawas $sertifikatPengawa)
    {
        if (!$sertifikatPengawa->is_generated || !$sertifikatPengawa->file_path) {
            return redirect()->back()->with('error', 'Sertifikat belum dicetak.');
        }

        \Log::info("Download attempt - File path from DB: " . $sertifikatPengawa->file_path);

        // Use Storage facade to get the file from public disk
        $filePath = $sertifikatPengawa->file_path;

        if (!Storage::disk('public')->exists($filePath)) {
            \Log::error("File not found in public storage. Path: " . $filePath);

            // Debug: List files in sertifikats directory
             $sertifikatsDir = storage_path('app/public/sertifikats');
            if (is_dir($sertifikatsDir)) {
                $files = scandir($sertifikatsDir);
                \Log::info("Files in sertifikats directory: " . implode(', ', array_filter($files, fn($f) => !in_array($f, ['.', '..']))));
            }

            return redirect()->back()->with('error', 'File sertifikat tidak ditemukan. Silahkan cetak ulang sertifikat.');
        }

        return Storage::disk('public')->download($filePath);
    }

    /**
     * Preview certificate
     */
    public function preview(SertifikatPengawas $sertifikatPengawa)
    {
        if (!$sertifikatPengawa->is_generated || !$sertifikatPengawa->file_path) {
            return redirect()->back()->with('error', 'Sertifikat belum dicetak.');
        }

        $filePath = storage_path('app/' . $sertifikatPengawa->file_path);
        
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File sertifikat tidak ditemukan.');
        }

        return response()->file($filePath);
    }

    /**
     * Generate all certificates that haven't been generated yet
     */
    public function generateAll()
    {
        try {
            $ungeneratedCertificates = SertifikatPengawas::where('is_generated', false)->get();

            if ($ungeneratedCertificates->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada sertifikat yang perlu dicetak.');
            }

            $successCount = 0;
            $errors = [];

            foreach ($ungeneratedCertificates as $sertifikat) {
                try {
                    // Generate PDF dengan template background
                    $pdf = $this->generatePDFWithTemplate($sertifikat);

                    // Save PDF to storage (public disk)
                    $filename = 'sertifikat-pengawas-' . $sertifikat->nomor_sertifikat . '.pdf';
                    $path = 'sertifikats/' . $filename;
                    Storage::disk('public')->put($path, $pdf->output());

                    // Update model
                    $sertifikat->update([
                        'file_path' => $path,
                        'is_generated' => true,
                    ]);

                    $successCount++;

                } catch (\Exception $e) {
                    $errors[] = "Error generating certificate for {$sertifikat->nama_pengawas}: " . $e->getMessage();
                    \Log::error('Batch PDF Generation Error for ' . $sertifikat->nama_pengawas . ': ' . $e->getMessage());
                }
            }

            $message = "Berhasil Cetak {$successCount} sertifikat.";

            if (!empty($errors)) {
                $message .= " Namun ada " . count($errors) . " error(s). Lihat log untuk detail.";
                \Log::error('Batch Generation Errors: ' . implode('; ', $errors));
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            \Log::error('Batch PDF Generation Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Debug template position
     */
    public function debug(SertifikatPengawas $sertifikatPengawa)
    {
        $templatePath = $this->getTemplatePath();

        if (!$templatePath) {
            return "Template tidak ditemukan. Pastikan file ada di: " . storage_path('app/public/sertifikat/sertifikat.png');
        }

        $templateBase64 = base64_encode(file_get_contents($templatePath));

        return view('superadmin.sertifikat-pengawas.debug', [
            'sertifikat' => $sertifikatPengawa,
            'templateBase64' => $templateBase64
        ]);
    }
}