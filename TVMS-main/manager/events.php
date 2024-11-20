<?php
include("../Security/session_validation.php");
include '../Security/Connection.php'; // Include database connection file

// Initialize variables for handling messages
$message = "";

// Handle adding a new event
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_event'])) {
    $event_name = trim($_POST['event_name']);
    $event_date = $_POST['event_date'];

    // Validate inputs
    if (!empty($event_name) && !empty($event_date)) {
        // Insert data into the database
        $sql = "INSERT INTO events (event_name, event_date) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $event_name, $event_date);
        
        if ($stmt->execute()) {
            $message = "Event added successfully!";
        } else {
            $message = "Error: Could not add event.";
        }
        
        $stmt->close();
    } else {
        $message = "Please provide a valid event name and date.";
    }
}

// Handle updating an existing event
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_event'])) {
    $id = intval($_POST['id']);
    $event_name = trim($_POST['event_name']);
    $event_date = $_POST['event_date'];
    
    if ($id && !empty($event_name) && !empty($event_date)) {
        $sql = "UPDATE events SET event_name = ?, event_date = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $event_name, $event_date, $id);
        
        if ($stmt->execute()) {
            $message = "Event updated successfully!";
        } else {
            $message = "Error: Could not update event.";
        }
        
        $stmt->close();
    } else {
        $message = "Please provide valid values.";
    }
}

// Handle deleting an event
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_event'])) {
    $id = intval($_POST['id']);
    
    if ($id) {
        $sql = "DELETE FROM events WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $message = "Event deleted successfully!";
        } else {
            $message = "Error: Could not delete event.";
        }
        
        $stmt->close();
    }
}

// Retrieve all events
$sql = "SELECT * FROM events ORDER BY event_date DESC";
$result = $conn->query($sql);
$events = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
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
    <script src="https://unpkg.com/tailwindcss-jit-cdn">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
</head>
<body style="background: linear-gradient(to right, #86ba82, #fcfdff);">

<script>
        document.addEventListener("DOMContentLoaded", function() {
            // Get today's date in YYYY-MM-DD format
            const today = new Date().toISOString().split("T")[0];

            // Set the 'min' attribute to today for both booking date inputs
            document.getElementById("event_date").setAttribute("min", today);
});
</script>

<?php include("header.php") ?>
<?php include("sidebar.php") ?>

<div class="max-w-4xl mx-auto mt-10 p-5 bg-white shadow-lg rounded-lg" style="margin-top: 120px;">
    <h2 class="text-2xl font-bold mb-5">Event Management</h2>

    <?php if ($message): ?>
        <p class="mb-5 text-center text-green-500 font-semibold"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <!-- Add Event Form -->
    <form method="POST" class="mb-10">
        <h3 class="text-xl font-semibold mb-3">Add New Event</h3>
        <div class="mb-4">
            <label for="event_name" class="block text-gray-700">Event Name:</label>
            <input type="text" id="event_name" name="event_name" class="border border-gray-400 p-2 w-full rounded-lg" required>
        </div>
        <div class="mb-4">
            <label for="event_date" class="block text-gray-700">Event Date:</label>
            <input type="date" id="event_date" name="event_date" class="border border-gray-400 p-2 w-full rounded-lg" required>
        </div>
        <button type="submit" name="add_event" class="bg-blue-600 text-white font-bold py-2 px-4 rounded">Add Event</button>
    </form>

    <!-- Event Management Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">Event Name</th>
                    <th scope="col" class="px-6 py-3">Event Date</th>
                    <th scope="col" class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event): ?>
                    <tr class="bg-white border-b">
                        <form method="POST">
                            <input type="hidden" name="id" value="<?= $event['id'] ?>">
                            <td class="px-6 py-4">
                                <input type="text" name="event_name" value="<?= htmlspecialchars($event['event_name']) ?>"
                                       class="border border-gray-400 p-1 rounded w-full" required>
                            </td>
                            <td class="px-6 py-4">
                                <input type="date" name="event_date" value="<?= htmlspecialchars($event['event_date']) ?>"
                                       class="border border-gray-400 p-1 rounded w-full" required>
                            </td>
                            <td class="px-6 py-4 flex space-x-2">
                                <!-- Update Button -->
                                <button type="submit" name="update_event" class="bg-green-500 text-white font-bold py-1 px-3 rounded">
                                    Update
                                </button>
                                <!-- Delete Button -->
                                <button type="submit" name="delete_event" class="bg-red-500 text-white font-bold py-1 px-3 rounded"
                                        onclick="return confirm('Are you sure you want to delete this event?');">
                                    Delete
                                </button>
                            </td>
                        </form>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.6/flowbite.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.7.0/datepicker.min.js"></script>
</body>
</html>
