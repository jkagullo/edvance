<?php
include "db.php";
session_start();

// Check if the user is logged in and is a registrar
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch students for the dropdown in "Enroll Student"
$studentsResult = $conn->query("SELECT id, CONCAT(first_name, ' ', last_name) AS student_name FROM students");
$students = $studentsResult->fetch_all(MYSQLI_ASSOC);

// Fetch courses for the dropdown
$coursesResult = $conn->query("SELECT id, course_name FROM courses");
$courses = $coursesResult->fetch_all(MYSQLI_ASSOC);

// Fetch user IDs with the role "student" for the "Add Student" form
$userStudentsResult = $conn->query("SELECT id, username FROM users WHERE role = 'student'");
$userStudents = $userStudentsResult->fetch_all(MYSQLI_ASSOC);

// Initialize variables to store form data and error messages
$userId = $firstName = $lastName = $email = "";
$userIdErr = $firstNameErr = $lastNameErr = $emailErr = "";
$studentId = $courseId = $grade = "";
$studentIdErr = $courseIdErr = $gradeErr = "";
$successMessage = $errorMessage = "";

// Function to sanitize form input
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Handle Enroll Student form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['enroll_student'])) {
    // Validate student ID
    if (empty($_POST["student_id"])) {
        $studentIdErr = "Student ID is required";
    } else {
        $studentId = test_input($_POST["student_id"]);
    }

    // Validate course ID
    if (empty($_POST["course_id"])) {
        $courseIdErr = "Course ID is required";
    } else {
        $courseId = test_input($_POST["course_id"]);
    }

    // Validate grade
    if (empty($_POST["grade"])) {
        $gradeErr = "Grade is required";
    } elseif (!is_numeric($_POST["grade"]) || $_POST["grade"] < 0 || $_POST["grade"] > 100) {
        $gradeErr = "Grade must be a number between 0 and 100";
    } else {
        $grade = test_input($_POST["grade"]);
    }

    // If all fields are valid, insert the enrollment into the database
    if (empty($studentIdErr) && empty($courseIdErr) && empty($gradeErr)) {
        $enrollmentDate = date("Y-m-d H:i:s");
        $stmt = $conn->prepare("INSERT INTO enrollments (student_id, course_id, enrollment_date, grade) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iisi", $studentId, $courseId, $enrollmentDate, $grade);
        if ($stmt->execute()) {
            // Enrollment added successfully
            $successMessage = "Student enrolled successfully";
        } else {
            // Error inserting enrollment
            $errorMessage = "Error enrolling student: " . $conn->error;
        }
        $stmt->close();
        // Set session variable to display success/error message
        $_SESSION['update_status'] = $successMessage ? $successMessage : $errorMessage;
        header("Location: " . $_SERVER['PHP_SELF']); // Redirect to refresh the page
        exit();
    }
}

// Handle Edit/Update Student form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_student'])) {
    // Retrieve student ID from the form
    $studentId = test_input($_POST["student_id"]);

    // Fetch student information from the database
    $stmt = $conn->prepare("SELECT id, first_name, last_name, email FROM students WHERE id = ?");
    $stmt->bind_param("i", $studentId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Student found, populate the input fields
        $row = $result->fetch_assoc();
        $userId = $row['id'];
        $firstName = $row['first_name'];
        $lastName = $row['last_name'];
        $email = $row['email'];
    } else {
        // Student not found
        $errorMessage = "Student not found";
    }

    $stmt->close();
}

// Handle Update Student form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_student'])) {
    // Retrieve and validate form data
    $userId = test_input($_POST["user_id"]);
    $firstName = test_input($_POST["first_name"]);
    $lastName = test_input($_POST["last_name"]);
    $email = test_input($_POST["email"]);

    // Update student information in the database
    $stmt = $conn->prepare("UPDATE students SET first_name = ?, last_name = ?, email = ? WHERE id = ?");
    $stmt->bind_param("sssi", $firstName, $lastName, $email, $userId);
    if ($stmt->execute()) {
        // Student information updated successfully
        $successMessage = "Student information updated successfully";
    } else {
        // Error updating student information
        $errorMessage = "Error updating student information: " . $conn->error;
    }
    $stmt->close();

    // Set session variable to display success/error message
    $_SESSION['update_status'] = $successMessage ? $successMessage : $errorMessage;
    header("Location: " . $_SERVER['PHP_SELF']); // Redirect to refresh the page
    exit();
}

// Handle Delete Student form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_student'])) {
    // Retrieve user ID with role 'student' from the form
    $deleteUserId = test_input($_POST["delete_student_id"]);

    // Fetch user information from the database
    $stmt = $conn->prepare("SELECT id, username, role FROM users WHERE id = ? AND role = 'student'");
    $stmt->bind_param("i", $deleteUserId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Student found, store the information
        $studentInfo = $result->fetch_assoc();
    } else {
        // Student not found
        $errorMessage = "Student not found";
    }

    $stmt->close();
}

// Handle Delete Student button click
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_student'])) {
    // Retrieve user ID with role 'student' from the form
    $deleteUserId = test_input($_POST["delete_student_id"]);

    // Delete the student (user with role 'student') from the database
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND role = 'student'");
    $stmt->bind_param("i", $deleteUserId);
    if ($stmt->execute()) {
        // Student deleted successfully
        $successMessage = "Student deleted successfully";
    } else {
        // Error deleting student
        $errorMessage = "Error deleting student: " . $conn->error;
    }
    $stmt->close();

    // Set session variable to display success/error message
    $_SESSION['update_status'] = isset($successMessage) ? $successMessage : (isset($errorMessage) ? $errorMessage : "An unknown error occurred.");
    header("Location: " . $_SERVER['PHP_SELF']); // Redirect to refresh the page
    exit();
}

