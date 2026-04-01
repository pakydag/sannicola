<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Agenda Appuntamenti') }}
            </h2>
            <div class="flex items-center space-x-4">
                <form action="{{ route('admin.appointments.index') }}" method="GET" id="deptFilterForm">
                <select name="department_id" onchange="this.form.submit()" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                    <option value="">Tutti i Reparti</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ $selected_department_id == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
        <style>
            .fc-event { cursor: pointer; padding: 2px 4px; }
            .fc-toolbar-title { font-size: 1.25rem !important; font-weight: bold; color: #374151; }
            .fc-button-primary { background-color: #4f46e5 !important; border-color: #4f46e5 !important; }
            .fc-button-primary:hover { background-color: #4338ca !important; border-color: #4338ca !important; }
            .fc-button-primary:disabled { background-color: #9ca3af !important; border-color: #9ca3af !important; }
            .fc-v-event .fc-event-main { color: #fff !important; }
        </style>
    @endpush

    @push('scripts')
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/it.js'></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');
                var departmentId = '{{ $selected_department_id }}';
                
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'timeGridWeek',
                    locale: 'it',
                    firstDay: 1, // Lunedì
                    slotMinTime: '08:00:00',
                    slotMaxTime: '20:00:00',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    events: {
                        url: '{{ route('admin.appointments.events') }}',
                        extraParams: {
                            department_id: departmentId
                        }
                    },
                    eventClick: function(info) {
                        const props = info.event.extendedProps;
                        Swal.fire({
                            title: info.event.title,
                            html: `
                                <div class="text-left text-sm">
                                    <p class="mb-2"><strong>Cliente:</strong> ${props.contact}</p>
                                    <p class="mb-2"><strong>Telefono:</strong> ${props.phone}</p>
                                    <p class="mb-2"><strong>Reparto:</strong> ${props.department}</p>
                                    <p class="mb-2"><strong>Data:</strong> ${info.event.start.toLocaleString('it-IT')}</p>
                                    <p class="mb-2"><strong>Stato:</strong> <span class="${props.status === 'cancelled' ? 'text-red-600' : 'text-green-600'}">${props.status.toUpperCase()}</span></p>
                                    ${info.event.description ? `<p class="mt-4 p-2 bg-gray-50 border rounded italic">"${info.event.description}"</p>` : ''}
                                </div>
                            `,
                            showCancelButton: true,
                            confirmButtonText: 'Chiudi',
                            cancelButtonText: props.status !== 'cancelled' ? 'Annulla Appuntamento' : 'Rimuovi',
                            cancelButtonColor: '#ef4444',
                        }).then((result) => {
                            if (result.dismiss === Swal.DismissReason.cancel) {
                                // Se l'utente clicca Annulla, invia una form via JS o fai una fetch
                                if (confirm('Sei sicuro di voler procedere?')) {
                                    const form = document.createElement('form');
                                    form.method = 'POST';
                                    form.action = props.status !== 'cancelled' 
                                        ? `/admin/appointments/${info.event.id}/cancel` 
                                        : `/admin/appointments/${info.event.id}`;
                                    
                                    const csrfInput = document.createElement('input');
                                    csrfInput.type = 'hidden';
                                    csrfInput.name = '_token';
                                    csrfInput.value = '{{ csrf_token() }}';
                                    
                                    if (props.status === 'cancelled') {
                                        const methodInput = document.createElement('input');
                                        methodInput.type = 'hidden';
                                        methodInput.name = '_method';
                                        methodInput.value = 'DELETE';
                                        form.appendChild(methodInput);
                                    } else {
                                        const methodInput = document.createElement('input');
                                        methodInput.type = 'hidden';
                                        methodInput.name = '_method';
                                        methodInput.value = 'PATCH';
                                        form.appendChild(methodInput);
                                    }
                                    
                                    form.appendChild(csrfInput);
                                    document.body.appendChild(form);
                                    form.submit();
                                }
                            }
                        });
                    }
                });
                calendar.render();
            });
        </script>
    @endpush
</x-app-layout>
