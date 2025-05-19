<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>FunSweep - Enter Game ID</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      background: url("{{asset('assets/img/image.jpg')}}") no-repeat center center fixed;
      background-size: cover;
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }
    .overlay {
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(240, 240, 240, 0.8));
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .game-id-card {
      background: #fff;
      border: none;
      border-radius: 20px;
      padding: 40px;
      width: 100%;
      max-width: 450px;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
      position: relative;
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .game-id-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
    }
    .game-id-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 5px;
      background: linear-gradient(90deg, #dc3545, #28a745);
    }
    .logo-section {
      text-align: center;
      margin-bottom: 30px;
    }
    .logo {
      width: 200px;
      transition: transform 0.3s ease;
    }
    .logo:hover {
      transform: scale(1.05);
    }
    .game-id-card label {
      font-weight: 600;
      color: #343a40;
      margin-bottom: 10px;
      display: block;
      text-align: center;
      font-size: 1.2rem;
    }
    .game-id-card .form-control {
      border: 1px solid #ced4da;
      padding: 12px;
      border-radius: 10px;
      transition: border-color 0.3s ease, box-shadow 0.3s ease;
      font-size: 1rem;
    }
    .game-id-card .form-control:focus {
      border-color: #28a745;
      box-shadow: 0 0 5px rgba(40, 167, 69, 0.3);
      outline: none;
    }
    .game-id-card .btn-next {
      background: linear-gradient(45deg, #343a40, #4b535b);
      border: none;
      padding: 12px;
      font-weight: 600;
      border-radius: 10px;
      color: #ffc107;
      width: 100%;
      margin-top: 20px;
      transition: background 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    .game-id-card .btn-next:hover {
      background: linear-gradient(45deg, #495057, #5c666f);
      color: #ffd700;
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
    }
    .game-id-card .btn-next:active {
      transform: translateY(1px);
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }
  </style>
</head>
<body>
  <div class="overlay">
    <div class="game-id-card">
      <div class="logo-section">
        <img src="https://logodix.com/logo/773821.png" alt="FunSweep Logo" class="logo">
      </div>
      <form method="post" action="{{route('show.merchant.deposit.step3',$merchantid)}}">
          @csrf
        <label for="game-id">Enter Game ID</label>
        <input type="text" class="form-control" id="game-id" placeholder="Your Game ID" name="gameid">
        <button type="submit" class="btn btn-next">Next</button>
      </form>
    </div>
  </div>
</body>
</html>