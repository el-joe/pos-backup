<?php

namespace App\Http\Controllers\Central\CPanel;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    public function create()
    {
        return view('central.cpanel.pages.form', ['page' => null]);
    }

    public function edit(int $id)
    {
        $page = Page::findOrFail($id);

        return view('central.cpanel.pages.form', ['page' => $page]);
    }

    public function store(Request $request)
    {
        return $this->save($request, new Page());
    }

    public function update(Request $request, int $id)
    {
        $page = Page::findOrFail($id);

        return $this->save($request, $page);
    }

    protected function save(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'nullable|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('central.pages', 'slug')->ignore($page->id),
            ],
            'short_description_en' => 'nullable|string',
            'short_description_ar' => 'nullable|string',
            'content_en' => 'required|string',
            'content_ar' => 'nullable|string',
        ]);

        $validated['is_published'] = $request->boolean('is_published');

        $page->fill($validated);
        $page->save();

        return redirect()
            ->route('cpanel.pages.edit', ['id' => $page->id])
            ->with('success', 'Page saved successfully');
    }
}
