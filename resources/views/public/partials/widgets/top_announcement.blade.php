@php
    $message = $widget->data['message'] ?? 'Benvenuti sul nostro sito!';
    $bgColor = $widget->data['bg_color'] ?? 'bg-indigo-600';
    $textColor = $widget->data['text_color'] ?? 'text-white';
    $btnText = $widget->data['button_text'] ?? null;
    $btnLink = $widget->data['button_url'] ?? '#';
    $icon = $widget->data['icon'] ?? '📣';
@endphp

<div class="{{ $bgColor }} {{ $textColor }} py-3 px-4 shadow-md relative overflow-hidden group">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-center gap-4 text-center sm:text-left relative z-10 transition-transform duration-500 hover:scale-[1.01]">
        <div class="flex items-center gap-3">
            <span class="text-2xl animate-bounce">{{ $icon }}</span>
            <p class="text-sm font-bold tracking-tight">
                {{ $message }}
            </p>
        </div>
        
        @if($btnText)
            <a href="{{ $btnLink }}" class="inline-flex items-center px-4 py-1.5 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full border border-white border-opacity-30 text-xs font-black uppercase tracking-widest transition-all hover:pr-6 relative">
                {{ $btnText }}
                <svg class="w-3 h-3 ml-2 absolute right-3 opacity-0 group-hover:opacity-100 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7-7 7"></path></svg>
            </a>
        @endif
    </div>
    
    <!-- Subtle overlay effect -->
    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
</div>
