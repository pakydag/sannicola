<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-3">
                <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Contatto CRM: {{ $customer->first_name }} {{ $customer->last_name }}
            </h2>
            <a href="{{ route('admin.customers.index') }}" class="text-indigo-600 hover:text-indigo-900 font-bold flex items-center gap-2 transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Torna al CRM
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 p-4 shadow-sm rounded-r-lg" role="alert">
                    <p class="font-bold">Successo</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <!-- Top Profile Card -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl mb-8 border border-slate-100">
                <div class="p-8 md:p-10">
                    <div class="flex flex-col md:flex-row justify-between items-start gap-8">
                        <div class="flex-1 flex items-start gap-6">
                            <div class="hidden sm:flex h-20 w-20 rounded-2xl bg-indigo-600 items-center justify-center text-white text-3xl font-black shadow-lg shadow-indigo-200">
                                {{ substr($customer->first_name, 0, 1) }}{{ substr($customer->last_name, 0, 1) }}
                            </div>
                            <div>
                                <h1 class="text-4xl font-black text-slate-900 tracking-tight mb-1">
                                    {{ $customer->first_name }} {{ $customer->last_name }}
                                </h1>
                                @if($customer->company_name)
                                    <div class="text-xl text-indigo-600 font-bold mb-4 flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        {{ $customer->company_name }}
                                    </div>
                                @endif

                                <div class="flex flex-wrap gap-2 mt-4">
                                    <!-- System Tags -->
                                    @if($customer->is_shop_customer)
                                        <span class="bg-indigo-600 text-white px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider shadow-sm">Shop Customer</span>
                                    @endif
                                    @if($customer->is_booking_customer)
                                        <span class="bg-sky-500 text-white px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider shadow-sm">Booking</span>
                                    @endif
                                    @if($customer->is_b2b_customer)
                                        <span class="bg-emerald-600 text-white px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider shadow-sm">B2B</span>
                                    @endif
                                    @if($customer->is_lead)
                                        <span class="bg-amber-500 text-white px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider shadow-sm">Lead</span>
                                    @endif
                                    
                                    <!-- Custom Tags -->
                                    @if($customer->tags)
                                        @foreach($customer->tags as $tag)
                                            @if(!in_array($tag, ['Shop', 'Booking', 'B2B', 'Lead']))
                                                <span class="bg-slate-100 text-slate-600 border border-slate-200 px-3 py-1 rounded-full text-xs font-bold uppercase">{{ $tag }}</span>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="w-full md:w-80 bg-slate-50 p-6 rounded-2xl border border-slate-200">
                            <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 11h.01M7 15h.01M13 7h.01M13 11h.01M13 15h.01M17 7h.01M17 11h.01M17 15h.01"/></svg>
                                Gestione Tag Personali
                            </h3>
                            
                            <!-- Existing Custom Tags -->
                            <div class="flex flex-wrap gap-2 mb-6">
                                @php
                                    $customTags = array_diff($customer->tags ?? [], ['Shop', 'Booking', 'B2B', 'Lead']);
                                @endphp
                                
                                @forelse($customTags as $tag)
                                    <div class="inline-flex items-center bg-white border border-slate-200 rounded-full pl-3 pr-1 py-1 shadow-sm group">
                                        <span class="text-[11px] font-bold text-slate-700 uppercase">{{ $tag }}</span>
                                        <form action="{{ route('admin.customers.remove-tag', $customer->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="tag" value="{{ $tag }}">
                                            <button type="submit" class="ml-1 p-1 text-slate-300 hover:text-red-500 hover:bg-red-50 rounded-full transition-all">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                @empty
                                    <p class="text-[11px] text-slate-400 italic">Nessun tag personalizzato.</p>
                                @endforelse
                            </div>

                            <!-- Add Tag Form -->
                            <form action="{{ route('admin.customers.add-tag', $customer->id) }}" method="POST">
                                @csrf
                                <div class="flex gap-2">
                                    <input type="text" name="tag" 
                                           placeholder="Nuovo tag..." 
                                           class="flex-1 rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 text-xs py-2 px-3">
                                    <button type="submit" class="bg-indigo-600 text-white p-2 rounded-xl hover:bg-indigo-700 transition-all shadow-md shadow-indigo-100 active:scale-95">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                                    </button>
                                </div>
                                <p class="text-[10px] text-slate-400 mt-3 font-medium leading-tight">
                                    Aggiungi tag uno alla volta. I tag di sistema sono protetti.
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Info Sidebar -->
                <div class="md:col-span-1 space-y-8">
                    <!-- Contact Info -->
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl border border-slate-100">
                        <div class="p-8">
                            <h3 class="font-black text-xs text-slate-400 uppercase tracking-widest border-b border-slate-50 pb-4 mb-6">Informazioni Recapito</h3>
                            <div class="space-y-6">
                                <div class="flex items-center gap-4">
                                    <div class="h-10 w-10 bg-indigo-50 rounded-lg flex items-center justify-center text-indigo-600 flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-tighter">Nome e Cognome</label>
                                        <p class="text-slate-900 font-black">{{ $customer->first_name }} {{ $customer->last_name }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4">
                                    <div class="h-10 w-10 bg-indigo-50 rounded-lg flex items-center justify-center text-indigo-600 flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2-0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-tighter">Email Principale</label>
                                        <p class="text-slate-900 font-black truncate">{{ $customer->email }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-4">
                                    <div class="h-10 w-10 bg-sky-50 rounded-lg flex items-center justify-center text-sky-600 flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-tighter">Telefono / Mobile</label>
                                        <p class="text-slate-900 font-black">{{ $customer->phone ?? $customer->mobile ?? 'N/D' }}</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-4 pt-4 border-t border-slate-50">
                                    <div class="h-10 w-10 bg-slate-50 rounded-lg flex items-center justify-center text-slate-600 flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-tighter transition-all">Sede e Indirizzo</label>
                                        <p class="text-slate-800 font-bold leading-relaxed">
                                            {{ $customer->address }}<br>
                                            <span class="text-slate-500 uppercase">{{ $customer->zip_code }} {{ $customer->city }} ({{ $customer->province }})</span><br>
                                            <span class="text-slate-400">{{ $customer->country }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tax Info -->
                    @if($customer->vat_number || $customer->tax_id)
                    <div class="bg-indigo-900 overflow-hidden shadow-lg sm:rounded-2xl text-white">
                        <div class="p-8">
                            <h3 class="font-black text-xs text-indigo-400 uppercase tracking-widest mb-6">Dati Fiscali</h3>
                            <div class="space-y-4">
                                @if($customer->vat_number)
                                <div>
                                    <label class="block text-[10px] font-black text-indigo-300 uppercase">Partita IVA</label>
                                    <p class="font-black text-lg">{{ $customer->vat_number }}</p>
                                </div>
                                @endif
                                @if($customer->tax_id)
                                <div>
                                    <label class="block text-[10px] font-black text-indigo-300 uppercase">Codice Fiscale</label>
                                    <p class="font-bold font-mono">{{ $customer->tax_id }}</p>
                                </div>
                                @endif
                                @if($customer->sdi_code)
                                <div class="pt-2">
                                    <label class="block text-[10px] font-black text-indigo-300 uppercase">Codice SDI</label>
                                    <p class="bg-white/10 px-2 py-1 rounded inline-block font-mono text-sm uppercase">{{ $customer->sdi_code }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Main Activity Column -->
                <div class="md:col-span-2 space-y-8">
                    <!-- Prenotazioni -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-slate-100">
                        <div class="p-8">
                            <header class="flex justify-between items-center mb-6 border-b border-slate-50 pb-4">
                                <h3 class="text-xl font-black text-slate-900 flex items-center gap-3">
                                    <div class="p-2 bg-sky-50 rounded-lg text-sky-600">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2-0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    Prenotazioni
                                </h3>
                                <span class="bg-sky-100 text-sky-700 font-black px-3 py-1 rounded-full text-[10px] uppercase">{{ $customer->bookings->count() }} Record</span>
                            </header>
                            
                            @forelse($customer->bookings as $b)
                                <div class="group relative flex flex-wrap items-center justify-between p-4 mb-4 bg-slate-50 hover:bg-sky-50 rounded-xl border border-transparent hover:border-sky-100 transition-all">
                                    <div class="flex-1">
                                        <div class="text-xs font-black text-slate-400 mb-1">#{{ $b->id }} • {{ \Carbon\Carbon::parse($b->start_date)->format('d/m/Y') }}</div>
                                        <div class="font-black text-slate-900">{{ $b->structure->nome }}</div>
                                        <div class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($b->start_date)->format('d/m/y') }} al {{ \Carbon\Carbon::parse($b->end_date)->format('d/m/y') }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-lg font-black text-slate-900 group-hover:text-sky-600 transition-colors tracking-tighter">€{{ number_format($b->totale_prezzo, 2, ',', '.') }}</div>
                                        <span class="inline-block px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest {{ $b->stato == 'confermato' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                            {{ $b->stato }}
                                        </span>
                                    </div>
                                    <a href="{{ route('admin.booking.bookings.show', $b) }}" class="absolute inset-0 z-10 opacity-0 cursor-pointer">Vedi</a>
                                </div>
                            @empty
                                <div class="py-12 text-center text-slate-300">
                                    <svg class="w-12 h-12 mx-auto mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2-0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <p class="text-sm font-bold uppercase tracking-widest">Nessuna prenotazione attiva</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Shop Orders -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-slate-100">
                        <div class="p-8">
                            <header class="flex justify-between items-center mb-6 border-b border-slate-50 pb-4">
                                <h3 class="text-xl font-black text-slate-900 flex items-center gap-3">
                                    <div class="p-2 bg-indigo-50 rounded-lg text-indigo-600">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    </div>
                                    Ordini Shop (B2C)
                                </h3>
                                <span class="bg-indigo-100 text-indigo-700 font-black px-3 py-1 rounded-full text-[10px] uppercase">{{ $customer->shopOrders->count() }} Record</span>
                            </header>
                            
                            @forelse($customer->shopOrders as $o)
                                <div class="group relative flex flex-wrap items-center justify-between p-4 mb-4 bg-slate-50 hover:bg-indigo-50 rounded-xl border border-transparent hover:border-indigo-100 transition-all">
                                    <div class="flex-1">
                                        <div class="text-xs font-black text-slate-400 mb-1">Ordine #{{ $o->numero_ordine }}</div>
                                        <div class="font-black text-slate-900">{{ $o->created_at->format('d F Y') }}</div>
                                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $o->metodo_pagamento }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-lg font-black text-slate-900 group-hover:text-indigo-600 transition-colors tracking-tighter">€{{ number_format($o->totale_ordine, 2, ',', '.') }}</div>
                                        <span class="inline-block px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest bg-slate-200 text-slate-600">
                                            {{ $o->stato }}
                                        </span>
                                    </div>
                                    <a href="{{ route('admin.shop.ordini.index', ['search' => $o->numero_ordine]) }}" class="absolute inset-0 z-10 opacity-0 cursor-pointer">Vedi</a>
                                </div>
                            @empty
                                <div class="py-12 text-center text-slate-300">
                                    <svg class="w-12 h-12 mx-auto mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    <p class="text-sm font-bold uppercase tracking-widest">Nessun ordine shop trovato</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- B2B Activity -->
                    @if($customer->is_b2b_customer)
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-slate-100">
                        <div class="p-8">
                            <header class="flex justify-between items-center mb-6 border-b border-slate-50 pb-4">
                                <h3 class="text-xl font-black text-slate-900 flex items-center gap-3">
                                    <div class="p-2 bg-emerald-50 rounded-lg text-emerald-600">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    </div>
                                    Ordini B2B
                                </h3>
                                <span class="bg-emerald-100 text-emerald-700 font-black px-3 py-1 rounded-full text-[10px] uppercase">{{ $customer->b2bOrders->count() }} Record</span>
                            </header>
                            
                            @forelse($customer->b2bOrders as $bo)
                                <div class="group relative flex flex-wrap items-center justify-between p-4 mb-4 bg-slate-50 hover:bg-emerald-50 rounded-xl border border-transparent hover:border-emerald-100 transition-all">
                                    <div class="flex-1">
                                        <div class="text-xs font-black text-slate-400 mb-1">Ref ID #{{ $bo->id }}</div>
                                        <div class="font-black text-slate-900">{{ $bo->created_at->format('d/m/Y') }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-lg font-black text-slate-900 group-hover:text-emerald-600 transition-colors tracking-tighter">€{{ number_format($bo->total_amount, 2, ',', '.') }}</div>
                                        <span class="inline-block px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest bg-slate-200 text-slate-600">
                                            {{ $bo->status }}
                                        </span>
                                    </div>
                                    <a href="{{ route('admin.b2b.orders.show', $bo) }}" class="absolute inset-0 z-10 opacity-0 cursor-pointer">Vedi</a>
                                </div>
                            @empty
                                <div class="py-12 text-center text-slate-300">
                                    <p class="text-sm font-bold uppercase tracking-widest">Nessun ordine B2B</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    @endif

                    <!-- AI Voice Assistant Tickets -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-slate-100">
                        <div class="p-8">
                            <header class="flex justify-between items-center mb-6 border-b border-slate-50 pb-4">
                                <h3 class="text-xl font-black text-slate-900 flex items-center gap-3">
                                    <div class="p-2 bg-rose-50 rounded-lg text-rose-600">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                                    </div>
                                    Ticket Voce AI (Vapi)
                                </h3>
                                <span class="bg-rose-100 text-rose-700 font-black px-3 py-1 rounded-full text-[10px] uppercase">{{ $customer->aiTickets->count() }} Record</span>
                            </header>
                            
                            @forelse($customer->aiTickets as $at)
                                <div class="group relative p-4 mb-4 bg-slate-50 hover:bg-rose-50 rounded-xl border border-transparent hover:border-rose-100 transition-all">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="text-xs font-black text-slate-400">#{{ $at->id }} • {{ $at->created_at->format('d/m/Y H:i') }}</div>
                                        <span class="inline-block px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest {{ $at->status == 'open' ? 'bg-amber-100 text-amber-700' : 'bg-slate-200 text-slate-600' }}">
                                            {{ $at->status }}
                                        </span>
                                    </div>
                                    <div class="font-black text-slate-900 mb-1">{{ $at->assistance_type }}</div>
                                    <div class="text-sm text-slate-600 italic leading-relaxed">"{{ $at->description }}"</div>
                                </div>
                            @empty
                                <div class="py-12 text-center text-slate-300">
                                    <p class="text-sm font-bold uppercase tracking-widest">Nessuna chiamata AI registrata</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
