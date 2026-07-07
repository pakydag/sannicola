@if(!empty($widget->data['photos']) && count($widget->data['photos']) > 0)

    @if(isset($adminPreview) && $adminPreview)
        <!-- Rendering semplificato per l'area di amministrazione (no js, no swiper) -->
        <div class="flex gap-2 overflow-x-auto pb-4">
            @foreach($widget->data['photos'] as $photo)
                <div class="flex-shrink-0 relative">
                    @if(!empty($photo['url']))
                        <img src="{{ asset($photo['url']) }}" class="h-32 w-48 object-cover rounded shadow-sm border border-gray-200" alt="Anteprima">
                    @endif
                    @if(!empty($photo['video_url']))
                        <div class="absolute bottom-1 right-1 bg-black bg-opacity-70 text-white text-[10px] px-1 rounded flex items-center">
                            <svg class="w-3 h-3 mr-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h12a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.5 9a.5.5 0 00-.5.5v1a.5.5 0 00.5.5h1a.5.5 0 00.5-.5v-1a.5.5 0 00-.5-.5h-1z" /></svg> Video
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <!-- Rendering Completo (Slide Swiper) per il Sito Pubblico -->
        <!-- Include Swiper.js CSS if not already present -->
        @once
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
        @endonce

        <div class="swiper widget-gallery-swiper shadow-lg overflow-hidden rounded-lg h-[400px] md:h-[650px]">
            <div class="swiper-wrapper flex items-center">
                @foreach($widget->data['photos'] as $photo)
                    @if(!empty($photo['url']) || !empty($photo['video_url']))
                        <div class="swiper-slide overflow-hidden group relative" role="group">

                            @if(!empty($photo['video_url']))
                                <video autoplay loop muted playsinline class="w-full h-full object-cover">
                                    <source src="{{ asset($photo['video_url']) }}" type="video/mp4">
                                </video>
                            @elseif(!empty($photo['url']))
                                <div class="relative w-full h-full">
                                    @if(!empty($photo['link']) && empty($photo['titolo']) && empty($photo['sottotitolo']))
                                        <a href="{{ $photo['link'] }}" target="_blank" rel="noopener" class="block w-full h-full">
                                            <img src="{{ asset($photo['url']) }}" class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-110" alt="Image">
                                        </a>
                                    @else
                                        <img src="{{ asset($photo['url']) }}" class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-110" alt="Image">
                                    @endif
                                </div>
                            @endif

                            @if(!empty($photo['titolo']) || !empty($photo['sottotitolo']))
                                <div class="absolute inset-0 bg-black bg-opacity-40 flex flex-col justify-end p-8 md:p-16 text-white text-left z-10">
                                    @if(!empty($photo['titolo']))
                                        <h3 class="text-2xl md:text-4xl font-extrabold mb-2 tracking-tight drop-shadow-md">{{ $photo['titolo'] }}</h3>
                                    @endif
                                    @if(!empty($photo['sottotitolo']))
                                        <p class="text-sm md:text-lg mb-4 opacity-90 font-medium drop-shadow-sm">{{ $photo['sottotitolo'] }}</p>
                                    @endif
                                    @if(!empty($photo['link']))
                                        <div>
                                            <a href="{{ $photo['link'] }}" target="_blank" rel="noopener" class="inline-flex items-center px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition-all shadow-md text-sm hover:scale-105 transform">
                                                Scopri di più
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @elseif(!empty($photo['link']))
                                <a href="{{ $photo['link'] }}" target="_blank" rel="noopener" class="absolute inset-0 z-10"></a>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Add Pagination -->
            <div class="swiper-pagination"></div>
            <!-- Add Navigation -->
            <div class="swiper-button-prev !text-white !bg-black !bg-opacity-30 !w-12 !h-12 !rounded-full after:!text-xl hover:!bg-opacity-60 transition"></div>
            <div class="swiper-button-next !text-white !bg-black !bg-opacity-30 !w-12 !h-12 !rounded-full after:!text-xl hover:!bg-opacity-60 transition"></div>
        </div>

        <!-- Include Swiper.js Script and Init -->
        @once
            <script type="module">
                import Swiper from 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.mjs'

                document.addEventListener('DOMContentLoaded', () => {
                    new Swiper('.widget-gallery-swiper', {
                        loop: true,
                        grabCursor: true,
                        autoplay: {
                            delay: 5000,
                            disableOnInteraction: false,
                        },
                        pagination: {
                            el: '.swiper-pagination',
                            clickable: true,
                        },
                        navigation: {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev',
                        },
                    });
                });
            </script>
        @endonce
    @endif

@else
    <p class="text-gray-500 italic">Gallery vuota.</p>
@endif
