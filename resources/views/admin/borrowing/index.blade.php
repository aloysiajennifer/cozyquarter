@extends('layout')

@section('content')

{{-- Menampilkan list pemesanan lapangan untuk semua tanggal --}}
<div class="px-12 md:px-24 py-5 mb-15">

    <div class="flex flex-wrap justify-between items-center mb-1.5">
        <h3 class="text-zinc-900 text-left text-xl md:text-2xl font-bold tracking-tight w-full md:w-auto">Borrowings</h3>
    </div>
        
    <div class="flex justify-end">
        <a href="{{ url('/borrowing/form') }}" class="bg-zinc-500 hover:bg-zinc-400 text-white px-6 py-1 rounded">
          + New Borrowing</a>
    </div>

    {{-- <h3 class="text-zinc-900 text-left text-xl md:text-2xl font-bold tracking-tight mt-8 mb-2">List</h3> --}}
    <div class="my-8 overflow-x-auto" id="borrowing_list">
        <table border="1" cellpadding="5" class="w-full table-auto text-center border-collapse border border-gray-400">
            <thead class="text-md md:text-lg">
                <th class="border border-gray-300 px-2 py-1">No.</th>
                <th class="border border-gray-300 px-2 py-1">Borrowing Date</th>
                <th class="border border-gray-300 px-2 py-1">Borrower's Name</th>
                <th class="border border-gray-300 px-2 py-1">Borrowed Book</th>
                <th class="border border-gray-300 px-2 py-1">Return Date</th>
                <th class="border border-gray-300 px-2 py-1">Status</th>
                {{-- <th class="border border-gray-300 px-2 py-1">Fine</th> --}}
            </thead>
            <tbody class="text-sm md:text-md">
                @foreach ($borrowings as $brw)
                    <tr>
                        <td class="border border-gray-300 px-2 py-1">{{ $loop->iteration }}</td>
                        <td class="border border-gray-300 px-2 py-1">{{ $brw->borrowing_date }}</td>
                        <td class="border border-gray-300 px-2 py-1">{{ $brw->user->name }}</td>
                        <td class="border border-gray-300 px-2 py-1">{{ $brw->book->title_book }}</td>
                        <td class="border border-gray-300 px-2 py-1">{{ $brw->return_date }}</td>
                        {{-- <td class="border border-gray-300 px-2 py-1">
                            @if ($pl->is_boarding == 0)
                                <form action="{{ route('board', $pl->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="bg-zinc-500 hover:bg-zinc-400 text-white px-6 py-1 rounded-full">Confirm</button>
                                </form>
                            @else
                                {{ \Carbon\Carbon::parse($pl->boarding_time)->format('d-m-Y, H:i') }}
                            @endif
                        </td> --}}

                        {{-- <td class="flex justify-center gap-2 px-2 py-1">
                            <a href="{{ route('edit', $lp->id) }}" class="bg-zinc-300 hover:bg-zinc-200 px-6 py-1 rounded">
                                Edit</a>

                            <form id="delete-form-{{$lp->id}}" action=" {{route('delete', ['id' => encrypt($lp->id)]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                            </form>
                            <button onclick="confirmDelete({{ $lp->id }})" class="bg-zinc-300 hover:bg-zinc-200 px-6 py-1 rounded">Delete</button>
                        </td> --}}

                        
                    </tr>
                @endforeach
            </tbody>
        </table>
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