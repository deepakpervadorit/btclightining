<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .welcome-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .welcome-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .welcome-message {
            font-size: 18px;
            line-height: 1.6;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="welcome-container">


            <div class="welcome-message">
                <p>Your Email Has Been Verified!!</p>

            </div>

            <div class="text-center mt-4">
                <a href="{{ route('login') }}" class="btn btn-primary">Go to Login</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
