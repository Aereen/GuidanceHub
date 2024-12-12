<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-4xl p-6 mx-auto bg-white rounded-lg shadow-lg">
        <!-- Header -->
        <div class="mb-4 text-center">
            <h1 class="text-lg font-bold text-gray-700">Career Interest Assessment</h1>
            <div class="flex justify-center mt-2 space-x-6 text-sm text-gray-500">
                <span>Dislike</span>
                <span>Neutral</span>
                <span>Like</span>
            </div>
        </div>

        <!-- Survey Questions -->
        <form action="submit-survey.php" method="POST" class="space-y-6">
            <?php
            // Example questions
            $questions = [
                "Inspect a roof for leaks",
                "Use precision machines to build custom metal parts",
                "Analyze the structure of molecules"
            ];

            // Dynamically render questions
            foreach ($questions as $index => $question) {
                echo '
                <div class="p-4 rounded-lg shadow-sm bg-gray-50">
                    <p class="mb-2 font-semibold text-gray-800">' . ($index + 1) . '. ' . $question . '</p>
                    <div class="flex space-x-6">
                        <label class="flex items-center">
                            <input type="radio" name="question_' . $index . '" value="dislike" class="text-blue-500 form-radio">
                            <span class="ml-2">Dislike</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="question_' . $index . '" value="neutral" class="text-blue-500 form-radio">
                            <span class="ml-2">Neutral</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="question_' . $index . '" value="like" class="text-blue-500 form-radio">
                            <span class="ml-2">Like</span>
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
    </div>
</body>
</html>
