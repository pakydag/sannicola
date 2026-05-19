@extends('public.layouts.main')

@section('content')
@php
    $isEn = app()->getLocale() === 'en';
@endphp
<div class="py-16 bg-gray-50 flex-grow">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header conversazione -->
        <div class="bg-white rounded-t-xl shadow-md border-b p-6 md:p-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 mb-2">
                        Ticket #{{ str_pad($contactRequest->id, 5, '0', STR_PAD_LEFT) }}
                    </span>
                    <h1 class="text-2xl md:text-3xl font-serif text-gray-900 font-bold mb-1">
                        {{ $isEn ? 'Conversation Thread' : 'Conversazione Richiesta' }}
                    </h1>
                    <p class="text-gray-500 text-sm">
                        {{ $isEn ? 'Opened on' : 'Iniziata il' }} {{ $contactRequest->created_at->format('d/m/Y H:i') }} | 
                        <strong>{{ $contactRequest->nome }} {{ $contactRequest->cognome }}</strong> ({{ $contactRequest->email }})
                    </p>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="{{ url('/') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 flex items-center transition">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        {{ $isEn ? 'Back to Home' : 'Torna alla Home' }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Finestra Messaggi -->
        <div class="bg-white shadow-md p-6 md:p-8 space-y-6 max-h-[600px] overflow-y-auto border-b">
            
            <!-- Messaggio Iniziale del Cliente -->
            <div class="flex items-start space-x-3 max-w-[85%]">
                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-sm shadow">
                    {{ strtoupper(substr($contactRequest->nome, 0, 1)) }}
                </div>
                <div class="bg-indigo-50 border border-indigo-100/50 rounded-2xl rounded-tl-none p-4 text-gray-800 shadow-sm">
                    <div class="flex items-center justify-between mb-1">
                        <span class="font-bold text-xs text-indigo-950">{{ $contactRequest->nome }} {{ $contactRequest->cognome }}</span>
                        <span class="text-[10px] text-indigo-500 ml-4">{{ $contactRequest->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <p class="text-sm whitespace-pre-line">{{ $contactRequest->richiesta }}</p>
                </div>
            </div>

            <!-- Lista dei messaggi del Thread -->
            @foreach($contactRequest->messages as $msg)
                @if($msg->sender === 'admin')
                    <!-- Messaggio dell'Amministratore (Allineato a Destra) -->
                    <div class="flex items-start justify-end space-x-3 max-w-[85%] ml-auto">
                        <div class="bg-gray-100 border border-gray-200 rounded-2xl rounded-tr-none p-4 text-gray-800 shadow-sm">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-bold text-xs text-gray-900 mr-4">{{ $isEn ? 'Support Team' : 'Supporto Clienti' }}</span>
                                <span class="text-[10px] text-gray-500">{{ $msg->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <p class="text-sm whitespace-pre-line">{{ $msg->message }}</p>

                            @if($msg->attachment_path)
                                <div class="mt-3 pt-2 border-t border-gray-200 flex items-center">
                                    <svg class="w-4 h-4 text-gray-500 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <a href="{{ asset('storage/' . $msg->attachment_path) }}" target="_blank" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 hover:underline truncate max-w-[200px]">
                                        {{ $msg->attachment_name ?? 'Download allegato' }}
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gray-900 text-white flex items-center justify-center font-bold text-xs shadow">
                            AD
                        </div>
                    </div>
                @else
                    <!-- Messaggio del Cliente (Allineato a Sinistra) -->
                    <div class="flex items-start space-x-3 max-w-[85%]">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-sm shadow">
                            {{ strtoupper(substr($contactRequest->nome, 0, 1)) }}
                        </div>
                        <div class="bg-indigo-50 border border-indigo-100/50 rounded-2xl rounded-tl-none p-4 text-gray-800 shadow-sm">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-bold text-xs text-indigo-950">{{ $contactRequest->nome }} {{ $contactRequest->cognome }}</span>
                                <span class="text-[10px] text-indigo-500 ml-4">{{ $msg->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <p class="text-sm whitespace-pre-line">{{ $msg->message }}</p>

                            @if($msg->attachment_path)
                                <div class="mt-3 pt-2 border-t border-indigo-100 flex items-center">
                                    <svg class="w-4 h-4 text-indigo-500 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <a href="{{ asset('storage/' . $msg->attachment_path) }}" target="_blank" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 hover:underline truncate max-w-[200px]">
                                        {{ $msg->attachment_name ?? 'Download allegato' }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @endforeach

        </div>

        <!-- Form Risposta per il Cliente -->
        <div class="bg-white rounded-b-xl shadow-md p-6 md:p-8">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                {{ $isEn ? 'Write a Reply' : 'Scrivi una Risposta' }}
            </h3>

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 p-4 rounded-lg mb-4 text-sm font-semibold flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg mb-4 text-sm font-semibold">
                    <ul class="list-disc pl-4 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('public.contact.thread.reply', ['token' => $contactRequest->secure_token]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <textarea name="message" rows="4" required class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="{{ $isEn ? 'Type your reply here...' : 'Scrivi qui la tua risposta...' }}">{{ old('message') }}</textarea>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <!-- Allegato -->
                    <div class="flex items-center">
                        <label class="cursor-pointer inline-flex items-center text-xs font-semibold text-gray-600 hover:text-indigo-600 transition bg-gray-50 border border-gray-200 hover:border-indigo-100 rounded-lg px-3 py-2 shadow-sm">
                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                            <span id="file-label">{{ $isEn ? 'Attach File (Max 10MB)' : 'Aggiungi Allegato (Max 10MB)' }}</span>
                            <input type="file" name="attachment" id="attachment-input" class="hidden" onchange="updateFileLabel()">
                        </label>
                    </div>

                    <!-- Invia -->
                    <div>
                        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 bg-indigo-600 text-white font-semibold text-sm rounded-lg shadow hover:bg-indigo-700 transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            {{ $isEn ? 'Send Reply' : 'Invia Risposta' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

<script>
function updateFileLabel() {
    const input = document.getElementById('attachment-input');
    const label = document.getElementById('file-label');
    if (input.files && input.files.length > 0) {
        label.innerText = input.files[0].name;
        label.parentElement.classList.add('bg-indigo-50', 'border-indigo-200', 'text-indigo-600');
    } else {
        label.innerText = "{{ $isEn ? 'Attach File (Max 10MB)' : 'Aggiungi Allegato (Max 10MB)' }}";
        label.parentElement.classList.remove('bg-indigo-50', 'border-indigo-200', 'text-indigo-600');
    }
}
</script>
@endsection
