<?php
// Connect to the database
$host = 'localhost';
$dbname = 'guidancehub';
$username = 'root';
$password = ''; // Leave empty if there's no password

// Connect using PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Fetch users from database
header('Content-Type: application/json');
$stmt = $pdo->query("SELECT id, name, email FROM users");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));


// fetch_users.php - Fetch users from database
header('Content-Type: application/json');
$stmt = $pdo->query("SELECT id_number, name, email FROM users");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

// export.php - Export selected users to CSV
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!empty($data['ids'])) {
        $ids = implode(',', array_map('intval', $data['ids']));
        $stmt = $pdo->query("SELECT id, name, email FROM users WHERE id IN ($ids)");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="users_export.csv"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Name', 'Email']);
        foreach ($users as $user) {
            fputcsv($output, $user);
        }
        fclose($output);
        exit;
    }
}
?>

<!-- index.html -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Export</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const response = await fetch('fetch_users.php');
            const users = await response.json();
            const tableBody = document.getElementById('userTableBody');
            
            users.forEach(user => {
                const row = `<tr class='border-b'><td><input type='checkbox' class='user-checkbox' value='${user.id}'></td>
                            <td>${user.name}</td><td>${user.email}</td></tr>`;
                tableBody.innerHTML += row;
            });
        });
        
        function exportCSV() {
            const selectedIds = Array.from(document.querySelectorAll('.user-checkbox:checked'))
                                    .map(checkbox => checkbox.value);
            if (selectedIds.length === 0) return alert('No users selected!');
            
            fetch('export.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ ids: selectedIds })
            })
            .then(response => response.blob())
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'users_export.csv';
                document.body.appendChild(a);
                a.click();
                a.remove();
            });
        }
    </script>
</head>
<body class="p-6">
    <div class="max-w-4xl p-6 mx-auto bg-white rounded-lg shadow">
        <h2 class="mb-4 text-xl font-bold">User List</h2>
        <table class="w-full border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2">Select</th>
                    <th class="p-2">Name</th>
                    <th class="p-2">Email</th>
                </tr>
            </thead>
            <tbody id="userTableBody"></tbody>
        </table>
        <button onclick="exportCSV()" class="px-4 py-2 mt-4 text-white bg-blue-500 rounded">Export CSV</button>
    </div>
</body>
</html>
