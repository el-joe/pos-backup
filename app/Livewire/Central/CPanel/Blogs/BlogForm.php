<?php

namespace App\Livewire\Central\CPanel\Blogs;

use App\Models\Blog;
use App\Traits\LivewireOperations;
use Illuminate\Support\Arr;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.cpanel')]
class BlogForm extends Component
{
    use LivewireOperations;

    public ?Blog $blog = null;
    public array $data = [];

    public $imageFile = null;

    public array $rules = [
        'data.title_en' => 'required|string|max:255',
        'data.title_ar' => 'nullable|string|max:255',
        'data.excerpt_en' => 'nullable|string',
        'data.excerpt_ar' => 'nullable|string',
        'data.content_en' => 'required|string',
        'data.content_ar' => 'nullable|string',
        'data.is_published' => 'boolean',
        'data.published_at' => 'nullable|date',
    ];

    public function mount(?int $id = null): void
    {
        $this->blog = $id ? Blog::findOrFail($id) : null;

        if ($this->blog) {
            $this->data = Arr::only($this->blog->toArray(), [
                'title_en',
                'title_ar',
                'excerpt_en',
                'excerpt_ar',
                'content_en',
                'content_ar',
                'is_published',
                'published_at',
            ]);

            $this->data['is_published'] = (bool) ($this->data['is_published'] ?? false);
            $this->data['published_at'] = $this->blog->published_at?->format('Y-m-d\TH:i');
        } else {
            $this->data = [
                'title_en' => '',
                'title_ar' => '',
                'excerpt_en' => '',
                'excerpt_ar' => '',
                'content_en' => '',
                'content_ar' => '',
                'is_published' => true,
                'published_at' => null,
            ];
        }
    }

    public function save(): void
    {
        $this->validate([
            ...$this->rules,
            'imageFile' => 'nullable|image|max:5120',
        ]);

        $payload = $this->data;

        if (empty($payload['published_at'])) {
            $payload['published_at'] = null;
        }

        $blog = $this->blog ?: new Blog();


        $blog->fill($payload);
        if ($this->imageFile) {
            $blog->image_file = $this->imageFile;

            $this->imageFile = null;
        }

        $blog->save();


        $this->blog = $blog;

        $this->popup('success', 'Blog saved successfully');

        $this->redirect(route('cpanel.blogs.edit', ['id' => $blog->id]), navigate: true);
    }

    public function render()
    {
        return view('livewire.central.cpanel.blogs.blog-form');
    }
}
