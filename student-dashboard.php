<?php
include "db.php";
session_start();

// Check if the user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Prepare and execute the SQL query to get student details
$stmt = $conn->prepare("SELECT id, first_name, last_name, email FROM students WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($student_id, $first_name, $last_name, $email);
$stmt->fetch();
$stmt->close();

// Fetch the highest grade and its respective course name
$stmt = $conn->prepare("
    SELECT c.course_name, MAX(e.grade) 
    FROM enrollments e 
    JOIN courses c ON e.course_id = c.id 
    WHERE e.student_id = ?
");
$stmt->bind_param("i", $student_id);
if (!$stmt->execute()) {
    die('Error executing query: ' . $stmt->error);
}
$stmt->bind_result($highest_grade_course, $highest_grade);
$stmt->fetch();
$stmt->close();

// Fetch the lowest grade and its respective course name
$stmt = $conn->prepare("
    SELECT c.course_name, MIN(e.grade) 
    FROM enrollments e 
    JOIN courses c ON e.course_id = c.id 
    WHERE e.student_id = ? 
");

$stmt->bind_param("i", $student_id);
if (!$stmt->execute()) {
    die('Error executing query: ' . $stmt->error);
}
$stmt->bind_result($lowest_grade_course, $lowest_grade);
$stmt->fetch();
$stmt->close();

// Prepare and execute the SQL query to get the total credits of the student
$stmt = $conn->prepare("
    SELECT SUM(c.credits) 
    FROM enrollments e 
    JOIN courses c ON e.course_id = c.id 
    WHERE e.student_id = ?
");
$stmt->bind_param("i", $student_id);
if (!$stmt->execute()) {
    die('Error executing query: ' . $stmt->error);
}
$stmt->bind_result($total_credits);
$stmt->fetch();
$stmt->close();

// Set the default timezone to the Philippines
date_default_timezone_set("Asia/Manila");
// Get the current date and time
$current_datetime = date("Y-m-d H:i:s");

$conn->close();
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
    <link rel="stylesheet" href="static/css/student-dashboard.css">
    <link rel="icon" href="assets/logo3.svg" type="image/x-icon">
    <title>Student Dashboard</title>
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
                    <span class="profession">Student</span>
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
                        <a href="student-dashboard.php">
                            <i class='bx bx-home-alt icon'></i>
                            <span class="text nav-text">Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="student-grades.php">
                            <i class='bx bx-server icon' ></i>
                            <span class="text nav-text">Grades</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="student-account.php">
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
        <div class="text">Dashboard</div>
        <div class="wrapper">
            <div class="card card1">
                <p class="header">Student Information</p>

                <table class="body-text">
                    <tr>
                        <td class="bold">Student ID</td>
                        <td><?php echo $student_id; ?></td>
                    </tr>
                    <tr>
                        <td class="bold">First Name</td>
                        <td><?php echo $first_name; ?></td>
                    </tr>
                    <tr>
                        <td class="bold">Last Name</td>
                        <td><?php echo $last_name; ?></td>
                    </tr>
                    <tr>
                        <td class="bold">Email</td>
                        <td><?php echo $email; ?></td>
                    </tr>
                </table>
            </div>
            <div class="card card2">
                <div class="content content1">
                    <p class="header">Highest Grade</p>
                    <p class="value"><?php echo $highest_grade; ?></p>
                    <p class="target"><?php echo $highest_grade_course; ?></p>
                </div>
                <div class="content content2">
                    <p class="header">Lowest Grade</p>
                    <p class="value"><?php echo $lowest_grade; ?></p>
                    <p class="target"><?php echo $lowest_grade_course; ?></p>
                </div>
                <div class="content content3">
                    <p class="header">Total Credits</p>
                    <p class="value"><?php echo $total_credits; ?></p>
                    <p class="target"><?php echo date("d/m/y", strtotime($current_datetime)); ?></p>
                </div>
            </div>
        </div>
    </section>
    <script src="script.js"></script>
</body>