<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Hijacked</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f5f5f5;
            margin: 0;
        }

        .container {
            text-align: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .message {
            font-size: 18px;
            margin-bottom: 20px;
        }

        .login-link {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #dc3545;
            border-radius: 5px;
            text-decoration: none;
        }

        .details {
            margin-top: 20px;
            font-size: 16px;
        }

        .details p {
            margin: 5px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="message">
            <span>Your session has been hijacked. Please login again.</span>
        </div>
        <a href="{{ $loginUrl }}" class="login-link">Login</a>
        <div class="details">
            <p><strong>User Type:</strong> {{ $userType }}</p>
            <p><strong>Device Type:</strong> {{ $deviceType }}</p>
            <p><strong>Location:</strong> {{ $location }}</p>
            <p><strong>IP Address:</strong> {{ $ip }}</p>
        </div>
    </div>
</body>

</html>
