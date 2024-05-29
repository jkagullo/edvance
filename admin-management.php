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

// Initialize variables to store form data and error messages
$registrarId = $registrarFirstName = $registrarLastName = $registrarEmail = $registrarUsername = "";
$registrarIdErr = $registrarFirstNameErr = $registrarLastNameErr = $registrarEmailErr = $registrarUsernameErr = $registrarPasswordErr = "";
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

// Handle Edit/Update Course form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_course'])) {
    // Retrieve course ID from the form
    $courseId = test_input($_POST["course_id"]);
    error_log("Course ID to edit: " . $courseId);

    // Fetch course information from the database
    $stmt = $conn->prepare("SELECT id, course_code, course_name, credits FROM courses WHERE id = ?");
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("i", $courseId);
    if (!$stmt->execute()) {
        error_log("Execute failed: " . $stmt->error);
    }
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Course found, populate the input fields
        $row = $result->fetch_assoc();
        $courseId = $row['id'];
        $courseCode = $row['course_code'];
        $courseName = $row['course_name'];
        $credits = $row['credits'];
        error_log("Course details fetched: " . json_encode($row));
    } else {
        // Course not found
        $errorMessage = "Course not found";
        error_log($errorMessage);
    }

    $stmt->close();
}

// Handle Update Course form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_course'])) {
    // Retrieve and validate form data
    $courseId = test_input($_POST["course_id"]);
    $courseCode = test_input($_POST["course_code"]);
    $courseName = test_input($_POST["course_name"]);
    $credits = test_input($_POST["credits"]);
    error_log("Updating course ID: " . $courseId . " with data - Code: " . $courseCode . ", Name: " . $courseName . ", Credits: " . $credits);

    // Validate credits
    if (!is_numeric($credits) || $credits < 0 || $credits > 100) {
        $errorMessage = "Credits must be a number between 0 and 100";
    } else {
        // Update course information in the database
        $stmt = $conn->prepare("UPDATE courses SET course_code = ?, course_name = ?, credits = ? WHERE id = ?");
        if (!$stmt) {
            error_log("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("ssii", $courseCode, $courseName, $credits, $courseId);
        if ($stmt->execute()) {
            // Course information updated successfully
            $successMessage = "Course information updated successfully";
            error_log($successMessage);
        } else {
            // Error updating course information
            $errorMessage = "Error updating course information: " . $stmt->error;
            error_log($errorMessage);
        }
        $stmt->close();
    }

    // Set session variable to display success/error message
    $_SESSION['update_status'] = isset($successMessage) ? $successMessage : (isset($errorMessage) ? $errorMessage : "An unknown error occurred.");
    header("Location: " . $_SERVER['PHP_SELF']); // Redirect to refresh the page
    exit();
}

// Handle Search Course form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_course'])) {
    // Retrieve course ID from the form
    $deleteCourseId = test_input($_POST["delete_course_id"]);

    // Fetch course information from the database
    $stmt = $conn->prepare("SELECT id, course_code, course_name FROM courses WHERE id = ?");
    $stmt->bind_param("i", $deleteCourseId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Course found, store the information
        $courseInfo = $result->fetch_assoc();
    } else {
        // Course not found
        $errorMessage = "Course not found";
    }

    $stmt->close();
}

// Handle Delete Course form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_course'])) {
    // Retrieve course ID from the form
    $deleteCourseId = test_input($_POST["delete_course_id"]);

    // Delete the course from the database
    $stmt = $conn->prepare("DELETE FROM courses WHERE id = ?");
    $stmt->bind_param("i", $deleteCourseId);
    if ($stmt->execute()) {
        // Course deleted successfully
        $successMessage = "Course deleted successfully";
    } else {
        // Error deleting course
        $errorMessage = "Error deleting course: " . $conn->error;
    }
    $stmt->close();

    // Set session variable to display success/error message
    $_SESSION['update_status'] = isset($successMessage) ? $successMessage : (isset($errorMessage) ? $errorMessage : "An unknown error occurred.");
    header("Location: " . $_SERVER['PHP_SELF']); // Redirect to refresh the page
    exit();
}

