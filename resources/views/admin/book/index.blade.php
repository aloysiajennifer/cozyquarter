<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Book Index</title>
</head>
<body class="bg-gray-100">

<div class="container mx-auto p-4">
    <div class="relative overflow-x-auto max-h-[500px]">
    <table class="w-full text-sm text-left text-gray-500 bg-white border border-gray-200 rounded-lg shadow-md">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 sticky top-0 z-10">
            <tr>
                <th scope="col" class="px-6 py-3">No</th>
                <th scope="col" class="px-6 py-3">Cover</th>
                <th scope="col" class="px-6 py-3">Title</th>
                <th scope="col" class="px-6 py-3">Author</th>
                <th scope="col" class="px-6 py-3">ISBN</th>
                <th scope="col" class="px-6 py-3">Synopsis</th>
                <th scope="col" class="px-6 py-3">Category</th>
                <th scope="col" class="px-6 py-3">Shelf</th>
                <th scope="col" class="px-6 py-3"></th>
                <th scope="col" class="px-6 py-3"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($books as $book)
            <tr class="bg-white border-b hover:bg-gray-100">
                <td class="px-6 py-4">{{ $loop->iteration }}</td>
                <td class="px-6 py-4">
                    <img src="{{ asset('storage/' . $book->cover_book) }}" alt="Cover" class="w-16 h-20 object-cover rounded">
                </td>
                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $book->title_book }}</td>
                <td class="px-6 py-4">{{ $book->author_book }}</td>
                <td class="px-6 py-4">{{ $book->isbn_book }}</td>

                <!-- Batasi tampilan sinopsis -->
                <td class="px-6 py-4 max-w-[200px] overflow-hidden text-ellipsis whitespace-nowrap">
                    {{ $book->synopsis_book }}
                </td>

                <td class="px-6 py-4">{{ $book->category->name_category }}</td>
                <td class="px-6 py-4">{{ $book->shelf->code_shelf }}</td>
                <td class="px-6 py-4">
                    <a href="{{ url('book/detail/' . $book->id) }}">
                        <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2">
                            Edit
                        </button>
                    </a>
                </td>
                <td class="px-6 py-4">
                    <form action="{{ route('book.delete', ['id'=> encrypt($book->id)]) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" onclick="return confirm('Hapus data ini?')" class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>

    <div class="mb-4 mt-4">
        <a href="{{ url('/book/form') }}">
            <button class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
                ADD
            </button>
        </a>
    </div>
</div>

</body>
</html>
