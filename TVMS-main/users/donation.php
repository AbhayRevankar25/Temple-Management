<?php
session_start();
include("../Security/Connection.php"); // Adjust the path to your database connection file

// Initialize variables for form data and error messages
$name = $email = $phone = $amount = "";
$errorMsg = $successMsg = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $amount = $_POST['amount'];

    // Validate required fields
    if (empty($name) || empty($email) || empty($amount)) {
        $errorMsg = "Please fill in all required fields.";
    } else {
        // Insert the data into the donations table
        $stmt = $conn->prepare("INSERT INTO donations (donor_name, email, phone, amount) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssd", $name, $email, $phone, $amount);

        if ($stmt->execute()) {
            $successMsg = "Thank you for your donation!";

            // Generate donation receipt text content
            $receiptContent = "Donation Receipt\n";
            $receiptContent .= "Name: $name\n";
            $receiptContent .= "Email: $email\n";
            $receiptContent .= "Phone: $phone\n";
            $receiptContent .= "Amount Donated: ₹$amount\n";
            $receiptContent .= "Thank you for your support!\n";

            // Store receipt content in session for JavaScript to access
            $_SESSION['receipt_content'] = $receiptContent;

            // Clear form data
            $name = $email = $phone = $amount = "";
        } else {
            $errorMsg = "Error: " . $conn->error;
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width" initial-scale="1.0">
    <title>User</title>
    <link rel="stylesheet" href="../../assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" />
    <link rel="shortcut icon" href="../../assets/img/favicon.ico" />
    <script src="https://unpkg.com/tailwindcss-jit-cdn">
    </script>
</head>
<body style="background: linear-gradient(to right, #7d96bd, #fcfdff);">
    <?php include("header.php") ?>
    <?php include("sidebar.php") ?>

    <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-6" style="margin-top: 120px; margin-left: 600px;">
        <h2 class="text-2xl font-bold mb-4">Make a Donation</h2>

        <?php if ($errorMsg): ?>
            <p class="text-red-500"><?php echo htmlspecialchars($errorMsg); ?></p>
        <?php elseif ($successMsg): ?>
            <p class="text-green-500"><?php echo htmlspecialchars($successMsg); ?></p>
        <?php endif; ?>

        <form id="donationForm" action="" method="POST">
            <div class="mb-4">
                <label for="name" class="block text-gray-700">Name</label>
                <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($name); ?>" required class="w-full p-2 border rounded">
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required class="w-full p-2 border rounded">
            </div>

            <div class="mb-4">
                <label for="phone" class="block text-gray-700">Phone </label>
                <input type="tel" name="phone" id="phone" required pattern="[0-9]{10}" value="<?php echo htmlspecialchars($phone); ?>" required class="w-full p-2 border rounded">
            </div>

            <div class="mb-4">
                <label for="amount" class="block text-gray-700">Donation Amount (₹)</label>
                <input type="number" step="0.01" name="amount" id="amount" min=1 value="<?php echo htmlspecialchars($amount); ?>" required class="w-full p-2 border rounded">
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded">Donate</button>
        </form>
    </div>

    <?php if (!empty($successMsg) && isset($_SESSION['receipt_content'])): ?>
        <script>
            // Delay the download by 3 seconds and clear the form fields
            setTimeout(function() {
                const receiptContent = `<?php echo $_SESSION['receipt_content']; ?>`;
                const blob = new Blob([receiptContent], { type: 'text/plain' });
                const link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = "Donation_Receipt.txt";
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                // Clear session receipt content and form fields
                <?php unset($_SESSION['receipt_content']); ?>
                document.getElementById('donationForm').reset();
            }, 3000); // 3-second delay
        </script>
    <?php endif; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.6/flowbite.min.js"></script>
</body>
</html>
