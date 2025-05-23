@extends('formLayout')

@section('content')
<div class="bg-gray-100">
<div class="container mx-auto p-4">
    <div class="text-left">
    <a href="{{ route('book.index') }}">
        <button class="text-white bg-gray-500 hover:bg-gray-600 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5">
            Back
        </button>
    </a>
    </div>

    <h1 class="text-center text-3xl font-semibold text-[var(--primary)] mb-6">Add Book Form</h1>
    <form action="{{ route('book.insert') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="max-w-3xl mx-auto">
            <div>
            <div class="mb-6 flex">
                <label for="title_book" class="w-1/3 block mb-2 text-sm font-medium text-[var(--primary)] dark:text-white">Title</label>
                <input type="text" id="title_book" name="title_book" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)] block w-2/3 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Enter book title" required />
            </div>
            <div class="mb-6 flex">
                <label for="author_book" class="w-1/3 block mb-2 text-sm font-medium text-[var(--primary)] dark:text-white">Author</label>
                <input type="text" id="author_book" name="author_book" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)] block w-2/3 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Enter author name" required />
            </div>
            <div class="mb-6 flex">
                <label for="isbn_book" class="w-1/3 block mb-2 text-sm font-medium text-[var(--primary)] dark:text-white">ISBN</label>
                <input type="text" id="isbn_book" name="isbn_book" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)] block w-2/3 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Enter ISBN" required />
            </div>
            <div class="mb-6 flex">
                <label for="synopsis_book" class="w-1/3 block mb-2 text-sm font-medium text-[var(--primary)] dark:text-white">Synopsis</label>
                <textarea id="synopsis_book" name="synopsis_book" rows="4" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)] block w-2/3 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Enter synopsis" required></textarea>
            </div>
            <div class="mb-6 flex">
                <label for="cover_book" class="w-1/3 block mb-2 text-sm font-medium text-[var(--primary)] dark:text-white">Cover</label>
                <div class="w-2/3">
                    <input 
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" 
                    aria-describedby="cover_book_help" 
                    id="cover_book" 
                    type="file" 
                    name="cover_book" 
                    required
                    >
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="cover_book_help">JPG, JPEG, or PNG.</p>
                </div>
            </div>
            <div class="mb-6 flex">
                <label for="id_category" class="w-1/3 block mb-2 text-sm font-medium text-[var(--primary)] dark:text-white">Kategori</label>
                <select id="id_category" name="id_category" class="bg-gray-50 border border-gray-300 text-[var(--primary)] text-sm rounded-lg focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)] block w-2/3 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                    <option value="">Choose Category</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name_category }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-6 flex">
                <label for="id_shelf" class="w-1/3 block mb-2 text-sm font-medium text-[var(--primary)] dark:text-white">Rak</label>
                <select id="id_shelf" name="id_shelf" class="bg-gray-50 border border-gray-300 text-[var(--primary)] text-sm rounded-lg focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)] block w-2/3 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                    <option value="">Choose Shelf</option>
                    @foreach ($shelves as $shelf)
                    <option value="{{ $shelf->id }}">{{ $shelf->code_shelf }}</option>
                    @endforeach
                </select>
            </div>
            </div>

        <div class="text-right">
        <button type="submit" class="text-white bg-[var(--accent-blue)] hover:bg-[var(--accent-green)] focus:ring-4 focus:outline-none focus:ring-cyan-200 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Save</button>
        </div>
    </div>
    </form>
</div>

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

