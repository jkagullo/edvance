<?php
    include "db.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="forms.css">
    <title>edvance</title>
</head>
<body>
    <div class="header">
        <div class="card1">
            <p> Student Management System </p>
        </div>
        <div class="card2">
            <a href="index.php"><button class="forms <?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'selected' : ''; ?>">
                <p> Forms </p>
            </button></a>
            <a href="reports.php"><button class="reports">
                <p> Reports </p>
            </button></a>
        </div>   
    </div>

    <div class="container1">
        <h1> Choose A Form </h1>
        <div class="form-cards">
            <div class="form-card1">
                <img src="assets/addstudent.png">
                <p class="head-card"> Create another record of student </p>
                <a href="addstudent.php"><button class="button">Add Student</button></a>
            </div>

            <div class="form-card2">
                <img src="assets/updatestudent.png">
                <p class="head-card">Update student's information</p>
                <a href="updstudent.php"><button class="button">Update Student</button></a>
            </div>

            <div class="form-card3">
                <img src="assets/deletestudent.png">
                <p class="head-card">Delete a student from the database</p>
                <a href="delstudent.php"><button class="button">Delete Student</button></a>
            </div>

            <div class="form-card4">
                <img src="assets/enrollstudent.png">
                <p class="head-card">Enroll a new student</p>
                <a href="enrstudent.php"><button class="button">Enroll Student</button></a>
            </div>
        </div>
    </div>
</body>
</html>
