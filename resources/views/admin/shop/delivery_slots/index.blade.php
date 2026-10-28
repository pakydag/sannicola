<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestione Orari di Consegna Shop</h2>
    </x-slot>

    <div class="py-12" x-data="{ editingId: null }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-extrabold text-slate-900">Configurazione Consegne a Mano</h3>
                    <p class="text-sm text-slate-500 mt-1">Imposta i giorni attivi divisi per Mattina e Pomeriggio, e definisci le fasce orarie con orario limite e costo.</p>
                </div>
                <a href="{{ route('admin.shop.configuration') }}" class="text-sm font-medium text-primary hover:text-gray-700">&larr; Torna alla Configurazione</a>
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

            @if($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-md shadow-sm">
                    <ul class="list-disc pl-5 text-sm text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- 1. Configurazione Giorni di Consegna (Mattina e Pomeriggio) -->
                <div class="lg:col-span-5">
                    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100 h-full p-8 flex flex-col justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-slate-800 mb-2 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Giorni di Consegna
                            </h3>
                            <p class="text-sm text-slate-500 mb-6 leading-relaxed">
                                Seleziona per ciascun giorno della settimana se le consegne a mano sono attive di <strong>Mattina</strong> e/o di <strong>Pomeriggio</strong>.
                            </p>

                            <form action="{{ route('admin.shop.delivery_slots.update_days') }}" method="POST" class="space-y-6">
                                @csrf
                                
                                @php
                                    $daysMap = [
                                        '1' => 'Lunedì',
                                        '2' => 'Martedì',
                                        '3' => 'Mercoledì',
                                        '4' => 'Giovedì',
                                        '5' => 'Venerdì',
                                        '6' => 'Sabato',
                                        '7' => 'Domenica',
                                    ];
                                @endphp

                                <div class="space-y-2.5 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                    <div class="flex items-center justify-between pb-1 px-1 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                        <span>Giorno</span>
                                        <div class="flex items-center gap-7">
                                            <span>Mattina</span>
                                            <span>Pomeriggio</span>
                                        </div>
                                    </div>

                                    @foreach($daysMap as $num => $label)
                                        <div class="flex items-center justify-between p-3 rounded-xl bg-white border border-slate-100 hover:border-slate-200 transition-all shadow-xs">
                                            <span class="text-sm font-bold text-slate-800">{{ $label }}</span>
                                            
                                            <div class="flex items-center gap-8">
                                                <!-- Mattina -->
                                                <label class="flex items-center gap-1.5 cursor-pointer" title="Consegne mattina attive il {{ $label }}">
                                                    <input type="checkbox" 
                                                           name="delivery_days[{{ $num }}][mattina]" 
                                                           value="1" 
                                                           {{ !empty($deliveryDays[$num]['mattina']) ? 'checked' : '' }} 
                                                           class="rounded border-gray-300 text-amber-500 shadow-sm focus:border-amber-400 focus:ring-amber-400 h-4 w-4">
                                                    <span class="text-xs font-semibold text-slate-600">☀️</span>
                                                </label>
                                                
                                                <!-- Pomeriggio -->
                                                <label class="flex items-center gap-1.5 cursor-pointer" title="Consegne pomeriggio attive il {{ $label }}">
                                                    <input type="checkbox" 
                                                           name="delivery_days[{{ $num }}][pomeriggio]" 
                                                           value="1" 
                                                           {{ !empty($deliveryDays[$num]['pomeriggio']) ? 'checked' : '' }} 
                                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-4 w-4">
                                                    <span class="text-xs font-semibold text-slate-600">🌙</span>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="pt-2">
                                    <label for="max_days" class="block text-sm font-semibold text-slate-700 mb-1">Finestra Prenotazioni (Giorni di anticipo)</label>
                                    <p class="text-xs text-slate-500 mb-2">Numero di giorni futuri mostrati al cliente nel checkout.</p>
                                    <input type="number" min="1" max="60" name="max_days" id="max_days" value="{{ $maxDays }}" required class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-primary focus:border-primary p-3 text-sm">
                                </div>

                                <button type="submit" class="w-full bg-primary hover:bg-secondary text-white font-bold py-3.5 px-6 rounded-xl shadow-md transition-all flex items-center justify-center">
                                    Salva Giorni di Consegna
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- 2. Configurazione Fasce Orarie, Orari Limite e Costo -->
                <div class="lg:col-span-7">
                    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100 p-8">
                        <h3 class="text-xl font-bold text-slate-800 mb-2 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Fasce Orarie e Costi di Consegna
                        </h3>
                        <p class="text-sm text-slate-500 mb-6 leading-relaxed">
                            Crea le fasce orarie specificando se appartengono alla <strong>Mattina</strong> o al <strong>Pomeriggio</strong>, l'<strong>Orario Limite</strong> (cutoff per lo stesso giorno) e il <strong>Costo Consegna</strong> che verrà aggiunto al carrello.
                        </p>

                        <!-- Form Aggiunta Fascia -->
                        <form action="{{ route('admin.shop.delivery_slots.store_slot') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-12 gap-3.5 mb-8 p-5 bg-slate-50 rounded-2xl border border-slate-100">
                            @csrf
                            
                            <div class="sm:col-span-3">
                                <label for="orario_consegna" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Orario Consegna *</label>
                                <input type="time" name="orario_consegna" id="orario_consegna" required class="w-full bg-white border-slate-200 rounded-xl focus:ring-primary focus:border-primary p-2.5 text-sm">
                                <span class="text-[10px] text-slate-400">Es. 12:00</span>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="periodo" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Periodo *</label>
                                <select name="periodo" id="periodo" required class="w-full bg-white border-slate-200 rounded-xl focus:ring-primary focus:border-primary p-2.5 text-sm font-medium text-slate-800">
                                    <option value="mattina">☀️ Mattina</option>
                                    <option value="pomeriggio">🌙 Pomeriggio</option>
                                </select>
                                <span class="text-[10px] text-slate-400">Fascia del giorno</span>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="orario_limite" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Limite Ordini *</label>
                                <input type="time" name="orario_limite" id="orario_limite" required class="w-full bg-white border-slate-200 rounded-xl focus:ring-primary focus:border-primary p-2.5 text-sm">
                                <span class="text-[10px] text-slate-400">Es. 11:00 (cutoff)</span>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="costo" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Costo (€) *</label>
                                <input type="number" step="0.50" min="0" name="costo" id="costo" value="0.00" required class="w-full bg-white border-slate-200 rounded-xl focus:ring-primary focus:border-primary p-2.5 text-sm">
                                <span class="text-[10px] text-slate-400">0.00 = Gratuito</span>
                            </div>

                            <div class="sm:col-span-12 flex justify-end pt-2">
                                <button type="submit" class="bg-primary hover:bg-secondary text-white font-bold py-2.5 px-6 rounded-xl transition-all text-sm shadow-sm flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Aggiungi Fascia Oraria
                                </button>
                            </div>
                        </form>

                        <!-- Tabella Elenco Fasce con Modifica Inline -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-100">
                                <thead>
                                    <tr>
                                        <th class="px-3 py-3 text-left text-xs font-extrabold text-slate-400 uppercase tracking-widest">Orario</th>
                                        <th class="px-3 py-3 text-left text-xs font-extrabold text-slate-400 uppercase tracking-widest">Periodo</th>
                                        <th class="px-3 py-3 text-left text-xs font-extrabold text-slate-400 uppercase tracking-widest">Limite Ordini</th>
                                        <th class="px-3 py-3 text-left text-xs font-extrabold text-slate-400 uppercase tracking-widest">Costo</th>
                                        <th class="px-3 py-3 text-center text-xs font-extrabold text-slate-400 uppercase tracking-widest">Stato</th>
                                        <th class="px-3 py-3 text-right text-xs font-extrabold text-slate-400 uppercase tracking-widest">Azioni</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @forelse($slots as $slot)
                                        <!-- Riga Normale Visualizzazione -->
                                        <tr x-show="editingId !== {{ $slot->id }}" class="hover:bg-slate-50 transition-colors">
                                            <!-- Orario -->
                                            <td class="px-3 py-4 whitespace-nowrap text-sm font-bold text-slate-900">
                                                <div class="flex items-center gap-1.5">
                                                    <svg class="w-4 h-4 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    <span>{{ \Carbon\Carbon::parse($slot->orario_consegna)->format('H:i') }}</span>
                                                </div>
                                            </td>

                                            <!-- Periodo -->
                                            <td class="px-3 py-4 whitespace-nowrap text-sm">
                                                @if($slot->periodo === 'pomeriggio')
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                                        🌙 Pomeriggio
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                                        ☀️ Mattina
                                                    </span>
                                                @endif
                                            </td>

                                            <!-- Limite -->
                                            <td class="px-3 py-4 whitespace-nowrap text-sm text-slate-600">
                                                Entro le <strong class="text-slate-800">{{ \Carbon\Carbon::parse($slot->orario_limite)->format('H:i') }}</strong>
                                            </td>

                                            <!-- Costo -->
                                            <td class="px-3 py-4 whitespace-nowrap text-sm">
                                                @if($slot->costo > 0)
                                                    <span class="font-bold text-slate-900">+ € {{ number_format($slot->costo, 2, ',', '.') }}</span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                        Gratuito
                                                    </span>
                                                @endif
                                            </td>

                                            <!-- Stato -->
                                            <td class="px-3 py-4 whitespace-nowrap text-center text-sm">
                                                <form action="{{ route('admin.shop.delivery_slots.toggle_slot', $slot->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" title="Clicca per {{ $slot->attivo ? 'disattivare' : 'attivare' }}" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $slot->attivo ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-rose-100 text-rose-700 hover:bg-rose-200' }} transition-colors shadow-xs">
                                                        <span class="h-1.5 w-1.5 rounded-full {{ $slot->attivo ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                                        {{ $slot->attivo ? 'Attiva' : 'Disattivata' }}
                                                    </button>
                                                </form>
                                            </td>

                                            <!-- Azioni -->
                                            <td class="px-3 py-4 whitespace-nowrap text-right text-sm">
                                                <button type="button" 
                                                        @click="editingId = {{ $slot->id }}"
                                                        class="text-indigo-600 hover:text-indigo-900 font-semibold text-xs mr-3">
                                                    Modifica
                                                </button>

                                                <form action="{{ route('admin.shop.delivery_slots.destroy_slot', $slot->id) }}" method="POST" onsubmit="return confirm('Sei sicuro di voler eliminare questa fascia oraria?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-rose-600 hover:text-rose-900 font-semibold text-xs">
                                                        Elimina
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>

                                        <!-- Riga Modifica Inline Espandibile -->
                                        <tr x-show="editingId === {{ $slot->id }}" x-cloak class="bg-indigo-50/70">
                                            <td colspan="6" class="p-4 border-2 border-indigo-200 rounded-xl bg-white shadow-xs">
                                                <form action="{{ route('admin.shop.delivery_slots.update_slot', $slot->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    
                                                    <div class="flex items-center justify-between mb-3 border-b pb-2">
                                                        <h5 class="text-xs font-bold uppercase tracking-wider text-indigo-900 flex items-center gap-1.5">
                                                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                            Modifica Fascia Oraria (Ore {{ \Carbon\Carbon::parse($slot->orario_consegna)->format('H:i') }})
                                                        </h5>
                                                        <button type="button" @click="editingId = null" class="text-xs text-slate-500 hover:text-slate-800 font-semibold">
                                                            Chiudi ✕
                                                        </button>
                                                    </div>

                                                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
                                                        <div class="sm:col-span-3">
                                                            <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Orario Consegna *</label>
                                                            <input type="time" name="orario_consegna" value="{{ \Carbon\Carbon::parse($slot->orario_consegna)->format('H:i') }}" required class="w-full bg-slate-50 border-slate-200 rounded-xl p-2.5 text-sm font-semibold">
                                                        </div>

                                                        <div class="sm:col-span-3">
                                                            <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Periodo *</label>
                                                            <select name="periodo" required class="w-full bg-slate-50 border-slate-200 rounded-xl p-2.5 text-sm font-semibold text-slate-800">
                                                                <option value="mattina" {{ $slot->periodo === 'mattina' ? 'selected' : '' }}>☀️ Mattina</option>
                                                                <option value="pomeriggio" {{ $slot->periodo === 'pomeriggio' ? 'selected' : '' }}>🌙 Pomeriggio</option>
                                                            </select>
                                                        </div>

                                                        <div class="sm:col-span-3">
                                                            <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Limite Ordini *</label>
                                                            <input type="time" name="orario_limite" value="{{ \Carbon\Carbon::parse($slot->orario_limite)->format('H:i') }}" required class="w-full bg-slate-50 border-slate-200 rounded-xl p-2.5 text-sm font-semibold">
                                                        </div>

                                                        <div class="sm:col-span-3">
                                                            <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Costo (€) *</label>
                                                            <input type="number" step="0.50" min="0" name="costo" value="{{ number_format($slot->costo, 2, '.', '') }}" required class="w-full bg-slate-50 border-slate-200 rounded-xl p-2.5 text-sm font-semibold">
                                                        </div>

                                                        <div class="sm:col-span-12 flex items-center justify-between pt-3 border-t mt-2">
                                                            <label class="flex items-center gap-2 cursor-pointer select-none">
                                                                <input type="hidden" name="has_attivo_field" value="1">
                                                                <input type="checkbox" name="attivo" value="1" {{ $slot->attivo ? 'checked' : '' }} class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500 h-4 w-4">
                                                                <span class="text-xs font-bold text-slate-700">Fascia Attiva per i Clienti</span>
                                                            </label>

                                                            <div class="flex items-center gap-2">
                                                                <button type="button" @click="editingId = null" class="px-4 py-2 text-xs text-slate-600 hover:text-slate-800 font-semibold border rounded-xl bg-slate-100 hover:bg-slate-200">
                                                                    Annulla
                                                                </button>
                                                                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-5 rounded-xl text-xs transition-all shadow-sm">
                                                                    Salva Modifiche
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-4 py-8 text-center text-slate-400 text-sm">
                                                Nessuna fascia oraria inserita. Aggiungi ad esempio le ore <strong>12:00</strong> (Mattina, limite 11:00) e le ore <strong>20:00</strong> (Pomeriggio, limite 19:00).
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
</x-app-layout>
