<?php
include("../Security/user_session_validation.php");
include("../Security/Connection.php");

$events = [];

// Fetch events from the events table
$query = "SELECT event_name, event_date FROM events";
$result = $conn->query($query);
while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> User</title>
    <link rel="stylesheet" href="../../assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" />
    <link rel="shortcut icon" href="../../assets/img/favicon.ico" />
    <script src="https://unpkg.com/tailwindcss-jit-cdn"></script>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
</head>

<body style="background: linear-gradient(to right, #7d96bd, #fcfdff);">
    <?php include("header.php"); ?>
    <?php include("sidebar.php"); ?>

    <div class="p-4 sm:ml-64">
        <div class="p-4 border-2 border-gray-900 border-solid rounded-lg dark:border-gray-900 mt-4" style="margin-left:100px; margin-top: 100px; margin-right: 100px; margin-bottom: 50px;">
            <h2 class="text-2xl font-bold mb-4">Event Calendar</h2>
            <div id="calendar" class="bg-white border-2 border-gray-500 shadow-lg rounded-lg p-6"></div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: [
                    <?php foreach ($events as $event): ?>
                    {
                        title: "<?= addslashes($event['event_name']); ?>",
                        start: "<?= $event['event_date']; ?>",
                    },
                    <?php endforeach; ?>
                ],
                eventClick: function(info) {
                    alert("Event: " + info.event.title);
                }
            });
            calendar.render();
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.6/flowbite.min.js"></script>
</body>
</html>
