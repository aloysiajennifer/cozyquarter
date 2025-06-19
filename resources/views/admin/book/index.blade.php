@extends('admin.layoutAdmin')

<!-- Title page -->
@section('title', 'CRUD - Home Book')

@php
    $bookStatusLabels = [
    1 => 'Available',
    0 => 'Unavailable',
    ];
    $bookStatusBgColors = [
    1 => 'bg-green-600',
    0 => 'bg-red-600',
    'default' => 'bg-gray-500',
    ];
@endphp

@section('content')

<div class="pt-16">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-button');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const form = this.closest('form');
                    const bookTitle = this.getAttribute('data-title');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: `You are about to delete "${bookTitle}"`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>

    <div class="w-full p-4 min-h-screen">

        <h1 class="text-center text-3xl font-semibold text-[var(--primary)] mt-2 mb-6 dark:text-white">Book List</h1>

        <form action="{{ route('book.index') }}" method="GET" class="max-w-md mx-auto mb-4">
            <label for="search" class="mb-2 text-sm font-medium text-primary sr-only">Search</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="search" name="search" id="search"
                    class="block w-full p-4 pl-10 text-sm text-primary border border-gray-300 rounded-lg bg-gray-50 focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)]"
                    placeholder="Search by title..." value="{{ request('search') }}">
                <button type="submit"
                    class="bg-[var(--accent-blue)] text-white hover:bg-[var(--accent-green)] focus:ring-4 focus:ring-cyan-200 focus:outline-none font-medium rounded-lg text-sm px-4 py-2 absolute right-2.5 bottom-2.5">
                    Search
                </button>
            </div>
        </form>

        @if($message)
        @if($alertType === 'error')
        <div id="alert-error" class="flex items-center p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
            <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm.75 14.25a.75.75 0 0 1-1.5 0V11a.75.75 0 0 1 1.5 0Zm0-6a.75.75 0 0 1-1.5 0V7a.75.75 0 0 1 1.5 0Z" />
            </svg>
            <span class="sr-only">Error</span>
            <div class="ms-3 text-sm font-medium text-primary">
                {{ $message }}
            </div>
            <button type="button"
                class="ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-100 inline-flex h-8 w-8 justify-center items-center"
                data-dismiss-target="#alert-error" aria-label="Close">
                <span class="sr-only">Dismiss</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M1 1l12 12M13 1 1 13" />
                </svg>
            </button>
        </div>
        @elseif($alertType === 'info')
        <div id="alert-info" class="flex items-center p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50"
            role="alert">
            <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm.75 14.25a.75.75 0 0 1-1.5 0V11a.75.75 0 0 1 1.5 0Zm0-6a.75.75 0 0 1-1.5 0V7a.75.75 0 0 1 1.5 0Z" />
            </svg>
            <span class="sr-only">Info</span>
            <div class="ms-3 text-sm font-medium text-primary">
                {{ $message }}
            </div>
            <button type="button"
                class="ms-auto -mx-1.5 -my-1.5 bg-blue-50 text-blue-500 rounded-lg focus:ring-2 focus:ring-blue-400 p-1.5 hover:bg-blue-100 inline-flex h-8 w-8 justify-center items-center"
                data-dismiss-target="#alert-info" aria-label="Close">
                <span class="sr-only">Dismiss</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M1 1l12 12M13 1 1 13" />
                </svg>
            </button>
        </div>
        @endif
        @endif

        <div class="relative overflow-x-auto overflow-y-auto max-h-[80vh]">
            <table class="w-full text-sm text-left text-gray-500 bg-white border border-gray-200 rounded-lg shadow-md">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 sticky top-0 z-10">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-primary">No</th>
                        <th scope="col" class="px-6 py-3 text-primary">Cover</th>
                        <th scope="col" class="px-6 py-3 text-primary">Title</th>
                        <th scope="col" class="px-6 py-3 text-primary">Author</th>
                        <th scope="col" class="px-6 py-3 text-primary">ISBN</th>
                        <th scope="col" class="px-6 py-3 text-primary">Synopsis</th>
                        <th scope="col" class="px-6 py-3 text-primary">Category</th>
                        <th scope="col" class="px-6 py-3 text-primary">Shelf</th>
                        <th scope="col" class="px-6 py-3 text-primary">Status</th>
                        <th scope="col" class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($books as $book)
                    <tr class="bg-white border-b hover:bg-gray-100">
                        <td class="px-6 py-4 text-primary">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4">
                            <img src="{{ dd(asset('storage/' . $book->cover_book))}}" alt="{{ $book->title_book }}" class="w-16 h-20 object-cover rounded">
                        </td>
                        <td class="px-6 py-4 font-medium text-[var(--primary)] whitespace-nowrap">
                            {{ $book->title_book }}
                        </td>
                        <td class="px-6 py-4 text-[var(--primary)]">{{ $book->author_book }}</td>
                        <td class="px-6 py-4 text-[var(--primary)]">{{ $book->isbn_book }}</td>

                        <!-- sinopsis dicut-->
                        <td
                            class="px-6 py-4 max-w-[200px] overflow-hidden text-ellipsis whitespace-nowrap text-[var(--primary)]">
                            {{ $book->synopsis_book }}
                        </td>

                        <td class="px-6 py-4 text-[var(--primary)]">{{ $book->category->name_category }}</td>
                        <td class="px-6 py-4 text-[var(--primary)]">{{ $book->shelf->code_shelf }}</td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center">
                                <span class="w-20 inline-flex items-center justify-center py-1 px-2 text-xs font-semibold text-white rounded-full {{ $bookStatusBgColors[$book->status_book] ?? $bookStatusBgColors['default'] }}">
                                    {{ $bookStatusLabels[$book->status_book] ?? 'Unknown' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2 justify-end">
                                <a href="{{ url('admin/book/detail/' . encrypt($book->id)) }}">
                                    <button type="button"
                                        class="bg-[var(--highlight)] text-white hover:bg-amber-400 focus:ring-4 focus:ring-amber-200 font-medium rounded-lg text-sm px-5 py-2.5 me-2">
                                        Edit
                                    </button>
                                </a>
                                <form action="{{ route('book.delete', ['id' => encrypt($book->id)]) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    <button type="button"
                                        class="delete-button text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5"
                                        data-title="{{ $book->title_book }}">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mb-4 mt-4 text-right">
            <a href="{{ url('admin/book/form') }}">
                <button
                    class="bg-[var(--accent-blue)] text-white hover:bg-[var(--accent-green)] focus:ring-4 focus:ring-cyan-200 font-medium rounded-lg text-sm px-5 py-2.5">
                    Add
                </button>
            </a>
        </div>
    </div>

    @if(session('success'))
    <script>
        Swal.fire({
            title: 'Success!',
            text: '{{ session("success") }}',
            icon: 'success',
            confirmButtonText: 'OK'
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

</div>

@endsection