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
    <link rel="stylesheet" href="static/css/admin-management.css">
    <link rel="icon" href="assets/logo3.svg" type="image/x-icon">
    <title>Admin Management</title>
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
                    <i class='bx bx-search icon' ></i>
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
                            <i class='bx bx-server icon' ></i>
                            <span class="text nav-text">Management</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="admin-account.php">
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
        <div class="wrapper">
            <div class="card card1">
                <p class="header">Admin Management Controls</p>
                <div class="content content1">
                    <p class="bold">Enroll Student</p>
                    <p class="body-text">Search by Student Username</p>
                    <input class="input" type="text" placeholder="Enter Student Username">
                    <div class="buttons">
                        <button>Search Student</button>
                    </div>
                    <p class="body-text">First Name</p>
                    <input type="text" class="input">
                    <p class="body-text">Last Name</p>
                    <input type="text" class="input">
                    <p class="body-text">Email</p>
                    <input type="text" class="input">
                    <p class="body-text">Choose Course to Enroll</p>
                    <select name="courses" id="courses">
                        <option value="CC103-M">Aritifical Intelligence</option>
                    </select>
                    <div class="buttons">
                        <button>Enroll Student</button>
                    </div>
                </div>
                <div class="content content2">
                    <p class="bold">Edit/Update Student</p>
                    <p class="body-text">Search by Student ID</p>
                    <input class="input" type="text" placeholder="Enter Student ID">
                    <div class="buttons">
                        <button>Search Student</button>
                    </div>
                    <p class="body-text">First Name</p>
                    <input type="text" class="input">
                    <p class="body-text">Last Name</p>
                    <input type="text" class="input">
                    <p class="body-text">Email</p>
                    <input type="text" class="input">
                    <p class="body-text">Update Course</p>
                    <select name="courses" id="courses">
                        <option value="CC103-M">Aritifical Intelligence</option>
                    </select>
                    <div class="buttons">
                        <button>Update Student</button>
                    </div>
                </div>
                <div class="content content3">
                    <p class="bold">Delete Student</p>
                    <p class="body-text">Search by Student ID</p>
                    <input class="input" type="text" placeholder="Enter Student ID">
                    <div class="buttons">
                        <button>Search Student</button>
                    </div>
                    <p class="bold">First Name</p>
                    <p class="body-text">Result</p>
                    <p class="bold">Last Name</p>
                    <p class="body-text">Result</p>
                    <p class="bold">Email</p>
                    <p class="body-text">Result</p>
                    <p class="bold">Course</p>
                    <p class="body-text">Result</p>
                    <div class="buttons">
                        <button>Delete Student</button>
                    </div>
                </div>
                <div class="content content4">
                    <p class="bold">Add Course</p>
                    <p class="body-text">Search by Student ID</p>
                    <input class="input" type="text" placeholder="Enter Student ID">
                    <div class="buttons">
                        <button>Search Student</button>
                    </div>
                    <p class="body-text">First Name</p>
                    <input type="text" class="input">
                    <p class="body-text">Last Name</p>
                    <input type="text" class="input">
                    <p class="body-text">Email</p>
                    <input type="text" class="input">
                    <p class="body-text">Update Course</p>
                    <select name="courses" id="courses">
                        <option value="CC103-M">Aritifical Intelligence</option>
                    </select>
                    <div class="buttons">
                        <button>Add Course</button>
                    </div>
                </div>
                <div class="content content5">
                    <p class="bold">Edit/Update Course</p>
                    <p class="body-text">Search by Course Code</p>
                    <input class="input" type="text" placeholder="Enter Course Code">
                    <div class="buttons">
                        <button>Search Course</button>
                    </div>
                    <p class="body-text">Course Code</p>
                    <input type="text" class="input">
                    <p class="body-text">Course Name</p>
                    <input type="text" class="input">
                    <div class="buttons">
                        <button>Update Course</button>
                    </div>
                </div>
                <div class="content content6">
                <p class="bold">Delete Course</p>
                    <p class="body-text">Search by Course Code</p>
                    <input class="input" type="text" placeholder="Enter Course Code">
                    <div class="buttons">
                        <button>Search Course</button>
                    </div>
                    <p class="bold">Course Code</p>
                    <p class="body-text">Result</p>
                    <p class="bold">Course Name</p>
                    <p class="body-text">Result</p>
                    <div class="buttons">
                        <button>Delete Course</button>
                    </div>
                </div>
                <div class="content content7">
                    <p class="bold">Add Registrar</p>
                    <p class="body-text">First Name</p>
                    <input type="text" class="input">
                    <p class="body-text">Last Name</p>
                    <input type="text" class="input">
                    <p class="body-text">Email</p>
                    <input type="text" class="input">
                    <p class="body-text">Account Username</p>
                    <input type="text" class="input">
                    <p class="body-text">Account Password</p>
                    <input type="text" class="input">
                    <div class="buttons">
                        <button>Add Registrar</button>
                    </div>
                </div>
                <div class="content content8">
                    <p class="bold">Edit/Update Registrar</p>
                    <p class="body-text">Search by Registrar ID</p>
                    <input class="input" type="text" placeholder="Enter Registrar ID">
                    <div class="buttons">
                        <button>Search Registrar</button>
                    </div>
                    <p class="body-text">First Name</p>
                    <input type="text" class="input">
                    <p class="body-text">Last Name</p>
                    <input type="text" class="input">
                    <p class="body-text">Email</p>
                    <input type="text" class="input">
                    <p class="body-text">Username</p>
                    <input type="text" class="input">
                    <p class="body-text">Password</p>
                    <input type="text" class="input">
                    <div class="buttons">
                        <button>Update Registrar</button>
                    </div>
                </div>
                <div class="content content9">
                    <p class="bold">Delete Registar</p>
                    <p class="body-text">Search by Registar ID</p>
                    <input class="input" type="text" placeholder="Enter Registrar ID">
                    <div class="buttons">
                        <button>Search Registrar</button>
                    </div>
                    <p class="bold">First Name</p>
                    <p class="body-text">Result</p>
                    <p class="bold">Last Name</p>
                    <p class="body-text">Result</p>
                    <p class="bold">Email</p>
                    <p class="body-text">Result</p>
                    <p class="bold">Username</p>
                    <p class="body-text">Result</p>
                    <p class="bold">Password</p>
                    <p class="body-text">Result</p>
                    <div class="buttons">
                        <button>Delete Registrar</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    

    <script src="script.js"></script>
</body>
</html>