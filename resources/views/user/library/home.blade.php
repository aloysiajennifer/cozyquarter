@extends('layout')

@section('content')
    {{-- Carousel --}}
    <div class="min-h-screen">
        <div id="default-carousel" class="relative w-full aspect-[3/1]" data-carousel="slide">
            <div class="relative w-full h-full overflow-hidden">
                <!-- Carousel items -->
                @foreach (['carousel1.jpg', 'carousel2.jpg', 'carousel3.jpg', 'carousel4.jpg', 'carousel5.jpeg', 'carousel6.jpg'] as $image)
                    <div class="hidden duration-700 ease-in-out h-full" data-carousel-item>
                        <img src="{{ asset('images/' . $image) }}" class="absolute inset-0 w-full h-full object-cover"
                            alt="...">
                    </div>
                @endforeach

                <!-- Controls -->
                <button type="button"
                    class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                    data-carousel-prev>
                    <span
                        class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50 group-focus:ring-4 group-focus:ring-white group-focus:outline-none">
                        <svg class="w-4 h-4 text-white rtl:rotate-180" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 1 1 5l4 4" />
                        </svg>
                        <span class="sr-only">Previous</span>
                    </span>
                </button>
                <button type="button"
                    class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                    data-carousel-next>
                    <span
                        class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50 group-focus:ring-4 group-focus:ring-white group-focus:outline-none">
                        <svg class="w-4 h-4 text-white rtl:rotate-180" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 9 4-4-4-4" />
                        </svg>
                        <span class="sr-only">Next</span>
                    </span>
                </button>
            </div>
        </div>

        <!-- bawah carousel -->
        <div class="py-10 px-10">
            <form action="{{ route('library.home') }}" method="GET" class="max-w-lg mx-auto">
                <input type="hidden" name="category" value="{{ request('category') }}">
                <div class="flex relative">
                    {{-- category dropdown --}}
                    <div class="relative">
                        <button id="dropdown-button" type="button" onclick="toggleCategoryDropdown()"
                            class="shrink-0 z-10 inline-flex items-center py-2.5 px-4 text-sm font-medium text-[var(--primary)] bg-[var(--highlight)] border border-gray-300 rounded-l-lg hover:bg-amber-400 focus:ring-4 focus:outline-none focus:ring-amber-200">
                            {{ request('category') ? $categories->find(request('category'))->name_category : 'All Categories' }}
                            <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 1 4 4 4-4" />
                            </svg>
                        </button>

                        <div id="dropdown"
                            class="z-10 hidden absolute mt-1 bg-white divide-y divide-gray-100 rounded-lg max-h-40 overflow-y-auto shadow-sm w-44">
                            <ul class="py-2 text-sm text-gray-700" aria-labelledby="dropdown-button">
                                <li>
                                    <button type="button" onclick="selectCategory('')"
                                        class="inline-flex w-full px-4 py-2 hover:bg-[var(--highlight)]">All Categories</button>
                                </li>
                                @foreach ($categories as $category)
                                    <li>
                                        <button type="button" onclick="selectCategory('{{ $category->id }}')"
                                            class="inline-flex w-full px-4 py-2 hover:bg-[var(--highlight)]">{{ $category->name_category }}</button>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    {{-- Search Input --}}
                    <div class="relative flex-grow">
                        <input type="search" name="search" id="search-dropdown"
                            class="block p-2.5 w-full z-20 text-sm text-gray-900 bg-gray-50 rounded-r-lg border border-gray-300 focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)]"
                            placeholder="Search by title..." value="{{ request('search') }}" />
                        <button type="submit"
                            class="absolute top-0 right-0 p-2.5 text-sm font-medium h-full text-white bg-[var(--accent-blue)] rounded-r-lg  hover:bg-[var(--accent-green)] focus:ring-4 focus:ring-cyan-200 focus:outline-none">
                            <span class="sr-only">Search</span>
                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </form>

            <script>
                function toggleCategoryDropdown() {
                    const dropdown = document.getElementById('dropdown');
                    dropdown.classList.toggle('hidden');
                }

                function selectCategory(categoryId) {
                    const dropdown = document.getElementById('dropdown');
                    dropdown.classList.add('hidden');

                    const categoryInput = document.querySelector('input[name="category"]');
                    if (categoryInput) {
                        categoryInput.value = categoryId;
                    }

                    const selectedCategory = categoryId === '' ? 'All Categories' :
                        (document.querySelector(`[onclick="selectCategory('${categoryId}')"]`)?.innerText || 'Selected');
                    document.getElementById('dropdown-button').innerHTML = `
            ${selectedCategory}
            <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
            </svg>
        `;
                }

                // Close dropdown if clicked outside
                window.onclick = function (event) {
                    if (!event.target.closest('#dropdown-button')) {
                        const dropdown = document.getElementById('dropdown');
                        if (!dropdown.classList.contains('hidden')) {
                            dropdown.classList.add('hidden');
                        }
                    }
                }
            </script>

        </div>

        <div class="px-10">
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

            <!-- list buku -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach ($books as $book)
                    <div class="bg-[var(--accent-blue)] hover:bg-[var(--accent-green)] rounded-xl shadow p-2 text-center cursor-pointer"
                        onclick="openModal({{ $book->id }})">
                        <div class="aspect-[2/3]">
                            <img src="{{ asset('storage/' . $book->cover_book) }}" alt="{{ $book->title_book }}"
                                class="rounded-lg object-cover w-full h-full mx-auto mb-1">
                        </div>
                        <h3 class="text-sm text-white font-semibold truncate max-w-full" title="{{ $book->title_book }}">
                            {{ $book->title_book }}
                        </h3>
                        <p class="text-xs text-white mb-1 truncate max-w-full" title="by {{ $book->author_book }}">
                            by {{ $book->author_book }}
                        </p>
                        <span
                            class="text-xs font-medium text-white px-2 py-1 rounded {{ $book->status_book == 1 ? 'bg-red-500' : 'bg-green-500' }}">
                            {{ $book->status_book == 1 ? 'Unavailable' : 'Available' }}
                        </span>
                    </div>

                    <!--  modal buat info buku lengkap -->
                    <div id="modal-{{ $book->id }}"
                        class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
                        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative overflow-y-auto max-h-[90vh]">
                            <button onclick="closeModal({{ $book->id }})"
                                class="absolute top-2 right-2 text-gray-500 hover:text-black text-2xl">&times;</button><br>
                            <img src="{{ asset('storage/' . $book->cover_book) }}" alt="Cover"
                                class="w-full h-auto object-contain rounded mb-4">
                            <h2 class="text-xl font-bold mb-2">{{ $book->title_book }}</h2>
                            <p class="text-sm text-[var(--primary)]  mb-1"><strong>Author:</strong> {{ $book->author_book }}</p>
                            <p class="text-sm text-[var(--primary)] mb-1"><strong>ISBN:</strong> {{ $book->isbn_book }}</p>
                            <p class="text-sm text-[var(--primary)] mb-1"><strong>Category:</strong>
                                {{ $book->category->name_category ?? 'N/A' }}</p>
                            <p class="text-sm text-[var(--primary)] mb-1"><strong>Shelf:</strong>
                                {{ $book->shelf->code_shelf ?? 'N/A' }}
                            </p>
                            <p class="text-sm text-[var(--text-primary)] mb-1"><strong>Status:</strong>
                                <span class="{{ $book->status_book == 1 ? 'text-red-500' : 'text-green-500' }}">
                                    {{ $book->status_book == 1 ? 'Unavailable in the library' : 'Available in the library' }}
                                </span>
                            </p>
                            <hr class="my-2">
                            <p class="text-sm text-[var(--text-primary)]  whitespace-pre-line">{{ $book->synopsis_book }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- buat modal-->
            <script>
                function openModal(id) {
                    document.getElementById('modal-' + id).classList.remove('hidden');
                }

                function closeModal(id) {
                    document.getElementById('modal-' + id).classList.add('hidden');
                }

                function toggleCategoryDropdown() {
                    const dropdown = document.getElementById('dropdown');
                    dropdown.classList.toggle('hidden');
                }
            </script>
        </div>
    </div>
@endsection