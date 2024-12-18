<?php include('E:/GuidanceHub/src/entry-page/server.php'); ?>
<?php
// Connect to the database
$con = mysqli_connect('localhost', 'root', '', 'guidancehub');

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize variables and error array
$errors = array(); 

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the assessment type (assuming you have a hidden input or identifier for this)
    $assessment_type = $_POST['assessment_type'];

    // Loop through all the questions
    foreach ($_POST as $key => $value) {
        // Assuming your question names are like 'question_0', 'question_1', etc.
        if (strpos($key, 'question_') !== false) {
            // Get the question number from the key
            $question_id = str_replace('question_', '', $key);
            $answer = $value; // User's response

            // Prepare and bind the query to insert the response into the database
            $stmt = $con->prepare("INSERT INTO survey_responses (question_id, answer, assessment_type) VALUES (?, ?, ?)");
            if ($stmt === false) {
                die('MySQL prepare error: ' . $con->error);
            }
            $stmt->bind_param("iss", $question_id, $answer, $assessment_type);

            // Execute the query
            $stmt->execute();
        }
    }

    // Close the statement and connection
    $stmt->close();
    $con->close();

    // Redirect or show success message
    echo "Thank you for submitting your responses!";
}

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
<title>GuidanceHub | Assessment</title>
    <link rel="icon" type="images/x-icon" href="/src/images/UMAK-CGCS-logo.png" />
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css"  rel="stylesheet" />
        <script src="https://kit.fontawesome.com/95c10202b4.js" crossorigin="anonymous"></script>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="./output.css" rel="stylesheet">   

    <script>
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.remove('hidden');
        modal.classList.add('bg-opacity-50');
        modal.classList.add('opacity-100');

        // Show the modal
        document.getElementById(modalId).classList.remove("hidden");

        // Disable scroll on body
        document.body.style.overflow = "hidden";
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.add('hidden');
        modal.classList.remove('bg-opacity-50');
        modal.classList.remove('opacity-100');

         // Hide the modal
        document.getElementById(modalId).classList.add("hidden");

        // Enable scroll on body
        document.body.style.overflow = "auto";
    }
    </script>

</head>
<body>
<!--TOP NAVIGATION BAR-->
<nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:border-gray-300">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start rtl:justify-end">
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
            <div class="flex items-center justify-end">
            </div>
        </div>
    </div>
</nav>

<!--SIDE NAVIGATION MENU-->
<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full border-r dark:border-gray-300 sm:translate-x-0" aria-label="Sidebar">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:border-gray-300">
        <ul class="space-y-2 font-medium">
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
                <a href="profile.php" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
                <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                    <i class="fa-solid fa-user"></i>
                </svg>
                <span class="flex-1 ms-3 whitespace-nowrap">Profile</span>
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
<div class="p-4 mt-10 sm:ml-64">
    <h2 class="p-3 my-2 text-4xl font-bold tracking-tight">Available Assessment</h2>
        <div class="p-5 bg-white">
            <ul class="divide-y divide-gray-200 dark:divide-gray-400">
                <!-- Career Interest Assessment -->
                <li class="flex items-center justify-between py-4">
                    <span class="text-gray-800">Career Interest Assessment</span>
                    <button 
                        class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600"
                        onclick="openModal('careerInterestModal')"
                    > Take Test
                    </button>
                </li>
                <!-- Psychological Assessment -->
                <li class="flex items-center justify-between py-4">
                    <span class="text-gray-800">Psychological Assessment</span>
                    <button 
                        class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600"
                        onclick="openModal('psychologicalModal')"
                    > Take Test
                    </button>
                </li>
                <!-- Academic Stress Test -->
                <li class="flex items-center justify-between py-4">
                    <span class="text-gray-800">Academic Stress Test</span>
                    <button 
                        class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600"
                        onclick="openModal('academicStressModal')"
                    > Take Test
                    </button>
                </li>
                <!-- Personal Growth Inventory -->
                <li class="flex items-center justify-between py-4">
                    <span class="text-gray-800">Personal Growth Inventory</span>
                    <button 
                        class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600"
                        onclick="openModal('personalGrowthModal')"
                    > Take Test
                    </button>
                </li>
            </ul>
        </div>
</div>

