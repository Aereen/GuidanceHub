<?php
session_start();
include('server.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

// Database Connection
try {
    $pdo = new PDO("mysql:host=localhost;dbname=guidancehub", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Function to generate unique Ticket ID
function generateTicketID($pdo) {
    $dateCode = date("Ym"); // Year + Month (e.g., 202403)
    $query = "SELECT COUNT(*) AS total FROM assessments WHERE DATE_FORMAT(submission_date, '%Y%m') = :dateCode";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':dateCode' => $dateCode]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $count = $row['total'] + 1;
    
    return "AS-" . $dateCode . "-" . str_pad($count, 3, '0', STR_PAD_LEFT);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Generate Ticket ID
        $ticket_id = generateTicketID($pdo);

        // Insert data into assessments table
        $sql = "INSERT INTO assessments (ticket_id, student_name, student_email, test_type, schedule_date, schedule_time)
                VALUES (:ticket_id, :student_name, :student_email, :test_type, :schedule_date, :schedule_time)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':ticket_id' => $ticket_id,
            ':student_name' => $_POST['student_name'],
            ':student_email' => $_POST['student_email'],
            ':test_type' => $_POST['test_type'],
            ':schedule_date' => $_POST['schedule_date'],
            ':schedule_time' => $_POST['schedule_time'],
        ]);

        // Send confirmation email
        $email = $_POST['student_email'];
        $name = $_POST['student_name'];
        $testType = $_POST['test_type'];
        $scheduleDate = $_POST['schedule_date'];
        $scheduleTime = $_POST['schedule_time'];

        if (!empty($email)) {
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'guidancehub01@gmail.com';
                $mail->Password   = 'mkqn ecje evor lgdj'; // Use an app password, not actual password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = 465;

                $mail->setFrom('guidancehub01@gmail.com', 'GuidanceHub');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = "Assessment Schedule Confirmation";
                $mail->Body    = "
                    <h2>Assessment Scheduled Successfully</h2>
                    <p>Dear <b>$name</b>,</p>
                    <p>Your assessment has been successfully scheduled. Below are your details:</p>
                    <ul>
                        <li><strong>Ticket ID:</strong> $ticket_id</li>
                        <li><strong>Name:</strong> $name</li>
                        <li><strong>Test Type:</strong> $testType</li>
                        <li><strong>Schedule Date:</strong> $scheduleDate</li>
                        <li><strong>Schedule Time:</strong> $scheduleTime</li>
                    </ul>
                    <p>Thank you for scheduling your assessment.</p>
                    <p>See us at 8th Floor, Health and Physical Science Building.</p>
                    <p>Best regards,<br><b>Guidance Office</b></p>
                ";

                $mail->send();
                echo "<script>alert('Assessment scheduled! Confirmation email sent.'); window.location.href='/src/ControlledData/assessment.php';</script>";
            } catch (Exception $e) {
                echo "<script>alert('Assessment scheduled, but email could not be sent. Error: " . addslashes($mail->ErrorInfo) . "'); window.location.href='/src/ControlledData/assessment.php';</script>";
            }
        } else {
            echo "<script>alert('Assessment scheduled, but no email was provided.'); window.location.href='/src/ControlledData/assessment.php';</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Error scheduling assessment: " . addslashes($e->getMessage()) . "'); window.location.href='/src/ControlledData/assessment.php';</script>";
    }
}
?>

<!doctype html>
<html>
<head>
<title> GuidanceHub </title>
    <link rel="icon" type="images/x-icon" href="/src/images/UMAK-CGCS-logo.png" />
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css"  rel="stylesheet" />
        <script src="https://kit.fontawesome.com/95c10202b4.js" crossorigin="anonymous"></script>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="./output.css" rel="stylesheet">   
</head>
<body class="bg-gray-100">

<!--HEADER-->
<header class="fixed top-0 left-0 z-50 w-full py-4 shadow-xl" style="background-color: #1EB0A9">
    <div class="flex items-center justify-between px-4 mx-auto container-fluid md:px-8">
        <!-- Logo -->
        <div class="flex items-center space-x-3">
            <img src="/src/images/UMAK-logo.png" alt="UMAK Logo" class="w-10 h-auto mx-5 md:w-14">
            <span class="font-semibold tracking-wide text-white md:text-2xl">University of Makati</span>
        </div>

        <!-- Hamburger Icon -->
        <button id="menu-toggle" class="block md:hidden">
            <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
        </button>

        <!-- Navigation Menu -->
        <nav id="menu" class="hidden md:flex">
            <ul class="flex flex-col space-y-2 text-lg font-semibold text-white md:flex-row md:space-x-10 md:space-y-0">
                <li><a href="/index.php" class="hover:text-cyan-950">Home</a></li>
                <li><a href="/src/ControlledData/appointment.php" class="hover:text-cyan-950">Appointment</a></li>
                <li><a href="/src/ControlledData/referral.php" class="hover:text-cyan-950">Referral</a></li>
                <li><a href="#about" class="hover:text-cyan-950">About</a></li>  
                <li>
                    <a href="/src/ControlledData/login.php" 
                    class="px-4 py-2 text-white rounded-md bg-cyan-800 hover:bg-cyan-950">Login</a>
                </li>
            </ul>
        </nav>
    </div>
