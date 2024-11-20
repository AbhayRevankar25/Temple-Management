<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width" initial-scale="1.0">
    <title> ABC</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/1.9.2/tailwind.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.6/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="style.css">
    <style>
        .hero-bg {
            background-image: url('./images/sunrise.webp'); /* Replace with your image path */
        }
    </style>
    
</head>

<body class="hero-bg overflow-x-hidden antialiased">
    <!--  Header section of landing page -->
    <?php
    include("./nav.php")
        ?>
    <!-- End of header section -->

    <!-- Contact US Section -->
    

   

    <div style="font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; min-height: 80vh; margin: 0;">

    <div style="background-color: white; border-radius: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); max-width: 800px; width: 90%; padding: 20px; text-align: center;">
    <h1 style="color: #4CAF50; font-size: 3.0em;">Contact Us</h1>
    
    <!-- Contact Information for Member 1 -->
    <div style="margin: 20px 0; padding: 15px; border-radius: 30px; background-color: #00FFFF;">
        <h2 style="color: #333; font-size: 1.2em; margin: 0;">Abhay</h2>
        <p style="color: #555; margin: 5px 0;">📞 Phone: +123-456-7890</p>
        <p style="color: #555; margin: 5px 0;">📧 Email: abhay5revankar@gmail.com</p>
    </div>

    <!-- Contact Information for Member 2 -->
    <div style="margin: 20px 0; padding: 15px; border-radius: 30px; background-color: #00FFFF;">
        <h2 style="color: #333; font-size: 1.2em; margin: 0;">Akshat Kothari</h2>
        <p style="color: #555; margin: 5px 0;">📞 Phone: +098-765-4321</p>
        <p style="color: #555; margin: 5px 0;">📧 Email: akshatkothari@gmail.com</p>
    </div>

    <!-- Location Information -->
    <div style="margin-top: 20px;">
        <h2 style="color: #333; font-size: 1.2em; margin: 0;">Our Location</h2>
        <p style="color: #555; margin: 5px 0;">PES University</p>
    </div>

    <div style="margin-top: 20px;">
        <h2 style="color: red; font-size: 1.2em; margin: 0;">For any query or cancellation please mail to down email with the ticket </h2>
        <p style="color: black; font-weight: solid; margin: 5px 0;">query@gmail.com</p>
        <p style="color: #555;font-size: 0.8em; margin: 5px 0;">(their is ticket policy so your 10% of the booking fees will not be returned)</p>
    </div>
</div>
    </div>

    <!-- Starting of footer section -->
    <?php
    include("./footer.php")
        ?>
</body>

</html>