@extends('public.layouts.main')

@section('title', 'Home - ' . config('app.name'))

@section('content')

<!-- Home Blocks (Drag & Drop Widgets) -->
@if(isset($homeBlocks) && $homeBlocks->count() > 0)
    <div class="mx-auto">
        @foreach($homeBlocks as $block)
            @php $widget = $block->globalWidget; @endphp
            @if($widget)

                   

                    @if($widget->tipo === 'gallery')
                    <div class="max-w-full w-screen mx-auto widget-block bg-white shadow-sm rounded-none overflow-hidden">
                    @if($widget->titolo)<h2 class="text-3xl hidden font-extrabold text-gray-900 tracking-tight sm:text-4xl mb-8 pb-4">{{ $widget->titolo }}</h2>
                    @endif    
                        @include('public.partials.widgets.gallery', ['widget' => $widget])</div>
                    @elseif($widget->tipo === 'video')
                        <div class="max-w-7xl mx-auto mb-12 widget-block bg-white rounded-2xl shadow-sm ring-1 ring-gray-800 p-8 overflow-hidden">
                            @if($widget->titolo)<h2 class="text-3xl font-extrabold text-gray-900 tracking-tight sm:text-4xl mb-8 pb-4 border-b border-gray-100">{{ $widget->titolo }}</h2>
                            @endif
                            @include('public.partials.widgets.video', ['widget' => $widget])</div>
                    @elseif($widget->tipo === 'mirror_blocks')
                        <div class="max-w-7xl mx-auto mb-12 widget-block bg-white rounded-2xl shadow-sm ring-1 ring-gray-800 p-8 overflow-hidden">
                            @if($widget->titolo)<h2 class="text-3xl font-extrabold hidden text-gray-900 tracking-tight sm:text-4xl mb-8 pb-4 border-b border-gray-100">{{ $widget->titolo }}</h2>
                            @endif
                            @include('public.partials.widgets.mirror', ['widget' => $widget])</div>
                    @elseif($widget->tipo === 'single_block')
                        <div class="max-w-7xl mx-auto mb-12 widget-block bg-white rounded-2xl shadow-sm ring-gray-800 overflow-hidden">
                            @if($widget->titolo)<h2 class="text-3xl font-extrabold text-gray-900 tracking-tight sm:text-4xl mb-8 pb-4 border-b border-gray-100">{{ $widget->titolo }}</h2>
                            @endif
                            @include('public.partials.widgets.single_block', ['widget' => $widget])</div>
                    @elseif($widget->tipo === 'section_grid')
                        <div class="max-w-7xl mx-auto mb-12 widget-block bg-white rounded-2xl shadow-sm ring-1 ring-gray-800 p-8 overflow-hidden">
                            @if($widget->titolo)<h2 class="text-3xl font-extrabold text-gray-900 tracking-tight sm:text-4xl mb-8 pb-4 border-b border-gray-100">{{ $widget->titolo }}</h2>
                            @endif
                            @include('public.partials.widgets.section_grid', ['widget' => $widget])</div>
                    @elseif($widget->tipo === 'image_text_image')
                        <div class="max-w-7xl mx-auto mb-12 widget-block overflow-hidden">
                            @if($widget->titolo)<h2 class="text-3xl font-extrabold text-center text-gray-900 tracking-tight sm:text-4xl px-4 py-6 hidden">{{ $widget->titolo }}</h2>
                            @endif
                            @include('public.partials.widgets.image_text_image', ['widget' => $widget])</div>
                    @elseif($widget->tipo === 'booking_search' && (config('app.booking_enabled') === '1' || \App\Models\Setting::where('key', 'booking_enabled')->value('value') == '1'))
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 relative z-20 widget-block">
                            @include('public.partials.widgets.booking_search', ['widget' => $widget])
                        </div>
                    @endif
                
            @endif
        @endforeach
    </div>
@else
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
        <p class="text-xl text-gray-500">Nessun contenuto presente in Home Page.</p>
    </div>
@endif

@endsection
