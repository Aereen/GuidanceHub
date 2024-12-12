<?php

// Connect to the database
$con = mysqli_connect('localhost', 'root', '', 'guidancehub');

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize variables
$first_name = $last_name = $email = $college = $password = "";
$errors = array(); 

// SIGNUP USER
if (isset($_POST['signup'])) {
    // Receive form data
    $first_name = mysqli_real_escape_string($con, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($con, $_POST['last_name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $role = mysqli_real_escape_string($con, $_POST['role']); // This will be either 'Counselor' or 'Student'
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($con, $_POST['confirm_password']);

    // Form validation: Ensure required fields are filled
    if (empty($first_name)) { array_push($errors, "First Name is required"); }
    if (empty($last_name)) { array_push($errors, "Last Name is required"); }
    if (empty($email)) { array_push($errors, "Email is required"); }
    if (empty($role)) { array_push($errors, "Role is required"); }
    if (empty($password)) { array_push($errors, "Password is required"); }
    if ($password !== $confirm_password) { array_push($errors, "Passwords do not match"); }

    // Check if email already exists
    $user_check_query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $result = mysqli_query($con, $user_check_query);
    
    if (!$result) {
        die("Query failed: " . mysqli_error($con)); // Debugging query error
    }

    $user = mysqli_fetch_assoc($result);

    if ($user) { // If user exists
        if ($user['email'] === $email) {
            array_push($errors, "Email already exists");
        }
    }

    // Register user if no errors
    if (count($errors) == 0) {
        // Hash the password before storing
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into the database
        $query = "INSERT INTO users (first_name, last_name, email, role, password) 
                    VALUES('$first_name', '$last_name', '$email', '$role', '$hashed_password')";

        if (mysqli_query($con, $query)) {
            $_SESSION['email'] = $email;
            $_SESSION['success'] = "You are now registered";
            header('location: login.php'); // Redirect to login page after successful registration
            exit();
        } else {
            // If query fails
            die("Error in inserting user: " . mysqli_error($con));
        }
    }
}






