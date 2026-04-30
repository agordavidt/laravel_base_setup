<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Password Reset</title>
</head>
<body>
    <h1>Hello, {{ $user->name }}</h1>
    <p>You are receiving this email because we received a password reset request for your account.</p>

    <p>
        <a href="{{ $url }}" style="background: #e3342f; color: #fff; padding: 10px 20px; text-decoration: none;">
            Reset Password
        </a>
    </p>

    <p>This password reset link will expire in 60 minutes.</p>
    <p>If you did not request a password reset, no further action is required.</p>
</body>
</html>
