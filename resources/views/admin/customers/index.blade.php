<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('CRM') }}
            </h2>
            <div class="flex gap-4">
                <a href="{{ route('admin.customers.export') }}" class="inline-flex items-center px-4 py-2 bg-slate-100 border border-slate-300 rounded-md font-semibold text-xs text-slate-700 uppercase tracking-widest hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Esporta CSV
                </a>
                <a href="{{ route('admin.customers.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Nuovo Contatto
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <form action="{{ route('admin.customers.index') }}" method="GET" class="flex gap-4">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cerca per nome, email o telefono..." class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Cerca</button>
                            @if(request('search'))
                                <a href="{{ route('admin.customers.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300">Resetta</a>
                            @endif
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b text-left">Nome / Ragione Sociale</th>
                                    <th class="py-2 px-4 border-b text-left">Email</th>
                                    <th class="py-2 px-4 border-b text-left">Tags</th>
                                    <th class="py-2 px-4 border-b text-center">Attività</th>
                                    <th class="py-2 px-4 border-b text-right">Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $customer)
                                    <tr class="hover:bg-gray-50 text-sm">
                                        <td class="py-3 px-4 border-b">
                                            <div class="font-bold text-slate-800">
                                                {{ $customer->first_name }} {{ $customer->last_name }}
                                            </div>
                                            @if($customer->company_name)
                                                <div class="text-xs text-gray-400 italic">{{ $customer->company_name }}</div>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 border-b text-gray-600">{{ $customer->email }}</td>
                                        <td class="py-3 px-4 border-b">
                                            <div class="flex flex-wrap gap-1">
                                                @if($customer->is_shop_customer)
                                                    <span class="bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded text-[10px] font-bold uppercase">Shop</span>
                                                @endif
                                                @if($customer->is_booking_customer)
                                                    <span class="bg-sky-100 text-sky-700 px-2 py-0.5 rounded text-[10px] font-bold uppercase">Booking</span>
                                                @endif
                                                @if($customer->is_b2b_customer)
                                                    <span class="bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded text-[10px] font-bold uppercase">B2B</span>
                                                @endif
                                                @if($customer->is_lead)
                                                    <span class="bg-amber-100 text-amber-700 px-2 py-0.5 rounded text-[10px] font-bold uppercase">Lead</span>
                                                @endif
                                                @if($customer->is_vapi_lead)
                                                    <span class="bg-purple-100 text-purple-700 px-2 py-0.5 rounded text-[10px] font-bold uppercase">Agente AI</span>
                                                @endif
                                                @if($customer->tags)
                                                    @foreach($customer->tags as $tag)
                                                        @if(!in_array($tag, ['Shop', 'Booking', 'B2B', 'Lead', 'Agente AI']))
                                                            <span class="bg-gray-100 text-gray-700 px-2 py-0.5 rounded text-[10px] font-bold uppercase">{{ $tag }}</span>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 border-b text-center">
                                            <div class="flex justify-center gap-3">
                                                <span title="Ordini Shop" class="text-xs font-semibold text-indigo-500 flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                                    {{ $customer->shopOrders->count() }}
                                                </span>
                                                <span title="Prenotazioni" class="text-xs font-semibold text-sky-500 flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2-0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                    {{ $customer->bookings->count() }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="py-2 px-4 border-b text-right">
                                            <div class="flex justify-end items-center gap-2">
                                                <a href="{{ route('admin.customers.show', $customer) }}" class="inline-flex items-center px-3 py-1 bg-white border border-indigo-600 rounded-md font-semibold text-xs text-indigo-600 uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition-all">Dettaglio</a>
                                                
                                                <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" class="inline-block" onsubmit="return confirm('Sei sicuro di voler eliminare definitivamente questo contatto? Questa azione cancellerà anche la sua storia ordini e prenotazioni.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 ml-2">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2-0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="py-4 px-4 text-center text-gray-500">Nessun contatto trovato nel CRM.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $customers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
