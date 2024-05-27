<?php
include "db.php";

// Start the session to use session variables
session_start();


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

// Handle Add Student form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_student'])) {
    // Validate user ID
    if (empty($_POST["user_id"])) {
        $userIdErr = "User ID is required";
    } else {
        $userId = test_input($_POST["user_id"]);
    }

    // Validate first name
    if (empty($_POST["first_name"])) {
        $firstNameErr = "First name is required";
    } else {
        $firstName = test_input($_POST["first_name"]);
    }

    // Validate last name
    if (empty($_POST["last_name"])) {
        $lastNameErr = "Last name is required";
    } else {
        $lastName = test_input($_POST["last_name"]);
    }

    // Validate email
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        // Check if email address is well-formed
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    // If all fields are valid, insert the student into the database
    if (empty($userIdErr) && empty($firstNameErr) && empty($lastNameErr) && empty($emailErr)) {
        // Generate the next student_id by incrementing the last student_id
        $stmt = $conn->query("SELECT MAX(student_id) AS max_student_id FROM students");
        $last_student_id = $stmt->fetch_assoc()['max_student_id'];
        $next_student_id = ($last_student_id ? $last_student_id + 1 : 1001);

        $stmt = $conn->prepare("INSERT INTO students (user_id, student_id, first_name, last_name, email) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisss", $userId, $next_student_id, $firstName, $lastName, $email);
        if ($stmt->execute()) {
            // Student added successfully
            $successMessage = "Student added successfully";
        } else {
            // Error inserting student
            $errorMessage = "Error adding student: " . $conn->error;
        }
        $stmt->close();
        // Set session variable to display success/error message
        $_SESSION['update_status'] = $successMessage ? $successMessage : $errorMessage;
        header("Location: " . $_SERVER['PHP_SELF']); // Redirect to refresh the page
        exit();
    }
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

// Function to sanitize form input
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
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
    <link rel="stylesheet" href="static/css/registrar-management.css">
    <link rel="icon" href="assets/logo3.svg" type="image/x-icon">
    <title>Registrar Management</title>
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
        <div class="text">Registrar Management</div>
        <div class="wrapper">
            <div class="card card1">
                <p class="header">Add A Student</p>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <input type="hidden" name="add_student" value="1">
                    <p class="bold">Select by Student User ID</p>
                    <select name="user_id" id="user">
                        <?php foreach ($userStudents as $user): ?>
                            <option value="<?php echo $user['id']; ?>"><?php echo $user['id'] . " - " . $user['username']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="error"><?php echo $userIdErr;?></span>
                    <p class="bold">First Name</p>
                    <input class="input" type="text" name="first_name" value="<?php echo $firstName;?>">
                    <span class="error"><?php echo $firstNameErr;?></span>
                    <p class="bold">Last Name</p>
                    <input class="input" type="text" name="last_name" value="<?php echo $lastName;?>">
                    <span class="error"><?php echo $lastNameErr;?></span>
                    <p class="bold">Email</p>
                    <input class="input" type="text" name="email" value="<?php echo $email;?>">
                    <span class="error"><?php echo $emailErr;?></span>
                    <div class="buttons">
                        <button type="submit">Add Student</button>
                    </div>
                </form>
            </div>
            <div class="card card1">
                <p class="header">Enroll A Student</p>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <input type="hidden" name="enroll_student" value="1">
                    <p class="bold">Select Student</p>
                    <select name="student_id" id="student">
                        <?php foreach ($students as $student): ?>
                            <option value="<?php echo $student['id']; ?>"><?php echo $student['student_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="error"><?php echo $studentIdErr;?></span>
                    <p class="bold">Select Course</p>
                    <select name="course_id" id="course">
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
