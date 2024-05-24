<?php include "db.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link href='https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="index.css">
    <title>edvance.</title>
</head>
<body>
  <div class="wrapper">
    <form action="">
        <img src="assets/logo1.svg" alt="logo" class="logo">
        <h1>edvance</h1>
        <h2>Welcome Back!</h2>
    <div class="input-box">
        <i class='bx bxs-user'></i>
        <input type="text" id="username" name="username" placeholder="username" required>
    </div>
    <div class="input-box">
        <i class='bx bxs-lock-alt'></i>
        <input type="password" id="password" name="password" placeholder="password" required>
    </div>
      <button type="submit" class="btn">Login</button>
    </form>
  </div>
</body>
</html>

