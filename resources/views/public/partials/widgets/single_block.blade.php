<div class="mb-12">
    @php
        $bgColor = $widget->data['bg_color'] ?? '#ffffff';
        $textColor = '#333333';
        
        // Simple logic to set text to white on dark backgrounds
        $hex = str_replace('#', '', $bgColor);
        if (strlen($hex) == 6) {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
            // Luminance formula
            $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
            if ($luminance < 0.5) {
                $textColor = '#ffffff';
            }
        }
    @endphp

    <div class="rounded-xl overflow-hidden shadow-lg transition-transform hover:-translate-y-1" style="background-color: {{ $bgColor }}; color: {{ $textColor }};">
        @if(($widget->data['layout'] ?? 'side') == 'stacked')
            <!-- Layout Stacked (Foto Sopra) -->
            <div class="flex flex-col">
                @if(!empty($widget->data['video']))
                    <div class="h-64 md:h-96 relative bg-black">
                        <video autoplay loop muted playsinline class="absolute inset-0 w-full h-full object-cover">
                            <source src="{{ asset($widget->data['video']) }}" type="video/mp4">
                        </video>
                    </div>
                @elseif(!empty($widget->data['image']))
                    <div class="h-64 md:h-96 relative bg-gray-200">
                        <img src="{{ asset($widget->data['image']) }}" alt="{{ $widget->titolo }}" class="absolute inset-0 w-full h-full object-cover">
                    </div>
                @endif

                <div class="p-8 md:p-12 text-center">
                    @if(!empty($widget->data['subtitle']))
                        <p class="text-sm font-semibold tracking-wider uppercase opacity-80 mb-2">{{ $widget->data['subtitle'] }}</p>
                    @endif
                    
                    <h3 class="text-3xl font-bold mb-6 leading-tight">{{ $widget->titolo }}</h3>
                    
                    @if(!empty($widget->data['link']))
                        <div class="mt-4">
                            <a href="{{ $widget->data['link'] }}" class="inline-block px-8 py-4 rounded-full font-bold transition-transform hover:scale-105" 
                               style="background-color: {{ $textColor }}; color: {{ $bgColor }}; border: 1px solid {{ $textColor }};">
                                Scopri di più
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <!-- Layout Side (Foto a Sinistra) - Default -->
            <div class="flex flex-col md:flex-row items-stretch">
                @if(!empty($widget->data['video']))
                    <div class="md:w-1/2 relative min-h-[300px] bg-black">
                        <video autoplay loop muted playsinline class="absolute inset-0 w-full h-full object-cover">
                            <source src="{{ asset($widget->data['video']) }}" type="video/mp4">
                        </video>
                    </div>
                @elseif(!empty($widget->data['image']))
                    <div class="md:w-1/2 relative min-h-[300px] bg-gray-200">
                        <img src="{{ asset($widget->data['image']) }}" alt="{{ $widget->titolo }}" class="absolute inset-0 w-full h-full object-cover">
                    </div>
                @endif
                
                <div class="p-8 md:p-12 {{ empty($widget->data['image']) ? 'w-full text-center' : 'md:w-1/2 flex flex-col justify-center' }}">
                    @if(!empty($widget->data['subtitle']))
                        <p class="text-sm font-semibold tracking-wider uppercase opacity-80 mb-2">{{ $widget->data['subtitle'] }}</p>
                    @endif
                    
                    <h3 class="text-3xl font-bold mb-6 leading-tight">{{ $widget->titolo }}</h3>
                    
                    @if(!empty($widget->data['link']))
                        <div class="mt-4">
                            <a href="{{ $widget->data['link'] }}" class="inline-block px-8 py-4 rounded-full font-bold transition-transform hover:scale-105" 
                               style="background-color: {{ $textColor }}; color: {{ $bgColor }}; border: 1px solid {{ $textColor }};">
                                Scopri di più
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
