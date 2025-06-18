@extends('admin.layoutAdmin')

<!-- Title page -->
@section('title', 'CRUD - Update Beverage')

@section('content')
    <div class="pt-16 p-4">
        <div class="container mx-auto p-4">
            <div class="text-left">
                <a href="{{ route('beverage.index') }}">
                    <button
                        class="text-white bg-gray-500 hover:bg-gray-600 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5">Back</button>
                </a>
            </div>

            <h1 class="text-center text-3xl font-semibold text-[var(--primary)] mb-6 dark:text-white">Edit Beverage Form</h1>
            <form action="{{ route('beverage.update', $beverage->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="max-w-3xl mx-auto">
                    <div class="mb-6 flex">
                        <label for="name"
                            class="w-1/3 block mb-2 text-sm font-medium text-[var(--primary)] dark:text-white">Name</label>
                        <input type="text" id="name" name="name" value="{{ $beverage->name }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)] block w-2/3 p-2.5"
                            required />
                    </div>

                    <div class="mb-6 flex">
                        <label for="price"
                            class="w-1/3 block mb-2 text-sm font-medium text-[var(--primary)] dark:text-white">Price</label>
                        <input type="number" id="price" name="price" min="0" value="{{ $beverage->price }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)] block w-2/3 p-2.5"
                            required />
                    </div>

                    <div class="mb-6 flex">
                        <label for="stock"
                            class="w-1/3 block mb-2 text-sm font-medium text-[var(--primary)] dark:text-white">Stock</label>
                        <input type="number" id="stock" name="stock" min="0" value="{{ $beverage->stock }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)] block w-2/3 p-2.5"
                            required />
                    </div>

                    <div class="mb-6 flex">
                        <label for="image"
                            class="w-1/3 block mb-2 text-sm font-medium text-[var(--primary)] dark:text-white">Image</label>
                        <div class="w-2/3">
                            <input
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50"
                                id="image" type="file" name="image">
                            <p class="mt-1 text-sm text-gray-500">JPG, JPEG, or PNG. Max 2MB.</p>
                            @if ($beverage->image)
                                <img src="{{ asset($beverage->image) }}" alt="Current image" class="w-24 mt-2 rounded">
                            @endif
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="submit"
                            class="text-white bg-[var(--accent-blue)] hover:bg-[var(--accent-green)] focus:ring-4 focus:outline-none focus:ring-cyan-200 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
