<?php

namespace App\Livewire\Central\CPanel\Blogs;

use App\Models\Blog;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.cpanel')]
class BlogsList extends Component
{
    use LivewireOperations, WithPagination;

    public ?Blog $current = null;

    public function setCurrent(?int $id): void
    {
        $this->current = $id ? Blog::find($id) : null;
    }

    public function deleteAlert(int $id): void
    {
        $this->setCurrent($id);

        $this->confirm('delete', 'warning', 'Are you sure?', 'You want to delete this Blog', 'Yes, delete it!');
    }

    public function delete(): void
    {
        if (!$this->current) {
            $this->popup('error', 'Blog not found');
            return;
        }

        $this->current->delete();

        $this->popup('success', 'Blog deleted successfully');

        $this->reset('current');
    }

    public function render()
    {
        $blogs = Blog::orderByDesc('id')->paginate(10);

        return view('livewire.central.cpanel.blogs.blogs-list', get_defined_vars());
    }
}
