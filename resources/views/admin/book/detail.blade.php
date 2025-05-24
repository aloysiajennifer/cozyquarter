@extends('admin.layoutAdmin')

<!-- Title page -->
@section('title', 'CRUD - Update Book')

@section('content')
    <div class="mt-16 p-4 bg-gray-100">
        <div class="container mx-auto p-4">
            <div class="text-left">
                <a href="{{ route('book.index') }}">
                    <button
                        class="text-white bg-gray-500 hover:bg-gray-600 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5">
                        Back
                    </button>
                </a>
            </div>

            <h1 class="text-center text-3xl font-semibold text-gray-800 mb-6">Update Book Form</h1>
            <form action="{{ route('book.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @if(isset($book))
                    <input type="hidden" name="id" value="{{ encrypt($book->id) }}">
                @endif

                <div class="max-w-3xl mx-auto">
                    <div>
                        <div class="mb-6 flex">
                            <label for="title_book" class="w-1/3 block mb-2 text-sm font-medium text-gray-900">Title</label>
                            <input type="text" id="title_book" name="title_book"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)] block w-2/3 p-2.5"
                                value="{{ old('title_book', $book->title_book ?? '') }}" required />
                        </div>

                        <div class="mb-6 flex">
                            <label for="author_book"
                                class="w-1/3 block mb-2 text-sm font-medium text-gray-900">Author</label>
                            <input type="text" id="author_book" name="author_book"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)] block w-2/3 p-2.5"
                                value="{{ old('author_book', $book->author_book ?? '') }}" required />
                        </div>

                        <div class="mb-6 flex">
                            <label for="isbn_book" class="w-1/3 block mb-2 text-sm font-medium text-gray-900">ISBN</label>
                            <input type="text" id="isbn_book" name="isbn_book"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)] block w-2/3 p-2.5"
                                value="{{ old('isbn_book', $book->isbn_book ?? '') }}" required />
                        </div>

                        <div class="mb-6 flex">
                            <label for="synopsis_book"
                                class="w-1/3 block mb-2 text-sm font-medium text-gray-900">Synopsis</label>
                            <textarea id="synopsis_book" name="synopsis_book" rows="4"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)] block w-2/3 p-2.5"
                                required>{{ old('synopsis_book', $book->synopsis_book ?? '') }}</textarea>
                        </div>

                        <div class="mb-6 flex">
                            <label for="cover_book" class="w-1/3 block mb-2 text-sm font-medium text-gray-900">Cover</label>
                            <div class="w-2/3">
                                <input type="file" name="cover_book" id="cover_book"
                                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                                    aria-describedby="cover_book_help">
                                <p class="mt-1 text-sm text-gray-500" id="cover_book_help">JPG, JPEG, or PNG.</p>
                                @if(isset($book) && $book->cover_book)
                                    <img src="{{ asset($book->cover_book) }}" alt="{{ $book->title_book }}" class="mt-2 h-24">
                                @endif
                            </div>
                        </div>

                        <div class="mb-6 flex">
                            <label for="id_category"
                                class="w-1/3 block mb-2 text-sm font-medium text-gray-900">Kategori</label>
                            <select id="id_category" name="id_category"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)] block w-2/3 p-2.5"
                                required>
                                <option value="">Choose Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('id_category', $book->id_category ?? '') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name_category }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-6 flex">
                            <label for="id_shelf" class="w-1/3 block mb-2 text-sm font-medium text-gray-900">Rak</label>
                            <select id="id_shelf" name="id_shelf"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)] block w-2/3 p-2.5"
                                required>
                                <option value="">Choose Shelf</option>
                                @foreach ($shelves as $shelf)
                                    <option value="{{ $shelf->id }}" {{ old('id_shelf', $book->id_shelf ?? '') == $shelf->id ? 'selected' : '' }}>
                                        {{ $shelf->code_shelf }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="submit"
                            class="bg-[var(--accent-blue)] text-white hover:bg-[var(--accent-green)] focus:ring-4 focus:ring-cyan-200 focus:outline-none font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Update</button>
                    </div>
                </div>
            </form>
        </div>

        @if ($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        title: 'Validation Error!',
                        html: `{!! implode('<br>', $errors->all()) !!}`,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
            </script>
        @endif



    </div>

@endsection