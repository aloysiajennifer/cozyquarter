@extends('admin.layoutAdmin')

@section('title', 'CRUD - Home Borrowing')
@section('content')

<div class="pt-16">

    <div class="w-full p-4 min-h-screen">
        <div class="relative overflow-x-auto overflow-y-auto max-h-[80vh]">
            <table class="w-full text-sm text-left text-gray-500 bg-white border border-gray-200 rounded-lg shadow-md">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 sticky top-0 z-10">
                <tr>
                <th class=th scope="col" class="px-6 py-3 text-primary">No</th>
                <th class=th scope="col" class="px-6 py-3 text-primary">Borrowing Date</th>
                <th class=th scope="col" class="px-6 py-3 text-primary">Borrower's Name</th>
                <th class=th scope="col" class="px-6 py-3 text-primary">Borrowed Book</th>
                <th class=th scope="col" class="px-6 py-3 text-primary">Return Due</th>
                <th class=th scope="col" class="px-6 py-3 text-primary">Status</th>
                <th class=th scope="col" class="px-6 py-3 text-primary">Fine</th>
                </tr>
            </thead>
            <tbody class="text-sm md:text-md">
                @foreach ($borrowings as $brw)
                    <tr class="bg-white border-b hover:bg-gray-100">
                        <td class="border border-gray-300 px-2 py-1">{{ $loop->iteration }}</td>
                        <td class="border border-gray-300 px-2 py-1">{{ $brw->borrowing_date }}</td>
                        <td class="border border-gray-300 px-2 py-1">{{ $brw->user->name }}</td>
                        <td class="border border-gray-300 px-2 py-1">{{ $brw->book->title_book }}</td>
                        <td class="border border-gray-300 px-2 py-1">{{ $brw->return_due }}</td>
                        <td class="border border-gray-300 px-2 py-1">
                            @if ($brw->status_returned == 0)
                                <form action="{{ route('borrowing.returned', $brw->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="id" value="{{ $brw->id }}">
                                    <button type="submit" class="bg-zinc-500 hover:bg-zinc-400 text-white px-6 py-1 rounded-full">Return</button>
                                </form>
                            @else
                                <p>Returned on {{ \Carbon\Carbon::parse($brw->return_date)->format('Y-m-d') }}</p>
                                
                            @endif
                        </td>
                        {{-- Fine --}}
                        <td class="border border-gray-300 px-2 py-1">
                            @if($brw->fine)
                                Rp{{ number_format($brw->fine->fine_total, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="border border-gray-300 px-2 py-1">
                            @if($brw->fine)
                                @if ($brw->fine->status_fine == 0)
                                    <form action="{{ route('fine.paid', $brw->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="id" value="{{ $brw->id }}">
                                        <button type="submit" class="bg-zinc-500 hover:bg-zinc-400 text-white px-6 py-1 rounded-full">Pay Fine</button>
                                    </form>
                                @else
                                    <p>Paid on {{ \Carbon\Carbon::parse($brw->fine->date_finepayment)->format('Y-m-d') }}</p>
                                    
                                @endif
                            @endif
                        </td>

                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


<script>
    function confirmDelete(id) {
        Swal.fire({
            title: "Are you sure?",
            text: "This data will be deleted permanently!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById("delete-form-" + id).submit();
            }
        });
    }
    </script>
@endsection