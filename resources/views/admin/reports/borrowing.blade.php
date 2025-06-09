@extends('admin.layoutAdmin')

@section('title', 'CRUD - Report Borrowing')
@section('content')

<div class="pt-16">
    <div class="w-full p-4 min-h-screen">
        <h1 class="text-center text-3xl font-semibold text-[var(--primary)] mt-2 mb-6">Borrowing Report</h1>

        <form action="{{ route('report.borrowing') }}" method="GET" class="max-w-4xl mx-auto mb-5" id="filterForm">    
            <div class="flex flex-wrap gap-4 items-center justify-center">

                    {{-- FILTER --}}
                    <div class="flex relative bg-gray-50 text-sm text-primary border border-gray-300 rounded-lg focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)]
                                justify-center items-center h-full">
                        {{-- Start Date Picker --}}
                        <div class="flex flex-col pl-4 pr-2 border-r border-gray-300 rounded-lg">
                            <label for="start_date" class="my-1 text-sm font-medium text-gray-700 ">Start Date</label>
                            <input type="date" placeholder="Start Date" name="start_date" id="start_date"
                                class="block pb-3 px-3 text-sm text-primary bg-gray-50 focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)]"
                                value="{{ request('start_date') }}">
                        </div>

                        {{-- End Date Picker --}}
                        <div class="flex flex-col pl-4 pr-2">
                            <label for="end_date" class="my-1 text-sm font-medium text-gray-700 ">End Date</label>
                            <input type="date" placeholder="Start Date" name="end_date" id="end_date"
                                class="block pb-3 px-3 text-sm text-primary bg-gray-50 focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)]"
                                value="{{ request('end_date') }}">
                        </div>
                        {{-- Button Filter --}}
                        <div class="px-3">
                            <button type="submit"
                                class="w-18 h-10 bg-[var(--accent-blue)] text-white hover:bg-[var(--accent-green)] focus:ring-4 focus:ring-cyan-200 focus:outline-none font-medium rounded-lg text-sm px-4 py-2">
                                Filter
                            </button>
                        </div>
                    </div>

                    {{-- Reset Button --}}
                     <button type="button" id="resetBtn"
                        class="text-white bg-amber-400 hover:bg-amber-500 focus:ring-4 
                                focus:outline-none focus:ring-amber-400 font-medium rounded-lg text-sm px-4 py-2">
                        Reset
                    </button>
                    

                </div>
            </form>



        <div class="relative overflow-x-scroll overflow-y-scroll max-h-[80vh] rounded-lg shadow">
            <table class="min-w-full text-sm text-left text-gray-500 bg-white border border-gray-200 border-collapse">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 sticky top-0 z-50">
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
                                    Unreturned
                                @else
                                    <p>Returned on {{ \Carbon\Carbon::parse($brw->return_date)->format('Y-m-d') }}</p>
                                @endif
                            </td>
                            <td class="border border-gray-300 px-6 py-3 text-primary whitespace-nowrap">
                                @if(isset($brw->fine->fine_total) && $brw->fine->fine_total > 0)
                                    <p>Rp{{ number_format($brw->fine->fine_total, 0, ',', '.') }} </p>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="border border-gray-300 px-2 py-1 whitespace-nowrap">
                                @if($brw->fine && $brw->fine->status_fine == 0)
                                    Unpaid
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
            <a href="{{ route('report.borrowingPDF', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}">
                <button
                    class="bg-rose-600 hover:bg-rose-500 text-white focus:ring-4 focus:ring-rose-200 font-medium rounded-lg text-sm px-5 py-2.5">
                    Export to PDF
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

<script>
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;

        if (startDate && endDate) {
            if (startDate >= endDate) {
                e.preventDefault(); 
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Start Date must be before End Date!',
                });
            }
        }
    });

    // Reset untuk search bar dan filter (kembali ke default)
    document.getElementById('resetBtn').addEventListener('click', function() {
        window.location.href = "{{ route('report.borrowing') }}";
    });

</script>
    
@endsection
