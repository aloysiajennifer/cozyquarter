@extends('admin.layoutAdmin')

<!-- Title page -->
@section('title', 'CRUD - Update Role')

@section('content')
    <div class="p-4 mt-16 min-h-screen">
        <div class="container mx-auto p-4">
            <div class="text-left">
                <a href="{{ route('role.index') }}">
                    <button
                        class="text-white bg-gray-500 hover:bg-gray-600 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5">
                        Back
                    </button>
                </a>
            </div>

            <h1 class="text-center text-3xl font-semibold text-gray-800 mb-6 dark:text-white">Update Role Form</h1>
            <form action="{{ route('role.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @if(isset($role))
                    <input type="hidden" name="id" value="{{ encrypt($role->id) }}">
                @endif

                <div class="max-w-3xl mx-auto">
                    <div>
                        <div class="mb-6 flex">
                            <label for="type" class="w-1/3 block mb-2 text-sm font-medium text-gray-900 dark:text-white">Type</label>
                            <input type="text" id="type" name="type"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)] block w-2/3 p-2.5"
                                value="{{ old('type', $role->type ?? '') }}" required />
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