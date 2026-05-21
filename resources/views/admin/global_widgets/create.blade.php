<x-app-layout>
@php
    $englishEnabled = \App\Models\Setting::where('key', 'english_enabled')->value('value') == '1';
@endphp
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Crea Nuovo Widget Globale
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200" x-data="{ activeTab: 'gallery' }">
                    
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4 mb-8">
                        <!-- Gallery Foto -->
                        <button @click="activeTab = 'gallery'" 
                            :class="{'ring-2 ring-indigo-500 bg-indigo-50 border-indigo-200': activeTab === 'gallery', 'bg-white border-gray-200 hover:border-indigo-300': activeTab !== 'gallery'}"
                            class="flex flex-col items-center justify-center p-4 rounded-xl border transition-all duration-200 group">
                            <div :class="activeTab === 'gallery' ? 'bg-indigo-600' : 'bg-gray-100 group-hover:bg-indigo-100'" class="w-12 h-12 rounded-lg flex items-center justify-center mb-2 transition-colors">
                                <svg class="w-6 h-6" :class="activeTab === 'gallery' ? 'text-white' : 'text-gray-500 group-hover:text-indigo-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <span class="text-xs font-bold" :class="activeTab === 'gallery' ? 'text-indigo-700' : 'text-gray-600'">Gallery Foto</span>
                        </button>

                        <!-- Video -->
                        <button @click="activeTab = 'video'" 
                            :class="{'ring-2 ring-indigo-500 bg-indigo-50 border-indigo-200': activeTab === 'video', 'bg-white border-gray-200 hover:border-indigo-300': activeTab !== 'video'}"
                            class="flex flex-col items-center justify-center p-4 rounded-xl border transition-all duration-200 group">
                            <div :class="activeTab === 'video' ? 'bg-indigo-600' : 'bg-gray-100 group-hover:bg-indigo-100'" class="w-12 h-12 rounded-lg flex items-center justify-center mb-2 transition-colors">
                                <svg class="w-6 h-6" :class="activeTab === 'video' ? 'text-white' : 'text-gray-500 group-hover:text-indigo-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            </div>
                            <span class="text-xs font-bold" :class="activeTab === 'video' ? 'text-indigo-700' : 'text-gray-600'">Video</span>
                        </button>

                        <!-- Blocchi Specchio -->
                        <button @click="activeTab = 'mirror'" 
                            :class="{'ring-2 ring-indigo-500 bg-indigo-50 border-indigo-200': activeTab === 'mirror', 'bg-white border-gray-200 hover:border-indigo-300': activeTab !== 'mirror'}"
                            class="flex flex-col items-center justify-center p-4 rounded-xl border transition-all duration-200 group">
                            <div :class="activeTab === 'mirror' ? 'bg-indigo-600' : 'bg-gray-100 group-hover:bg-indigo-100'" class="w-12 h-12 rounded-lg flex items-center justify-center mb-2 transition-colors">
                                <svg class="w-6 h-6" :class="activeTab === 'mirror' ? 'text-white' : 'text-gray-500 group-hover:text-indigo-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                            </div>
                            <span class="text-xs font-bold" :class="activeTab === 'mirror' ? 'text-indigo-700' : 'text-gray-600'">Specchio</span>
                        </button>

                        <!-- Blocco Singolo -->
                        <button @click="activeTab = 'single'" 
                            :class="{'ring-2 ring-indigo-500 bg-indigo-50 border-indigo-200': activeTab === 'single', 'bg-white border-gray-200 hover:border-indigo-300': activeTab !== 'single'}"
                            class="flex flex-col items-center justify-center p-4 rounded-xl border transition-all duration-200 group">
                            <div :class="activeTab === 'single' ? 'bg-indigo-600' : 'bg-gray-100 group-hover:bg-indigo-100'" class="w-12 h-12 rounded-lg flex items-center justify-center mb-2 transition-colors">
                                <svg class="w-6 h-6" :class="activeTab === 'single' ? 'text-white' : 'text-gray-500 group-hover:text-indigo-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                            <span class="text-xs font-bold" :class="activeTab === 'single' ? 'text-indigo-700' : 'text-gray-600'">Blocco Singolo</span>
                        </button>

                        <!-- Griglia Sezione -->
                        <button @click="activeTab = 'grid'" 
                            :class="{'ring-2 ring-indigo-500 bg-indigo-50 border-indigo-200': activeTab === 'grid', 'bg-white border-gray-200 hover:border-indigo-300': activeTab !== 'grid'}"
                            class="flex flex-col items-center justify-center p-4 rounded-xl border transition-all duration-200 group">
                            <div :class="activeTab === 'grid' ? 'bg-indigo-600' : 'bg-gray-100 group-hover:bg-indigo-100'" class="w-12 h-12 rounded-lg flex items-center justify-center mb-2 transition-colors">
                                <svg class="w-6 h-6" :class="activeTab === 'grid' ? 'text-white' : 'text-gray-500 group-hover:text-indigo-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                            </div>
                            <span class="text-xs font-bold" :class="activeTab === 'grid' ? 'text-indigo-700' : 'text-gray-600'">Griglia Sezione</span>
                        </button>

                        <!-- Blocchi Info -->
                        <button @click="activeTab = 'info_blocks'" 
                            :class="{'ring-2 ring-indigo-500 bg-indigo-50 border-indigo-200': activeTab === 'info_blocks', 'bg-white border-gray-200 hover:border-indigo-300': activeTab !== 'info_blocks'}"
                            class="flex flex-col items-center justify-center p-4 rounded-xl border transition-all duration-200 group">
                            <div :class="activeTab === 'info_blocks' ? 'bg-indigo-600' : 'bg-gray-100 group-hover:bg-indigo-100'" class="w-12 h-12 rounded-lg flex items-center justify-center mb-2 transition-colors">
                                <svg class="w-6 h-6" :class="activeTab === 'info_blocks' ? 'text-white' : 'text-gray-500 group-hover:text-indigo-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <span class="text-xs font-bold" :class="activeTab === 'info_blocks' ? 'text-indigo-700' : 'text-gray-600'">Blocchi Info</span>
                        </button>

                        <!-- Foto-Testo-Foto -->
                        <button @click="activeTab = 'image_text_image'" 
                            :class="{'ring-2 ring-indigo-500 bg-indigo-50 border-indigo-200': activeTab === 'image_text_image', 'bg-white border-gray-200 hover:border-indigo-300': activeTab !== 'image_text_image'}"
                            class="flex flex-col items-center justify-center p-4 rounded-xl border transition-all duration-200 group">
                            <div :class="activeTab === 'image_text_image' ? 'bg-indigo-600' : 'bg-gray-100 group-hover:bg-indigo-100'" class="w-12 h-12 rounded-lg flex items-center justify-center mb-2 transition-colors">
                                <svg class="w-6 h-6" :class="activeTab === 'image_text_image' ? 'text-white' : 'text-gray-500 group-hover:text-indigo-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h2a2 2 0 00-2 2"></path></svg>
                            </div>
                            <span class="text-xs font-bold" :class="activeTab === 'image_text_image' ? 'text-indigo-700' : 'text-gray-600'">F-T-F</span>
                        </button>

                        <!-- Strutture -->
                        <button @click="activeTab = 'booking_structures'" 
                            :class="{'ring-2 ring-indigo-500 bg-indigo-50 border-indigo-200': activeTab === 'booking_structures', 'bg-white border-gray-200 hover:border-indigo-300': activeTab !== 'booking_structures'}"
                            class="flex flex-col items-center justify-center p-4 rounded-xl border transition-all duration-200 group">
                            <div :class="activeTab === 'booking_structures' ? 'bg-indigo-600' : 'bg-gray-100 group-hover:bg-indigo-100'" class="w-12 h-12 rounded-lg flex items-center justify-center mb-2 transition-colors">
                                <svg class="w-6 h-6" :class="activeTab === 'booking_structures' ? 'text-white' : 'text-gray-500 group-hover:text-indigo-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <span class="text-xs font-bold" :class="activeTab === 'booking_structures' ? 'text-indigo-700' : 'text-gray-600'">Strutture</span>
                        </button>

                        <!-- Mappa -->
                        <button @click="activeTab = 'map'" 
                            :class="{'ring-2 ring-indigo-500 bg-indigo-50 border-indigo-200': activeTab === 'map', 'bg-white border-gray-200 hover:border-indigo-300': activeTab !== 'map'}"
                            class="flex flex-col items-center justify-center p-4 rounded-xl border transition-all duration-200 group">
                            <div :class="activeTab === 'map' ? 'bg-indigo-600' : 'bg-gray-100 group-hover:bg-indigo-100'" class="w-12 h-12 rounded-lg flex items-center justify-center mb-2 transition-colors">
                                <svg class="w-6 h-6" :class="activeTab === 'map' ? 'text-white' : 'text-gray-500 group-hover:text-indigo-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <span class="text-xs font-bold" :class="activeTab === 'map' ? 'text-indigo-700' : 'text-gray-600'">Mappa</span>
                        </button>

                        @if(config('app.booking_enabled') === '1' || \App\Models\Setting::where('key', 'booking_enabled')->value('value') == '1')
                            <!-- Ricerca Booking -->
                            <button @click="activeTab = 'booking_search'" 
                                :class="{'ring-2 ring-indigo-500 bg-indigo-50 border-indigo-200': activeTab === 'booking_search', 'bg-white border-gray-200 hover:border-indigo-300': activeTab !== 'booking_search'}"
                                class="flex flex-col items-center justify-center p-4 rounded-xl border transition-all duration-200 group">
                                <div :class="activeTab === 'booking_search' ? 'bg-indigo-600' : 'bg-gray-100 group-hover:bg-indigo-100'" class="w-12 h-12 rounded-lg flex items-center justify-center mb-2 transition-colors">
                                    <svg class="w-6 h-6" :class="activeTab === 'booking_search' ? 'text-white' : 'text-gray-500 group-hover:text-indigo-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <span class="text-xs font-bold" :class="activeTab === 'booking_search' ? 'text-indigo-700' : 'text-gray-600'">Cerca Booking</span>
                            </button>
                        @endif

                        @if($shop_enabled)
                            <!-- Collezione Shop -->
                            <button @click="activeTab = 'shop_collection'" 
                                :class="{'ring-2 ring-orange-500 bg-orange-50 border-orange-200': activeTab === 'shop_collection', 'bg-white border-gray-200 hover:border-orange-300': activeTab !== 'shop_collection'}"
                                class="flex flex-col items-center justify-center p-4 rounded-xl border transition-all duration-200 group">
                                <div :class="activeTab === 'shop_collection' ? 'bg-orange-500 shadow-orange-200 shadow-lg' : 'bg-orange-100 group-hover:bg-orange-200'" class="w-12 h-12 rounded-lg flex items-center justify-center mb-2 transition-colors">
                                    <svg class="w-6 h-6" :class="activeTab === 'shop_collection' ? 'text-white' : 'text-orange-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                </div>
                                <span class="text-xs font-bold uppercase tracking-wider" :class="activeTab === 'shop_collection' ? 'text-orange-700' : 'text-gray-600'">Shop Collezione</span>
                            </button>

                            <!-- Prodotti Evidenza -->
                            <button @click="activeTab = 'shop_products'" 
                                :class="{'ring-2 ring-orange-500 bg-orange-50 border-orange-200': activeTab === 'shop_products', 'bg-white border-gray-200 hover:border-orange-300': activeTab !== 'shop_products'}"
                                class="flex flex-col items-center justify-center p-4 rounded-xl border transition-all duration-200 group">
                                <div :class="activeTab === 'shop_products' ? 'bg-orange-500 shadow-orange-200 shadow-lg' : 'bg-orange-100 group-hover:bg-orange-200'" class="w-12 h-12 rounded-lg flex items-center justify-center mb-2 transition-colors">
                                    <svg class="w-6 h-6" :class="activeTab === 'shop_products' ? 'text-white' : 'text-orange-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.54 1.118l-3.976-2.888a1 1 0 00-1.175 0l-3.976 2.888c-.784.57-1.838-.197-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                                </div>
                                <span class="text-xs font-bold uppercase tracking-wider" :class="activeTab === 'shop_products' ? 'text-orange-700' : 'text-gray-600'">Shop In Evidenza</span>
                            </button>

                            <!-- Marche -->
                            <button @click="activeTab = 'shop_brands'" 
                                :class="{'ring-2 ring-orange-500 bg-orange-50 border-orange-200': activeTab === 'shop_brands', 'bg-white border-gray-200 hover:border-orange-300': activeTab !== 'shop_brands'}"
                                class="flex flex-col items-center justify-center p-4 rounded-xl border transition-all duration-200 group">
                                <div :class="activeTab === 'shop_brands' ? 'bg-orange-500 shadow-orange-200 shadow-lg' : 'bg-orange-100 group-hover:bg-orange-200'" class="w-12 h-12 rounded-lg flex items-center justify-center mb-2 transition-colors">
                                    <svg class="w-6 h-6" :class="activeTab === 'shop_brands' ? 'text-white' : 'text-orange-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                </div>
                                <span class="text-xs font-bold uppercase tracking-wider" :class="activeTab === 'shop_brands' ? 'text-orange-700' : 'text-gray-600'">Shop Marche</span>
                            </button>
                        @endif
                        
                        <!-- Annuncio (Top Announcement) -->
                        <button @click="activeTab = 'announcement'" 
                            :class="{'ring-2 ring-indigo-500 bg-indigo-50 border-indigo-200': activeTab === 'announcement', 'bg-white border-gray-200 hover:border-indigo-300': activeTab !== 'announcement'}"
                            class="flex flex-col items-center justify-center p-4 rounded-xl border transition-all duration-200 group">
                            <div :class="activeTab === 'announcement' ? 'bg-indigo-500 shadow-indigo-200 shadow-lg' : 'bg-indigo-100 group-hover:bg-indigo-200'" class="w-12 h-12 rounded-lg flex items-center justify-center mb-2 transition-colors">
                                <svg class="w-6 h-6" :class="activeTab === 'announcement' ? 'text-white' : 'text-indigo-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.167M12 13h1m8-7V5a2 2 0 00-2-2H9a2 2 0 00-2 2v1h10a2 2 0 012 2v3m2 4h-2.343M11 13v9m0-9a3 3 0 013-3h3a3 3 0 013 3v9m-9 0h9"></path></svg>
                            </div>
                            <span class="text-xs font-bold uppercase tracking-wider" :class="activeTab === 'announcement' ? 'text-indigo-700' : 'text-gray-600'">Annuncio Top</span>
                        </button>
                    </div>

                    <!-- Form Gallery -->
                    <div x-show="activeTab === 'gallery'" class="bg-indigo-50 p-6 rounded-lg border border-indigo-100 shadow-inner">
                        <form action="{{ route('admin.global-widgets.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="tipo" value="gallery">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Globale *</label>
                                <input type="text" name="titolo" required class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none">
                            </div>
