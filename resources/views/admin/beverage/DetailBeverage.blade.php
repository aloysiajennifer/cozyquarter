@extends('formLayout')

@section('content')
<div class="bg-gray-100">
    <div class="container mx-auto p-4">
        <div class="text-left">
            <a href="{{ route('beverage.index') }}">
                <button class="text-white bg-gray-500 hover:bg-gray-600 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5">Back</button>
            </a>
        </div>

        <h1 class="text-center text-3xl font-semibold text-[var(--primary)] mb-6">Edit Beverage Form</h1>
        <form action="{{ route('beverage.update', $beverage->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="max-w-3xl mx-auto">
                <div class="mb-6 flex">
                    <label for="name" class="w-1/3 block mb-2 text-sm font-medium text-[var(--primary)]">Name</label>
                    <input type="text" id="name" name="name" value="{{ $beverage->name }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)] block w-2/3 p-2.5" required />
                </div>

                <div class="mb-6 flex">
                    <label for="price" class="w-1/3 block mb-2 text-sm font-medium text-[var(--primary)]">Price</label>
                    <input type="number" id="price" name="price" min="0" value="{{ $beverage->price }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)] block w-2/3 p-2.5" required />
                </div>

                <div class="mb-6 flex items-center">
                    <label class="w-1/3 block mb-2 text-sm font-medium text-[var(--primary)]">Availability</label>
                    <div class="w-2/3 flex space-x-6">
                        <label class="inline-flex items-center">
                            <input type="radio" name="availability" value="0" class="form-radio" {{ $beverage->availability == 0 ? 'checked' : '' }}>
                            <span class="ml-2">Not Available</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="availability" value="1" class="form-radio" {{ $beverage->availability == 1 ? 'checked' : '' }}>
                            <span class="ml-2">Available</span>
                        </label>
                    </div>
                </div>

                <div class="mb-6 flex">
                    <label for="image" class="w-1/3 block mb-2 text-sm font-medium text-[var(--primary)]">Image</label>
                    <div class="w-2/3">
                        <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50" id="image" type="file" name="image">
                        <p class="mt-1 text-sm text-gray-500">JPG, JPEG, or PNG. Max 2MB.</p>
                        @if($beverage->image)
                            <img src="{{ asset($beverage->image) }}" alt="Current image" class="w-24 mt-2 rounded">
                        @endif
                    </div>
                </div>

                <div class="text-right">
                    <button type="submit" class="text-white bg-[var(--accent-blue)] hover:bg-[var(--accent-green)] focus:ring-4 focus:outline-none focus:ring-cyan-200 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
