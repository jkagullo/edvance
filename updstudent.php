<?php
    include "db.php";

    $student = null;
    $message = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['StudentID'])) {
            $studentID = $conn->real_escape_string($_POST['StudentID']);
            $result = $conn->query("SELECT * FROM student WHERE StudentID = '$studentID'");
            if ($result->num_rows > 0) {
                $student = $result->fetch_assoc();
            } else {
                $message = "No student found with ID: $studentID";
            }
        } else {
            $studentID = $conn->real_escape_string($_POST['UpdateStudentID']);
            $firstName = $conn->real_escape_string($_POST['FirstName']);
            $lastName = $conn->real_escape_string($_POST['LastName']);
            $age = (int)$_POST['Age'];
            $sql = "UPDATE student SET FirstName = '$firstName', LastName = '$lastName', Age = $age WHERE StudentID = '$studentID'";
            if ($conn->query($sql) === TRUE) {
                $message = "Student updated successfully";
            } else {
                $message = "Error: " . $sql . "<br>" . $conn->error;
            }
            header("Location: updstudent.php?message=" . urlencode($message));
            exit();
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
    <link rel="stylesheet" href="upd.css">
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
            <h1>Update Student</h1>
        </div>
        <div class="content">
    <form action="updstudent.php" method="post">
        <p> Note: Input the Student ID to update </p><br>
        <label for="StudentID">Student ID: </label>
        <input type="text" id="StudentID" name="StudentID" required><br>
        <input type="submit" value="Search Student">
    </form>

    <?php if ($student): ?>
        <form action="updstudent.php" method="post">
            <input type="hidden" name="UpdateStudentID" value="<?php echo $student['StudentID']; ?>">
            <label for="FirstName">First Name: </label>
            <input type="text" id="FirstName" name="FirstName" value="<?php echo $student['FirstName']; ?>" required><br>
            <label for="LastName">Last Name: </label>
            <input type="text" id="LastName" name="LastName" value="<?php echo $student['LastName']; ?>" required><br>
            <label for="Age">Age: </label>
            <input type="number" id="Age" name="Age" value="<?php echo $student['Age']; ?>" required><br>
            <input type="submit" value="Update Student">
        </form>
    <?php endif; ?>
</div>
    </div>
    <div id="snackbar" class="<?php echo isset($_GET['message']) ? 'show' : ''; ?>"><?php echo isset($_GET['message']) ? $_GET['message'] : ''; ?></div>

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
