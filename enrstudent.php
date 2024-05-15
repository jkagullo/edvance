<?php
    include "db.php";

    $newEnrollmentID = 0;
    $courses = array(); // Define the $courses array

    $message = ""; // Initialize message variable

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Find the highest current EnrollmentID
        $result = $conn->query("SELECT MAX(EnrollmentID) AS maxID FROM enrollment");
        $row = $result->fetch_assoc();
        $maxID = (int)$row['maxID'];
        $newEnrollmentID = $maxID + 1;

        // Retrieve form data
        $studentID = $conn->real_escape_string($_POST['StudentID']);
        $courseID = $conn->real_escape_string($_POST['CourseID']);
        $grade = $conn->real_escape_string($_POST['Grade']);

        // Insert new enrollment with generated EnrollmentID
        $sql = "INSERT INTO enrollment (EnrollmentID, StudentID, CourseID, Grade) VALUES ('$newEnrollmentID', '$studentID', '$courseID', '$grade')";

        if ($conn->query($sql) === TRUE) {
            $message = "New enrollment added successfully";
        } else {
            $message = "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    } else {
        // Get all courses
        $result = $conn->query("SELECT * FROM course");
        while ($row = $result->fetch_assoc()) {
            $courses[] = $row;
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="enr.css">
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

    <div class="wrapper">
        <div class="header-container">
            <a href="index.php"><img class="back" src="assets/backicon.png"></a>
            <h1>Enroll Student</h1>
        </div>
        <div class="content">
    <form action="enrstudent.php" method="post">
        <p> Note: Enrollment ID will be auto generated </p><br>
        <?php
            echo "<p> Enrollment ID: " . $newEnrollmentID . "</p>";
        ?>
        <label for="StudentID">Student ID:</label>
        <input type="text" id="StudentID" name="StudentID" required><br>

        <label for="CourseID">Course ID:</label>
        <select id="CourseID" name="CourseID" required>
            <?php
                foreach ($courses as $course) {
                    echo "<option value=\"" . $course['CourseID'] . "\">" . $course['CourseName'] . "</option>";
                }
            ?>
        </select><br>

        <label for="Grade">Grade:</label>
        <select id="Grade" name="Grade" required>
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="C">C</option>
            <option value="D">D</option>
            <option value="E">E</option>
            <option value="F">F</option>
        </select><br>

        <input type="submit" value="Enroll Student">
    </form>
</div>
    </div>
    <div id="snackbar" class="<?php echo $message ? 'show' : ''; ?>"><?php echo $message; ?></div>

    <script>
        function showSnackbar() {
            var snackbar = document.getElementById("snackbar");
            snackbar.className = "snackbar show";
            setTimeout(function(){ snackbar.className = snackbar.className.replace("show", ""); }, 3000);
        }

        <?php if ($message): ?>
            window.onload = function() {
                showSnackbar();
            };
        <?php endif; ?>
    </script>
</body>
</html>