@if($englishEnabled)
<div class="mb-4 bg-indigo-50/30 p-3 rounded border border-indigo-100/50">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Globale * [INGLESE]</label>
                                <input type="text" name="titolo_en"  class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none">
                            </div>
@endif

                            <div id="gallery-container" class="space-y-3 mb-4">
                                <div class="flex items-center space-x-2 gallery-row">
                                    <div class="flex-1 flex flex-col space-y-2 text-sm">
                                        <div class="flex">
                                            <input type="text" name="data[photos][0][url]" readonly required placeholder="URL Foto dal File Manager" class="shadow border rounded-l w-full py-2 px-3 bg-white focus:outline-none">
                                            <button type="button" class="btn-sfoglia-gallery bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-r shadow border border-l-0 text-sm whitespace-nowrap">📸 Foto</button>
                                        </div>
                                        <div class="flex">
                                            <input type="text" name="data[photos][0][video_url]" readonly placeholder="URL Video (opzionale)" class="shadow border rounded-l w-full py-2 px-3 bg-white focus:outline-none">
                                            <button type="button" class="btn-sfoglia-video-gallery bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-r shadow border border-l-0 text-sm whitespace-nowrap">🎥 Video</button>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <input type="text" name="data[photos][0][link]" placeholder="Link Opzionale" class="shadow border rounded w-full py-2 px-3 h-full focus:outline-none text-sm">
                                    </div>
                                    <button type="button" class="btn-rimuovi-foto text-red-500 opacity-50 hover:opacity-100"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg></button>
                                </div>
                            </div>
                            <div class="flex items-center justify-between mt-4">
                                <button type="button" id="btn-aggiungi-foto" class="text-indigo-600 font-bold flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> Aggiungi Silde</button>
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-6 rounded shadow">Salva Gallery Globale</button>
                            </div>
                        </form>
                    </div>

                    <!-- Form Top Announcement -->
                    <div x-show="activeTab === 'announcement'" style="display: none;" class="bg-indigo-50 p-6 rounded-lg border border-indigo-100 shadow-inner">
                        <form action="{{ route('admin.global-widgets.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="tipo" value="top_announcement">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Interno Admin *</label>
                                <input type="text" name="titolo" required class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none" placeholder="es. Messaggio di benvenuto">
                            </div>
