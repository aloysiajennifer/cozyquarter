@extends('layout')

@section('content')
<div class="py-20 flex items-center justify-center">
    <div class="w-full max-w-6xl px-5 text-center">
        <!-- Intro Section -->
        <h2 class="text-4xl font-bold mt-10 mb-3 text-center">What is A Co-Working Space?</h2>
        <p class="text-justify mb-10 leading-relaxed">
            A co-working space is a shared workspace where individuals and teams work independently but under the same roof, fostering collaboration and community. It's a professional environment that offers amenities like desks, meeting rooms, and Wi-Fi, but it's not a traditional office where everyone works for the same company.
        </p>

        <!-- Book A Room Section -->
        <h2 class="text-4xl font-bold mb-4 text-center">Book A Room</h2>

        <!-- Date Picker -->
        <form method="GET" action="{{ route('coworking.schedule') }}" class="mb-6 flex flex-col md:flex-row items-center justify-center gap-2">
            <label for="date" class="font-semibold">Date:</label>
            <input type="date"
                id="date"
                name="date"
                value="{{ request('date', \Carbon\Carbon::now()->toDateString()) }}"
                min="{{ \Carbon\Carbon::now()->toDateString() }}"
                max="{{ \Carbon\Carbon::now()->addWeeks(2)->toDateString() }}"
                class="border px-3 py-1 rounded-md">
            <button type="submit" class="px-4 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Show</button>
        </form>

        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
        @endif

        <!-- User Booking Details Form -->
        <div class="mb-6 bg-gray-100 p-6 rounded-lg shadow-md">
            <h3 class="text-2xl font-bold mb-4 text-center">Your Booking Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-left">
                <div>
                    <label for="userName" class="block text-gray-700 text-sm font-bold mb-2">Name:<span class="text-red-500">*</span></label>
                    <input type="text" id="userName" name="userName"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        placeholder="Your Full Name" value="{{ $userName ?? '' }}" {{ !empty($userName) ? 'readonly' : '' }} required>
                </div>
                <div>
                    <label for="purpose" class="block text-gray-700 text-sm font-bold mb-2">Purpose:<span class="text-red-500">*</span></label>
                    <select id="purpose" name="purpose"
                        class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        <option value="">Select Purpose</option>
                        <option value="individual_work">Individual Work</option>
                        <option value="discussion">Discussion</option>
                        <option value="others">Others</option>
                    </select>
                </div>
                <div>
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:<span class="text-red-500">*</span></label>
                    <input type="email" id="email" name="email"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        placeholder="Your Email Address" value="{{ old('email', $userEmail ?? '') }}" {{ !empty($userEmail) ? 'readonly' : '' }} required>
                </div>
                <div>
                    <label for="numParticipants" class="block text-gray-700 text-sm font-bold mb-2">Number of Participants:<span class="text-red-500">*</span></label>
                    <input type="number" id="numParticipants" name="numParticipants" min="1"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        placeholder="e.g., 1" required>
                </div>
            </div>
            <p id="participant-message" class="text-sm text-red-600 mt-4 text-center hidden"></p>
            <p id="capacity-message" class="text-sm text-red-600 mt-4 text-center hidden"></p>
        </div>

        <!-- Schedule Table -->
        <div class="overflow-x-auto">
            <table class="table-auto w-full border text-sm text-center">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-4 py-2">TIME</th>
                        @foreach ($cwspaces as $space)
                        <th class="px-4 py-2" data-room-id="{{ $space->id }}" data-room-capacity="{{ $space->capacity_cwspace }}" data-room-code="{{ $space->code_cwspace }}">
                            Room {{ $space->code_cwspace }}<br> (Cap: {{ $space->capacity_cwspace }})
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($timeSlots as $slot)
                    <tr>
                        <td class="border px-4 py-2 font-semibold">{{ $slot['label'] }}</td>
                        @foreach ($cwspaces as $space)
                        @php
                        // Use the status determined in the controller logic
                        $status = $schedules[$space->id][$slot['start']] ?? 'closed';
                        @endphp
                        <td class="border px-4 py-2
                                    {{ $status === 'reserved' ? 'bg-red-600 text-white' : '' }}
                                    {{ $status === 'available' ? 'bg-green-500 text-white selectable-slot' : '' }}
                                    {{ $status === 'closed' ? 'bg-gray-300 text-gray-600' : '' }}"
                            data-room-id="{{ $space->id }}"
                            data-time-start="{{ $slot['start'] }}"
                            data-time-label="{{ $slot['label'] }}"
                            data-slot-status="{{ $status }}"
                            data-room-code="{{ $space->code_cwspace }}">
                            {{ ucfirst($status) }}
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Confirm Reservation Button -->
        <div class="mt-6 text-center">
            <button id="confirmReservationBtn" class="px-6 py-2 bg-blue-600 text-white rounded-md text-lg font-semibold hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Confirm Reservation
            </button>
        </div>

        <p class="mt-6 text-sm text-gray-700 text-center">
            <strong>Note:</strong> In the event of a <strong>no-show</strong>, a <span class="text-red-600 font-bold">blacklist</span> will be issued, preventing you from making future reservations. Please consider <a href="{{ route('user.reservations.index') }}" class="text-blue-600 underline">canceling your reservation</a> in advance.
        </p>
    </div>
