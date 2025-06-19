@extends('layoutAuth') 

@section('title', 'Register') 

@section('content')
{{-- Konten ini akan diinjeksikan ke dalam @yield('content') di layoutAuth.blade.php --}}
<div class="flex items-center justify-center min-h-screen px-4">
    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
        <div class="flex flex-col items-center mb-6">
            <img src="{{ asset('images/logo.jpeg') }}" alt="Logo Perpustakaan"
                class="w-28 h-28 mb-4 rounded-full border-4 border-[var(--accent-blue)]">
            <h2 class="text-2xl font-bold text-[var(--primary)]">Daftar CozyQuarter</h2>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <input type="text" name="name" placeholder="Name" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--accent-blue)]"
                value="{{ old('name') }}">

            <input type="text" name="username" placeholder="Username" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--accent-blue)]"
                value="{{ old('username') }}">

            <input type="text" name="phone" placeholder="Phone" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--accent-blue)]"
                value="{{ old('phone') }}">

            <input type="email" name="email" placeholder="Email" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--accent-blue)]"
                value="{{ old('email') }}">

            <input type="password" name="password" placeholder="Password" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--accent-blue)]">

            <input type="password" name="password_confirmation" placeholder="Confirm Password" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--accent-blue)]">

            @if ($errors->any())
                <div class="text-red-600 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <button type="submit"
                class="w-full bg-[var(--accent-blue)] hover:bg-[var(--accent-green)] text-white font-semibold py-2 rounded-lg transition duration-200">
                Register
            </button>
        </form>

        <p class="text-sm text-center mt-4 text-[var(--primary)]">
            Already have an account?
            <a href="{{ route('login') }}" class="text-[var(--accent-blue)] hover:underline">Login</a>
        </p>
    </div>
</div>
@endsection