<?php
// Include server logic if required
include('E:/GuidanceHub/src/entry-page/server.php');

// Create MySQLi connection
$con = new mysqli('localhost', 'root', '', 'guidancehub');

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Announcements
$query = "SELECT * FROM announcements ORDER BY created_at DESC";
$result = $con->query($query);
$announcements = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $announcements[] = $row;
    }
}

// Check if email is set before running the query
if (isset($email)) {
    // Fetch the user's profile data
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('s', $email); // 's' for string
    $stmt->execute();
    $user_result = $stmt->get_result();
    $user = $user_result->fetch_assoc();

    // Check if the user data was returned
    if ($user === null) {
        echo "<div class='text-red-600'>No user found with this email.</div>";
    }
} else {
    echo "<div class='text-red-600'>Email is not set.</div>";
}

// Handle form submission for profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $id_number = htmlspecialchars($_POST['id_number']);
    $college = htmlspecialchars($_POST['college']);
    $year = htmlspecialchars($_POST['year']);
    $section = htmlspecialchars($_POST['section']);
    $current_time = date('Y-m-d H:i:s'); // Timestamp for the update

    // Update the existing user's data in the users table
    $query = "UPDATE users SET 
                name = ?, 
                id_number = ?, 
                college = ?, 
                year = ?, 
                section = ?, 
                updated_at = ? 
                WHERE email = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('sssssss', $name, $id_number, $college, $year, $section, $current_time, $email);

    if ($stmt->execute()) {
        echo "<div class='text-green-600'>Profile updated successfully!</div>";
    } else {
        echo "<div class='text-red-600'>Error updating profile.</div>";
    }
}

// Close connection
$con->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

<link rel="icon" type="images/x-icon" href="/src/images/UMAK-CGCS-logo.png" />
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css"  rel="stylesheet" />
        <script src="https://kit.fontawesome.com/95c10202b4.js" crossorigin="anonymous"></script>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="./output.css" rel="stylesheet">  
</head>
<body>
    <!-- PROFILE -->
        <div class="p-5 border-2 rounded-lg bg-gray-50 dark:border-gray-300">
    <div class="flex items-center justify-between">
        <h4 class="p-2 text-2xl font-bold text-gray-700">PROFILE</h4>
        <div class="float-right text-xl font-semibold cursor-pointer" onclick="showForm()">
            <i class="fa-solid fa-pen-to-square"></i>
        </div>
    </div>
    <div class="flex flex-col items-center justify-center p-3">
        <div class="flex flex-col items-center space-y-4">
            <img src="/src/images/UMak-Facade-Admin.jpg" alt="Profile Picture" class="object-cover w-48 h-48 p-2 rounded-full">
            <div class="w-full overflow-x-auto">
                <table class="mt-4 text-sm table-auto">
                    <tr>
                        <th class="text-right">Full Name:</th>
                        <td><?= htmlspecialchars($user['name']); ?></td>
                    </tr>
                    <tr>
                        <th class="text-right">Email:</th>
                        <td><?= htmlspecialchars($user['email']); ?></td>
                    </tr>
                    <tr>
                        <th class="text-right">ID Number:</th>
                        <td><?= htmlspecialchars($user['id_number']); ?></td>
                    </tr>
                    <tr>
                        <th class="text-right">College:</th>
                        <td><?= htmlspecialchars($user['college']); ?></td>
                    </tr>
                    <tr>
                        <th class="text-right">Year:</th>
                        <td><?= htmlspecialchars($user['year']); ?></td>
                    </tr>
                    <tr>
                        <th class="text-right">Section:</th>
                        <td><?= htmlspecialchars($user['section']); ?></td>
                    </tr>
                </table>                
            </div>
        </div>
    </div>

    <!-- PROFILE UPDATE MODAL -->
    <div id="editFormModal" class="fixed inset-0 z-50 items-center justify-center hidden bg-gray-500 bg-opacity-50">
        <div class="w-1/2 p-6 bg-white rounded-lg">
            <h4 class="mb-4 text-xl font-bold">Edit Profile</h4>
                <form method="POST">
                    <table class="w-full text-sm">
                        <tr>
                            <th class="text-right">Name:</th>
                            <td><input type="text" name="name" value="<?= htmlspecialchars($user['name']); ?>" class="w-full px-2 py-1 border rounded" required></td>
                        </tr>
                        <tr>
                            <th class="text-right">Email:</th>
                            <td><input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" class="w-full px-2 py-1 border rounded" readonly></td>
                        </tr>
                        <tr>
                            <th class="text-right">ID Number:</th>
                            <td><input type="text" name="id_number" value="<?= htmlspecialchars($user['id_number']); ?>" class="w-full px-2 py-1 border rounded" required></td>
                        </tr>
                        <tr>
                            <th class="text-right">College:</th>
                            <td><input type="text" name="college" value="<?= htmlspecialchars($user['college']); ?>" class="w-full px-2 py-1 border rounded" required></td>
                        </tr>
                        <tr>
                            <th class="text-right">Year:</th>
                            <td><input type="text" name="year" value="<?= htmlspecialchars($user['year']); ?>" class="w-full px-2 py-1 border rounded" required></td>
                        </tr>
                        <tr>
                            <th class="text-right">Section:</th>
                            <td><input type="text" name="section" value="<?= htmlspecialchars($user['section']); ?>" class="w-full px-2 py-1 border rounded" required></td>
                        </tr>
                    </table>
                    <div class="mt-4">
                        <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">Save Changes</button>
                    </div>
                </form>
            <div class="mt-4">
                <button type="button" class="px-4 py-2 text-white bg-red-600 rounded-md hover:bg-red-700" onclick="closeForm()">Cancel</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>