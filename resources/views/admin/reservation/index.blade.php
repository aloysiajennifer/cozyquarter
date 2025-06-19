@extends('admin.layoutAdmin')

@section('title', 'Reservation Management')

@php
use Carbon\Carbon;

$statuses = [
'0' => 'Reserved',
'1' => 'Attended',
'2' => 'Not Attended',
'3' => 'Cancelled',
'4' => 'Closed'
];

$statusBgColors = [
0 => 'bg-blue-500', // Reserved
1 => 'bg-green-600', // Attended
2 => 'bg-red-600', // Not Attended
3 => 'bg-orange-500', // Cancelled
4 => 'bg-gray-600', // Closed
'default' => 'bg-gray-400'
];
@endphp

@section('content')
<div class="p-4 sm:p-6 mt-14 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-center text-3xl font-semibold text-[var(--primary)] mt-2 mb-6 dark:text-white">Reservation Management</h1>

        

        <div class="bg-white p-6 rounded-xl shadow-lg mb-8">
            <form action="{{ route('reservation.index') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                    <div>
                        <label for="filter_date" class="block text-sm font-medium text-gray-700 mb-1">Filter by Date:</label>
                        <input type="date" id="filter_date" name="filter_date"
                            class="w-full px-4 py-2 rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition"
                            value="{{ request('filter_date') }}">
                    </div>

                    <div>
                        <label for="filter_cwspace" class="block text-sm font-medium text-gray-700 mb-1">Filter by CW Space:</label>
                        <select name="filter_cwspace" id="filter_cwspace"
                            class="w-full px-4 py-2 rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition">
                            <option value="">-- All CW Spaces --</option>
                            @foreach ($cwspaces as $cwspace)
                            <option value="{{ $cwspace->id }}" {{ request('filter_cwspace') == $cwspace->id ? 'selected' : '' }}>
                                {{ $cwspace->code_cwspace }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="filter_status" class="block text-sm font-medium text-gray-700 mb-1">Filter by Status:</label>
                        <select name="filter_status" id="filter_status"
                            class="w-full px-4 py-2 rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition">
                            <option value="">-- All Statuses --</option>
                            @foreach ($statuses as $key => $value)
                            <option value="{{ $key }}" {{ request('filter_status') == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="lg:col-span-2">
                        <label for="search_user" class="block text-sm font-medium text-gray-700 mb-1">Search by User Name:</label>
                        <input type="text" id="search_user" name="search_user"
                            class="w-full px-4 py-2 rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition"
                            placeholder="Enter user name..." value="{{ request('search_user') }}">
                    </div>

                    <div class="flex items-end gap-3">
                        <button type="submit" class="w-full bg-teal-600 text-white px-5 py-2.5 rounded-lg hover:bg-teal-700 font-medium text-sm text-center transition shadow">Search</button>
                        <a href="{{ route('reservation.index') }}" class="w-full bg-gray-600 text-white px-5 py-2.5 rounded-lg hover:bg-gray-700 font-medium text-sm text-center transition shadow">Reset</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="mb-6 space-y-4">
            @if (session('success'))
            <div class="flex items-center bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg shadow" role="alert">
                <p class="font-bold">{{ session('success') }}</p>
            </div>
            @endif
            @if (session('error'))
            <div class="flex items-center bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg shadow" role="alert">
                <p class="font-bold">{{ session('error') }}</p>
            </div>
            @endif
        </div>

        @if($selectedCwspaceObj && $selectedCwspaceObj->status_cwspace == 0)
        <div class="flex items-center bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg shadow mb-6" role="alert">
            <p class="font-bold">Co-working space ini sedang ditutup dan tidak memiliki jadwal.</p>
        </div>
        @elseif ($reservations->isEmpty())
        <div class="flex items-center bg-gray-100 border-l-4 border-gray-500 text-gray-700 px-4 py-3 rounded-lg shadow mb-6">
            <p class="font-bold">Tidak ada data reservasi yang ditemukan.</p>
        </div>
        @else
        <div class="relative overflow-x-auto shadow-xl sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                    <tr>
                        <th scope="col" class="px-6 py-3">No</th>
                        <th scope="col" class="px-6 py-3">Name</th>
                        <th scope="col" class="px-6 py-3">CW Space</th>
                        <th scope="col" class="px-6 py-3">Purpose</th>
                        <th scope="col" class="px-6 py-3">Datetime</th>
                        <th scope="col" class="px-6 py-3 text-center">Check-in / Out</th>
                        <th scope="col" class="px-6 py-3 text-center">Status</th>
                        <th scope="col" class="px-6 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reservations as $reservation)
                    <tr class="bg-white border-b hover:bg-gray-50 transition">
                        <td class="px-6 py-4">{{ $loop->iteration + ($reservations->currentPage() - 1) * $reservations->perPage() }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $reservation->user->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4">{{ $reservation->reservation_code_cwspace ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $reservation->purpose ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <div class="font-medium">{{ Carbon::parse($reservation->reservation_date)->translatedFormat('d F Y') }}</div>
                            <div class="text-xs text-gray-500">{{ Carbon::parse($reservation->reservation_start_time)->format('H:i') }} - {{ Carbon::parse($reservation->reservation_end_time)->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($reservation->check_in_time)
                            <div class="text-green-600 text-xs">In: {{ Carbon::parse($reservation->check_in_time)->format('H:i:s') }}</div>
                            @endif
                            @if($reservation->check_out_time)
                            <div class="text-red-600 text-xs">Out: {{ Carbon::parse($reservation->check_out_time)->format('H:i:s') }}</div>
                            @endif
                            @if(!$reservation->check_in_time && !$reservation->check_out_time)
                            <span>-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center">
                                <span class="w-24 inline-flex items-center justify-center py-1 px-2 text-xs font-semibold text-white rounded-full {{ $statusBgColors[$reservation->status_reservation] ?? $statusBgColors['default'] }}">
                                    {{ $statuses[$reservation->status_reservation] ?? 'Unknown' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                @if($reservation->status_reservation == 0)
                                <form action="{{ route('reservation.updateStatus', $reservation->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="1">
                                    <button type="submit" class="bg-[var(--highlight)] hover:bg-yellow-600 text-white font-semibold text-xs py-1.5 px-3 rounded-lg shadow-sm transition-colors" title="Mark as Attended" onclick="return confirm('Are you sure you want to mark this reservation as attended?')">
                                        Edit
                                    </button>

                                    
                                </form>
                                @elseif($reservation->status_reservation == 1) 
                                <form action="{{ route('reservation.updateStatus', $reservation->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="0">
                                    <button type="submit" class="text-blue-600 hover:text-blue-900" title="Batalkan Kehadiran (kembali ke Reserved)" onclick="return confirm('Cancel attendance? Status will revert to RESERVED.')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </form>
                                @else
                                <span class="text-gray-400">-</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $reservations->appends(request()->except('page'))->links('pagination::tailwind') }}
        </div>
        @endif
    </div>
</div>

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

@if(session('error') || $errors->any())
<script>
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        html: '{!! session("error") ?? implode("<br>", $errors->all()) !!}',
        confirmButtonText: 'OK',
        confirmButtonColor: '#ef4444'
    });
</script>
@endif

@if(session('info'))
<script>
    Swal.fire({
        icon: 'info',
        title: 'Info',
        text: '{{ session("info") }}',
        confirmButtonText: 'OK',
        confirmButtonColor: '#3b82f6'
    });
</script>
@endif

<script>
    // Prevent showing old alerts when pressing back
    window.addEventListener('pageshow', function(event) {
        if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
            window.location.reload();
        }
    });
</script>

@endsection