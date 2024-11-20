<?php
include("../Security/Connection.php");

// Initialize variables for total visitors, waste management cost, and expenditure
$totalVisitors = 0;
$totalWasteCost = 0;
$totalExpenditure = 0;

// Query for total visitor count
$queryVisitors = "SELECT SUM(Male + Female) AS TotalVisitors FROM visitors";
$resultVisitors = $conn->query($queryVisitors);
if ($resultVisitors->num_rows > 0) {
    $totalVisitors = $resultVisitors->fetch_assoc()['TotalVisitors'];
}

// Query for total waste management cost
$queryWaste = "SELECT SUM(cost) AS TotalWasteCost FROM waste_management";
$resultWaste = $conn->query($queryWaste);
if ($resultWaste->num_rows > 0) {
    $totalWasteCost = $resultWaste->fetch_assoc()['TotalWasteCost'];
}

// Query for total expenditure (based on most recent inventory update)
$queryExpenditure = "SELECT SUM(unit_price * quantity) AS TotalExpenditure 
                     FROM inventory_log WHERE inventory_id = any(SELECT id from inventory)";
$resultExpenditure = $conn->query($queryExpenditure);
if ($resultExpenditure->num_rows > 0) {
    $totalExpenditure = $resultExpenditure->fetch_assoc()['TotalExpenditure'];
}

// query for total expenditure for graph
$queryExpenditure = "SELECT DATE(operation_timestamp) AS ExpenditureDate, SUM(unit_price * quantity) AS TotalExpenditure 
                     FROM inventory_log WHERE inventory_id = any(SELECT id from inventory)
                     GROUP BY DATE(operation_timestamp)";
$resultExpenditure = $conn->query($queryExpenditure);
$expenditureDates = [];
$expenditureCost = [];

if ($resultExpenditure->num_rows > 0) {
    while ($row = $resultExpenditure->fetch_assoc()) {
        $expenditureDates[] = $row['ExpenditureDate'];
        $expenditureCost[] = $row['TotalExpenditure'];
    }
}

// Fetch daily visitor counts
$queryVisitorGraph = "SELECT Date, SUM(Male + Female) AS TotalVisitors FROM visitors GROUP BY Date";
$resultVisitorGraph = $conn->query($queryVisitorGraph);
$xVisitorDates = [];
$yVisitorCounts = [];

while ($row = $resultVisitorGraph->fetch_assoc()) {
    $xVisitorDates[] = $row['Date'];
    $yVisitorCounts[] = $row['TotalVisitors'];
}

// Fetch daily waste management costs
$queryWasteGraph = "SELECT date_recorded, SUM(cost) AS TotalCost FROM waste_management GROUP BY date_recorded";
$resultWasteGraph = $conn->query($queryWasteGraph);
$xWasteDates = [];
$yWasteCosts = [];

while ($row = $resultWasteGraph->fetch_assoc()) {
    $xWasteDates[] = $row['date_recorded'];
    $yWasteCosts[] = $row['TotalCost'];
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-100">
    <?php include("header.php"); ?>
    <?php include("sidebar.php"); ?>

    <div class="space-y-8 " style="margin-left: 400px; margin-top: 100px; margin-right: 100px;">
        <!-- Visitor Count Section -->
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-4">Total Visitor Count</h2>
            <p class="text-3xl font-semibold text-blue-600 mb-4"><?= htmlspecialchars($totalVisitors) ?></p>
            <canvas id="visitorChart"></canvas>
        </div>

        <!-- Waste Management Cost Section -->
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-4">Total Waste Management Cost</h2>
            <p class="text-3xl font-semibold text-green-600 mb-4"><?= htmlspecialchars($totalWasteCost) ?> ₹</p>
            <canvas id="wasteChart"></canvas>
        </div>

        <!-- Expenditure Section -->
        <div class="bg-white shadow-lg rounded-lg p-6" style="margin-bottom: 50px;">
            <h2 class="text-2xl font-bold mb-4">Total Expenditure</h2>
            <p class="text-3xl font-semibold text-green-600 mb-4"><?= htmlspecialchars($totalExpenditure) ?> ₹</p>
            <canvas id="expenditureChart"></canvas>
        </div>
    </div>

    <!-- Visitor Count Chart Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const visitorDates = <?php echo json_encode($xVisitorDates); ?>;
            const visitorCounts = <?php echo json_encode($yVisitorCounts); ?>;

            new Chart("visitorChart", {
                type: "line",
                data: {
                    labels: visitorDates,
                    datasets: [{
                        label: "Visitor Count",
                        data: visitorCounts,
                        borderColor: "rgba(54, 162, 235, 1)",
                        backgroundColor: "rgba(54, 162, 235, 0.2)",
                        fill: true,
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    },
                    plugins: {
                        legend: { display: true }
                    }
                }
            });
        });
    </script>

    <!-- Waste Management Cost Chart Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const wasteDates = <?php echo json_encode($xWasteDates); ?>;
            const wasteCosts = <?php echo json_encode($yWasteCosts); ?>;

            new Chart("wasteChart", {
                type: "bar",
                data: {
                    labels: wasteDates,
                    datasets: [{
                        label: "Waste Management Cost (₹)",
                        data: wasteCosts,
                        backgroundColor: "rgba(50, 20, 192, 0.5)",
                        borderColor: "rgba(50, 20, 192, 1)",
                        borderWidth: 1,
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    },
                    plugins: {
                        legend: { display: true }
                    }
                }
            });
        });
    </script>

    <!-- Expenditure Chart Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const expenditureDates = <?php echo json_encode($expenditureDates); ?>;
            const expenditureValues = <?php echo json_encode($expenditureCost); ?>;

            new Chart("expenditureChart", {
                type: "line",
                data: {
                    labels: expenditureDates,
                    datasets: [{
                        label: "Expenditure (₹)",
                        data: expenditureValues,
                        borderColor: "rgba(255, 99, 132, 1)",
                        backgroundColor: "rgba(255, 200, 133,0.5)",
                        fill: true,
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    },
                    plugins: {
                        legend: { display: true }
                    }
                }
            });
        });
    </script>
    

</body>
</html>
