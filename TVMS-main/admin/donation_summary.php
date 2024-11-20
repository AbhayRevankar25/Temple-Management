<?php
session_start();
include("../Security/Connection.php"); // Assumes Connection.php handles database connection

// Fetch records from the donations table
$query = "SELECT donor_name, email, phone, amount, DATE_FORMAT(donation_date, '%Y-%m-%d') as date FROM donations";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <style>
        /* Styling for the page layout */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #c8bcd4, #fcfdff);
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        /* Container styling */
        .container {
            background-color: white;
            border-radius: 8px;
            padding: 20px 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            margin-right: 60px;
            width: 60%;
            text-align: center;
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #6a11cb;
            color: white;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <?php
    include("./admin_imports.php");
    include("./header.php");
    include("./sidebar.php");
    ?>
    <div class="container" style="margin-left: 300px;">
        <h2 style="font-size: 30px; font-weight: bold;">Donation Summary</h2>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Amount</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['donor_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['amount']); ?></td>
                            <td><?php echo htmlspecialchars($row['date']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No donations found.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
$conn->close();
?>
