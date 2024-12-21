<?php
// Set timezone
date_default_timezone_set('Asia/Manila');

// Get current month and year
$month = date('m');
$year = date('Y');

// Get first day of the month and total days in the month
$firstDayOfMonth = date('w', strtotime("$year-$month-01"));
$totalDays = date('t', strtotime("$year-$month-01"));

// Days of the week
$daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

// Generate calendar
$calendar = [];
$row = array_fill(0, 7, null);
$dayCounter = 1;

for ($i = 0; $i < 42; $i++) {
    if ($i >= $firstDayOfMonth && $dayCounter <= $totalDays) {
        $row[$i % 7] = $dayCounter++;
    }

    if ($i % 7 === 6) {
        $calendar[] = $row;
        $row = array_fill(0, 7, null);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-6 bg-gray-100">
    <div class="max-w-4xl p-6 mx-auto bg-white rounded-lg shadow-lg">
        <h1 class="mb-6 text-2xl font-bold text-center text-gray-800">
            <?php echo date('F Y'); ?> Calendar
        </h1>
        <div class="grid grid-cols-7 gap-2 font-semibold text-center text-gray-600">
            <?php foreach ($daysOfWeek as $day): ?>
                <div class="p-2 text-white bg-teal-500 rounded-lg"><?php echo $day; ?></div>
            <?php endforeach; ?>
        </div>
        <div class="grid grid-cols-7 gap-2 mt-2">
            <?php foreach ($calendar as $week): ?>
                <?php foreach ($week as $day): ?>
                    <div class="p-2 <?php echo $day === (int)date('j') ? 'bg-teal-500 text-white' : 'bg-gray-100'; ?> rounded-lg">
                        <?php echo $day ? $day : ''; ?>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
