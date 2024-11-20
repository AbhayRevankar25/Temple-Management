<?php
include("../Security/Connection.php");

// Initialize variables for total visitors, expenditure, and profits
$totalVisitors = 0;
$totalExpenditure = 0;
$totalProfit = 0;

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

// Fetch daily visitor counts
$queryVisitorGraph = "SELECT Date, SUM(Male + Female) AS TotalVisitors FROM visitors GROUP BY Date";
$resultVisitorGraph = $conn->query($queryVisitorGraph);
$xVisitorDates = [];
$yVisitorCounts = [];

while ($row = $resultVisitorGraph->fetch_assoc()) {
    $xVisitorDates[] = $row['Date'];
    $yVisitorCounts[] = $row['TotalVisitors'];
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

// Fetch and calculate profit from each table

// 1. Profit from Pass table (Aarthi slots)
$queryPassProfit = "SELECT date(booking_date) AS Date, SUM(total_amount) AS PassProfit FROM aarthi_bookings GROUP BY Date";
$resultPassProfit = $conn->query($queryPassProfit);
$passProfits = [];

while ($row = $resultPassProfit->fetch_assoc()) {
    $passProfits[$row['Date']] = $row['PassProfit'];
}

// 2. Profit from Ticket table (Darshana tickets)
$queryTicketProfit = "SELECT date(booking_date) AS Date, SUM(ticket_price * ticket_quantity) AS TicketProfit FROM darshana_tickets GROUP BY Date";
$resultTicketProfit = $conn->query($queryTicketProfit);
$ticketProfits = [];

while ($row = $resultTicketProfit->fetch_assoc()) {
    $ticketProfits[$row['Date']] = $row['TicketProfit'];
}

// 3. Profit from Donations
$queryDonationProfit = "SELECT date(donation_date) AS Date, SUM(amount) AS DonationProfit FROM donations GROUP BY Date";
$resultDonationProfit = $conn->query($queryDonationProfit);
$donationProfits = [];

while ($row = $resultDonationProfit->fetch_assoc()) {
    $donationProfits[$row['Date']] = $row['DonationProfit'];
}

// Fetch daily waste management costs
$queryWasteGraph = "SELECT date_recorded, SUM(cost) AS TotalCost FROM waste_management GROUP BY date_recorded";
$resultWasteGraph = $conn->query($queryWasteGraph);
$yWasteCosts = [];

while ($row = $resultWasteGraph->fetch_assoc()) {
    $yWasteCosts[$row['date_recorded']] = $row['TotalCost'];
}

// Combine all profits by date
$combinedProfits = [];
foreach (array_keys($passProfits + $ticketProfits + $donationProfits + $yWasteCosts) as $date) {
    $combinedProfits[$date] = ($passProfits[$date] ?? 0) + ($ticketProfits[$date] ?? 0) + ($donationProfits[$date] ?? 0) + ($yWasteCosts[$date] ?? 0);
}
ksort($combinedProfits);

// Prepare data for profit chart
$xProfitDates = array_keys($combinedProfits);
$yProfitValues = array_values($combinedProfits);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <div class="space-y-8 mt-1" style="margin-left: 400px; margin-top: 100px; margin-right: 100px;">
        <!-- Visitor Count Section -->
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-4">Total Visitor Count</h2>
            <p class="text-3xl font-semibold text-blue-600 mb-4"><?= htmlspecialchars($totalVisitors) ?></p>
            <canvas id="visitorChart"></canvas>
        </div>

        <!-- Total Profit Section -->
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-4">Total Profit Generated</h2>
            <p class="text-3xl font-semibold text-green-600 mb-4"><?= htmlspecialchars(array_sum($yProfitValues)) ?> ₹</p>
            <canvas id="profitChart"></canvas>
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

    <!-- Total Profit Chart Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const profitDates = <?php echo json_encode($xProfitDates); ?>;
            const profitValues = <?php echo json_encode($yProfitValues); ?>;

            new Chart("profitChart", {
                type: "bar",
                data: {
                    labels: profitDates,
                    datasets: [{
                        label: "Total Profit (₹)",
                        data: profitValues,
                        backgroundColor: "rgba(250, 20, 200, 0.5)",
                        borderColor: "rgba(250, 20, 200, 1)",
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
                        backgroundColor: "rgba(255, 99, 132, 0.2)",
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
