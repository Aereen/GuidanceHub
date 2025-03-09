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
function generateTicketID($con) {
    $dateCode = date("Ym"); // Year + Month (e.g., 202402)
    $query = "SELECT COUNT(*) AS total FROM appointments WHERE DATE_FORMAT(submission_date, '%Y%m') = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $dateCode);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $count = $row['total'] + 1;
    
    return "CS-" . $dateCode . "-" . str_pad($count, 3, '0', STR_PAD_LEFT);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Generate Ticket ID
        $ticket_id = generateTicketID($con);

        // Prepare and execute insert query
        $sql = "INSERT INTO appointments (ticket_id, name, contact, email, college, year_level, section, feelings, need_counselor, counseling_type, first_date, first_time, second_date, second_time)
                VALUES (:ticket_id, :name, :contact, :email, :college, :year_level, :section, :feelings, :need_counselor, :counseling_type, :first_date, :first_time, :second_date, :second_time)";

        $stmt = $pdo->prepare($sql);

        // Process "feelings" input
        $feelings = isset($_POST['feelings']) ? implode(", ", $_POST['feelings']) : '';
        if (!empty($_POST['feelings_other'])) {
            $feelings .= (!empty($feelings) ? ", " : "") . $_POST['feelings_other'];
        }

        // Execute the prepared statement
        $stmt->execute([
            ':ticket_id' => $ticket_id,
            ':name' => $_POST['name'],
            ':contact' => $_POST['contact'],
            ':email' => $_POST['email'],
            ':college' => $_POST['college'],
            ':year_level' => $_POST['year_level'],
            ':section' => $_POST['section'],
            ':feelings' => $feelings,
            ':need_counselor' => $_POST['need_counselor'],
            ':counseling_type' => $_POST['counseling_type'],
            ':first_date' => $_POST['first_date'],
            ':first_time' => $_POST['first_time'],
            ':second_date' => $_POST['second_date'],
            ':second_time' => $_POST['second_time'],
        ]);

        // Retrieve email and name for confirmation
        $email = $_POST['email'];
        $name = $_POST['name'];

        if (!empty($email)) {
            $mail = new PHPMailer(true);
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'guidancehub01@gmail.com';
                $mail->Password   = 'zjrtujjwbznuzbzv'; // Use an app password, not your actual password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = 465;

                // Recipients
                $mail->setFrom('guidancehub01@gmail.com', 'GuidanceHub');
                $mail->addAddress($email);

                // Email content
                $mail->isHTML(true);
                $mail->Subject = "Appointment Confirmation";
                $mail->Body    = "
                    <h2>Appointment Scheduled Successfully</h2>
                    <p>Dear <b>$name</b>,</p>
                    <p>Your appointment has been successfully scheduled. Below are your appointment details:</p>
                    <ul>
                        <li><strong>Ticket ID:</strong> $ticket_id</li>
                        <li><strong>Name:</strong> $name</li>
                        <li><strong>College:</strong> {$_POST['college']}</li>
                        <li><strong>Preferred Type:</strong> {$_POST['counseling_type']}</li>
                        <li><strong>First Appointment:</strong> {$_POST['first_date']} at {$_POST['first_time']}</li>
                        <li><strong>Second Appointment:</strong> {$_POST['second_date']} at {$_POST['second_time']}</li>
                    </ul>
                    <p>Thank you for scheduling your appointment.</p>
                    <p>Best regards,<br><b>Guidance Office</b></p>
                ";

                $mail->send();
                echo "<script>alert('Appointment scheduled! Confirmation email sent.'); window.location.href='/src/ControlledData/appointment.php';</script>";
            } catch (Exception $e) {
                echo "<script>alert('Appointment scheduled, but email could not be sent. Error: " . addslashes($mail->ErrorInfo) . "'); window.location.href='/src/ControlledData/appointment.php';</script>";
            }
        } else {
            echo "<script>alert('Appointment scheduled, but no email was provided.'); window.location.href='/src/ControlledData/appointment.php';</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Error scheduling appointment: " . addslashes($e->getMessage()) . "'); window.location.href='/src/ControlledData/appointment.php';</script>";
    }
}
?>

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
    <div class="container flex items-center justify-between px-4 mx-auto md:px-8">
        <!-- Logo -->
        <div class="flex items-center space-x-3">
            <img src="/src/images/UMAK-logo.png" alt="UMAK Logo" class="w-10 h-auto md:w-14"> <!--CHANGE INTO UMAK LOGO-->
            <img src="/src/images/UMAK-CGCS-logo.png" alt="CGCS Logo" class="w-10 h-auto md:w-14">
            <span class="font-semibold tracking-wide text-white md:text-2xl">GuidanceHub</span>
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
                <li><a href="/src/ControlledData/information.php" class="hover:text-cyan-950">Inventory Form</a></li>  
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
<h2 class="mb-6 text-2xl font-bold text-center">Appointment Scheduling</h2>
<div class="max-w-4xl mx-auto my-5 mt-6">
    <div class="border-b border-gray-200">
        <nav class="flex space-x-4" id="tabs">
            <button class="px-4 py-2 text-gray-700 bg-gray-300 rounded-md active" onclick="openTab(event, 'tab1')">Student Information</button>
            <button class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md" onclick="openTab(event, 'tab2')">Appointment Details</button>
        </nav>
    </div>

    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-lg">
        <form action="appointment.php" method="POST" class="mt-4">

            <!-- Student Information Form Tab -->
            <div id="tab1" class="tab-content">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <!-- Full Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name (First Name-MI-Last Name)</label>
                        <input type="text" id="name" name="name" required
                            class="w-full p-2 mt-1 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500"
                            placeholder="juan P. Dela Cruz">
                    </div>

                    <!-- Contact Number & Email -->
                    <div>
                        <label for="contact" class="block text-sm font-medium text-gray-700">Active Contact Number</label>
                        <input type="text" id="contact" name="contact" required
                            class="w-full p-2 mt-1 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500"
                            placeholder="09XX XXX XXXX">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" name="email" required
                            class="w-full p-2 mt-1 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500"
                            placeholder="@umak.edu.ph">
                    </div>

                    <!-- College Selection -->
                    <div>
                        <label for="college" class="block text-sm font-medium text-gray-700">College/Institute</label>
                        <select id="college" name="college" required
                            class="w-full p-2 mt-1 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500">
                            <option value="" disabled selected>Select College</option>
                            <option value="CBFS">College of Business and Financial Science (CBFS)</option>
                            <option value="CCIS">College of Computing and Information Sciences (CCIS)</option>
                            <option value="CCSE">College of Construction Sciences and Engineering (CCSE)</option>
                            <option value="CGPP">College of Governance and Public Policy (CGPP)</option>
                            <option value="CHK">College of Human Kinetics (CHK)</option>
                            <option value="CITE">College of Innovative Teacher Education (CITE)</option>
                            <option value="CTM">College of Technology Management (CTM)</option>
                            <option value="CTHM">College of Tourism and Hospitality Management (CTHM)</option>
                            <option value="IOA">Institute of Accountancy (IOA)</option>
                            <option value="IAD">Institute of Arts and Design (IAD)</option>
                            <option value="IIHS">Institute of Imaging Health Sciences (IIHS)</option>
                            <option value="ION">Institute of Nursing (ION)</option>
                            <option value="IOP">Institute of Pharmacy (IOP)</option>
                            <option value="IOPsy">Institute of Psychology (IOPsy)</option>
                            <option value="ISDNB">Institute of Social Development and Nation Building (ISDNB)</option>
                            <option value="HSU">Higher School ng UMak (HSU)</option>
                            <option value="SOL">School of Law (SOL)</option>
                        </select>
                    </div>

                    <!-- Year Level and Section -->
                    <div>
                        <label for="year_level" class="block text-sm font-medium text-gray-700">Year Level</label>
                        <select id="year_level" name="year_level" required
                            class="w-full p-2 mt-1 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500">
                            <option value="" disabled selected>Select Year</option>
                            <option value="1st Year">1st Year</option>
                            <option value="2nd Year">2nd Year</option>
                            <option value="3rd Year">3rd Year</option>
                            <option value="4th Year">4th Year</option>
                            <option value="5th Year">5th Year</option>
                        </select>
                    </div>
                    <div>
                        <label for="section" class="block text-sm font-medium text-gray-700">Section</label>
                        <input type="text" id="section" name="section" required
                            class="w-full p-2 mt-1 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500"
                            placeholder="AINS">
                    </div>
                </div>
            </div>

            <!-- Appointment Details Tab -->
            <div id="tab2" class="hidden tab-content">
                <div class="grid grid-cols-1 gap-6">
                <!-- Feelings Checkboxes -->
                <div>
                    <label id="feelings-label" class="block text-sm font-medium text-gray-700">
                        How are you (or how are you feeling) right now? Please check all that apply *
                    </label>
                    <div id="feelings-group" class="grid grid-cols-2 gap-2 mt-2 md:grid-cols-3" aria-labelledby="feelings-label">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="feelings[]" value="Excited"> 😄 Excited
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="feelings[]" value="Happy"> 😂 Happy
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="feelings[]" value="Sad"> 😔 Sad
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="feelings[]" value="Scared"> 😨 Scared
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="feelings[]" value="Angry"> 😠 Angry
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="feelings[]" value="Confused"> 😵 Confused
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="feelings[]" value="Burned Out"> 🥵 Burned Out
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="feelings[]" value="Calm"> 😌 Calm
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="feelings[]" value="Struggling"> 😣 Struggling
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="feelings[]" value="Hopeful"> 😇 Hopeful
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="feelings[]" value="Need a hug"> 🤗 Need a hug
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="feelings[]" value="Stuck and Unsure"> 🤨 Stuck and Unsure
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="feelings[]" value="Numb"> 😶 Numb
                        </label>
                    </div>

                    <input type="text" name="feelings_other" placeholder="Other..." class="w-full p-2 mt-2 border rounded-md">
                </div>

                <!-- Need to Talk to Counselor & Counseling Type in Same Row -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <!-- Need to Talk to Counselor -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Do you want/need to talk to a Guidance Counselor? *</label>
                        <select name="need_counselor" required class="w-full p-2 mt-1 border border-gray-300 rounded-md">
                            <option value="" disabled selected>Select an option</option>
                            <option value="Absolutely">Absolutely</option>
                            <option value="Not sure">Not sure</option>
                            <option value="Definitely not">Definitely not</option>
                        </select>
                    </div>

                    <!-- Counseling Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">What type of counseling session do you prefer? *</label>
                        <select name="counseling_type" required class="w-full p-2 mt-1 border border-gray-300 rounded-md">
                            <option value="" disabled selected>Select a type</option>
                            <option value="Virtual">Virtual (Online) Counseling</option>
                            <option value="In-Person">In-Person (Face-to-Face) Counseling</option>
                        </select>
                    </div>
                </div>

                <!-- Available Schedule Section -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <!-- First Available Schedule -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Please indicate your first available schedule for a counseling session *</label>
                        <div class="grid grid-cols-2 gap-4 mt-1">
                            <input type="date" name="first_date" required class="w-full p-2 border border-gray-300 rounded-md">
                            <input type="time" name="first_time" required min="08:00" max="17:00" class="w-full p-2 border border-gray-300 rounded-md">
                        </div>
                    </div>

                    <!-- Second Available Schedule -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Please indicate your second available schedule for a counseling session *</label>
                        <div class="grid grid-cols-2 gap-4 mt-1">
                            <input type="date" name="second_date" required class="w-full p-2 border border-gray-300 rounded-md">
                            <input type="time" name="second_time" required min="08:00" max="17:00" class="w-full p-2 border border-gray-300 rounded-md">
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-center mt-4">
                    <button type="submit" class="px-6 py-3 text-white bg-blue-500 rounded-md hover:bg-blue-600">
                        Submit
                    </button>
                </div>
            </div>
            </div>
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