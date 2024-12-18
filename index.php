<?php include('E:/GuidanceHub/src/entry-page/server.php'); ?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // Process the data (save to database, send email, etc.)
    // For demonstration, we just echo the data
    echo "<h1>Thank you for contacting us, $name!</h1>";
    echo "<p>Your message has been received. We will get back to you soon.</p>";
    echo "<p><strong>Message:</strong> $message</p>";
    echo "<p><strong>Contact Email:</strong> $email</p>";
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
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- JavaScript for Toggle Menu -->
    <script>
        const menuToggle = document.getElementById('menu-toggle');
        const menu = document.getElementById('menu');

        menuToggle.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    </script>

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
    
<!-- Header Section -->
<header class="py-4 bg-yellow-400 shadow-md">
    <div class="container flex items-center justify-between px-4 mx-auto md:px-8">
        <!-- Logo -->
        <div class="flex items-center space-x-4">
            <img src="/src/images/UMAK-CGCS-logo.png" alt="CGCS Logo" class="w-10 h-auto md:w-14">
            <span class="text-lg font-bold md:text-xl">GuidanceHub</span>
        </div>

        <!-- Hamburger Icon -->
        <button id="menu-toggle" class="block md:hidden">
            <svg class="w-6 h-6 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
        </button>

        <!-- Navigation Menu -->
        <nav id="menu" class="hidden md:flex">
            <ul class="flex flex-col space-y-2 text-lg font-semibold md:flex-row md:space-x-6 md:space-y-0">
                <li><a href="#home" class="text-gray-700 hover:text-blue-600">Home</a></li>
                <li><a href="#services" class="text-gray-700 hover:text-blue-600">Services</a></li>
                <li><a href="#about" class="text-gray-700 hover:text-blue-600">About</a></li>
                <li><a href="#contact" class="text-gray-700 hover:text-blue-600">Contact</a></li>
                <li>
                    <a href="/src/entry-page/login.php" 
                    class="px-4 py-2 text-white bg-black rounded-md hover:bg-gray-800"> Login
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</header>

<!-- Hero Section -->
<section id="home" class="relative flex items-center justify-center text-center text-white bg-center bg-cover hero" 
    style="background-image: url('/src/images/UMak-Facade-Admin.jpg'); height: 75vh;">
    <div class="absolute inset-0 bg-slate-800 bg-opacity-60"></div>
    <div class="relative z-10 max-w-2xl px-4 py-8 md:px-8">
        <p class="mb-4 text-2xl font-medium sm:text-3xl">Center of Guidance and Counseling Services</p>
        <h1 class="text-5xl font-bold sm:text-7xl">Home of the Brave Herons</h1>
    </div>
</section>

<!-- Services Section -->
<section id="services" class="py-16 bg-gray-50" style="background-color: #1EB0A9">
    <div class="container mx-auto text-center">
        <h2 class="mb-8 text-4xl font-semibold">Our Services</h2>
            <div class="grid gap-8 md:grid-cols-3">
                <div class="p-6 bg-white rounded-lg shadow-md">
                    <h3 class="mb-4 text-xl font-semibold">Personal Counseling</h3>
                    <p>We provide one-on-one counseling to help students overcome personal challenges and achieve mental well-being.</p>
                </div>
                <div class="p-6 bg-white rounded-lg shadow-md">
                    <h3 class="mb-4 text-xl font-semibold">Academic Assistance</h3>
                    <p>Get help with your academic decisions, study techniques, and career planning from our expert counselors.</p>
                </div>
                <div class="p-6 bg-white rounded-lg shadow-md">
                    <h3 class="mb-4 text-xl font-semibold">Career Guidance</h3>
                    <p>Explore career opportunities and get professional advice to shape your future career path.</p>
                </div>
            </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="py-16 bg-gray-100">
    <div class="container px-4 mx-auto sm:px-8">
        <h2 class="mb-8 text-3xl font-semibold text-center">Contact Us</h2>
        <form method="POST" action="contact_form.php" class="max-w-lg mx-auto space-y-6">
            <div>
                <label for="name" class="block text-lg">Name</label>
                <input type="text" id="name" name="name" class="w-full p-3 border border-gray-300 rounded-lg" required>
            </div>
            <div>
                <label for="email" class="block text-lg">Email</label>
                <input type="email" id="email" name="email" class="w-full p-3 border border-gray-300 rounded-lg" required>
            </div>
            <div>
                <label for="message" class="block text-lg">Message</label>
                <textarea id="message" name="message" rows="4" class="w-full p-3 border border-gray-300 rounded-lg" required></textarea>
            </div>
            <button type="submit" class="w-full py-3 text-lg text-white bg-blue-600 rounded-lg hover:bg-blue-800">Send Message</button>
        </form>
    </div>
</section>

<!-- About Section -->
<section id="about" class="py-16 bg-white">
    <div class="container mx-auto text-center">
        <h2 class="mb-8 text-3xl font-semibold">About Us</h2>
            <p class="max-w-4xl mx-auto text-lg">
                GuidanceHub is committed to providing students with the necessary tools to succeed academically and emotionally. Our team of professional counselors is here to support your journey through personal growth, mental health, academic success, and career development.
            </p>
    </div>
</section>

<!--FOOTER-->
<footer class="w-full bg-white">
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
                    <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase">Resources</h2>
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
        <hr class="my-6 border-gray-200 sm:mx-auto dark:border-gray-700 lg:my-8" />
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

</body>
</html>
