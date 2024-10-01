<!-- resources/views/errors/401.blade.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>401 Unauthorized</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            flex-direction: column;
        }

        .error-container {
            text-align: center;
        }

        h1 {
            font-size: 2em;
            margin-bottom: 10px;
            color: #333;
        }

        p {
            font-size: 1.2em;
            margin-bottom: 20px;
            color: #555;
        }

        .error-image {
            height: 250px;
        }
    </style>
</head>

<body>

    <div class="error-container">
        <img src="https://i.pinimg.com/originals/ac/06/47/ac064781d562d0963f62ab456c0f2f01.png" alt="Page Not Found" class="error-image">

        <h1>401 Unauthorized</h1>
        <p>Sorry, you do not have the necessary permissions to access this resource.</p>
    </div>

</body>

</html>