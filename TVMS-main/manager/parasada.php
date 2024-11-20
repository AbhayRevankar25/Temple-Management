<?php
include("../Security/session_validation.php");
include '../Security/Connection.php'; // Include database connection file

// Initialize message variable
$message = "";

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve count and time slot from form input
    $count = intval($_POST['count']);
    $time_slot = $_POST['time_slot'];

    // Validate that the count is a positive number and time slot is selected
    if ($count > 0 && ($time_slot == '10:00 AM' || $time_slot == '6:00 PM')) {
        // Get today's date
        $today = date('Y-m-d');

        // Check if an entry already exists for today with the selected time slot
        $sql = "SELECT * FROM parasada_log WHERE DATE(timestamp) = ? AND time_slot = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $today, $time_slot);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Entry for today and selected time slot already exists
            $message = "An entry for today at $time_slot has already been recorded. Please try a different time slot.";
        } else {
            // Insert new entry for today with the selected time slot
            $timestamp = date('Y-m-d H:i:s');
            $sql = "INSERT INTO parasada_log (count, timestamp, time_slot) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iss", $count, $timestamp, $time_slot);

            if ($stmt->execute()) {
                $message = "Entry recorded successfully for $time_slot!";
            } else {
                $message = "Error: Could not record the entry. Please try again.";
            }

            $stmt->close();
        }
    } else {
        $message = "Please provide valid item details and select a time slot.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width" initial-scale="1.0">
    <title>Manager</title>
    <link rel="stylesheet" href="../../assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" />
    <link rel="shortcut icon" href="../../assets/img/favicon.ico" />
    <script src="https://unpkg.com/tailwindcss-jit-cdn"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
</head>

<body style="background: linear-gradient(to right, #86ba82, #fcfdff);">

<?php include("header.php") ?>
<?php include("sidebar.php") ?>

<div class="max-w-md mx-auto mt-10 p-5 bg-white shadow-lg rounded-lg" style="margin-top: 150px">
    <h2 class="text-2xl font-bold mb-5">Parasada Entry</h2>
    
    <?php if ($message): ?>
        <p class="mb-5 text-center text-green-500 font-semibold"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
    
    <form method="POST" action="">
        <label for="count" class="block text-gray-700 font-semibold mb-2">Enter Count of People Had Food Today:</label>
        <input type="number" id="count" name="count"
               class="border border-gray-400 p-2 w-full rounded-lg focus:outline-none focus:border-blue-400"
               min="1" required>
        
        <label for="time_slot" class="block text-gray-700 font-semibold mb-2 mt-4">Select Time Slot:</label>
        <select id="time_slot" name="time_slot"
                class="border border-gray-400 p-2 w-full rounded-lg focus:outline-none focus:border-blue-400"
                required>
            <option value="" disabled selected>Select a time slot</option>
            <option value="10:00 AM">10:00 AM</option>
            <option value="6:00 PM">6:00 PM</option>
        </select>
        
        <button type="submit"
                class="w-full mt-5 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                <?php if (strpos($message, "already been recorded") !== false) echo 'disabled'; ?>>
            Submit
        </button>
    </form>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.6/flowbite.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.7.0/datepicker.min.js"></script>
</div>

</body>
</html>
