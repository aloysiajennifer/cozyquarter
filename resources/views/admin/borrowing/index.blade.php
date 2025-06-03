@extends('admin.layoutAdmin')

@section('title', 'CRUD - Home Borrowing')
@section('content')

<div class="pt-16">
    <div class="w-full p-4 min-h-screen">
        <h1 class="text-center text-3xl font-semibold text-[var(--primary)] mt-2 mb-6">Borrowing List</h1>

        <form action="{{ route('borrowing.index') }}" method="GET" class="max-w-3xl mx-auto mb-4">    
            <div class="flex flex-wrap gap-4 items-center">

                {{-- Search input --}}
                <div class="relative flex-grow">
                        <label for="search" class="mb-2 text-sm font-medium text-primary sr-only">Search</label>
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                        </div>
                        <input type="search" name="search" id="search"
                            class="block w-full p-4 pl-10 text-sm text-primary border border-gray-300 rounded-lg bg-gray-50 focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)]"
                            placeholder="Search by user or title..." value="{{ request('search') }}">
                        <button type="submit"
                            class="bg-[var(--accent-blue)] text-white hover:bg-[var(--accent-green)] focus:ring-4 focus:ring-cyan-200 focus:outline-none font-medium rounded-lg text-sm px-4 py-2 absolute right-2.5 bottom-2.5">
                            Search
                        </button>
                    </div>

                    <div class="flex bg-gray-50 text-sm text-primary border border-gray-300 rounded-lg focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)]
                                justify-center items-center h-full">
                        {{-- Start Date Picker --}}
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                {{-- <i class="far fa-calendar"></i> --}}
                            </div>
                            <input type="date" name="start_date" id="start_date"
                                class="block p-4 pl-10 text-sm text-primary border-r border-gray-300 rounded-lg bg-gray-50 focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)]"
                                value="{{ request('start_date') }}">
                        </div>

                        {{-- End Date Picker --}}
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                {{-- <i class="far fa-calendar"></i> --}}
                            </div>
                            <input type="date" name="end_date" id="end_date"
                                class="block w-full p-4 pl-10 text-sm text-primary rounded-lg bg-gray-50 focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)]"
                                value="{{ request('end_date') }}">
                        </div>

                        <div></div>
                    </div>
                    

                </div>
            </form>



        <div class="relative overflow-x-auto overflow-y-auto max-h-[80vh] rounded-lg shadow">
            <table class="min-w-full text-sm text-left text-gray-500 bg-white border border-gray-200">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 sticky top-0 z-10">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-primary whitespace-nowrap">No</th>
                        <th scope="col" class="px-6 py-3 text-primary whitespace-nowrap">Borrowing Date</th>
                        <th scope="col" class="px-6 py-3 text-primary whitespace-nowrap">Borrower's Name</th>
                        <th scope="col" class="px-6 py-3 text-primary whitespace-nowrap">Borrowed Book</th>
                        <th scope="col" class="px-6 py-3 text-primary whitespace-nowrap">Return Due</th>
                        <th scope="col" class="px-6 py-3 text-primary whitespace-nowrap">Status</th>
                        <th scope="col" colspan="2" class="px-6 py-3 text-primary whitespace-nowrap">Fine</th>
                    </tr>
                </thead>
                <tbody class="text-sm md:text-md">
                    @foreach ($borrowings as $brw)
                        <tr class="bg-white border-b hover:bg-gray-100">
                            <td class="border border-gray-300 px-6 py-3 text-primary whitespace-nowrap">{{ $loop->iteration }}</td>
                            <td class="border border-gray-300 px-6 py-3 text-primary whitespace-nowrap">{{ $brw->borrowing_date }}</td>
                            <td class="border border-gray-300 px-6 py-3 text-primary whitespace-nowrap">{{ $brw->user->name }}</td>
                            <td class="border border-gray-300 px-6 py-3 text-primary whitespace-nowrap">{{ $brw->book->title_book }}</td>
                            <td class="border border-gray-300 px-6 py-3 text-primary whitespace-nowrap">{{ $brw->return_due }}</td>
                            <td class="border border-gray-300 px-6 py-3 text-primary whitespace-nowrap">
                                @if ($brw->status_returned == 0)
                                    <form action="{{ route('borrowing.returned', $brw->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="id" value="{{ $brw->id }}">
                                        <button type="submit" class="text-white w-full px-6 py-1 rounded-full
                                             {{ \Carbon\Carbon::parse($brw->return_due)->isPast() ? 'bg-red-500 hover:bg-red-600' : 'bg-zinc-500 hover:bg-zinc-400' }}">Return</button>
                                    </form>
                                @else
                                    <p>Returned on {{ \Carbon\Carbon::parse($brw->return_date)->format('Y-m-d') }}</p>
                                @endif
                            </td>
                            <td class="border border-gray-300 px-6 py-3 text-primary whitespace-nowrap">
                                @if($brw->fine)
                                    <p>Rp{{ number_format($brw->fine->fine_total, 0, ',', '.') }} </p>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="border border-gray-300 px-2 py-1 whitespace-nowrap">
                                @if($brw->fine && $brw->fine->status_fine == 0)
                                    <form action="{{ route('fine.paid', $brw->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="id" value="{{ $brw->fine->id }}">
                                        <button type="submit" class="bg-zinc-500 hover:bg-zinc-400 text-white w-full px-6 py-1 rounded-full">Pay Fine</button>
                                    </form>
                                @elseif($brw->fine)
                                    <p class="px-6 py-3 text-primary whitespace-nowrap">Paid on {{ \Carbon\Carbon::parse($brw->fine->date_finepayment)->format('Y-m-d') }}</p>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


        <div class="mb-4 mt-4 text-right">
            <a href="{{ url('/borrowing/form') }}">
                <button
                    class="bg-[var(--accent-blue)] text-white hover:bg-[var(--accent-green)] focus:ring-4 focus:ring-cyan-200 font-medium rounded-lg text-sm px-5 py-2.5">
                    Add Borrowing
                </button>
            </a>
        </div>

    </div>
</div>


{{-- SUCCESS SWAL --}}
@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: "{{ session('success') }}",
            confirmButtonText: 'OK'
        });
    </script>
@endif
    
@endsection
