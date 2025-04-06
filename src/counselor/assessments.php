<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "u406807013_guidancehub";
$password = "GuidanceHub2025";
$dbname = "u406807013_guidancehub";

// Create connection
$con = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Fetch assessments from the database
$sql = "SELECT ticket_id, student_name, test_type, schedule_date, schedule_time FROM assessments";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessments</title>
    <link rel="stylesheet" href="path/to/your/css/file.css"> <!-- Add your CSS file path here -->
</head>
<body>
    <h2>Assessments</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Ticket ID</th>
                <th>Student Name</th>
                <th>Test Type</th>
                <th>Schedule Date</th>
                <th>Schedule Time</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['ticket_id']}</td>
                            <td>{$row['student_name']}</td>
                            <td>{$row['test_type']}</td>
                            <td>{$row['schedule_date']}</td>
                            <td>{$row['schedule_time']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No assessments found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>

<?php
// Close the connection at the end of the script
$con->close();
?>