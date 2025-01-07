<?php
// Include database connection
include('E:/GuidanceHub/src/entry-page/server.php');

// Database connection using PDO
try {
    $host = 'localhost';
    $dbname = 'guidancehub';
    $username = 'root';
    $password = '';
    $con = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Fetch the profile data from the database
$query = "SELECT * FROM students WHERE id = 1"; // assuming user with ID 1
$stmt = $con->prepare($query);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $student_number = $_POST['student_number'];
    $college = $_POST['college'];
    $year_level = $_POST['year_level'];
    $section = $_POST['section'];
    $profile_picture = $_FILES['profile_picture']['name'];

    // Handle profile picture upload
    if ($profile_picture) {
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], "uploads/" . $profile_picture);
    } else {
        $profile_picture = $row['profile_picture']; // keep the old picture if none is uploaded
    }

    // Update the profile details in the database
    $update_query = "UPDATE students SET name = :name, student_number = :student_number, college = :college, year_level = :year_level, section = :section, profile_picture = :profile_picture WHERE id = 1";
    $update_stmt = $con->prepare($update_query);
    $update_stmt->bindParam(':name', $name);
    $update_stmt->bindParam(':student_number', $student_number);
    $update_stmt->bindParam(':college', $college);
    $update_stmt->bindParam(':year_level', $year_level);
    $update_stmt->bindParam(':section', $section);
    $update_stmt->bindParam(':profile_picture', $profile_picture);

    $update_stmt->execute();

    // Redirect or refresh to show updated profile
    header("Location: test-run.php");
}
?>

<h4 class="p-2 text-xl font-semibold text-gray-700">PROFILE</h4>
<div class="flex flex-col items-center justify-center p-5">
    <form method="POST" enctype="multipart/form-data" class="w-full max-w-lg">
        <div class="flex flex-col items-center space-y-4">
            <img src="uploads/<?php echo $row['profile_picture']; ?>" alt="Profile Picture" class="object-cover w-48 h-48 p-2 rounded-full">
            
            <label for="profile_picture" class="text-gray-700 font-semibold">Upload Profile Picture</label>
            <input type="file" id="profile_picture" name="profile_picture" class="p-2 border rounded-md text-gray-800" accept="image/*">
            
            <div class="w-full overflow-x-auto">
                <table class="w-full text-sm text-center text-gray-800">
                    <tbody>
                        <tr>
                            <th scope="row" class="px-6 py-3 text-right">NAME:</th>
                            <td class="px-6 py-3">
                                <input type="text" name="name" value="<?php echo $row['name']; ?>" class="px-3 py-2 border rounded-md" required>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row" class="px-6 py-3 text-right">STUDENT NO.:</th>
                            <td class="px-6 py-3">
                                <input type="text" name="student_number" value="<?php echo $row['student_number']; ?>" class="px-3 py-2 border rounded-md" required>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row" class="px-6 py-3 text-right">COLLEGE/INSTITUTE:</th>
                            <td class="px-6 py-3">
                                <input type="text" name="college" value="<?php echo $row['college']; ?>" class="px-3 py-2 border rounded-md" required>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row" class="px-6 py-3 text-right">YEAR LEVEL:</th>
                            <td class="px-6 py-3">
                                <input type="text" name="year_level" value="<?php echo $row['year_level']; ?>" class="px-3 py-2 border rounded-md" required>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row" class="px-6 py-3 text-right">SECTION:</th>
                            <td class="px-6 py-3">
                                <input type="text" name="section" value="<?php echo $row['section']; ?>" class="px-3 py-2 border rounded-md" required>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <button type="submit" class="px-6 py-3 mt-4 text-white bg-blue-500 rounded-md">Save Changes</button>
        </div>
    </form>
</div>
