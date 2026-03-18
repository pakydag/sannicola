@extends('public.layouts.main')

@section('title', 'Shop - ' . config('app.name'))

@section('content')
<div class="bg-white shadow-sm border-b">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl">
                Il Nostro Shop
            </h1>
            <p class="mt-4 text-gray-500 max-w-2xl mx-auto">
                Esplora le nostre collezioni e categorie per trovare i migliori prodotti.
            </p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar -->
        <aside class="w-full lg:w-1/5 flex-shrink-0">
            @include('public.shop.partials.sidebar')
        </aside>

        <!-- Main Content -->
        <div class="flex-grow">
            <h2 class="text-2xl font-bold text-gray-900 mb-8 flex items-center">
                <span class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </span>
                Le Nostre Collezioni
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @forelse($collezioni as $collezione)
                    <div class="group relative bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden transition-all hover:shadow-xl hover:-translate-y-1">
                        <div class="aspect-[16/9] overflow-hidden">
                            @if($collezione->foto)
                                <img src="{{ $collezione->foto }}" alt="{{ $collezione->nome }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            @else
                                <div class="w-full h-full bg-gray-50 flex items-center justify-center text-gray-300">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-60 group-hover:opacity-80 transition-opacity"></div>
                        </div>
                        
                        <div class="absolute bottom-0 left-0 right-0 p-6 text-white text-center">
                            <h3 class="text-2xl font-bold">{{ $collezione->nome }}</h3>
                            <a href="{{ route('public.shop.collezione', $collezione->slug) }}" class="bottone-personalizzato mt-4 inline-flex items-center text-sm font-bold bg-white text-gray-900 px-6 py-2 rounded-xl hover:bg-indigo-600 hover:text-white transition-all">
                                Esplora Collezione
                                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-gray-500">
                        Nessuna collezione disponibile.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
