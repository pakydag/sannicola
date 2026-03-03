@extends('public.layouts.main')

@section('content')
    <div class="bg-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <nav class="flex mb-8 text-gray-500 text-sm" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2">
                    <li><a href="{{ route('public.booking.index') }}" class="hover:text-indigo-600 transition">Tutte le strutture</a></li>
                    <li>
                        <svg class="h-4 w-4 mx-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </li>
                    <li class="font-bold text-gray-900">{{ $structure->nome }}</li>
                </ol>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                
                <!-- Colonna Sinistra: Media & Descrizione -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Gallery -->
                    <div x-data="{ activePhoto: '{{ $structure->photos->count() > 0 ? asset($structure->photos->first()->path) : '' }}' }" class="space-y-4">
                        <div class="h-[500px] w-full rounded-3xl overflow-hidden shadow-xl bg-gray-100">
                            @if($structure->photos->count() > 0)
                                <img :src="activePhoto" class="w-full h-full object-cover transition-opacity duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="h-20 w-20 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                        </div>
                        
                        @if($structure->photos->count() > 1)
                            <div class="flex gap-4 overflow-x-auto pb-2">
                                @foreach($structure->photos as $photo)
                                    <button @click="activePhoto = '{{ asset($photo->path) }}'" class="flex-shrink-0 h-24 w-36 rounded-xl overflow-hidden border-2 transition-all hover:opacity-80" :class="activePhoto === '{{ asset($photo->path) }}' ? 'border-indigo-600 scale-105' : 'border-transparent'">
                                        <img src="{{ asset($photo->path) }}" class="w-full h-full object-cover">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Details -->
                    <div class="bg-gray-50 rounded-3xl p-8 border border-gray-100">
                        <h1 class="text-4xl font-extrabold text-gray-900 mb-6">{{ $structure->nome }}</h1>
                        
                        <div class="flex flex-wrap gap-8 mb-8 pb-8 border-b border-gray-200">
                            <div class="flex items-center gap-2">
                                <div class="h-10 w-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-indigo-600">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-bold">Camere</p>
                                    <p class="font-extrabold text-gray-900">{{ $structure->camere_letto }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="h-10 w-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-indigo-600">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-bold">Bagni</p>
                                    <p class="font-extrabold text-gray-900">{{ $structure->bagni }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="h-10 w-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-indigo-600">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-bold">Max Ospiti</p>
                                    <p class="font-extrabold text-gray-900">{{ $structure->posti_totali }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="prose prose-indigo max-w-none text-gray-600">
                            {!! $structure->descrizione !!}
                        </div>
                    </div>
                </div>

                <!-- Colonna Destra: Prenotazione -->
                <div class="lg:col-span-1">
                    <div class="sticky top-8 bg-white rounded-3xl p-8 shadow-2xl border border-gray-100" x-data="bookingForm()">
                        <div class="flex justify-between items-end mb-8">
                            <div>
                                <p class="text-3xl font-extrabold text-gray-900">€{{ number_format($structure->costo_al_giorno, 2, ',', '.') }}</p>
                                <p class="text-gray-500 text-sm">per notte</p>
                            </div>
                            <div class="text-right">
                                <span class="text-yellow-400">★★★★★</span>
                                <p class="text-xs text-gray-400 font-bold uppercase">5.0 Recensioni</p>
                            </div>
                        </div>

                        <form @submit.prevent="submitBooking" class="space-y-6">
                            <div class="grid grid-cols-2 gap-0 border rounded-xl overflow-hidden shadow-sm">
                                <div class="border-b p-3 bg-gray-50">
                                    <label class="block text-[10px] font-bold uppercase text-gray-400">Arrivo</label>
                                    <input type="date" x-model="startDate" @change="checkDates" class="w-full bg-transparent border-0 p-0 text-sm focus:ring-0 font-bold" min="{{ date('Y-m-d') }}">
                                </div>
                                <div class="border-b border-l p-3 bg-gray-50">
                                    <label class="block text-[10px] font-bold uppercase text-gray-400">Partenza</label>
                                    <input type="date" x-model="endDate" @change="checkDates" class="w-full bg-transparent border-0 p-0 text-sm focus:ring-0 font-bold" :min="startDate">
                                </div>
                                <div class="col-span-2 p-3 bg-gray-50">
                                    <label class="block text-[10px] font-bold uppercase text-gray-400">Ospiti</label>
                                    <div class="flex justify-between items-center mt-1">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs">Adulti</span>
                                            <select x-model="adults" class="bg-transparent border-0 p-0 text-sm focus:ring-0 font-extrabold">
                                                @for($i=1; $i<=$structure->posti_totali; $i++)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs">Bambini</span>
                                            <select x-model="kids" class="bg-transparent border-0 p-0 text-sm focus:ring-0 font-extrabold">
                                                @for($i=0; $i<$structure->posti_totali; $i++)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div x-show="totalDays > 0" x-transition class="space-y-3 py-4 text-sm animate-pulse" x-show="isChecking">
                                <div class="flex justify-between items-center text-gray-600">
                                    <span>Verifica disponibilità...</span>
                                </div>
                            </div>

                            <div x-show="totalDays > 0 && !isChecking" x-transition class="space-y-3 py-4 text-sm">
                                <template x-if="isAvailable">
                                    <div class="space-y-3">
                                        <div class="flex justify-between items-center text-gray-600">
                                            <span>€{{ number_format($structure->costo_al_giorno, 2, ',', '.') }} x <span x-text="totalDays"></span> notti</span>
                                            <span class="font-bold text-gray-900">€<span x-text="totalPrice"></span></span>
                                        </div>
                                        <div class="flex justify-between items-center pt-3 border-t text-base">
                                            <span class="font-extrabold text-gray-900">Totale</span>
                                            <span class="font-extrabold text-indigo-700 text-xl">€<span x-text="totalPrice"></span></span>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="!isAvailable">
                                    <div class="p-3 bg-red-50 border border-red-200 text-red-600 rounded-xl text-center font-bold">
                                        Le date selezionate non sono disponibili.
                                    </div>
                                </template>
                            </div>

                            <button type="submit" 
                                    :disabled="!isAvailable || totalDays <= 0 || isChecking"
                                    class="w-full py-4 rounded-2xl font-extrabold text-lg shadow-lg transition-all duration-300 transform active:scale-95"
                                    :class="isAvailable && totalDays > 0 && !isChecking ? 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-indigo-200' : 'bg-gray-100 text-gray-400 cursor-not-allowed'">
                                <span x-show="!isChecking">Prenota Ora</span>
                                <span x-show="isChecking">Verifica in corso...</span>
                            </button>
                        </form>
                        
                        <p class="text-center text-[11px] text-gray-400 mt-6 px-4">
                            Non ti verrà addebitato nulla in questo momento. Verrai reindirizzato alla pagina di pagamento.
                        </p>
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
            startDate: '',
            endDate: '',
            adults: 1,
            kids: 0,
            totalDays: 0,
            totalPrice: 0,
            isChecking: false,
            isAvailable: true,
            bookedDates: @json($bookedDates),
            costPerDay: {{ $structure->costo_al_giorno }},

            checkDates() {
                if (this.startDate && this.endDate) {
                    const start = new Date(this.startDate);
                    const end = new Date(this.endDate);
                    
                    if (end > start) {
                        const diffTime = Math.abs(end - start);
                        this.totalDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                        this.totalPrice = (this.totalDays * this.costPerDay).toFixed(2);
                        this.verifyAvailability();
                    } else {
                        this.totalDays = 0;
                    }
                }
            },

            verifyAvailability() {
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

                // Server side double check
                fetch('{{ route('public.booking.check') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        structure_id: {{ $structure->id }},
                        start_date: this.startDate,
                        end_date: this.endDate
                    })
                })
                .then(res => res.json())
                .then(data => {
                    this.isAvailable = data.available;
                    this.isChecking = false;
                })
                .catch(() => {
                    this.isChecking = false;
                });
            },

            submitBooking() {
                if (!this.isAvailable || this.totalDays <= 0) return;

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('public.booking.reserve') }}';
                
                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                form.appendChild(csrf);

                const inputs = {
                    structure_id: '{{ $structure->id }}',
                    start_date: this.startDate,
                    end_date: this.endDate,
                    adulti: this.adults,
                    bambini: this.kids,
                    totale: this.totalPrice
                };

                for (const key in inputs) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = inputs[key];
                    form.appendChild(input);
                }

                document.body.appendChild(form);
                form.submit();
            }
        }
    }
</script>
@endpush
