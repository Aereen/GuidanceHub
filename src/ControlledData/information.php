<?php
session_start();
include('E:/GuidanceHub/src/ControlledData/server.php');

$host = 'localhost';
$dbname = 'guidancehub';
$username = 'root';
$password = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Establish PDO connection
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if student_number exists in the form
        if (!isset($_POST['student_number']) || empty($_POST['student_number'])) {
            throw new Exception("Error: Student number is required.");
        }

        // Required fields validation
        $required_fields = [
            'student_name', 'student_email', 'student_contact', 'student_birthdate', 
            'student_age', 'student_gender', 'civil_status', 'address', 'birthrank', 'religion',
            'elementary', 'elementary_year', 'junior_high', 'junior_year',
            'senior_high', 'senior_year', 'college', 'college_year', 'national_exam', 'board_exam'
        ];

        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                throw new Exception("Error: Missing required field - " . $field);
            }
        }

        // Prepare SQL statement
        $stmt = $pdo->prepare("
            INSERT INTO student_profile
            (student_number, student_name, student_email, student_contact, college, year_level, section, student_birthdate, student_age, student_gender, civil_status, address, birthrank, religion, 
            elementary, elementary_year, junior_high, junior_year, senior_high, senior_year, college, college_year, 
            national_exam, board_exam, spouse_name, date_marriage, place_marriage, spouse_occupation, spouse_employer, spouse_contact, num_children) 
            VALUES 
            (:student_number, :student_name, :student_email, :student_contact, :student_birthdate, :student_age, :student_gender, :civil_status, :address, :birthrank, :religion, 
            :elementary, :elementary_year, :junior_high, :junior_year, :senior_high, :senior_year, :college, :college_year, 
            :national_exam, :board_exam, :spouse_name, :date_marriage, :place_marriage, :spouse_occupation, :spouse_employer, :spouse_contact, :num_children)
        ");

        // Bind and execute parameters
        $stmt->execute([
            ':student_number' => $_POST['student_number'],
            ':student_name' => $_POST['student_name'],
            ':student_email' => $_POST['student_email'],
            ':college' => $_POST['college_dept'] ?? NULL,
            ':year_level' => $_POST['year_level'] ?? NULL,
            ':section' => $_POST['section'] ?? NULL,
            ':student_contact' => $_POST['student_contact'] ?? NULL,
            ':student_birthdate' => $_POST['student_birthdate'] ?? NULL,
            ':student_age' => $_POST['student_age'] ?? NULL,
            ':student_gender' => $_POST['student_gender'] ?? NULL,
            ':civil_status' => $_POST['civil_status'] ?? NULL,
            ':address' => $_POST['address'] ?? NULL,
            ':birthrank' => $_POST['birthrank'] ?? NULL,
            ':religion' => $_POST['religion'] ?? NULL,
            ':elementary' => $_POST['elementary'] ?? NULL,
            ':elementary_year' => $_POST['elementary_year'] ?? NULL,
            ':junior_high' => $_POST['junior_high'] ?? NULL,
            ':junior_year' => $_POST['junior_year'] ?? NULL,
            ':senior_high' => $_POST['senior_high'] ?? NULL,
            ':senior_year' => $_POST['senior_year'] ?? NULL,
            ':college' => $_POST['college'] ?? NULL,
            ':college_year' => $_POST['college_year'] ?? NULL,
            ':national_exam' => $_POST['national_exam'] ?? NULL,
            ':board_exam' => $_POST['board_exam'] ?? NULL,
            ':spouse_name' => $_POST['spouse_name'] ?? NULL,
            ':date_marriage' => $_POST['date_marriage'] ?? NULL,
            ':place_marriage' => $_POST['place_marriage'] ?? NULL,
            ':spouse_occupation' => $_POST['spouse_occupation'] ?? NULL,
            ':spouse_employer' => $_POST['spouse_employer'] ?? NULL,
            ':spouse_contact' => $_POST['spouse_contact'] ?? NULL,
            ':num_children' => $_POST['num_children'] ?? NULL
        ]);

        if ($success) {
            echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    var toastElement = document.getElementById("toastMessage");
                    var toast = new bootstrap.Toast(toastElement);
                    toast.show();
                });
            </script>';
        }
        exit();

    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
}
?>

<!doctype html>
<html>
<head>
<title>Individual Inventory Form</title>
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

