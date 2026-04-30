<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Verify Email</title>
</head>
<body>
    <h1>Welcome, {{ $user->name }}!</h1>
    <p>Thanks for joining our platform. Please click the button below to verify your email address:</p>

    <p>
        <a href="{{ $url }}" style="background: #3490dc; color: #fff; padding: 10px 20px; text-decoration: none;">
            Verify Email
        </a>
    </p>

    <p>If you did not create an account, no further action is required.</p>
</body>
</html>
