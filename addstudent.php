<?php
    include "db.php";

    $newStudentID = 0;
    $message = "";
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $result = $conn->query("SELECT MAX(StudentID) AS maxID FROM student");
        $row = $result->fetch_assoc();
        $maxID = (int)$row['maxID'];
        $newStudentID = $maxID + 1;

        $firstName = $conn->real_escape_string($_POST['FirstName']);
        $lastName = $conn->real_escape_string($_POST['LastName']);
        $age = (int)$_POST['Age'];

        $sql = "INSERT INTO student (StudentID, FirstName, LastName, Age) VALUES ('$newStudentID', '$firstName', '$lastName', $age)";

        if ($conn->query($sql) === TRUE) {
            $message = "New student added successfully!";
        } else {
            $message = "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
        header("Location: addstudent.php?message=" . urlencode($message));
        exit();
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
    <link rel="stylesheet" href="add.css">
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
            <h1>Add Student</h1>
        </div>
        <div class="content">
            <form action="addstudent.php" method="post">
                <p> Note: Student ID will be auto generated </p><br>
                <?php
                    echo "<p> Student ID: " . $newStudentID . "</p>";
                ?>
                <label for="FirstName">First Name:</label>
                <input type="text" id="FirstName" name="FirstName" required><br>

                <label for="LastName">Last Name:</label>
                <input type="text" id="LastName" name="LastName" required><br>

                <label for="Age">Age:</label>
                <input type="number" id="Age" name="Age" required><br>

                <input type="submit" value="Add Student">
            </form>
        </div>
    </div>

    <div id="snackbar"></div>

    <script>
        function showSnackbar(message) {
            var snackbar = document.getElementById('snackbar');
            snackbar.innerText = message;
            snackbar.className = 'snackbar show';
            setTimeout(function() { snackbar.className = snackbar.className.replace('show', ''); }, 3000);
        }

        <?php if (isset($_GET['message'])): ?>
            window.onload = function() {
                showSnackbar("<?php echo $_GET['message']; ?>");
            };
        <?php endif; ?>
    </script>
    
</body>
</html>
