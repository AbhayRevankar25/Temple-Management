<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width" initial-scale="1.0">
    <title> Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/1.9.2/tailwind.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.6/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="style.css">
</head>

<body class="overflow-x-hidden antialiased" style="background: linear-gradient(to right, #86ba82, #fcfdff);">
    <?php

    include("../Security/session_validation.php");
    ?>


    <?php
    include("../Security/Connection.php");

    $providedUid = $_SESSION['user_id']; // Replace with the value you want to retrieve
    
    // Your SQL query
    $sql = "SELECT * FROM creds
        NATURAL JOIN users
        WHERE Uid = $providedUid";

    // Execute the SQL query
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Assign values to variables
        $uid = $row['Uid'];
        $email = $row['Email'];
        $firstName = $row['FName'];
        $secondName = $row['SName'];
        $dob = $row['DOB'];
        $gender = $row['Gender'];
        $role = $row['Role'];
    } else {
        echo "User not found";
    }
    // Close the MySQLi connection
    $conn->close();
    ?>
    <!-- Completion of fetch -->
    <!-- Update Form -->
    <div class="p-4 sm:ml-64" style="align-items: center;margin-right: 250px;" >
        <div class="border width-auto rounded-lg px-8 py-6 mx-auto my-8 max-w-2xl" style="background-color: #FFFFFF; border-color: #000000">
            <h2 class="text-2xl font-medium mb-4 text-center">Update User Profile</h2>
            <form method="post" action="../Security/valid_manag_update.php">
                <input type="hidden" name="id" value="<?php echo $uid ?>">
                <div class="mb-4">
                    <label for="name" class="block text-gray-800 font-medium mb-2"> First Name</label>
                    <input type="text" id="name" name="first-name" placeholder="<?php echo "$firstName" ?>"
                        class="border border-gray-400 p-2 w-full rounded-lg focus:outline-none focus:border-blue-400">
                </div>
                <div class="mb-4">
                    <label for="name" class="block text-gray-800 font-medium mb-2"> Second Name</label>
                    <input type="text" id="name" name="second-name" placeholder="<?php echo "$secondName" ?>"
                        class="border border-gray-400 p-2 w-full rounded-lg focus:outline-none focus:border-blue-400">
                </div>

                <div class="mb-4">
                    <label for="age" class="block text-gray-800 font-medium mb-2">Date of Birth</label>
                    <div class="relative max-w-sm">
                        <div class="absolute inset-y-0 left-2 flex items-center pl-3.5 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-800" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                            </svg>
                        </div>
                        <input type="date" datepicker-format="yyyy-mm-dd" id="dob" name="dob"
                            class="bg-gray-50  border-gray-300 text-gray-900 w-auto text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-11 p-2.5"
                            placeholder="<?php echo "$dob" ?>" name="dob">
                    </div>
                </div>

                <script>
                    // Function to set max date to 18 years ago from today
                    document.addEventListener("DOMContentLoaded", function() {
                        const dobInput = document.getElementById("dob");
                        const today = new Date();
                        const minAgeDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
                        dobInput.setAttribute("max", minAgeDate.toISOString().split("T")[0]);

                        // Validate age on change
                        dobInput.addEventListener("change", function() {
                            const selectedDate = new Date(dobInput.value);
                            if (selectedDate > minAgeDate) {
                                alert("You must be at least 18 years old.");
                                dobInput.value = ""; // Clear the invalid date
                            }
                        });
                    });
                </script>


                <div class="mb-4">
                    <label for="email" class="block text-gray-800 font-medium mb-2">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="<?php echo "$email" ?>"
                        class="border border-gray-400 p-2 w-full rounded-lg focus:outline-none focus:border-blue-400">
                </div>
                <div class="container py-10 px-10 mx-0 min-w-full flex flex-col items-center">
                    <input type="submit" name="submit"
                        class="bg-blue-500 text-center text-white px-4 py-2 rounded-lg hover:bg-blue-600"
                        value="update">
                </div>

            </form>
        </div>
    </div>
    <!-- Completion of Update Section -->
</body>



<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.7.0/datepicker.min.js"></script>

</html>