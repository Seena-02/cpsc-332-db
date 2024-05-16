<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Interface</title>
</head>
<body>
    <header>
        <h1>Student Interface</h1>
    </header>
    <form method="post">
        Enter Course Number to view sections: <input type="text" name="course_num">
        <input type="submit" name="submit" value="View Sections">
    </form>
    <form method="post">
        Enter your CWID to view your courses and grades: <input type="text" name="cwid">
        <input type="submit" name="submit" value="View Courses">
    </form>

    <?php
    include 'database.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!empty($_POST['course_num']) && isset($_POST['submit']) && $_POST['submit'] == "View Sections") {
            $course_num = $_POST['course_num'];
            $query = "SELECT s.classroom, m.meeting_day, s.beginning_time, s.ending_time FROM course c
                      JOIN section s ON c.course_id = s.course_id
                      JOIN meeting_days m ON s.professor_ssn = m.professor_ssn AND s.course_id = m.course_id AND s.section_number = m.section_number
                      WHERE c.course_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $course_num);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "Classroom: " . $row["classroom"] . ", Day: " . $row["meeting_day"] . ", Time: " . $row["beginning_time"] . " to " . $row["ending_time"] . "<br>";
                }
            } else {
                echo "No sections found.";
            }
        } elseif (!empty($_POST['cwid']) && isset($_POST['submit']) && $_POST['submit'] == "View Courses") {
            $cwid = $_POST['cwid'];
            $query = "SELECT c.title, er.grade FROM enrollment_records er
                      JOIN course c ON er.course_id = c.course_id
                      WHERE er.cwid = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $cwid);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "Course: " . $row["title"] . ", Grade: " . $row["grade"] . "<br>";
                }
            } else {
                echo "No course data found.";
            }
        }
    }
    ?>

</body>
</html>