<?php
session_start();
include('server.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

// Connect to the database
$con = mysqli_connect('localhost', 'root', '', 'guidancehub');

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Function to generate unique Ticket ID
function generateTicketID($con) {
    $dateCode = date("Ym"); // Year + Month (e.g., 202402)
    $query = "SELECT COUNT(*) AS total FROM referrals WHERE DATE_FORMAT(submission_date, '%Y%m') = '$dateCode'";
    $result = $con->query($query);
    $row = $result->fetch_assoc();
    $count = $row['total'] + 1;
    
    return "RF-" . $dateCode . "-" . str_pad($count, 3, '0', STR_PAD_LEFT);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $referrer_name = $con->real_escape_string($_POST['referrer_name']);
    $referrer_id = $con->real_escape_string($_POST['referrer_id']);
    $position = $con->real_escape_string($_POST['position']);
    $department = $con->real_escape_string($_POST['department']);
    $referrer_email = $con->real_escape_string($_POST['referrer_email']);
    $contact_number = $con->real_escape_string($_POST['contact_number']);

    $ticket_id = generateTicketID($con);
    $student_name = $con->real_escape_string($_POST['student_name']);
    $student_id = $con->real_escape_string($_POST['student_id']);
    $college = $con->real_escape_string($_POST['college']);
    $program = $con->real_escape_string($_POST['program']);
    $reason = $con->real_escape_string($_POST['reason']);
    $terms_accepted = isset($_POST['terms']) ? 1 : 0;

    // Fetch student email from database using student_id
    $emailQuery = "SELECT student_email FROM student_profile WHERE student_number = '$student_id'";
    $emailResult = $con->query($emailQuery);

    if ($emailResult->num_rows > 0) {
        $emailRow = $emailResult->fetch_assoc();
        $email = trim($emailRow['student_email']); 
    } else {
        echo "<script>alert('Error: Student email not found.'); window.location.href='/src/ControlledData/referral.php';</script>";
        exit();
    }

    // Insert into database
    $sql = "INSERT INTO referrals (ticket_id, student_name, student_id, college, program, reason, terms_accepted, referrer_name, referrer_id, position, department, referrer_email, contact_number) 
            VALUES ('$ticket_id', '$student_name', '$student_id', '$college', '$program', '$reason', '$terms_accepted', '$referrer_name', '$referrer_id', '$position', '$department', '$referrer_email', '$contact_number')";
    if ($con->query($sql) === TRUE) {
        // Send confirmation email
        if (!empty($email)) {
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'guidancehub01@gmail.com';
                $mail->Password   = 'zjrtujjwbznuzbzv';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = 465;

                // Recipients
                $mail->setFrom('guidancehub01@gmail.com', 'GuidanceHub');
                $mail->addAddress($email); // Ensure email is valid

                // Content
                $mail->isHTML(true);
                $mail->Subject = "Counseling Services Referral - $ticket_id";
                $mail->Body    = "
                    <h2>Counseling Services Referral</h2>
                    <p>Dear <b>$student_name</b>,</p>
                    <p>You have been referred by $referrer_name, $position of $department for Counseling Services. 
                        <br>Below are the details of your referral:</p>
                    <ul>
                        <li><strong>Ticket ID:</strong> $ticket_id</li>
                        <li><strong>Student Name:</strong> $student_name</li>
                        <li><strong>Student ID:</strong> $student_id</li>
                        <li><strong>College/Institute:</strong> $college</li>
                        <li><strong>Program:</strong> $program</li>
                        <li><strong>Reason for Referral:</strong> $reason</li>
                    </ul>
                    <p>For further details, please check your profile.</p>
                    <p>Best regards,<br><b>Guidance Office</b></p>
                ";

                // Send email
                $mail->send();
                echo "<script>alert('Referral submitted successfully! A confirmation email has been sent.'); window.location.href='/src/ControlledData/referral.php';</script>";

            } catch (Exception $e) {
                echo "<script>alert('Referral submitted, but email could not be sent. Error: {$mail->ErrorInfo}'); window.location.href='/src/ControlledData/referral.php';</script>";
            }
        } else {
            echo "<script>alert('Referral submitted, but email could not be sent. No email found.'); window.location.href='/src/ControlledData/referral.php';</script>";
        }
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
        exit();
    }
}

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
<main class="mt-24"> 
    <h2 class="text-2xl font-bold text-center">Referral Form</h2>

