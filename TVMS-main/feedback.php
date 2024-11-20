<?php
$host = "localhost"; // Database host
$db = "tvms"; // Database name
$user = "root"; // Database username
$pass = ""; // Database password (default is empty for local)

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $email = $conn->real_escape_string($_POST['email']);
    $answer = $conn->real_escape_string($_POST['answer']);

    $sql = "INSERT INTO feedback (name, phone, email, answer) VALUES ('$name', '$phone', '$email', '$answer')";


if ($conn->query($sql) === TRUE) {
    echo "<script type='text/javascript'>alert('FeedBack submitted successfully.');</script>";
} else {
    echo "<script type='text/javascript'>alert('Error: " . $conn->error . "');</script>";
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
    <style>
        /* General Styling */
        .comp {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh;
            margin: 0;
        }
        .container {
            background-color: #ffffff;
            max-width: 400px;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            font-weight: bold;
            font-size: 25px;
            text-align: center;
            color: red;
        }
        label {
            font-weight: bold;
            margin-top: 15px;
            display: block;
        }
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        textarea {
            resize: vertical;
        }
        button {
            width: 100%;
            padding: 15px;
            background-color: #4CAF50;
            color: green;
            border: 2px solid black;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 30px;
        }
        button:hover {
            color: white;
            background-color: #45a049;
        }
        .error {
            color: red;
            font-size: 0.9em;
        }
        .success {
            color: green;
            font-size: 0.9em;
        }
    </style>
</head>
<body class="overflow-x-hidden antialiased">

<?php
    include("./nav.php")
    ?>
    <div class="comp">
    <div class="container">
        <h2>Submit your FeedBack</h2>
        <form action="feedback.php" method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="phone">Phone Number:</label>
            <input type="tel" id="phone" name="phone" required pattern="[0-9]{10}" placeholder="10-digit number">

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="answer">FeedBack:</label>
            <textarea id="answer" name="answer" rows="4" required></textarea>

            <button type="submit">Submit FeedBack</button>
        </form>
    </div>
    </div>

    <?php
    include("./footer.php")
    ?>

</body>
</html>
