<?php
include "db.php";
session_start();

// Check if the user is logged in and is a registrar
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'registrar') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch registrar information from the database
$query = "SELECT registrars.first_name, registrars.last_name, registrars.email, users.username, users.password 
          FROM registrars 
          INNER JOIN users ON registrars.user_id = users.id
          WHERE registrars.user_id = $user_id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_account'])) {
    // Get form data
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    // Update registrar information in the database
    $update_query = "UPDATE registrars 
                     INNER JOIN users ON registrars.user_id = users.id
                     SET registrars.first_name = '$first_name', registrars.last_name = '$last_name', 
                         registrars.email = '$email', users.username = '$username', users.password = '$password'
                     WHERE registrars.user_id = $user_id";
    $update_result = mysqli_query($conn, $update_query);
    
    if($update_result) {
        // Set success message in session
        $_SESSION['update_status'] = "Account information updated successfully.";
    } else {
        // Set error message in session
        $_SESSION['update_status'] = "Error updating account information: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="static/css/registrar-account.css">
    <link rel="icon" href="assets/logo3.svg" type="image/x-icon">
    <title>Registrar Account</title>
</head>
<body>
    <nav class="sidebar close">
        <header>
            <div class="image-text">
                <span class="image">
                    <img src="assets/logo3.svg" alt="logo">
                </span>

                <div class="text header-text">
                    <span class="name">Edvance</span>
                    <span class="profession">Registrar</span>
                </div>
            </div>

            <i class='bx bx-chevron-right toggle'></i>
        </header>
        <div class="menu-bar">
            <div class="menu">
                <li class="search-box">
                    <i class='bx bx-search icon' ></i>
                    <input type="text" placeholder="Search">
                </li>
                <ul class="menu-links">
                    <li class="nav-link">
                        <a href="registrar-dashboard.php">
                            <i class='bx bx-home-alt icon'></i>
                            <span class="text nav-text">Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="registrar-management.php">
                            <i class='bx bx-server icon' ></i>
                            <span class="text nav-text">Management</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="registrar-account.php">
                            <i class='bx bxs-cog icon' ></i>
                            <span class="text nav-text">Account Settings</span>
                        </a>
                    </li>

                </ul>
            </div>
            <div class="bottom-content">
                <li class="nav-link">
                    <a href="index.php">
                        <i class='bx bx-log-out icon' ></i>
                        <span class="text nav-text">Logout</span>
                    </a>
                </li>

                <li class="mode">
                    <div class="moon-sun">
                        <i class='bx bx-moon icon moon' ></i>
                        <i class='bx bx-sun icon sun' ></i>
                    </div>
                    <span class="mode-text text">Dark Mode</span>

                    <div class="toggle-switch">
                        <span class="switch"></span>
                    </div>
                </li>
            </div>
        </div>
    </nav>

    <section class="home">
        <div class="text">Account Settings</div>
        <div class="wrapper">
            <div class="card card1">
                <p class="header">Account Settings Information</p>
                <form method="POST">
                <p class="bold">First Name</p>
                <input type="text" name="first_name" value="<?php echo $row['first_name'];?>">
                <p class="bold">Last Name</p>
                <input type="text" name="last_name" value="<?php echo $row['last_name'];?>">
                <p class="bold">Email</p>
                <input type="text" name="email" value="<?php echo $row['email'];?>">
                <p class="bold">Account Username</p>
                <input type="text" name="username" value="<?php echo $row['username'];?>">
                <p class="bold">Account Password</p>
                <input type="text" name="password" value="<?php echo $row['password'];?>">
                <button type="submit" name="update_account">Update Account</button>
            </form>
            </div>
        </div>
    </section>
 
    <div id="snackbar"></div>
    <script src="script.js"></script>
    <script>
    // Get the snackbar div
    var snackbar = document.getElementById("snackbar");

    // Check if the session variable is set
    <?php if(isset($_SESSION['update_status'])): ?>
        // Display the snackbar with appropriate message
        snackbar.innerText = "<?php echo $_SESSION['update_status']; ?>";
        snackbar.className = "show";
        
        // After 3 seconds, remove the show class from DIV
        setTimeout(function(){ snackbar.className = snackbar.className.replace("show", ""); }, 3000);
        
        // Unset the session variable
        <?php unset($_SESSION['update_status']); ?>
    <?php endif; ?>
    </script>
    
</body>
</html>