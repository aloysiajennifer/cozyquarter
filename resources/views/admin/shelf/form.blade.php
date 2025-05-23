@extends('formLayout')

@section('content')
<div class="bg-gray-100 min-h-screen">
<div class="container mx-auto p-4">
    <div class="text-left">
    <a href="{{ route('shelf.index') }}">
        <button class="text-white bg-gray-500 hover:bg-gray-600 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5">
            Back
        </button>
    </a>
    </div>

    <h1 class="text-center text-3xl font-semibold text-[var(--primary)] mb-6">Add Shelf Form</h1>
    <form action="{{ route('shelf.insert') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="max-w-3xl mx-auto">
            <div>
            <div class="mb-6 flex">
                <label for="code_shelf" class="w-1/3 block mb-2 text-sm font-medium text-[var(--primary)]">Shelf Code</label>
                <input type="text" id="code_shelf" name="code_shelf" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)] block w-2/3 p-2.5" placeholder="Enter shelf code" required />
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

@endsection