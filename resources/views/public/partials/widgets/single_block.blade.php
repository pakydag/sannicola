<div>
    @php
        $bgColor = 'transparent';
        $textColor = '#000000';

        // Simple logic to set text to white on dark backgrounds
        $hex = str_replace('#', '', $bgColor);
        if (strlen($hex) == 6) {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
            // Luminance formula
            $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
            if ($luminance < 0.5) {
                $textColor = '#dedede';
            }
        }
    @endphp

    <div class="overflow-hidden" style="background-color: {{ $bgColor }}; color: {{ $textColor }};">
        @if(($widget->data['layout'] ?? 'side') == 'stacked')
            <!-- Layout Stacked (Foto Sopra) -->
            <div class="flex flex-col bg-gray-100 rounded-lg">
                @if(!empty($widget->data['video']))
                    <div class="h-64 md:h-96 relative bg-black">
                        <video autoplay loop muted playsinline class="absolute inset-0 w-full h-full object-cover">
                            <source src="{{ asset($widget->data['video']) }}" type="video/mp4">
                        </video>
                    </div>
                @elseif(!empty($widget->data['image']))
                    <div class="h-64 md:h-96 relative bg-gray-200">
                        <img src="{{ asset($widget->data['image']) }}" alt="{{ $widget->titolo }}" class="absolute inset-0 w-full h-full object-cover rounded-t-lg">
                    </div>
                @endif

                <div class="titoli p-8 md:p-12 text-center">
                    @if(!empty($widget->data['subtitle']))
                        <h2 class="text-base mb-2">{{ $widget->data['subtitle'] }}</h2>
                    @endif

                    <h3 class="text-3xl mb-6 leading-tight">{{ $widget->titolo }}</h3>

                    @if(!empty($widget->data['link']))
                        <div class="mt-4">
                            <a href="{{ $widget->data['link'] }}" class="btn">
                                {{ app()->getLocale() === 'en' ? 'Find out more' : 'Scopri di più' }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <!-- Layout Side (Foto a Sinistra) - Default -->
            <div class="flex flex-col md:flex-row items-stretch bg-gray-100 rounded-lg">
                @if(!empty($widget->data['video']))
                    <div class="md:w-1/2 relative min-h-[300px] bg-black">
                        <video autoplay loop muted playsinline class="absolute inset-0 w-full h-full object-cover">
                            <source src="{{ asset($widget->data['video']) }}" type="video/mp4">
                        </video>
                    </div>
                @elseif(!empty($widget->data['image']))
                    <div class="md:w-1/2 relative min-h-[300px] photo rounded-r-none rounded-r-none">
                        <img src="{{ asset($widget->data['image']) }}" alt="{{ $widget->titolo }}" class="absolute inset-0 w-full h-full object-cover">
                    </div>
                @endif

                <div class="titoli p-8 md:p-12 {{ empty($widget->data['image']) ? 'w-full text-center' : 'md:w-1/2 flex flex-col justify-center' }}">
                    @if(!empty($widget->data['subtitle']))
                        <p class="text-sm font-semibold tracking-wider uppercase opacity-80 mb-2">{{ $widget->data['subtitle'] }}</p>
                    @endif

                    <h3 class="text-3xl mb-6 leading-tight">{{ $widget->titolo }}</h3>

                    @if(!empty($widget->data['link']))
                        <div class="mt-4">
                            <a href="{{ $widget->data['link'] }}" class="btn">
                                {{ app()->getLocale() === 'en' ? 'Find out more' : 'Scopri di più' }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