<!--Toast Notification for Data Insertion-->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="toastMessage" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                Data inserted successfully!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<!--HEADER-->
<header class="fixed top-0 left-0 z-50 w-full py-4 shadow-xl" style="background-color: #1EB0A9">
    <div class="container-fluid flex items-center justify-between px-4 mx-auto md:px-8">
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
<main class="mt-20">
<h2 class="text-2xl font-bold text-center mb-6">Individual Inventory</h2>
    <div class="w-5/6 p-6 my-4 mx-auto bg-white rounded-lg shadow-lg">
        <form action="information.php" method="POST">
        <!-- Personal Information Table -->
        <table class="w-full mb-6 border border-collapse border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2 border border-gray-300" colspan="4">PERSONAL INFORMATION</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="px-4 py-2 border border-gray-300">Name<br>(Last Name, Given Name, Middle Name)</td>
                    <td class="px-4 py-2 border border-gray-300"><input type="text" name="student_name" class="w-full p-2 border rounded"></td>
                    <td class="px-4 py-2 border border-gray-300">Student No.</td>
                    <td class="px-4 py-2 border border-gray-300"><input type="text" name="student_number" class="w-full p-2 border rounded"></td>
                </tr>
                <tr>
                    <td class="px-4 py-2 border border-gray-300">University Email Address</td>
                    <td class="px-4 py-2 border border-gray-300"><input type="text" name="student_email" class="w-full p-2 border rounded"></td>
                    <td class="px-4 py-2 border border-gray-300">Contact No.</td>
                    <td class="px-4 py-2 border border-gray-300"><input type="number" name="student_contact" class="w-full p-2 border rounded"></td>
                </tr>
                <tr>
                    <td class="px-4 py-2 border border-gray-300">Date of Birth</td>
                    <td class="px-4 py-2 border border-gray-300">
                        <input type="date" name="student_birthdate" id="birthdate" class="w-full p-2 border rounded" oninput="calculateAge()">
                    </td>
                    <td class="px-4 py-2 border border-gray-300">Age</td>
                    <td class="px-4 py-2 border border-gray-300">
                        <input type="number" name="student_age" id="age" class="w-full p-2 border rounded">
                    </td>
                </tr>
                <tr>
                    <td class="px-4 py-2 border border-gray-300">Gender</td>
                    <td class="px-4 py-2 border border-gray-300">
                        <select name="student_gender" class="w-full p-2 border rounded">
                            <option>Select Option</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Intersex">Intersex</option>
                        </select>
                    </td>
                    <td class="px-4 py-2 border border-gray-300">Civil Status</td>
                    <td class="px-4 py-2 border border-gray-300">
                        <select name="civil_status" class="w-full p-2 border rounded">
                            <option>Select Option</option>
                            <option value="single">Single</option>
                            <option value="married">Married</option>
                            <option value="widow">Widow</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="px-4 py-2 border border-gray-300">Address</td>
                    <td class="px-4 py-2 border border-gray-300" colspan="3"><input type="text" name="address" class="w-full p-2 border rounded"></td>
                </tr>
                <tr>
                    <td class="px-4 py-2 border border-gray-300">Birth Rank</td>
                    <td class="px-4 py-2 border border-gray-300">
                        <input type="number" name="birthrank" class="w-full p-2 border rounded">
                    </td>
                    <td class="px-4 py-2 border border-gray-300">Religion</td>
                    <td class="px-4 py-2 border border-gray-300" colspan="3">
                        <select name="religion" class="w-full p-2 border rounded">
                            <option>Select Option</option>
                            <option value="catholic">Roman Catholic</option>
                            <option value="muslim">Muslim</option>
                            <option value="iglesia">Iglesia ni Cristo</option>
                            <option value="atheist">Atheist</option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Educational Background Table -->
        <table class="w-full mb-6 border border-collapse border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2 border border-gray-300" colspan="3">EDUCATIONAL BACKGROUND</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="px-4 py-2 border border-gray-300 border-none"></td>
                    <td class="px-4 py-2 text-center border border-gray-300 border-none">School Name</td>
                    <td class="px-4 py-2 text-center border border-gray-300 border-none">Year Graduated</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 border border-gray-300">Elementary</td>
                    <td class="px-4 py-2 border border-gray-300"><input type="text" name="elementary" class="w-full p-2 border rounded"></td>
                    <td class="px-4 py-2 border border-gray-300"><input type="number" name="elementary_year" class="w-full p-2 border rounded"></td>
                </tr>
                <tr>
                    <td class="px-4 py-2 border border-gray-300">Junior High</td>
                    <td class="px-4 py-2 border border-gray-300"><input type="text" name="junior_high" class="w-full p-2 border rounded"></td>
                    <td class="px-4 py-2 border border-gray-300"><input type="number" name="junior_year" class="w-full p-2 border rounded"></td>
                </tr>
                <tr>
                    <td class="px-4 py-2 border border-gray-300">Senior High</td>
                    <td class="px-4 py-2 border border-gray-300"><input type="text" name="senior_high" class="w-full p-2 border rounded"></td>
                    <td class="px-4 py-2 border border-gray-300"><input type="number" name="senior_year" class="w-full p-2 border rounded"></td>
                </tr>
                <tr>
                    <td class="px-4 py-2 border border-gray-300">College</td>
                    <td class="px-4 py-2 border border-gray-300"><input type="text" name="college" class="w-full p-2 border rounded"></td>
                    <td class="px-4 py-2 border border-gray-300"><input type="number" name="college_year" class="w-full p-2 border rounded"></td>
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
                <tr>
                    <td class="px-4 py-2 border border-gray-300">National Exams Passed</td>
                    <td class="px-4 py-2 border border-gray-300"><input type="text" name="national_exam" class="w-full p-2 border rounded"></td>
                </tr>
                <tr>
                    <td class="px-4 py-2 border border-gray-300">Board Examination</td>
                    <td class="px-4 py-2 border border-gray-300"><input type="text" name="board_exam" class="w-full p-2 border rounded"></td>
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
                <tr>
                    <td class="px-4 py-2 border border-gray-300"><input type="text" name="sibling_name_<?= $i ?>" placeholder="Name" class="w-full p-2 border rounded"></td>
                    <td class="px-4 py-2 border border-gray-300"><input type="text" name="sibling_age_<?= $i ?>" placeholder="Age" class="w-full p-2 border rounded"></td>
                    <td class="px-4 py-2 border border-gray-300"><input type="text" name="sibling_occupation_<?= $i ?>" placeholder="Occupation" class="w-full p-2 border rounded"></td>
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
                <tr>
                    <td class="px-4 py-2 border border-gray-300">Spouse's Name</td>
                    <td class="px-4 py-2 border border-gray-300"><input type="text" name="spouse_name" class="w-full p-2 border rounded"></td>
                    <td class="px-4 py-2 border border-gray-300">Date of Marriage</td>
                    <td class="px-4 py-2 border border-gray-300"><input type="date" name="date_marriage" class="w-full p-2 border rounded"></td>
                </tr>
                <tr>
                    <td class="px-4 py-2 border border-gray-300">Place of Marriage</td>
                    <td class="px-4 py-2 border border-gray-300"><input type="text" name="place_marriage" class="w-full p-2 border rounded"></td>
                    <td class="px-4 py-2 border border-gray-300">Spouse's Occupation</td>
                    <td class="px-4 py-2 border border-gray-300"><input type="text" name="spouse_occupation" class="w-full p-2 border rounded"></td>
                </tr>
                <tr>
                    <td class="px-4 py-2 border border-gray-300">Employer</td>
                    <td class="px-4 py-2 border border-gray-300"><input type="text" name="spouse_employer" class="w-full p-2 border rounded"></td>
                    <td class="px-4 py-2 border border-gray-300">Contact No.</td>
                    <td class="px-4 py-2 border border-gray-300"><input type="text" name="spouse_contact" class="w-full p-2 border rounded"></td>
                </tr>
                <tr>
                    <td class="px-4 py-2 border border-gray-300">No. of Children</td>
                    <td class="px-4 py-2 border border-gray-300"><input type="number" name="num_children" class="w-full p-2 border rounded"></td>
                </tr>
            </tbody>
        </table>

        <table class="w-full mt-4 border border-collapse border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2 border border-gray-300">NAME OF CHILD</th>
                    <th class="px-4 py-2 border border-gray-300">AGE</th>
                    <th class="px-4 py-2 border border-gray-300">EDUCATIONAL ATTAINMENT</th>
                    <th class="px-4 py-2 border border-gray-300">OCCUPATION</th>
                </tr>
            </thead>
            <tbody>
                <?php for ($i = 1; $i <= 3; $i++): ?>
                <tr>
                    <td class="px-4 py-2 border border-gray-300"><input type="text" name="child_name_<?= $i ?>" class="w-full p-2 border rounded"></td>
                    <td class="px-4 py-2 border border-gray-300"><input type="number" name="child_age_<?= $i ?>" class="w-full p-2 border rounded"></td>
                    <td class="px-4 py-2 border border-gray-300"><input type="text" name="child_education_<?= $i ?>" class="w-full p-2 border rounded"></td>
                    <td class="px-4 py-2 border border-gray-300"><input type="text" name="child_occupation_<?= $i ?>" class="w-full p-2 border rounded"></td>
                </tr>
                <?php endfor; ?>
            </tbody>
        </table>

        <div class="flex items-center mt-4">
            <input type="checkbox" id="terms" name="terms" required>
                <label for="terms" class="ml-2 text-sm text-gray-700">
                    I agree to the <a href="#" class="text-blue-500 underline">Data Privacy Policy</a> and
                    <a href="#" class="text-blue-500 underline">Terms and Conditions</a>.
                </label>
        </div>

        <button type="submit" class="w-40 p-3 m-5 text-white bg-blue-600 rounded-lg hover:bg-blue-700">Submit</button>
    </form>
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
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
