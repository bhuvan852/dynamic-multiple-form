<!DOCTYPE html>
<html lang="en">
<head>
 <title>{{ config('app.name', 'MDFMS') }}</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <style>
  .bg-1 {
    background-color: #1abc9c; /* Green */
    color: #ffffff;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    text-align:center;
    flex-direction: column; 
  }
  .btn-login {
      padding: 10px 20px;
      font-size: 16px;
      background-color: #f39c12;
      border: none;
      border-radius: 5px;
      color: white;
      cursor: pointer;
    }

    .btn-login:hover {
      background-color: #e67e22;
    }
  </style>
</head>

<body>
<div class="container-fluid bg-1 text-center">
  <h1>Multiple Dynamic Form Managemnt System (MDFMS) </h1> 
  <a href="{{route('login')}}" class="btn btn-login">Login </a>
</div>
</body>
</html>