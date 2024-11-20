<?php
session_start();
include("../Security/Connection.php");

// Messages for feedback
$successMsg = '';
$errorMsg = '';
$redirect = false; // This will control redirection after success

// Function to insert data into the password_log table
function insertPasswordLog($conn, $name, $email, $new_password) {
    $query = "INSERT INTO pass_log (name, email, new_password) VALUES ('$name', '$email', '$new_password')";
    return $conn->query($query);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];

    // Insert into the pass_log table
    if (insertPasswordLog($conn, $name, $email, $new_password)) {
        // Call the stored procedure to update credentials in the creds table
        $stmt = $conn->prepare("CALL updatePassword(?, ?)");
        $stmt->bind_param("ss", $email, $new_password); // Bind email and new password

        if ($stmt->execute()) {
            $successMsg = "Password updated successfully!";
            $redirect = true; // Set to redirect after a short delay
        } else {
            $errorMsg = "Error updating password. Please try again.";
        }
        $stmt->close();
    } else {
        $errorMsg = "Error saving password reset request. Please try again.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <style>
        /* Body and background */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #6a11cb, #2575fc); /* Gradient background */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Container for the form */
        .container {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }

        /* Form styling */
        form {
            display: flex;
            flex-direction: column;
        }

        input[type="text"], input[type="email"], input[type="password"] {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            padding: 10px;
            background-color: #2575fc;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #1a5db0;
        }

        /* Feedback messages */
        .success-msg {
            color: green;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .error-msg {
            color: red;
            font-weight: bold;
            margin-bottom: 10px;
        }

        /* Label styling */
        label {
            font-size: 18px;
            margin-bottom: 5px;
            text-align: left;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Password Reset</h2>

        <?php if ($successMsg): ?>
            <p class="success-msg"><?php echo htmlspecialchars($successMsg); ?></p>
        <?php endif; ?>

        <?php if ($errorMsg): ?>
            <p class="error-msg"><?php echo htmlspecialchars($errorMsg); ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="name">Name: </label>
            <input type="text" name="name" required><br>

            <label for="email">Email: </label>
            <input type="email" name="email" required><br>

            <label for="new_password">New Password: </label>
            <input type="password" name="new_password" required><br>

            <button type="submit">Reset Password</button>
        </form>
    </div>

    <?php if ($redirect): ?>
        <!-- JavaScript for delayed redirection -->
        <script>
            setTimeout(function() {
                window.location.href = '../login.php';
            }, 3000); // Redirect after 3 seconds
        </script>
    <?php endif; ?>
</body>
</html>
