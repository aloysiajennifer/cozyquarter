<h2>Login CozyQuarter</h2>
<form method="POST" action="{{ route('login') }}">
    @csrf

    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>

    <button type="submit">Login</button>
</form>

@if ($errors->any())
    <p style="color:red;">{{ $errors->first() }}</p>
@endif
<p>Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
