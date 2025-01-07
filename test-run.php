<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "guidancehub";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form data is submitted
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Referral Form</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container p-8 mx-auto">
        <div class="p-6 bg-white rounded-lg shadow-lg">
            <h2 class="text-xl font-semibold text-gray-700">Referral Form</h2>
            <form action="submit_referral.php" method="POST" class="mt-4">
                <div class="mb-4">
                    <label for="student_id" class="block text-sm font-medium text-gray-700">Student ID</label>
                    <input type="text" id="student_id" name="student_id" class="w-full p-2 mt-1 border border-gray-300 rounded-md" required>
                </div>

                <div class="mb-4">
                    <label for="counselor_id" class="block text-sm font-medium text-gray-700">Counselor ID</label>
                    <input type="text" id="counselor_id" name="counselor_id" class="w-full p-2 mt-1 border border-gray-300 rounded-md" required>
                </div>

                <div class="mb-4">
                    <label for="reason" class="block text-sm font-medium text-gray-700">Reason for Referral</label>
                    <textarea id="reason" name="reason" rows="4" class="w-full p-2 mt-1 border border-gray-300 rounded-md" required></textarea>
                </div>

                <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-md">Submit Referral</button>
            </form>
        </div>
    </div>
</body>
</html>
