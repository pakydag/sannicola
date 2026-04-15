<div class="w-full h-64 sm:h-96 md:h-[500px] relative overflow-hidden">
    <video 
        class="w-full h-full object-cover" 
        controls 
        controlsList="nodownload" 
        preload="metadata"
        {{ !empty($widget->data['autoplay']) ? 'autoplay muted playsinline' : '' }}
        {{ !empty($widget->data['loop']) ? 'loop' : '' }}>
        @php
            $videoUrl = $widget->data['video_url'] ?? '';
            if ($videoUrl && !Str::startsWith($videoUrl, ['http://', 'https://'])) {
                $cleanPath = Str::startsWith($videoUrl, 'storage/') ? Str::after($videoUrl, 'storage/') : $videoUrl;
                $videoUrl = rtrim(env('STORAGE_URL', asset('storage')), '/') . '/' . ltrim($cleanPath, '/');
            }
        @endphp
        <source src="{{ $videoUrl }}" type="video/mp4">
        
        Il tuo browser non supporta il tag video.
    </video>
</div>
