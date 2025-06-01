@extends('admin.layoutAdmin')

@section('title', 'CRUD - Schedule')

@section('content')

<div class="mt-16 p-4 ">
    <h1 class="text-center text-3xl font-semibold text-[var(--primary)] mt-2 mb-6">Schedule List</h1>

    <form method="GET" action="{{ route('schedule.index') }}" class="mb-6">
        <!-- Filter Tanggal -->
        <label for="date" class="block text-sm font-semibold text-[var(--primary)] mb-2">Choose Operational Date:</label>
        <input type="text" id="date" name="date" class="w-full px-4 py-2 rounded-md border border-gray-300 bg-white text-[var(--text-primary)] focus:ring-2 focus:ring-[var(--accent-blue)] focus:outline-none shadow-sm" value="{{ $selectedDate }}" placeholder="Select date" readonly>



        <!-- Filter Cwspace -->
        <label for="cwspace" class="block text-sm font-semibold text-[var(--primary)] mb-2">Filter by CW Space:</label>
        <select name="cwspace" id="cwspace" class="w-full px-4 py-2 rounded-md border border-gray-300 bg-white text-[var(--text-primary)] focus:ring-2 focus:ring-[var(--accent-blue)] focus:outline-none shadow-sm" onchange="this.form.submit()">
            <option value="">-- All CW Spaces --</option>
            @foreach ($cwspaces as $space)
            <option value="{{ $space->id }}" {{ request('cwspace') == $space->id ? 'selected' : '' }}>
                {{ $space->code_cwspace }}
            </option>
            @endforeach
        </select>
    </form>

    @if ($schedules)
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-md text-center rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Time</th>
                    <th scope="col" class="px-6 py-3">CW Space</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Edit</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($schedules as $schedule)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                    <td class="px-4 py-2">{{ $schedule->time->start_time }} - {{ $schedule->time->end_time }}</td>
                    <td class="px-4 py-2">{{ $schedule->cwspace->code_cwspace }}</td>
                    <td class="px-4 py-2">
                        @php
                        $statusLabels = ['Closed', 'Available', 'Reserved'];
                        $statusColors = ['text-red-600', 'text-green-600', 'text-yellow-600'];
                        @endphp
                        <span class="{{ $statusColors[$schedule->status_schedule] }}">
                            {{ $statusLabels[$schedule->status_schedule] }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <button data-modal-target="schedule-edit-{{ $schedule->id }}" data-modal-toggle="schedule-edit-{{ $schedule->id }}"
                            class="block mx-auto text-white bg-amber-400 hover:bg-amber-500 focus:ring-4 focus:outline-none focus:ring-amber-400 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                            type="button">
                            Edit
                        </button>
                    </td>
                </tr>

                <!-- Modal Edit -->
                <div id="schedule-edit-{{ $schedule->id }}" tabindex="-1" aria-hidden="true"
                    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="relative p-4 w-full max-w-lg max-h-full">
                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                <h3 class="text-xl font-semibold text-gray-600 dark:text-white">
                                    Edit Schedule Status
                                </h3>
                                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                    data-modal-hide="schedule-edit-{{ $schedule->id }}">
                                    ✕
                                </button>
                            </div>

                            <div class="p-4">
                                <form action="{{ route('schedule.update', $schedule->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="space-y-4">
                                        <div>
                                            <label for="status_schedule_{{ $schedule->id }}" class="block text-sm font-medium text-gray-900 dark:text-white">Status</label>
                                            <select name="status_schedule" id="status_schedule_{{ $schedule->id }}"
                                                class="border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5" required>
                                                <option value="0" {{ $schedule->status_schedule == 0 ? 'selected' : '' }}>Closed</option>
                                                <option value="1" {{ $schedule->status_schedule == 1 ? 'selected' : '' }}>Available</option>
                                                <option value="2" {{ $schedule->status_schedule == 2 ? 'selected' : '' }}>Reserved</option>
                                            </select>
                                        </div>

                                        <div class="flex justify-end pt-3 border-t">
                                            <button type="submit"
                                                class="bg-blue-700 text-white rounded-lg px-4 py-2 hover:bg-blue-800">Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>
    @elseif ($selectedDate)
    <p class="text-gray-800 mt-4">No schedules found for selected date.</p>
    @endif
</div>

<!-- Pilih tanggal -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        flatpickr("#date", {
            dateFormat: "Y-m-d", // format untuk value input
            enable: [
                @foreach($operationalDays as $day)
                "{{ \Carbon\Carbon::parse($day->date)->format('Y-m-d') }}",
                @endforeach
            ],
            onChange: function(selectedDates, dateStr, instance) {
                instance.input.form.submit(); // submit otomatis saat pilih tanggal
            }
        });
    });
</script>

{{-- SweetAlert Success --}}
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session("success") }}',
        confirmButtonText: 'OK',
        confirmButtonColor: '#22c55e'
    });
</script>
@endif

{{-- SweetAlert Validasi --}}
@if($errors->any())
<script>
    Swal.fire({
        icon: 'error',
        title: 'Input Tidak Valid!',
        html: `{!! implode('<br>', $errors->all()) !!}`,
        confirmButtonText: 'Try Again',
        confirmButtonColor: '#ef4444'
    });
</script>
@endif

<script>
    // Prevent showing old alerts when pressing back
    window.addEventListener('pageshow', function(event) {
        if (event.persisted || (window.performance && performance.navigation.type === 2)) {
            window.location.reload();
        }
    });
</script>
@endsection