@if($englishEnabled)
<div class="mb-4 bg-indigo-50/30 p-3 rounded border border-indigo-100/50">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Interno Admin * [INGLESE]</label>
                                <input type="text" name="titolo_en"  class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none" placeholder="es. Messaggio di benvenuto">
                            </div>
@endif

                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div class="md:col-span-2">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Messaggio Comunicazione *</label>
                                    <input type="text" name="data[message]" required placeholder="Es: Spedizione gratuita per ordini sopra i 50€!" class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                                </div>
@if($englishEnabled)
<div class="md:col-span-2">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Messaggio Comunicazione * [INGLESE]</label>
                                    <input type="text" name="data[message_en]"  placeholder="Es: Spedizione gratuita per ordini sopra i 50€!" class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                                </div>
@endif

                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Colore Sfondo</label>
                                    <select name="data[bg_color]" class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                                        <option value="bg-indigo-600">Indaco (Default)</option>
                                        <option value="bg-gray-900">Nero</option>
                                        <option value="bg-red-600">Rosso (Emergenza)</option>
                                        <option value="bg-yellow-500">Giallo (Avviso)</option>
                                        <option value="bg-green-600">Verde (Successo)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Icona (Emoji)</label>
                                    <input type="text" name="data[icon]" placeholder="Es: 📣 o 🚚" class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Testo Bottone (Opzionale)</label>
                                    <input type="text" name="data[button_text]" placeholder="Es: Scopri di più" class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                                </div>
@if($englishEnabled)
<div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Testo Bottone (Opzionale) [INGLESE]</label>
                                    <input type="text" name="data[button_text_en]" placeholder="Es: Scopri di più" class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                                </div>
@endif

                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Link Bottone</label>
                                    <input type="text" name="data[button_url]" placeholder="Es: /shop o https://..." class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                                </div>
                            </div>

                            <div class="text-right mt-4">
                                <button type="submit" class="bg-indigo-600 text-white font-bold py-2 px-6 rounded shadow">Salva Widget Annuncio</button>
                            </div>
                        </form>
                    </div>

                    <!-- Form Video -->
                    <div x-show="activeTab === 'video'" style="display: none;" class="bg-indigo-50 p-6 rounded-lg border border-indigo-100 shadow-inner">
                        <form action="{{ route('admin.global-widgets.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="tipo" value="video">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Globale *</label>
                                <input type="text" name="titolo" required class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none">
                            </div>
@if($englishEnabled)
<div class="mb-4 bg-indigo-50/30 p-3 rounded border border-indigo-100/50">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Globale * [INGLESE]</label>
                                <input type="text" name="titolo_en"  class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none">
                            </div>
