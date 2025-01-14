<?php
// Start the session
session_start();

// Include database connection
$conn = new mysqli("localhost", "root", "", "guidancehub");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (isset($_SESSION['email'])) {
    // Get the user ID from the session
    $email = $_SESSION['email'];

    // Fetch user details from the database
    $stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
    $stmt->bind_param("i", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = htmlspecialchars($row['name']);

        // Display the welcome banner
        echo "<div style='background-color: #4CAF50; color: white; padding: 10px; text-align: center; font-size: 20px;'>
                Welcome, $name!
                </div>";
    } else {
        echo "<div style='background-color: #f44336; color: white; padding: 10px; text-align: center; font-size: 20px;'>
                User not found.
                </div>";
    }
} else {
    // Redirect to login page if not logged in
    header("Location: /src/ControlledData/login.php");
    exit();
}

// Close connection
$conn->close();
?>
