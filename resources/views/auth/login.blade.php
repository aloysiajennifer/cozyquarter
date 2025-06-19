@extends('layoutAuth')

@section('title', 'Login')

@section('content')
{{-- Konten ini akan diinjeksikan ke dalam @yield('content') di layoutAuth.blade.php --}}
<div class="flex items-center justify-center pt-20 bg-[var(--background-light)] px-4 ">
    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
        <div class="flex flex-col items-center mb-6">
            <img src="{{ asset('images/logo.jpeg') }}" alt="Logo Perpustakaan" class="w-28 h-28 mb-4 rounded-full border-4 border-[var(--accent-blue)]">
            <h2 class="text-2xl font-bold text-[var(--primary)]">Login CozyQuarter</h2>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <input type="text" name="username" placeholder="Username"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--accent-blue)]"
                required>

            <input type="password" name="password" placeholder="Password"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--accent-blue)]"
                required>

            @if ($errors->any())
            <div class="text-red-600 text-sm">{{ $errors->first() }}</div>
            @endif

            <button type="submit"
                class="w-full bg-[var(--accent-blue)] hover:bg-[var(--accent-green)] text-white font-semibold py-2 rounded-lg transition duration-200">
                Login
            </button>
        </form>

        <p class="text-sm text-center mt-4 text-[var(--primary)]">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-[var(--accent-blue)] hover:underline">Register</a>
        </p>
    </div>
</div>
@endsection