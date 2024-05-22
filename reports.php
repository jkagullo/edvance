<?php
    include "db.php";

    if(isset($_POST['report'])){
        $selected = $_POST['report'];
    } else {
        $selected = "Student"; 
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
    <link rel="stylesheet" href="main.css">
    <title>edvance</title>

</head>
<body>
    <div class="header">
        <div class="card1">
            <p> Student Management System </p>
        </div>
        <div class="card2">
            <a href="index.php"><button class="forms">
                <p> Forms </p>
            </button></a>
            <a href="reports.php"><button class="reports <?php echo $selected === 'Courses' || $selected === 'Student' || $selected === 'Enrollment' || $selected === 'Top Students' ? 'selected' : ''; ?>">
                <p> Reports </p>
            </button></a>
        </div>   
    </div>

    <div class="content">
        <div class="choose">
            <p> Choose a report </p>
            <form method="post">
                <select name="report" onchange="this.form.submit()">
                    <?php
                        $options = array('Student', 'Courses', 'Enrollment', 'Top Students');
                        foreach($options as $option){
                            if($selected == $option){
                                echo "<option selected='selected' value='$option'>$option</option>";
                            } else {
                                echo "<option value='$option'>$option</option>";
                            }
                        }
                    ?>
                </select>
            </form>
        </div>

        <div class="db">
            <?php
                if($selected == "Student") {
            ?>
            <table>
                <thead>
                    <tr>
                        <th>StudentID</th>
                        <th>FirstName</th>
                        <th>LastName</th>
                        <th>Age</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $sql = "SELECT * FROM student";
                        if($result = mysqli_query($conn, $sql)){
                            if(mysqli_num_rows($result) > 0){
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                    echo "<td>" . $row['StudentID'] . "</td>";
                                    echo "<td>" . $row['FirstName'] . "</td>";
                                    echo "<td>" . $row['LastName'] . "</td>";
                                    echo "<td>" . $row['Age'] . "</td>";
                                    echo "</tr>";
                                }
                            }
                        }
                    ?>
                </tbody>
            </table>
            <?php
                } elseif($selected == "Courses") {
            ?>
            <table>
                <thead>
                    <tr>
                        <th>CourseID</th>
                        <th>CourseName</th>
                        <th>Credits</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $sql = "SELECT * FROM course"; // Changed from "courses" to "course"
                        if($result = mysqli_query($conn, $sql)){
                            if(mysqli_num_rows($result) > 0){
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                    echo "<td>" . $row['CourseID'] . "</td>";
                                    echo "<td>" . $row['CourseName'] . "</td>";
                                    echo "<td>" . $row['Credits'] . "</td>";
                                    echo "</tr>";
                                }
                            }
                        }
                    ?>
                </tbody>
            </table>
            <?php
                } elseif($selected == "Enrollment") {
            ?>
            <table>
                <thead>
                    <tr>
                        <th>EnrollmentID</th>
                        <th>StudentID</th>
                        <th>CourseID</th>
                        <th>Grade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $sql = "SELECT * FROM enrollment"; // Assuming "enrollment" is the name of your table
                        if($result = mysqli_query($conn, $sql)){
                            if(mysqli_num_rows($result) > 0){
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                    echo "<td>" . $row['EnrollmentID'] . "</td>";
                                    echo "<td>" . $row['StudentID'] . "</td>";
                                    echo "<td>" . $row['CourseID'] . "</td>";
                                    echo "<td>" . $row['Grade'] . "</td>";
                                    echo "</tr>";
                                }
                            }
                        }
                    ?>
                </tbody>
            </table>
            <?php
                } elseif($selected == "Top Students") {
            ?>
            <table>
                <thead>
                    <tr>
                        <th>StudentID</th>
                        <th>FirstName</th>
                        <th>LastName</th>
                        <th>CourseName</th>
                        <th>Grade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $sql = "SELECT enrollment.StudentID, student.FirstName, student.LastName, course.CourseName, enrollment.Grade
                                FROM enrollment
                                JOIN course ON enrollment.CourseID = course.CourseID
                                JOIN student ON enrollment.StudentID = student.StudentID
                                WHERE enrollment.Grade = 'A'";
                        if($result = mysqli_query($conn, $sql)){
                            if(mysqli_num_rows($result) > 0){
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                    echo "<td>" . $row['StudentID'] . "</td>";
                                    echo "<td>" . $row['FirstName'] . "</td>";
                                    echo "<td>" . $row['LastName'] . "</td>";
                                    echo "<td>" . $row['CourseName'] . "</td>";
                                    echo "<td>" . $row['Grade'] . "</td>";
                                    echo "</tr>";
                                }
                            }
                        }
                    ?>
                </tbody>
            </table>
            <?php
                }
            ?>
        </div>
    </div>
</body>
</html>
