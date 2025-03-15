
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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

    </style>
</head>
<body class="font-sans antialiased bg-gray-100">

<!--HEADER-->
<header class="fixed top-0 left-0 z-50 w-full py-4 shadow-xl marcellus-regular" style="background-color: #1EB0A9">
    <div class="flex items-center justify-between px-4 mx-auto container-fluid md:px-8">
        <!-- Logo -->
        <div class="flex items-center space-x-3">
            <a href="https://www.umak.edu.ph/" class="flex items-center space-x-3">
                <img src="/src/images/UMAK-logo.png" alt="UMAK Logo" class="w-10 h-auto md:w-14">
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

<!--BANNER-->
<section id="home" class="relative top-0 left-0 flex items-center justify-center mt-16 text-center text-white bg-center bg-cover marcellus-regular hero" 
    style="background-image: url('/src/images/UMak-Facade-Admin.jpg'); height: 90vh;">
    <div class="absolute inset-0 bg-slate-800 bg-opacity-60"></div>
    <div class="relative max-w-4xl px-4 py-8 md:px-8">
        <p class="mb-2 text-3xl font-medium max-sm:text-2xl">University of Makati</p>
        <p class="mb-4 text-4xl font-medium max-sm:text-3xl">Center of Guidance and Counseling Services</p>
        <h1 class="font-bold text-yellow-400 text-8xl max-sm:text-6xl">Home of the Brave Herons</h1>
    </div>
</section>

<main>

<!--INFORMATION-->
<section class="h-auto bg-yellow-300">
    <div class="grid grid-cols-1 gap-1 md:grid-cols-2 justify-items-center">
        <div>
            <img src="/src/images/UMAK-CGCS-logo.png" alt="CGCS Logo" class="my-3 w-50 h-50">
        </div>
        <div class="p-4 m-3 mx-auto place-content-center">
            <div class="flex items-center justify-center w-full text-4xl font-bold text-center text-gray-800">GuidanceHub: Center for Guidance and Counseling Services</div>
            <div class="my-3 text-lg font-medium text-justify text-blue-900 md:mx-10"> An integral part of the university which commits to develop and deliver comprehensive guidance 
                programs that will holistically empower students with good moral values and right attitudes geared toward 
                academic excellence and remarkable achievements for the good of our society.</div>
            <div class="flex justify-center">
                <a href="https://www.umak.edu.ph/student/guidance/" class="w-48 h-10 pt-1 text-lg font-medium text-center text-white border rounded-lg bg-cyan-800 border-cyan-800 justify-items-center focus:ring-6 focus:outline-none dark:focus:ring-cyan-800 hover:bg-transparent hover:text-cyan-800">
                    Learn More
                </a>
            </div>
        </div>
    </div>
</section>

<!-- SERVICES -->
<section class="px-10 pt-5 mt-8">
<h2 class="text-4xl font-bold text-center underline underline-offset-4 decoration-yellow-400">OUR SERVICES</h2>
    <p class="p-5 text-xl text-center text-gray-600">
        Facilitates students' personal, social, spiritual, and academic development, and career planning. <br> 
        Promotes holistic growth through responsive guidance and counseling services aligned with the university’s vision-mission.
    </p>
        <div class="grid grid-cols-1 gap-8 p-5 md:grid-cols-2">
            <div class="relative overflow-hidden rounded-lg shadow-lg">
                <img src="/src/images/counseling.jpg" alt="Counseling" class="object-cover w-full h-48">
                <div class="absolute inset-0 flex flex-col justify-end p-4 bg-black bg-opacity-50">
                    <h3 class="text-xl font-bold text-white">Counseling Appointment</h3>
                    <a href="/src/ControlledData/appointment.php" class="inline-block px-3 py-2 mt-2 font-medium text-white bg-blue-600 rounded-lg text-md hover:bg-blue-700">Schedule Appointment</a>
                </div>
            </div>
            
            <div class="relative overflow-hidden rounded-lg shadow-lg">
                <img src="/src/images/referral.jpg" alt="Referral" class="object-cover w-full h-48">
                <div class="absolute inset-0 flex flex-col justify-end p-4 bg-black bg-opacity-50">
                    <h3 class="text-xl font-bold text-white">Referral System</h3>
                    <a href="/src/ControlledData/referral.php" class="inline-block px-3 py-2 mt-2 font-medium text-white bg-blue-600 rounded-lg text-md hover:bg-blue-700">Refer in Need</a>
                </div>
            </div>
            
            <div class="relative overflow-hidden rounded-lg shadow-lg">
                <img src="/src/images/inventory.jpg" alt="Inventory" class="object-cover w-full h-48">
                <div class="absolute inset-0 flex flex-col justify-end p-4 bg-black bg-opacity-50">
                    <h3 class="text-xl font-bold text-white">Individual Inventory</h3>
                    <a href="/src/ControlledData/information.php" class="inline-block px-3 py-2 mt-2 font-medium text-white bg-blue-600 rounded-lg text-md hover:bg-blue-700">Answer Here</a>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-lg shadow-lg">
                <img src="/src/images/assessment.jpg" alt="Assessment" class="object-cover w-full h-48">
                <div class="absolute inset-0 flex flex-col justify-end p-4 bg-black bg-opacity-50">
                    <h3 class="text-xl font-bold text-white">Assessment</h3>
                    <a href="/src/ControlledData/assessment.php" class="inline-block px-3 py-2 mt-2 font-medium text-white bg-blue-600 rounded-lg text-md hover:bg-blue-700">Assess Yourself</a>
                </div>
            </div>
        </div>
