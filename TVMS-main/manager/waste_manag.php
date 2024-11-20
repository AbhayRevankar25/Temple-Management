<?php
include("../Security/session_validation.php");
include '../Security/Connection.php'; // Include database connection file

// Initialize variables for handling messages
$message = "";

// Handle adding a new waste record
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_waste'])) {
    $waste_amount = floatval($_POST['waste_amount']);
    $cost = floatval($_POST['cost']);
    $date = date('Y-m-d');

    // Validate inputs
    if ($waste_amount >= 0 && $cost >= 0) {
        // Insert data into the database
        $sql = "INSERT INTO waste_management (waste_amount, cost, date_recorded) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("dds", $waste_amount, $cost, $date);
        
        if ($stmt->execute()) {
            $message = "Waste record added successfully!";
        } else {
            $message = "Error: Could not add waste record.";
        }
        
        $stmt->close();
    } else {
        $message = "Please provide valid amounts.";
    }
}

// Handle updating an existing waste record
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_waste'])) {
    $id = intval($_POST['id']);
    $waste_amount = floatval($_POST['waste_amount']);
    $cost = floatval($_POST['cost']);
    
    if ($id && $waste_amount >= 0 && $cost >= 0) {
        $sql = "UPDATE waste_management SET waste_amount = ?, cost = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ddi", $waste_amount, $cost, $id);
        
        if ($stmt->execute()) {
            $message = "Waste record updated successfully!";
        } else {
            $message = "Error: Could not update waste record.";
        }
        
        $stmt->close();
    } else {
        $message = "Please provide valid values.";
    }
}

// Handle deleting a waste record
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_waste'])) {
    $id = intval($_POST['id']);
    
    if ($id) {
        $sql = "DELETE FROM waste_management WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $message = "Waste record deleted successfully!";
        } else {
            $message = "Error: Could not delete waste record.";
        }
        
        $stmt->close();
    }
}

// Retrieve waste records
$sql = "SELECT * FROM waste_management ORDER BY date_recorded DESC";
$result = $conn->query($sql);
$waste_records = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $waste_records[] = $row;
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
<?php include("header.php") ?>
<?php include("sidebar.php") ?>

<div class="max-w-4xl mx-auto mt-10 p-5 bg-white shadow-lg rounded-lg" style="margin-top: 150px; margin-bottom: 50px;">
    <h2 class="text-2xl font-bold mb-5">Waste Management</h2>

    <?php if ($message): ?>
        <p class="mb-5 text-center text-green-500 font-semibold"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <!-- Add Waste Record Form -->
    <form method="POST" class="mb-10">
        <h3 class="text-xl font-semibold mb-3">Add New Waste Record</h3>
        <div class="mb-4">
            <label for="waste_amount" class="block text-gray-700">Waste Generated (in kg):</label>
            <input type="number" id="waste_amount" name="waste_amount" class="border border-gray-400 p-2 w-full rounded-lg" min="0" step="0.01" required>
        </div>
        <div class="mb-4">
            <label for="cost" class="block text-gray-700">Cost (₹):</label>
            <input type="number" id="cost" name="cost" class="border border-gray-400 p-2 w-full rounded-lg" min="0" step="0.01" required>
        </div>
        <button type="submit" name="add_waste" class="bg-blue-600 text-white font-bold py-2 px-4 rounded">Add Record</button>
    </form>

    <!-- Waste Management Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">Date</th>
                    <th scope="col" class="px-6 py-3">Waste Amount (kg)</th>
                    <th scope="col" class="px-6 py-3">Cost</th>
                    <th scope="col" class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($waste_records as $record): ?>
                    <tr class="bg-white border-b">
                        <form method="POST">
                            <input type="hidden" name="id" value="<?= $record['id'] ?>">
                            <td class="px-6 py-4"><?= htmlspecialchars($record['date_recorded']) ?></td>
                            <td class="px-6 py-4">
                                <input type="number" name="waste_amount" value="<?= htmlspecialchars($record['waste_amount']) ?>"
                                       class="border border-gray-400 p-1 rounded w-full" min="0" step="0.01">
                            </td>
                            <td class="px-6 py-4">
                                <input type="number" name="cost" value="<?= htmlspecialchars($record['cost']) ?>"
                                       class="border border-gray-400 p-1 rounded w-full" min="0" step="0.01">
                            </td>
                            <td class="px-6 py-4 flex space-x-2">
                                <!-- Update Button -->
                                <button type="submit" name="update_waste" class="bg-green-500 text-white font-bold py-1 px-3 rounded">
                                    Update
                                </button>
                                <!-- Delete Button -->
                                <button type="submit" name="delete_waste" class="bg-red-500 text-white font-bold py-1 px-3 rounded"
                                        onclick="return confirm('Are you sure you want to delete this record?');">
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
