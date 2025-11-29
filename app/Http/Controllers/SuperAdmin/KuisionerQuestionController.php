<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KuisionerQuestion;

class KuisionerQuestionController extends Controller
{
    public function index()
    {
        $questions = KuisionerQuestion::orderBy('order')->get();
        return view('superadmin.kuisioner_questions.index', compact('questions'));
    }

    public function create()
    {
        $nextOrder = KuisionerQuestion::max('order') + 1;
        return view('superadmin.kuisioner_questions.create', compact('nextOrder'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question_text' => 'required|string',
            'type' => 'required|in:rating,text,yes_no',
            'is_active' => 'boolean',
            'order' => 'integer',
        ]);

        KuisionerQuestion::create([
            'question_text' => $request->question_text,
            'type' => $request->type,
            'is_active' => $request->has('is_active'),
            'order' => $request->order ?? 0,
        ]);

        return redirect()->route('superadmin.kuisioner_questions.index')->with('success', 'Pertanyaan kuisioner berhasil ditambahkan.');
    }

    public function edit(KuisionerQuestion $kuisionerQuestion)
    {
        return view('superadmin.kuisioner_questions.edit', compact('kuisionerQuestion'));
    }

    public function update(Request $request, KuisionerQuestion $kuisionerQuestion)
    {
        $request->validate([
            'question_text' => 'required|string',
            'type' => 'required|in:rating,text,yes_no',
            'is_active' => 'boolean',
            'order' => 'integer',
        ]);

        $kuisionerQuestion->update([
            'question_text' => $request->question_text,
            'type' => $request->type,
            'is_active' => $request->has('is_active'),
            'order' => $request->order ?? 0,
        ]);

        return redirect()->route('superadmin.kuisioner_questions.index')->with('success', 'Pertanyaan kuisioner berhasil diperbarui.');
    }

    public function destroy(KuisionerQuestion $kuisionerQuestion)
    {
        $kuisionerQuestion->delete();
        return redirect()->route('superadmin.kuisioner_questions.index')->with('success', 'Pertanyaan kuisioner berhasil dihapus.');
    }
}
