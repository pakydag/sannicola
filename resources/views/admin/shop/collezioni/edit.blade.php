<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Modifica Collezione Shop: ' . $collezione->nome) }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.shop.collezioni.update', $collezione) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nome Collezione *</label>
                            <input type="text" name="nome" value="{{ old('nome', $collezione->nome) }}" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">URL Slug</label>
                            <input type="text" name="slug" value="{{ old('slug', $collezione->slug) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Immagine Copertina</label>
                            @if($collezione->foto)
                                <div class="mb-3 px-4 py-2 bg-gray-50 border rounded inline-block">
                                    <img src="{{ asset($collezione->foto) }}" alt="Foto Attuale" class="h-16 object-contain">
                                </div>
                            @endif
                            <div class="flex mt-1 relative rounded-md shadow-sm max-w-lg">
                                <input type="text" id="foto" name="foto" value="{{ old('foto', $collezione->foto) }}" readonly placeholder="Seleziona Immagine..." class="shadow border rounded-l w-full py-2 px-3 bg-white focus:outline-none">
                                <button type="button" id="btn-sfoglia-foto" class="-ml-px relative inline-flex items-center space-x-2 px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-200 hover:bg-gray-300 focus:outline-none">
                                    <span>Sfoglia...</span>
                                </button>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tag Associati</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 border p-3 rounded bg-gray-50 h-32 overflow-y-auto">
                                @forelse($tags as $tag)
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm" {{ in_array($tag->id, old('tags', $collezione->tags->pluck('id')->toArray())) ? 'checked' : '' }}>
                                        <span class="ml-2 text-gray-700 text-sm">{{ $tag->nome }}</span>
                                    </label>
                                @empty
                                    <span class="text-sm text-gray-500">Nessun tag disponibile.</span>
                                @endforelse
                            </div>
                        </div>
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Aggiungi Nuovi Tag (Separati da virgola)</label>
                            <input type="text" name="tags_string" value="{{ old('tags_string') }}" class="shadow border rounded w-full py-2 px-3 text-gray-700" placeholder="Es. Estate, Mare, Sole">
                        </div>
                        <div class="mb-6">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="visibile" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm" {{ old('visibile', $collezione->visibile) ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">Visibile nel Negozio</span>
                            </label>
                        </div>
                        <div class="flex items-center justify-between">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none">Aggiorna</button>
                            <a href="{{ route('admin.shop.collezioni.index') }}" class="inline-block align-baseline font-bold text-sm text-gray-500 hover:text-gray-800">Annulla</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                if (document.getElementById('btn-sfoglia-foto')) {
                    document.getElementById('btn-sfoglia-foto').addEventListener('click', (e) => {
                        e.preventDefault();
                        window.fmActiveInput = document.getElementById('foto');
                        window.open('{{ url('file-manager/fm-button') }}', 'fm', 'width=1400,height=800');
                    });
                }
            });
            function fmSetLink($url) {
                if (window.fmActiveInput) {
                    window.fmActiveInput.value = $url;
                }
            }
        </script>
    @endpush
</x-app-layout>
