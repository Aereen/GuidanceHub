<?php include('server.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sign Up | CounselPro</title>
    <link rel="icon" type="images/x-icon" href="/src/images/UMAK-CGCS-logo.png" />
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://kit.fontawesome.com/95c10202b4.js" crossorigin="anonymous"></script>

    <!-- JavaScript for validating password and confirm password match -->
    <script>
        function validatePasswords() {
            var password = document.getElementById('password').value;
            var confirmPassword = document.getElementById('confirm_password').value;
            var message = document.getElementById('password-message');

            // Check password length and complexity
            var passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{16,21}$/;

            if (!passwordPattern.test(password)) {
                message.style.color = 'red';
                message.textContent = 'Password must be 16-21 characters long, with at least one uppercase, one lowercase, one number, and one special character.';
                return false;
            }

            if (password !== confirmPassword) {
                message.style.color = 'red';
                message.textContent = 'Passwords do not match!';
                return false;
            }

            message.style.color = 'green';
            message.textContent = 'Passwords match!';
            return true;
        }

        // Function to toggle password visibility
        function togglePassword() {
            var passwordField = document.getElementById('password');
            var confirmPasswordField = document.getElementById('confirm_password');
            var type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;
            confirmPasswordField.type = type;
        }
    </script>
</head>
<body>
    <!-- Background Image with overlay -->
    <div class="relative flex items-center justify-center bg-center bg-cover hero"
        style="background-image: url('/src/images/UMak-Facade-Admin.jpg'); height: 100vh;">
        <div class="absolute inset-0 bg-black opacity-50"></div> <!-- Dark overlay -->

        <!-- Form Container -->
        <div class="relative z-10 flex items-center justify-center w-full h-full">
            <div class="w-full max-w-md p-8 bg-white rounded-lg shadow-md">
                <!-- Back Link -->
                <div class="float-left mb-4 text-xl font-semibold">
                    <a href="/src/entry-page/index.php">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                </div>

                <!-- Sign Up Heading -->
                <h2 class="mb-6 text-2xl font-semibold text-center">Sign Up</h2>

                <!-- Sign Up Form -->
                <form action="server.php" method="POST" onsubmit="return validatePasswords()">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- First Name -->
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                            <input type="text" id="first_name" name="first_name" class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600" required>
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                            <input type="text" id="last_name" name="last_name" class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600" required>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" name="email" class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600" required>
                    </div>

                    <!-- Role Selection: Counselor or Student -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Role</label>
                        <div class="flex items-center space-x-4">
                            <div>
                                <input type="radio" id="counselor" name="role" value="Counselor" class="mr-2" required>
                                <label for="counselor" class="text-sm font-medium text-gray-700">Counselor</label>
                            </div>
                            <div>
                                <input type="radio" id="student" name="role" value="Student" class="mr-2" required>
                                <label for="student" class="text-sm font-medium text-gray-700">Student</label>
                            </div>
                        </div>
                    </div>

                    <!-- Password and Confirm Password -->
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600" required>
                            <button type="button" onclick="togglePassword()" class="absolute text-gray-500 right-3 top-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12c0 3.866-3.134 7-7 7S1 15.866 1 12s3.134-7 7-7 7 3.134 7 7zm2 0c0-4.418-3.582-8-8-8s-8 3.582-8 8 3.582 8 8 8 8-3.582 8-8z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <div class="relative">
                            <input type="password" id="confirm_password" name="confirm_password" class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600" required>
                            <button type="button" onclick="togglePassword()" class="absolute text-gray-500 right-3 top-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12c0 3.866-3.134 7-7 7S1 15.866 1 12s3.134-7 7-7 7 3.134 7 7zm2 0c0-4.418-3.582-8-8-8s-8 3.582-8 8 3.582 8 8 8 8-3.582 8-8z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Password validation message -->
                    <div id="password-message" class="mt-2 text-sm text-gray-500"></div>

                    <!-- Submit Button -->
                    <div class="flex justify-center mt-4">
                        <button type="submit" name="signup" class="w-full px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600">Sign Up</button>
                    </div>
                </form>

                <!-- Sign Up Redirect Link -->
                <div class="mt-4 text-center">
                    <p class="text-sm text-gray-600">Already have an account? <a href="/src/entry-page/login.php" class="text-blue-600 hover:text-blue-800">Log in here</a></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Function to toggle password visibility
        function togglePassword() {
            var passwordField = document.getElementById("password");
            var confirmPasswordField = document.getElementById("confirm_password");
            if (passwordField.type === "password") {
                passwordField.type = "text";
                confirmPasswordField.type = "text";
            } else {
                passwordField.type = "password";
                confirmPasswordField.type = "password";
            }
        }
    </script>
</body>

</html>
