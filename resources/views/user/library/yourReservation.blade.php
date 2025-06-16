@extends('layout')

@section('content')
<div class="py-20 flex flex-col items-center justify-center min-h-screen bg-[var(--background-light)] text-[var(--text-primary)]">
    <div class="w-full max-w-6xl px-5 text-center">
        <h2 class="text-4xl font-bold mt-10 mb-8 text-center text-[var(--primary)]">Your Reservations</h2>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @if($reservations->isEmpty())
            <p class="text-lg text-gray-600">You have no reservations yet.</p>
            <p class="mt-4">
                <a href="{{ route('coworking.schedule') }}" class="px-6 py-2 bg-[var(--accent-blue)] text-white rounded-md text-lg font-semibold hover:bg-[var(--primary)] focus:outline-none focus:ring-2 focus:ring-[var(--accent-blue)] focus:ring-offset-2">
                    Book a Co-Working Space
                </a>
            </p>
        @else
            {{-- Layout: grid-cols-1 ensures full width and vertical stacking --}}
            <div class="grid grid-cols-1 gap-6">
                @foreach($reservations as $reservation)
                    {{-- Card Styling: bg-white for clean, minimal look. text-gray-700 for main text color. --}}
                    {{-- border-t-4 adds a colored top border for visual status cue. --}}
                    <div class="relative flex bg-white rounded-xl shadow p-4 text-gray-700 border-t-4
                        @if(Str::startsWith($reservation->status_for_display, 'Reserved')) border-blue-500
                        @elseif(Str::startsWith($reservation->status_for_display, 'Attended')) border-green-500
                        @elseif($reservation->status_for_display == 'Closed') border-gray-500
                        @elseif($reservation->status_for_display == 'Cancelled') border-yellow-500 {{-- Updated: Cancelled is now yellow --}}
                        @elseif($reservation->status_for_display == 'Not Attended') border-red-500 {{-- Updated: Not Attended is now red --}}
                        @else border-gray-400 {{-- Fallback for unknown/transitional states --}}
                        @endif">
                        {{-- Left Side: Room Code/Name (always visible) --}}
                        {{-- Styling for a clean, contrasting block for the room code. --}}
                        <div class="aspect-[2/3] w-32 h-48 object-cover rounded mr-4 flex-shrink-0 flex items-center justify-center bg-gray-200 text-gray-800">
                            <span class="text-6xl font-bold">{{ $reservation->reservation_code_cwspace }}</span>
                        </div>

                        {{-- Right Side: Booking Details (always visible) --}}
                        {{-- flex-col and text-left ensure details stack vertically and are left-aligned --}}
                        <div class="flex flex-col text-left text-sm lg:text-md w-full">
                            <div>
                                <h3 class="text-xl font-bold mb-2 text-[var(--primary)]">Reservation #{{ $loop->iteration }}</h3> {{-- UPDATED: Use $loop->iteration for user-specific numbering --}}
                                <p class="mb-1 max-w-full">
                                    <span class="font-semibold">Date: </span>
                                    {{ \Carbon\Carbon::parse($reservation->reservation_date)->format('F d, Y') }}
                                </p>
                                <p class="mb-1 max-w-full">
                                    <span class="font-semibold">Time: </span>
                                    {{ \Carbon\Carbon::parse($reservation->reservation_start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($reservation->reservation_end_time)->format('H:i') }}
                                </p>
                                <p class="mb-1 max-w-full">
                                    <span class="font-semibold">Purpose: </span>
                                    {{ ucfirst(str_replace('_', ' ', $reservation->purpose)) }}
                                </p>
                                <p class="mb-1 max-w-full">
                                    <span class="font-semibold">Participants: </span>
                                    {{ $reservation->num_participants }}
                                </p>
                            </div>

                            {{-- STATUS and Buttons --}}
                            {{-- Placed at the end of the right section, aligned to the right --}}
                            <div class="flex flex-col items-end self-end mt-4">
                                {{-- Status Badge (always visible, color changes by status) --}}
                                <span class="px-3 py-1 rounded-full text-sm font-semibold mb-2
                                    @if(Str::startsWith($reservation->status_for_display, 'Reserved')) bg-blue-100 text-blue-800 {{-- Reserved -> Blue --}}
                                    @elseif(Str::startsWith($reservation->status_for_display, 'Attended')) bg-green-100 text-green-800 {{-- Attended -> Green --}}
                                    @elseif($reservation->status_for_display == 'Closed') bg-gray-100 text-gray-800 {{-- Closed -> Gray --}}
                                    @elseif($reservation->status_for_display == 'Cancelled') bg-yellow-100 text-yellow-800 {{-- Updated: Cancelled -> Yellow --}}
                                    @elseif($reservation->status_for_display == 'Not Attended') bg-red-100 text-red-800 {{-- Updated: Not Attended -> Red --}}
                                    @else bg-gray-200 text-gray-700 {{-- Fallback --}}
                                    @endif">
                                    {{ $reservation->status_for_display }}
                                </span>

                                {{-- Action Buttons (visibility controlled by controller logic) --}}
                                <div class="flex flex-col sm:flex-row justify-center sm:justify-end gap-2 w-full">
                                    @if($reservation->show_cancel_button)
                                        <button type="button"
                                            class="cancel-reservation-btn w-full sm:w-auto px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                                            data-reservation-id="{{ $reservation->id }}">
                                            Cancel Reservation
                                        </button>
                                    @endif
                                    @if($reservation->show_order_drink_button)
                                        <a href="{{ route('beverages.menu') }}"
                                            class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex items-center justify-center">
                                            Order Drink
                                        </a>
                                    @endif
                                    @if($reservation->show_mark_attended_button)
                                        <button type="button"
                                            class="mark-attended-btn w-full sm:w-auto px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                                            data-reservation-id="{{ $reservation->id }}">
                                            Mark Attended
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- Custom Message Box/Modal and Success Modal (kept unchanged as they are already functional) --}}
<div id="customMessageBox" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white p-6 rounded-lg shadow-xl max-w-sm w-full text-center">
        <p id="messageBoxContent" class="text-lg font-semibold mb-4"></p>
        <button id="messageBoxCloseBtn" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">OK</button>
    </div>
