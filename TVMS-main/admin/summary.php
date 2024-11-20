<?php
include("../Security/Connection.php"); // Database connection

// Fetch Darshana Tickets data
$queryDarshana = "SELECT * FROM darshana_tickets";
$resultDarshana = $conn->query($queryDarshana);

// Fetch Aarthi Bookings data
$queryAarthi = "SELECT * FROM aarthi_bookings";
$resultAarthi = $conn->query($queryAarthi);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width" initial-scale="1.0">
    <title> Admin</title>
    <link rel="stylesheet" href="../../assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" />
    <link rel="shortcut icon" href="../../assets/img/favicon.ico" />
    <script src="https://unpkg.com/tailwindcss-jit-cdn">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
</head>
<body style="background: linear-gradient(to right, #c8bcd4, #fcfdff);">
    <?php include("header.php"); ?>
    <?php include("sidebar.php"); ?>

    <div  style="margin-left: 300px; margin-right: 50px; margin-top: 100px;">
        <!-- Darshana Tickets Summary -->
        <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
            <h2 class="text-2xl font-bold mb-4">Darshana Tickets Summary</h2>
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase">Quantity</th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase">Total Amount</th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase">Booking Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $resultDarshana->fetch_assoc()): ?>
                        <tr>
                            <td class="px-6 py-4 border-b border-gray-200"><?= htmlspecialchars($row['user_name']) ?></td>
                            <td class="px-6 py-4 border-b border-gray-200"><?= htmlspecialchars($row['user_email']) ?></td>
                            <td class="px-6 py-4 border-b border-gray-200"><?= htmlspecialchars($row['ticket_quantity']) ?></td>
                            <td class="px-6 py-4 border-b border-gray-200"><?= htmlspecialchars($row['total_amount']) ?> ₹</td>
                            <td class="px-6 py-4 border-b border-gray-200"><?= htmlspecialchars($row['booking_date']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Aarthi Bookings Summary -->
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-4">Aarthi Ticket Bookings Summary</h2>
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase">Quantity</th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase">Total Amount</th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase">Booking Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $resultAarthi->fetch_assoc()): ?>
                        <tr>
                            <td class="px-6 py-4 border-b border-gray-200"><?= htmlspecialchars($row['user_name']) ?></td>
                            <td class="px-6 py-4 border-b border-gray-200"><?= htmlspecialchars($row['user_email']) ?></td>
                            <td class="px-6 py-4 border-b border-gray-200"><?= htmlspecialchars($row['ticket_quantity']) ?></td>
                            <td class="px-6 py-4 border-b border-gray-200"><?= htmlspecialchars($row['total_amount']) ?> ₹</td>
                            <td class="px-6 py-4 border-b border-gray-200"><?= htmlspecialchars($row['booking_date']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.6/flowbite.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.7.0/datepicker.min.js"></script>

</body>
</html>
