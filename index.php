<?php include('E:/GuidanceHub/src/ControlledData/server.php'); ?>
<?php
// Connect to the database
$con = mysqli_connect('localhost', 'root', '', 'guidancehub');

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch articles from the database
$sql = "SELECT id, title, content, published_at FROM announcement ORDER BY published_at DESC";
$result = $con->query($sql);

//Referral System
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $reason = $_POST['reason'];
    $referral_date = date('Y-m-d'); // Current date

    // SQL query to insert referral data
    $sql = "INSERT INTO referrals (student_id, student_name, counselor_name, referral_date, reason) 
            VALUES ('$student_id', '$name', '$counselor_name', '$referral_date', '$reason')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Referral submitted successfully');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="images/x-icon" href="/src/images/UMAK-CGCS-logo.png" />
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GuidanceHub</title>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css"  rel="stylesheet" />
    <script src="https://kit.fontawesome.com/95c10202b4.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>



    <style>
        body::-webkit-scrollbar {
            width: 15px; }
        body::-webkit-scrollbar-track {
            background: #f1f1f1; }
        body::-webkit-scrollbar-thumb {
            background: #888; }
        body::-webkit-scrollbar-thumb:hover {
            background: #555; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100">

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
                <li>
                    <a href="/src/ControlledData/login.php" 
                    class="px-4 py-2 text-white rounded-md bg-cyan-800 hover:bg-cyan-950">Login</a>
                </li>
            </ul>
        </nav>
    </div>
</header>

<!--BANNER-->
<section id="home" class="relative top-0 left-0 flex items-center justify-center mt-16 text-center text-white bg-center bg-cover hero" 
    style="background-image: url('/src/images/UMak-Facade-Admin.jpg'); height: 90vh;">
    <div class="absolute inset-0 bg-slate-800 bg-opacity-60"></div>
    <div class="relative max-w-4xl px-4 py-8 md:px-8">
        <p class="mb-2 text-3xl font-medium max-sm:text-2xl">University of Makati</p>
        <p class="mb-4 text-4xl font-medium max-sm:text-3xl">Center of Guidance and Counseling Services</p>
        <h1 class="font-bold text-yellow-400 text-8xl max-sm:text-6xl">Home of the Brave Herons</h1>
    </div>
</section>

<!--ARTICLES-->
<article class="container px-4 mx-auto my-5">
    <h1 class="text-4xl font-bold text-center underline decoration-yellow-400">Publications, Updates and More!</h1>
    <div class="grid grid-cols-1 gap-6 mt-8 md:grid-cols-2 lg:grid-cols-4">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-md">
                    <h2 class="mb-2 text-xl font-bold text-gray-800"> <?= htmlspecialchars($row['title']); ?> </h2>
                    <p class="mb-4 text-sm text-gray-600">
                        <?= htmlspecialchars(substr($row['content'], 0, 150)); ?>...
                    </p>
                    <p class="text-xs text-gray-400">Published on: <?= date('F j, Y', strtotime($row['published_at'])); ?></p>
                    <a href="article.php?id=<?= $row['id']; ?>" class="inline-block mt-4 font-semibold text-cyan-500">Read More</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center text-gray-500 col-span-full">No articles found.</p>
        <?php endif; ?>
    </div>
</article>

<!--SERVICES-->
<section id="services" class="py-5">
    <div class="container mx-auto text-center">
        <h2 class="mb-5 text-4xl font-bold underline max-sm:text-4xl decoration-yellow-400">SERVICES</h2>
            <div class="grid gap-8 md:grid-cols-3">
                <div class="p-6 bg-white rounded-lg shadow-md">
                    <p>Facilitates and helps students in the achievement of their personal, social, spiritual and academic 
                        development as well as the acquisition of relevant knowledge and skills in career planning.</p>
                </div>
                <div class="p-6 bg-white rounded-lg shadow-md">
                    <p>Promotes holistic development and helps students to become well-adjusted individuals through 
                        responsive guidance and counseling services in line with the year level thrust and the university’s vision-mission.</p>
                </div>
                <div class="p-6 bg-white rounded-lg shadow-md">
                    <p>Creates preventive/proactive guidance programs that lead to the enrichment and satisfaction of students 
                        learning and experiences necessary for a successful and well-balanced student life.</p>
                </div>
            </div>
    </div>
</section>

<!--REFERRAL FORM-->
<section id="referral" class="container px-6 mx-auto my-10">
    <h4 class="p-3 text-2xl font-semibold text-center text-white bg-teal-500 rounded-lg">
        REFERRAL SYSTEM
    </h4>

    <div class="grid gap-6 p-6 my-4 bg-white border-2 rounded-lg dark:border-gray-300 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
        <!-- Faculty Section -->
        <div class="w-full p-6 bg-white border border-gray-200 rounded-lg shadow">
            <h3 class="text-xl font-bold text-gray-700">Faculty</h3>
            <p class="text-gray-600">The Faculty will fill out an Online Referral Form indicating the reason why the student is being referred.</p>
        </div>

        <!-- Guidance Counselor Section -->
        <div class="w-full p-6 bg-white border border-gray-200 rounded-lg shadow">
            <h3 class="text-xl font-bold text-gray-700">Guidance Counselor</h3>
            <p class="text-gray-600">The Guidance Counselor will contact the referred student to schedule a counseling session.</p>
        </div>

        <!-- Student Section -->
        <div class="w-full p-6 bg-white border border-gray-200 rounded-lg shadow">
            <h3 class="text-xl font-bold text-gray-700">Student</h3>
            <p class="text-gray-600">The referred student will meet the guidance counselor through an online platform for an initial interview/counseling and/or psychological assessment if necessary.</p>
        </div>
    </div>

    <!-- Button to open the modal -->
    <div class="flex justify-center mt-6">
        <button onclick="toggleModal()" class="px-6 py-2 text-lg font-semibold text-white bg-blue-500 rounded-md hover:bg-blue-600">
            Referral Form
        </button>
    </div>

    <!-- Modal -->
    <div id="referralModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-gray-800 bg-opacity-50">
        <div class="relative flex w-full max-w-2xl p-6 bg-white rounded-lg shadow-lg">
            <!-- Left side: Referral Form -->
            <div class="w-1/2 pr-4">
                <h2 class="text-2xl font-semibold text-center text-gray-700">Referral Form</h2>
                <form action="submit_referral.php" method="POST" class="mt-4">
                    <div class="mb-4">
                        <label for="student_name" class="block text-sm font-medium text-gray-700">Student Name</label>
                        <input type="text" id="student_name" name="student_name" class="w-full p-2 mt-1 border border-gray-300 rounded-md" required>
                    </div>
                    <div class="mb-4">
                        <label for="student_id" class="block text-sm font-medium text-gray-700">Student ID</label>
                        <input type="text" id="student_id" name="student_id" class="w-full p-2 mt-1 border border-gray-300 rounded-md" required>
                    </div>
                    <div class="mb-4">
                        <label for="reason" class="block text-sm font-medium text-gray-700">Reason for Referral</label>
                        <textarea id="reason" name="reason" rows="4" class="w-full p-2 mt-1 border border-gray-300 rounded-md" required></textarea>
                    </div>
                    <div class="flex justify-center">
                        <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600">
                            Submit Referral
                        </button>
                    </div>
                </form>
                <div class="flex justify-center mt-4">
                    <button onclick="toggleModal()" class="text-gray-500 hover:text-gray-700">Close</button>
                </div>
            </div>
            
            <!-- Right side: Counseling Indicators -->
            <div class="w-1/2 pl-4 border-l border-gray-300">
                <h3 class="text-xl font-semibold text-gray-700">Indicators for Counseling</h3>
                <ul class="mt-2 text-sm text-gray-600 list-disc list-inside">
                    <li>Absenteeism</li>
                    <li>Lack of energy and enthusiasm for studies</li>
                    <li>Abrasive/Aggressive behavior</li>
                    <li>Sleepiness in class</li>
                    <li>Marked changes in personal hygiene</li>
                    <li>Low self-esteem</li>
                    <li>Marked changes in academic performance</li>
                    <li>Talking to self</li>
                    <li>Suicidal attempts</li>
                    <li>Excessive dependency on others</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!--ABOUT-->
<section id="about" class="container px-4 mx-auto my-10">
    <div class="grid items-center grid-cols-1 gap-8 my-2 md:grid-cols-2">
            <img 
                src="/src/images/CGCS-About.jpg" 
                alt="GuidanceHub-AboutUs"  
                class="w-full rounded-lg"
            >
        <div>
            <h3 class="text-4xl font-bold underline decoration-yellow-400">ABOUT US</h3>
                <p class="p-2 mb-8 text-xl text-gray-600">
                    GuidanceHub is committed to providing students with the necessary tools to succeed academically and emotionally. 
                    Our team of professional counselors is here to support your journey through personal growth, mental health, 
                    academic success, and career development.
                </p>
                <a 
                    href="https://www.facebook.com/UMakCGCS" 
                    class="px-6 py-3 mx-3 text-white rounded-full bg-cyan-800 hover:bg-cyan-950">
                    More Details
                </a>
                <a href="javascript:void(0);" onclick="toggleGuidanceStaffModal()" 
            class="px-6 py-3 mx-3 text-white rounded-full bg-cyan-800 hover:bg-cyan-950">
                GuidanceHub Staff
            </a>

            <!-- Modal Background -->
            <div id="guidanceStaffModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-gray-800 bg-opacity-50">
                <!-- Modal Content -->
                <div class="p-6 bg-white rounded-lg shadow-lg w-3/4 h-[600px] overflow-auto mx-auto flex flex-col items-center justify-center overflow-hidden">
                    <h2 class="mb-4 text-2xl font-semibold text-center text-gray-700">Guidance Staff</h2>
                    <div class="overflow-auto max-w-full h-[400px] w-full">
                        <table class="w-full text-center border border-collapse border-gray-300">
                            <thead>
                                <tr class="bg-gray-200">
                                    <th class="px-4 py-2 border border-gray-300">Staff</th>
                                    <th class="px-4 py-2 border border-gray-300">Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td class="px-4 py-2 border">Prof. Ryan C. Villanueva, MAEd, RGC, LPT, CLDP, CHRA</td><td class="px-4 py-2 border">Director</td></tr>
                                <tr><td class="px-4 py-2 border">Karen M. Rico, MAEd, RGC</td><td class="px-4 py-2 border">Guidance Counselor</td></tr>
                                <tr><td class="px-4 py-2 border">Gichelle Hanna C. Roxas, MAEd, RPm</td><td class="px-4 py-2 border">Guidance Counselor</td></tr>
                                <tr><td class="px-4 py-2 border">Ma. Romanita C. De Borja, RPm, LPT</td><td class="px-4 py-2 border">Guidance Coordinator</td></tr>
                                <tr><td class="px-4 py-2 border">Bowie L. Bello, RPm</td><td class="px-4 py-2 border">Guidance Coordinator</td></tr>
                                <tr><td class="px-4 py-2 border">Estella O. Obnamia, MP, RPm, LPT</td><td class="px-4 py-2 border">Guidance Coordinator</td></tr>
                                <tr><td class="px-4 py-2 border">Aiko B. Caguioa</td><td class="px-4 py-2 border">Guidance Coordinator</td></tr>
                                <tr><td class="px-4 py-2 border">Carolyn S.M. Balsamo, RSW</td><td class="px-4 py-2 border">IEGAD Coordinator</td></tr>
                                <tr><td class="px-4 py-2 border">Janella M. Largadas, CHRA</td><td class="px-4 py-2 border">Guidance Secretary/Coordinator</td></tr>
                                <tr><td class="px-4 py-2 border">Dr. Evangeline M. Alayon, RGC, LPT</td><td class="px-4 py-2 border">Associate Guidance Counselor</td></tr>
                                <tr><td class="px-4 py-2 border">Dr. Lucia B. Dela Cruz, RGC</td><td class="px-4 py-2 border">Associate Guidance Counselor</td></tr>
                                <tr><td class="px-4 py-2 border">Prof. Kim Patrick Magdangan, MAEd, RGC</td><td class="px-4 py-2 border">Associate Guidance Counselor</td></tr>
                                <tr><td class="px-4 py-2 border">Dr. Francisco M. Lambojon, Jr., RPsy</td><td class="px-4 py-2 border">Associate Psychologist</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Close Button -->
                    <div class="flex justify-center mt-4">
                        <button onclick="toggleGuidanceStaffModal()" class="px-4 py-2 text-white bg-red-500 rounded-md hover:bg-red-600">
                            Close
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
    
    <div class="flex flex-wrap justify-between w-3/4 p-6 m-4 mx-auto bg-white rounded-lg shadow-lg">
        <div class="w-1/3 px-4 text-center">
            <h2 class="text-3xl font-bold text-gray-800">Vision</h2>
            <p class="mt-2 text-gray-600">
                We envision the Center to be the heart of the university where every student shall be empowered in realizing their fullest educational potential as lifelong learners with good moral values who are committed to excellence.
            </p>
        </div>
        
        <div class="w-1/3 px-4 text-center">
            <h2 class="text-3xl font-bold text-gray-800">Mission</h2>
            <p class="mt-2 text-gray-600">
                The Center shall develop a comprehensive program that will provide life skills enhancement and psychological support that will equip the students to be productive, well-balanced, responsible and competent members of society. The Center shall promote and strengthen the holistic development of students.
            </p>
        </div>
        
        <div class="w-1/3 px-4 text-center">
            <h2 class="text-3xl font-bold text-gray-800">Core Values</h2>
            <ul class="mt-4 space-y-2">
                <li class="font-semibold text-gray-600">❤️ Love</li>
                <li class="font-semibold text-gray-600">📚 Wisdom</li>
                <li class="font-semibold text-gray-600">🛡️ Integrity</li>
            </ul>
        </div>
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
    //Toggle menu
        const menuToggle = document.getElementById('menu-toggle');
        const menu = document.getElementById('menu');

        menuToggle.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });

    //Toggle modal visibility
        function toggleModal() {
            const modal = document.getElementById('referralModal');
            modal.classList.toggle('hidden');
        }

    //Toggle the popover visibility
        document.addEventListener('DOMContentLoaded', function () {
            const button = document.querySelector('button[data-popover-target]');
            const popover = document.getElementById(button.getAttribute('data-popover-target'));

            button.addEventListener('click', function () {
                popover.classList.toggle('invisible');
                popover.classList.toggle('opacity-0');
                popover.classList.toggle('opacity-100');
            });

            // Optional: Close the popover if clicked outside
            window.addEventListener('click', function (event) {
                if (!popover.contains(event.target) && !button.contains(event.target)) {
                    popover.classList.add('invisible', 'opacity-0');
                    popover.classList.remove('opacity-100');
                }
            });
        });

    //Toggle for link tab section slide
        document.querySelectorAll('.scroll-link').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);

                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

    //Toggle for Guidance Staff Modal
        function toggleGuidanceStaffModal() {
        const modal = document.getElementById('guidanceStaffModal');
        modal.classList.toggle('hidden');
    }
    </script>
</body>
</html>
