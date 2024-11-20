<?php
include('user_session_validation.php');
include('Connection.php');
include("sweetjs.php");

// Function to fetch Uid from the users table based on the email
function getUidByEmail($conn, $email) {
    $email = $conn->real_escape_string($email); // Sanitize input to prevent SQL injection
    $query = "SELECT Uid, email FROM users NATURAL JOIN creds WHERE email = '$email'";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['Uid'];
    } else {
        return null; // Return null if no user found
    }
}

// Check if the operation is an update or delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit'])) {
        $operation = $_POST['submit'];

        // If the operation is 'update'
        if ($operation === 'update') {
            // Get the data from the POST request
            $fn = $_POST['first-name'];
            $ln = $_POST['second-name'];
            $dob = $_POST['dob'];
            $email = $_POST['email'];

            // Fetch Uid based on the provided email
            $Uid = getUidByEmail($conn, $email);

            if ($Uid) {
                // Initialize an array to track the updates and their corresponding SQL statements
                $updates = array();

                if (!empty($fn)) {
                    $updates[] = "users.FName = '$fn'";
                }

                if (!empty($ln)) {
                    $updates[] = "users.SName = '$ln'";
                }

                if (!empty($dob)) {
                    $updates[] = "users.DOB = '$dob'";
                }

                if (!empty($email)) {
                    $updates[] = "creds.Email = '$email'";
                }

                // Check if there are fields to update
                if (!empty($updates)) {
                    // Construct the UPDATE query
                    $update_query = "UPDATE users
                                     JOIN creds ON users.Uid = creds.Uid
                                     SET " . implode(", ", $updates) . " WHERE users.Uid = $Uid";

                    // Execute the update query
                    if ($conn->query($update_query) === TRUE) {
                        // Handle success
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Updated.',
                                text: 'Successful!',
                                timer: 1500
                            }).then(function () {
                                window.location = "../users/dashboard.php";
                            })
                        </script>
                        <?php
                    } else {
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong!',
                                footer: '<a href="">Why do I have this issue?</a>'
                            }).then(function () {
                                window.location = "../users/dashboard.php";
                            })
                        </script>
                        <?php
                    }
                } else {
                    ?>
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'No Field Provided!',
                        }).then(function () {
                            window.location = "../users/dashboard.php";
                        })
                    </script>
                    <?php
                }
            } else {
                ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'User not found!',
                    }).then(function () {
                        window.location = "../users/dashboard.php";
                    })
                </script>
                <?php
            }
        }
    }
}

// Close the database connection
$conn->close();
?>