@endif

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">File Video (MP4) *</label>
                                <div class="flex mt-1 relative rounded-md shadow-sm">
                                    <input type="text" id="video_url" name="data[video_url]" readonly required class="shadow border rounded-l w-full py-2 px-3 bg-white focus:outline-none">
                                    <button type="button" id="btn-sfoglia-video" class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-200">Scegli MP4...</button>
                                </div>
                            </div>
                            <div class="mb-4 flex space-x-6">
                                <label class="flex items-center">
                                    <input type="checkbox" name="data[autoplay]" value="1" class="form-checkbox h-5 w-5 text-indigo-600 rounded border-gray-300">
                                    <span class="ml-2 text-gray-700 font-medium">Autoplay (Parte in automatico)</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="data[loop]" value="1" class="form-checkbox h-5 w-5 text-indigo-600 rounded border-gray-300">
                                    <span class="ml-2 text-gray-700 font-medium">Loop (Ripeti all'infinito)</span>
                                </label>
                            </div>
                            <div class="text-right mt-4">
                                <button type="submit" class="bg-indigo-600 text-white font-bold py-2 px-6 rounded shadow">Salva Video Globale</button>
                            </div>
                        </form>
                    </div>

                    <!-- Form Mirror Blocks -->
                    <div x-show="activeTab === 'mirror'" style="display: none;" class="bg-indigo-50 p-6 rounded-lg border border-indigo-100 shadow-inner">
                        <form action="{{ route('admin.global-widgets.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="tipo" value="mirror_blocks">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Globale *</label>
                                <input type="text" name="titolo" required class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                            </div>
                            @if($englishEnabled)
                            <div class="mb-4 bg-indigo-50/30 p-3 rounded border border-indigo-100/50">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Globale * [INGLESE]</label>
                                <input type="text" name="titolo_en" class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                            </div>
                            @endif

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Sezione Sorgente *</label>
                                    <select name="data[source_section_id]" required class="shadow border rounded w-full py-2 px-3">
                                        <option value="">-- Seleziona --</option>
                                        @foreach(\App\Models\Section::all() as $sez)
                                            <option value="{{ $sez->id }}">{{ $sez->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Numero Articoli (Limite) *</label>
                                    <select name="data[limit]" required class="shadow border rounded w-full py-2 px-3">
                                        @for($i=1; $i<=10; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="text-right mt-4">
                                <button type="submit" class="bg-indigo-600 text-white font-bold py-2 px-6 rounded shadow">Salva Specchio Globale</button>
                            </div>
                        </form>
                    </div>

                    <!-- Form Single Block -->
                    <div x-show="activeTab === 'single'" style="display: none;" class="bg-indigo-50 p-6 rounded-lg border border-indigo-100 shadow-inner">
                        <form action="{{ route('admin.global-widgets.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="tipo" value="single_block">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Blocco *</label>
                                <input type="text" name="titolo" required class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none">
                            </div>
                            @if($englishEnabled)
                            <div class="mb-4 bg-indigo-50/30 p-3 rounded border border-indigo-100/50">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Blocco * [INGLESE]</label>
                                <input type="text" name="titolo_en" class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none">
                            </div>
                            @endif

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Sottotitolo (Opzionale)</label>
                                <input type="text" name="data[subtitle]" class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none">
                            </div>
                            @if($englishEnabled)
                            <div class="mb-4 bg-indigo-50/30 p-3 rounded border border-indigo-100/50">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Sottotitolo (Opzionale) [INGLESE]</label>
                                <input type="text" name="data[subtitle_en]" class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none">
                            </div>
                            @endif

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Link al click (Opzionale)</label>
                                <input type="url" name="data[link]" placeholder="https://..." class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none">
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Immagine Sfondo / Principale</label>
                                    <div class="flex mt-1 relative rounded-md shadow-sm">
                                        <input type="text" id="single_block_image" name="data[image]" readonly required placeholder="Immagine dal File Manager" class="shadow border rounded-l w-full py-2 px-3 bg-white focus:outline-none">
                                        <button type="button" id="btn-sfoglia-single" class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-200">Scegli Foto...</button>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Video Sfondo (Opzionale)</label>
                                    <div class="flex mt-1 relative rounded-md shadow-sm">
                                        <input type="text" id="single_block_video" name="data[video]" readonly placeholder="URL Video (.mp4)" class="shadow border rounded-l w-full py-2 px-3 bg-white focus:outline-none">
                                        <button type="button" id="btn-sfoglia-single-video" class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-200">Scegli Video...</button>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div class="col-span-1">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Colore Sfondo Blocco (HEX)</label>
                                    <div class="flex items-center space-x-2">
                                        <input type="color" name="data[bg_color]" value="#ffffff" class="h-10 w-10 border rounded cursor-pointer">
                                        <span class="text-sm text-gray-500">Scegli un colore di sfondo.</span>
                                    </div>
                                </div>
                                <div class="col-span-1">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Disposizione Contenuto</label>
                                    <div class="flex flex-col space-y-2">
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="data[layout]" value="side" checked class="form-radio h-4 w-4 text-indigo-600">
                                            <span class="ml-2 text-sm text-gray-700 font-medium">Foto a sinistra (Full)</span>
                                        </label>
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="data[layout]" value="stacked" class="form-radio h-4 w-4 text-indigo-600">
                                            <span class="ml-2 text-sm text-gray-700 font-medium">Foto sopra (Stacked)</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right mt-4">
                                <button type="submit" class="bg-indigo-600 text-white font-bold py-2 px-6 rounded shadow">Salva Blocco Singolo</button>
                            </div>
                        </form>
                    </div>

                    <!-- Form Booking Structures -->
                    <div x-show="activeTab === 'booking_structures'" style="display: none;" class="bg-indigo-50 p-6 rounded-lg border border-indigo-100 shadow-inner">
                        <form action="{{ route('admin.global-widgets.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="tipo" value="booking_structures">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Globale *</label>
                                <input type="text" name="titolo" required class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none">
                            </div>
@if($englishEnabled)
<div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Immagine Sfondo / Principale</label>
                                    <div class="flex mt-1 relative rounded-md shadow-sm">
                                        <input type="text" id="single_block_image" name="data[image]" readonly  placeholder="Immagine dal File Manager" class="shadow border rounded-l w-full py-2 px-3 bg-white focus:outline-none">
                                        <button type="button" id="btn-sfoglia-single" class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-200">Scegli Foto...</button>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Video Sfondo (Opzionale)</label>
                                    <div class="flex mt-1 relative rounded-md shadow-sm">
                                        <input type="text" id="single_block_video" name="data[video]" readonly placeholder="URL Video (.mp4)" class="shadow border rounded-l w-full py-2 px-3 bg-white focus:outline-none">
                                        <button type="button" id="btn-sfoglia-single-video" class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-200">Scegli Video...</button>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div class="col-span-1">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Colore Sfondo Blocco (HEX)</label>
                                    <div class="flex items-center space-x-2">
                                        <input type="color" name="data[bg_color]" value="#ffffff" class="h-10 w-10 border rounded cursor-pointer">
                                        <span class="text-sm text-gray-500">Scegli un colore di sfondo.</span>
                                    </div>
                                </div>
                                <div class="col-span-1">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Disposizione Contenuto</label>
                                    <div class="flex flex-col space-y-2">
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="data[layout]" value="side" checked class="form-radio h-4 w-4 text-indigo-600">
                                            <span class="ml-2 text-sm text-gray-700 font-medium">Foto a sinistra (Full)</span>
                                        </label>
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="data[layout]" value="stacked" class="form-radio h-4 w-4 text-indigo-600">
                                            <span class="ml-2 text-sm text-gray-700 font-medium">Foto sopra (Stacked)</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right mt-4">
                                <button type="submit" class="bg-indigo-600 text-white font-bold py-2 px-6 rounded shadow">Salva Blocco Singolo</button>
                            </div>
                        </form>
                    </div>

                    <!-- Form Booking Structures -->
                    <div x-show="activeTab === 'booking_structures'" style="display: none;" class="bg-indigo-50 p-6 rounded-lg border border-indigo-100 shadow-inner">
                        <form action="{{ route('admin.global-widgets.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="tipo" value="booking_structures">
                            <div class="mb-4 bg-indigo-50/30 p-3 rounded border border-indigo-100/50">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Globale * [INGLESE]</label>
                                <input type="text" name="titolo_en"  class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none">
                            </div>
@endif

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Numero di Strutture *</label>
                                    <select name="data[limit]" required class="shadow border rounded w-full py-2 px-3">
                                        @for($i=1; $i<=12; $i++)
                                            <option value="{{ $i }}" {{ $i == 3 ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Elementi per riga *</label>
                                    <select name="data[columns]" required class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                                        <option value="1">1 Colonna</option>
                                        <option value="2">2 Colonne</option>
                                        <option value="3" selected>3 Colonne</option>
                                        <option value="4">4 Colonne</option>
                                    </select>
                                </div>
                            </div>
                            <div class="text-right mt-4">
                                <button type="submit" class="bg-indigo-600 text-white font-bold py-2 px-6 rounded shadow">Salva Widget Strutture</button>
                            </div>
                        </form>
                    </div>

                    <!-- Form Map -->
                    <div x-show="activeTab === 'map'" style="display: none;" class="bg-indigo-50 p-6 rounded-lg border border-indigo-100 shadow-inner">
                        <form action="{{ route('admin.global-widgets.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="tipo" value="map">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Globale *</label>
                                <input type="text" name="titolo" required class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none" placeholder="es. La nostra posizione">
                            </div>
@if($englishEnabled)
<div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Numero di Strutture *</label>
                                    <select name="data[limit]"  class="shadow border rounded w-full py-2 px-3">
                                        @for($i=1; $i<=12; $i++)
                                            <option value="{{ $i }}" {{ $i == 3 ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Elementi per riga *</label>
                                    <select name="data[columns]"  class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                                        <option value="1">1 Colonna</option>
                                        <option value="2">2 Colonne</option>
                                        <option value="3" selected>3 Colonne</option>
                                        <option value="4">4 Colonne</option>
                                    </select>
                                </div>
                            </div>
                            <div class="text-right mt-4">
                                <button type="submit" class="bg-indigo-600 text-white font-bold py-2 px-6 rounded shadow">Salva Widget Strutture</button>
                            </div>
                        </form>
                    </div>

                    <!-- Form Map -->
                    <div x-show="activeTab === 'map'" style="display: none;" class="bg-indigo-50 p-6 rounded-lg border border-indigo-100 shadow-inner">
                        <form action="{{ route('admin.global-widgets.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="tipo" value="map">
                            <div class="mb-4 bg-indigo-50/30 p-3 rounded border border-indigo-100/50">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Globale * [INGLESE]</label>
                                <input type="text" name="titolo_en"  class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none" placeholder="es. La nostra posizione">
                            </div>
@endif

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Codice Embed Google Maps (iframe) *</label>
                                <textarea name="data[embed_code]" required rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none text-xs" placeholder='Incolla qui il codice <iframe src="..."></iframe>'></textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Altezza (px)</label>
                                    <input type="number" name="data[height]" value="450" class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none">
                                </div>
                                <div class="flex items-center mt-6">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="data[full_width]" value="1" class="form-checkbox h-5 w-5 text-indigo-600">
                                        <span class="ml-2 text-gray-700 text-sm font-bold">Tutta Larghezza (Full Width)</span>
                                    </label>
                                </div>
                            </div>
                            <div class="text-right mt-4">
                                <button type="submit" class="bg-indigo-600 text-white font-bold py-2 px-6 rounded shadow">Salva Widget Mappa</button>
                            </div>
                        </form>
                    </div>

                    <!-- Form Section Grid -->
                    <div x-show="activeTab === 'grid'" style="display: none;" class="bg-indigo-50 p-6 rounded-lg border border-indigo-100 shadow-inner">
                        <form action="{{ route('admin.global-widgets.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="tipo" value="section_grid">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Griglia *</label>
                                <input type="text" name="titolo" required class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                            </div>
@if($englishEnabled)
<div class="flex items-center mt-6">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="data[full_width]" value="1" class="form-checkbox h-5 w-5 text-indigo-600">
                                        <span class="ml-2 text-gray-700 text-sm font-bold">Tutta Larghezza (Full Width)</span>
                                    </label>
                                </div>
                            </div>
                            <div class="text-right mt-4">
                                <button type="submit" class="bg-indigo-600 text-white font-bold py-2 px-6 rounded shadow">Salva Widget Mappa</button>
                            </div>
                        </form>
                    </div>

                    <!-- Form Section Grid -->
                    <div x-show="activeTab === 'grid'" style="display: none;" class="bg-indigo-50 p-6 rounded-lg border border-indigo-100 shadow-inner">
                        <form action="{{ route('admin.global-widgets.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="tipo" value="section_grid">
                            <div class="mb-4 bg-indigo-50/30 p-3 rounded border border-indigo-100/50">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Griglia * [INGLESE]</label>
                                <input type="text" name="titolo_en"  class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                            </div>
@endif

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Sezione Sorgente *</label>
                                <select name="data[source_section_id]" required class="shadow border rounded w-full py-2 px-3">
                                    <option value="">-- Seleziona --</option>
                                    @foreach(\App\Models\Section::all() as $sez)
                                        <option value="{{ $sez->id }}">{{ $sez->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Numero Articoli (Limite) *</label>
                                    <select name="data[limit]" required class="shadow border rounded w-full py-2 px-3">
                                        @for($i=1; $i<=20; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Elementi per Riga *</label>
                                    <select name="data[columns]" required class="shadow border rounded w-full py-2 px-3">
                                        <option value="1">1 Colonna</option>
                                        <option value="2">2 Colonne</option>
                                        <option value="3" selected>3 Colonne</option>
                                        <option value="4">4 Colonne</option>
                                    </select>
                                </div>
                            </div>
                            <div class="text-right mt-4">
                                <button type="submit" class="bg-indigo-600 text-white font-bold py-2 px-6 rounded shadow">Salva Griglia Globale</button>
                            </div>
                        </form>
                    </div>

                    <!-- Form Info Blocks -->
                    <div x-show="activeTab === 'info_blocks'" style="display: none;" class="bg-indigo-50 p-6 rounded-lg border border-indigo-100 shadow-inner">
                        <form action="{{ route('admin.global-widgets.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="tipo" value="info_blocks">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Globale *</label>
                                <input type="text" name="titolo" required class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none">
                            </div>
@if($englishEnabled)
<div class="mb-4 bg-indigo-50/30 p-3 rounded border border-indigo-100/50">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Sezione Sorgente *</label>
                                <select name="data[source_section_id]"  class="shadow border rounded w-full py-2 px-3">
                                    <option value="">-- Seleziona --</option>
                                    @foreach(\App\Models\Section::all() as $sez)
                                        <option value="{{ $sez->id }}">{{ $sez->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Numero Articoli (Limite) *</label>
                                    <select name="data[limit]"  class="shadow border rounded w-full py-2 px-3">
                                        @for($i=1; $i<=20; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Elementi per Riga *</label>
                                    <select name="data[columns]"  class="shadow border rounded w-full py-2 px-3">
                                        <option value="1">1 Colonna</option>
                                        <option value="2">2 Colonne</option>
                                        <option value="3" selected>3 Colonne</option>
                                        <option value="4">4 Colonne</option>
                                    </select>
                                </div>
                            </div>
                            <div class="text-right mt-4">
                                <button type="submit" class="bg-indigo-600 text-white font-bold py-2 px-6 rounded shadow">Salva Griglia Globale</button>
                            </div>
                        </form>
                    </div>

                    <!-- Form Info Blocks -->
                    <div x-show="activeTab === 'info_blocks'" style="display: none;" class="bg-indigo-50 p-6 rounded-lg border border-indigo-100 shadow-inner">
                        <form action="{{ route('admin.global-widgets.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="tipo" value="info_blocks">
                            <div class="mb-4 bg-indigo-50/30 p-3 rounded border border-indigo-100/50">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Globale * [INGLESE]</label>
                                <input type="text" name="titolo_en"  class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none">
                            </div>
@endif

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Numero Colonne (Desktop) *</label>
                                <select name="data[columns]" required class="shadow border rounded w-full md:w-1/4 py-2 px-3 focus:outline-none">
                                    <option value="1">1 Colonna</option>
                                    <option value="2">2 Colonne</option>
                                    <option value="3" selected>3 Colonne</option>
                                    <option value="4">4 Colonne</option>
                                    <option value="6">6 Colonne</option>
                                </select>
                            </div>
                            
                            <div id="info-blocks-container" class="space-y-4 mb-4">
                                <div class="bg-white p-4 rounded border border-gray-200 shadow-sm info-block-row">
                                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                        <div class="md:col-span-4">
                                            <label class="block text-gray-700 text-xs font-bold mb-1">Icona / Immagine</label>
                                            <div class="flex">
                                                <input type="text" name="data[items][0][image]" readonly placeholder="Scegli icona..." class="shadow border rounded-l w-full py-2 px-3 bg-gray-50 focus:outline-none text-xs">
                                                <button type="button" class="btn-sfoglia-block bg-gray-200 hover:bg-gray-300 px-3 rounded-r border border-l-0 text-xs">📸</button>
                                            </div>
                                        </div>
                                        <div class="md:col-span-7">
                                            <label class="block text-gray-700 text-xs font-bold mb-1">Testo del blocco (Accetta HTML)</label>
                                            <textarea name="data[items][0][text]" rows="2" class="shadow border rounded w-full py-2 px-3 focus:outline-none text-xs" placeholder="Inserisci testo..."></textarea>
                                        </div>
                                        <div class="md:col-span-1 flex items-center justify-center">
                                            <button type="button" class="text-red-100 opacity-20 pointer-events-none" title="Non eliminabile">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between mt-4">
                                <button type="button" id="btn-aggiungi-block" class="text-indigo-600 font-bold hover:text-indigo-800 text-sm flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> Aggiungi un altro blocco
                                </button>
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-6 rounded shadow focus:outline-none focus:shadow-outline">Salva Widget Blocchi</button>
                            </div>
                        </form>
                    </div>

                    <!-- Form Image Text Image -->
                    <div x-show="activeTab === 'image_text_image'" style="display: none;" class="bg-indigo-50 p-6 rounded-lg border border-indigo-100 shadow-inner">
                        <form action="{{ route('admin.global-widgets.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="tipo" value="image_text_image">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Globale Widget *</label>
                                <input type="text" name="titolo" required class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                            </div>
@if($englishEnabled)
<div class="mb-4 bg-indigo-50/30 p-3 rounded border border-indigo-100/50">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Globale Widget * [INGLESE]</label>
                                <input type="text" name="titolo_en"  class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                            </div>
@endif

                            <hr class="my-6 border-indigo-200">
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Elemento Sinistra -->
                                <div class="bg-white p-4 rounded shadow-sm border">
                                    <h3 class="font-bold text-gray-700 mb-3 text-center border-b pb-2">Sinistra (Foto/Video)</h3>
                                    <div class="mb-3">
                                        <label class="block text-xs text-gray-600 font-bold mb-1">Immagine</label>
                                        <div class="flex relative rounded-md shadow-sm">
                                            <input type="text" id="iti_img_left" name="data[img_left]" readonly class="shadow border rounded-l w-full py-1 text-sm px-2 bg-gray-50 focus:outline-none">
                                            <button type="button" class="btn-sfoglia-iti relative inline-flex items-center px-2 py-1 border border-gray-300 text-xs font-medium rounded-r-md text-gray-700 bg-gray-200" data-target="iti_img_left">Foto</button>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="block text-xs text-gray-600 font-bold mb-1">Video (Opzionale)</label>
                                        <div class="flex relative rounded-md shadow-sm">
                                            <input type="text" id="iti_video_left" name="data[video_left]" readonly class="shadow border rounded-l w-full py-1 text-sm px-2 bg-gray-50 focus:outline-none">
                                            <button type="button" class="btn-sfoglia-iti relative inline-flex items-center px-2 py-1 border border-gray-300 text-xs font-medium rounded-r-md text-gray-700 bg-gray-200" data-target="iti_video_left">Video</button>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="block text-xs text-gray-600 font-bold mb-1">Link Cliccabile (Opzionale)</label>
                                        <input type="url" name="data[link_left]" placeholder="https://..." class="shadow border rounded w-full py-1 text-sm px-2 focus:outline-none">
                                    </div>
                                </div>

                                <!-- Elemento Centrale (Testo) -->
                                <div class="bg-white p-4 rounded shadow-sm border">
                                    <h3 class="font-bold text-gray-700 mb-3 text-center border-b pb-2">Testo Centrale</h3>
                                    <div class="mb-3">
                                        <label class="block text-xs text-gray-600 font-bold mb-1">Titolo in Evidenza</label>
                                        <input type="text" name="data[center_title]" class="shadow border rounded w-full py-1 text-sm px-2 focus:outline-none">
                                    </div>
                                    <div class="mb-3">
                                        <label class="block text-xs text-gray-600 font-bold mb-1">Sottotitolo / Descrizione</label>
                                        <textarea name="data[center_subtitle]" rows="3" class="shadow border rounded w-full py-1 text-sm px-2 focus:outline-none"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="block text-xs text-gray-600 font-bold mb-1">Colore Testo (HEX)</label>
                                        <div class="flex items-center space-x-2">
                                            <input type="color" name="data[text_color]" value="#111827" class="h-8 w-8 border rounded cursor-pointer">
                                        </div>
                                    </div>
                                </div>

                                <!-- Elemento Destra -->
                                <div class="bg-white p-4 rounded shadow-sm border">
                                    <h3 class="font-bold text-gray-700 mb-3 text-center border-b pb-2">Destra (Foto/Video)</h3>
                                    <div class="mb-3">
                                        <label class="block text-xs text-gray-600 font-bold mb-1">Immagine</label>
                                        <div class="flex relative rounded-md shadow-sm">
                                            <input type="text" id="iti_img_right" name="data[img_right]" readonly class="shadow border rounded-l w-full py-1 text-sm px-2 bg-gray-50 focus:outline-none">
                                            <button type="button" class="btn-sfoglia-iti relative inline-flex items-center px-2 py-1 border border-gray-300 text-xs font-medium rounded-r-md text-gray-700 bg-gray-200" data-target="iti_img_right">Foto</button>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="block text-xs text-gray-600 font-bold mb-1">Video (Opzionale)</label>
                                        <div class="flex relative rounded-md shadow-sm">
                                            <input type="text" id="iti_video_right" name="data[video_right]" readonly class="shadow border rounded-l w-full py-1 text-sm px-2 bg-gray-50 focus:outline-none">
                                            <button type="button" class="btn-sfoglia-iti relative inline-flex items-center px-2 py-1 border border-gray-300 text-xs font-medium rounded-r-md text-gray-700 bg-gray-200" data-target="iti_video_right">Video</button>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="block text-xs text-gray-600 font-bold mb-1">Link Cliccabile (Opzionale)</label>
                                        <input type="url" name="data[link_right]" placeholder="https://..." class="shadow border rounded w-full py-1 text-sm px-2 focus:outline-none">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-right mt-6">
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-6 rounded shadow">Salva Widget Foto-Testo-Foto</button>
                            </div>
                        </form>
                    </div>

                    <!-- Form Booking Search -->
                    <div x-show="activeTab === 'booking_search'" style="display: none;" class="bg-indigo-50 p-6 rounded-lg border border-indigo-100 shadow-inner">
                        <form action="{{ route('admin.global-widgets.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="tipo" value="booking_search">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Globale Widget *</label>
                                <input type="text" name="titolo" required class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                            </div>
@if($englishEnabled)
<div class="mb-4 bg-indigo-50/30 p-3 rounded border border-indigo-100/50">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Globale Widget * [INGLESE]</label>
                                <input type="text" name="titolo_en"  class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                            </div>
@endif

                            
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">
                                            Questo widget mostrerà un motore di ricerca disponibilità (Check-in, Check-out, Ospiti). Sarà visibile sul sito pubblico solo se il modulo <strong>Booking</strong> è abilitato nelle impostazioni generali.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Testo in Evidenza (Titolo Interno)</label>
                                    <input type="text" name="data[title]" placeholder="es. Trova la tua struttura perfetta" class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                                </div>
@if($englishEnabled)
<div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Testo in Evidenza (Titolo Interno) [INGLESE]</label>
                                    <input type="text" name="data[title_en]" placeholder="es. Trova la tua struttura perfetta" class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                                </div>
@endif

                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Sottotitolo</label>
                                    <input type="text" name="data[subtitle]" placeholder="es. Inserisci le date per verificare la disponibilità" class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                                </div>
@if($englishEnabled)
<div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Sottotitolo [INGLESE]</label>
                                    <input type="text" name="data[subtitle_en]" placeholder="es. Inserisci le date per verificare la disponibilità" class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                                </div>
@endif

                            </div>

                            <div class="text-right mt-6">
                                <button type="submit" class="bg-indigo-600 text-white font-bold py-2 px-6 rounded shadow">Salva Motore di Ricerca</button>
                            </div>
                        </form>
                    </div>

                    @if($shop_enabled)
                        <!-- Form Shop Collection -->
                        <div x-show="activeTab === 'shop_collection'" style="display: none;" class="bg-orange-50 p-6 rounded-lg border border-orange-100 shadow-inner">
                            <form action="{{ route('admin.global-widgets.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="tipo" value="shop_collection">
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Globale *</label>
                                    <input type="text" name="titolo" required class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline">
                                </div>
@if($englishEnabled)
<div class="mb-4 bg-indigo-50/30 p-3 rounded border border-indigo-100/50">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Globale * [INGLESE]</label>
                                    <input type="text" name="titolo_en"  class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline">
                                </div>
@endif

                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Seleziona Collezione *</label>
                                        <select name="data[shop_collection_id]" required class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                                            <option value="">-- Seleziona una Collezione --</option>
                                            @foreach($shop_collections as $col)
                                                <option value="{{ $col->id }}">{{ $col->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Limite Prodotti *</label>
                                        <input type="number" name="data[limit]" value="4" min="1" max="20" class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none">
                                    </div>
                                </div>
                                <div class="text-right mt-4">
                                    <button type="submit" class="bg-orange-600 hover:bg-orange-800 text-white font-bold py-2 px-6 rounded shadow focus:outline-none">Salva Widget Collezione</button>
                                </div>
                            </form>
                        </div>

                        <!-- Form Shop Featured Products -->
                        <div x-show="activeTab === 'shop_products'" style="display: none;" class="bg-orange-50 p-6 rounded-lg border border-orange-100 shadow-inner" x-data="{ selectionMode: 'manual' }">
                            <form action="{{ route('admin.global-widgets.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="tipo" value="shop_featured_products">
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Globale *</label>
                                    <input type="text" name="titolo" required class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none">
                                </div>
@if($englishEnabled)
<div class="mb-4 bg-indigo-50/30 p-3 rounded border border-indigo-100/50">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Globale * [INGLESE]</label>
                                    <input type="text" name="titolo_en"  class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none">
                                </div>
@endif


                                <div class="mb-6">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Modalità di selezione</label>
                                    <div class="flex space-x-4">
                                        <label class="inline-flex items-center">
                                            <input type="radio" x-model="selectionMode" name="data[mode]" value="manual" class="form-radio text-orange-600">
                                            <span class="ml-2 text-sm">Selezione Manuale</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" x-model="selectionMode" name="data[mode]" value="category" class="form-radio text-orange-600">
                                            <span class="ml-2 text-sm">Per Categoria</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Selezione Manuale -->
                                <div x-show="selectionMode === 'manual'" class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Seleziona i Prodotti da mostrare:</label>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2 bg-white p-4 rounded border max-h-60 overflow-y-auto shadow-inner">
                                        @foreach($shop_products as $p)
                                            <label class="flex items-center p-1 hover:bg-orange-50 rounded cursor-pointer transition-colors">
                                                <input type="checkbox" name="data[product_ids][]" value="{{ $p->id }}" class="rounded border-gray-300 text-orange-600">
                                                <span class="ml-2 text-xs text-gray-700 truncate" title="{{ $p->nome }}">{{ $p->nome }}</span>
                                            </label>
                                        @endforeach
                                        @if($shop_products->isEmpty())
                                            <p class="text-xs text-gray-500 italic">Nessun prodotto disponibile nello shop.</p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Selezione Categoria -->
                                <div x-show="selectionMode === 'category'" class="grid grid-cols-2 gap-4 mb-4" style="display: none;">
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Categoria/Sottocategoria *</label>
                                        <select name="data[shop_category_id]" x-bind:required="selectionMode === 'category'" class="shadow border rounded w-full py-2 px-3 focus:outline-none">
                                            <option value="">-- Scegli Categoria --</option>
                                            @foreach($shop_categories as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->nome }}</option>
                                                @foreach($cat->children as $sub)
                                                    <option value="{{ $sub->id }}">-- {{ $sub->nome }}</option>
                                                @endforeach
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Numero Prodotti *</label>
                                        <input type="number" name="data[cat_limit]" value="4" min="1" max="20" class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none">
                                    </div>
                                </div>

                                <div class="text-right mt-4">
                                    <button type="submit" class="bg-orange-600 hover:bg-orange-800 text-white font-bold py-2 px-6 rounded shadow focus:outline-none">Salva Widget Prodotti</button>
                                </div>
                            </form>
                        </div>

                        <!-- Form Shop Brands -->
                        <div x-show="activeTab === 'shop_brands'" style="display: none;" class="bg-orange-50 p-6 rounded-lg border border-orange-100 shadow-inner">
                            <form action="{{ route('admin.global-widgets.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="tipo" value="shop_brands">
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Globale *</label>
                                    <input type="text" name="titolo" required class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none" placeholder="Es. Le nostre Marche">
                                </div>
@if($englishEnabled)
<div class="mb-4 bg-indigo-50/30 p-3 rounded border border-indigo-100/50">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Titolo Globale * [INGLESE]</label>
                                    <input type="text" name="titolo_en"  class="shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none" placeholder="Es. Le nostre Marche">
                                </div>
@endif

                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Stile Visualizzazione</label>
                                    <select name="data[style]" class="shadow border rounded w-full md:w-1/4 py-2 px-3 focus:outline-none">
                                        <option value="grid">Griglia Loghi</option>
                                        <option value="carousel">Slider Carosello</option>
                                    </select>
                                </div>
                                <div class="text-right mt-4">
                                    <button type="submit" class="bg-orange-600 hover:bg-orange-800 text-white font-bold py-2 px-6 rounded shadow focus:outline-none">Salva Widget Marche</button>
                                </div>
                            </form>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script>
            let fmActiveTarget = null;
            let fmActiveInput = null;

            document.addEventListener("DOMContentLoaded", function() {
                const btnVideo = document.getElementById('btn-sfoglia-video');
                if (btnVideo) {
                    btnVideo.addEventListener('click', (event) => {
                        event.preventDefault();
                        fmActiveInput = document.getElementById('video_url');
                        window.open('{{ url('file-manager/fm-button') }}', 'fm', 'width=1400,height=800');
                    });
                }

                const btnSingle = document.getElementById('btn-sfoglia-single');
                if (btnSingle) {
                    btnSingle.addEventListener('click', (event) => {
                        event.preventDefault();
                        fmActiveInput = document.getElementById('single_block_image');
                        window.open('{{ url('file-manager/fm-button') }}', 'fm', 'width=1400,height=800');
                    });
                }
                const btnSingleVideo = document.getElementById('btn-sfoglia-single-video');
                if (btnSingleVideo) {
                    btnSingleVideo.addEventListener('click', (event) => {
                        event.preventDefault();
                        fmActiveInput = document.getElementById('single_block_video');
                        window.open('{{ url('file-manager/fm-button') }}', 'fm', 'width=1400,height=800');
                    });
                }

                const galleryContainer = document.getElementById('gallery-container');
                let photoIndex = 1;

                if (document.getElementById('btn-aggiungi-foto')) {
                    document.getElementById('btn-aggiungi-foto').addEventListener('click', () => {
                        const row = document.createElement('div');
                        row.className = 'flex items-center space-x-2 gallery-row mt-3';
                        row.innerHTML = `
                            <div class="flex-1 flex flex-col space-y-2 text-sm">
                                <div class="flex">
                                    <input type="text" name="data[photos][${photoIndex}][url]" readonly required placeholder="URL Foto" class="shadow border rounded-l w-full py-2 px-3 bg-white focus:outline-none text-sm">
                                    <button type="button" class="btn-sfoglia-gallery bg-gray-200 hover:bg-gray-300 font-bold py-2 px-4 shadow border border-l-0">📸 Foto</button>
                                </div>
                                <div class="flex">
                                    <input type="text" name="data[photos][${photoIndex}][video_url]" readonly placeholder="URL Video (opzionale)" class="shadow border rounded-l w-full py-2 px-3 bg-white focus:outline-none text-sm">
                                    <button type="button" class="btn-sfoglia-video-gallery bg-gray-200 hover:bg-gray-300 font-bold py-2 px-4 shadow border border-l-0">🎥 Video</button>
                                </div>
                            </div>
                            <div class="flex-1">
                                <input type="text" name="data[photos][${photoIndex}][link]" placeholder="Link Opzionale" class="shadow border rounded w-full py-2 px-3 h-full focus:outline-none text-sm">
                            </div>
                            <button type="button" class="btn-rimuovi-foto text-red-500 hover:text-red-700"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg></button>
                        `;
                        galleryContainer.appendChild(row);
                        photoIndex++;
                    });
                }

                if (galleryContainer) {
                    galleryContainer.addEventListener('click', (e) => {
                        if(e.target.closest('.btn-sfoglia-gallery')) {
                            e.preventDefault();
                            const btn = e.target.closest('.btn-sfoglia-gallery');
                            fmActiveInput = btn.previousElementSibling;
                            window.open('{{ url('file-manager/fm-button') }}', 'fm', 'width=1400,height=800');
                        }
                        if(e.target.closest('.btn-sfoglia-video-gallery')) {
                            e.preventDefault();
                            const btn = e.target.closest('.btn-sfoglia-video-gallery');
                            fmActiveInput = btn.previousElementSibling;
                            window.open('{{ url('file-manager/fm-button') }}', 'fm', 'width=1400,height=800');
                        }
                        if(e.target.closest('.btn-rimuovi-foto')) {
                            e.preventDefault();
                            if(document.querySelectorAll('.gallery-row').length > 1) {
                                e.target.closest('.gallery-row').remove();
                            } else {
                                alert('Almeno una slide necessaria.');
                            }
                        }
                    });
                }
                const itBottons = document.querySelectorAll('.btn-sfoglia-iti');
                itBottons.forEach(btn => {
                    btn.addEventListener('click', (event) => {
                        event.preventDefault();
                        fmActiveInput = document.getElementById(event.target.getAttribute('data-target'));
                        window.open('{{ url('file-manager/fm-button') }}', 'fm', 'width=1400,height=800');
                    });
                });

                // --- Widget Info Blocks: Dynamic Rows ---
                let blockIndex = 1;
                const btnAddBlock = document.getElementById('btn-aggiungi-block');
                if (btnAddBlock) {
                    btnAddBlock.addEventListener('click', (e) => {
                        e.preventDefault();
                        const blockContainer = document.getElementById('info-blocks-container');
                        if (!blockContainer) return;
                        const row = document.createElement('div');
                        row.className = 'bg-white p-4 rounded border border-gray-200 shadow-sm info-block-row mt-4';
                        row.innerHTML = `
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                <div class="md:col-span-4">
                                    <label class="block text-gray-700 text-xs font-bold mb-1">Icona / Immagine</label>
                                    <div class="flex">
                                        <input type="text" name="data[items][${blockIndex}][image]" readonly placeholder="Scegli icona..." class="shadow border rounded-l w-full py-2 px-3 bg-gray-50 focus:outline-none text-xs">
                                        <button type="button" class="btn-sfoglia-block bg-gray-200 hover:bg-gray-300 px-3 rounded-r border border-l-0 text-xs">📸</button>
                                    </div>
                                </div>
                                <div class="md:col-span-7">
                                    <label class="block text-gray-700 text-xs font-bold mb-1">Testo del blocco (Accetta HTML)</label>
                                    <textarea name="data[items][${blockIndex}][text]" rows="2" class="shadow border rounded w-full py-2 px-3 focus:outline-none text-xs" placeholder="Inserisci testo..."></textarea>
                                </div>
                                <div class="md:col-span-1 flex items-center justify-center">
                                    <button type="button" class="btn-rimuovi-block text-red-500 hover:text-red-700" title="Rimuovi Block">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                    </button>
                                </div>
                            </div>
                        `;
                        blockContainer.appendChild(row);
                        blockIndex++;
                    });
                }

                // Handling dynamic clicks for blocks
                document.body.addEventListener('click', (e) => {
                    const btnSfoglia = e.target.closest('.btn-sfoglia-block');
                    if (btnSfoglia) {
                        e.preventDefault();
                        fmActiveInput = btnSfoglia.previousElementSibling;
                        window.open('{{ url('file-manager/fm-button') }}', 'fm', 'width=1400,height=800');
                    }
                    const btnRimuovi = e.target.closest('.btn-rimuovi-block');
                    if (btnRimuovi) {
                        e.preventDefault();
                        btnRimuovi.closest('.info-block-row').remove();
                    }
                });

            });

            function fmSetLink($url) {
                if (fmActiveInput) {
                    // Fix absolute URL: Rimuove protocollo e dominio per avere il percorso relativo (/storage/...)
                    $url = $url.replace(/^https?:\/\/[^\/]+/, "");
                    fmActiveInput.value = $url;
                }
            }
        </script>
    @endpush
</x-app-layout>