// Handle Add Registrar form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_registrar'])) {
    // Retrieve and sanitize form data
    $registrarFirstName = test_input($_POST["registrar_first_name"]);
    $registrarLastName = test_input($_POST["registrar_last_name"]);
    $registrarEmail = test_input($_POST["registrar_email"]);
    $registrarUsername = test_input($_POST["registrar_username"]);
    $registrarPassword = password_hash($_POST["registrar_password"], PASSWORD_DEFAULT); // Hash the password

    // Start transaction
    $conn->begin_transaction();

    try {
        // Insert into users table
        $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'registrar')");
        $stmt->bind_param("ss", $registrarUsername, $registrarPassword);
        if (!$stmt->execute()) {
            throw new Exception("Error adding user: " . $stmt->error);
        }

        // Get the inserted user ID
        $userId = $conn->insert_id;

        // Insert into registrars table
        $stmt = $conn->prepare("INSERT INTO registrars (user_id, first_name, last_name, email) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $userId, $registrarFirstName, $registrarLastName, $registrarEmail);
        if (!$stmt->execute()) {
            throw new Exception("Error adding registrar: " . $stmt->error);
        }

        // Commit transaction
        $conn->commit();
        $successMessage = "Registrar added successfully";
    } catch (Exception $e) {
        // Rollback transaction
        $conn->rollback();
        $errorMessage = $e->getMessage();
    }

    // Close statement
    $stmt->close();

    // Set session variable to display success/error message
    $_SESSION['update_status'] = isset($successMessage) ? $successMessage : (isset($errorMessage) ? $errorMessage : "An unknown error occurred.");
    header("Location: " . $_SERVER['PHP_SELF']); // Redirect to refresh the page
    exit();
}

// Handle Search Registrar form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_registrar'])) {
    // Retrieve registrar ID from the form
    $registrarId = test_input($_POST["registrar_id"]);

    // Fetch registrar information from the database
    $stmt = $conn->prepare("SELECT users.id, users.username, registrars.first_name, registrars.last_name, registrars.email 
                            FROM users 
                            JOIN registrars ON users.id = registrars.user_id 
                            WHERE users.id = ? AND users.role = 'registrar'");
    $stmt->bind_param("i", $registrarId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Registrar found, populate the input fields
        $row = $result->fetch_assoc();
        $registrarId = $row['id'];
        $registrarFirstName = $row['first_name'];
        $registrarLastName = $row['last_name'];
        $registrarEmail = $row['email'];
        $registrarUsername = $row['username'];
    } else {
        // Registrar not found
        $errorMessage = "Registrar not found";
    }

    $stmt->close();
}

