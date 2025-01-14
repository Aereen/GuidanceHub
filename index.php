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
    $counselor_name = $_POST['counselor_name'];
    $reason = $_POST['reason'];
    $referral_date = date('Y-m-d'); // Current date

    // SQL query to insert referral data
    $sql = "INSERT INTO referrals (student_id, counselor_name, referral_date, reason) 
            VALUES ('$student_id', '$counselor_name', '$referral_date', '$reason')";

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
<header class="sticky top-0 z-50 py-4 shadow-md" style="background-color: #1EB0A9">
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
<section id="home" class="relative flex items-center justify-center text-center text-white bg-center bg-cover hero" 
    style="background-image: url('/src/images/UMak-Facade-Admin.jpg'); height: 90vh;">
    <div class="absolute inset-0 bg-slate-800 bg-opacity-60"></div>
    <div class="relative z-10 max-w-4xl px-4 py-8 md:px-8">
        <p class="mb-2 text-3xl font-medium max-sm:text-2xl">University of Makati</p>
        <p class="mb-4 text-4xl font-medium max-sm:text-3xl">Center of Guidance and Counseling Services</p>
        <h1 class="font-bold text-yellow-400 text-8xl max-sm:text-6xl">Home of the Brave Herons</h1>
    </div>
</section>

<!--SERVICES-->
<section id="services" class="py-5">
    <div class="container mx-auto text-center">
        <h2 class="mb-5 text-4xl font-bold underline max-sm:text-4xl decoration-yellow-400">SERVICES</h2>
            <div class="grid gap-8 md:grid-cols-3">
                <div class="p-6 bg-white rounded-lg shadow-md">
                    <h3 class="mb-4 text-2xl font-semibold">Personal Counseling</h3>
                    <p>We provide one-on-one counseling to help students overcome personal challenges and achieve mental well-being.</p>
                </div>
                <div class="p-6 bg-white rounded-lg shadow-md">
                    <h3 class="mb-4 text-2xl font-semibold">Academic Assistance</h3>
                    <p>Get help with your academic decisions, study techniques, and career planning from our expert counselors.</p>
                </div>
                <div class="p-6 bg-white rounded-lg shadow-md">
                    <h3 class="mb-4 text-2xl font-semibold">Career Guidance</h3>
                    <p>Explore career opportunities and get professional advice to shape your future career path.</p>
                </div>
            </div>
    </div>
</section>

<!--ARTICLES---> <!--CREATE A CONTENT MANAGER OF ARTICLES IN ADMIN-->
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

<!--ABOUT-->
<section id="about" class="container px-4 mx-auto my-10">
    <div class="grid items-center grid-cols-1 gap-8 md:grid-cols-2">
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
                    class="px-6 py-3 text-white rounded-full bg-cyan-800 hover:bg-cyan-950">
                    More Details
                </a>
        </div>
    </div>
</section>

<!--GET TO KNOW US-->
<section>

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
    </script>
</body>
</html>
