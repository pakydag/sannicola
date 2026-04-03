<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-3">
                <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Modifica Contatto: {{ $customer->full_name }}
            </h2>
            <a href="{{ route('admin.customers.show', $customer->id) }}" class="text-slate-400 hover:text-slate-600 font-bold flex items-center gap-2 transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Annulla e Torna
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-slate-100">
                <div class="p-8 md:p-10">
                    <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <!-- Nome -->
                            <div class="space-y-2">
                                <label for="first_name" class="block text-xs font-black text-slate-500 uppercase tracking-widest">Nome</label>
                                <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $customer->first_name) }}" 
                                       class="w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 font-bold text-slate-700" required>
                                @error('first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Cognome -->
                            <div class="space-y-2">
                                <label for="last_name" class="block text-xs font-black text-slate-500 uppercase tracking-widest">Cognome</label>
                                <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $customer->last_name) }}" 
                                       class="w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 font-bold text-slate-700" required>
                                @error('last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Azienda -->
                            <div class="md:col-span-2 space-y-2">
                                <label for="company_name" class="block text-xs font-black text-slate-500 uppercase tracking-widest">Nome Azienda</label>
                                <input type="text" name="company_name" id="company_name" value="{{ old('company_name', $customer->company_name) }}" 
                                       class="w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 font-bold text-slate-700">
                                @error('company_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Email -->
                            <div class="md:col-span-2 space-y-2">
                                <label for="email" class="block text-xs font-black text-slate-500 uppercase tracking-widest">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $customer->email) }}" 
                                       class="w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 font-bold text-slate-700">
                                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Telefono -->
                            <div class="space-y-2">
                                <label for="phone" class="block text-xs font-black text-slate-500 uppercase tracking-widest">Telefono Fisso</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $customer->phone) }}" 
                                       class="w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 font-bold text-slate-700">
                                @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Cellulare -->
                            <div class="space-y-2">
                                <label for="mobile" class="block text-xs font-black text-slate-500 uppercase tracking-widest">Cellulare</label>
                                <input type="text" name="mobile" id="mobile" value="{{ old('mobile', $customer->mobile) }}" 
                                       class="w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 font-bold text-slate-700">
                                @error('mobile') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-6 border-t border-slate-50">
                            <button type="submit" class="inline-flex items-center gap-2 px-8 py-4 bg-indigo-600 border border-transparent rounded-xl font-black text-sm text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 transition-all shadow-xl shadow-indigo-100 active:scale-95">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                Salva Modifiche
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