// Handle Update Registrar form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_registrar'])) {
    // Retrieve and validate form data
    $registrarId = test_input($_POST["registrar_id"]);
    $registrarFirstName = test_input($_POST["registrar_first_name"]);
    $registrarLastName = test_input($_POST["registrar_last_name"]);
    $registrarEmail = test_input($_POST["registrar_email"]);
    $registrarUsername = test_input($_POST["registrar_username"]);
    $registrarPassword = !empty($_POST["registrar_password"]) ? password_hash($_POST["registrar_password"], PASSWORD_DEFAULT) : "";

    // Validate inputs
    if (empty($registrarFirstName)) $registrarFirstNameErr = "First name is required";
    if (empty($registrarLastName)) $registrarLastNameErr = "Last name is required";
    if (empty($registrarEmail) || !filter_var($registrarEmail, FILTER_VALIDATE_EMAIL)) $registrarEmailErr = "Valid email is required";
    if (empty($registrarUsername)) $registrarUsernameErr = "Username is required";

    if (empty($registrarFirstNameErr) && empty($registrarLastNameErr) && empty($registrarEmailErr) && empty($registrarUsernameErr)) {
        // Update registrar information in the database
        if (!empty($registrarPassword)) {
            // Update password if provided
            $stmt = $conn->prepare("UPDATE users SET username = ?, password = ? WHERE id = ?");
            $stmt->bind_param("ssi", $registrarUsername, $registrarPassword, $registrarId);
        } else {
            $stmt = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
            $stmt->bind_param("si", $registrarUsername, $registrarId);
        }
        if ($stmt->execute()) {
            // Update registrar table
            $stmt = $conn->prepare("UPDATE registrars SET first_name = ?, last_name = ?, email = ? WHERE user_id = ?");
            $stmt->bind_param("sssi", $registrarFirstName, $registrarLastName, $registrarEmail, $registrarId);
            if ($stmt->execute()) {
                // Registrar information updated successfully
                $successMessage = "Registrar information updated successfully";
            } else {
                // Error updating registrar information
                $errorMessage = "Error updating registrar information: " . $stmt->error;
            }
        } else {
            // Error updating user information
            $errorMessage = "Error updating user information: " . $stmt->error;
        }
        $stmt->close();
    }

    // Set session variable to display success/error message
    $_SESSION['update_status'] = $successMessage ? $successMessage : $errorMessage;
    header("Location: " . $_SERVER['PHP_SELF']); // Redirect to refresh the page
    exit();
}

// Handle Search Registrar form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_registrar'])) {
    // Retrieve registrar ID from the form
    $deleteRegistrarId = test_input($_POST["delete_registrar_id"]);

    // Fetch registrar information from the database
    $stmt = $conn->prepare("SELECT id, username, role FROM users WHERE id = ? AND role = 'registrar'");
    $stmt->bind_param("i", $deleteRegistrarId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Registrar found, store the information
        $registrarInfo = $result->fetch_assoc();
    } else {
        // Registrar not found
        $errorMessage = "Registrar not found";
    }

    $stmt->close();
}

