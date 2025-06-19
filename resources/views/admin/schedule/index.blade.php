@extends('admin.layoutAdmin')

@section('title', 'Schedule Management')

@php
use Carbon\Carbon;

// Definisikan label dan warna untuk status jadwal
$scheduleStatusLabels = [
0 => 'Closed',
1 => 'Available',
2 => 'Reserved'
];
$scheduleStatusBgColors = [
0 => 'bg-red-600',
1 => 'bg-green-600',
2 => 'bg-yellow-500',
'default' => 'bg-gray-500'
];
@endphp

@section('content')
<div class="p-4 sm:p-6 mt-14 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-center text-3xl font-semibold text-[var(--primary)] mt-2 mb-6 dark:text-white">Schedule Management</h1>


        <div class="bg-white p-6 rounded-xl shadow-lg mb-8">
            <form method="GET" action="{{ route('schedule.index') }}" id="scheduleFilterForm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Choose Date:</label>
                        <div class="relative">
                            <input type="text" id="date" name="date"
                                class="w-full pl-4 pr-10 py-2 rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition cursor-pointer"
                                value="{{ $selectedDate }}" placeholder="Pilih tanggal..." readonly>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="cwspace" class="block text-sm font-medium text-gray-700 mb-1">Filter by CW Space:</label>
                        <select name="cwspace" id="cwspace"
                            class="w-full px-4 py-2 rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition"
                            onchange="this.form.submit()">
                            <option value="">-- All CW Spaces --</option>
                            @foreach ($cwspaces as $space)
                            <option value="{{ $space->id }}" {{ request('cwspace') == $space->id ? 'selected' : '' }}>
                                {{ $space->code_cwspace }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>

        @if ($selectedCwspaceObj && $selectedCwspaceObj->status_cwspace == 0)
        <div class="flex items-center bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg shadow mb-6" role="alert">
            <p class="font-bold">Co-working space is closed and has no schedule.</p>
        </div>
        @elseif ($schedules && count($schedules) > 0)
        <div class="relative overflow-x-auto shadow-xl sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                    <tr>
                        <th scope="col" class="px-6 py-3">No</th>
                        <th scope="col" class="px-6 py-3">Time</th>
                        <th scope="col" class="px-6 py-3">CW Space</th>
                        <th scope="col" class="px-6 py-3 text-center">Status</th>
                        <th scope="col" class="px-6 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($schedules as $schedule)
                    <tr class="bg-white border-b hover:bg-gray-50 transition">
                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ Carbon::parse($schedule->time->start_time)->format('H:i') }} - {{ Carbon::parse($schedule->time->end_time)->format('H:i') }}</td>
                        <td class="px-6 py-4">{{ $schedule->cwspace->code_cwspace }}</td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center">
                                <span class="w-24 inline-flex items-center justify-center py-1 px-2 text-xs font-semibold text-white rounded-full {{ $scheduleStatusBgColors[$schedule->status_schedule] ?? $scheduleStatusBgColors['default'] }}">
                                    {{ $scheduleStatusLabels[$schedule->status_schedule] ?? 'Unknown' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if ($schedule->status_schedule == 2)
                            <span class="text-gray-400">-</span>
                            @else
                            <button data-modal-target="schedule-edit-{{ $schedule->id }}" data-modal-toggle="schedule-edit-{{ $schedule->id }}"
                                class="bg-[var(--highlight)] hover:bg-yellow-600 text-white font-semibold text-xs py-1.5 px-3 rounded-lg shadow-sm transition-colors" type="button">
                                Edit
                            </button>
                            @endif
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-12 bg-white rounded-xl shadow-lg">
            <p class="text-gray-500 text-lg">There are no schedules found for the selected criteria.</p>
        </div>
        @endif
    </div>
</div>

@if(!empty($schedules))
@foreach ($schedules as $schedule)
<div id="schedule-edit-{{ $schedule->id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow">
            <div class="flex items-center justify-between p-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Edit Schedule Status</h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="schedule-edit-{{ $schedule->id }}">
                    ✕<span class="sr-only">Close modal</span>
                </button>
            </div>
            <form action="{{ route('schedule.update', $schedule->id) }}" method="POST" class="p-4">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label for="status_schedule_{{ $schedule->id }}" class="block mb-2 text-sm font-medium text-gray-900">Status</label>
                        <select name="status_schedule" id="status_schedule_{{ $schedule->id }}" class="w-full px-4 py-2 rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition" required>
                            <option value="0" {{ $schedule->status_schedule == 0 ? 'selected' : '' }}>Closed</option>
                            <option value="1" {{ $schedule->status_schedule == 1 ? 'selected' : '' }}>Available</option>
                        </select>
                    </div>
                    <div class="flex justify-end pt-3 border-t">
                        <button type="submit" class="bg-teal-600 text-white rounded-lg px-5 py-2.5 hover:bg-teal-700 font-medium text-sm text-center transition">Update Status</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endif


<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Inisialisasi Flatpickr
        flatpickr("#date", {
            altInput: true,
            altFormat: "d F Y",
            dateFormat: "Y-m-d",
            enable: [
                @foreach($operationalDays as $day)
                "{{ Carbon::parse($day->date)->format('Y-m-d') }}",
                @endforeach
            ],
            onChange: function(selectedDates, dateStr, instance) {
                document.getElementById('scheduleFilterForm').submit();
            }
        });
    });

    window.addEventListener('pageshow', function(event) {
        if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
            window.location.reload();
        }
    });
</script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session("success") }}',
        confirmButtonColor: '#14b8a6'
    });
</script>
@endif

@if($errors->any())
<script>
    Swal.fire({
        icon: 'error',
        title: 'Input Is Not Valid!',
        html: `{!! implode('<br>', $errors->all()) !!}`,
        confirmButtonColor: '#ef4444'
    });
</script>
@endif


@endsection