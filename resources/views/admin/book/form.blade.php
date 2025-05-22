<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Book</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-100">
<div class="container mx-auto p-4">
    <form action="{{ route('book.insert') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="max-w-3xl mx-auto">
            <div>
            <div class="mb-6 flex">
                <label for="title_book" class="w-1/3 block mb-2 text-sm font-medium text-gray-900 dark:text-white">Title</label>
                <input type="text" id="title_book" name="title_book" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-2/3 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Masukkan judul buku" required />
            </div>
            <div class="mb-6 flex">
                <label for="author_book" class="w-1/3 block mb-2 text-sm font-medium text-gray-900 dark:text-white">Author</label>
                <input type="text" id="author_book" name="author_book" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-2/3 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Masukkan nama pengarang" required />
            </div>
            <div class="mb-6 flex">
                <label for="isbn_book" class="w-1/3 block mb-2 text-sm font-medium text-gray-900 dark:text-white">ISBN</label>
                <input type="text" id="isbn_book" name="isbn_book" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-2/3 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Masukkan ISBN" required />
            </div>
            <div class="mb-6 flex">
                <label for="synopsis_book" class="w-1/3 block mb-2 text-sm font-medium text-gray-900 dark:text-white">Synopsis</label>
                <textarea id="synopsis_book" name="synopsis_book" rows="4" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-2/3 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Masukkan sinopsis buku" required></textarea>
            </div>
            <div class="mb-6 flex">
                <label for="cover_book" class="w-1/3 block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cover</label>
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
                <label for="id_category" class="w-1/3 block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kategori</label>
                <select id="id_category" name="id_category" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-2/3 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                    <option value="">Choose Category</option>
                    <option value="1">Fiksi</option>
                    <option value="2">Non-Fiksi</option>
                    <option value="3">Biografi</option>
                </select>
            </div>
            <div class="mb-6 flex">
                <label for="id_shelf" class="w-1/3 block mb-2 text-sm font-medium text-gray-900 dark:text-white">Rak</label>
                <select id="id_shelf" name="id_shelf" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-2/3 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                    <option value="">Choose Shelf</option>
                    <option value="1">Rak 1</option>
                    <option value="2">Rak 2</option>
                    <option value="3">Rak 3</option>
                </select>
            </div>
            </div>
        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">SAVE</button>
    </div>
    </form>
</div>
</body>
</html>
