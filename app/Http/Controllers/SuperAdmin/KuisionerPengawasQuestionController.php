<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\KuisionerPengawasQuestion;
use Illuminate\Http\Request;

class KuisionerPengawasQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $questions = KuisionerPengawasQuestion::ordered()->get();
        return view('superadmin.kuisioner_pengawas_questions.index', compact('questions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $nextOrder = KuisionerPengawasQuestion::max('order') + 1;
        return view('superadmin.kuisioner_pengawas_questions.create', compact('nextOrder'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'question_text' => 'required|string',
            'type' => 'required|in:rating,text,yes_no,qualitative_rating',
            'is_active' => 'boolean',
            'order' => 'integer|min:0',
        ]);

        KuisionerPengawasQuestion::create([
            'question_text' => $request->question_text,
            'type' => $request->type,
            'is_active' => $request->boolean('is_active'),
            'order' => $request->order ?? 0,
        ]);

        return redirect()->route('superadmin.kuisioner_pengawas_questions.index')
            ->with('success', 'Pertanyaan kuisioner pengawas berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $question = KuisionerPengawasQuestion::findOrFail($id);
        return view('superadmin.kuisioner_pengawas_questions.show', compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $question = KuisionerPengawasQuestion::findOrFail($id);
        return view('superadmin.kuisioner_pengawas_questions.edit', compact('question'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'question_text' => 'required|string',
            'type' => 'required|in:rating,text,yes_no,qualitative_rating', // Tambahkan qualitative_rating
            'is_active' => 'boolean',
            'order' => 'integer|min:0',
        ]);

        $question = KuisionerPengawasQuestion::findOrFail($id);
        
        $question->update([
            'question_text' => $request->question_text,
            'type' => $request->type,
            'is_active' => $request->boolean('is_active'),
            'order' => $request->order ?? $question->order,
        ]);

        return redirect()->route('superadmin.kuisioner_pengawas_questions.index')
            ->with('success', 'Pertanyaan kuisioner pengawas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $question = KuisionerPengawasQuestion::findOrFail($id);
        $question->delete();

        return redirect()->route('superadmin.kuisioner_pengawas_questions.index')
            ->with('success', 'Pertanyaan kuisioner pengawas berhasil dihapus.');
    }
}