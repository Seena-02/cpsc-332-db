<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Professor Interface</title>
</head>
<body>
    <header>
        <h1>Professor Interface</h1>
    </header>
    <form method="post">
        Enter SSN to view your courses (xxx-xx-xxxx): <input type="text" name="ssn">
        <input type="submit" name="submit" value="View Courses">
    </form>
    <form method="post">
        Enter Course ID: <input type="text" name="course_id">
        Enter Section Number: <input type="text" name="section_number">
        <input type="submit" name="submit" value="View Grades">
    </form>

    <?php
    include 'database.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!empty($_POST['ssn']) && isset($_POST['submit']) && $_POST['submit'] == "View Courses") {
            $ssn = $_POST['ssn'];
            $query = "SELECT c.title, s.classroom, m.meeting_day, s.beginning_time, s.ending_time FROM professor p
                      JOIN section s ON p.professor_ssn = s.professor_ssn
                      JOIN course c ON s.course_id = c.course_id
                      JOIN meeting_days m ON s.professor_ssn = m.professor_ssn AND s.course_id = m.course_id AND s.section_number = m.section_number
                      WHERE p.professor_ssn = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $ssn);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "Course: " . $row["title"] . ", Classroom: " . $row["classroom"] . ", Day: " . $row["meeting_day"] . ", Time: " . $row["beginning_time"] . " to " . $row["ending_time"] . "<br>";
                }
            } else {
                echo "No courses found.";
            }
        } elseif (!empty($_POST['course_id']) && !empty($_POST['section_number']) && isset($_POST['submit']) && $_POST['submit'] == "View Grades") {
            $course_id = $_POST['course_id'];
            $section_number = $_POST['section_number'];
            $query = "SELECT grade, COUNT(*) as count FROM enrollment_records WHERE course_id = ? AND section_number = ? GROUP BY grade";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $course_id, $section_number);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "Grade " . $row["grade"] . ": " . $row["count"] . " students<br>";
                }
            } else {
                echo "No grade data found.";
            }
        }
    }
    ?>

</body>
</html>