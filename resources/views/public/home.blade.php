@extends('public.layouts.main')

@section('title', 'Home - ' . config('app.name'))

@section('content')

<!-- Home Blocks (Drag & Drop Widgets) -->
@if(isset($homeBlocks) && $homeBlocks->count() > 0)
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-16">
        @foreach($homeBlocks as $block)
            @php $widget = $block->globalWidget; @endphp
            @if($widget)
```blade
                <div class="widget-block bg-white rounded-2xl shadow-sm ring-1 ring-gray-800 p-8 overflow-hidden">
```
                    @if($widget->titolo)
                        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight sm:text-4xl mb-8 pb-4 border-b border-gray-100">{{ $widget->titolo }}</h2>
                    @endif

                    @if($widget->tipo === 'gallery')
                        @include('public.partials.widgets.gallery', ['widget' => $widget])
                    @elseif($widget->tipo === 'video')
                        @include('public.partials.widgets.video', ['widget' => $widget])
                    @elseif($widget->tipo === 'mirror_blocks')
                        @include('public.partials.widgets.mirror', ['widget' => $widget])
                    @elseif($widget->tipo === 'single_block')
                        @include('public.partials.widgets.single_block', ['widget' => $widget])
                    @elseif($widget->tipo === 'section_grid')
                        @include('public.partials.widgets.section_grid', ['widget' => $widget])
                    @endif
                </div>
            @endif
        @endforeach
    </div>
@else
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
        <p class="text-xl text-gray-500">Nessun contenuto presente in Home Page.</p>
    </div>
@endif

@endsection