</div>

<!-- Custom Message Box/Modal for alerts -->
<div id="customMessageBox" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white p-6 rounded-lg shadow-xl max-w-sm w-full text-center">
        <p id="messageBoxContent" class="text-lg font-semibold mb-4"></p>
        <button id="messageBoxCloseBtn" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">OK</button>
    </div>
</div>

<!-- Success Modal -->
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
        // --- Custom Message Box Functions (Replacing alert()) ---
        const customMessageBox = document.getElementById('customMessageBox');
        const messageBoxContent = document.getElementById('messageBoxContent');
        const messageBoxCloseBtn = document.getElementById('messageBoxCloseBtn');

        function displayMessage(message) {
            messageBoxContent.textContent = message;
            customMessageBox.classList.remove('hidden');
        }

        messageBoxCloseBtn.addEventListener('click', function() {
            customMessageBox.classList.add('hidden');
        });

        // --- SweetAlert for Server Errors ---
        document.addEventListener('serverError', function(e) {
            const errorData = e.detail;
            let message = errorData.message || 'An unexpected error occurred.';
            let icon = 'error'; // Default to red error icon

            if (errorData.errors) {
                // If validation errors, concatenate them into a readable string
                // Check if the specific blacklist message is present in the first error message
                const firstErrorMessage = Object.values(errorData.errors).flat()[0];
                if (firstErrorMessage && firstErrorMessage.includes('blacklisted from booking')) {
                    message = firstErrorMessage; // Display only the specific blacklist message
                    icon = 'warning'; // Change icon to yellow for blacklist warning
                } else {
                    message = Object.values(errorData.errors).flat().join('\n'); // Display all other validation errors
                }
            }

            // Using Swal.fire (SweetAlert) for better error display
            Swal.fire({
                icon: icon, // Use dynamically determined icon
                title: 'Booking Error',
                text: message,
                confirmButtonText: 'OK'
            });
        });

        // --- Success Modal Functions ---
        const successModal = document.getElementById('successModal');
        const successModalCloseBtn = document.getElementById('successModalCloseBtn');

        function showSuccessModal(bookingDetails) {
            document.getElementById('modalUserName').textContent = bookingDetails.name;
            document.getElementById('modalPurpose').textContent = bookingDetails.purpose;
            document.getElementById('modalContactNumber').textContent = bookingDetails.email;
            document.getElementById('modalNumParticipants').textContent = bookingDetails.numParticipants;
            document.getElementById('modalBookingDate').textContent = bookingDetails.bookingDate;

            const slotsList = document.getElementById('modalSelectedSlots');
            slotsList.innerHTML = '';
            if (bookingDetails.selectedTimeSlots && bookingDetails.selectedTimeSlots.length > 0) {
                const li = document.createElement('li');
                li.textContent = bookingDetails.selectedTimeSlots[0];
                slotsList.appendChild(li);
            }

            successModal.classList.remove('hidden');
        }

        successModalCloseBtn.addEventListener('click', function() {
            successModal.classList.add('hidden');
            location.reload(); // Refresh to show updated availability
        });


        // --- Booking Form Details Logic ---
        const purposeSelect = document.getElementById('purpose');
        const numParticipantsInput = document.getElementById('numParticipants');
        const participantMessage = document.getElementById('participant-message');
        const capacityMessage = document.getElementById('capacity-message');

        function updateParticipantInput() {
            const purpose = purposeSelect.value;
            participantMessage.classList.add('hidden'); // Hide previous messages

            if (purpose === 'individual_work') {
                numParticipantsInput.value = 1;
                numParticipantsInput.readOnly = true;
                numParticipantsInput.classList.add('bg-gray-200');
                participantMessage.textContent = 'For individual work, participants automatically set to 1.';
                participantMessage.classList.remove('hidden');
            } else if (purpose === 'discussion') {
                numParticipantsInput.value = ''; // Clear previous value
                numParticipantsInput.readOnly = false;
                numParticipantsInput.classList.remove('bg-gray-200');
                numParticipantsInput.min = 2; // Set min to 2 for discussion
                participantMessage.textContent = 'For discussion, participants must be greater than 1.';
                participantMessage.classList.remove('hidden');
            } else { // 'others' or no selection
                numParticipantsInput.value = ''; // Clear previous value
                numParticipantsInput.readOnly = false;
                numParticipantsInput.classList.remove('bg-gray-200');
                numParticipantsInput.min = 1; // Reset min to 1
            }
        }

        updateParticipantInput();
        purposeSelect.addEventListener('change', updateParticipantInput);


        // --- Schedule Table Interaction Logic (Single Slot Selection) ---
        const allSlots = document.querySelectorAll('td[data-slot-status]');
        let selectableSlots = [];
        let currentSelectedSlot = null; // Holds a single selected slot object { roomId, timeStart, timeLabel, element }

        const originalBgColorClass = 'bg-green-500';
        const hoverBgColorClass = 'bg-green-700';
        const selectedBgColorClass = 'bg-yellow-400';
        const reservedBgColorClass = 'bg-red-600';
        const closedBgColorClass = 'bg-gray-300';
        const textColorClass = 'text-white';
        const greyTextColorClass = 'text-gray-600';

        const dateInput = document.getElementById('date');

        // Fetch user's active reservations (for client-side conflict display)
        // This data needs to be passed from the controller to the Blade view.
        // Assuming $userActiveReservations is an array of objects like:
        // [{ reservation_date: 'YYYY-MM-DD', reservation_start_time: 'HH:MM:SS' }, ...]
        const userActiveReservations = @json($userActiveReservations ?? []);

        // Initial setup for selectable slots based on current date
        updateSlotStatus();

        function updateSlotStatus() {
            const selectedDateStr = dateInput.value;
            // Normalize selected date to start of day for accurate comparison
            const selectedDateJS = new Date(selectedDateStr + 'T00:00:00');
            const currentDateTime = new Date(); // Current time with hours/minutes
            const todayJS = new Date();
            todayJS.setHours(0, 0, 0, 0); // Normalize today's date to start of day

            selectableSlots = []; // Reset the list of genuinely selectable slots

            allSlots.forEach(slot => {
                const initialStatus = slot.dataset.slotStatus;
                const slotStartTimeStr = slot.dataset.timeStart;
                const slotDateTime = new Date(selectedDateStr + 'T' + slotStartTimeStr);

                // Always reset classes first to avoid stale states
                slot.classList.remove(originalBgColorClass, hoverBgColorClass, selectedBgColorClass, 'status-selected', 'selectable-slot', 'cursor-pointer');
                // Reset text content based on initial status or default to empty for now
                slot.textContent = initialStatus ? (initialStatus.charAt(0).toUpperCase() + initialStatus.slice(1)) : ''; // Capitalize first letter
                slot.style.cursor = ''; // Reset cursor style

                // Remove previous event listeners
                if (slot.clickHandler) slot.removeEventListener('click', slot.clickHandler);
                if (slot.mouseoverHandler) slot.removeEventListener('mouseover', slot.mouseoverHandler);
                if (slot.mouseoutHandler) slot.removeEventListener('mouseout', slot.mouseoutHandler);

                let isConflictingWithUserReservation = false;
                const slotFullDateTime = selectedDateStr + ' ' + slotStartTimeStr;

                // Check for conflict with user's own existing active reservations
                for (const userRes of userActiveReservations) {
                    const userResFullStartDateTime = userRes.reservation_date + ' ' + userRes.reservation_start_time;
                    if (userRes.reservation_date === selectedDateStr && userRes.reservation_start_time === slotStartTimeStr) {
                        // This slot conflicts with an active reservation for the current user
                        isConflictingWithUserReservation = true;
                        break;
                    }
                }

                // Re-apply base styles based on initial status from backend AND client-side conflicts
                if (initialStatus === 'reserved' || isConflictingWithUserReservation) {
                    // If it's reserved by anyone OR conflicts with current user's active booking
                    slot.classList.add(reservedBgColorClass, textColorClass);
                    slot.textContent = 'Reserved'; // Or 'Your Booking'
                } else if (initialStatus === 'closed') {
                    slot.classList.add(closedBgColorClass, greyTextColorClass);
                    slot.textContent = 'Closed';
                } else { // initialStatus is 'available' from backend
                    // Check if this "available" slot has now passed based on current time
                    if (selectedDateJS.toDateString() === todayJS.toDateString() && currentDateTime.getTime() >= slotDateTime.getTime()) {
                        // If it's today and the time has passed, mark as closed
                        slot.classList.add(closedBgColorClass, greyTextColorClass);
                        slot.textContent = 'Closed';
                    } else {
                        // Otherwise, it's truly available and selectable
                        slot.classList.add(originalBgColorClass, textColorClass);
                        slot.textContent = 'Available'; // Ensure it says Available
                        slot.classList.add('selectable-slot', 'cursor-pointer');
                        selectableSlots.push(slot); // Add to the list of truly selectable elements
                    }
                }
            });
            // Re-attach event listeners only to the newly identified selectable slots
            attachSlotEventListeners();
            // Update the display for the currently selected slot if any
            updateSelectedSlotDisplay();
        }


        function showMessage(element, message) {
            element.textContent = message;
            element.classList.remove('hidden');
        }

        function hideMessages() {
            participantMessage.classList.add('hidden');
            capacityMessage.classList.add('hidden');
            customMessageBox.classList.add('hidden');
        }

        function validateFormInputsOnly() {
            hideMessages();

            const numParticipants = parseInt(numParticipantsInput.value, 10);
            const purpose = purposeSelect.value;

            if (!purpose) {
                displayMessage('Please select a purpose.');
                return false;
            }

            if (isNaN(numParticipants) || numParticipants < 1) {
                showMessage(participantMessage, 'Please enter a valid number of participants (at least 1).');
                return false;
            }

            if (purpose === 'individual_work' && numParticipants !== 1) {
                showMessage(participantMessage, 'For individual work, participants must be exactly 1.');
                return false;
            } else if (purpose === 'discussion' && numParticipants <= 1) {
                showMessage(participantMessage, 'For discussion, participants must be greater than 1.');
                return false;
            }
            return true;
        }

        function validateSelectedSlotAgainstForm(slotElement) {
            // Validate form inputs first
            if (!validateFormInputsOnly()) {
                return false;
            }

            const numParticipants = parseInt(numParticipantsInput.value, 10);
            const roomCapacity = parseInt(slotElement.closest('table').querySelector(`th[data-room-id="${slotElement.dataset.roomId}"]`).dataset.roomCapacity, 10);

            if (numParticipants > roomCapacity) {
                showMessage(capacityMessage, `Number of participants (${numParticipants}) exceeds capacity of Room ${slotElement.dataset.roomCode} (Capacity: ${roomCapacity}).`);
                return false;
            }
            return true;
        }


        function attachSlotEventListeners() {
            // Remove listeners from ALL slots before re-attaching to prevent duplicates
            // This loop ensures that even if a slot changed its status (e.g., from available to closed dynamically),
            // any old listeners are cleaned up.
            allSlots.forEach(slot => {
                if (slot.clickHandler) slot.removeEventListener('click', slot.clickHandler);
                if (slot.mouseoverHandler) slot.removeEventListener('mouseover', slot.mouseoverHandler);
                if (slot.mouseoutHandler) slot.removeEventListener('mouseout', slot.mouseoutHandler);
            });

            selectableSlots.forEach(slot => { // Iterate ONLY over currently identified selectable slots
                // console.log('Attaching listener to:', slot.dataset.roomCode, slot.dataset.timeLabel); // Debugging line

                slot.mouseoverHandler = function() {
                    if (!this.classList.contains('status-selected')) {
                        this.classList.remove(originalBgColorClass);
                        slot.classList.add(hoverBgColorClass);
                    }
                };
                slot.mouseoutHandler = function() {
                    if (!this.classList.contains('status-selected')) {
                        slot.classList.remove(hoverBgColorClass);
                        slot.classList.add(originalBgColorClass);
                    }
                };
                slot.clickHandler = function() {
                    console.log('Slot clicked:', this.dataset.roomCode, this.dataset.timeLabel, 'Status:', this.dataset.slotStatus, 'Is selectable:', this.classList.contains('selectable-slot')); // Debugging line
                    hideMessages();

                    if (this.classList.contains('status-selected')) {
                        // If already selected, deselect it
                        currentSelectedSlot = null;
                        console.log('Deselecting slot.');
                    } else {
                        // If a different slot was previously selected, deselect it first visually
                        if (currentSelectedSlot) {
                            currentSelectedSlot.element.classList.remove(selectedBgColorClass, 'status-selected');
                            currentSelectedSlot.element.classList.add(originalBgColorClass); // Revert to available color
                            currentSelectedSlot.element.textContent = 'Available';
                            currentSelectedSlot.element.style.cursor = 'pointer';
                            console.log('Deselected previous slot:', currentSelectedSlot.roomCode, currentSelectedSlot.timeLabel);
                        }
                        // Select the new slot
                        currentSelectedSlot = {
                            roomId: this.dataset.roomId,
                            timeStart: this.dataset.timeStart,
                            timeLabel: this.dataset.timeLabel,
                            element: this,
                            roomCode: this.dataset.roomCode
                        };
                        console.log('Selected new slot:', currentSelectedSlot.roomCode, currentSelectedSlot.timeLabel);
                    }
                    updateSelectedSlotDisplay(); // Update visual state for all slots
                    if (currentSelectedSlot) {
                        validateSelectedSlotAgainstForm(currentSelectedSlot.element);
                    }
                };

                slot.addEventListener('mouseover', slot.mouseoverHandler);
                slot.addEventListener('mouseout', slot.mouseoutHandler);
                slot.addEventListener('click', slot.clickHandler);
            });
        }

        // Updates the visual state of the selected slot (only one can be selected)
        function updateSelectedSlotDisplay() {
            allSlots.forEach(slot => {
                if (currentSelectedSlot && slot === currentSelectedSlot.element) {
                    // This is the currently selected slot
                    slot.classList.add(selectedBgColorClass, textColorClass, 'status-selected');
                    slot.classList.remove(originalBgColorClass, hoverBgColorClass);
                    slot.textContent = 'SELECTED';
                    slot.style.cursor = 'default';
                } else if (slot.classList.contains('selectable-slot')) { // IMPORTANT: Only modify selectable slots that are currently available
                    // This is an available slot but not currently selected
                    slot.classList.remove(selectedBgColorClass, 'status-selected');
                    slot.classList.add(originalBgColorClass); // Ensure it's light green if available
                    slot.textContent = 'Available'; // Ensure it says Available
                    slot.style.cursor = 'pointer';
                }
            });
        }


        // Re-validate form and selected slot when form inputs change
        numParticipantsInput.addEventListener('input', function() {
            validateFormInputsOnly();
            if (currentSelectedSlot) {
                validateSelectedSlotAgainstForm(currentSelectedSlot.element);
            }
        });
        purposeSelect.addEventListener('change', function() {
            updateParticipantInput();
            validateFormInputsOnly();
            if (currentSelectedSlot) {
                validateSelectedSlotAgainstForm(currentSelectedSlot.element);
            }
        });

        // Date picker validation on change
        dateInput.addEventListener('change', function() {
            const chosenDate = new Date(this.value + 'T00:00:00'); // Normalize to start of day
            const dayOfWeek = chosenDate.getDay(); // 0 for Sunday, 6 for Saturday
            const today = new Date();
            today.setHours(0, 0, 0, 0); // Normalize today's date

            if (isNaN(chosenDate.getTime())) { // Check for invalid date (e.g., empty string)
                displayMessage('Please select a valid date.');
                this.value = ''; // Clear invalid date
                return;
            }

            if (chosenDate < today) {
                displayMessage('You cannot select a past date.');
                this.value = ''; // Clear invalid date
                return;
            }

            if (dayOfWeek === 0 || dayOfWeek === 6) {
                displayMessage('Weekends (Saturday and Sunday) are not available for booking. Please select a weekday.');
                this.value = ''; // Clear the invalid date selection
            } else {
                this.closest('form').submit(); // If date is valid, re-submit form to refresh schedule for the new date
            }
        });


        // Event listener for the Confirm Reservation button
        const confirmButton = document.getElementById('confirmReservationBtn');
        confirmButton.addEventListener('click', async function() {
            const userName = document.getElementById('userName').value.trim();
            const purpose = document.getElementById('purpose').value;
            const email = document.getElementById('email').value.trim();
            const numParticipants = document.getElementById('numParticipants').value.trim();
            const bookingDate = dateInput.value;

            // Client-side validation before sending to backend
            if (!userName || !purpose || !email || !numParticipants) {
                displayMessage('Please fill in all required booking details.');
                return;
            }

            if (!currentSelectedSlot) { // Check for a single selected slot
                displayMessage('Please select one time slot.');
                return;
            }

            // Final client-side validation against all rules for the selected slot
            if (!validateSelectedSlotAgainstForm(currentSelectedSlot.element)) {
                return; // Messages already displayed by the validation function
            }

            const selectedTimeSlotsData = [{ // Always send exactly one slot
                roomId: currentSelectedSlot.roomId,
                timeStart: currentSelectedSlot.timeStart,
                timeLabel: currentSelectedSlot.timeLabel
            }];

            const bookingDetails = {
                name: userName,
                purpose: purpose,
                email: email,
                numParticipants: parseInt(numParticipants, 10), // Ensure integer
                bookingDate: bookingDate,
                selectedTimeSlots: selectedTimeSlotsData
            };

            console.log('Sending booking details:', bookingDetails);

            try {
                const response = await fetch('{{ route('coworking.book') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(bookingDetails)
                    });

                const result = await response.json();

                if (response.ok) {
                    showSuccessModal(result.bookingDetails);
                } else {
                    document.dispatchEvent(new CustomEvent('serverError', {
                        detail: result
                    }));
                }
            } catch (error) {
                console.error('Error during booking:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Network Error',
                    text: 'An unexpected network error occurred. Please check your connection and try again.',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
</script>