</section>

<!--ARTICLES-->
<article class="container px-4 mx-auto my-8">
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
<section id="about" class="container px-4 mx-auto">
    <div class="grid items-center grid-cols-1 gap-8 my-3 md:grid-cols-2">
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
            <div class="flex flex-wrap justify-center gap-4">
                <a href="https://www.facebook.com/UMakCGCS" 
                    class="w-full px-6 py-3 text-lg font-medium text-center text-white border rounded-lg bg-cyan-800 border-cyan-800 focus:ring-4 focus:outline-none dark:focus:ring-cyan-800 hover:bg-transparent hover:text-cyan-800 sm:w-auto">
                    More Details
                </a>
                <a href="javascript:void(0);" onclick="toggleGuidanceStaffModal()" 
                    class="w-full px-6 py-3 text-lg font-medium text-center text-white border rounded-lg bg-cyan-800 border-cyan-800 focus:ring-4 focus:outline-none dark:focus:ring-cyan-800 hover:bg-transparent hover:text-cyan-800 sm:w-auto">
                    GuidanceHub Staff
                </a>
            </div>


            <!-- Modal Background -->
            <div id="guidanceStaffModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-gray-800 bg-opacity-50">
                <!-- Modal Content -->
                <div class="p-6 bg-white rounded-lg shadow-lg w-3/4 h-[700px] overflow-auto mx-auto flex flex-col items-center justify-center overflow-hidden">
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
    
    <!-- MISSION, VISION, CORE VALUES -->
    <div class="max-w-4xl px-6 py-12 mx-auto my-10">
        <ol class="relative border-gray-200 border-s dark:border-gray-700">
            
            <!-- Vision -->
            <li class="mb-10 ms-4">
                <div class="absolute w-3 h-3 bg-yellow-400 rounded-full mt-1.5 -start-1.5 border border-white"></div>
                <h1 class="text-4xl font-semibold text-gray-900">Vision</h1>
                <p class="mt-2 text-xl text-gray-600">
                    We envision the Center to be the heart of the university where every student shall be empowered in realizing their fullest educational potential as lifelong learners with good moral values who are committed to excellence.
                </p>
            </li>

            <!-- Mission -->
            <li class="mb-10 ms-4">
                <div class="absolute w-3 h-3 bg-yellow-400 rounded-full mt-1.5 -start-1.5 border border-white"></div>
                <h1 class="text-4xl font-semibold text-gray-900">Mission</h1>
                <p class="mt-2 text-xl text-gray-600">
                    The Center shall develop a comprehensive program that will provide life skills enhancement and psychological support that will equip the students to be productive, well-balanced, responsible, and competent members of society.
                </p>
            </li>

            <!-- Core Values -->
            <li class="ms-4">
                <div class="absolute w-3 h-3 bg-yellow-400 rounded-full mt-1.5 -start-1.5 border border-white"></div>
                <h1 class="text-4xl font-semibold text-gray-900">Core Values</h1>
                <ul class="mt-2 text-xlspace-y-2">
                    <li class="text-gray-600">❤️ Love</li>
                    <li class="text-gray-600">📚 Wisdom</li>
                    <li class="text-gray-600">🛡️ Integrity</li>
                </ul>
            </li>

        </ol>
    </div>

</section>

<!--CONTACT INFORMATION
<section class="bg-gray-100 p-7">
    <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-3xl font-bold text-gray-800">Contact Information</h2>
            <p class="text-xl font-semibold text-gray-700">Prof. Ryan C. Villanueva, MAEd, RGC, LPT</p>
            <p class="text-lg text-gray-600">Director</p>
            <p class="mt-2 text-lg text-gray-600">
                E-mail: <a href="mailto:gcc@umak.edu.ph" class="text-blue-600 hover:underline">gcc@umak.edu.ph</a>
            </p>
            <p class="mt-4 text-lg text-gray-700">
                <strong>Office Location (Temporary):</strong> <br> 
                8th Floor, Health and Physical Science Building
            </p>
    </div>
</section> -->

</main>

<!--FOOTER-->
<footer class="w-full mt-5" style="background-color: #1EB0A9">
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
