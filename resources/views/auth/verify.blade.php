<!-- resources/views/auth/verify.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Email Verification</title>
</head>
<body>
    <h1>Verify Your Email</h1>
    <form method="POST" action="{{ route('verify.email') }}">
        @csrf
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="verification_code">Verification Code:</label>
            <input type="text" id="verification_code" name="verification_code" required>
        </div>
        <button type="submit">Verify</button>
    </form>
</body>
</html>