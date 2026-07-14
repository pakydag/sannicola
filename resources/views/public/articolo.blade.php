@extends('public.layouts.main')

@section('title', $articolo->titolo . ' - ' . config('app.name'))

@section('content')
<div class="gradient relative bg-gray-50 h-64 flex items-end bg-cover bg-top px-6 lg:px-0" @if($articolo->section && $articolo->section->immagine) style="background-image: url('{{ asset($articolo->section->immagine) }}');" @endif>
    <div class="mx-auto max-w-7xl w-full bg-white rounded-t-lg px-6 lg:px-0 relative z-10">
        <!-- Breadcrumb / Back link -->
        <nav class=" flex p-6 items-left text-sm font-medium text-gray-400 breadcrumb">
            <a href="{{ route('public.home') }}" class="hover:text-gray-900">Home</a>
            <svg class="h-5 w-5 shrink-0 text-gray-400 mx-1" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>
            @if($articolo->section && $articolo->section->visibile)
                <a href="{{ route('public.sezione', $articolo->section->slug ?? $articolo->section->id.'-it') }}" class="hover:text-gray-900">{{ $articolo->section->nome }}</a>
            @else
                <span>Sezione Nascosta</span>
            @endif
        </nav>
    </div>
</div>
<div class="bg-gray-50 pb-16 mx-6 lg:mx-auto">

    <div class="mx-auto max-w-7xl rounded-b-lg">

        <article class="bg-white shadow-sm py-0 px-11 sm:px-12 lg:px-14 overflow-hidden relative rounded-lg">

            <header class="mb-10 text-center titoli">
                <p class="text-base font-semibold text-indigo-600 tracking-wide uppercase hidden">
                    {{ $articolo->section->nome ?? 'Senza Categoria' }}
                    @if($articolo->mostra_data)
                        &bull; {{ $articolo->created_at->format('d M Y') }}
                    @endif
                </p>
                <h1 class="mt-2 text-3xl sm:text-4xl tracking-tight primary">
                    {{ $articolo->titolo }}
                </h1>
                @if($articolo->sottotitolo)
                    <h2 class="mt-4 text-xl leading-18">
                        {{ $articolo->sottotitolo }}
                    </h2>
                @endif
            </header>

            @php
                $alignment = $articolo->allineamento_media ?? 'center';
                $alignmentClasses = 'w-full rounded-xl bg-gray-50 object-cover max-h-[600px] shadow-lg ring-1 ring-gray-200 mb-10';

                if ($alignment === 'left') {
                    $alignmentClasses = 'md:float-left md:mr-8 md:mb-6 md:w-1/2 w-full rounded-lg bg-gray-50 object-cover max-h-[500px] shadow-lg ring-1 ring-gray-200';
                } elseif ($alignment === 'right') {
                    $alignmentClasses = 'md:float-right md:ml-8 md:mb-6 md:w-1/2 w-full rounded-xl bg-gray-50 object-cover max-h-[500px] shadow-lg ring-1 ring-gray-200';
                }
            @endphp

            @if($articolo->video)
                <figure class="{{ $alignment === 'center' ? 'my-10' : '' }}">
                    <video class="{{ $alignmentClasses }}" autoplay loop muted playsinline controls>
                        <source src="{{ asset($articolo->video) }}" type="video/mp4">
                        Il tuo browser non supporta il tag video.
                    </video>
                </figure>
            @elseif($articolo->hasMedia('foto'))
                <figure class="{{ $alignment === 'center' ? 'my-10' : '' }}">
                    <img class="{{ $alignmentClasses }}" src="{{ $articolo->getFirstMediaUrl('foto') }}" alt="{{ $articolo->titolo }}">
                </figure>
            @elseif($articolo->foto)
                <figure class="{{ $alignment === 'center' ? 'my-10' : '' }}">
                    <img class="{{ $alignmentClasses }}" src="{{ asset($articolo->foto) }}" alt="{{ $articolo->titolo }}">
                </figure>
            @endif

            <div class="{{ $alignment === 'center' ? 'mx-auto max-w-4xl' : 'max-w-none' }} text-gray-600">
                {!! $articolo->descrizione !!}
            </div>

            @if($alignment !== 'center')
                <div class="clear-both"></div>
            @endif

            @if($articolo->hasMedia('allegati') || $articolo->link)
                <div class="mt-12 pt-8 border-t border-gray-100 flex flex-col sm:flex-row gap-4 justify-center items-center">
                    @if($articolo->link)
                        <a href="{{ $articolo->link }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center rounded-md bg-primary px-6 py-3 text-base font-semibold text-white shadow-sm hover:bg-secondary focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition">
                            Visita Sito Esterno
                            <svg class="ml-2 -mr-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                        </a>
                    @endif

                    @if($articolo->hasMedia('allegati'))
                        <a href="{{ $articolo->getFirstMediaUrl('allegati') }}" target="_blank" download class="inline-flex items-center rounded-md bg-white border border-gray-300 px-6 py-3 text-base font-semibold text-gray-700 shadow-sm hover:bg-gray-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition">
                            Scarica Allegato
                            <svg class="ml-2 -mr-1 h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                        </a>
                    @endif
                </div>
            @endif

        </article>

        <!-- Area Widget -->
        @if($articolo->widgets->count() > 0)
            <div>
                @foreach($articolo->widgets as $widget)
                    @php
                        $isGlobal = $widget->tipo === 'global_widget';
                        $actualWidget = $isGlobal ? \App\Models\GlobalWidget::find($widget->data['global_widget_id'] ?? null) : $widget;
                    @endphp


                    @if($actualWidget)
                        <div class="widget-block shadow-sm overflow-hidden {{ ($actualWidget->tipo === 'gallery' || $actualWidget->tipo === 'video') ? 'rounded-none p-0' : '' }}">
                            @if($actualWidget->titolo && !in_array($actualWidget->tipo, ['gallery', 'video']))
                                <h3 class="text-2xl font-bold text-gray-800 mb-6 pb-4 border-b border-gray-100">{{ $actualWidget->titolo }}</h3>
                            @endif

                            @if($actualWidget->tipo === 'gallery')
                                @include('public.partials.widgets.gallery', ['widget' => $actualWidget])
                            @elseif($actualWidget->tipo === 'video')
                                @include('public.partials.widgets.video', ['widget' => $actualWidget])
                            @elseif($actualWidget->tipo === 'mirror_blocks')
                                @include('public.partials.widgets.mirror', ['widget' => $actualWidget])
                            @elseif($actualWidget->tipo === 'single_block')
                                @include('public.partials.widgets.single_block', ['widget' => $actualWidget])
                            @elseif($actualWidget->tipo === 'section_grid')
                                @include('public.partials.widgets.section_grid', ['widget' => $actualWidget])
                            @elseif($actualWidget->tipo === 'info_blocks')
                                @include('public.partials.widgets.info_blocks', ['widget' => $actualWidget])
                            @elseif($actualWidget->tipo === 'booking_structures')
                                @include('public.partials.widgets.booking_structures', ['widget' => $actualWidget])
                            @elseif($actualWidget->tipo === 'map')
                                @include('public.partials.widgets.map', ['widget' => $actualWidget])
                            @elseif($actualWidget->tipo === 'shop_collection')
                                @include('public.partials.widgets.shop_collection', ['widget' => $actualWidget])
                            @elseif($actualWidget->tipo === 'shop_featured_products')
                                @include('public.partials.widgets.shop_featured_products', ['widget' => $actualWidget])
                            @elseif($actualWidget->tipo === 'shop_brands')
                                @include('public.partials.widgets.shop_brands', ['widget' => $actualWidget])
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
        @endif

        <!-- Area Forms -->
        @if($articolo->has_contact_form)
            <div>
                @include('public.partials.contact_form')
            </div>
        @endif

        @if($articolo->has_transfer_form)
            <div class="mt-8">
                @include('public.partials.transfer_form')
            </div>
        @endif

        @if($articolo->has_car_rental_form)
            <div class="mt-8">
                @include('public.partials.car_rental_form')
            </div>
        @endif

    </div>
</div>
@endsection
