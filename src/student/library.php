<?php include('E:/GuidanceHub/src/ControlledData/server.php'); ?>
<?php
// Connect to the database
$con = mysqli_connect('localhost', 'root', '', 'guidancehub');

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT * FROM library_resources ORDER BY created_at DESC";
$result = $con->query($sql);

// Initialize variables and error array
$errors = array(); 

// Check if logout is requested
if (isset($_GET['logout'])) {
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session
    header("Location: /index.php"); // Redirect to the login page after logout
    exit;
}
?>

<!doctype html>
<html>
<head>
<title>GuidanceHub | Library</title>
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
<div class="w-full p-4 mt-20">
<h2 class="p-3 my-2 text-4xl font-bold"> Resource Library </h2>
    <h4 class="p-2 text-xl font-semibold text-white bg-teal-500 rounded-lg"> Learn about yourself with... </h4>
    <main class="my-5">
    <div class="flex justify-center mb-8">
            <input type="text" id="search" placeholder="Search for resources..." class="w-1/3 p-2 border border-gray-300 rounded">
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="overflow-hidden bg-white rounded-lg shadow-lg">';
                    echo '  <div class="p-4">';
                    echo '      <h2 class="mb-2 text-xl font-semibold">' . $row['title'] . '</h2>';
                    echo '      <p class="mb-4 text-gray-700">' . substr($row['description'], 0, 100) . '...</p>';
                    echo '      <a href="' . $row['resource_link'] . '" class="text-blue-600 hover:underline">Read more</a>';
                    echo '  </div>';
                    echo '</div>';
                }
            } else {
                echo '<p class="col-span-4 text-center text-gray-600">No resources available at the moment.</p>';
            }

            $con->close();
            ?>
        </div>
    </main>
</div>

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

//Toggle for Notification
const notificationButton = document.getElementById('notificationButton');
    const notificationDropdown = document.getElementById('notificationDropdown');
    const notificationBadge = document.getElementById('notificationBadge');
    const notificationList = document.getElementById('notificationList');
    const markReadButton = document.getElementById('markReadButton');

    // Sample notifications array
    let notifications = [
        "Your appointment has been confirmed.",
        "New message from the guidance office.",
        "Reminder: Your appointment is tomorrow at 10:00 AM."
    ];

    // Function to display notifications
    function updateNotifications() {
        if (notifications.length > 0) {
            notificationBadge.textContent = notifications.length;
            notificationBadge.classList.remove('hidden');
            markReadButton.classList.remove('hidden');

            // Update the dropdown list
            notificationList.innerHTML = notifications.map(
                (notif) => `<li class="p-2 bg-gray-100 rounded hover:bg-gray-200">${notif}</li>`
            ).join('');
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
        notifications = []; // Clear notifications array
        updateNotifications(); // Update the UI
    });

    // Initialize notifications on page load
    updateNotifications();

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
</script>
<script src="../path/to/flowbite/dist/flowbite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</body>
</html>