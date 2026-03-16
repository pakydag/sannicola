<div class="video-container relative w-full overflow-hidden rounded-xl shadow-lg" style="padding-top: 56.25%;">
    <video 
        class="absolute top-0 left-0 w-full h-full object-cover" 
        controls 
        controlsList="nodownload" 
        preload="metadata">
        
        <source src="{{ $widget->data['video_url'] ?? '' }}" type="video/mp4">
        
        Il tuo browser non supporta il tag video.
    </video>
</div>