// Handle Delete Registrar button click
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_registrar'])) {
    // Retrieve registrar ID from the form
    $deleteRegistrarId = test_input($_POST["delete_registrar_id"]);

    // Delete the registrar (user with role 'registrar') from the database
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND role = 'registrar'");
    $stmt->bind_param("i", $deleteRegistrarId);
    if ($stmt->execute()) {
        // Registrar deleted successfully
        $successMessage = "Registrar deleted successfully";
    } else {
        // Error deleting registrar
        $errorMessage = "Error deleting registrar: " . $conn->error;
    }
    $stmt->close();

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
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <input class="input" type="text" name="course_id" placeholder="Enter Course ID">
                        <div class="buttons">
                            <button type="submit" name="edit_course">Search Course</button>
                        </div>
                        <?php if (isset($courseCode)): ?>
                        <p class="body-text">Course Code</p>
                        <input type="text" class="input" name="course_code" value="<?php echo $courseCode; ?>">
                        <p class="body-text">Course Name</p>
                        <input type="text" class="input" name="course_name" value="<?php echo $courseName; ?>">
                        <p class="body-text">Course Credits</p>
                        <input type="number" class="input" name="credits" value="<?php echo $credits; ?>" min="0" max="100">
                        <!-- Hidden field for course ID -->
                        <input type="hidden" name="course_id" value="<?php echo $courseId; ?>">
                        <div class="buttons">
                            <button type="submit" name="update_course">Update Course</button>
                        </div>
                        <?php endif; ?>
                    </form>
                </div>
                <div class="content content6">
                    <p class="bold">Delete Course</p>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <p class="body-text">Search by Course Code</p>
                        <input class="input" type="text" name="delete_course_id" placeholder="Enter Course Code">
                        <div class="buttons">
                            <button type="submit" name="search_course">Search Course</button>
                        </div>
                    </form>
                    <?php if(isset($courseInfo)): ?>
                    <p class="bold">Course Code</p>
                    <p class="body-text"><?php echo $courseInfo['course_code']; ?></p>
                    <p class="bold">Course Name</p>
                    <p class="body-text"><?php echo $courseInfo['course_name']; ?></p>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <input type="hidden" name="delete_course_id" value="<?php echo $courseInfo['id']; ?>">
                        <div class="buttons">
                            <button type="submit" name="delete_course">Delete Course</button>
                        </div>
                    </form>
                    <?php endif; ?>
                </div>

                <div class="content content7">
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <p class="bold">Add Registrar</p>
                        <p class="body-text">First Name</p>
                        <input type="text" class="input" name="registrar_first_name" required>
                        <p class="body-text">Last Name</p>
                        <input type="text" class="input" name="registrar_last_name" required>
                        <p class="body-text">Email</p>
                        <input type="email" class="input" name="registrar_email" required>
                        <p class="body-text">Account Username</p>
                        <input type="text" class="input" name="registrar_username" required>
                        <p class="body-text">Account Password</p>
                        <input type="password" class="input" name="registrar_password" required>
                        <div class="buttons">
                            <button type="submit" name="add_registrar">Add Registrar</button>
                        </div>
                    </form>
                </div>
                <div class="content content8">
                    <p class="bold">Edit/Update Registrar</p>
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <p class="body-text">Search by Registrar ID</p>
                        <input class="input" type="text" name="registrar_id" value="<?php echo $registrarId; ?>" placeholder="Enter Registrar ID">
                        <div class="buttons">
                            <button type="submit" name="search_registrar">Search Registrar</button>
                        </div>
                    </form>
                    
                    <?php if (!empty($registrarId)): ?>
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <input type="hidden" name="registrar_id" value="<?php echo $registrarId; ?>">
                            <p class="body-text">First Name</p>
                            <input type="text" name="registrar_first_name" class="input" value="<?php echo $registrarFirstName; ?>">
                            <p class="body-text">Last Name</p>
                            <input type="text" name="registrar_last_name" class="input" value="<?php echo $registrarLastName; ?>">
                            <p class="body-text">Email</p>
                            <input type="text" name="registrar_email" class="input" value="<?php echo $registrarEmail; ?>">
                            <p class="body-text">Username</p>
                            <input type="text" name="registrar_username" class="input" value="<?php echo $registrarUsername; ?>">
                            <p class="body-text">Password (leave blank to keep current password)</p>
                            <input type="password" name="registrar_password" class="input">
                            <div class="buttons">
                                <button type="submit" name="update_registrar">Update Registrar</button>
                            </div>
                        </form>
                    <?php endif; ?>

                    <?php if (!empty($successMessage)): ?>
                        <div class="success-message"><?php echo $successMessage; ?></div>
                    <?php endif; ?>
                    <?php if (!empty($errorMessage)): ?>
                        <div class="error-message"><?php echo $errorMessage; ?></div>
                    <?php endif; ?>
                </div>
                <div class="content content9">
                    <p class="bold">Delete Registrar</p>
                    <p class="body-text">Search by User ID</p>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <input class="input" type="text" name="delete_registrar_id" placeholder="Enter Registrar ID">
                        <div class="buttons">
                            <button type="submit" name="search_registrar">Search Registrar</button>
                        </div>
                    </form>
                    <?php if (isset($registrarInfo)): ?>
                        <p class="bold">Username:</p>
                        <p class="body-text"><?php echo htmlspecialchars($registrarInfo['username']); ?></p>
                        <p class="bold">Role:</p>
                        <p class="body-text"><?php echo htmlspecialchars($registrarInfo['role']); ?></p>
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                            <input type="hidden" name="delete_registrar_id" value="<?php echo $registrarInfo['id']; ?>">
                            <div class="buttons">
                                <button type="submit" name="delete_registrar">Delete Registrar</button>
                            </div>
                        </form>
                    <?php elseif (isset($errorMessage)): ?>
                        <p class="error"><?php echo $errorMessage; ?></p>
                    <?php endif; ?>
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