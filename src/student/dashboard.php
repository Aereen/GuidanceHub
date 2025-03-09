<?php
// Start session
session_start();

// Check if the user is logged in, redirect to login page if not
if (!isset($_SESSION['id_number'])) {
    header("Location: /src/ControlledData/login.php"); // If not logged in, redirect to login
    exit;
}

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

// Fetch announcements from the database
try {
    $stmt = $pdo->prepare("SELECT * FROM announcement ORDER BY published_at DESC");
    $stmt->execute();
    $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Handle any errors during the query
    echo "Error fetching announcements: " . $e->getMessage();
}

// Query to check for an appointment
$email = $_SESSION['email'];

$stmt = $pdo->prepare("SELECT * FROM appointments 
                        WHERE email = :email 
                        AND (first_date >= NOW() OR second_date >= NOW())");
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->execute();
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);



// Fetch student profile
try {
    $stmt = $pdo->prepare("SELECT student_name, student_number, student_email, college_dept, year_level, section FROM student_profile WHERE student_number = ?");
    $stmt->execute([$_SESSION['id_number']]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        die("Student not found.");
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

//CALENDAR
// Set timezone
date_default_timezone_set('Asia/Manila');

// Get current month and year
$month = date('m');
$year = date('Y');

// Get first day of the month and total days in the month
$firstDayOfMonth = date('w', strtotime("$year-$month-01"));
$totalDays = date('t', strtotime("$year-$month-01"));

// Days of the week
$daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

// Generate calendar
$calendar = [];
$row = array_fill(0, 7, null);
$dayCounter = 1;

for ($i = 0; $i < 42; $i++) {
    if ($i >= $firstDayOfMonth && $dayCounter <= $totalDays) {
        $row[$i % 7] = $dayCounter++;
    }

    if ($i % 7 === 6) {
        $calendar[] = $row;
        $row = array_fill(0, 7, null);
    }
}

// When logout is requested
if (isset($_GET['logout'])) {
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session
    header("Location: /index.php"); // Redirect after logout
    exit;
}
?>
<!doctype html>
<html>
<head>
<title>GuidanceHub | Dashboard</title>
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

<!--TOP NAVIGATION BAR-->
<header class="fixed top-0 left-0 z-50 w-full shadow-md" style="background-color: #1EB0A9">
    <div class="flex px-3 py-4 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between w-full max-w-7xl">

            <!--LOGOS-->
            <div class="flex items-center mx-5">
                <img src="/src/images/UMAK-CGCS-logo.png" alt="CGCS Logo" class="w-10 h-auto md:w-14">
                <span class="mx-6 font-semibold tracking-wide text-white md:text-2xl">GuidanceHub</span>
            </div>

            <!-- Hamburger Icon (Mobile) -->
            <button id="menu-toggle" class="text-2xl text-white md:hidden focus:outline-none">
                <i class="fa-solid fa-bars"></i>
            </button>

            <!-- Navigation Links -->
            <div id="nav-menu" class="items-center hidden space-x-6 md:flex">
                <a href="dashboard.php" class="text-white hover:text-gray-300">Dashboard</a>
                <a href="library.php" class="text-white hover:text-gray-300">Library</a>
                
                <!--Message Icon-->
                    <div class="relative">
                        <button id="messageButton" class="text-gray-600 hover:text-gray-900 focus:outline-none">
                            <i class="text-2xl fa-solid fa-message"></i>
                            <!-- Unread Message Badge -->
                            <span id="messageBadge" class="absolute top-0 right-0 inline-flex items-center justify-center hidden w-4 h-4 text-xs font-bold text-white bg-red-500 rounded-full">
                                3
                            </span>
                        </button>
                        <!-- Message Chat Modal -->
                        <div id="chatModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-50">
                            <div class="w-full max-w-lg p-6 bg-white rounded-lg shadow-md">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-xl font-semibold">Chat with Support</h3>
                                    <button onclick="closeChatModal()" class="text-gray-500 hover:text-gray-800">✖</button>
                                </div>
                                <div id="chatContent" class="h-64 mb-4 overflow-y-auto text-sm text-gray-700">
                                    <div class="mb-2">
                                        <p class="p-2 bg-gray-100 rounded">Hello! How can I assist you today?</p>
                                    </div>
                                    <div class="mb-2">
                                        <p class="p-2 text-blue-800 bg-blue-100 rounded">I need help with my appointment.</p>
                                    </div>
                                </div>
                                <div class="flex">
                                    <input id="chatInput" type="text" class="w-full p-2 border border-gray-300 rounded-l-md" placeholder="Type your message...">
                                    <button onclick="sendMessage()" class="px-4 py-2 text-white bg-blue-500 rounded-r-md hover:bg-blue-700">Send</button>
                                </div>
                            </div>
                        </div>
                    </div>

                <!--Notification Bell Icon-->
                    <div class="relative">
                        <button id="notificationButton" class="text-gray-600 hover:text-gray-900 focus:outline-none">
                            <i class="text-2xl fa-solid fa-bell"></i>
                            <!-- Notification Badge -->
                            <span id="notificationBadge" class="absolute top-0 right-0 inline-flex items-center justify-center hidden w-4 h-4 text-xs font-bold text-white bg-red-500 rounded-full">
                                0
                            </span>
                        </button>
                        <!-- Notification Dropdown -->
                        <div id="notificationDropdown" class="absolute right-0 z-50 hidden w-64 mt-2 bg-white border border-gray-200 rounded-lg shadow-lg">
                            <div class="p-4 text-sm text-gray-700">
                                <h4 class="text-lg font-bold">Notifications</h4>
                                <ul id="notificationList" class="mt-2 space-y-2">
                                    <li class="text-gray-500">No new notifications</li>
                                </ul>
                                <!-- Mark as Read Button -->
                                <button id="markReadButton" class="hidden w-full px-4 py-2 mt-4 text-sm font-medium text-white bg-blue-500 rounded hover:bg-blue-700">
                                    Mark All as Read
                                </button>
                            </div>
                        </div>
                    </div>

                <!-- Search Icon -->
                <div class="relative">
                    <button
                        id="search-toggle"
                        class="text-xl text-gray-700 hover:text-blue-600 focus:outline-none">
                        <i id="search-icon" class="fa-solid fa-magnifying-glass"></i>
                    </button>

                    <!-- Search Box (Hidden Initially) -->
                    <div id="search-box" class="absolute right-0 p-4 mt-2 overflow-hidden transition-all duration-300 ease-in-out bg-white border border-gray-300 rounded-lg shadow-lg opacity-0 w-80 max-h-0">
                        <form action="" method="GET" class="w-full max-w-md mx-auto">
                            <label for="default-search" class="mb-2 text-sm font-medium sr-only">Search</label>
                            <div class="relative">
                                <input type="search" id="default-search" name="query"
                                    class="block w-full p-4 text-sm text-gray-900"
                                    placeholder="Search" />
                                <button type="submit"
                                    class="absolute px-4 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 right-2 bottom-2">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!--Logout Button-->
                <div class="relative">
                    <a href="?logout=true" class="text-gray-600 hover:text-gray-900 focus:outline-none">
                        <i class="text-xl fa-solid fa-right-from-bracket"></i>
                    </a>
                </div>
            </div>
        </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="flex-col items-center hidden p-4 mt-4 space-y-4 bg-gray-700 md:hidden">
                <a href="dashboard.php" class="text-white hover:text-gray-300">Dashboard</a>
                <a href="assessment.php" class="text-white hover:text-gray-300">Assessment</a>
                <a href="library.php" class="text-white hover:text-gray-300">Library</a>
                <a href="?logout=true" class="text-white hover:text-gray-300">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </a>
            </div>
        </div>
    </div>
</header>

<!--CONTENT-->
<main class="w-full p-4 mt-20">
<h2 class="p-3 text-4xl font-bold tracking-tight"><?php echo "Welcome, " . $_SESSION['name'] . "!<br>" ?></h2>
    <div class="grid grid-cols-1 gap-4 p-1 lg:grid-cols-3">
    <!-- Left Column: Activities and Announcements -->
    <div class="col-span-2 space-y-4">
        <!-- ACTIVITIES -->
        <section class="p-5 bg-white border-2 rounded-lg h-80 dark:border-gray-300">   
            <h2 class="text-2xl font-bold">Activities</h2>
            <div class="grid grid-cols-1 gap-4 mt-4 sm:grid-cols-2">
                <!-- UPCOMING SESSIONS -->
                <div class="p-6 bg-white rounded-lg shadow-md">
                    <h2 class="mb-3 text-xl font-bold text-gray-700 underline">Upcoming Appointments</h2>
                    <ul id="appointments-list" class="space-y-3">
                        <?php if (!empty($appointments)): ?>
                            <?php foreach ($appointments as $appointment): ?>
                                <li class="flex flex-col space-y-1 text-gray-800">
                                    <h2 class="font-medium">First Date & Time:</h2>
                                    <span><?= htmlspecialchars($appointment['first_date']) ?></span>
                                    <span><?= htmlspecialchars($appointment['first_time']) ?></span>
                                    <h2 class="font-medium">Second Date & Time:</h2>
                                    <span><?= htmlspecialchars($appointment['second_date']) ?></span>
                                    <span><?= htmlspecialchars($appointment['second_time']) ?></span>
                                    <h2 class="font-medium">Status:</h2>
                                    <span><?= htmlspecialchars($appointment['status']) ?: 'Pending'; ?></span>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="text-gray-600">No Appointments...</li>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- PENDING ASSESSMENTS -->
                <div class="p-6 bg-white rounded-lg shadow-md">
                    <h2 class="mb-3 text-xl font-bold text-gray-700 underline">Scheduled Assessments</h2>
                    <ul class="space-y-3">
                        <?php if (!empty($appointments)): ?>
                            <?php foreach ($appointments as $appointment): ?>
                                <li class="flex flex-col space-y-1 text-gray-800">
                                    <h2 class="font-medium">Assessment Date:</h2>
                                    <span><?= htmlspecialchars($appointment['first_date']) ?></span>
                                    <span><?= htmlspecialchars($appointment['first_time']) ?></span>
                                    <h2 class="font-medium">Status:</h2>
                                    <span><?= htmlspecialchars($appointment['status']) ?: 'Pending'; ?></span>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="text-gray-600">No Assessments...</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </section>

        <!-- ANNOUNCEMENT SECTION -->
        <section class="p-4 bg-white border-2 border-gray-300 rounded-lg">
            <h4 class="p-2 text-xl font-semibold text-white bg-teal-500 rounded-lg">ANNOUNCEMENTS</h4>
            <div class="grid grid-cols-1 gap-4 my-3 sm:grid-cols-2">
                <?php if (empty($announcements)): ?>
                    <p class="text-gray-500 col-span-full">No announcements available.</p>
                <?php else: ?>
                    <?php foreach ($announcements as $announcement): ?>
                        <div class="p-6 bg-white rounded-lg shadow-lg">
                            <h3 class="mb-2 text-2xl font-semibold text-blue-800">
                                <?= htmlspecialchars($announcement['title']); ?>
                            </h3>
                            <p class="text-gray-700">
                                <?= nl2br(htmlspecialchars($announcement['content'])); ?>
                            </p>
                            <p class="mt-2 text-sm text-gray-500">
                                Posted on: <?= date('F j, Y, g:i a', strtotime($announcement['published_at'])); ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </div>

    <!-- Right Column: Profile and Calendar -->
    <div class="self-start col-span-1 space-y-6">
        <!-- PROFILE CARD -->
        <div class="w-full p-5 bg-white border-2 rounded-lg shadow-lg">
            <h4 class="text-2xl font-bold text-center text-gray-700">PROFILE</h4>
            <div class="flex flex-col items-center p-3">
                <img src="/src/images/UMak-Facade-Admin.jpg" alt="Profile Picture"
                    class="w-32 h-32 border-4 border-gray-300 rounded-full">
                <table class="w-full mt-4 text-sm text-center text-gray-800">
                    <tbody>
                        <tr><td class="px-5 py-1"><?= htmlspecialchars($student['student_name']); ?></td></tr>
                        <tr><td class="px-5 py-1"><?= htmlspecialchars($student['student_number']); ?></td></tr>
                        <tr><td class="px-5 py-1"><?= htmlspecialchars($student['student_email']); ?></td></tr>
                        <tr><td class="px-5 py-1"><?= htmlspecialchars($student['college_dept']); ?></td></tr>
                        <tr><td class="px-5 py-1"><?= htmlspecialchars($student['year_level']); ?></td></tr>
                        <tr><td class="px-5 py-1"><?= htmlspecialchars($student['section']); ?></td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- CALENDAR -->
        <div class="p-3 bg-white border-2 rounded-lg">
            <h2 class="mb-4 text-2xl font-bold text-center text-gray-800">
                <?= date('F Y'); ?>
            </h2>
            
            <!-- Days of the Week -->
            <div class="grid grid-cols-7 gap-2 font-semibold text-center text-gray-600">
                <?php foreach ($daysOfWeek as $day): ?>
                    <div class="p-1 text-white bg-teal-500 rounded-lg"><?= $day; ?></div>
                <?php endforeach; ?>
            </div>

            <!-- Calendar Days -->
            <div class="grid grid-cols-7 gap-2 mt-2 text-center">
                <?php foreach ($calendar as $week): ?>
                    <?php foreach ($week as $day): ?>
                        <?php 
                            $dateStr = "$year-$month-" . str_pad($day, 2, '0', STR_PAD_LEFT);
                            $appointmentFound = false;
                            $tooltipText = "";

                            foreach ($appointments as $appointment) {
                                if ($appointment['appointment_date'] === $dateStr) {
                                    $appointmentFound = true;
                                    $tooltipText = "Confirmed Appointment Scheduled<br>Pending Assessment";
                                    break;
                                }
                            }
                        ?>
                        
                        <!-- Calendar Day Box -->
                        <div 
                            class="relative p-2 rounded-lg cursor-pointer <?= $appointmentFound ? 'bg-teal-500 text-white' : 'bg-gray-100'; ?>" 
                            <?php if ($appointmentFound): ?>
                                onmouseover="showTooltip(this, '<?= $tooltipText; ?>')" 
                                onmouseout="hideTooltip(this)"
                            <?php endif; ?>
                        >
                            <?= $day ?: ''; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

</div>

<!-- COUNSELING PROCESS -->
<h4 class="p-2 text-xl font-semibold text-white bg-teal-500 rounded-lg">PROCESS</h4>
    <section class="grid grid-cols-3 gap-4 p-5 my-5 bg-white border-2 rounded-lg dark:border-gray-300">
        <!-- Card 1 -->
        <div class="w-full p-6 bg-white border border-gray-200 rounded-lg shadow dark:border-gray-300">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-black">Needs Assessment</h5>
            <p class="mb-3 text-gray-700 dark:text-gray-800">To identify students' needs and concerns, conduct surveys or questionnaires...</p>
        </div>

        <!-- Card 2 -->
        <div class="w-full p-6 bg-white border border-gray-200 rounded-lg shadow dark:border-gray-300">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-black">Program Planning</h5>
            <p class="mb-3 text-gray-700 dark:text-gray-800">Include a plan for delivering services, such as individual counseling...</p>
        </div>

        <!-- Card 3 -->
        <div class="w-full p-6 bg-white border border-gray-200 rounded-lg shadow dark:border-gray-300">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-black">Counseling and Intervention</h5>
            <p class="mb-3 text-gray-700 dark:text-gray-800">Individual counseling provides one-on-one support...</p>
        </div>

        <!-- Card 4 -->
        <div class="w-full p-6 bg-white border border-gray-200 rounded-lg shadow dark:border-gray-300">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-black">Follow-up Consultation</h5>
            <p class="mb-3 text-gray-700 dark:text-gray-800">Monitor the progress of students and provide ongoing support...</p>
        </div>

        <!-- Card 5 -->
        <div class="w-full p-6 bg-white border border-gray-200 rounded-lg shadow dark:border-gray-300">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-black">Evaluation and Program Improvement</h5>
            <p class="mb-3 text-gray-700 dark:text-gray-800">Conduct regular evaluations to assess the effectiveness...</p>
        </div>
    </section>
</main>

<!--SCHEDULING CALL TO ACTION-->
<section class="flex items-center justify-center w-full bg-yellow-300">
    <div class="p-8 text-center">
        <h2 class="mb-4 text-3xl font-semibold text-gray-800">Schedule Your Counseling Appointment</h2>
        <p class="mb-6 text-lg text-gray-600">
            Taking the first step toward mental well-being is easy. Book an appointment with our counselors today.
        </p>

        <!-- Call to Action Link -->
        <a href="/src/ControlledData/appointment.php" class="inline-block px-6 py-3 text-xl text-white transition duration-300 bg-blue-600 rounded-lg hover:bg-blue-700">
            Book Your Appointment
        </a>

        <!-- Optional: Contact Details -->
        <div class="mt-6 text-sm text-gray-500">
            <p>If you need assistance, call us at <strong>(123) 456-7890</strong> or email <strong>support@counseling.com</strong></p>
        </div>
    </div>
</section>

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
//Toggling Menu
document.getElementById('menu-toggle').addEventListener('click', function () {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });


//Toggle for Message
const messageButton = document.getElementById('messageButton');
    const chatModal = document.getElementById('chatModal');
    const messageBadge = document.getElementById('messageBadge');
    const chatContent = document.getElementById('chatContent');
    const chatInput = document.getElementById('chatInput');

    // Show chat modal when message icon is clicked
    messageButton.addEventListener('click', () => {
        chatModal.classList.toggle('hidden');
    });

    // Close the chat modal
    function closeChatModal() {
        chatModal.classList.add('hidden');
    }

    // Send a new message
    function sendMessage() {
        const messageText = chatInput.value.trim();
        if (messageText) {
            const newMessage = document.createElement('div');
            newMessage.classList.add('mb-2');
            newMessage.innerHTML = `<p class="p-2 text-blue-800 bg-blue-100 rounded">${messageText}</p>`;
            chatContent.appendChild(newMessage);
            chatInput.value = ''; // Clear input after sending
            chatContent.scrollTop = chatContent.scrollHeight; // Scroll to the latest message
        }
    }

    // Simulate unread messages (this would be dynamic in a real app)
    function simulateUnreadMessages() {
        const unreadMessages = 3; // Example count of unread messages
        if (unreadMessages > 0) {
            messageBadge.textContent = unreadMessages;
            messageBadge.classList.remove('hidden');
        } else {
            messageBadge.classList.add('hidden');
        }
    }

    // Initialize unread message count
    simulateUnreadMessages();

// JavaScript to handle search box toggling and icon change
    document.addEventListener('DOMContentLoaded', function () {
        const searchToggle = document.getElementById('search-toggle');
        const searchBox = document.getElementById('search-box');
        const searchIcon = document.getElementById('search-icon');
        const searchExit = document.getElementById('search-exit');

        // Toggle the search box and icon when search icon is clicked
        searchToggle.addEventListener('click', function () {
            // Toggle search box visibility
            if (searchBox.classList.contains('opacity-0')) {
                searchBox.classList.remove('opacity-0', 'max-h-0');
                searchBox.classList.add('opacity-100', 'max-h-screen');
                searchIcon.classList.remove('fa-magnifying-glass');
                searchIcon.classList.add('fa-circle-xmark');  // Change to exit icon
            } else {
                searchBox.classList.add('opacity-0', 'max-h-0');
                searchBox.classList.remove('opacity-100', 'max-h-screen');
                searchIcon.classList.remove('fa-circle-xmark'); // Revert to search icon
                searchIcon.classList.add('fa-magnifying-glass');
            }
        });

        // Hide search box when clicking the exit icon
        searchExit.addEventListener('click', function () {
            searchBox.classList.add('opacity-0', 'max-h-0');
            searchBox.classList.remove('opacity-100', 'max-h-screen');
            searchIcon.classList.remove('fa-circle-xmark');
            searchIcon.classList.add('fa-magnifying-glass'); // Revert to search icon
        });
    });

//Notifications
const appointments = <?php echo json_encode($appointments); ?>;

document.addEventListener('DOMContentLoaded', () => {
    const notificationButton = document.getElementById('notificationButton');
    const notificationDropdown = document.getElementById('notificationDropdown');
    const notificationBadge = document.getElementById('notificationBadge');
    const notificationList = document.getElementById('notificationList');
    const markReadButton = document.getElementById('markReadButton');
    
    // Modal Elements
    const notificationModal = document.getElementById('notificationModal');
    const modalContent = document.getElementById('modalContent');
    const closeModal = document.getElementById('closeModal');

    let notifications = appointments.map(appointment => ({
        text: `Reminder: You have an appointment on ${appointment.appointment_date} at ${appointment.appointment_time}.`,
        read: false
    }));

    function updateNotifications() {
        if (notifications.length > 0) {
            notificationBadge.textContent = notifications.filter(n => !n.read).length;
            notificationBadge.classList.remove('hidden');
            markReadButton.classList.remove('hidden');

            notificationList.innerHTML = notifications.map((notif, index) => 
                `<li class="p-2 rounded cursor-pointer ${notif.read ? 'bg-gray-200' : 'bg-gray-100 hover:bg-gray-200'}" data-index="${index}">
                    ${notif.text}
                </li>`
            ).join('');

            // Add event listeners to open modal on click
            document.querySelectorAll('#notificationList li').forEach(item => {
                item.addEventListener('click', () => {
                    const index = item.getAttribute('data-index');
                    notifications[index].read = true;
                    modalContent.textContent = notifications[index].text;
                    notificationModal.classList.remove('hidden'); // Show modal
                    
                    notificationDropdown.classList.add('hidden'); // Hide notification dropdown
                    updateNotifications();
                });
            });
        } else {
            notificationBadge.classList.add('hidden');
            markReadButton.classList.add('hidden');
            notificationList.innerHTML = `<li class="text-gray-500">No new notifications</li>`;
        }
    }

    // Show or hide the dropdown
    notificationButton.addEventListener('click', () => {
        notificationDropdown.classList.toggle('hidden');
    });

    // Mark all notifications as read
    markReadButton.addEventListener('click', () => {
        notifications.forEach(n => n.read = true);
        updateNotifications();
    });

    // Close modal event
    closeModal.addEventListener('click', () => {
        notificationModal.classList.add('hidden');
    });

    updateNotifications();
});

//Highlighted Appointment Schedule Calendar
    function showTooltip(element, text) {
        let tooltip = element.querySelector('.tooltip');
        tooltip.innerHTML = text;
        tooltip.classList.remove('hidden');
    }

    function hideTooltip(element) {
        let tooltip = element.querySelector('.tooltip');
        tooltip.classList.add('hidden');
    }
</script>
<script src="../path/to/flowbite/dist/flowbite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</body>
</html>