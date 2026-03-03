<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Modifica Categoria Shop: ' . $categoria->nome) }}</h2>
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

                    <form action="{{ route('admin.shop.categorie.update', $categoria) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nome Categoria *</label>
                            <input type="text" name="nome" value="{{ old('nome', $categoria->nome) }}" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">URL Slug</label>
                            <input type="text" name="slug" value="{{ old('slug', $categoria->slug) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Categoria Padre</label>
                            <select name="parent_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:outline-none">
                                <option value="">-- Nessuna (Categoria Principale) --</option>
                                @foreach($categorie_padre as $cp)
                                    <option value="{{ $cp->id }}" {{ old('parent_id', $categoria->parent_id) == $cp->id ? 'selected' : '' }}>{{ $cp->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-6">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="visibile" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm" {{ old('visibile', $categoria->visibile) ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">Visibile nel Negozio</span>
                            </label>
                        </div>
                        <div class="flex items-center justify-between">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none">Aggiorna</button>
                            <a href="{{ route('admin.shop.categorie.index') }}" class="inline-block align-baseline font-bold text-sm text-gray-500 hover:text-gray-800">Annulla</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
