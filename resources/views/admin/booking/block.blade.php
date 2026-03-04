<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Blocca Date / Prenotazione Manuale') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.booking.bookings.store_block') }}" method="POST" class="max-w-4xl">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <h3 class="text-lg font-bold mb-4 border-b pb-2 text-indigo-600">Dettagli Soggiorno</h3>
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Seleziona Struttura</label>
                                    <select name="booking_structure_id" class="shadow border rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                                        <option value="">-- Seleziona --</option>
                                        @foreach($structures as $s)
                                            <option value="{{ $s->id }}" {{ $selected_id == $s->id ? 'selected' : '' }}>{{ $s->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Data Inizio</label>
                                        <input type="date" name="start_date" class="shadow border rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500" required min="{{ date('Y-m-d') }}">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Data Fine</label>
                                        <input type="date" name="end_date" class="shadow border rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500" required min="{{ date('Y-m-d') }}">
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Stato Pagamento</label>
                                    <select name="stato_pagamento" id="stato_pagamento" class="shadow border rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                                        <option value="attesa">In attesa di pagamento</option>
                                        <option value="paga_in_struttura">Paga in struttura</option>
                                        <option value="pagato">Già pagato</option>
                                    </select>
                                </div>

                                <div id="metodo_attesa_container" class="mb-4 p-4 bg-indigo-50 border border-indigo-100 rounded-xl">
                                    <label class="block text-indigo-700 text-sm font-bold mb-2">Modalità di pagamento attesa</label>
                                    <div class="flex gap-4">
                                        <label class="flex items-center">
                                            <input type="radio" name="metodo_attesa" value="stripe" checked class="text-indigo-600 focus:ring-indigo-500">
                                            <span class="ml-2 text-sm text-gray-700">Carta di Credito (Stripe)</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="metodo_attesa" value="bonifico" class="text-indigo-600 focus:ring-indigo-500">
                                            <span class="ml-2 text-sm text-gray-700">Bonifico Bancario</span>
                                        </label>
                                    </div>
                                    <p class="text-[10px] text-indigo-400 mt-1 italic">Verrà generato il link Stripe o inviati i dati IBAN via email.</p>
                                </div>

                                <div class="mb-4 p-4 bg-slate-50 border border-slate-200 rounded-xl">
                                    <div class="flex justify-between items-center">
                                        <label class="block text-slate-700 text-sm font-bold">Importo Totale Calcolato</label>
                                        <span id="display-total" class="text-lg font-bold text-indigo-600">€ 0,00</span>
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-1 italic">Calcolo basato su: <span id="display-price-info">scegli struttura e date</span></p>
                                </div>

                                <div class="mb-6">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Note Amministrative</label>
                                    <textarea name="note" rows="2" class="shadow border rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Esempio: Prenotazione telefonica"></textarea>
                                </div>

                                <!-- Box Occupazione -->
                                <div id="occupancy-container" class="mb-6 hidden">
                                    <h4 class="text-sm font-bold text-red-600 mb-2 border-b border-red-100 pb-1">Date Già Occupate</h4>
                                    <div id="occupancy-list" class="max-h-32 overflow-y-auto space-y-1 text-xs text-gray-600">
                                        <!-- Caricato via JS -->
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold mb-4 border-b pb-2 text-indigo-600">Dettagli Cliente</h3>
                                
                                <div class="mb-6">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Cerca Cliente Esistente (opzionale)</label>
                                    <input type="text" list="customers-list" id="customer-search" class="shadow border rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Inizia a scrivere il nome o email...">
                                    <datalist id="customers-list">
                                        @foreach($customers ?? [] as $c)
                                            <option value="{{ $c->email }}" data-nome="{{ $c->nome }}" data-cognome="{{ $c->cognome }}" data-tel="{{ $c->telefono }}" data-nazione="{{ $c->nazione }}" data-citta="{{ $c->citta }}">
                                                {{ $c->nome }} {{ $c->cognome }} ({{ $c->email }})
                                            </option>
                                        @endforeach
                                    </datalist>
                                    <p class="text-[10px] text-gray-400 mt-1 italic">Scegli dalla lista per auto-compilare i campi sottostanti.</p>
                                </div>

                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Nome *</label>
                                        <input type="text" name="nome" id="field-nome" value="{{ old('nome') }}" required class="shadow border rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Cognome *</label>
                                        <input type="text" name="cognome" id="field-cognome" value="{{ old('cognome') }}" required class="shadow border rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">E-mail *</label>
                                    <input type="email" name="email" id="field-email" value="{{ old('email') }}" required class="shadow border rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Telefono *</label>
                                    <input type="text" name="telefono" id="field-telefono" value="{{ old('telefono') }}" required class="shadow border rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Nazione</label>
                                        <input type="text" name="nazione" id="field-nazione" value="{{ old('nazione') }}" class="shadow border rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="E.g. Italia">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Città</label>
                                        <input type="text" name="citta" id="field-citta" value="{{ old('citta') }}" class="shadow border rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 italic">Se il cliente non esiste, il sistema genererà una password casuale e gli invierà una e-mail di benvenuto.</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 mt-8 pt-6 border-t">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded shadow-lg transition transform active:scale-95">
                                Conferma Prenotazione e Blocca Date
                            </button>
                            <a href="{{ route('admin.booking.calendar') }}" class="text-gray-600 hover:underline">Torna al Calendario</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const structuresData = @json($structures->keyBy('id'));
        const structureSelect = document.querySelector('select[name="booking_structure_id"]');
        const startDateInp = document.querySelector('input[name="start_date"]');
        const endDateInp = document.querySelector('input[name="end_date"]');
        const statoPagamentoSelect = document.getElementById('stato_pagamento');
        const metodoAttesaContainer = document.getElementById('metodo_attesa_container');

        // Toggle sub-payment options
        statoPagamentoSelect.addEventListener('change', function() {
            metodoAttesaContainer.classList.toggle('hidden', this.value !== 'attesa');
        });

        // Occupancy lookup
        structureSelect.addEventListener('change', function() {
            const sid = this.value;
            if (!sid) {
                document.getElementById('occupancy-container').classList.add('hidden');
                updatePrice();
                return;
            }

            fetch(`{{ url('amministrazione/booking/api/occupied-dates') }}/${sid}`)
                .then(res => res.json())
                .then(dates => {
                    const list = document.getElementById('occupancy-list');
                    list.innerHTML = '';
                    if (dates.length > 0) {
                        dates.forEach(d => {
                            const item = document.createElement('div');
                            item.className = 'flex justify-between border-b pb-1 last:border-0';
                            item.innerHTML = `<span>DAL: <b>${formatDate(d.start)}</b></span> <span>AL: <b>${formatDate(d.end)}</b></span>`;
                            list.appendChild(item);
                        });
                        document.getElementById('occupancy-container').classList.remove('hidden');
                    } else {
                        document.getElementById('occupancy-container').classList.add('hidden');
                    }
                    updatePrice();
                });
        });

        [startDateInp, endDateInp].forEach(inp => inp.addEventListener('change', updatePrice));

        function updatePrice() {
            const sid = structureSelect.value;
            const start = startDateInp.value;
            const end = endDateInp.value;

            if (!sid || !start || !end) {
                document.getElementById('display-total').innerText = '€ 0,00';
                document.getElementById('display-price-info').innerText = 'Scegli struttura e date';
                return;
            }

            const struct = structuresData[sid];
            const sDate = new Date(start);
            const eDate = new Date(end);
            const diffTime = Math.abs(eDate - sDate);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            const days = diffDays > 0 ? diffDays : 1;

            const total = (struct.costo_al_giorno * days).toFixed(2);
            document.getElementById('display-total').innerText = '€ ' + total.replace('.', ',');
            document.getElementById('display-price-info').innerText = `${days} gg x ${struct.costo_al_giorno}€`;
        }

        function formatDate(dStr) {
            const d = new Date(dStr);
            return d.toLocaleDateString('it-IT');
        }

        // Initialize display
        updatePrice();
        metodoAttesaContainer.classList.toggle('hidden', statoPagamentoSelect.value !== 'attesa');

        // Customer search (existing code)
        document.getElementById('customer-search').addEventListener('input', function(e) {
            const val = e.target.value;
            const options = document.querySelectorAll('#customers-list option');
            
            options.forEach(option => {
                if (option.value === val) {
                    document.getElementById('field-email').value = option.value;
                    document.getElementById('field-nome').value = option.getAttribute('data-nome');
                    document.getElementById('field-cognome').value = option.getAttribute('data-cognome');
                    document.getElementById('field-telefono').value = option.getAttribute('data-tel');
                    document.getElementById('field-nazione').value = option.getAttribute('data-nazione');
                    document.getElementById('field-citta').value = option.getAttribute('data-citta');
                    
                    e.target.value = '';
                    e.target.placeholder = 'Cliente Selezionato: ' + option.getAttribute('data-nome') + ' ' + option.getAttribute('data-cognome');
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
