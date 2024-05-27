<?php 
include "db.php"; 

session_start(); // Start the session to store user data

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the submitted form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute the SQL query to find the user
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    
    // Check if a user with the given username exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $stored_password, $role);
        $stmt->fetch();
        
        // Verify the password (plain text comparison)
        if ($password === $stored_password) {
            // Store user data in session
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;
            
            // Redirect based on user role
            if ($role == 'admin') {
                header("Location: admin-dashboard.php");
            } elseif ($role == 'registrar') {
                header("Location: registrar-dashboard.php");
            } elseif ($role == 'student') {
                header("Location: student-dashboard.php");
            }
            exit(); // Ensure the script stops executing after the redirect
        } else {
            $error_message = "Invalid password. Please try again.";
        }
    } else {
        $error_message = "Username not found. Please try again.";
    }
    
    $stmt->close();
}

$conn->close();
?>

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
    <link rel="stylesheet" href="static/css/index.css">
    <link rel="icon" href="assets/logo3.svg" type="image/x-icon">
    <title>edvance.</title>
</head>
<body>
  <div class="wrapper">
    <form action="index.php" method="POST">
        <img src="assets/logo1.svg" alt="logo" class="logo">
        <h1>edvance</h1>
        <h2>Welcome back!</h2>
        <p class="no-acc">Don't have an account yet? <a href="signup.php">Sign up</a></p>
        <?php if (isset($error_message)): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <div class="input-box">
            <i class='bx bxs-user'></i>
            <input type="text" id="username" name="username" placeholder="Username" required>
        </div>
        <div class="input-box">
            <i class='bx bxs-lock-alt'></i>
            <input type="password" id="password" name="password" placeholder="Password" required>
        </div>
        <button type="submit" class="btn">Login</button>
    </form>
  </div>
</body>
</html>
