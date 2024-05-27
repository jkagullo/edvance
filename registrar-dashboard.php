<?php
    include "db.php";
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
    <link rel="stylesheet" href="static/css/registrar-dashboard.css">
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
        <div class="text">Dashboard</div>
        <div class="wrapper">
            <div class="card card1">
                <p class="header">Registrar Information</p>
                <table class="body-text">
                    <tr>
                        <td class="bold">Registrar Account ID</td>
                        <td>1</td>
                    </tr>
                    <tr>
                        <td class="bold">First Name</td>
                        <td>Lebron</td>
                    </tr>
                    <tr>
                        <td class="bold">Last Name</td>
                        <td>James</td>
                    </tr>
                    <tr>
                        <td class="bold">Email</td>
                        <td>lebron@gmail.com</td>
                    </tr>
                </table>
            </div>
            <div class="card card2">
                <div class="content content1">
                    <p class="header">Enrollments Today</p>
                    <p class="value">10</p>
                    <p class="target"></p>
                </div>
                <div class="content content2">
                    <p class="header">Total Enrollments</p>
                    <p class="value">50</p>
                    <p class="target"></p>
                </div>
                <div class="content content3">
                    <p class="header">Date and Time</p>
                    <p class="value">2:19 AM</p>
                    <p class="target">27/05/24</p>
                </div>
            </div>
        </div>
    </section>

    

    <script src="script.js"></script>
</body>
</html>