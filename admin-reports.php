<?php
include "db.php";
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Initialize variables
$tableData = '';
$selectedReport = isset($_POST['reports']) ? $_POST['reports'] : '';

if ($selectedReport) {
    // Query the database based on the selected report
    if ($selectedReport == 'students') {
        $query = "SELECT * FROM students";
    } elseif ($selectedReport == 'courses') {
        $query = "SELECT * FROM courses";
    } elseif ($selectedReport == 'enrollments') {
        $query = "SELECT * FROM enrollments";
    } elseif ($selectedReport == 'registrars') {
        $query = "SELECT * FROM registrars";
    }

    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        // Build the table header
        $tableData .= '<table border="1"><tr>';
        $fields = mysqli_fetch_fields($result);
        foreach ($fields as $field) {
            $tableData .= '<th>' . $field->name . '</th>';
        }
        $tableData .= '</tr>';

        // Build the table rows
        while ($row = mysqli_fetch_assoc($result)) {
            $tableData .= '<tr>';
            foreach ($row as $cell) {
                $tableData .= '<td>' . $cell . '</td>';
            }
            $tableData .= '</tr>';
        }
        $tableData .= '</table>';
    } else {
        $tableData = '<p>No records found.</p>';
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
    <link rel="stylesheet" href="static/css/admin-reports.css">
    <link rel="icon" href="assets/logo3.svg" type="image/x-icon">
    <title>Admin Reports</title>
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
                    <span class="profession">Admin</span>
                </div>
            </div>
            <i class='bx bx-chevron-right toggle'></i>
        </header>
        <div class="menu-bar">
            <div class="menu">
                <li class="search-box">
                    <i class='bx bx-search icon'></i>
                    <input type="text" placeholder="Search">
                </li>
                <ul class="menu-links">
                    <li class="nav-link">
                        <a href="admin-dashboard.php">
                            <i class='bx bx-home-alt icon'></i>
                            <span class="text nav-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="admin-management.php">
                            <i class='bx bx-server icon'></i>
                            <span class="text nav-text">Management</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="admin-account.php">
                            <i class='bx bxs-cog icon'></i>
                            <span class="text nav-text">Account Settings</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="admin-reports.php">
                            <i class='bx bxs-report icon'></i>
                            <span class="text nav-text">Reports</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="bottom-content">
                <li class="nav-link">
                    <a href="index.php">
                        <i class='bx bx-log-out icon'></i>
                        <span class="text nav-text">Logout</span>
                    </a>
                </li>
                <li class="mode">
                    <div class="moon-sun">
                        <i class='bx bx-moon icon moon'></i>
                        <i class='bx bx-sun icon sun'></i>
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
        <div class="text">Reports</div>
        <div class="wrapper">
            <div class="card card1">
                <p class="header">Select A Report</p>
                <form method="post" action="">
                    <select name="reports" id="reports" onchange="this.form.submit()">
                        <option value="">Select a report</option>
                        <option value="students" <?php if($selectedReport == 'students') echo 'selected'; ?>>Students</option>
                        <option value="courses" <?php if($selectedReport == 'courses') echo 'selected'; ?>>Courses</option>
                        <option value="enrollments" <?php if($selectedReport == 'enrollments') echo 'selected'; ?>>Enrollments</option>
                        <option value="registrars" <?php if($selectedReport == 'registrars') echo 'selected'; ?>>Registrars</option>
                    </select>
                </form>
            </div>
            <div class="card card2">
                <?php echo $tableData; ?>
            </div>
        </div>
    </section>
</body>
<script src="script.js"></script>
</html>
