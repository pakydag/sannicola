@if(!empty($widget->data['photos']) && count($widget->data['photos']) > 0)
    
    @if(isset($adminPreview) && $adminPreview)
        <!-- Rendering semplificato per l'area di amministrazione (no js, no swiper) -->
        <div class="flex gap-2 overflow-x-auto pb-4">
            @foreach($widget->data['photos'] as $photo)
                @if(!empty($photo['url']))
                    <img src="{{ $photo['url'] }}" class="h-32 object-cover rounded shadow-sm border border-gray-200" alt="Anteprima">
                @endif
            @endforeach
        </div>
    @else
        <!-- Rendering Completo (Slide Swiper) per il Sito Pubblico -->
        <!-- Include Swiper.js CSS if not already present -->
        @once
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
        @endonce

        <div class="swiper widget-gallery-swiper shadow-lg border border-gray-100 overflow-hidden">
            <div class="swiper-wrapper">
                @foreach($widget->data['photos'] as $photo)
                    @if(!empty($photo['url']))
                        <div class="swiper-slide h-64 sm:h-96 md:h-[500px]">
                            @if(!empty($photo['link']))
                                <a href="{{ $photo['link'] }}" target="_blank" rel="noopener" class="block w-full h-full">
                                    <img src="{{ $photo['url'] }}" class="w-full h-full object-cover" alt="Image">
                                </a>
                            @else
                                <img src="{{ $photo['url'] }}" class="w-full h-full object-cover" alt="Image">
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
