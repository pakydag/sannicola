@php
    $leftLink = !empty($widget->data['link_left']) ? $widget->data['link_left'] : null;
    $rightLink = !empty($widget->data['link_right']) ? $widget->data['link_right'] : null;
    $textColor = $widget->data['text_color'] ?? '#111827';
@endphp

<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center p-6 overflow-hidden">
        
        <!-- Left Image -->
        <div class="col-span-1 overflow-hidden rounded-lg shadow-sm group">
            @if($leftLink)
                <a href="{{ $leftLink }}" target="_blank" rel="noopener noreferrer" class="block">
            @endif
            @if(!empty($widget->data['video_left']))
                <video autoplay loop muted playsinline class="w-full h-auto object-cover transform transition-transform duration-500 group-hover:scale-105">
                    <source src="{{ asset($widget->data['video_left']) }}" type="video/mp4">
                </video>
            @elseif(!empty($widget->data['img_left']))
                <img src="{{ asset($widget->data['img_left']) }}" alt="Foto Sinistra" class="w-full h-auto object-cover transform transition-transform duration-500 group-hover:scale-105">
            @endif
            @if($leftLink)
                </a>
            @endif
        </div>

        <!-- Center Text -->
        <div class="col-span-1 text-center flex flex-col justify-center items-center py-4 px-2" style="color: {{ $textColor }};">
            @if(!empty($widget->data['center_title']))
                <h3 class="text-2xl md:text-3xl font-extrabold mb-4 tracking-tight leading-tight uppercase relative inline-block">
                    {{ $widget->data['center_title'] }}
                    <span class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 w-12 h-1 bg-indigo-500 rounded-full"></span>
                </h3>
            @endif
            @if(!empty($widget->data['center_subtitle']))
                <p class="text-base md:text-lg opacity-90 leading-relaxed font-light mt-4">
                    {{ nl2br(e($widget->data['center_subtitle'])) }}
                </p>
            @endif
        </div>

        <!-- Right Image -->
        <div class="col-span-1 overflow-hidden rounded-lg shadow-sm group">
            @if($rightLink)
                <a href="{{ $rightLink }}" target="_blank" rel="noopener noreferrer" class="block">
            @endif
            @if(!empty($widget->data['video_right']))
                <video autoplay loop muted playsinline class="w-full h-auto object-cover transform transition-transform duration-500 group-hover:scale-105">
                    <source src="{{ asset($widget->data['video_right']) }}" type="video/mp4">
                </video>
            @elseif(!empty($widget->data['img_right']))
                <img src="{{ asset($widget->data['img_right']) }}" alt="Foto Destra" class="w-full h-auto object-cover transform transition-transform duration-500 group-hover:scale-105">
            @endif
            @if($rightLink)
                </a>
            @endif
        </div>

    </div>
</div>
