<?php
session_start();
include("../Security/Connection.php");

$successAarthiMsg = '';
$errorAarthiMsg = '';
$successTicketMsg = '';
$errorTicketMsg = '';

function generateTextFile($title, $content, $filename) {
    $fileContent = $title . "\n\n";
    foreach ($content as $line) {
        $fileContent .= $line . "\n";
    }

    $_SESSION['file_content'] = $fileContent;
    $_SESSION['file_name'] = $filename;
}

function getCost($conn, $itemName) {
    $query = "SELECT cost FROM list WHERE name = '$itemName'";
    $result = $conn->query($query);
    return $result->num_rows > 0 ? (float) $result->fetch_assoc()['cost'] : 0;
}

$darshanaTicketPrice = getCost($conn, 'Darshana Ticket');
$aarthiTicketPrice = getCost($conn, 'Aarthi Ticket');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['buy_darshana_ticket'])) {
        $userName = $_POST['user_name'];
        $userEmail = $_POST['user_email'];
        $ticketQuantity = (int)$_POST['ticket_quantity'];
        $bookingDate = $_POST['booking_date'];
        $totalAmount = $ticketQuantity * $darshanaTicketPrice;

        $query = "INSERT INTO darshana_tickets (user_name, user_email, ticket_quantity, ticket_price, booking_date) 
                  VALUES ('$userName', '$userEmail', $ticketQuantity, $darshanaTicketPrice, '$bookingDate')";
        
        if ($conn->query($query)) {
            $content_page = [
                "Name: $userName",
                "Email: $userEmail",
                "Ticket Quantity: $ticketQuantity",
                "Total Price: ₹$totalAmount",
                "Booking Date: $bookingDate"
            ];
            generateTextFile("Darshana Ticket", $content_page, "Darshana_Ticket_$userName.txt");
            $successTicketMsg = "Darshana Ticket purchased successfully!";
        } else {
            $errorTicketMsg = "Failed to purchase Darshana Ticket. Please try again.";
        }
    }

    if (isset($_POST['buy_aarthi_Ticket'])) {
        $userName = $_POST['user_name'];
        $userEmail = $_POST['user_email'];
        $ticketQuantity = (int)$_POST['aarthi_ticket_quantity'];
        $bookingDate = $_POST['booking_date'];
        $totalAmount = $ticketQuantity * $aarthiTicketPrice;

        $query = "INSERT INTO aarthi_bookings (user_name, user_email, ticket_quantity, ticket_price, booking_date) 
                  VALUES ('$userName', '$userEmail', '$ticketQuantity', $aarthiTicketPrice, '$bookingDate')";
        
        if ($conn->query($query)) {
            $content = [
                "Name: $userName",
                "Email: $userEmail",
                "Aarthi Ticket Quantity: $ticketQuantity",
                "Total Price: ₹$totalAmount",
                "Booking Date: $bookingDate"
            ];
            generateTextFile("Aarthi Ticket Pass", $content, "Aarthi_Ticket_$userName.txt");
            $successAarthiMsg = "Aarthi Ticket booked successfully!";
        } else {
            $errorAarthiMsg = "Failed to book Aarthi Ticket. Please try again.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width" initial-scale="1.0">
    <title>User</title>
    <link rel="stylesheet" href="../../assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" />
    <link rel="shortcut icon" href="../../assets/img/favicon.ico" />
    <script src="https://unpkg.com/tailwindcss-jit-cdn"></script>
</head>
<body style="background: linear-gradient(to right, #7d96bd, #fcfdff);">

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const today = new Date().toISOString().split("T")[0];
            document.getElementById("darshanaBookingDate").setAttribute("min", today);
            document.getElementById("aarthiBookingDate").setAttribute("min", today);
        });
    </script>

    <?php include("header.php"); ?>
    <?php include("sidebar.php"); ?>

    <!-- Darshana Ticket Purchase Form -->
    <div class="bg-white shadow-lg rounded-xl p-6 mb-8" style="margin-left: 300px; margin-top: 100px; margin-right: 50px;">
        <h2 class="text-2xl font-bold mb-4">Buy Darshana Ticket</h2>
        <?php if ($errorTicketMsg): ?>
            <p class="text-red-500"><?php echo htmlspecialchars($errorTicketMsg); ?></p>
        <?php elseif ($successTicketMsg): ?>
            <p class="text-green-500"><?php echo htmlspecialchars($successTicketMsg); ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="hidden" name="buy_darshana_ticket" value="1">
            <input type="text" name="user_name" placeholder="Your Name" required class="border p-2 mb-4 w-full">
            <input type="email" name="user_email" placeholder="Your Email" required class="border p-2 mb-4 w-full">
            <input type="number" name="ticket_quantity" placeholder="Number of Tickets" required class="border p-2 mb-4 w-full" min="1">
            <p class="mb-4">Ticket Price: ₹ <b><?= $darshanaTicketPrice ?></b> per ticket</p>
            <label for="darshanaBookingDate">Select Booking Date:</label>
            <input type="date" name="booking_date" id="darshanaBookingDate" required class="border p-2 mb-4 w-full">
            <b><p>Total Cost: <span id="darshana_total_cost">0</span></p></b>
            <button type="submit" class="bg-blue-500 text-white p-2 rounded" style="margin-top:20px;">Buy Ticket</button>
        </form>
    </div>

    <!-- Aarthi Ticket Booking Form -->
    <div class="bg-white shadow-lg rounded-xl p-6" style="margin-left: 300px; margin-top: 20px; margin-right: 50px; margin-bottom: 50px;">
        <h2 class="text-2xl font-bold mb-4">Buy Aarthi Ticket</h2>
        <?php if ($errorAarthiMsg): ?>
            <p class="text-red-500"><?php echo htmlspecialchars($errorAarthiMsg); ?></p>
        <?php elseif ($successAarthiMsg): ?>
            <p class="text-green-500"><?php echo htmlspecialchars($successAarthiMsg); ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="hidden" name="buy_aarthi_Ticket" value="1">
            <input type="text" name="user_name" placeholder="Your Name" required class="border p-2 mb-4 w-full">
            <input type="email" name="user_email" placeholder="Your Email" required class="border p-2 mb-4 w-full">
            <input type="number" name="aarthi_ticket_quantity" placeholder="Number of Tickets" required class="border p-2 mb-4 w-full" min="1">
            <p class="mb-4">Ticket Price: ₹ <b><?= $aarthiTicketPrice ?></b> per ticket</p>
            <label for="aarthiBookingDate">Select Booking Date:</label>
            <input type="date" name="booking_date" id="aarthiBookingDate" pattern="([0-2][0-9]|3[01])-(0[1-9]|1[0-2])-\d{2}" placeholder="dd-mm-yy" required class="border p-2 mb-4 w-full">
            <b><p>Total Cost: <span id="aarthi_total_cost">0</span></p></b>
            <button type="submit" class="bg-green-500 text-white p-2 rounded" style="margin-top:20px;">Buy Ticket</button>
        </form>
    </div>

    <script>
        document.querySelector("input[name='ticket_quantity']").addEventListener("input", function() {
            const quantity = this.value;
            const pricePerTicket = <?= $darshanaTicketPrice ?>;
            document.getElementById("darshana_total_cost").textContent = "₹ " + (quantity * pricePerTicket).toFixed(2);
        });

        document.querySelector("input[name='aarthi_ticket_quantity']").addEventListener("input", function() {
            const quantity = this.value;
            const pricePerTicket = <?= $aarthiTicketPrice ?>;
            document.getElementById("aarthi_total_cost").textContent = "₹ " + (quantity * pricePerTicket).toFixed(2);
        });

        document.addEventListener("DOMContentLoaded", function() {
        const today = new Date().toISOString().split("T")[0];
        document.getElementById("darshanaBookingDate").setAttribute("min", today);
        document.getElementById("aarthiBookingDate").setAttribute("min", today);

        // Function to check if the selected date is within 10 years from the current year
        function checkDateLimit(inputField) {
            const selectedDate = new Date(inputField.value);
            const currentDate = new Date();
            const tenYearsFromNow = new Date();
            tenYearsFromNow.setFullYear(currentDate.getFullYear() + 10);

            if (selectedDate > tenYearsFromNow) {
                alert("The selected date is more than 10 years from now. Please choose an earlier date.");
                inputField.value = ""; // Clear the invalid date
            }
        }

        // Add event listeners for both booking dates
        document.getElementById("darshanaBookingDate").addEventListener("change", function() {
            checkDateLimit(this);
        });
        
        document.getElementById("aarthiBookingDate").addEventListener("change", function() {
            checkDateLimit(this);
        });
        });

        <?php if (isset($_SESSION['file_content']) && isset($_SESSION['file_name'])): ?>
            setTimeout(function() {
                const content = `<?php echo $_SESSION['file_content']; ?>`;
                const filename = `<?php echo $_SESSION['file_name']; ?>`;

                const blob = new Blob([content], { type: 'text/plain' });
                const link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = filename;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                <?php unset($_SESSION['file_content'], $_SESSION['file_name']); ?>
            }, 3000); // 3-second delay
        <?php endif; ?>

        

    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.6/flowbite.min.js"></script>
</body>
</html>
