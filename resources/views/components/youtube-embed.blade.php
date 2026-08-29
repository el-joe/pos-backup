@props(['url' => '', 'title' => ''])

@if ($url)
    <div class="ratio ratio-16x9 my-4">
        <iframe src="{{ $url }}" title="{{ $title }}" allowfullscreen loading="lazy"
                referrerpolicy="strict-origin-when-cross-origin"
                sandbox="allow-scripts allow-same-origin allow-presentation"></iframe>
    </div>
@endif
