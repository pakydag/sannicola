@extends('public.layouts.main')

@section('content')
    <div class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl">Le Nostre Strutture</h1>
                <p class="mt-4 text-xl text-gray-500">Prenota il tuo soggiorno ideale in una delle nostre fantastiche location.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($structures as $s)
                    @include('public.partials.booking.structure_card', ['structure' => $s])
                @empty
                    <div class="col-span-full text-center py-20 bg-white rounded-2xl border-2 border-dashed border-gray-200">
                        <p class="text-gray-500 text-lg">Al momento non ci sono strutture disponibili.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
