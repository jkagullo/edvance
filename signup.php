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
    <link rel="shortcut icon" href="assets/logo1.svg" type="image/x-icon">
    <link rel="stylesheet" href="static/css/signup.css">
    <link rel="icon" href="assets/logo3.svg" type="image/x-icon">
    <title>edvance.</title>
</head>
<body>
  <div class="wrapper">
    <form action="">
        <img src="assets/logo1.svg" alt="logo" class="logo">
        <h2>Create an edvance account</h2>
        <p class="no-acc">Already have an account? <a href="index.php">Log in</a></p>
    
    <p class="label">First Name</p>
    <div class="input-box">
        <input type="text" id="username" name="username" placeholder="Enter your first name" required>
    </div>

    <p class="label">Last Name</p>
    <div class="input-box">
        <input type="text" id="username" name="username" placeholder="Enter your last name" required>
    </div>

    <p class="label">Email</p>
    <div class="input-box">
        <input type="text" id="username" name="username" placeholder="your_name@gmail.com" required>
    </div>

    <div class="input-box">
        <i class='bx bxs-user'></i>
        <input type="text" id="username" name="username" placeholder="Username" required>
    </div>
    <div class="input-box">
        <i class='bx bxs-lock-alt'></i>
        <input type="password" id="password" name="password" placeholder="Password" required>
    </div>
      <button type="submit" class="btn">Signup</button>
    </form>
  </div>
</body>
</html>

