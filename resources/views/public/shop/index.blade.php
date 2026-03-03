@extends('public.layouts.main')

@section('title', 'Shop - ' . config('app.name'))

@section('content')
<div class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                Le Nostre Collezioni
            </h1>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($collezioni as $collezione)
            <div class="bg-white rounded-lg shadow-sm border overflow-hidden flex flex-col transition hover:shadow-md">
                @if($collezione->foto)
                    <a href="{{ route('public.shop.collezione', $collezione->slug) }}" class="block p-4 border-b">
                        <img src="{{ $collezione->foto }}" alt="{{ $collezione->nome }}" class="w-full h-48 object-cover rounded">
                    </a>
                @else
                    <a href="{{ route('public.shop.collezione', $collezione->slug) }}" class="block p-4 border-b bg-gray-100 flex items-center justify-center h-56">
                        <span class="text-gray-400">Nessuna immagine</span>
                    </a>
                @endif
                <div class="p-6 flex-grow flex flex-col text-center items-center justify-center">
                    <a href="{{ route('public.shop.collezione', $collezione->slug) }}" class="block mt-2">
                        <h3 class="text-xl font-semibold text-gray-900">{{ $collezione->nome }}</h3>
                    </a>
                </div>
                <div class="pb-6 px-6 text-center">
                    <a href="{{ route('public.shop.collezione', $collezione->slug) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Esplora &rarr;</a>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center text-gray-500 py-12">
                Nessuna collezione disponibile al momento.
            </div>
        @endforelse
    </div>
</div>
@endsection
