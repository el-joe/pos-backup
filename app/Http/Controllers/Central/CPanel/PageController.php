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
            'page_type' => 'required|in:static,documentation',
            'section' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer',
            'youtube_videos' => 'nullable|array',
            'youtube_videos.*.title_en' => 'nullable|string|max:255',
            'youtube_videos.*.title_ar' => 'nullable|string|max:255',
            'youtube_videos.*.url' => 'nullable|string|max:500',
            'youtube_videos.*.position' => 'nullable|in:top,middle,bottom',
            'youtube_videos.*.description_en' => 'nullable|string',
            'youtube_videos.*.description_ar' => 'nullable|string',
        ]);

        $validated['is_published'] = $request->boolean('is_published');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        $validated['youtube_videos'] = collect($validated['youtube_videos'] ?? [])
            ->filter(fn ($video) => !empty($video['url']))
            ->map(function ($video) use ($page) {
                $url = $this->sanitizeYoutubeUrl((string) $video['url']);

                return [
                    'title_en' => $video['title_en'] ?? '',
                    'title_ar' => $video['title_ar'] ?? '',
                    'url' => $url,
                    'position' => in_array($video['position'] ?? 'top', ['top', 'middle', 'bottom'], true)
                        ? $video['position']
                        : 'top',
                    'description_en' => $video['description_en'] ?? '',
                    'description_ar' => $video['description_ar'] ?? '',
                ];
            })
            ->filter(fn ($video) => $video['url'] !== '')
            ->values()
            ->all();

        $page->fill($validated);
        $page->save();

        return redirect()
            ->route('cpanel.pages.edit', ['id' => $page->id])
            ->with('success', 'Page saved successfully');
    }

    /**
     * Only allow youtube.com / youtu.be hosts to prevent XSS via iframe src.
     */
    private function sanitizeYoutubeUrl(string $url): string
    {
        $url = trim($url);

        if ($url === '') {
            return '';
        }

        $parts = parse_url($url);

        if (!$parts || empty($parts['scheme']) || !in_array(strtolower($parts['scheme']), ['http', 'https'], true)) {
            return '';
        }

        if (empty($parts['host'])) {
            return '';
        }

        $host = strtolower(preg_replace('/^www\./', '', $parts['host']));

        if (!in_array($host, ['youtube.com', 'm.youtube.com', 'youtu.be'], true)) {
            return '';
        }

        return $url;
    }
}
