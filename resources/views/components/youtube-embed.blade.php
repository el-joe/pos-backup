@props(['url' => '', 'title' => ''])

@if ($url)
    <div class="w-full max-w-3xl mx-auto my-6">
        <div class="relative w-full overflow-hidden rounded-2xl shadow-lg bg-black" style="aspect-ratio: 16 / 9;">
            <iframe src="{{ $url }}" title="{{ $title }}" allowfullscreen loading="lazy"
                    referrerpolicy="strict-origin-when-cross-origin"
                    sandbox="allow-scripts allow-same-origin allow-presentation"
                    class="absolute inset-0 w-full h-full border-0"></iframe>
        </div>
        @if ($title)
            <p class="text-center text-sm text-slate-500 dark:text-slate-400 mt-2">{{ $title }}</p>
        @endif
    </div>
@endif
