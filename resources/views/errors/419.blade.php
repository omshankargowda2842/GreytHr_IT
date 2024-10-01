<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CSRF Token Mismatch</title>
    <style>
        body {
            background-color: #ffff;
            font-family: 'Arial', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            flex-direction: column;
        }

        .error-container {
            text-align: center;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
        }

        .error-image {
            height: 300px;
        }

        h1 {
            font-size: 2em;
            margin-bottom: 10px;
            color:  rgb(2, 17, 79);
        }

        p {
            font-size: 1.2em;
            margin-bottom: 20px;
            color: #555;
        }

        .refresh-btn {
            padding: 10px 20px;
            font-size: 1.2em;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .refresh-btn:hover {
            background-color: #2980b9;
        }
    </style>
</head>

<body>

    <!-- resources/views/livewire/csrf-token-mismatch.blade.php -->
    <div class="error-container">
        <img src="https://th.bing.com/th/id/R.2d8eebf00a6cfceb533277c9530e50c5?rik=mdTIVVS4FOYIpw&riu=http%3a%2f%2fbsm.birlacane.com%2fBOOTSTRAP_IMAGES%2fsession-expire.jpg&ehk=sC0FyYsOgofL300RZtnNP53I431EqKu3uUIerxYXJU0%3d&risl=&pid=ImgRaw&r=0" alt="Page Not Found" class="error-image">
        <h1>Page Expired</h1>
        <p>Your session has expired. Please refresh the page to continue.</p>
    </div>

</body>

</html>
