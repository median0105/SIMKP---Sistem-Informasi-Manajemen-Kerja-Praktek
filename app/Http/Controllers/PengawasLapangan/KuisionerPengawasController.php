<?php

namespace App\Http\Controllers\PengawasLapangan;

use App\Http\Controllers\Controller;
use App\Models\KuisionerPengawas;
use App\Models\KuisionerPengawasQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KuisionerPengawasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $questions = KuisionerPengawasQuestion::active()->ordered()->get();
        $responses = KuisionerPengawas::where('pengawas_id', Auth::id())->get()->keyBy('kuisioner_pengawas_question_id');

        return view('pengawas.kuisioner_pengawas.index', compact('questions', 'responses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'responses' => 'required|array',
            'responses.*.question_id' => 'required|exists:kuisioner_pengawas_questions,id',
            'responses.*.answer' => 'nullable|string',
            'responses.*.rating' => 'nullable|integer|min:1|max:5',
            'responses.*.yes_no' => 'nullable|boolean',
        ]);

        foreach ($request->responses as $responseData) {
            KuisionerPengawas::updateOrCreate(
                [
                    'pengawas_id' => Auth::id(),
                    'kuisioner_pengawas_question_id' => $responseData['question_id'],
                ],
                [
                    'answer' => $responseData['answer'] ?? null,
                    'rating' => $responseData['rating'] ?? null,
                    'yes_no' => $responseData['yes_no'] ?? null,
                    'submitted_at' => now(),
                ]
            );
        }

        return redirect()->route('pengawas.kuisioner-pengawas.index')
            ->with('success', 'Kuisioner berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
