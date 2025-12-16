<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <h2>Register</h2>
    @if($errors->any())
        <div style="color:red;">
            <ul>
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ url('register') }}">
        @csrf
        <label>Username:</label>
        <input type="text" name="username" required><br>
        <label>Nama:</label>
        <input type="text" name="name" required><br>
        <label>Email:</label>
        <input type="email" name="email" required><br>
        <label>Password:</label>
        <input type="password" name="password" required><br>
        <label>Konfirmasi Password:</label>
        <input type="password" name="password_confirmation" required><br>
        <label>Role:</label>
        <select name="role" required>
            <option value="">Pilih Role</option>
            <option value="Admin">Admin</option>
            <option value="Dispatcher">Dispatcher</option>
            <option value="Teknisi">Teknisi</option>
            <option value="Manajer">Manajer</option>
        </select><br>
        <button type="submit">Register</button>
    </form>
    <a href="{{ route('login') }}">Sudah punya akun? Login</a>
</body>
</html>