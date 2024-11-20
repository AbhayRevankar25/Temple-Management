<?php
include("../Security/session_validation.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width" initial-scale="1.0">
    <title> Admin</title>
    <link rel="stylesheet" href="../../assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" />
    <link rel="shortcut icon" href="../../assets/img/favicon.ico" />
    <script src="https://unpkg.com/tailwindcss-jit-cdn">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

    <!-- flow bite script for apex charts -->
</head>

<body style="background: linear-gradient(to right, #c8bcd4, #fcfdff);">
    <!-- Dashboard View Including Navbar and Side bar -->

    <?php include("header.php") ?>
    <?php include("sidebar.php") ?>
    <?php include("../Scripts/sub_report.php") ?>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.6/flowbite.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.7.0/datepicker.min.js"></script>
</body>

</html>