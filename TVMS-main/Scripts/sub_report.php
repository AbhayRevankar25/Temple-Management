<?php
include("../Security/Connection.php");

// Initialize variables for total visitors, waste management cost, and expenditure

$totalWasteCost = 0;
$totalDonationCost = 0;
$totalTicketCost = 0;
$totalPassCost = 0;

// Query for total waste management cost
$queryWaste = "SELECT SUM(cost) AS TotalWasteCost FROM waste_management";
$resultWaste = $conn->query($queryWaste);
if ($resultWaste->num_rows > 0) {
    $totalWasteCost = $resultWaste->fetch_assoc()['TotalWasteCost'];
}

//Query for pass 
$qPassProfit = "SELECT SUM(total_amount) AS PassProfit FROM aarthi_bookings";
$rPassProfit = $conn->query($qPassProfit);
if ($rPassProfit->num_rows > 0) {
    $totalPassCost = $rPassProfit->fetch_assoc()['PassProfit'];
}

//Query for ticket
$qTicketProfit = "SELECT SUM(total_amount) AS TicketProfit FROM darshana_tickets";
$rTicketProfit = $conn->query($qTicketProfit);
if ($rTicketProfit->num_rows > 0) {
    $totalTicketCost = $rTicketProfit->fetch_assoc()['TicketProfit'];
}

//Query for Donation
$qDonationProfit = "SELECT SUM(amount) AS Profit FROM donations";
$rDonationProfit = $conn->query($qDonationProfit);
if ($rDonationProfit->num_rows > 0) {
    $totalDonationCost = $rDonationProfit->fetch_assoc()['Profit'];
}


// 1. Profit from Pass table (Aarthi slots)
$queryPassProfit = "SELECT date(booking_date) AS Date, SUM(total_amount) AS PassProfit FROM aarthi_bookings GROUP BY Date";
$resultPassProfit = $conn->query($queryPassProfit);
$xpassProfits = [];
$ypassProfits = [];

while ($row = $resultPassProfit->fetch_assoc()) {
    $xpassProfits[] = $row['Date'];
    $ypassProfits[] = $row['PassProfit'];
}

// 2. Profit from Ticket table (Darshana tickets)
$queryTicketProfit = "SELECT date(booking_date) AS Date, SUM(total_amount) AS TicketProfit FROM darshana_tickets GROUP BY Date";
$resultTicketProfit = $conn->query($queryTicketProfit);
$xticketProfits = [];
$yticketProfits = [];

while ($row = $resultTicketProfit->fetch_assoc()) {
    $xticketProfits[] = $row['Date'];
    $yticketProfits[] = $row['TicketProfit'];
}

// 3. Profit from Donations
$queryDonationProfit = "SELECT date(donation_date) AS Date, SUM(amount) AS DonationProfit FROM donations GROUP BY Date";
$resultDonationProfit = $conn->query($queryDonationProfit);
$xdonationProfits = [];
$ydonationProfits = [];

while ($row = $resultDonationProfit->fetch_assoc()) {
    $xdonationProfits[] = $row['Date'];
    $ydonationProfits[] = $row['DonationProfit'];
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
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    
    <div class="space-y-8 " style="margin-left: 400px; margin-top: 100px; margin-right: 100px;">
        
        <!-- Ticket Generated Cost Section -->
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-4">Total Ticket Amount Generated</h2>
            <p class="text-3xl font-semibold text-green-600 mb-4"><?= htmlspecialchars($totalTicketCost) ?> ₹</p>
            <canvas id="TicketChart"></canvas>
        </div>

        <!-- Donations Cost Section -->
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-4">Total Donation Amount Generated</h2>
            <p class="text-3xl font-semibold text-green-600 mb-4"><?= htmlspecialchars($totalDonationCost) ?> ₹</p>
            <canvas id="DonationChart"></canvas>
        </div>

        <!-- Pass Cost Section -->
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-4">Total Aarthi Amount Generated</h2>
            <p class="text-3xl font-semibold text-green-600 mb-4"><?= htmlspecialchars($totalPassCost) ?> ₹</p>
            <canvas id="PassChart"></canvas>
        </div>

        <!-- Waste Management Cost Section -->
        <div class="bg-white shadow-lg rounded-lg p-6" style="margin-bottom: 50px;">
            <h2 class="text-2xl font-bold mb-4">Total Waste Management Cost</h2>
            <p class="text-3xl font-semibold text-green-600 mb-4"><?= htmlspecialchars($totalWasteCost) ?> ₹</p>
            <canvas id="wasteChart"></canvas>
        </div>

    </div>


    <!-- Donations Cost Chart Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const donationDates = <?php echo json_encode($xdonationProfits); ?>;
            const donationCosts = <?php echo json_encode($ydonationProfits); ?>;

            new Chart("DonationChart", {
                type: "bar",
                data: {
                    labels: donationDates,
                    datasets: [{
                        label: "Donation Amount Generated(₹)",
                        data: donationCosts,
                        backgroundColor: "rgba(30, 200, 20, 0.5)",
                        borderColor: "rgba(30, 200, 20, 1)",
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

    <!-- Ticket Cost Chart Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const TicketDates = <?php echo json_encode($xticketProfits); ?>;
            const TicketCosts = <?php echo json_encode($yticketProfits); ?>;

            new Chart("TicketChart", {
                type: "bar",
                data: {
                    labels: TicketDates,
                    datasets: [{
                        label: "Ticket Generated Amount (₹)",
                        data: TicketCosts,
                        backgroundColor: "rgba(200, 200, 20, 0.5)",
                        borderColor: "rgba(200, 200, 20, 1)",
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

    <!-- Pass Cost Chart Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const PassDates = <?php echo json_encode($xpassProfits); ?>;
            const PassCosts = <?php echo json_encode($ypassProfits); ?>;

            new Chart("PassChart", {
                type: "bar",
                data: {
                    labels: PassDates,
                    datasets: [{
                        label: "Pass Generated Amount (₹)",
                        data: PassCosts,
                        backgroundColor: "rgba(75, 192, 192, 0.5)",
                        borderColor: "rgba(75, 192, 192, 1)",
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
                        backgroundColor: "rgba(100, 100, 200, 0.5)",
                        borderColor: "rgba(100, 100, 200, 1)",
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


</body>
</html>
