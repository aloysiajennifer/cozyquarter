@extends('admin.layoutAdmin')

<!-- Title page -->
@section('title', 'CRUD - Add Borrowing')

@section('content')
    <div class="mt-16 p-4">
        <div class="container mx-auto p-4">
            <div class="text-left">
                <a href="{{ route('borrowing.index') }}">
                    <button
                        class="text-white bg-gray-500 hover:bg-gray-600 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5">
                        Back
                    </button>
                </a>
            </div>

            <h1 class="text-center text-3xl font-semibold text-[var(--primary)] mb-6">Add Borrowing Form</h1>
            <form action="{{ route('borrowing.insert') }}" method="POST" enctype="multipart/form-data" id="borrowingForm">
                @csrf
                <div class="max-w-3xl mx-auto">
                    {{-- Borrowing Date --}}
                    <div class="mb-6 flex">
                        <label for="borrowing_date" class="w-1/3 block mb-2 text-sm font-medium text-[var(--primary)] ">Borrowing Date</label>
                        <input type="text" id="borrowing_date" 
                            class="bg-gray-200 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)] block w-2/3 p-2.5"
                            value="{{ $borrowingDate }}" readonly/>
                        <input type="hidden" name="borrowing_date" value="{{ $borrowingDate }}" />
                    </div>

                    {{-- Return Due --}}
                     <div class="mb-6 flex">
                        <label for="return_due"
                            class="w-1/3 block mb-2 text-sm font-medium text-[var(--primary)] ">Return Due Date</label>
                        <input type="text" id="return_due"
                            class="bg-gray-200 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)] block w-2/3 p-2.5"
                            value=" {{ $returnDue }}" readonly />
                        <input type="hidden" name="return_due" value="{{ $returnDue }}" />
                    </div>

                    {{-- Dropdown Borrower's Name --}}
                    <div class="mb-6 flex">
                        <label for="borrower_name" class="w-1/3 block mb-2 text-sm font-medium text-[var(--primary)]">Borrower's Name</label>
                        <select name="id_user" id="user" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)] block w-2/3 p-2.5" required>
                            <option value="">Select Borrower</option>
                                @foreach ($listUsers as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                        </select>
                    </div>

                    {{-- Dropdown Borrowed Book --}}
                    <div class="mb-6 flex">
                        <label for="borrowed_book" class="w-1/3 block mb-2 text-sm font-medium text-[var(--primary)]">Borrowed Book</label>
                        <select name="id_book" id="book" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)] block w-2/3 p-2.5" required>
                            <option value="">Select Book</option>
                                @foreach ($listBooks as $book)
                                    <option value="{{ $book->id }}">{{ $book->title_book }} - {{ $book->author_book }}</option>
                                @endforeach
                        </select>
                    </div>

                <div class="text-right">
                    <button type="button" id="btnSubmit" class="text-white bg-[var(--accent-blue)] hover:bg-[var(--accent-green)] focus:ring-4 focus:outline-none focus:ring-cyan-200 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Save</button>
                </div>  
            </div>
        </form>
    </div>


    <script>
        // Konfirmasi submit
        document.getElementById('btnSubmit').addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to save this borrowing?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6', // biru
                cancelButtonColor: '#d33', // merah
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('borrowingForm').submit();
                }
            });
        });
    </script>

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


    @if ($errors->any())
        <script>
            Swal.fire({
                title: 'Validation Error!',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        </script>
    @endif

    </div>

@endsection