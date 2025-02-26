<?php include('E:/GuidanceHub/src/ControlledData/server.php'); ?>
<?php
session_start(); // Start the session

$host = 'localhost';
$dbname = 'guidancehub';
$username = 'root';
$password = '';

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle connection error
    die("Could not connect to the database $dbname :" . $e->getMessage());
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
<header class="fixed top-0 left-0 z-50 w-full py-4 shadow-md" style="background-color: #1EB0A9">
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
                <li><a href="#home" class="hover:text-cyan-950 scroll-link">Home</a></li>
                <li><a href="#services" class="hover:text-cyan-950 scroll-link">Services</a></li>
                <li><a href="#about" class="hover:text-cyan-950 scroll-link">About</a></li>
                <li><a href="/src/ControlledData/referral.php" class="hover:text-cyan-950 scroll-link">Referral</a></li> 
                <li>
                    <a href="/src/ControlledData/login.php" 
                    class="px-4 py-2 text-white rounded-md bg-cyan-800 hover:bg-cyan-950">Login</a>
                </li>
            </ul>
        </nav>
    </div>
</header>

<main class="mt-20"> 

<!-- Referral Form -->
    <div class="justify-center p-6 mt-6">
        <h2 class="text-2xl font-semibold text-center text-gray-700">Referral Form</h2>
        <form action="submit_referral.php" method="POST" class="mt-4">
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
                                <h2 class="mb-4 text-xl font-bold">Referral Process</h2>
                                <p class="font-semibold">1. Identification of the Need for Referral</p>
                                <p class="mb-2">A teacher, staff member, parent, or even the student themselves recognizes the need for counseling services. Common reasons for referral include academic concerns, behavioral issues, emotional distress, or personal/social problems.</p>
                                
                                <p class="font-semibold">2. Initial Consultation and Documentation</p>
                                <p class="mb-4">The referrer (e.g., teacher, adviser, or parent) consults with the guidance counselor about the student’s situation. A referral form is usually completed, detailing the student’s concerns, behaviors observed, and reason for referral.</p>
                                
                                <button onclick="closeModal()" class="px-4 py-2 text-white bg-red-500 rounded">Close</button>
                            </div>
                        </div>
                </label>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <textarea id="reason" name="reason" rows="4" class="w-full p-2 mt-1 border border-gray-300 rounded-md" required></textarea>
                    </div>
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                        <input type="date" id="date" name="date" class="w-full p-2 mt-1 border border-gray-300 rounded-md" required>
                        <label for="time" class="block mt-4 text-sm font-medium text-gray-700">Time</label>
                        <input type="time" id="time" name="time" class="w-full p-2 mt-1 border border-gray-300 rounded-md" required>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms" class="text-sm text-gray-700"> I agree to the <a href="#" class="text-blue-500 underline">Data Privacy Policy</a> and <a href="#" class="text-blue-500 underline">Terms and Conditions</a>.</label>
                </div>
                <div>
                    <input type="checkbox" id="notify_parents" name="notify_parents" onclick="toggleModal()">
                    <label for="notify_parents" class="text-sm text-gray-700"> Notify Parents</label>
                </div>
            </div>
            <div class="flex justify-center">
                <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600">
                    Submit Referral
                </button>
            </div>
            </div>
        </form>
    </div>
</main>

<!-- Notification Modal -->
    <div id="notifyModal" class="fixed inset-0 flex items-center justify-center hidden bg-gray-800 bg-opacity-50">
        <div class="max-w-md p-6 bg-white rounded-lg shadow-lg">
            <h2 class="mb-4 text-xl font-bold">Notify Parents</h2>
            <p>Are you sure you want to notify the parents about this referral?</p>
            <div class="flex justify-end mt-4">
                <button onclick="toggleModal()" class="px-4 py-2 text-white bg-red-500 rounded-md">Cancel</button>
                <button onclick="toggleModal()" class="px-4 py-2 ml-2 text-white bg-blue-500 rounded-md">Confirm</button>
            </div>
        </div>
    </div>
    

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