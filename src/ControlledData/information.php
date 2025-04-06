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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Prepare the SQL query using MySQLi
    $sql = "INSERT INTO individual_inventory (
        student_name, student_number, student_email, student_contact, student_birthdate, 
        student_age, student_gender, civil_status, address,
        religion, religion_specify, college_dept, year_level, elementary, 
        elementary_year, junior_high, junior_year, senior_high, 
        senior_year, college_name, college_year, national_exam, board_exam, 
        spouse_name, date_marriage, place_marriage, spouse_occupation, spouse_employer
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $con->prepare($sql)) {
        // Assign values with null-handling for optional fields
        $params = [
            $_POST['student_name'] ?? null, $_POST['student_number'] ?? null, $_POST['student_email'] ?? null,
            $_POST['student_contact'] ?? null, $_POST['student_birthdate'] ?? null, $_POST['student_age'] ?? null,
            $_POST['student_gender'] ?? null, $_POST['civil_status'] ?? null, $_POST['address'] ?? null,
            $_POST['religion'] ?? null, $_POST['religion_specify'] ?? null, $_POST['college_dept'] ?? null,
            $_POST['year_level'] ?? null, $_POST['elementary'] ?? null, $_POST['elementary_year'] ?? null,
            $_POST['junior_high'] ?? null, $_POST['junior_year'] ?? null, $_POST['senior_high'] ?? null,
            $_POST['senior_year'] ?? null, $_POST['college_name'] ?? null, $_POST['college_year'] ?? null,
            $_POST['national_exam'] ?? null, $_POST['board_exam'] ?? null, $_POST['spouse_name'] ?? null,
            $_POST['date_marriage'] ?? null, $_POST['place_marriage'] ?? null, $_POST['spouse_occupation'] ?? null,
            $_POST['spouse_employer'] ?? null
        ];

        // Bind parameters dynamically
        $types = str_repeat('s', count($params));
        $stmt->bind_param($types, ...$params);

        // Execute statement
        if ($stmt->execute()) {
            echo "<script>alert('Information submitted successfully!'); window.location.href='/src/ControlledData/information.php';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    } else {
        echo "Error preparing the statement: " . $con->error;
    }

    // Close database connection
    $con->close();
} else {
    echo "Invalid request.";
}
?>


<!doctype html>
<html>
<head>
<title>GuidanceHub | Individual Inventory Form</title>
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

<!--Toast Notification for Data Insertion-->
<div class="bottom-0 p-3 position-fixed end-0" style="z-index: 11">
    <div id="toastMessage" class="text-white border-0 toast align-items-center bg-success" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                Data inserted successfully!
            </div>
            <button type="button" class="m-auto btn-close btn-close-white me-2" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

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
<main class="mt-16">
<div class="relative flex items-center justify-center mb-6">
    <h2 class="text-2xl font-bold text-center">INDIVIDUAL INVENTORY FORM</h2>
    <div class="relative ml-2">
        <button class="focus:outline-none" id="popoverButton">
            <svg class="w-6 h-6 text-gray-500 hover:text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v4a1 1 0 001 1h2a1 1 0 100-2h-1V7zm-1 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
            </svg>
        </button>
        <div class="absolute z-10 hidden w-64 p-4 mt-2 text-sm text-white bg-gray-800 rounded-lg shadow-lg" id="popoverContent">
            This form is used to collect individual information for inventory purposes. Please fill out all the required fields accurately.
        </div>
    </div>
