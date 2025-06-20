@extends('admin.layoutAdmin')

<!-- Title page -->
@section('title', 'CRUD - Update User')

@section('content')
    <div class="p-4 mt-16 min-h-screen">
        <div class="container mx-auto p-4">
            <div class="text-left">
                <a href="{{ route('user.index') }}">
                    <button
                        class="text-white bg-gray-500 hover:bg-gray-600 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5">
                        Back
                    </button>
                </a>
            </div>

            <h1 class="text-center text-3xl font-semibold text-gray-800 mb-6 dark:text-white">Update User Form</h1>
            <form action="{{ route('user.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @if(isset($user))
                    <input type="hidden" name="id" value="{{ encrypt($user->id) }}">
                @endif

                <div class="max-w-3xl mx-auto">
                    <div>
                        <div class="mb-6 flex">
                            <label for="name" class="w-1/3 block mb-2 text-sm font-medium text-[var(--primary)] dark:text-white">Name</label>
                            <input type="text" id="name" name="name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)] block w-2/3 p-2.5"
                                value="{{ old('name', $user->name ?? '') }}"  required />
                        </div>
                        <div class="mb-6 flex">
                            <label for="email" class="w-1/3 block mb-2 text-sm font-medium text-[var(--primary)] dark:text-white">Email</label>
                            <input type="email" id="email" name="email"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)] block w-2/3 p-2.5"
                                value="{{ old('email', $user->email ?? '') }}" required />
                        </div>
                        <div class="mb-6 flex">
                                <label for="phone" class="w-1/3 block mb-2 text-sm font-medium text-[var(--primary)] dark:text-white">Phone Number</label>
                            <input type="text" id="phone" name="phone"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)] block w-2/3 p-2.5"
                                value="{{ old('phone', $user->phone ?? '') }}" required />
                        </div>
                        <div class="mb-6 flex">
                            <label for="username" class="w-1/3 block mb-2 text-sm font-medium text-[var(--primary)] dark:text-white">Username</label>
                            <input type="text" id="username" name="username"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)] block w-2/3 p-2.5"
                                value="{{ old('username', $user->username ?? '') }}" required />
                        </div>
                        <div class="mb-6 flex">
                            <label for="password" class="w-1/3 block mb-2 text-sm font-medium text-[var(--primary)] dark:text-white">Password</label>
                            <input type="password" id="password" name="password"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)] block w-2/3 p-2.5"
                                placeholder="Leave blank to keep current password" />
                        </div>
                        <div class="mb-6 flex">    
                            <label for="password_confirmation" class="w-1/3 block mb-2 text-sm font-medium text-[var(--primary)] dark:text-white">Confirm Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)] block w-2/3 p-2.5"
                                placeholder="Leave blank to keep current password" />
                        </div>
                        <div class="mb-6 flex">
                            <label for="role" class="w-1/3 block mb-2 text-sm font-medium text-[var(--primary)] dark:text-white">Role</label>
                            <select name="role_id" id="role" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[var(--accent-green)] focus:border-[var(--accent-green)] block w-2/3 p-2.5" required>
                                <option value="">Select Role</option>
                                    @foreach ($listRoles as $role)
                                        <option value="{{ $role->id }}" {{ (old('role_id', $user->role_id ?? '') == $role->id) ? 'selected' : '' }}>{{ $role->type }}</option>
                                    @endforeach
                            </select>
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