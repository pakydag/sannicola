@if(!empty($widget->data['photos']) && count($widget->data['photos']) > 0)
    <div class="w-full bg-black">
        <div class="swiper widget-gallery-swiper w-full h-screen">
            <div class="swiper-wrapper">
                @foreach($widget->data['photos'] as $photo)
                    @if(!empty($photo['url']))
                        <div class="swiper-slide h-screen">
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
            <div class="swiper-pagination"></div>
            <div class="swiper-button-prev !text-white !bg-black !bg-opacity-30 !w-12 !h-12 !rounded-full after:!text-xl hover:!bg-opacity-60 transition"></div>
            <div class="swiper-button-next !text-white !bg-black !bg-opacity-30 !w-12 !h-12 !rounded-full after:!text-xl hover:!bg-opacity-60 transition"></div>
        </div>
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
    </div>
@else
    <p class="text-gray-500 italic">Gallery vuota.</p>
@endif
