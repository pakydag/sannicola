<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Modifica Marca: {{ $marca->nome }}</h2>
            <a href="{{ route('admin.shop.marche.index') }}" class="text-sm text-gray-500 hover:text-gray-800">← Torna alle Marche</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.shop.marche.update', $marca) }}" method="POST">
                        @csrf @method('PUT')

                        {{-- Nome --}}
                        <div class="mb-5">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nome Marca *</label>
                            <input type="text" name="nome" value="{{ old('nome', $marca->nome) }}" required
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        </div>

                        {{-- Foto / Logo --}}
                        <div class="mb-5">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Logo / Immagine Marca</label>
                            <div class="flex items-center gap-2">
                                <input type="text" id="foto" name="foto" value="{{ old('foto', $marca->foto) }}" readonly
                                       placeholder="Seleziona immagine dal media manager…"
                                       class="shadow border rounded-l w-full py-2 px-3 bg-white focus:outline-none text-gray-600">
                                <button type="button" id="btn-sfoglia-foto"
                                        class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-r bg-gray-200 hover:bg-gray-300 focus:outline-none whitespace-nowrap">
                                    Sfoglia…
                                </button>
                            </div>
                            <div id="foto-preview" class="{{ old('foto', $marca->foto) ? '' : 'hidden' }} mt-2">
                                <img id="foto-img" src="{{ old('foto', $marca->foto) }}" alt="preview"
                                     class="h-16 object-contain rounded border border-gray-200 bg-gray-50 p-1">
                                <button type="button" id="btn-rimuovi-foto"
                                        class="mt-1 text-xs text-red-500 hover:text-red-700">✕ Rimuovi immagine</button>
                            </div>
                        </div>

                        {{-- Descrizione --}}
                        <div class="mb-5">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Descrizione (opzionale)</label>
                            <textarea name="descrizione" rows="3"
                                      class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-300">{{ old('descrizione', $marca->descrizione) }}</textarea>
                        </div>

                        {{-- Ordine --}}
                        <div class="mb-5">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Ordine di visualizzazione</label>
                            <input type="number" name="ordine" value="{{ old('ordine', $marca->ordine) }}" min="0"
                                   class="shadow border rounded py-2 px-3 text-gray-700 w-24 focus:outline-none">
                        </div>

                        {{-- Visibile --}}
                        <div class="mb-6">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="visibile" value="1"
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm"
                                       {{ old('visibile', $marca->visibile) ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">Visibile nel Negozio</span>
                            </label>
                        </div>

                        {{-- Info prodotti associati --}}
                        @php $productCount = $marca->products()->count(); @endphp
                        @if($productCount > 0)
                            <div class="mb-6 p-3 bg-indigo-50 border border-indigo-200 rounded text-sm text-indigo-700">
                                ℹ️ Questa marca è associata a <strong>{{ $productCount }}</strong> {{ $productCount == 1 ? 'prodotto' : 'prodotti' }}.
                            </div>
                        @endif

                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <button type="submit"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded focus:outline-none transition">
                                Aggiorna Marca
                            </button>
                            <a href="{{ route('admin.shop.marche.index') }}"
                               class="text-sm text-gray-500 hover:text-gray-800 font-bold">Annulla</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const fotoInput = document.getElementById('foto');
                const fotoPreview = document.getElementById('foto-preview');
                const fotoImg = document.getElementById('foto-img');
                const btnRimuovi = document.getElementById('btn-rimuovi-foto');

                document.getElementById('btn-sfoglia-foto').addEventListener('click', function (e) {
                    e.preventDefault();
                    window.fmActiveInput = fotoInput;
                    window.open('{{ url('file-manager/fm-button') }}', 'fm', 'width=1400,height=800');
                });

                fotoInput.addEventListener('input', function () {
                    if (this.value) {
                        fotoImg.src = this.value;
                        fotoPreview.classList.remove('hidden');
                    } else {
                        fotoPreview.classList.add('hidden');
                    }
                });

                if (btnRimuovi) {
                    btnRimuovi.addEventListener('click', function () {
                        fotoInput.removeAttribute('readonly');
                        fotoInput.value = '';
                        fotoInput.setAttribute('readonly', true);
                        fotoPreview.classList.add('hidden');
                    });
                }
            });

            function fmSetLink($url) {
                if (window.fmActiveInput) {
                    window.fmActiveInput.value = $url;
                    window.fmActiveInput.dispatchEvent(new Event('input', { bubbles: true }));
                }
            }
        </script>
    @endpush
</x-app-layout>
