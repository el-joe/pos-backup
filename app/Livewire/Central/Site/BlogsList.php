<?php

namespace App\Livewire\Central\Site;

use App\Models\Blog;
use Livewire\Attributes\Url;
use Livewire\Component;

class BlogsList extends Component
{
    #[Url]
    public string $q = '';

    public function render()
    {
        $featured = Blog::published()
            ->orderByDesc('id')
            ->first();

        $blogs = Blog::published()
            ->where('id','!=',$featured?->id)
            ->when($this->q, function ($query) {
                $query->where(function ($q) {
                    $q->whereAny(['title_en','title_ar','excerpt_en','excerpt_ar'], 'like', '%' . $this->q . '%');
                });
            })
            ->orderByDesc('id')
            ->paginate(12);

        return view('livewire.central.'. defaultLandingLayout() .'.blogs-list',get_defined_vars());
    }
}
