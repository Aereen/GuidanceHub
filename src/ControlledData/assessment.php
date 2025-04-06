<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "guidancehub";

// Create connection
$con = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Require login
if (!isset($_SESSION['email'])) {
    header("Location: login.php?redirect=" . urlencode($_SERVER['REQUEST_URI']));
    exit();
}

// Function to generate unique Ticket ID
function generateTicketID($con) {
    $dateCode = date("Ym"); // Year + Month (e.g., 202402)
    $query = "SELECT COUNT(*) AS total FROM assessments WHERE DATE_FORMAT(created_at, '%Y%m') = '$dateCode'";
    $result = $con->query($query);

    if ($result) {
        $row = $result->fetch_assoc();
        $count = $row['total'] + 1;

        return "AS-" . $dateCode . "-" . str_pad($count, 3, '0', STR_PAD_LEFT);
    } else {
        // Handle query error
        die("Query failed: " . $con->error);
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Ensure all required fields are present
        $required_fields = ['student_name', 'test_type', 'schedule_date', 'schedule_time'];
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Missing required field: " . ucfirst(str_replace('_', ' ', $field)));
            }
        }

        // Generate Ticket ID
        $ticket_id = generateTicketID($con);

        // Insert data into assessments table
        $sql = "INSERT INTO assessments (ticket_id, student_name, test_type, schedule_date, schedule_time)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $con->prepare($sql);
        $stmt->bind_param(
            "sssss",
            $ticket_id,
            $_POST['student_name'],
            $_POST['test_type'],
            $_POST['schedule_date'],
            $_POST['schedule_time']
        );
        $stmt->execute();

        echo "<script>alert('Assessment scheduled successfully!'); window.location.href='/src/ControlledData/assessment.php';</script>";
    } catch (Exception $e) {
        echo "<script>alert('Error: " . addslashes($e->getMessage()) . "'); window.location.href='/src/ControlledData/assessment.php';</script>";
    }
}

// Close connection
$con->close();
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
        <link href="https://fonts.googleapis.com/css2?family=Marcellus&family=Montserrat:wght@500&display=swap" rel="stylesheet">
        
    <style>
        .marcellus-regular {
            font-family: "Marcellus", serif;
            font-style: normal;
            letter-spacing: 2px; }
        body::-webkit-scrollbar {
            width: 15px; }
        body::-webkit-scrollbar-track {
            background: #f1f1f1; }
        body::-webkit-scrollbar-thumb {
            background: #888; }
        body::-webkit-scrollbar-thumb:hover {
            background: #555; }
        .blue-1:hover {
            color: #111c4e;
        }
        .blue-2:hover {
            color: #618dc2;
        }

    </style>
</head>
<body class="bg-gray-100">

<!--HEADER-->
<header class="fixed top-0 left-0 z-50 w-full py-4 shadow-xl marcellus-regular" style="background-color: #111c4e">
    <div class="flex items-center justify-between px-4 mx-auto container-fluid md:px-8">
        <!-- Logo -->
        <div class="flex items-center space-x-3">
            <a href="https://www.umak.edu.ph/" class="flex items-center space-x-3">
                <img src="/src/images/UMAK-Logo.png" alt="UMAK Logo" class="w-10 h-auto md:w-14">
                <span class="font-semibold tracking-wide text-white md:text-2xl">University of Makati</span>
            </a>
        </div>

        <!-- Hamburger Icon -->
        <button id="menu-toggle" class="block md:hidden">
            <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
        </button>

        <!-- Navigation Menu -->
        <nav id="menu" class="hidden md:flex">
            <ul class="flex flex-col space-y-2 text-lg font-semibold text-wshite md:flex-row md:space-x-10 md:space-y-0">
                <li><a href="/index.php" class="text-white blue-2">Home</a></li>
                <li><a href="/src/ControlledData/appointment.php" class="text-white blue-2">Appointment</a></li>
                <li><a href="/src/ControlledData/referral.php" class="text-white blue-2">Referral</a></li>
                <li>
                    <a href="/src/ControlledData/login.php" 
                    class="px-4 py-2 text-white rounded-md blue-1" style="background-color: #618dc2">Login</a>
                </li>
            </ul>
        </nav>
    </div>
</header>

<!--CONTENT-->
<main class="flex items-center justify-center mt-24"> 
<div class="w-full max-w-4xl p-6">
<h2 class="text-2xl text-center font-bold">Assessment Scheduling</h2>
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
<footer class="w-full" style="background-color: #111c4e; position: fixed; bottom: 0; left: 0; right: 0;">
    <div class="w-full max-w-screen-xl p-4 py-6 mx-auto lg:py-8 dark:text-gray-800">
        <div class="md:flex md:justify-between">
            <div class="mb-6 md:mb-0">
                <a href="https://flowbite.com/" class="flex items-center">
                    <img src="/src/images/UMAK-CGCS-logo.png" class="h-8 me-3" alt="GuidanceHub Logo" />
                    <span class="font-bold tracking-wide text-white md:text-2xl">GuidanceHub</span>
                </a>
            </div>
            <div class="grid grid-cols-2 text-white gap-8 sm:gap-6 sm:grid-cols-3">
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
        <div class="sm:flex sm:items-center text-white sm:justify-between">
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