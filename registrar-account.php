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
    <link rel="stylesheet" href="static/css/registrar-account.css">
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
        <div class="text">Account Settings</div>
        <div class="wrapper">
            <div class="card card1">
                <p class="header">Account Settings Information</p>
                <table class="body-text">
                    <tr>
                        <td class="bold">First Name</td>
                        <td>Jhan Kyle</td>
                        <td class="button"><a href="#"><button class="edit">Edit</button></a></td>
                    </tr>
                    <tr>
                        <td class="bold">Last Name</td>
                        <td>Agullo</td>
                        <td class="button"><a href=""><button class="edit">Edit</button></a></td>
                    </tr>
                    <tr>
                        <td class="bold">Email</td>
                        <td>jkyleagullo@gmail.com</td>
                        <td class="button"><a href=""><button class="edit">Edit</button></a></td>
                    </tr>
                    <tr>
                        <td class="bold">Account Username</td>
                        <td>agullo</td>
                        <td class="button"><a href=""><button class="edit">Edit</button></a></td>
                    </tr>
                    <tr>
                        <td class="bold">Account Password</td>
                        <td>******</td>
                        <td class="button"><a href=""><button class="edit">Edit</button></a></td>
                    </tr>
                </table>
            </div>
        </div>
    </section>

    

    <script src="script.js"></script>
</body>
</html>