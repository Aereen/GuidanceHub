<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Team</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <section class="py-12 bg-white">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold text-gray-800">Meet Our Team</h2>
            <p class="text-gray-600 mt-2">A group of dedicated professionals</p>

            <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php
                // Team members array
                $team = [
                    ["name" => "John Doe", "role" => "CEO", "image" => "https://via.placeholder.com/150"],
                    ["name" => "Jane Smith", "role" => "CTO", "image" => "https://via.placeholder.com/150"],
                    ["name" => "Mike Johnson", "role" => "Lead Developer", "image" => "https://via.placeholder.com/150"],
                    ["name" => "Emily Davis", "role" => "UI/UX Designer", "image" => "https://via.placeholder.com/150"]
                ];

                // Loop through team members
                foreach ($team as $member) {
                    echo "
                    <div class='bg-white p-6 rounded-2xl shadow-md hover:shadow-lg transition duration-300'>
                        <img src='{$member['image']}' alt='{$member['name']}' class='w-24 h-24 mx-auto rounded-full border-4 border-gray-300'>
                        <h3 class='mt-4 text-xl font-semibold text-gray-800'>{$member['name']}</h3>
                        <p class='text-gray-500'>{$member['role']}</p>
                    </div>";
                }
                ?>
            </div>
        </div>
    </section>

</body>
</html>
