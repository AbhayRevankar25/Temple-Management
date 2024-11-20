<?php
include 'Security/Connection.php'; // Include database connection file

// Get the current date
$currentDate = date('Y-m-d');

// Fetch events from the database where event_date is greater than or equal to the current date
$sql = "SELECT * FROM events WHERE event_date >= '$currentDate' ORDER BY event_date ASC";
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
    <title> ABC</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/1.9.2/tailwind.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.6/flowbite.min.css" rel="stylesheet" />
</head>
<body class="overflow-x-hidden antialiased">
<?php include("nav.php") ?>

<div class="max-w-4xl mx-auto mt-10 p-5 bg-white shadow-lg rounded-lg">
    <h2 class="text-3xl font-bold text-center mb-8">Upcoming Events</h2>
    
    <!-- Event List -->
    <div class="space-y-6">
        <?php if (count($events) > 0): ?>
            <?php foreach ($events as $event): ?>
                <div class="p-6 bg-blue-50 border-l-4 border-blue-500 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold text-blue-800">
                        <?= htmlspecialchars($event['event_name']) ?>
                    </h3>
                    <p class="text-gray-700 mt-2">
                        Date: <?= htmlspecialchars(date("F j, Y", strtotime($event['event_date']))) ?>
                    </p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-gray-500 text-center">No upcoming events to display.</p>
        <?php endif; ?>
    </div>
</div>

<?php include("footer.php") ?>

</body>
</html>