</div>
    <div class="w-5/6 p-6 mx-auto my-4 bg-white rounded-lg shadow-lg">
        <form action="information.php" method="POST">
            
        <!-- Personal Information Table -->
        <div class="overflow-x-auto">
            <table class="w-full mb-6 border border-collapse border-gray-300">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="px-4 py-2 border border-gray-300 text-lg" colspan="4">PERSONAL INFORMATION</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <tr class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <td class="px-4 py-2 border border-gray-300">Name<br>(Last Name, Given Name, Middle Name)</td>
                        <td class="px-4 py-2 border border-gray-300">
                            <input type="text" name="student_name" class="w-full p-2 border rounded" required>
                        </td>
                        <td class="px-4 py-2 border border-gray-300">Student No.</td>
                        <td class="px-4 py-2 border border-gray-300">
                            <input type="text" name="student_number" class="w-full p-2 border rounded" required>
                        </td>
                    </tr>
        
                    <tr class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <td class="px-4 py-2 border border-gray-300">University Email Address</td>
                        <td class="px-4 py-2 border border-gray-300">
                            <input type="email" name="student_email" class="w-full p-2 border rounded">
                        </td>
                        <td class="px-4 py-2 border border-gray-300">Contact No.</td>
                        <td class="px-4 py-2 border border-gray-300">
                            <input type="number" name="student_contact" class="w-full p-2 border rounded" required>
                        </td>
                    </tr>
        
                    <tr class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <td class="px-4 py-2 border border-gray-300">Date of Birth</td>
                        <td class="px-4 py-2 border border-gray-300">
                            <input type="date" name="student_birthdate" id="birthdate" class="w-full p-2 border rounded" oninput="calculateAge()" required>
                        </td>
                        <td class="px-4 py-2 border border-gray-300">Age</td>
                        <td class="px-4 py-2 border border-gray-300">
                            <input type="number" name="student_age" id="age" class="w-full p-2 border rounded" required>
                        </td>
                    </tr>
        
                    <tr class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <td class="px-4 py-2 border border-gray-300">Gender</td>
                        <td class="px-4 py-2 border border-gray-300">
                            <select name="student_gender" class="w-full p-2 border rounded" required>
                                <option>Select Option</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Intersex">Intersex</option>
                            </select>
                        </td>
                        <td class="px-4 py-2 border border-gray-300">Civil Status</td>
                        <td class="px-4 py-2 border border-gray-300">
                            <select name="civil_status" class="w-full p-2 border rounded" required>
                                <option>Select Option</option>
                                <option value="single">Single</option>
                                <option value="married">Married</option>
                                <option value="widow">Widow</option>
                            </select>
                        </td>
                    </tr>
        
                    <tr class="grid grid-cols-1">
                        <td class="px-4 py-2 border border-gray-300">Address</td>
                        <td class="px-4 py-2 border border-gray-300">
                            <input type="text" name="address" class="w-full p-2 border rounded" required>
                        </td>
                    </tr>
        
                    <tr class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <td class="px-4 py-2 border border-gray-300">Religion</td>
                        <td class="px-4 py-2 border border-gray-300">
                            <select name="religion" id="religion" class="w-full p-2 border rounded" onchange="toggleSpecifyInput()" required>
                                <option value="">Select Option</option>
                                <option value="catholic">Roman Catholic</option>
                                <option value="muslim">Muslim</option>
                                <option value="iglesia">Iglesia ni Cristo</option>
                                <option value="atheist">Atheist</option>
                                <option value="others">Others</option>
                            </select>
                            <input type="text" name="religion_specify" id="religion_specify" class="hidden w-full p-2 mt-2 border rounded" placeholder="Please specify your religion">
                        </td>
                    </tr>
        
                    <tr class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <td class="px-4 py-2 border border-gray-300">College/Institute</td>
                        <td class="px-4 py-2 border border-gray-300">
                            <select id="college_dept" name="college_dept" class="w-full p-2 border rounded" required>
                                <option value="" disabled selected>Select College</option>
                                <option value="CBFS">College of Business and Financial Science</option>
                                <option value="CCIS">College of Computing and Information Sciences</option>
                                <option value="CCSE">College of Construction Sciences and Engineering</option>
                                <option value="CGPP">College of Governance and Public Policy</option>
                                <option value="CHK">College of Human Kinetics</option>
                                <option value="CITE">College of Innovative Teacher Education</option>
                                <option value="CTM">College of Technology Management</option>
                                <option value="CTHM">College of Tourism and Hospitality Management</option>
                                <option value="IOA">Institute of Accountancy</option>
                                <option value="IAD">Institute of Arts and Design</option>
                                <option value="IIHS">Institute of Imaging Health Sciences</option>
                                <option value="ION">Institute of Nursing</option>
                                <option value="IOP">Institute of Pharmacy</option>
                                <option value="IOPsy">Institute of Psychology</option>
                                <option value="ISDNB">Institute of Social Development and Nation Building</option>
                                <option value="HSU">Higher School ng UMak</option>
                                <option value="SOL">School of Law</option>
                            </select>
                        </td>
                        <td class="px-4 py-2 border border-gray-300">Year Level</td>
                        <td class="px-4 py-2 border border-gray-300">
                            <select id="year_level" name="year_level" class="w-full p-2 border rounded" required>
                                <option value="" disabled selected>Select Year</option>
                                <option value="1st Year">1st Year</option>
                                <option value="2nd Year">2nd Year</option>
                                <option value="3rd Year">3rd Year</option>
                                <option value="4th Year">4th Year</option>
                                <option value="5th Year">5th Year</option>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Educational Background Table -->
        <table class="w-full mb-6 border border-collapse border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2 border border-gray-300" colspan="3">EDUCATIONAL BACKGROUND</th>
                </tr>
            </thead>
            <tbody>
                <tr class="flex flex-wrap">
                    <td class="w-full lg:w-1/3 px-4 py-2 border border-gray-300">Elementary</td>
                    <td class="w-full lg:w-1/3 px-4 py-2 border border-gray-300"><input type="text" placeholder="Elementary School Name" name="elementary" class="w-full p-2 border rounded" required></td>
                    <td class="w-full lg:w-1/3 px-4 py-2 border border-gray-300"><input type="number" placeholder="School Year" name="elementary_year" class="w-full p-2 border rounded" required></td>
                </tr>
                <tr class="flex flex-wrap">
                    <td class="w-full lg:w-1/3 px-4 py-2 border border-gray-300">Junior High</td>
                    <td class="w-full lg:w-1/3 px-4 py-2 border border-gray-300"><input type="text" placeholder="Junior High School Name" name="junior_high" class="w-full p-2 border rounded" required></td>
                    <td class="w-full lg:w-1/3 px-4 py-2 border border-gray-300"><input type="number" placeholder="School Year" name="junior_year" class="w-full p-2 border rounded" required></td>
                </tr>
                <tr class="flex flex-wrap">
                    <td class="w-full lg:w-1/3 px-4 py-2 border border-gray-300">Senior High</td>
                    <td class="w-full lg:w-1/3 px-4 py-2 border border-gray-300"><input type="text" placeholder="Senior High School Name" name="senior_high" class="w-full p-2 border rounded" required></td>
                    <td class="w-full lg:w-1/3 px-4 py-2 border border-gray-300"><input type="number" placeholder="School Year" name="senior_year" class="w-full p-2 border rounded" required></td>
                </tr>
                <tr class="flex flex-wrap">
                    <td class="w-full lg:w-1/3 px-4 py-2 border border-gray-300">University</td>
                    <td class="w-full lg:w-1/3 px-4 py-2 border border-gray-300"><input type="text" placeholder="University Name" name="college" class="w-full p-2 border rounded"></td>
                    <td class="w-full lg:w-1/3 px-4 py-2 border border-gray-300"><input type="number" placeholder="School Year" name="college_year" class="w-full p-2 border rounded"></td>
                </tr>
            </tbody>
        </table>

        <!-- Career Background Table -->
        <table class="w-full mb-6 border border-collapse border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2 border border-gray-300" colspan="2">CAREER BACKGROUND</th>
                </tr>
            </thead>
            <tbody>
                <tr class="flex flex-wrap">
                    <td class="w-full lg:w-1/2 px-4 py-2 border border-gray-300">National Exams Passed</td>
                    <td class="w-full lg:w-1/2 px-4 py-2 border border-gray-300"><input type="text" placeholder="Year Passed" name="national_exam" class="w-full p-2 border rounded"></td>
                </tr>
                <tr class="flex flex-wrap">
                    <td class="w-full lg:w-1/2 px-4 py-2 border border-gray-300">Board Examination</td>
                    <td class="w-full lg:w-1/2 px-4 py-2 border border-gray-300"><input type="text" placeholder="Year Passed" name="board_exam" class="w-full p-2 border rounded"></td>
                </tr>
            </tbody>
        </table>

        <!-- Siblings' Background Table -->
        <table class="w-full mb-6 border border-collapse border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2 border border-gray-300" colspan="3">SIBLING'S BACKGROUND</th>
                </tr>
            </thead>
            <tbody>
                <?php for ($i = 1; $i <= 3; $i++): ?>
                <tr class="flex flex-wrap">
                    <td class="w-full lg:w-1/3 px-4 py-2 border border-gray-300"><input type="text" name="sibling_name_<?= $i ?>" placeholder="Name" class="w-full p-2 border rounded"></td>
                    <td class="w-full lg:w-1/3 px-4 py-2 border border-gray-300"><input type="text" name="sibling_age_<?= $i ?>" placeholder="Age" class="w-full p-2 border rounded"></td>
                    <td class="w-full lg:w-1/3 px-4 py-2 border border-gray-300"><input type="text" name="sibling_occupation_<?= $i ?>" placeholder="Occupation" class="w-full p-2 border rounded"></td>
                </tr>
                <?php endfor; ?>
            </tbody>
        </table>

        <table class="w-full border border-collapse border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2 border border-gray-300" colspan="4">SPOUSE'S BACKGROUND (if married)</th>
                </tr>
            </thead>
            <tbody>
                <tr class="flex flex-wrap">
                    <td class="w-full lg:w-1/2 px-4 py-2 border border-gray-300">Spouse's Name</td>
                    <td class="w-full lg:w-1/2 px-4 py-2 border border-gray-300"><input type="text" name="spouse_name" class="w-full p-2 border rounded"></td>
                    <td class="w-full lg:w-1/2 px-4 py-2 border border-gray-300">Date of Marriage</td>
                    <td class="w-full lg:w-1/2 px-4 py-2 border border-gray-300"><input type="date" name="date_marriage" class="w-full p-2 border rounded"></td>
                </tr>
                <tr class="flex flex-wrap">
                    <td class="w-full lg:w-1/2 px-4 py-2 border border-gray-300">Place of Marriage</td>
                    <td class="w-full lg:w-1/2 px-4 py-2 border border-gray-300"><input type="text" name="place_marriage" class="w-full p-2 border rounded"></td>
                    <td class="w-full lg:w-1/2 px-4 py-2 border border-gray-300">Spouse's Occupation</td>
                    <td class="w-full lg:w-1/2 px-4 py-2 border border-gray-300"><input type="text" name="spouse_occupation" class="w-full p-2 border rounded"></td>
                </tr>
                <tr class="flex flex-wrap">
                    <td class="w-full lg:w-1/2 px-4 py-2 border border-gray-300">Employer</td>
                    <td class="w-full lg:w-1/2 px-4 py-2 border border-gray-300"><input type="text" name="spouse_employer" class="w-full p-2 border rounded"></td>
        </table>

        <div class="flex items-center mt-4">
            <input type="checkbox" id="terms" name="terms" required>
                <label for="terms" class="ml-2 text-sm text-gray-700">
                    I agree to the <a href="policy.php" class="text-blue-500 underline">Data Privacy Policy</a> and
                    <a href="terms.php" class="text-blue-500 underline">Terms and Conditions</a>.
                </label>
        </div>

        <button type="submit" class="w-40 p-3 m-5 text-white bg-blue-600 rounded-lg hover:bg-blue-700">Submit</button>
        </form>
    </div>
</main>

<!--FOOTER-->
<footer class="w-full" style="background-color: #111c4e">
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
//Menu button
    document.getElementById('popoverButton').addEventListener('click', function() {
        var popoverContent = document.getElementById('popoverContent');
        popoverContent.classList.toggle('hidden');
    });
    
function calculateAge() {
    const birthdate = document.getElementById('birthdate').value;
        if (birthdate) {
            const today = new Date();
            const birthDate = new Date(birthdate);
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();

            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }

            document.getElementById('age').value = age;
        }
}

function toggleSpecifyInput() {
        var religionSelect = document.getElementById("religion");
        var specifyInput = document.getElementById("religion_specify");

        if (religionSelect.value === "others") {
            specifyInput.classList.remove("hidden"); // Show input field
            specifyInput.setAttribute("required", "true"); // Make it required
        } else {
            specifyInput.classList.add("hidden"); // Hide input field
            specifyInput.removeAttribute("required"); // Remove required attribute
            specifyInput.value = ""; // Clear input value
        }
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
