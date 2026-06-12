<!DOCTYPE html>
<html>
<head>
    <title>Debug Registration</title>
</head>
<body>
    <h1>Debug Registration</h1>
    
    <form method="POST" action="{{ route('register.submit') }}">
        @csrf
        <div>
            <label>Name:</label>
            <input type="text" name="name" value="Test User" required>
        </div>
        <div>
            <label>Email:</label>
            <input type="email" name="email" value="test{{ time() }}@test.com" required>
        </div>
        <div>
            <label>Password:</label>
            <input type="text" name="password" value="password123" required>
        </div>
        <div>
            <label>Confirm Password:</label>
            <input type="text" name="password_confirmation" value="password123" required>
        </div>
        <div>
            <input type="checkbox" name="terms" checked required>
            <label>Accept terms</label>
        </div>
        <button type="submit">Register</button>
    </form>
    
    <hr>
    
    <h2>Test Login</h2>
    <form method="POST" action="{{ route('login.submit') }}">
        @csrf
        <div>
            <label>Email:</label>
            <input type="email" name="email" value="" placeholder="Enter email to test">
        </div>
        <div>
            <label>Password:</label>
            <input type="text" name="password" value="password123">
        </div>
        <button type="submit">Login</button>
    </form>
    
    <hr>
    
    <h2>Direct Links</h2>
    <ul>
        <li><a href="/password-debug" target="_blank">Password Debug</a></li>
        <li><a href="/debug/user/data/your-email@example.com" target="_blank">View User Data</a></li>
        <li><a href="/login">Login Page</a></li>
        <li><a href="/register">Register Page</a></li>
    </ul>
</body>
</html>