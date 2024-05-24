<?php
    include "db.php";

    $message = "";
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $studentID = $conn->real_escape_string($_POST['StudentID']);

        // Start a transaction
        $conn->begin_transaction();
        try {
            // Delete associated records in the enrollment table
            $deleteEnrollment = "DELETE FROM enrollment WHERE StudentID = '$studentID'";
            $conn->query($deleteEnrollment);

            // Delete the student record
            $deleteStudent = "DELETE FROM student WHERE StudentID = '$studentID'";
            if ($conn->query($deleteStudent) === TRUE) {
                $message = "Student deleted successfully!";
            } else {
                throw new Exception("Error deleting student: " . $conn->error);
            }

            // Commit the transaction
            $conn->commit();
        } catch (Exception $e) {
            // Rollback the transaction in case of error
            $conn->rollback();
            $message = "Error: " . $e->getMessage();
        }

        header("Location: delstudent.php?message=" . urlencode($message));
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
    <link rel="stylesheet" href="del.css">
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
            <h1>Delete Student</h1>
        </div>
        <div class="content">
            <form action="delstudent.php" method="post">
                <p>Input the Student ID to delete</p><br>
                <label for="StudentID">Student ID: </label>
                <input type="text" id="StudentID" name="StudentID" required><br>
                <input type="submit" value="Delete Student">
            </form>
        </div>
    </div>

    <div id="snackbar"><?php echo isset($_GET['message']) ? $_GET['message'] : ''; ?></div>

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
