<?php
include("../Security/Connection.php"); // Include database connection

// Fetch feedback data from the table
$query = "SELECT name, phone, email, answer, created_at FROM feedback ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #c8bcd4, #fcfdff);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            max-width: 600px;
            margin-left: 280px;
            margin-right: 50px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333333;
            font-weight: solid;
            font-size: 100px;
            margin-bottom: 40px;
            margin-top: 40px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #dddddd;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .feedback-row:hover {
            background-color: #f1f1f1;
        }
        .contact-info {
            color: #555555;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <?php
    include("./admin_imports.php");
    include("./header.php");
    include("./sidebar.php");
    ?>
    <div class="container">
        <h1 style="font-size: 40px;">Feedback Responses</h1>
        
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Feedback</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="feedback-row">
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td class="contact-info"><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td class="contact-info"><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['answer']); ?></td>
                            <td><?php echo date("Y-m-d H:i:s", strtotime($row['created_at'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align: center; color: #888;">No feedback responses found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
