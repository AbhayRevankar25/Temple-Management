<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width" initial-scale="1.0">
    <title> ABC</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/1.9.2/tailwind.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.6/flowbite.min.css" rel="stylesheet" />
    <style>
        

        .container {
            background-color: #ffffff;
            width: 2000px;
            padding: 40px;
            border-radius: 10px;
            margin-top: 20px;
            margin-left: 70px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .image-container {
            display: flex; /* Use flexbox to place image and summary side by side */
            margin-bottom: 30px;
            align-items: center;
            justify-content: flex-start;
        }

        .image-container img {
            max-width: 50%; /* Restrict image size to 50% */
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .image-summary {
            margin-left: 20px; /* Adds some space between image and text */
            flex: 1; /* Allow summary to take up the remaining space */
        }

        .heading {
            font-size: 24px;
            color: #333;
            margin-top: 10px;
            cursor: pointer;
            text-decoration: none;
        }

        .heading:hover {
            color: #007bff;
            text-decoration: underline;
        }

        .summary {
            font-size: 16px;
            color: #666;
            margin-top: 10px;
        }

        .login-button {
            margin-left: 600px;
            width: 200px;
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 40px;
        }

        .login-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body class="overflow-x-hidden antialiased">

    <?php
    include("./nav.php")
    ?>
    
    <div class="container">
        <!-- First Image Block -->
        <div class="image-container">
            <img src="images/aarthi.png" alt="Image 1">
            <div class="image-summary">
                <a href="login.php" class="heading">Aarthi</a>
                <p class="summary">Aarthi, a revered Hindu ritual, is performed in temples to honor and seek blessings from deities. In this ceremony, a priest or devotee offers a flame, usually from a ghee or oil lamp, which is moved in a circular motion in front of the deity. This flame symbolizes the presence of divine energy, illuminating and purifying the surroundings. After the aarthi, the flame is brought to devotees, who cup their hands over it and touch their foreheads as a symbolic gesture of receiving the deity's blessings.</p>
            </div>
        </div>

        <!-- Second Image Block -->
        <div class="image-container">
            <img src="images/darshana.png" alt="Image 2">
            <div class="image-summary">
                <a href="login.php" class="heading">Dharshana</a>
                <p class="summary">In temples, devotees often wait patiently in long queues for darshana, a sacred sight of the deity. Standing in line, people chant prayers, sing hymns, or quietly reflect, their hearts filled with devotion and anticipation. The atmosphere is charged with reverence as everyone moves slowly, step by step, closer to the sanctum where the deity resides. For many, this moment of darshana is deeply personal, a time to connect spiritually and seek blessings. Despite the wait, the faithful find joy in the journey, each eager for a brief but profound encounter with the divine</p>
            </div>
        </div>

        <!-- Optional: Login button for quick access -->
        <a href="login.php" class="login-button">Login Now</a>
    </div>

    <?php
    include("./footer.php")
        ?>

</body>
</html>
