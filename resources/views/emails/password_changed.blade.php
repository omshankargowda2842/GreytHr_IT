<!DOCTYPE html>
<html>

<head>
    <title>Password Changed Notification</title>
    <style>
        /* Include Montserrat font */
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap');

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .email-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .email-footer {
            text-align: center;
            margin-top: 30px;
            color: #888;
        }

        .logo {
            max-width: 100px;
            /* Adjust as needed */
            height: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            {{-- Company Logo --}}
            <img src="{{ $logoUrl }}" alt="Company Logo" class="logo">
            <h1>Your password has been changed.</h1>
        </div>
        <p>Hello {{ $user->first_name }} {{ $user->last_name }},</p>
        <p>This is a notification to inform you that your password has been successfully changed. If you did not
            initiate this change, please contact our support team immediately.</p>
        <p><strong>Details:</strong></p>
        <table>
            <tr>
                <th>IP Address</th>
                <td>{{ $ipAddress }}</td>
            </tr>
            <tr>
                <th>Location</th>
                <td>{{ $location['country'] ?? 'Unknown' }} ({{ $location['city'] ?? 'Unknown' }})</td>
            </tr>
            <tr>
                <th>Browser</th>
                <td>{{ $browser }}</td>
            </tr>
            <tr>
                <th>Device</th>
                <td>{{ $device }}</td>
            </tr>
            <tr>
                <th>OS</th>
                <td>{{ $os }} {{ $osVersion ? '(' . $osVersion . ')' : '' }}</td>
            </tr>
        </table>
        <p>Thank you for using our service!</p>
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} {{ $companyName }} Pvt.Ltd All rights reserved.</p>
        </div>
    </div>
</body>

</html>
