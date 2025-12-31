<?php

namespace App\Livewire\Central\CPanel\Pages;

use App\Models\Page;
use App\Traits\LivewireOperations;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.cpanel')]
class PageForm extends Component
{
    use LivewireOperations;

    public ?Page $page = null;
    public array $data = [];

    public function rules(): array
    {
        return [
            'data.title_en' => 'required|string|max:255',
            'data.title_ar' => 'nullable|string|max:255',
            'data.slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('central.pages', 'slug')->ignore($this->page?->id),
            ],
            'data.short_description_en' => 'nullable|string',
            'data.short_description_ar' => 'nullable|string',
            'data.content_en' => 'required|string',
            'data.content_ar' => 'nullable|string',
            'data.is_published' => 'boolean',
        ];
    }

    public function mount(?int $id = null): void
    {
        $this->page = $id ? Page::findOrFail($id) : null;

        if ($this->page) {
            $this->data = Arr::only($this->page->toArray(), [
                'title_en',
                'title_ar',
                'slug',
                'short_description_en',
                'short_description_ar',
                'content_en',
                'content_ar',
                'is_published',
            ]);

            $this->data['is_published'] = (bool) ($this->data['is_published'] ?? false);
        } else {
            $this->data = [
                'title_en' => '',
                'title_ar' => '',
                'slug' => '',
                'short_description_en' => '',
                'short_description_ar' => '',
                'content_en' => '',
                'content_ar' => '',
                'is_published' => true,
            ];
        }
    }

    public function save(): void
    {
        $this->validate($this->rules());

        $page = $this->page ?: new Page();

        $page->fill($this->data);
        $page->save();

        $this->page = $page;

        $this->popup('success', 'Page saved successfully');

        $this->redirect(route('cpanel.pages.edit', ['id' => $page->id]), navigate: true);
    }

    public function render()
    {
        return view('livewire.central.cpanel.pages.page-form');
    }
}
