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

$stmt = $pdo->prepare("SELECT * FROM appointments WHERE email = :email AND appointment_date >= NOW()");
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->execute();

$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Fetch student profile
try {
    $stmt = $pdo->prepare("SELECT name, student_number, email, college, year_level, section FROM student_profile WHERE student_number = ?");
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
<nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200">
    <div class="flex px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between w-full max-w-7xl">
            <div class="flex items-center justify-start">
                <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                    </svg>
                </button>
                <a href="" class="flex ms-2 md:me-24">
                    <img src="/src/images/UMAK-CGCS-logo.png" class="h-8 me-3" alt="GuidanceHub Logo" />
                    <span class="self-center text-xl font-semibold text-black sm:text-2xl whitespace-nowrap">GuidanceHub</span>
                </a>
            </div>
            <div class="flex items-center justify-end gap-7 text-gray">
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

                <!-- Notifaction Modal (Hidden by Default) -->
                    <div id="notificationModal" class="fixed inset-0 flex items-center justify-center hidden bg-gray-900 bg-opacity-50">
                        <div class="p-6 bg-white rounded-lg shadow-lg w-96">
                            <h2 class="text-lg font-bold">Notification</h2>
                            <p id="modalContent" class="mt-2 text-gray-700"></p>
                            <button id="closeModal" class="px-4 py-2 mt-4 text-white bg-blue-500 rounded hover:bg-blue-700">
                                Close
                            </button>
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
            </div>
        </div>
    </div>
</nav>

<!--SIDE NAVIGATION MENU-->
<aside id="logo-sidebar" class="fixed z-40 h-screen pt-20 transition-transform -translate-x-full bg-white border-r w-60 dark:border-gray-300 sm:translate-x-0" aria-label="Sidebar">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-white border-gray-300">
        <ul class="m-3 space-y-2 font-medium">
            <li>
                <a href="dashboard.php" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
                <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                    <i class="fa-solid fa-house"></i>
                </svg>
                <span class="ms-3">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="assessment.php" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
                <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                    <i class="fa-solid fa-book-open"></i>
                </svg>
                <span class="flex-1 ms-3 whitespace-nowrap">Assessment</span>
                </a>
            </li>
            <li>
                <a href="appointment.php" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
                <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                    <i class="fa-solid fa-calendar-check"></i>
                </svg>
                <span class="flex-1 ms-3 whitespace-nowrap">Appointment</span>
                </a>
            </li>
            <li>
                <a href="library.php" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
                <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <i class="fa-solid fa-book-open"></i>
                </svg>
                <span class="flex-1 ms-3 whitespace-nowrap">Library</span>
                </a>
            </li>
            <li>
                <a href="?logout=true" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
                    <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 16">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/> <i class="fa-solid fa-right-from-bracket"></i>
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap">Log Out</span>
                </a>
            </li>
        </ul>
    </div>
</aside>

<!--CONTENT-->
<main class="p-4 mt-10 sm:ml-64">
    <h2 class="p-3 text-4xl font-bold tracking-tight"><?php echo "Welcome, " . $_SESSION['name'] . "!<br>" ?></h2>
    <div class="grid grid-cols-1 gap-4 p-1 lg:grid-cols-4">
        <!-- ACTIVITIES -->
        <section class="col-span-1 p-5 bg-white border-2 rounded-lg lg:col-span-3 dark:border-gray-300">   
            <h2 class="text-2xl font-bold">Activities</h2>
                <div class="grid grid-cols-1 gap-4 m-5 sm:grid-cols-1 lg:grid-cols-1">

                    <!--UPCOMING SESSIONS-->
                    <div class="max-w-md p-6 mx-auto bg-white rounded-lg shadow-md">
                        <h2 class="mb-3 text-xl font-bold text-gray-700 underline">Upcoming Appointments</h2>
                        <ul id="appointments-list" class="space-y-3">
                            <?php if (!empty($appointments)): ?>
                                <?php foreach ($appointments as $appointment): ?>
                                    <li class="flex items-center space-x-2 text-gray-800">
                                        <h2 class="font-medium">Date:</h2>
                                        <span><?= htmlspecialchars($appointment['appointment_date']) ?></span>
                                        <h2 class="font-medium">Time:</h2>
                                        <span><?= htmlspecialchars($appointment['appointment_time']) ?></span>
                                        <h2 class="font-medium">Status:</h2>
                                        <span>
                                            <?php 
                                                $status = htmlspecialchars($appointment['status']);
                                                echo $status ? $status : 'Pending'; // Default to "Pending" if status is empty
                                            ?>
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li class="text-gray-600">No Appointments...</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <!---->
                    <div class="max-w-md p-6 mx-auto bg-white rounded-lg shadow-md">

                    </div>
                </div>

        <!-- CALENDAR -->
            <div class="p-3 mt-1 bg-white border-2 rounded-lg dark:border-gray-300">
                <h2 class="mb-4 text-2xl font-bold text-center text-gray-800">
                    <?php echo date('F Y'); ?>
                </h2>
                
                <!-- Days of the Week -->
                <div class="grid grid-cols-7 gap-2 font-semibold text-center text-gray-600">
                    <?php foreach ($daysOfWeek as $day): ?>
                        <div class="p-1 text-white bg-teal-500 rounded-lg"><?php echo $day; ?></div>
                    <?php endforeach; ?>
                </div>

                <!-- Calendar Days -->
                <div class="grid grid-cols-7 gap-2 mt-2 text-center">
                    <?php foreach ($calendar as $week): ?>
                        <?php foreach ($week as $day): ?>
                            <?php 
                                // Check if the date has an appointment
                                $dateStr = $year . '-' . $month . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
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
                                class="relative p-2 rounded-lg cursor-pointer <?php echo $appointmentFound ? 'bg-teal-500 text-white' : 'bg-gray-100'; ?>" 
                                <?php if ($appointmentFound): ?>
                                    onmouseover="showTooltip(this, '<?php echo $tooltipText; ?>')" 
                                    onmouseout="hideTooltip(this)"
                                <?php endif; ?>
                            >
                                <?php echo $day ?: ''; ?>

                                <!-- Tooltip -->
                                <?php if ($appointmentFound): ?>
                                    <div class="absolute hidden p-2 mt-2 text-sm text-white transform -translate-x-1/2 bg-black rounded-lg shadow-md left-1/2 tooltip">
                                        <?php echo $tooltipText; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            </div>

        </section>

    <aside class="col-span-1 space-y-6">

        <!-- PROFILE CARD -->
        <div class="w-full max-w-lg p-5 bg-white border-2 rounded-lg shadow-lg">
            <div class="flex items-center justify-between">
                <h4 class="text-2xl font-bold text-gray-700">PROFILE</h4>
            </div>
            <div class="flex flex-col items-center p-3">
                <img src="/src/images/UMak-Facade-Admin.jpg" alt="Profile Picture"
                    class="w-32 h-32 border-4 border-gray-300 rounded-full">
                <table class="w-full mt-4 text-sm text-center text-gray-800">
                    <tbody>
                        <tr><td class="px-5 py-1"><?php echo htmlspecialchars($student['name']); ?></td></tr>
                        <tr><td class="px-5 py-1"><?php echo htmlspecialchars($student['student_number']); ?></td></tr>
                        <tr><td class="px-5 py-1"><?php echo htmlspecialchars($student['email']); ?></td></tr>
                        <tr><td class="px-5 py-1"><?php echo htmlspecialchars($student['college']); ?></td></tr>
                        <tr><td class="px-5 py-1"><?php echo htmlspecialchars($student['year_level']); ?></td></tr>
                        <tr><td class="px-5 py-1"><?php echo htmlspecialchars($student['section']); ?></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<!-- ANNOUNCEMENT SECTION -->
<section class="col-span-3 p-2 my-5 bg-white border-2 border-gray-300 rounded-lg">
    <h4 class="p-2 text-xl font-semibold text-white bg-teal-500 rounded-lg">ANNOUNCEMENTS</h4>
        <div class="grid grid-cols-1 gap-3 my-3 sm:grid-cols-2 lg:grid-cols-2">
            <?php if (empty($announcements)): ?>
                <p class="text-gray-500 col-span-full">No announcements available.</p>
            <?php else: ?>
                <?php foreach ($announcements as $announcement): ?>
                    <div class="p-6 bg-white rounded-lg shadow-lg">
                        <h3 class="mb-2 text-2xl font-semibold text-blue-800">
                            <?php echo htmlspecialchars($announcement['title']); ?>
                        </h3>
                        <p class="text-gray-700">
                            <?php echo nl2br(htmlspecialchars($announcement['content'])); ?>
                        </p>
                        <p class="mt-2 text-sm text-gray-500">
                            Posted on: <?php echo date('F j, Y, g:i a', strtotime($announcement['published_at'])); ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
</section>
        

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

<!--SCHEDULING CALL TO ACTION-->
<section class="flex items-center justify-center">
    <div class="p-8 text-center rounded-lg shadow-lg">
        <h2 class="mb-4 text-3xl font-semibold text-gray-800">Schedule Your Counseling Appointment</h2>
            <p class="mb-6 text-lg text-gray-600">Taking the first step toward mental well-being is easy. Book an appointment with our counselors today.</p>

        <!-- Call to Action Button -->
        <a href="/src/student/appointment.php" class="inline-block px-6 py-3 text-xl text-white transition duration-300 bg-blue-600 rounded-lg hover:bg-blue-700">Book Your Appointment</a>

        <!-- Optional: Contact Details -->
        <div class="mt-6 text-sm text-gray-500">
            <p>If you need assistance, call us at <strong>(123) 456-7890</strong> or email <strong>support@counseling.com</strong></p>
        </div>
    </div>
</section>

</main>

<!--FOOTER-->
<footer class="overflow-auto bg-gray-100 sm:ml-64 w-75">
    <div class="w-full max-w-screen-xl p-4 py-6 mx-auto lg:py-8 dark:text-gray-800">
        <div class="md:flex md:justify-between">
            <div class="mb-6 md:mb-0">
                <a href="https://flowbite.com/" class="flex items-center">
                    <img src="/src/images/UMAK-CGCS-logo.png" class="h-8 me-3" alt="GuidanceHub Logo" />
                    <span class="self-center text-2xl font-semibold whitespace-nowrap">GuidanceHub<span>
                </a>
            </div>
            <div class="grid grid-cols-2 gap-8 sm:gap-6 sm:grid-cols-3">
                <div>
                    <h2 class="mb-6 text-sm font-semibold text-black uppercase">Resources</h2>
                    <ul class="font-medium text-gray-500 dark:text-gray-400">
                        <li class="mb-4">
                            <a href="https://flowbite.com/" class="hover:underline">GuidanceHub</a>
                        </li>
                        <li>
                            <a href="https://tailwindcss.com/" class="hover:underline">Tailwind CSS</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase">Follow us</h2>
                    <ul class="font-medium text-gray-500 dark:text-gray-400">
                        <li class="mb-4">
                            <a href="https://github.com/themesberg/flowbite" class="hover:underline">Github</a>
                        </li>
                        <li>
                            <a href="https://discord.gg/4eeurUVvTy" class="hover:underline">Discord</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase">Legal</h2>
                    <ul class="font-medium text-gray-500 dark:text-gray-400">
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
        <hr class="my-6 border-gray-200 sm:mx-auto dark:border-gray-300 lg:my-8" />
        <div class="sm:flex sm:items-center sm:justify-between">
            <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2023 <a href="https://flowbite.com/" class="hover:underline">Flowbite™</a>. All Rights Reserved.
            </span>
            <div class="flex mt-4 sm:justify-center sm:mt-0">
                <a href="#" class="text-gray-500 hover:text-gray-900 dark:hover:text-white">
                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 8 19">
                            <path fill-rule="evenodd" d="M6.135 3H8V0H6.135a4.147 4.147 0 0 0-4.142 4.142V6H0v3h2v9.938h3V9h2.021l.592-3H5V3.591A.6.6 0 0 1 5.592 3h.543Z" clip-rule="evenodd"/>
                        </svg>
                    <span class="sr-only">Facebook page</span>
                </a>
                <a href="#" class="text-gray-500 hover:text-gray-900 dark:hover:text-white ms-5">
                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 21 16">
                            <path d="M16.942 1.556a16.3 16.3 0 0 0-4.126-1.3 12.04 12.04 0 0 0-.529 1.1 15.175 15.175 0 0 0-4.573 0 11.585 11.585 0 0 0-.535-1.1 16.274 16.274 0 0 0-4.129 1.3A17.392 17.392 0 0 0 .182 13.218a15.785 15.785 0 0 0 4.963 2.521c.41-.564.773-1.16 1.084-1.785a10.63 10.63 0 0 1-1.706-.83c.143-.106.283-.217.418-.33a11.664 11.664 0 0 0 10.118 0c.137.113.277.224.418.33-.544.328-1.116.606-1.71.832a12.52 12.52 0 0 0 1.084 1.785 16.46 16.46 0 0 0 5.064-2.595 17.286 17.286 0 0 0-2.973-11.59ZM6.678 10.813a1.941 1.941 0 0 1-1.8-2.045 1.93 1.93 0 0 1 1.8-2.047 1.919 1.919 0 0 1 1.8 2.047 1.93 1.93 0 0 1-1.8 2.045Zm6.644 0a1.94 1.94 0 0 1-1.8-2.045 1.93 1.93 0 0 1 1.8-2.047 1.918 1.918 0 0 1 1.8 2.047 1.93 1.93 0 0 1-1.8 2.045Z"/>
                        </svg>
                    <span class="sr-only">Discord community</span>
                </a>
                <a href="#" class="text-gray-500 hover:text-gray-900 dark:hover:text-white ms-5">
                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 17">
                        <path fill-rule="evenodd" d="M20 1.892a8.178 8.178 0 0 1-2.355.635 4.074 4.074 0 0 0 1.8-2.235 8.344 8.344 0 0 1-2.605.98A4.13 4.13 0 0 0 13.85 0a4.068 4.068 0 0 0-4.1 4.038 4 4 0 0 0 .105.919A11.705 11.705 0 0 1 1.4.734a4.006 4.006 0 0 0 1.268 5.392 4.165 4.165 0 0 1-1.859-.5v.05A4.057 4.057 0 0 0 4.1 9.635a4.19 4.19 0 0 1-1.856.07 4.108 4.108 0 0 0 3.831 2.807A8.36 8.36 0 0 1 0 14.184 11.732 11.732 0 0 0 6.291 16 11.502 11.502 0 0 0 17.964 4.5c0-.177 0-.35-.012-.523A8.143 8.143 0 0 0 20 1.892Z" clip-rule="evenodd"/>
                    </svg>
                    <span class="sr-only">Twitter page</span>
                </a>
                <a href="#" class="text-gray-500 hover:text-gray-900 dark:hover:text-white ms-5">
                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 .333A9.911 9.911 0 0 0 6.866 19.65c.5.092.678-.215.678-.477 0-.237-.01-1.017-.014-1.845-2.757.6-3.338-1.169-3.338-1.169a2.627 2.627 0 0 0-1.1-1.451c-.9-.615.07-.6.07-.6a2.084 2.084 0 0 1 1.518 1.021 2.11 2.11 0 0 0 2.884.823c.044-.503.268-.973.63-1.325-2.2-.25-4.516-1.1-4.516-4.9A3.832 3.832 0 0 1 4.7 7.068a3.56 3.56 0 0 1 .095-2.623s.832-.266 2.726 1.016a9.409 9.409 0 0 1 4.962 0c1.89-1.282 2.717-1.016 2.717-1.016.366.83.402 1.768.1 2.623a3.827 3.827 0 0 1 1.02 2.659c0 3.807-2.319 4.644-4.525 4.889a2.366 2.366 0 0 1 .673 1.834c0 1.326-.012 2.394-.012 2.72 0 .263.18.572.681.475A9.911 9.911 0 0 0 10 .333Z" clip-rule="evenodd"/>
                    </svg>
                    <span class="sr-only">GitHub account</span>
                </a>
                <a href="#" class="text-gray-500 hover:text-gray-900 dark:hover:text-white ms-5">
                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 0a10 10 0 1 0 10 10A10.009 10.009 0 0 0 10 0Zm6.613 4.614a8.523 8.523 0 0 1 1.93 5.32 20.094 20.094 0 0 0-5.949-.274c-.059-.149-.122-.292-.184-.441a23.879 23.879 0 0 0-.566-1.239 11.41 11.41 0 0 0 4.769-3.366ZM8 1.707a8.821 8.821 0 0 1 2-.238 8.5 8.5 0 0 1 5.664 2.152 9.608 9.608 0 0 1-4.476 3.087A45.758 45.758 0 0 0 8 1.707ZM1.642 8.262a8.57 8.57 0 0 1 4.73-5.981A53.998 53.998 0 0 1 9.54 7.222a32.078 32.078 0 0 1-7.9 1.04h.002Zm2.01 7.46a8.51 8.51 0 0 1-2.2-5.707v-.262a31.64 31.64 0 0 0 8.777-1.219c.243.477.477.964.692 1.449-.114.032-.227.067-.336.1a13.569 13.569 0 0 0-6.942 5.636l.009.003ZM10 18.556a8.508 8.508 0 0 1-5.243-1.8 11.717 11.717 0 0 1 6.7-5.332.509.509 0 0 1 .055-.02 35.65 35.65 0 0 1 1.819 6.476 8.476 8.476 0 0 1-3.331.676Zm4.772-1.462A37.232 37.232 0 0 0 13.113 11a12.513 12.513 0 0 1 5.321.364 8.56 8.56 0 0 1-3.66 5.73h-.002Z" clip-rule="evenodd"/>
                    </svg>
                    <span class="sr-only">Dribbble account</span>
                </a>
            </div>
        </div>
    </div>
</footer>

<script>
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