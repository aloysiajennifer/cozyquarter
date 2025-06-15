@extends('admin.layoutAdmin')

<!-- Title page -->
@section('title', 'CRUD - Add Beverage')

@section('content')
<div class="pt-16 p-4">
    <div class="container mx-auto p-4">
        <div class="text-left">
            <a href="{{ route('beverage.index') }}">
                <button
                    class="text-white bg-gray-500 hover:bg-gray-600 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5">
                    Back
                </button>
            </a>
        </div>

        <h1 class="text-center text-3xl font-semibold text-[var(--primary)] mb-6">Add Beverage Form</h1>
        <form action="{{ route('beverage.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="max-w-3xl mx-auto">
                <div>
                    <div class="mb-6 flex">
                        <label for="name"
                            class="w-1/3 block mb-2 text-sm font-medium text-[var(--primary)]">Name</label>
                        <input type="text" id="name" name="name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)] block w-2/3 p-2.5"
                            placeholder="Enter beverage name" required />
                    </div>

                    <div class="mb-6 flex">
                        <label for="price"
                            class="w-1/3 block mb-2 text-sm font-medium text-[var(--primary)]">Price</label>
                        <input type="number" id="price" name="price" min="0"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)] block w-2/3 p-2.5"
                            placeholder="Enter price" required />
                    </div>
                    <div class="mb-6 flex">
                        <label for="stock"
                            class="w-1/3 block mb-2 text-sm font-medium text-[var(--primary)]">Stock</label>
                        <input type="number" id="stock" name="stock" min="0"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)] block w-2/3 p-2.5"
                            placeholder="Enter stock quantity" required />
                    </div>

                    <div class="mb-6 flex items-center">
                        <label class="w-1/3 block mb-2 text-sm font-medium text-[var(--primary)]">Availability</label>
                        <div class="w-2/3 flex space-x-6">
                            <label class="inline-flex items-center">
                                <input type="radio" name="availability" value="0" class="form-radio" required>
                                <span class="ml-2">Not Available</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="availability" value="1" class="form-radio" required>
                                <span class="ml-2">Available</span>
                            </label>
                        </div>
                    </div>

                    <div class="mb-6 flex">
                        <label for="image"
                            class="w-1/3 block mb-2 text-sm font-medium text-[var(--primary)]">Image</label>
                        <div class="w-2/3">
                            <input
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50"
                                aria-describedby="image_help" id="image" type="file" name="image">
                            <p class="mt-1 text-sm text-gray-500" id="image_help">JPG, JPEG, or PNG. Max 2MB.</p>
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <button type="submit"
                        class="text-white bg-[var(--accent-blue)] hover:bg-[var(--accent-green)] focus:ring-4 focus:outline-none focus:ring-cyan-200 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Save</button>
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
@endsection