<div class="max-w-4xl mx-auto my-5 mt-6">
    <div class="border-b border-gray-200">
        <nav class="flex space-x-4" id="tabs">
            <button class="px-4 py-2 text-gray-700 bg-gray-300 rounded-md active" onclick="openTab(event, 'tab1')">Referrer Form</button>
            <button class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md" onclick="openTab(event, 'tab2')">Student Information</button>
        </nav>
    </div>

    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-lg">
        <form action="referral.php" method="POST" class="mt-4">

            <!-- Referrer Form Tab -->
            <div id="tab1" class="tab-content">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Referrer Name</label>
                        <input type="text" name="referrer_name" class="w-full p-2 mt-1 border border-gray-300 rounded-md" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Referrer ID</label>
                        <input type="text" name="referrer_id" class="w-full p-2 mt-1 border border-gray-300 rounded-md" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Position</label>
                        <input type="text" name="position" class="w-full p-2 mt-1 border border-gray-300 rounded-md" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Department</label>
                        <input type="text" name="department" class="w-full p-2 mt-1 border border-gray-300 rounded-md" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="referrer_email" class="w-full p-2 mt-1 border border-gray-300 rounded-md" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Contact Number</label>
                        <input type="text" name="contact_number" class="w-full p-2 mt-1 border border-gray-300 rounded-md" required>
                    </div>
                </div>
            </div>

            <!-- Student Information Tab -->
            <div id="tab2" class="hidden tab-content">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="student_name" class="block text-sm font-medium text-gray-700">Student Name</label>
                        <input type="text" id="student_name" name="student_name" class="w-full p-2 mt-1 border border-gray-300 rounded-md" required>
                    </div>
                    <div>
                        <label for="student_id" class="block text-sm font-medium text-gray-700">Student ID</label>
                        <input type="text" id="student_id" name="student_id" class="w-full p-2 mt-1 border border-gray-300 rounded-md" required>
                    </div>
                    <div>
                        <label for="college" class="block text-sm font-medium text-gray-700">College/Institute</label>
                        <input type="text" id="college" name="college" class="w-full p-2 mt-1 border border-gray-300 rounded-md" required>
                    </div>
                    <div>
                        <label for="program" class="block text-sm font-medium text-gray-700">Program</label>
                        <input type="text" id="program" name="program" class="w-full p-2 mt-1 border border-gray-300 rounded-md" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="reason" class="block text-sm font-medium text-gray-700">Reason for Referral
                        <button onclick="openModal()" class="mx-2 text-xl text-black rounded">
                            <i class="fa-solid fa-circle-question"></i>
                        </button>
                            <!-- Modal -->
                            <div id="modal" class="fixed inset-0 flex items-center justify-center hidden bg-gray-800 bg-opacity-50">
                                <div class="max-w-lg p-6 bg-white rounded-lg shadow-lg">
                                    <h2 class="mb-4 text-3xl font-bold">Referral Process</h2>
                                    <p class="mb-2 text-2xl font-bold">Identification of the Need for Referral</p>
                                    <p class="mb-2 text-lg">A teacher, staff member, parent, or even the student themselves recognizes the need for counseling services. Common reasons for referral include academic concerns, behavioral issues, emotional distress, or personal/social problems.</p>
                                    <button onclick="closeModal()" class="px-4 py-2 text-white bg-red-500 rounded">Close</button>
                                </div>
                            </div>
                    </label>
                    <textarea id="reason" name="reason" rows="4" class="w-full p-2 mt-1 border border-gray-300 rounded-md" required></textarea>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms" class="ml-2 text-sm text-gray-700">
                        I agree to the <a href="#" class="text-blue-500 underline">Data Privacy Policy</a> and
                        <a href="#" class="text-blue-500 underline">Terms and Conditions</a>.
                    </label>
                </div>
                <!-- Submit Button -->
                    <div class="flex justify-center mt-4">
                        <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600">
                            Submit Referral
                        </button>
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

    function openModal() {
        document.getElementById('modal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('modal').classList.add('hidden');
    }
</script>
<script src="../path/to/flowbite/dist/flowbite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</body>
</html>