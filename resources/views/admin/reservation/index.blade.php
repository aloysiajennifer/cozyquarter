@extends('admin.layoutAdmin')

@section('title', 'CRUD Reservation')

<!-- buat formatting tgl -->
@php
    use Carbon\Carbon;
@endphp

@section('content')

<div class="mt-16 p-4">
    <h1 class="text-center text-3xl font-semibold text-[var(--primary)] mt-2 mb-6">Reservation List</h1>

    <!-- search n filter -->
    <form action="{{ route('reservation.index') }}" method="GET" class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:gap-6 mb-6">

            <div class="flex flex-col md:flex-row md:items-center md:gap-3 mb-4 md:mb-0 md:flex-1">
                <label for="filter_date" class="font-semibold text-[var(--primary)] mb-1 md:mb-0 md:w-48">
                    Filter by Reservation Date:
                </label>
                <input type="date" id="filter_date" name="filter_date"
                    class="w-full px-4 py-2 rounded-md border border-gray-300 bg-white text-[var(--text-primary)] shadow-sm focus:ring-2 focus:ring-[var(--accent-blue)] focus:outline-none"
                    value="{{ request('filter_date') }}">
            </div>

            <div class="flex flex-col md:flex-row md:items-center md:gap-3 md:flex-1">
                <label for="filter_cwspace" class="font-semibold text-[var(--primary)] mb-1 md:mb-0 md:w-48">
                    Filter by CW Space:
                </label>
                <select name="filter_cwspace" id="filter_cwspace"
                    class="w-full px-4 py-2 rounded-md border border-gray-300 bg-white text-[var(--text-primary)] shadow-sm focus:ring-2 focus:ring-[var(--accent-blue)] focus:outline-none">
                    <option value="">-- All CW Spaces --</option>
                    @foreach ($cwspaces as $cwspace)
                        <option value="{{ $cwspace->id }}" {{ request('filter_cwspace') == $cwspace->id ? 'selected' : '' }}>
                            {{ $cwspace->code_cwspace }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex flex-col md:flex-row md:items-center md:gap-6">
            <div class="flex flex-col md:flex-row md:items-center md:gap-3 md:flex-1">
                <label for="search_user" class="font-semibold text-[var(--primary)] mb-1 md:mb-0 md:w-48">
                    Search by User Name:
                </label>
                <input type="text" id="search_user" name="search_user"
                    class="w-full px-4 py-2 rounded-md border border-gray-300 bg-white text-[var(--text-primary)] shadow-sm focus:ring-2 focus:ring-[var(--accent-blue)] focus:outline-none"
                    placeholder="Enter user name" value="{{ request('search_user') }}">
            </div>

            <div class="flex md:flex-1 md:justify-end gap-3 mt-4 md:mt-0">
                <button type="submit" class="bg-[var(--accent-blue)] text-white px-5 py-2.5 rounded-lg hover:bg-teal-500 font-medium text-sm text-center">
                    Search
                </button>
                <a href="{{ route('reservation.index') }}" class="hover:bg-gray-400 text-white btn btn-secondary ml-2 px-5 py-2.5 rounded-lg bg-[var(--secondary)] font-medium text-sm text-center">Reset</a>
            </div>
        </div>
    </form>

    {{-- Session Messages --}}
    @if (session('success'))
        <div class="flex items-center bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mt-4" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
            </svg>
            <span class="pl-3 block font-semibold">{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="flex items-center bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-4" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
            </svg>
            <span class="pl-3 block font-semibold">{{ session('error') }}</span>
        </div>
    @endif
    @if (session('info'))
        <div class="flex items-center bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mt-4" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
            </svg>
            <span class="pl-3 block font-semibold">{{ session('info') }}</span>
        </div>
    @endif

    {{-- SweetAlert Validasi --}}
    @if($errors->any())
        <div class="flex items-center bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-4" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
            </svg>
            <span class="pl-3 block font-semibold">
                Input Tidak Valid!
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </span>
        </div>
    @endif

    @if ($reservations->isEmpty())
        <div class="flex items-center bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mt-4" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
            </svg>
            <span class="pl-3 block font-semibold">Tidak ada data reservasi yang ditemukan.</span>
        </div>
    @else
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-md text-center rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">No</th>
                        <th scope="col" class="px-6 py-3">Nama Peminjam</th>
                        <th scope="col" class="px-6 py-3">CW Space</th>
                        <th scope="col" class="px-6 py-3">Tanggal Reservasi</th>
                        <th scope="col" class="px-6 py-3">Waktu Reservasi</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                        <th scope="col" class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reservations as $reservation)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                            <td class="px-6 py-4">{{ $loop->iteration + ($reservations->currentPage() - 1) * $reservations->perPage() }}</td>
                            <td class="px-6 py-4">{{ $reservation->user->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4">{{ $reservation->reservation_code_cwspace ?? 'N/A' }}</td>
                            <td class="px-6 py-4">{{ Carbon::parse($reservation->reservation_date)->format('d-m-Y') }}</td>
                            <td class="px-6 py-4">
                                {{ Carbon::parse($reservation->reservation_start_time)->format('H:i') }} - {{ Carbon::parse($reservation->reservation_end_time)->format('H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    // Status mapping: NULL=Pending, 0=Attended, 1=Not Attended, 2=Cancelled, 3=Closed
                                    $statusLabels = [
                                        null => 'Belum Jadwalnya',
                                        0 => 'Attended',
                                        1 => 'Not Attended',
                                        2 => 'Cancelled',
                                        3 => 'Closed'
                                    ];
                                    $statusColors = [
                                        null => 'text-blue-500', // Pending
                                        0 => 'text-green-600',  // Attended
                                        1 => 'text-yellow-600', // Not Attended
                                        2 => 'text-gray-600',   // Cancelled
                                        3 => 'text-red-600'     // Closed
                                    ];
                                @endphp
                                <span class="{{ $statusColors[$reservation->status_reservation] ?? 'text-gray-500' }} font-semibold">
                                    {{ $statusLabels[$reservation->status_reservation] ?? 'Unknown Status' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                {{-- Tombol "Tandai Attended" hanya jika status NULL --}}
                                @if(is_null($reservation->status_reservation))
                                    <form action="{{ route('reservation.updateStatus', $reservation->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="0"> {{-- Ubah ke status Attended (0) --}}
                                        <button type="submit"
                                            class="block mx-auto text-white bg-green-500 hover:bg-green-600 focus:ring-4 focus:outline-none focus:ring-green-400 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                                            onclick="return confirm('Apakah Anda yakin ingin menandai reservasi ini sebagai \'Attended\'? Ini akan mencatat waktu check-in.')">
                                            Attended
                                        </button>
                                    </form>
                                @else {{-- Selain status NULL, tidak ada tombol aksi --}}
                                    <span class="text-gray-500">No Action</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- Pagination Links --}}
        <div class="d-flex justify-content-center mt-3">
            {{ $reservations->appends(request()->except('page'))->links('pagination::tailwind') }}
        </div>
    @endif
</div>

{{-- SweetAlerts and JS --}}
{{-- Make sure SweetAlert2 is loaded globally in your layoutAdmin.blade.php to avoid multiple CDN calls --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // SweetAlert Success
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session("success") }}',
            confirmButtonText: 'OK',
            confirmButtonColor: '#22c55e'
        });
    @endif

    // SweetAlert Error / Validation
    @if(session('error') || $errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            html: '{!! session('error') ?? implode('<br>', $errors->all()) !!}',
            confirmButtonText: 'OK',
            confirmButtonColor: '#ef4444'
        });
    @endif

    // SweetAlert Info
    @if(session('info'))
        Swal.fire({
            icon: 'info',
            title: 'Info!',
            text: '{{ session("info") }}',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3b82f6'
        });
    @endif

    // Prevent showing old alerts when pressing back
    window.addEventListener('pageshow', function(event) {
        if (event.persisted || (window.performance && performance.navigation.type === 2)) {
            window.location.reload();
        }
    });
</script>
@endsection