@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="mb-8 flex items-center justify-between">
            <h2 class="text-3xl font-extrabold text-slate-900">Gestione Spedizioni Shop</h2>
            <a href="{{ route('admin.shop.configuration') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">&larr; Torna alla Configurazione</a>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-400 p-4 rounded-md shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-emerald-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Configurazione Soglia -->
            <div class="lg:col-span-1">
                <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100 h-full">
                    <div class="p-8">
                        <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Spedizione Gratuita
                        </h3>
                        <p class="text-sm text-slate-500 mb-8 leading-relaxed">
                            Imposta la soglia di spesa minima per offrire la spedizione gratuita ai tuoi clienti. Imposta a 0 per disabilitare la funzione.
                        </p>

                        <form action="{{ route('admin.shop.shipping_costs.threshold') }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PATCH')
                            <div>
                                <label for="shop_free_shipping_threshold" class="block text-sm font-semibold text-slate-700 mb-2">Soglia Ordine (€)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-slate-400 font-medium">€</span>
                                    </div>
                                    <input type="number" step="0.01" name="shop_free_shipping_threshold" id="shop_free_shipping_threshold" value="{{ $freeThreshold }}" class="bg-slate-50 border-slate-200 text-slate-900 text-lg rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 p-4 transition-all" placeholder="Es. 49.90">
                                </div>
                            </div>
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-6 rounded-xl shadow-lg shadow-indigo-200 transition-all flex items-center justify-center">
                                Salva Soglia
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Elenco Nazioni e Costi -->
            <div class="lg:col-span-2">
                <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100 mb-8">
                    <div class="p-8">
                        <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Costi per Nazione
                        </h3>
                        
                        <!-- Form Aggiunta -->
                        <form action="{{ route('admin.shop.shipping_costs.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            @csrf
                            <div class="col-span-1">
                                <label for="nazione" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nazione</label>
                                <input type="text" name="nazione" id="nazione" required class="w-full bg-white border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 p-3" placeholder="Es. Francia">
                            </div>
                            <div class="col-span-1">
                                <label for="costo" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Costo (€)</label>
                                <input type="number" step="0.01" name="costo" id="costo" required class="w-full bg-white border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 p-3" placeholder="Es. 9.90">
                            </div>
                            <div class="col-span-1 flex items-end">
                                <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-3 px-6 rounded-xl transition-all">
                                    Aggiungi
                                </button>
                            </div>
                        </form>

                        <!-- Tabella Elenco -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-100">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-extrabold text-slate-400 uppercase tracking-widest">Nazione</th>
                                        <th class="px-6 py-4 text-left text-xs font-extrabold text-slate-400 uppercase tracking-widest">Costo Spedizione</th>
                                        <th class="px-6 py-4 text-right text-xs font-extrabold text-slate-400 uppercase tracking-widest">Azioni</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @forelse($costs as $cost)
                                        <tr class="hover:bg-slate-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-700">
                                                {{ $cost->nazione }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                                € {{ number_format($cost->costo, 2, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                                <form action="{{ route('admin.shop.shipping_costs.destroy', $cost) }}" method="POST" class="inline" onsubmit="return confirm('Sei sicuro di voler eliminare questa nazione?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-rose-500 hover:text-rose-700 font-bold transition-colors">
                                                        Elimina
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-12 text-center text-slate-400 italic">
                                                Nessun costo configurato. Verrà applicato il costo predefinito.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
