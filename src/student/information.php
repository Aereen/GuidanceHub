<?php
session_start();
include('E:/GuidanceHub/src/ControlledData/server.php');

$host = 'localhost';
$dbname = 'guidancehub';
$username = 'root';
$password = '';

// Check if the user is logged in
if (!isset($_SESSION['id_number'])) {
    header("Location: /src/ControlledData/login.php"); //if not logged in
    exit;
}

// Create PDO connection first
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare("INSERT INTO student_profile (name, student_number, birth_date, age, gender, civil_status, nationality, religion, contact_number, email, home_address, current_address, college, program, year_level, section, student_status, scholarship, academic_standing, parent_name, parent_relationship, parent_contact, parent_address, parent_occupation, emergency_contact_name, emergency_contact_number, medical_conditions, disabilities, medications, mental_health_history, personal_physician, stress_experience, coping_mechanism, past_counseling, support_system, comfort_seeking_help, counseling_type, preferred_mode, preferred_counselor, availability, hobbies, organizations, leadership_roles, volunteer_work, consent, agreement) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

    // Check each POST value to ensure it's set and provide a default value if not
    $stmt->execute([
        $_POST['name'],
        $_POST['student_number'],
        $_POST['birth_date'],
        $_POST['age'],
        $_POST['gender'],
        $_POST['civil_status'] ?? '',
        $_POST['nationality'] ?? '',
        $_POST['religion'] ?? '',
        $_POST['contact_number'] ?? '',
        $_POST['email'],
        $_POST['home_address'] ?? '',
        $_POST['current_address'] ?? '',
        $_POST['college'] ?? '',
        $_POST['program'] ?? '',
        $_POST['year_level'] ?? '',
        $_POST['section'] ?? '',
        $_POST['student_status'] ?? '',
        $_POST['scholarship'] ?? '',
        $_POST['academic_standing'] ?? '',
        $_POST['parent_name'] ?? '',
        $_POST['parent_relationship'] ?? '',
        $_POST['parent_contact'] ?? '',
        $_POST['parent_address'] ?? '',
        $_POST['parent_occupation'] ?? '',
        $_POST['emergency_contact_name'] ?? '',
        $_POST['emergency_contact_number'] ?? '',
        $_POST['medical_conditions'] ?? '',
        $_POST['disabilities'] ?? '',
        $_POST['medications'] ?? '',
        $_POST['mental_health_history'] ?? '',
        $_POST['personal_physician'] ?? '',
        $_POST['stress_experience'] ?? '',
        $_POST['coping_mechanism'] ?? '',
        $_POST['past_counseling'] ?? '',
        $_POST['support_system'] ?? '',
        $_POST['comfort_seeking_help'] ?? '',
        implode(',', $_POST['counseling_type'] ?? []),
        $_POST['preferred_mode'] ?? '',
        $_POST['preferred_counselor'] ?? '',
        $_POST['availability'] ?? '',
        $_POST['hobbies'] ?? '',
        $_POST['leadership_roles'] ?? '',
        $_POST['volunteer_work'] ?? '',
        $_POST['consent'] ?? '',
        $_POST['agreement'] ?? ''
    ]);

    header("Location: /src/student/dashboard.php");
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>GuidanceHub | Student Inventory</title>
    <link rel="icon" type="images/x-icon" href="/src/images/UMAK-CGCS-logo.png" />
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/95c10202b4.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="./output.css" rel="stylesheet">
</head>

<body class="p-6 bg-gray-100">
    <div class="max-w-4xl p-6 mx-auto bg-white rounded-lg shadow-md">
        <h2 class="mb-4 text-xl font-bold">Student Information Form</h2>
        <form method="POST" class="space-y-4">
            <input type="text" name="name" value="<?php echo htmlspecialchars($_SESSION['name']); ?>" placeholder="Full Name" class="w-full p-2 border rounded" required>
            <input type="text" name="student_number" value="<?php echo htmlspecialchars($_SESSION['id_number']); ?>" placeholder="Student ID" class="w-full p-2 border rounded" required>
            <input type="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" placeholder="Email" class="w-full p-2 border rounded" required>

            <input type="date" name="birth_date" class="w-full p-2 border rounded" required>
            <input type="number" name="age" placeholder="Age" class="w-full p-2 border rounded" required>
            <select name="gender" class="w-full p-2 border rounded" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
            <input type="text" name="civil_status" placeholder="Civil Status" class="w-full p-2 border rounded">
            <input type="text" name="nationality" placeholder="Nationality" class="w-full p-2 border rounded">
            <input type="text" name="religion" placeholder="Religion" class="w-full p-2 border rounded">
            <input type="text" name="contact_number" placeholder="Contact Number" class="w-full p-2 border rounded">
            <input type="text" name="home_address" placeholder="Home Address" class="w-full p-2 border rounded">
            <input type="text" name="current_address" placeholder="Current Address" class="w-full p-2 border rounded">
            <input type="text" name="college" placeholder="College" class="w-full p-2 border rounded">
            <input type="text" name="program" placeholder="Program" class="w-full p-2 border rounded">
            <input type="text" name="year_level" placeholder="Year Level" class="w-full p-2 border rounded">
            <input type="text" name="section" placeholder="Section" class="w-full p-2 border rounded">

            <h3 class="font-semibold">Counseling Type</h3>
            <label><input type="checkbox" name="counseling_type[]" value="Academic Counseling"> Academic Counseling</label>
            <label><input type="checkbox" name="counseling_type[]" value="Career Guidance"> Career Guidance</label>
            <label><input type="checkbox" name="counseling_type[]" value="Personal Counseling"> Personal Counseling</label>
            <label><input type="checkbox" name="counseling_type[]" value="Mental Health Support"> Mental Health Support</label>

            <input type="text" name="preferred_mode" placeholder="Preferred Counseling Mode" class="w-full p-2 border rounded">
            <input type="text" name="preferred_counselor" placeholder="Preferred Counselor" class="w-full p-2 border rounded">
            <input type="text" name="availability" placeholder="Availability" class="w-full p-2 border rounded">
            <input type="text" name="hobbies" placeholder="Hobbies" class="w-full p-2 border rounded">
            <input type="text" name="leadership_roles" placeholder="Leadership Roles" class="w-full p-2 border rounded">
            <input type="text" name="volunteer_work" placeholder="Volunteer Work" class="w-full p-2 border rounded">

            <label><input type="checkbox" name="consent" value="Yes" required> I give my consent for counseling.</label>
            <label><input type="checkbox" name="agreement" value="Yes" required> I agree to the terms and conditions.</label>

            <button type="submit" class="w-full py-2 text-white bg-blue-500 rounded">Submit</button>
        </form>
    </div>
</body>
</html>
