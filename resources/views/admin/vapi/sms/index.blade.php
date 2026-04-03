<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-3">
            <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>
            SMS Ricevuti (Vapi)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-slate-100">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-slate-50">
                                    <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Mittente</th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Messaggio</th>
                                    <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Ricevuto il</th>
                                    <th class="px-6 py-4 text-right text-xs font-black text-slate-500 uppercase tracking-widest">Azioni</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100">
                                @forelse ($messages as $sms)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                </div>
                                                <div>
                                                    @if($sms->contact)
                                                        <a href="{{ route('admin.customers.show', $sms->contact->id) }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-900 transition-colors">
                                                            {{ $sms->contact->full_name }}
                                                        </a>
                                                        <div class="text-xs text-slate-400 font-medium">{{ $sms->phone_number }}</div>
                                                    @else
                                                        <span class="text-sm font-bold text-slate-700">{{ $sms->phone_number }}</span>
                                                        <div class="text-xs text-slate-400 font-medium italic">Mittente non in CRM</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-slate-600 font-medium max-w-lg leading-relaxed">
                                                {{ $sms->content }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 font-bold">
                                            {{ $sms->received_at ? $sms->received_at->format('d/m/Y H:i') : $sms->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                            <form action="{{ route('admin.vapi.sms.destroy', $sms->id) }}" method="POST" onsubmit="return confirm('Sei sicuro di voler eliminare questo messaggio?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-600 transition-colors p-2 rounded-lg hover:bg-red-50">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-10 text-center">
                                            <div class="flex flex-col items-center gap-2">
                                                <svg class="w-12 h-12 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-3.586a1 1 0 00-.707.293l-1.414 1.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-1.414-1.414A1 1 0 006.586 13H4"/></svg>
                                                <span class="text-slate-400 font-bold uppercase tracking-widest text-xs">Nessun SMS ricevuto al momento</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">
                        {{ $messages->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
