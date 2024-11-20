<?php
include("../Security/Connection.php"); // Include database connection

// Initialize a variable to check if prices were updated
$pricesUpdated = false;

// Handle the form submission to update prices
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($_POST['price'] as $id => $newPrice) {
        $id = (int)$id;
        $newPrice = (float)$newPrice;
        // Update each item's price in the list table
        $query = "UPDATE list SET cost = $newPrice WHERE id = $id";
        $conn->query($query);
    }
    $pricesUpdated = true; // Set flag to true if prices were updated
}

// Fetch items from the list table
$query = "SELECT id, name, cost FROM list";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager</title>
    <link rel="stylesheet" href="../../assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" />
    <link rel="shortcut icon" href="../../assets/img/favicon.ico" />
    <script src="https://unpkg.com/tailwindcss-jit-cdn"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #dddddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        input[type="number"] {
            width: 100%;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #cccccc;
        }
        .btn-update {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
            display: block;
            width: 100%;
            margin-top: 20px;
        }
        .btn-update:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body style="background: linear-gradient(to right, #86ba82, #fcfdff); margin-top: 100px;">
    <?php include("header.php") ?>
    <?php include("sidebar.php") ?>
    
    <div class="container">
        <h1>Update Item Prices</h1>
        <form method="POST">
            <table>
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Current Price</th>
                        <th>New Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td>₹<?php echo number_format($row['cost'], 2); ?></td>
                            <td><input type="number" name="price[<?php echo $row['id']; ?>]" step="0.01" min="0" value="<?php echo $row['cost']; ?>"></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <button type="submit" class="btn-update">Update Prices</button>
        </form>
    </div>

    <?php if ($pricesUpdated) : ?>
        <script>
            alert("Prices updated successfully!");
        </script>
    <?php endif; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.6/flowbite.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.7.0/datepicker.min.js"></script>
</body>
</html>
