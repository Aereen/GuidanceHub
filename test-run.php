<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Status Pie Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <canvas id="appointmentChart"></canvas>

    <script>
        // Sample appointment status data (Replace this with dynamic PHP data if needed)
        const appointmentData = {
            "Pending": 12,
            "Scheduled": 15,
            "Completed": 20,
            "Cancelled": 5
        };

        const ctx = document.getElementById('appointmentChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: Object.keys(appointmentData),
                datasets: [{
                    data: Object.values(appointmentData),
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#4BC0C0', '#FFCE56'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</body>
</html>
