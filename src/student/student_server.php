<?php
// Connect to the database
$con = mysqli_connect('localhost', 'root', '', 'guidancehub');

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize variables and error array
$errors = array(); 

// Handle form submission for appointment scheduling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and assign POST values to variables
    $fullName = mysqli_real_escape_string($con, $_POST['full-name']);
    $studentNumber = mysqli_real_escape_string($con, $_POST['student_number']);
    $contactNumber = mysqli_real_escape_string($con, $_POST['contact']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $college = mysqli_real_escape_string($con, $_POST['college']);
    $course = mysqli_real_escape_string($con, $_POST['course']);
    $yearLevel = mysqli_real_escape_string($con, $_POST['year-level']);
    $section = mysqli_real_escape_string($con, $_POST['section']);
    $appointmentType = mysqli_real_escape_string($con, $_POST['appointment-type']);
    $appointmentDate = mysqli_real_escape_string($con, $_POST['appointment-date']);
    $appointmentTime = mysqli_real_escape_string($con, $_POST['appointment-time']);

    // Validate form fields to ensure no empty values
    if (empty($fullName)) { array_push($errors, "Full Name is required"); }
    if (empty($studentNumber)) { array_push($errors, "Student Number is required"); }
    if (empty($contactNumber)) { array_push($errors, "Contact Number is required"); }
    if (empty($email)) { array_push($errors, "Email is required"); }
    if (empty($college)) { array_push($errors, "College is required"); }
    if (empty($course)) { array_push($errors, "Course is required"); }
    if (empty($yearLevel)) { array_push($errors, "Year Level is required"); }
    if (empty($section)) { array_push($errors, "Section is required"); }
    if (empty($appointmentType)) { array_push($errors, "Appointment Type is required"); }
    if (empty($appointmentDate)) { array_push($errors, "Appointment Date is required"); }
    if (empty($appointmentTime)) { array_push($errors, "Appointment Time is required"); }

    // If no errors, proceed to insert the data into the database
    if (count($errors) == 0) {
        $sql = "INSERT INTO appointments (full_name, student_number, contact_number, email, college, course, year_level, section, appointment_type, appointment_date, appointment_time)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sssssssssss", $fullName, $studentNumber, $contactNumber, $email, $college, $course, $yearLevel, $section, $appointmentType, $appointmentDate, $appointmentTime);

        // If the appointment is successfully scheduled
        if ($stmt->execute()) {
            echo "<script>alert('Appointment successfully scheduled!'); window.location.href = '/src/student/appointment.php';</script>";
        } else {
            // If there's an error with the query
            echo "<script>alert('Error scheduling appointment: {$stmt->error}'); window.location.href = '/src/student/appointment.php';</script>";
        }

        $stmt->close();
    } else {
        // Handle form validation errors
        foreach ($errors as $error) {
            echo "<script>alert('$error');</script>";
        }
    }
}
?>
