<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100">
<div class="container mx-auto p-4">
    <form action="{{ route('book.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @if(isset($book))
            <input type="hidden" name="id" value="{{ $book->id }}">
        @endif

        <div class="max-w-3xl mx-auto">
            <div>
                <div class="mb-6 flex">
                    <label for="title_book" class="w-1/3 block mb-2 text-sm font-medium text-gray-900">Title</label>
                    <input type="text" id="title_book" name="title_book" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-2/3 p-2.5" value="{{ $book->title_book }}" required />
                </div>

                <div class="mb-6 flex">
                    <label for="author_book" class="w-1/3 block mb-2 text-sm font-medium text-gray-900">Author</label>
                    <input type="text" id="author_book" name="author_book" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-2/3 p-2.5" value="{{ $book->author_book }}" required />
                </div>

                <div class="mb-6 flex">
                    <label for="isbn_book" class="w-1/3 block mb-2 text-sm font-medium text-gray-900">ISBN</label>
                    <input type="text" id="isbn_book" name="isbn_book" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-2/3 p-2.5" value="{{ $book->isbn_book }}" required />
                </div>

                <div class="mb-6 flex">
                    <label for="synopsis_book" class="w-1/3 block mb-2 text-sm font-medium text-gray-900">Synopsis</label>
                    <textarea id="synopsis_book" name="synopsis_book" rows="4" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-2/3 p-2.5" required>{{ $book->synopsis_book }}</textarea>
                </div>

                <div class="mb-6 flex">
                    <label for="cover_book" class="w-1/3 block mb-2 text-sm font-medium text-gray-900">Cover</label>
                    <div class="w-2/3">
                        <input type="file" name="cover_book" id="cover_book" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" aria-describedby="cover_book_help">
                        <p class="mt-1 text-sm text-gray-500" id="cover_book_help">JPG, JPEG, or PNG.</p>
                        @if(isset($book) && $book->cover_book)
                            <img src="{{ asset('storage/' . $book->cover_book) }}" class="mt-2 h-24">
                        @endif
                    </div>
                </div>

                <div class="mb-6 flex">
                    <label for="id_category" class="w-1/3 block mb-2 text-sm font-medium text-gray-900">Kategori</label>
                    <select id="id_category" name="id_category" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-2/3 p-2.5" required>
                        <option value="">Choose Category</option>
                        <option value="1" {{ (isset($book) && $book->id_category == 1) ? 'selected' : '' }}>Fiksi</option>
                        <option value="2" {{ (isset($book) && $book->id_category == 2) ? 'selected' : '' }}>Non-Fiksi</option>
                        <option value="3" {{ (isset($book) && $book->id_category == 3) ? 'selected' : '' }}>Biografi</option>
                    </select>
                </div>

                <div class="mb-6 flex">
                    <label for="id_shelf" class="w-1/3 block mb-2 text-sm font-medium text-gray-900">Rak</label>
                    <select id="id_shelf" name="id_shelf" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-2/3 p-2.5" required>
                        <option value="">Choose Shelf</option>
                        <option value="1" {{ (isset($book) && $book->id_shelf == 1) ? 'selected' : '' }}>Rak 1</option>
                        <option value="2" {{ (isset($book) && $book->id_shelf == 2) ? 'selected' : '' }}>Rak 2</option>
                        <option value="3" {{ (isset($book) && $book->id_shelf == 3) ? 'selected' : '' }}>Rak 3</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">UPDATE</button>
        </div>
    </form>
</div>
</body>
</html>
