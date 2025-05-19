<!DOCTYPE html>
<html>
<head>
    <title>Withdraw Confirmation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .container {
            background: #ffffff;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #28a745;
            color: white;
            text-align: center;
            padding: 10px;
            border-radius: 8px 8px 0 0;
        }
        .btn-custom {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
        }
        .btn-custom:hover {
            background-color: #218838;
            color: white;
        }
        .footer {
            font-size: 14px;
            color: #777;
            margin-top: 20px;
            text-align: center;
            padding: 10px;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <h2>Withdraw Successful</h2>
        </div>
        
        <div class="p-3">
            <p class="fw-bold">Dear {{ $user->name }},</p>
            <p>Your Withdraw of <strong>${{ number_format($amount, 2) }}</strong> has been successfully processed.</p>
            <p>If this transaction was not authorized by you, please contact support immediately.</p>
        </div>

        <div class="footer">
            If you have any questions, contact our support team.
        </div>
    </div>

</body>
</html>
