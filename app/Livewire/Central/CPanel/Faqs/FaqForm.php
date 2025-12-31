<?php

namespace App\Livewire\Central\CPanel\Faqs;

use App\Models\Faq;
use App\Traits\LivewireOperations;
use Illuminate\Support\Arr;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.cpanel')]
class FaqForm extends Component
{
    use LivewireOperations;

    public ?Faq $faq = null;
    public array $data = [];

    public array $rules = [
        'data.question_en' => 'required|string|max:255',
        'data.question_ar' => 'nullable|string|max:255',
        'data.answer_en' => 'required|string',
        'data.answer_ar' => 'nullable|string',
        'data.is_published' => 'boolean',
        'data.sort_order' => 'nullable|integer|min:0',
    ];

    public function mount(?int $id = null): void
    {
        $this->faq = $id ? Faq::findOrFail($id) : null;

        if ($this->faq) {
            $this->data = Arr::only($this->faq->toArray(), [
                'question_en',
                'question_ar',
                'answer_en',
                'answer_ar',
                'is_published',
                'sort_order',
            ]);

            $this->data['is_published'] = (bool) ($this->data['is_published'] ?? false);
        } else {
            $this->data = [
                'question_en' => '',
                'question_ar' => '',
                'answer_en' => '',
                'answer_ar' => '',
                'is_published' => true,
                'sort_order' => 0,
            ];
        }
    }

    public function save(): void
    {
        $this->validate($this->rules);

        $payload = $this->data;

        if ($payload['sort_order'] === null || $payload['sort_order'] === '') {
            $payload['sort_order'] = 0;
        }

        $faq = $this->faq ?: new Faq();

        $faq->fill($payload);
        $faq->save();

        $this->faq = $faq;

        $this->popup('success', 'FAQ saved successfully');

        $this->redirect(route('cpanel.faqs.edit', ['id' => $faq->id]), navigate: true);
    }

    public function render()
    {
        return view('livewire.central.cpanel.faqs.faq-form');
    }
}
