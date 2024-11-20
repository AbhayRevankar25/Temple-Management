<?php
include("../Security/session_validation.php");
include '../Security/Connection.php'; // Include database connection file

// Initialize variables for handling messages
$message = "";

// Handle adding a new inventory item
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_item'])) {
    $item_name = trim($_POST['item_name']);
    $quantity = intval($_POST['quantity']);
    $unit_price = floatval($_POST['unit_price']);
    
    if ($item_name && $quantity >= 0 && $unit_price >= 0) {
        $sql = "INSERT INTO inventory (item_name, quantity, unit_price) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sid", $item_name, $quantity, $unit_price);
        
        if ($stmt->execute()) {
            $message = "Item added successfully!";
        } else {
            $message = "Error: Could not add item.";
        }
        
        $stmt->close();
    } else {
        $message = "Please provide valid item details.";
    }
}

// Handle updating an existing inventory item
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_item'])) {
    $id = intval($_POST['id']);
    $item_name = trim($_POST['item_name']);
    $quantity = intval($_POST['quantity']);
    $unit_price = floatval($_POST['unit_price']);
    
    if ($id && $item_name && $quantity >= 0 && $unit_price >= 0) {
        $sql = "UPDATE inventory SET item_name = ?, quantity = ?, unit_price = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sidi", $item_name, $quantity, $unit_price, $id);
        
        if ($stmt->execute()) {
            $message = "Item updated successfully!";
        } else {
            $message = "Error: Could not update item.";
        }
        
        $stmt->close();
    } else {
        $message = "Please provide valid item details.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_item'])) {
    $id = intval($_POST['id']);
    $item_name = trim($_POST['item_name']);
    $quantity = intval($_POST['quantity']);
    $unit_price = floatval($_POST['unit_price']);
    
    if ($id && $item_name && $quantity >= 0 && $unit_price >= 0) {
        $sql = "DELETE FROM inventory WHERE id = $id";
        $stmt = $conn->prepare($sql);
        
        if ($stmt->execute()) {
            $message = "item record deleted successfully!";
        } else {
            $message = "Error: Could not delete  record.";
        }
        
        $stmt->close();
    }
}

// Retrieve inventory items
$sql = "SELECT * FROM inventory";
$result = $conn->query($sql);
$items = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
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

<div class="max-w-4xl mx-auto mt-10 p-5 bg-white shadow-lg rounded-lg" style="margin-top: 100px; margin-bottom: 50px;">
    <h2 class="text-2xl font-bold mb-5">Inventory Management</h2>

    <?php if ($message): ?>
        <p class="mb-5 text-center text-green-500 font-semibold"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <!-- Add Item Form -->
    <form method="POST" class="mb-10">
        <h3 class="text-xl font-semibold mb-3">Add New Item</h3>
        <div class="mb-4">
            <label for="item_name" class="block text-gray-700">Item Name:</label>
            <input type="text" id="item_name" name="item_name" class="border border-gray-400 p-2 w-full rounded-lg" required>
        </div>
        <div class="mb-4">
            <label for="quantity" class="block text-gray-700">Quantity (kg):</label>
            <input type="number" id="quantity" name="quantity" class="border border-gray-400 p-2 w-full rounded-lg" min="0" required>
        </div>
        <div class="mb-4">
            <label for="unit_price" class="block text-gray-700">Unit Price (₹):</label>
            <input type="number" id="unit_price" name="unit_price" class="border border-gray-400 p-2 w-full rounded-lg" min="0" step="0.01" required>
        </div>
        <button type="submit" name="add_item" class="bg-blue-600 text-white font-bold py-2 px-4 rounded">Add Item</button>
    </form>

    <!-- Inventory Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">Item Name</th>
                    <th scope="col" class="px-6 py-3">Quantity (kg)</th>
                    <th scope="col" class="px-6 py-3">Unit Price (₹)</th>
                    <th scope="col" class="px-6 py-3">Last Updated</th>
                    <th scope="col" class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr class="bg-white border-b">
                        <form method="POST">
                            <input type="hidden" name="id" value="<?= $item['id'] ?>">
                            <td class="px-6 py-4">
                                <input type="text" name="item_name" value="<?= htmlspecialchars($item['item_name']) ?>"
                                       class="border border-gray-400 p-1 rounded w-full">
                            </td>
                            <td class="px-6 py-4">
                                <input type="number" name="quantity" value="<?= htmlspecialchars($item['quantity']) ?>"
                                       class="border border-gray-400 p-1 rounded w-full" min="0">
                            </td>
                            <td class="px-6 py-4">
                                <input type="number" name="unit_price" value="<?= htmlspecialchars($item['unit_price']) ?>"
                                       class="border border-gray-400 p-1 rounded w-full" min="0" step="0.01">
                            </td>
                            <td class="px-6 py-4"><?= htmlspecialchars($item['last_updated']) ?></td>
                            <td class="px-6 py-4 flex space-x-2">
                                <!-- Update Button -->
                                <button type="submit" name="update_item" class="bg-green-500 text-white font-bold py-1 px-3 rounded">
                                    Update
                                </button>
                                <!-- Delete Button -->
                                <button type="submit" name="delete_item" class="bg-red-500 text-white font-bold py-1 px-3 rounded"
                                        onclick="return confirm('Are you sure you want to delete this item?');">
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
