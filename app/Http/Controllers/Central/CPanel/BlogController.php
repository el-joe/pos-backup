<?php

namespace App\Http\Controllers\Central\CPanel;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BlogController extends Controller
{
    public function create()
    {
        return view('central.cpanel.blogs.form', ['blog' => null]);
    }

    public function edit(int $id)
    {
        $blog = Blog::findOrFail($id);

        return view('central.cpanel.blogs.form', ['blog' => $blog]);
    }

    public function store(Request $request)
    {
        return $this->save($request, new Blog());
    }

    public function update(Request $request, int $id)
    {
        $blog = Blog::findOrFail($id);

        return $this->save($request, $blog);
    }

    protected function save(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'nullable|string|max:255',
            'excerpt_en' => 'nullable|string',
            'excerpt_ar' => 'nullable|string',
            'content_en' => 'required|string',
            'content_ar' => 'nullable|string',
            'published_at' => 'nullable|date',
            'imageFile' => 'nullable|image|max:5120',
        ]);

        $validated['is_published'] = $request->boolean('is_published');

        if (empty($validated['published_at'])) {
            $validated['published_at'] = null;
        }

        unset($validated['imageFile']);

        $blog->fill($validated);
        $blog->save();

        $blog->generateImages($request->file('imageFile'));

        return redirect()
            ->route('cpanel.blogs.edit', ['id' => $blog->id])
            ->with('success', 'Blog saved successfully');
    }
}
