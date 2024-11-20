<?php
include("../Security/session_validation.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width" initial-scale="1.0">
    <title> Manager</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/1.9.2/tailwind.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.6/flowbite.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="style.css">

    <!-- flow bite script for apex charts -->
</head>

<body style="background: linear-gradient(to right, #86ba82, #fcfdff);">
    <!-- Dashboard View Including Navbar and Side bar -->
    <?php include("admin_imports.php"); ?>
    <?php include("header.php") ?>
    <?php include("sidebar.php") ?>
    <?php include("../Scripts/ViewerReport.php") ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.7.0/datepicker.min.js"></script>
</body>

</html>