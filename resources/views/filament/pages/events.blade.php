<x-filament-panels::page>

    <div class="flex items-center justify-end mb-4">
        {{ $this->createAction() }}
    </div>

    {{-- Space to show calendar --}}

    <x-filament::section>
        <div wire:ignore id="calendar"></div>
    </x-filament::section>

    {{-- Table to show events --}}

    {{ $this->table }}

    {{-- Calendar code --}}

    @assets
        <script src="{{ asset('js/fullcalendar.min.js') }}" data-navigate-once></script>
    @endassets

    @script
        <script>
            const calendarFunction = () => {

                let calendarEl = document.getElementById('calendar');
                let calendar = new FullCalendar.Calendar(calendarEl, {
                    editable: true,
                    selectable: true,
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,listWeek'
                    },
                    height: 600,
                    eventClick: function (info) {
                        // View the event in a dialog or modal box
                        $wire.mountAction('viewAction', {
                            id: info.event.id,
                            startDate: info.event.startStr,
                            endDate: info.event.endStr,
                        });
                    },
                    // Handle the creation of new events
                    select: function (info) {
                        $wire.mountAction('createAction', {
                            startDate: info.startStr,
                            endDate: info.endStr,
                        });
                    },
                    // Dragging and dropping events
                    eventDrop: function (info) {
                        $wire.mountAction('droppedEvent', {
                            id: info.event.id,
                            startDate: info.event.startStr,
                            endDate: info.event.endStr,
                        });
                    },
                    // Resizing events
                    eventResize: function (info) {
                        $wire.mountAction('droppedEvent', {
                            id: info.event.id,
                            startDate: info.event.startStr,
                            endDate: info.event.endStr,
                        });
                    },
                    events: JSON.parse($wire.events),

                });

                calendar.render();

            }

            document.addEventListener('livewire:navigated', () => {
                calendarFunction();
            })

            $wire.on('refresh-calendar', () => {
                calendarFunction();
            });

        </script>
    @endscript

</x-filament-panels::page>