</header>

<!--CONTENT-->
<main class="mt-28">
<h2 class="mb-6 text-2xl font-bold text-center">Assessment Scheduling</h2>
    <div class="max-w-4xl mx-auto my-5 mt-6">

        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-lg">
            <form action="assessment.php" method="POST">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label class="block font-medium text-gray-700 text-md">Name (First Name-MI-Last Name)</label>
                        <input type="text" name="student_name" required class="w-full p-2 mb-3 border rounded">
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 text-md">UMak Email Address</label>
                        <input type="text" name="student_email" required class="w-full p-2 mb-3 border rounded">
                    </div>
                </div>
                <label class="block font-medium text-gray-700 text-md">Select Test</label>
                <select name="test_type" required class="w-full p-2 mb-3 border rounded">
                    <option value="" disabled selected>Select Test</option>
                    <option value="Personality">Personality</option>
                    <option value="Traits">Traits</option>
                    <option value="Intelligence">Intelligence</option>
                    <option value="Emotional">Emotional</option>
                    <option value="Aptitude">Aptitude</option>
                    <option value="Career">Career</option>
                    <option value="Behavioral">Behavioral</option>
                </select>

                <label class="block font-medium text-gray-700 text-md">Schedule Date</label>
                <input type="date" name="schedule_date" required class="w-full p-2 mb-3 border rounded">

                <label class="block font-medium text-gray-700 text-md">Schedule Time</label>
                <input type="time" name="schedule_time" required class="w-full p-2 mb-4 border rounded">

                <button type="submit" class="w-full p-2 text-white bg-blue-500 rounded">Schedule</button>
            </form>
        </div>
    </div>
</main>

<!--FOOTER-->
<footer class="w-full" style="background-color: #1EB0A9">
    <div class="w-full max-w-screen-xl p-4 py-6 mx-auto lg:py-8 dark:text-gray-800">
        <div class="md:flex md:justify-between">
            <div class="mb-6 md:mb-0">
                <a href="https://flowbite.com/" class="flex items-center">
                    <img src="/src/images/UMAK-CGCS-logo.png" class="h-8 me-3" alt="GuidanceHub Logo" />
                    <span class="font-bold tracking-wide text-white md:text-2xl">GuidanceHub</span>
                </a>
            </div>
            <div class="grid grid-cols-2 gap-8 sm:gap-6 sm:grid-cols-3">
                <div>
                    <h2 class="mb-6 text-sm font-semibold uppercase">Resources</h2>
                    <ul class="font-medium">
                        <li class="mb-4">
                            <a href="https://flowbite.com/" class="hover:underline">GuidanceHub</a>
                        </li>
                        <li>
                            <a href="https://tailwindcss.com/" class="hover:underline">Tailwind CSS</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <h2 class="mb-6 text-sm font-semibold uppercase">Follow us</h2>
                    <ul class="font-medium">
                        <li class="mb-4">
                            <a href="https://github.com/themesberg/flowbite" class="hover:underline">Github</a>
                        </li>
                        <li>
                            <a href="https://discord.gg/4eeurUVvTy" class="hover:underline">Discord</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <h2 class="mb-6 text-sm font-semibold uppercase">Legal</h2>
                    <ul class="font-medium">
                        <li class="mb-4">
                            <a href="#" class="hover:underline">Privacy Policy</a>
                        </li>
                        <li>
                            <a href="#" class="hover:underline">Terms &amp; Conditions</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="sm:flex sm:items-center sm:justify-between">
            <span class="text-sm sm:text-center">© 2025 Group 8 | IV-AINS. All Rights Reserved.
            </span>
        </div>
    </div>
</footer>

<script>
function openTab(event, tabId) {
        // Hide all tabs
        document.querySelectorAll(".tab-content").forEach(tab => {
            tab.classList.add("hidden");
        });

        // Remove active class from all buttons
        document.querySelectorAll("#tabs button").forEach(button => {
            button.classList.remove("bg-gray-300");
            button.classList.add("bg-gray-200");
        });

        // Show the selected tab
        document.getElementById(tabId).classList.remove("hidden");

        // Highlight the active button
        event.currentTarget.classList.add("bg-gray-300");
        event.currentTarget.classList.remove("bg-gray-200");
    }
</script>
<script src="../path/to/flowbite/dist/flowbite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</body>
</html>