// Handle Add Course form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_course'])) {
    // Retrieve and sanitize form data
    $courseCode = test_input($_POST["course_code"]);
    $courseName = test_input($_POST["course_name"]);
    $credits = test_input($_POST["credits"]);

    // Validate credits
    if (!is_numeric($credits) || $credits < 0 || $credits > 100) {
        $errorMessage = "Credits must be a number between 0 and 100";
    } else {
        // Insert the course into the database
        $stmt = $conn->prepare("INSERT INTO courses (course_code, course_name, credits) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $courseCode, $courseName, $credits);
        if ($stmt->execute()) {
            // Course added successfully
            $successMessage = "Course added successfully";
        } else {
            // Error adding course
            $errorMessage = "Error adding course: " . $conn->error;
        }
        $stmt->close();
    }

    // Set session variable to display success/error message
    $_SESSION['update_status'] = isset($successMessage) ? $successMessage : (isset($errorMessage) ? $errorMessage : "An unknown error occurred.");
    header("Location: " . $_SERVER['PHP_SELF']); // Redirect to refresh the page
    exit();
}


// Close the database connection
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
                    <p class="bold">Enroll A Student</p>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <input type="hidden" name="enroll_student" value="1">
                        <p class="bold">Select Student</p>
                        <select name="student_id" id="student">
                        <option value="" selected>Select Student</option> <!-- Default option -->
                            <?php foreach ($students as $student): ?>
                                <option value="<?php echo $student['id']; ?>"><?php echo $student['student_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="error"><?php echo $studentIdErr;?></span>
                        <p class="bold">Select Course</p>
                        <select name="course_id" id="course">
                        <option value="" selected>Select Course</option> <!-- Default option -->
                            <?php foreach ($courses as $course): ?>
                                <option value="<?php echo $course['id']; ?>"><?php echo $course['course_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="error"><?php echo $courseIdErr;?></span>
                        <p class="bold">Grade</p>
                        <input class="input" type="number" name="grade" value="<?php echo $grade;?>" min="0" max="100">
                        <span class="error"><?php echo $gradeErr;?></span>
                        <div class="buttons">
                            <button type="submit">Enroll Student</button>
                        </div>
                    </form>
                </div>
                <div class="content content2">
                    <p class="bold">Edit/Update Student</p>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <input type="hidden" name="edit_student" value="1">
                        <p class="body-text">Search by Student ID</p>
                        <input class="input" type="text" name="student_id" placeholder="Enter Student ID">
                        <div class="buttons">
                            <button type="submit">Search Student</button>
                        </div>
                        <!-- Input fields for student details -->
                        <p class="body-text">First Name</p>
                        <input type="text" class="input" name="first_name" value="<?php echo $firstName; ?>">
                        <p class="body-text">Last Name</p>
                        <input type="text" class="input" name="last_name" value="<?php echo $lastName; ?>">
                        <p class="body-text">Email</p>
                        <input type="text" class="input" name="email" value="<?php echo $email; ?>">
                        <!-- Hidden field for user ID -->
                        <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
                        <!-- Dropdown for updating course (if necessary) -->
                        <p class="body-text">Update Course</p>
                        <select name="courses" id="courses">
                            <option value="" selected>Select Course</option> <!-- Default option -->
                            <?php foreach ($courses as $course): ?>
                                <option value="<?php echo $course['id']; ?>"><?php echo $course['course_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="buttons">
                            <button type="submit" name="update_student">Update Student</button>
                        </div>
                    </form>
                </div>
                <div class="content content3">
                    <p class="bold">Delete Student</p>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <p class="body-text">Search by User ID</p>
                        <input class="input" type="text" name="delete_student_id" placeholder="Enter Student ID">
                        <div class="buttons">
                            <button type="submit" name="search_student">Search Student</button>
                        </div>
                        <?php if(isset($studentInfo)): ?>
                        <p class="bold">Username</p>
                        <p class="body-text"><?php echo $studentInfo['username']; ?></p>
                        <p class="bold">Role</p>
                        <p class="body-text"><?php echo $studentInfo['role']; ?></p>
                        <!-- You can display more information here if needed -->
                        <input type="hidden" name="delete_student_id" value="<?php echo $studentInfo['id']; ?>">
                        <div class="buttons">
                            <button type="submit" name="delete_student">Delete Student</button>
                        </div>
                        <?php endif; ?>
                    </form>
                </div>
                <div class="content content4">
                    <p class="bold">Add Course</p>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <p class="body-text">Course Code</p>
                    <input type="text" class="input" name="course_code" required>
                    <p class="body-text">Course Name</p>
                    <input type="text" class="input" name="course_name" required>
                    <p class="body-text">Credits</p>
                    <input type="number" class="input" name="credits" min="0" max="100" required>
                    <div class="buttons">
                        <button type="submit" name="add_course">Add Course</button>
                    </div>
                    </form>
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

    <div id="snackbar"></div>
    
    <script src="script.js"></script>
    <script>
    // Get the snackbar div
    var snackbar = document.getElementById("snackbar");

    // Check if the session variable is set
    <?php if(isset($_SESSION['update_status'])): ?>
        // Display the snackbar with appropriate message
        snackbar.innerText = "<?php echo $_SESSION['update_status']; ?>";
        snackbar.className = "show";
        
        // After 3 seconds, remove the show class from DIV
        setTimeout(function(){ snackbar.className = snackbar.className.replace("show", ""); }, 3000);
        
        // Unset the session variable
        <?php unset($_SESSION['update_status']); ?>
    <?php endif; ?>
    </script>
</body>
</html>