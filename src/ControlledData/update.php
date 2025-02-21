<?php
session_start();
include('E:/GuidanceHub/src/ControlledData/server.php');

$host = 'localhost';
$dbname = 'guidancehub';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
}

// Check if the user is logged in
if (!isset($_SESSION['id_number'])) {
    header("Location: login.php");
    exit;
}

// Update user details after form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_number = $_SESSION['id_number'];
    $college = $_POST['college'];
    $year = $_POST['year'];
    $section = $_POST['section'];

    // Prepare the SQL query to update the user's details
    $stmt = $pdo->prepare("UPDATE user_profiles SET college = ?, year = ?, section = ? WHERE id_number = ?");
    $stmt->execute([$college, $year, $section, $id_number]);

    // Update session variables to reflect the changes
    $_SESSION['college'] = $college;
    $_SESSION['year'] = $year;
    $_SESSION['section'] = $section;

    // Redirect to the dashboard after update
    header("Location: /src/student/dashboard.php");
    exit;
}
?>

<!doctype html>
<html>
<head>
<title>GuidanceHub | Update</title>
    <link rel="icon" type="images/x-icon" href="/src/images/UMAK-CGCS-logo.png" />
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css"  rel="stylesheet" />
        <script src="https://kit.fontawesome.com/95c10202b4.js" crossorigin="anonymous"></script>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="./output.css" rel="stylesheet">   

</head>
<body class="bg-gray-100">
<!-- Update Profile Form -->
<div class="max-w-2xl p-6 mx-auto mt-5 bg-white border border-gray-300 rounded-lg shadow-lg">
    <h2 class="mb-4 text-2xl font-semibold text-center text-gray-700">Update Profile</h2>
    <form method="POST" action="update.php" class="space-y-4">
        <div class="grid grid-cols-2 gap-4 p-1" >
            <!-- Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Full Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($_SESSION['name'] ?? ''); ?>" 
                    class="w-full p-2 mt-1 bg-gray-100 border rounded-lg cursor-not-allowed" readonly>
            </div>

            <!-- Student ID -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Student ID</label>
                <input type="text" name="id_number" value="<?php echo htmlspecialchars($_SESSION['id_number'] ?? ''); ?>" 
                    class="w-full p-2 mt-1 bg-gray-100 border rounded-lg cursor-not-allowed" readonly>
            </div>
        </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?>" 
                    class="w-full p-2 mt-1 bg-gray-100 border rounded-lg cursor-not-allowed" readonly>
            </div>

            <!-- College -->
            <div>
                <label for="college" class="block text-sm font-medium text-gray-700">College</label>
                <input type="text" name="college" value="<?php echo htmlspecialchars($_SESSION['college'] ?? ''); ?>" required
                    class="w-full p-2 mt-1 border rounded-lg">
            </div>

            <!-- Year -->
            <div>
                <label for="year" class="block text-sm font-medium text-gray-700">Year</label>
                <input type="text" name="year" value="<?php echo htmlspecialchars($_SESSION['year'] ?? ''); ?>" required
                    class="w-full p-2 mt-1 border rounded-lg">
            </div>

            <!-- Section -->
            <div>
                <label for="section" class="block text-sm font-medium text-gray-700">Section</label>
                <input type="text" name="section" value="<?php echo htmlspecialchars($_SESSION['section'] ?? ''); ?>" required
                    class="w-full p-2 mt-1 border rounded-lg">
            </div>

        <!-- Submit Button -->
        <div class="flex justify-center">
            <button type="submit" class="px-6 py-2 text-white transition bg-blue-600 rounded-lg hover:bg-blue-700">
                Save Changes
            </button>
        </div>
    </form>
</form>

</body>
</html>


