<h2>Daftar CozyQuarter</h2>
<form method="POST" action="{{ route('register') }}">
    @csrf

    <input type="text" name="name" placeholder="Name" required>
    <input type="text" name="username" placeholder="Username" required>
    <input type="text" name="phone" placeholder="Phone" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="password" name="password_confirmation" placeholder="Confirm Password" required>

    <button type="submit">Register</button>
</form>

@if ($errors->any())
    <ul style="color:red;">
        @foreach ($errors->all() as $err)
            <li>{{ $err }}</li>
        @endforeach
    </ul>
@endif
<p>Sudah punya akun? <a href="{{ route('login') }}">Login</a></p>
