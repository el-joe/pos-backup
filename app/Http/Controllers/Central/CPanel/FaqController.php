<?php

namespace App\Http\Controllers\Central\CPanel;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function create()
    {
        return view('central.cpanel.faqs.form', ['faq' => null]);
    }

    public function edit(int $id)
    {
        $faq = Faq::findOrFail($id);

        return view('central.cpanel.faqs.form', ['faq' => $faq]);
    }

    public function store(Request $request)
    {
        return $this->save($request, new Faq());
    }

    public function update(Request $request, int $id)
    {
        $faq = Faq::findOrFail($id);

        return $this->save($request, $faq);
    }

    protected function save(Request $request, Faq $faq)
    {
        $validated = $request->validate([
            'question_en' => 'required|string|max:255',
            'question_ar' => 'nullable|string|max:255',
            'answer_en' => 'required|string',
            'answer_ar' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['is_published'] = $request->boolean('is_published');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $faq->fill($validated);
        $faq->save();

        return redirect()
            ->route('cpanel.faqs.edit', ['id' => $faq->id])
            ->with('success', 'FAQ saved successfully');
    }
}