<!-- MODALS -->
    <!-- Career Interest Assessment Modal -->
    <div id="careerInterestModal" 
        class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-50">
        <div class="relative p-6 bg-white rounded shadow-lg max-h-[90vh] w-[80vw] overflow-y-auto">
            <!-- Exit Button -->
            <button 
                class="absolute top-0 right-0 m-5 text-gray-500 hover:text-red-600"
                onclick="closeModal('careerInterestModal')">
                    <i class="text-xl fa-solid fa-circle-xmark"></i>
            </button>
            <section>
                <h2 class="mb-4 text-2xl font-bold">Career Interest Assessment</h2>
                <p class="mb-4 text-lg">Take the Career Interest Assessment to explore your career preferences.</p>

                <!-- Survey Questions -->
                <form action="#" method="POST" class="space-y-6">
                    <input type="hidden" name="assessment_type" value="Career Interest">
                    <?php
                    $questions = [
                        "Inspect a roof for leaks",
                        "Use precision machines to build custom metal parts",
                        "Analyze the structure of molecules",
                        "Do scientific experiments",
                        "Design magazine cover",
                        "Paint a portrait",
                    ];

                    foreach ($questions as $index => $question) {
                        echo '
                            <div class="p-4 rounded-lg shadow-sm bg-gray-50">
                                <p class="mb-2 text-xl font-semibold text-center text-gray-800">' . ($index + 1) . '. ' . $question . '</p>
                                <div class="flex justify-center space-x-6">
                                    <label class="flex items-center">
                                        <input type="radio" name="question_' . $index . '" value="dislike" class="text-blue-500 form-radio" required>
                                        <span class="ml-2">DISLIKE</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="question_' . $index . '" value="neutral" class="text-blue-500 form-radio" required>
                                        <span class="ml-2">NEUTRAL</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="question_' . $index . '" value="like" class="text-blue-500 form-radio" required>
                                        <span class="ml-2">LIKE</span>
                                    </label>
                                </div>
                            </div>';
                    }
                    ?>
                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit" class="px-6 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600">
                            Submit
                        </button>
                    </div>
                </form>
            </section>
        </div>
    </div>

    <!-- Psychological Assessment Modal -->
    <div id="psychologicalModal" 
        class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-50">
        <div class="relative p-6 bg-white rounded shadow-lg max-h-[90vh] w-[80vw] overflow-y-auto">
            <!-- Exit Button -->
            <button 
                class="absolute top-0 right-0 m-5 text-gray-500 hover:text-red-600"
                onclick="closeModal('psychologicalModal')">
                    <i class="text-xl fa-solid fa-circle-xmark"></i>
            </button>
            <section>
                <h2 class="mb-4 text-2xl font-bold">Psychological Assessment</h2>
                <p class="mb-4 text-lg">Take the Psychological Assessment to evaluate your mental health.</p>

                <!-- Survey Questions -->
                <form action="#" method="POST" class="space-y-6">
                    <input type="hidden" name="assessment_type" value="Psychological">
                    <?php
                    $questions = [
                        "I fell overwhelmed by my emotions",
                        "I am able to handle the level of stress I experience",
                        "I have physical symptoms of anxiety, such as sweaty palms",
                        "I have strong relationship with people I care about",
                        "I made many terrible decisions in my life",
                        "I am very self-critical",
                        "I cannot get beyond long-past traumatic events or significant losses",
                        "I am able to identify and express my emotions",
                        "I trust that if I confide in others, they will be supportive",
                        "I engage in at least one behavior that significantly impairs my ability to function on a daily basis"
                    ];

                    // Dynamically render questions
                    foreach ($questions as $index => $question) {
                        echo '
                        <div class="p-4 rounded-lg shadow-sm bg-gray-50">
                            <p class="mb-2 text-xl font-semibold text-center text-gray-800">' . ($index + 1) . '. ' . $question . '</p>
                            <div class="flex justify-center space-x-6">
                                <label class="flex items-center">
                                    <span class="mr-2">DISLIKE</span>
                                    <input type="radio" name="question_' . $index . '" value="dislike" class="text-blue-500 form-radio">
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="question_' . $index . '" value="neutral" class="text-blue-500 form-radio">
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="question_' . $index . '" value="like" class="text-blue-500 form-radio">
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="question_' . $index . '" value="like" class="text-blue-500 form-radio">
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="question_' . $index . '" value="dislike" class="text-blue-500 form-radio">
                                    <span class="ml-2">LIKE</span>
                                </label>
                            </div>
                        </div>
                        ';
                    }
                    ?>
                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit" class="px-6 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600">
                            Submit
                        </button>
                    </div>
                </form>
            </section>
        </div>
    </div>

    <!-- Academic Stress Test Modal -->
    <div id="academicStressModal" 
        class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-50">
        <div class="relative p-6 bg-white rounded shadow-lg max-h-[90vh] w-[80vw] overflow-y-auto">
            <!-- Exit Button -->
            <button 
                class="absolute top-0 right-0 m-5 text-gray-500 hover:text-red-600"
                onclick="closeModal('academicStressModal')">
                    <i class="text-xl fa-solid fa-circle-xmark"></i>
            </button>
            <section>
                <h2 class="mb-4 text-2xl font-bold">Academic Stress Test</h2>
                <p class="mb-4 text-lg">Take the Academic Stress Test to evaluate your academic workload.</p>
                <!-- Survey Questions -->
                <form action="#" method="POST" class="space-y-6">
                    <input type="hidden" name="assessment_type" value="Academic Stress">
                    <?php
                    $questions = [
                        "I fell overwhelmed by my emotions",
                        "I am able to handle the level of stress I experience",
                        "I have physical symptoms of anxiety, such as sweaty palms",
                        "I have strong relationship with people I care about",
                        "I made many terrible decisions in my life",
                        "I am very self-critical",
                        "I cannot get beyond long-past traumatic events or significant losses",
                        "I am able to identify and express my emotions",
                        "I trust that if I confide in others, they will be supportive",
                        "I engage in at least one behavior that significantly impairs my ability to function on a daily basis"
                    ];

                    // Dynamically render questions
                    foreach ($questions as $index => $question) {
                        echo '
                        <div class="p-4 rounded-lg shadow-sm bg-gray-50">
                            <p class="mb-2 text-xl font-semibold text-center text-gray-800">' . ($index + 1) . '. ' . $question . '</p>
                            <div class="flex justify-center space-x-6">
                                <label class="flex items-center">
                                    <span class="mr-2">DISLIKE</span>
                                    <input type="radio" name="question_' . $index . '" value="dislike" class="text-blue-500 form-radio">
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="question_' . $index . '" value="neutral" class="text-blue-500 form-radio">
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="question_' . $index . '" value="like" class="text-blue-500 form-radio">
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="question_' . $index . '" value="like" class="text-blue-500 form-radio">
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="question_' . $index . '" value="dislike" class="text-blue-500 form-radio">
                                    <span class="ml-2">LIKE</span>
                                </label>
                            </div>
                        </div>
                        ';
                    }
                    ?>
                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit" class="px-6 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600">
                            Submit
                        </button>
                    </div>
                </form>
            </section>
        </div>
    </div>

    <!-- Personal Growth Inventory Modal -->
    <div id="personalGrowthModal" 
        class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-50">
        <div class="relative p-6 bg-white rounded shadow-lg max-h-[90vh] w-[80vw] overflow-y-auto">
            <!-- Exit Button -->
            <button 
                class="absolute top-0 right-0 m-5 text-gray-500 hover:text-red-600"
                onclick="closeModal('personalGrowthModal')">
                    <i class="text-xl fa-solid fa-circle-xmark"></i>
            </button>
            <section>
                <h2 class="mb-4 text-2xl font-bold">Personal Growth Inventory</h2>
                <p class="mb-4 text-lg">Take the Personal Growth Inventory to explore your self-development journey.</p>
                <!-- Survey Questions -->
                <form action="#" method="POST" class="space-y-6">
                    <input type="hidden" name="assessment_type" value="Personal Growth Inventory">
                    <?php
                    $questions = [
                        "I fell overwhelmed by my emotions",
                        "I am able to handle the level of stress I experience",
                        "I have physical symptoms of anxiety, such as sweaty palms",
                        "I have strong relationship with people I care about",
                        "I made many terrible decisions in my life",
                        "I am very self-critical",
                        "I cannot get beyond long-past traumatic events or significant losses",
                        "I am able to identify and express my emotions",
                        "I trust that if I confide in others, they will be supportive",
                        "I engage in at least one behavior that significantly impairs my ability to function on a daily basis"
                    ];

                    // Dynamically render questions
                    foreach ($questions as $index => $question) {
                        echo '
                        <div class="p-4 rounded-lg shadow-sm bg-gray-50">
                            <p class="mb-2 text-xl font-semibold text-center text-gray-800">' . ($index + 1) . '. ' . $question . '</p>
                            <div class="flex justify-center space-x-6">
                                <label class="flex items-center">
                                    <span class="mr-2">DISLIKE</span>
                                    <input type="radio" name="question_' . $index . '" value="dislike" class="text-blue-500 form-radio">
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="question_' . $index . '" value="neutral" class="text-blue-500 form-radio">
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="question_' . $index . '" value="like" class="text-blue-500 form-radio">
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="question_' . $index . '" value="like" class="text-blue-500 form-radio">
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="question_' . $index . '" value="dislike" class="text-blue-500 form-radio">
                                    <span class="ml-2">LIKE</span>
                                </label>
                            </div>
                        </div>
                        ';
                    }
                    ?>
                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit" class="px-6 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600">
                            Submit
                        </button>
                    </div>
                </form>
            </section>
        </div>
    </div>


<!--FOOTER-->
<footer class="overflow-auto bg-white sm:ml-64 w-75">
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

<script src="../path/to/flowbite/dist/flowbite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</body>
</html>