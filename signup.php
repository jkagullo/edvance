<?php 
include "db.php"; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the submitted form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Set the default role as 'student'
    $role = 'student';

    // Prepare and execute the SQL query to insert the new user
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashed_password, $role);

    if ($stmt->execute()) {
        // Redirect to login page after successful signup
        header("Location: index.php");
        exit();
    } else {
        $error_message = "Error: " . $stmt->error;
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
    <link rel="stylesheet" href="static/css/signup.css">
    <link rel="icon" href="assets/logo3.svg" type="image/x-icon">
    <title>edvance.</title>
</head>
<body>
  <div class="wrapper">
    <form action="signup.php" method="POST">
        <img src="assets/logo1.svg" alt="logo" class="logo">
        <h2>Create an edvance account</h2>
        <p class="no-acc">Already have an account? <a href="index.php">Log in</a></p>

        <?php if (isset($error_message)): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <p class="label">Account Username</p>
        <div class="input-box">
            <i class='bx bxs-user'></i>
            <input type="text" id="username" name="username" placeholder="Username" required>
        </div>
        
        <p class="label">Account Password</p>
        <div class="input-box">
            <i class='bx bxs-lock-alt'></i>
            <input type="password" id="password" name="password" placeholder="Password" required>
        </div>
        <button type="submit" class="btn">Signup</button>
    </form>
  </div>
</body>
</html>
