<div class="w-full h-64 sm:h-96 md:h-[500px] relative overflow-hidden">
    <video 
        class="w-full h-full object-cover" 
        controls 
        controlsList="nodownload" 
        preload="metadata"
        {{ !empty($widget->data['autoplay']) ? 'autoplay muted playsinline' : '' }}
        {{ !empty($widget->data['loop']) ? 'loop' : '' }}>
        <source src="{{ asset($widget->data['video_url'] ?? '') }}" type="video/mp4">
        
        Il tuo browser non supporta il tag video.
    </video>
</div>