</div>

<div id="successModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white p-6 rounded-lg shadow-xl max-w-md w-full text-left">
        <h3 class="text-2xl font-bold mb-4 text-center text-green-700">Reservation Confirmed!</h3>
        <p class="mb-2"><strong>Name:</strong> <span id="modalUserName"></span></p>
        <p class="mb-2"><strong>Purpose:</strong> <span id="modalPurpose"></span></p>
        <p class="mb-2"><strong>Contact:</strong> <span id="modalContactNumber"></span></p>
        <p class="mb-4"><strong>Number of Participants:</strong> <span id="modalNumParticipants"></span></p>
        <p class="mb-2"><strong>Date:</strong> <span id="modalBookingDate"></span></p>
        <p class="font-semibold mb-2">Selected Slot:</p>
        <ul id="modalSelectedSlots" class="list-disc list-inside mb-4">
            <!-- Selected slot will be injected here -->
        </ul>
        <p class="text-sm text-gray-700 text-center">
            Please kindly check your email for further instructions. Thank you!
        </p>
        <div class="text-center mt-6">
            <button id="successModalCloseBtn" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Close</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Function to handle showing SweetAlert messages
        function showSweetAlert(icon, title, text) {
            Swal.fire({
                icon: icon,
                title: title,
                text: text,
            });
        }

        // Cancel Reservation button handler
        document.querySelectorAll('.cancel-reservation-btn').forEach(button => {
            button.addEventListener('click', function() {
                const reservationId = button.dataset.reservationId;
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, cancel it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/user/reservations/${reservationId}/cancel`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.message) {
                                if (data.new_status === 'Cancelled') {
                                    showSweetAlert('success', 'Cancelled!', data.message);
                                    location.reload();
                                } else {
                                    showSweetAlert('error', 'Error', data.message);
                                }
                            } else {
                                showSweetAlert('error', 'Error', 'Failed to cancel reservation.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showSweetAlert('error', 'Error', 'An unexpected error occurred.');
                        });
                    }
                });
            });
        });

        // Mark Attended button handler
        document.querySelectorAll('.mark-attended-btn').forEach(button => {
            button.addEventListener('click', function() {
                const reservationId = button.dataset.reservationId;
                Swal.fire({
                    title: 'Mark as Attended?',
                    text: "This reservation will be marked as attended.",
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, mark it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/user/reservations/${reservationId}/attend`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.message) {
                                if (data.new_status === 'Attended') {
                                    showSweetAlert('success', 'Success!', data.message);
                                    location.reload();
                                } else {
                                    showSweetAlert('error', 'Error', data.message);
                                }
                            } else {
                                showSweetAlert('error', 'Error', 'Failed to mark as attended.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showSweetAlert('error', 'Error', 'An unexpected error occurred.');
                        });
                    }
                });
            });
        });
    });
</script>