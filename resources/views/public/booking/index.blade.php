@extends('public.layouts.main')

@section('content')
    <div class="bg-gray-50 pb-16 lg:mx-auto">
        @if(isset($section) && $section->immagine)
        <div class="relative bg-gray-50 h-64 flex items-end bg-cover bg-center bg-fixed px-6 lg:px-0" style="background-image: url('{{ asset($section->immagine) }}');">
            <div class="mx-auto max-w-7xl w-full bg-white rounded-t-lg px-6 lg:px-0">
                <nav class=" flex p-6 items-left text-sm font-medium text-gray-400 breadcrumb">
                    <a href="{{ route('public.home') }}" class="hover:text-gray-900">Home</a>
                    <svg class="h-5 w-5 shrink-0 text-gray-400 mx-1" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>
                    <span>{{ app()->getLocale() === 'en' ? 'Book' : 'Prenota' }}</span>
                </nav>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 lg:px-0">
            <div class="titoli text-center mb-16 bg-white py-12 border-t-0 shadow-sm rounded-b-lg border border-gray-100 px-4 md:px-4">
                <h1 class="text-4xl sm:text-5xl">{{ app()->getLocale() === 'en' ? 'Our Structures' : 'Le Nostre Strutture' }}</h1>
                <h2 class="mt-4 text-xl">{{ app()->getLocale() === 'en' ? 'Book your ideal stay in one of our fantastic locations.' : 'Prenota il tuo soggiorno ideale in una delle nostre fantastiche location.' }}</h2>
            </div>
        @else
        <div class="pt-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h1 class="text-4xl sm:text-5xl">{{ app()->getLocale() === 'en' ? 'Our Structures' : 'Le Nostre Strutture' }}</h1>
                <h2 class="mt-4 text-xl">{{ app()->getLocale() === 'en' ? 'Book your ideal stay in one of our fantastic locations.' : 'Prenota il tuo soggiorno ideale in una delle nostre fantastiche location.' }}</h2>
            </div>
        @endif
 
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($structures as $s)
                    @include('public.partials.booking.structure_card', ['structure' => $s])
                @empty
                    <div class="col-span-full text-center py-20 bg-white rounded-2xl border-2 border-dashed border-gray-200">
                        <p class="text-gray-500 text-lg">{{ app()->getLocale() === 'en' ? 'Currently, there are no structures available.' : 'Al momento non ci sono strutture disponibili.' }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
