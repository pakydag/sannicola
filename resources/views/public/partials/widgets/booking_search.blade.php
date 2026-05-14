<div class="mx-auto">
    <div class="mx-auto bg-white/80 backdrop-blur-md overflow-hidden p-6 md:p-8 shadow-xl relative rounded-lg">
        <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-black rounded-full opacity-20 blur-3xl"></div>
        <div class="absolute top-0 right-0 -mb-10 -ml-10 w-40 h-40 bg-black rounded-full opacity-20 blur-3xl"></div>

        <div class="relative z-10 max-w-7xl mx-auto">
            @if(!empty($widget->data['title']) || !empty($widget->data['subtitle']))
                <div class="text-center mb-8 hidden">
                    @if(!empty($widget->data['title']))
                        <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-2">{{ $widget->data['title'] }}</h2>
                    @endif
                    @if(!empty($widget->data['subtitle']))
                        <p class="text-indigo-100 text-lg">{{ $widget->data['subtitle'] }}</p>
                    @endif
                </div>
            @endif

            <form action="{{ route('public.booking.search') }}" method="GET" class="p-4 md:p-6 flex flex-col md:flex-row items-end gap-4">

                <!-- Check-in -->
                <div class="w-full md:w-1/4">
                    <label class="block text-sm text-neutral-500 mb-2" for="start_date">
                        Check-in
                    </label>
                    <input class="w-full px-4 py-3 border border-gray-200 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-colors"
                           id="start_date" name="start_date" type="date" required min="{{ date('Y-m-d') }}" value="{{ request('start_date') }}">
                </div>

                <!-- Check-out -->
                <div class="w-full md:w-1/4">
                    <label class="block text-sm text-neutral-500 mb-2" for="end_date">
                        Check-out
                    </label>
                    <input class="w-full px-4 py-3 border border-gray-200 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-colors"
                           id="end_date" name="end_date" type="date" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ request('end_date') }}">
                </div>

                <!-- Adulti -->
                <div class="w-full md:w-1/6">
                    <label class="block text-sm text-neutral-500 mb-2" for="adulti">
                        Adulti
                    </label>
                    <select class="w-full px-4 py-3 border border-gray-200 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-colors appearance-none bg-white"
                            id="adulti" name="adulti">
                        @for($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}" {{ request('adulti') == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <!-- Bambini (0-17) -->
                <div class="w-full md:w-1/6">
                    <label class="block text-sm text-neutral-500 mb-2" for="bambini">
                        Bambini / Ragazzi
                    </label>
                    <select class="w-full px-4 py-3 border border-gray-200 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-colors appearance-none bg-white"
                            id="bambini" name="bambini">
                        @for($i = 0; $i <= 10; $i++)
                            <option value="{{ $i }}" {{ request('bambini') == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <!-- Submit -->
                <div class="w-full md:w-auto flex-grow flex items-end">
                    <button type="submit" class="inline-flex leading-none self-end px-6 pt-[17px] pb-[17px] py-3 uppercase btn">
                        <svg class="w-4 h-4 self-center -mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        Cerca
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const startInput = document.getElementById('start_date');
        const endInput = document.getElementById('end_date');

        startInput.addEventListener('change', function() {
            if (this.value) {
                const nextDay = new Date(this.value);
                nextDay.setDate(nextDay.getDate() + 1);

                const yyyy = nextDay.getFullYear();
                const mm = String(nextDay.getMonth() + 1).padStart(2, '0');
                const dd = String(nextDay.getDate()).padStart(2, '0');

                const minEnd = `${yyyy}-${mm}-${dd}`;
                endInput.min = minEnd;

                if (endInput.value && endInput.value <= this.value) {
                    endInput.value = minEnd;
                }
            }
        });
    });
</script>
