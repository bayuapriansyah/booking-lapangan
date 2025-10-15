@extends('layouts.app')

@section('title', 'Field Reservation')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="bg-emerald-500 relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="flex items-center justify-between">
                    <div class="z-10">
                        <h1 class="text-5xl font-bold text-white mb-2">Reservasi Lapangan</h1>
                        <p class="text-emerald-50 text-lg">Reservasi lapangan badminton dengan mudah dari mana saja</p>
                    </div>
                    <div class="hidden lg:block">
                        <img src="https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?w=400" alt="Badminton Player"
                            class="w-64 h-64 object-cover rounded-2xl shadow-2xl border-4 border-white/20">
                    </div>
                </div>
            </div>
            <div class="absolute top-0 right-0 w-96 h-96 bg-yellow-400/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-emerald-600/10 rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="bg-white rounded-xl shadow-sm p-4 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <button id="prevDay" class="p-2 hover:bg-gray-100 rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <div>
                        <input type="date" id="dateSelector"
                            class="text-lg font-semibold border-0 focus:ring-0 cursor-pointer" value="{{ date('Y-m-d') }}">
                    </div>
                    <button id="nextDay" class="p-2 hover:bg-gray-100 rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
                <button id="todayBtn"
                    class="px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition">
                    Today
                </button>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    <!-- Reservation Modal -->
    <div id="reservationModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="modal-backdrop fixed inset-0" onclick="closeModal()"></div>
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 relative z-10 transform transition-all">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">Reservasi baru</h3>
                        <p class="text-sm text-gray-500 mt-1" id="modalCourtName"></p>
                    </div>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Waktu mulai</label>
                        <div class="bg-gray-50 px-4 py-3 rounded-lg">
                            <p class="font-semibold text-gray-900" id="modalStartTime"></p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Durasi</label>
                        <div class="grid grid-cols-2 gap-2">
                            <button onclick="selectDuration(0.5)"
                                class="duration-btn px-4 py-3 border-2 border-gray-200 rounded-lg hover:border-emerald-500 hover:bg-emerald-50 transition">
                                30 min
                            </button>
                            <button onclick="selectDuration(1)"
                                class="duration-btn px-4 py-3 border-2 border-gray-200 rounded-lg hover:border-emerald-500 hover:bg-emerald-50 transition">
                                1 hour
                            </button>
                            <button onclick="selectDuration(1.5)"
                                class="duration-btn px-4 py-3 border-2 border-gray-200 rounded-lg hover:border-emerald-500 hover:bg-emerald-50 transition">
                                1.5 hour
                            </button>
                            <button onclick="selectDuration(2)"
                                class="duration-btn px-4 py-3 border-2 border-gray-200 rounded-lg hover:border-emerald-500 hover:bg-emerald-50 transition">
                                2 hour
                            </button>
                        </div>
                    </div>

                    <div class="bg-emerald-50 border border-emerald-200 px-4 py-3 rounded-lg">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total Harga</span>
                            <span class="text-2xl font-bold text-emerald-600" id="modalPrice">$0.00</span>
                        </div>
                    </div>

                    <button onclick="submitReservation()"
                        class="w-full bg-emerald-500 text-white py-4 rounded-xl font-semibold hover:bg-emerald-600 transition shadow-lg hover:shadow-xl">
                        Reservasi Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        let calendar;
        let selectedSlot = null;
        let selectedDuration = 1;
        let courts = @json($courts);

        document.addEventListener('DOMContentLoaded', function () {
            initCalendar();

            document.getElementById('dateSelector').addEventListener('change', function () {
                calendar.gotoDate(this.value);
                loadReservations();
            });

            document.getElementById('prevDay').addEventListener('click', () => changeDay(-1));
            document.getElementById('nextDay').addEventListener('click', () => changeDay(1));
            document.getElementById('todayBtn').addEventListener('click', () => {
                const today = new Date().toISOString().split('T')[0];
                document.getElementById('dateSelector').value = today;
                calendar.gotoDate(today);
                loadReservations();
            });
        });

        function initCalendar() {
            const calendarEl = document.getElementById('calendar');
            calendar = new FullCalendar.Calendar(calendarEl, {
                plugins: [
                    FullCalendar.interactionPlugin,
                    FullCalendar.timeGridPlugin,
                    FullCalendar.resourceTimeGridPlugin,
                ],
                schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
                initialView: 'resourceTimeGridDay',
                headerToolbar: false,
                slotMinTime: '06:00:00',
                slotMaxTime: '22:00:00',
                slotDuration: '00:30:00',
                allDaySlot: false,
                height: 'auto',
                expandRows: true,
                selectable: true,
                selectMirror: true,
                resources: courts.map(court => ({
                    id: court.id,
                    title: court.name,
                    extendedProps: { price: court.price_per_hour },
                })),
                events: '{{ url('/api/reservations') }}',
                eventContent: function (arg) {
                    const userTitle = arg.event.title;
                    const court = arg.event.extendedProps.courtName;
                    const status = arg.event.extendedProps.status;
                    return {
                        html: `
                                <div class="p-1 text-xs font-semibold rounded text-white ${status === 'pending' ? 'bg-yellow-500' : 'bg-emerald-500'}">
                                    ${userTitle} <br> <span class="text-[10px]">${court}</span>
                                </div>
                            `
                    };
                },
                select: handleDateSelect,
                eventClick: handleEventClick,
            });

            calendar.render();
        }

        function handleDateSelect(selectInfo) {
            const court = courts.find(c => c.id == selectInfo.resource.id);
            if (!court) return;

            selectedSlot = {
                courtId: selectInfo.resource.id,
                courtName: selectInfo.resource.title,
                startTime: selectInfo.startStr,
                pricePerHour: court.price_per_hour
            };

            // isi modal
            document.getElementById('modalCourtName').textContent = selectInfo.resource.title;
            document.getElementById('modalStartTime').textContent = new Date(selectInfo.startStr)
                .toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: false });

            // reset durasi ke default
            selectedDuration = 1;
            updateDurationButtons(null, 1);
            updatePriceDisplay();

            // tampilkan modal
            document.getElementById('reservationModal').classList.remove('hidden');
            calendar.unselect();
        }

        function handleEventClick(clickInfo) {
            if (clickInfo.event.extendedProps.isOwn) {
                alert('This is your reservation');
            }
        }

        function updateDurationButtons(clickedButton, hours) {
            document.querySelectorAll('.duration-btn').forEach(btn => {
                btn.classList.remove('border-emerald-500', 'bg-emerald-50', 'text-emerald-700');
                btn.classList.add('border-gray-200');
            });
            if (clickedButton) {
                clickedButton.classList.add('border-emerald-500', 'bg-emerald-50', 'text-emerald-700');
                clickedButton.classList.remove('border-gray-200');
            }
            selectedDuration = hours;
            updatePriceDisplay();
        }

        function updatePriceDisplay() {
            if (!selectedSlot) return;
            const totalPrice = selectedSlot.pricePerHour * selectedDuration;
            document.getElementById('modalPrice').textContent = `Rp ${totalPrice.toLocaleString()}`;
        }

        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('duration-btn')) {
                const hours = parseFloat(e.target.textContent);
                updateDurationButtons(e.target, hours === 30 ? 0.5 : hours);

            }
        });

        async function submitReservation() {
            if (!selectedSlot) return;

            const button = event.target;
            button.disabled = true;
            button.textContent = 'Processing...';

            try {
                const response = await fetch('/reservations', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        court_id: selectedSlot.courtId,
                        start_time: selectedSlot.startTime,
                        duration: selectedDuration
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    window.location.href = data.redirect;
                } else {
                    alert(data.error || 'Failed to create reservation');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            } finally {
                button.disabled = false;
                button.textContent = 'Reserve Now';
            }
        }

        function closeModal() {
            document.getElementById('reservationModal').classList.add('hidden');
            selectedSlot = null;
        }

        function changeDay(delta) {
            const dateInput = document.getElementById('dateSelector');
            const currentDate = new Date(dateInput.value);
            currentDate.setDate(currentDate.getDate() + delta);
            const newDate = currentDate.toISOString().split('T')[0];
            dateInput.value = newDate;
            calendar.gotoDate(newDate);
            loadReservations();
        }

        function loadReservations() {
            calendar.refetchEvents();
        }

        window.closeModal = closeModal;
        window.submitReservation = submitReservation;
    </script>
@endpush