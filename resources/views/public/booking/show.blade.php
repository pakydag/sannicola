@extends('public.layouts.main')

@section('content')
    <div class="bg-gray-50 pb-16 mx-6 lg:mx-auto">
        @if(isset($section) && $section->immagine)
            <div class="relative bg-gray-50 h-64 flex items-end bg-cover bg-center bg-fixed px-6 lg:px-0" style="background-image: url('{{ asset($section->immagine) }}');">
                <div class="mx-auto max-w-7xl w-full bg-white rounded-t-lg px-6 lg:px-0">
                    <nav class="flex p-6 items-left text-sm font-medium text-gray-400">
                        <a href="{{ route('public.home') }}" class="hover:text-gray-900 transition">Home</a>
                        <svg class="h-5 w-5 shrink-0 text-gray-400 mx-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>
                        <a href="{{ route('public.booking.index') }}" class="hover:text-gray-900 transition">{{ app()->getLocale() === 'en' ? 'Book' : 'Prenota' }}</a>
                        <svg class="h-5 w-5 shrink-0 text-gray-400 mx-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>
                        <span>{{ $structure->nome }}</span>
                    </nav>
                </div>
            </div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-0 pb-12 bg-white">
        @else
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12">
                <nav class="flex mb-8 text-gray-500 text-sm" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2">
                        <li><a href="{{ route('public.home') }}" class="hover:text-indigo-600 transition">Home</a></li>
                        <li><svg class="h-4 w-4 mx-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
                        <li><a href="{{ route('public.booking.index') }}" class="hover:text-indigo-600 transition">{{ app()->getLocale() === 'en' ? 'All structures' : 'Tutte le strutture' }}</a></li>
                        <li><svg class="h-4 w-4 mx-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
                        <li class="font-bold text-gray-900">{{ $structure->nome }}</li>
                    </ol>
                </nav>
        @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

                <!-- Colonna Sinistra: Media & Descrizione -->
                <div class="lg:col-span-2 space-y-8">

                    <!-- Gallery -->
                    <style>
                        .hide-scroll::-webkit-scrollbar { display: none; }
                        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
                    </style>
                    <div x-data="{
                            activePhoto: '{{ $structure->photos->count() > 0 ? asset($structure->photos->first()->path) : '' }}',
                            photos: {{ json_encode($structure->photos->pluck('path')->map(fn($path) => asset($path))) }},
                            currentIndex: 0,
                            init() {
                                this.currentIndex = this.photos.indexOf(this.activePhoto);
                                if (this.currentIndex === -1) this.currentIndex = 0;
                            },
                            next() {
                                this.currentIndex = (this.currentIndex + 1) % this.photos.length;
                                this.activePhoto = this.photos[this.currentIndex];
                                this.scrollToThumb();
                            },
                            prev() {
                                this.currentIndex = (this.currentIndex - 1 + this.photos.length) % this.photos.length;
                                this.activePhoto = this.photos[this.currentIndex];
                                this.scrollToThumb();
                            },
                            setActive(idx) {
                                this.currentIndex = idx;
                                this.activePhoto = this.photos[idx];
                                this.scrollToThumb();
                            },
                            scrollToThumb() {
                                this.$nextTick(() => {
                                    if (this.$refs.thumbArray && this.$refs.thumbArray.children[this.currentIndex]) {
                                        this.$refs.thumbArray.children[this.currentIndex].scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                                    }
                                });
                            }
                        }" class="space-y-4">

                        <!-- PHOTo PRINCIPALE CON FRECCE -->
                        <div class="relative h-[500px] w-full rounded-lg overflow-hidden shadow-xl bg-gray-100 group">
                            @if($structure->photos->count() > 0)
                                <img :src="activePhoto" class="w-full h-full object-cover transition-opacity duration-300">

                                @if($structure->photos->count() > 1)
                                    <!-- Freccia Sinistra Copertina -->
                                    <button type="button" @click="prev" class="absolute left-4 top-1/2 -translate-y-1/2 p-3 bg-white/90 hover:bg-white rounded-full shadow-lg text-gray-800 transition-all opacity-0 group-hover:opacity-100 transform hover:scale-110 focus:outline-none" style="z-index: 20;">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                        </svg>
                                    </button>
                                    <!-- Freccia Destra Copertina -->
                                    <button type="button" @click="next" class="absolute right-4 top-1/2 -translate-y-1/2 p-3 bg-white/90 hover:bg-white rounded-full shadow-lg text-gray-800 transition-all opacity-0 group-hover:opacity-100 transform hover:scale-110 focus:outline-none" style="z-index: 20;">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                @endif

                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="h-20 w-20 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                        </div>

                        <!-- RIGA MINIATURE -->
                        @if($structure->photos->count() > 1)
                            <div class="relative flex items-center pt-1 group/thumbs">
                                <!-- Scroll Thumb Sinistra -->
                                <button type="button" @click="if($refs.thumbArray) $refs.thumbArray.scrollBy({left: -200, behavior: 'smooth'})" class="absolute left-0 z-10 bg-gradient-to-r from-white via-white to-transparent h-full px-2 flex items-center focus:outline-none transition-opacity opacity-0 group-hover/thumbs:opacity-100" style="width: 60px; margin-left: -4px;">
                                    <div class="bg-white p-1.5 rounded-full shadow-md hover:scale-110 transition-transform">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                                    </div>
                                </button>

                                <!-- Contenitore Miniature -->
                                <div x-ref="thumbArray" class="hide-scroll flex gap-2 overflow-x-auto scroll-smooth w-full px-8 py-1 snap-x snap-mandatory">
                                    @foreach($structure->photos as $index => $photo)
                                        <button @click="setActive({{ $index }})" class="flex-shrink-0 h-16 w-24 sm:h-20 sm:w-28 rounded-lg overflow-hidden transition-all hover:opacity-100 snap-center" :class="currentIndex === {{ $index }} ? 'ring-1 ring-indigo-600 opacity-100 scale-105 shadow-md' : 'opacity-60 grayscale-[30%]'">
                                            <img src="{{ asset($photo->path) }}" class="w-full h-full object-cover">
                                        </button>
                                    @endforeach
                                </div>

                                <!-- Scroll Thumb Destra -->
                                <button type="button" @click="if($refs.thumbArray) $refs.thumbArray.scrollBy({left: +200, behavior: 'smooth'})" class="absolute right-0 z-10 bg-gradient-to-l from-white via-white to-transparent h-full px-2 flex items-center justify-end focus:outline-none transition-opacity opacity-0 group-hover/thumbs:opacity-100" style="width: 60px; margin-right: -4px;">
                                    <div class="bg-white p-1.5 rounded-full shadow-md hover:scale-110 transition-transform">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                    </div>
                                </button>
                            </div>
                        @endif
                    </div>

                    <!-- Details -->
                    <div class="bg-white rounded-lg p-6">
                        <div class="titoli"><h1 class="text-4xl mb-6">{{ $structure->nome }}</h1></div>

                        <div class="flex flex-wrap gap-8 mb-6 pb-8 border-b border-gray-200">
                            <div class="flex items-center gap-2">
                                <div class="h-10 w-10 bg-gray-100 rounded-xl shadow-sm flex items-center justify-center primary">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-bold">{{ app()->getLocale() === 'en' ? 'Bedrooms' : 'Camere' }}</p>
                                    <p class="font-extrabold text-gray-900">{{ $structure->camere_letto }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="h-10 w-10 bg-gray-100 rounded-xl shadow-sm flex items-center justify-center primary">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-bold">{{ app()->getLocale() === 'en' ? 'Bathrooms' : 'Bagni' }}</p>
                                    <p class="font-extrabold text-gray-900">{{ $structure->bagni }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="h-10 w-10 bg-gray-100 rounded-xl shadow-sm flex items-center justify-center primary">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-bold">{{ app()->getLocale() === 'en' ? 'Max Guests' : 'Max Ospiti' }}</p>
                                    <p class="font-extrabold text-gray-900">{{ $structure->posti_totali }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="prose prose-indigo max-w-none text-gray-600 mb-12">
                            {!! $structure->descrizione !!}
                        </div>

                        <!-- Servizi e Amenità -->
                        @if($structure->services->count() > 0)
                            <div class="mt-12 border-t pt-12 titoli">
                                <h3 class="text-2xl mb-8">{{ app()->getLocale() === 'en' ? 'Services & Amenities' : 'Servizi e Dotazioni' }}</h3>
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                                    @foreach($structure->services as $service)
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 bg-indigo-50 rounded-xl flex items-center justify-center text-xl shrink-0 shadow-sm border border-indigo-100">
                                                {{ $service->icona ?: '✨' }}
                                            </div>
                                            <span class="text-gray-800 text-sm leading-tight">{{ $service->nome }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Colonna Destra: Prenotazione -->
                <div class="lg:col-span-1">
                    <div class="sticky top-14 p-8 bg-gray-30 rounded-xl">
                        @if($structure->prenotabile)
                        <div x-data="bookingForm()">
                            <div class="flex justify-between items-end">
                                <div>
                                    @if($structure->tipo_prezzo === 'fisso')
                                        <p class="text-xl font-extrabold text-gray-900 mb-6">{{ app()->getLocale() === 'en' ? 'Book Stay' : 'Prenota Soggiorno' }}</p>
                                    @else
                                        <p class="text-xl font-extrabold text-gray-900">{{ app()->getLocale() === 'en' ? 'Price per person' : 'Prezzo per persona' }}</p>
                                        <p class="text-gray-500 text-sm">{{ app()->getLocale() === 'en' ? 'per period' : 'per periodo' }}</p>
                                    @endif
                                </div>
                                <div class="text-right">
                                </div>
                            </div>

                            <form @submit.prevent="submitBooking" class="space-y-6">
                                <div class="grid grid-cols-2 gap-0 border rounded-xl overflow-hidden shadow-sm">
                                    <div class="border-b p-3 bg-gray-50">
                                        <label class="block text-[10px] font-bold uppercase text-gray-400">{{ app()->getLocale() === 'en' ? 'Arrival' : 'Arrivo' }}</label>
                                        <input type="date" x-model="startDate" @change="checkDates" class="w-full bg-transparent border-0 p-0 text-sm focus:ring-0" min="{{ date('Y-m-d') }}">
                                    </div>
                                    <div class="border-b border-l p-3 bg-gray-50">
                                        <label class="block text-[10px] font-bold uppercase text-gray-400">{{ app()->getLocale() === 'en' ? 'Departure' : 'Partenza' }}</label>
                                        <input type="date" x-model="endDate" @change="checkDates" class="w-full bg-transparent border-0 p-0 text-sm focus:ring-0" :min="startDate">
                                    </div>
                                    @if($structure->tipo_prezzo === 'persona' && $structure->variants->count() > 0)
                                        <div class="col-span-2 p-3 bg-gray-50 border-t">
                                            <label class="block text-[10px] font-bold uppercase text-gray-400">{{ app()->getLocale() === 'en' ? 'Guests' : 'Ospiti' }}</label>
                                            <div class="grid grid-cols-2 gap-4 mt-2">
                                                @foreach($structure->variants as $variant)
                                                    <div class="flex items-center justify-between bg-white p-2 rounded-lg border shadow-sm">
                                                        <span class="text-xs font-bold">{{ $variant->nome }}</span>
                                                        <select x-model.number="ospiti['{{ $variant->id }}']" @change="checkDates" class="bg-transparent border-0 p-0 text-sm focus:ring-0 font-extrabold w-12 text-right">
                                                            @for($i=0; $i<=$structure->posti_totali; $i++)
                                                                 <option value="{{ $i }}">{{ $i }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    @if($structure->extras->count() > 0)
                                        <div class="col-span-2 p-3 bg-gray-50 border-t">
                                            <label class="block text-[10px] font-bold uppercase text-gray-400 mb-2">{{ app()->getLocale() === 'en' ? 'Extra Services' : 'Servizi Extra' }}</label>
                                            <div class="space-y-2">
                                                @foreach($structure->extras as $extra)
                                                    <label class="flex items-center justify-between p-2 rounded-lg border bg-white cursor-pointer hover:border-indigo-300 transition-colors">
                                                        <div class="flex items-center gap-2">
                                                            <input type="checkbox" x-model="selectedExtras" value="{{ $extra->id }}" @change="checkDates" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                            <span class="text-xs text-gray-700">{{ $extra->nome }}</span>
                                                        </div>
                                                        <span class="text-xs text-gray-500">€{{ number_format($extra->prezzo, 2) }}{{ app()->getLocale() === 'en' ? '/d' : '/g' }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div x-show="totalDays > 0" x-transition class="space-y-3 py-4 text-sm" :class="isChecking ? 'animate-pulse opacity-50' : ''">
                                    <template x-if="isAvailable">
                                        <div class="space-y-3">
                                            <div class="flex justify-between items-center text-gray-600">
                                                @if(app()->getLocale() === 'en')
                                                    <span>Stay of <span x-text="totalDays"></span> nights</span>
                                                @else
                                                    <span>Soggiorno di <span x-text="totalDays"></span> notti</span>
                                                @endif
                                                <span class="font-bold text-gray-900">€<span x-text="totalPrice"></span></span>
                                            </div>
                                            <div class="flex justify-between items-center pt-3 border-t text-base">
                                                <span class="font-extrabold text-gray-900">{{ app()->getLocale() === 'en' ? 'Total' : 'Totale' }}</span>
                                                <span class="font-extrabold text-indigo-700 text-xl">€<span x-text="totalPrice"></span></span>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="!isAvailable && !isChecking && totalGuests <= {{ $structure->posti_totali }}">
                                        <div class="p-3 bg-red-50 border border-red-200 text-red-600 rounded-xl text-center font-bold">
                                            {{ app()->getLocale() === 'en' ? 'The selected dates are not available.' : 'Le date selezionate non sono disponibili.' }}
                                        </div>
                                    </template>
                                    <template x-if="totalGuests > {{ $structure->posti_totali }}">
                                        <div class="p-3 bg-orange-50 border border-orange-200 text-orange-600 rounded-xl text-center font-bold">
                                            @if(app()->getLocale() === 'en')
                                                The total number of reserved guests (<span x-text="totalGuests"></span>) exceeds the structure's capacity ({{ $structure->posti_totali }}).
                                            @else
                                                Il numero totale di ospiti riservati (<span x-text="totalGuests"></span>) supera la capacità della struttura ({{ $structure->posti_totali }}).
                                            @endif
                                        </div>
                                    </template>
                                </div>

                                <button type="submit"
                                        :disabled="!isAvailable || totalDays <= 0 || isChecking || parseFloat(totalPrice) <= 0 || totalGuests > {{ $structure->posti_totali }}"
                                        x-show="totalDays > 0 && (isAvailable || totalGuests > {{ $structure->posti_totali }}) && (parseFloat(totalPrice) > 0 || totalGuests > {{ $structure->posti_totali }})"
                                        class="w-full py-4 rounded-2xl font-extrabold text-lg shadow-lg transition-all duration-300 transform active:scale-95 bg-indigo-600 text-white hover:bg-indigo-700 shadow-indigo-200 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span x-show="!isChecking">{{ app()->getLocale() === 'en' ? 'Book Now' : 'Prenota Ora' }}</span>
                                    <span x-show="isChecking">{{ app()->getLocale() === 'en' ? 'Checking...' : 'Verifica in corso...' }}</span>
                                </button>
                            </form>

                            <p class="text-center text-[11px] text-gray-400 mt-6 px-4">
                                {{ app()->getLocale() === 'en' ? 'You will not be charged anything at this time. You will be redirected to the payment page.' : 'Non ti verrà addebitato nulla in questo momento. Verrai reindirizzato alla pagina di pagamento.' }}
                            </p>
                        </div>
                        @else
                            <div class="text-center py-12">
                                <div class="h-16 w-16 bg-gray-50 text-gray-400 rounded-2xl flex items-center justify-center mx-auto mb-6">
                                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <h3 class="text-xl font-extrabold text-gray-900 mb-2">{{ app()->getLocale() === 'en' ? 'Not Bookable' : 'Non Prenotabile' }}</h3>
                                <p class="text-gray-500 font-bold leading-relaxed">
                                    {{ app()->getLocale() === 'en' ? 'This structure is currently not bookable.' : 'Questa struttura non è al momento prenotabile.' }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function bookingForm() {
        return {
            startDate: '{{ request('start_date', '') }}',
            endDate: '{{ request('end_date', '') }}',
            ospiti: {!! json_encode($structure->variants->pluck('id')->mapWithKeys(fn($id) => [(string)$id => 0])) !!},
            totalDays: 0,
            totalPrice: 0,
            selectedExtras: [],
            isChecking: false,
            isAvailable: true,
            bookedDates: @json($bookedDates),
            tipoPrezzo: '{{ $structure->tipo_prezzo }}',

            init() {
                // Pre-fill guests from request if available
                const reqAdulti = parseInt('{{ request('adulti', 0) }}');
                const reqBambini = parseInt('{{ request('bambini', 0) }}');

                if (reqAdulti > 0 || reqBambini > 0) {
                    const variants = @json($structure->variants);
                    if (variants.length > 0) {
                        // Attempt to find "Adulto" and "Bambino" variants
                        let adultVariant = variants.find(v => v.nome.toLowerCase().includes('adult'));
                        let childVariant = variants.find(v => v.nome.toLowerCase().includes('bambin') || v.nome.toLowerCase().includes('child'));

                        if (reqAdulti > 0) {
                            const vId = adultVariant ? adultVariant.id : variants[0].id;
                            this.ospiti[vId] = reqAdulti;
                        }
                        if (reqBambini > 0 && childVariant) {
                            this.ospiti[childVariant.id] = reqBambini;
                        } else if (reqBambini > 0 && !adultVariant && variants.length > 1) {
                            // If no child variant found but 2nd variant exists, maybe it's the child one
                            this.ospiti[variants[1].id] = reqBambini;
                        }
                    }
                }

                // Trigger check if dates are present
                if (this.startDate && this.endDate) {
                    this.checkDates();
                }
            },

            get totalGuests() {
                return Object.values(this.ospiti).reduce((a, b) => (parseInt(a) || 0) + (parseInt(b) || 0), 0);
            },

            checkDates() {
                if (this.startDate && this.endDate) {
                    const start = new Date(this.startDate);
                    const end = new Date(this.endDate);

                    if (end > start) {
                        const diffTime = Math.abs(end - start);
                        this.totalDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                        this.verifyAvailability();
                    } else {
                        this.totalDays = 0;
                    }
                }
            },

            verifyAvailability() {
                // Se il totale ospiti supera la capacità, non verifichiamo nemmeno la disponibilità
                if (this.totalGuests > {{ $structure->posti_totali }}) {
                    this.isAvailable = false;
                    this.totalPrice = 0;
                    return;
                }

                this.isChecking = true;
                this.isAvailable = true;

                // Client side quick check
                const start = new Date(this.startDate);
                const end = new Date(this.endDate);

                for (let d = new Date(start); d < end; d.setDate(d.getDate() + 1)) {
                    let dateStr = d.toISOString().split('T')[0];
                    if (this.bookedDates.includes(dateStr)) {
                        this.isAvailable = false;
                        this.isChecking = false;
                        return;
                    }
                }

                // Build ospiti payload — convert keys to integers for server
                const ospitiPayload = {};
                for (const key in this.ospiti) {
                    ospitiPayload[key] = parseInt(this.ospiti[key]) || 0;
                }

                // Server side double check & Price calculation
                fetch('{{ route("public.booking.check") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        structure_id: {{ $structure->id }},
                        start_date: this.startDate,
                        end_date: this.endDate,
                        ospiti: ospitiPayload,
                        extras: this.selectedExtras
                    })
                })
                .then(res => res.json())
                .then(data => {
                    this.isAvailable = data.available;
                    this.totalPrice = data.total_price;
                    this.isChecking = false;
                })
                .catch(() => {
                    this.isChecking = false;
                });
            },

            submitBooking() {
                if (!this.isAvailable || this.totalDays <= 0 || parseFloat(this.totalPrice) <= 0) return;

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("public.booking.reserve") }}';

                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                form.appendChild(csrf);

                // Add structure_id
                const structureInput = document.createElement('input');
                structureInput.type = 'hidden';
                structureInput.name = 'structure_id';
                structureInput.value = '{{ $structure->id }}';
                form.appendChild(structureInput);

                // Add dates
                const startInput = document.createElement('input');
                startInput.type = 'hidden';
                startInput.name = 'start_date';
                startInput.value = this.startDate;
                form.appendChild(startInput);

                const endInput = document.createElement('input');
                endInput.type = 'hidden';
                endInput.name = 'end_date';
                endInput.value = this.endDate;
                form.appendChild(endInput);

                // Add ospiti
                for (const variantId in this.ospiti) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `ospiti[${variantId}]`;
                    input.value = this.ospiti[variantId];
                    form.appendChild(input);
                }

                // Add extras
                this.selectedExtras.forEach(extraId => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'extras[]';
                    input.value = extraId;
                    form.appendChild(input);
                });

                // Add total
                const totalInput = document.createElement('input');
                totalInput.type = 'hidden';
                totalInput.name = 'totale';
                totalInput.value = this.totalPrice;
                form.appendChild(totalInput);

                document.body.appendChild(form);
                form.submit();
            }
        }
    }
</script>
@endpush
