<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Assigned</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    .email-container {
        max-width: 600px;
        margin: 20px auto;
        background: #ffffff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        overflow: hidden;
    }

    .header {
        color: black;
        background-color: white;
        padding: 20px;
        text-align: center;
        font-size: 25px;
        font-weight: 500;
    }

    .content {
        padding: 20px;
    }

    .content p {
        margin: 10px 0;
        line-height: 1.6;
    }

    .content a {
        display: inline-block;
        background-color: #5865f2;
        color: #ffffff;
        padding: 10px 20px;
        margin: 15px 0;
        text-decoration: none;
        border-radius: 4px;
    }

    .footer {
        background-color: #f9f9f9;
        text-align: center;
        padding: 10px;
        font-size: 12px;
        color: #777;
    }

    .email-body {
        margin-left: 4%;
    }

    .hor-line {
        margin-left: 4%;
        margin-right: 4%;
    }
    .sub-heading{
        font-size: 13px;

    }
    .view-button{
        padding: 10px;
        background-color: blue;
        color: white;
        border-radius: 10px;
    }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">

        </div>
        <hr class="hor-line">
        <!-- Body -->
        <div class="email-body">


        <p class="sub-heading"><strong> {{ucwords(strtolower($assignedBy))}}</strong>  assigned task to you.</p>
        <h3>Details of Task</h3>


            <p class="sub-heading">Request ID : <strong>{{ $requestId }}</strong></p>
            <p class="sub-heading">Category : <strong>{{ $category }}</strong></p>

            <button class="view-button">View Task</button>

            <p class="sub-heading">Short Description : <strong>{{ $shortDescription }}</strong></p>

            <div>
                <p class="sub-heading">Thank you,</p>
                <p class="sub-heading">IT Support</p>
            </div>


        </div>
    </div>
</body>

</html>
