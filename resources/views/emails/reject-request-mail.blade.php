<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Rejected</title>
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
        .email-body{
            margin-left: 4%;
        }
        .hor-line{
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
            Your request was rejected
        </div>
       <hr class="hor-line">
        <!-- Body -->
        <div class="email-body">

            <p class="sub-heading">Hi {{ $employeeName }},</p>
            <p class="sub-heading"><strong>{{ $requestId }} </strong> has been rejected for the following reason.</p>
            <p class="sub-heading">Rejected By : <strong>{{  ucwords(strtolower($rejectedEmpName))}}</strong></p>
            <p class="sub-heading">Reason :<em> <strong>{{ $rejectionReason }}</strong></em></p>

            <button class="view-button">View Request</button>

            <h2>About this request</h2>
            <div>
            <p class="sub-heading">Request ID : <strong>{{ $requestId }}</strong></p>
            <p class="sub-heading">Short description :<strong> {{ $shortDescription }} </strong></p>
            </div>

            <p class="sub-heading">Thank you,</p>
            <p class="sub-heading">Fulfillment Team</p>
        </div>
    </div>
</body>
</html>









