<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width" initial-scale="1.0">
    <title> ABC</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/1.9.2/tailwind.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.6/flowbite.min.css" rel="stylesheet" />
    <style>
        html {
            scroll-behavior: smooth;
        }
        .container {
            display: flex;
            justify-content: space-around;
            align-items: flex-start;
            flex-wrap: wrap;
            margin: 0 auto;
            max-width: 1200px;
            padding: 20px;
        }
        
        .card {
            text-align: center;
            width: 30%;
            box-sizing: border-box;
            padding: 10px;
            margin: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        
        .card img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }
        
        .main-heading {
            text-align: center;
            margin-bottom: 30px;
            margin-top: 50px;
            font-size: 50px;
        }

        .hero-bg {
            background-image: url('./images/Hindu.webp'); /* Replace with your image path */
            background-size: 98% 95%;
            background-position: center;
            background-repeat: no-repeat;
            height: 87vh;      
            border-radius: 5%;     
        }

        .gradient-text {
            background: linear-gradient(to right, #FF8C00, #FF8C00); /* Customize your gradient colors */
            -webkit-background-clip: text;
            text-shadow: 
                -1px -1px 0 #19e3d6,  /* Top-left shadow */
                2px -2px 0 black,   /* Top-right shadow */
                -2px 2px 0 black,   /* Bottom-left shadow */
                2px 2px 0 black;    /* Bottom-right shadow */
            color: transparent;
        }
    </style>
</head>

<body class="overflow-x-hidden antialiased">
    <!--  Header section of landing page -->
    <?php
    include("./nav.php")
        ?>

    <!-- End of header section of landing page -->

    <!-- My front -->
    <div class="hero-bg flex items-center justify-center text-white">
        <h1 class="gradient-text text-8xl md:text-8xl font-bold" style="margin-top: 280px;">Welcome to ABC Temple</h1>
    </div>

    <!--  2 section of homepage -->
    <div class="px-4 py-10 mx-full sm:max-w-xl md:max-w-full lg:max-w-screen-xl md:px-24 lg:px-8 lg:py-5" style="margin-top: 80px; margin-bottom: 80px; margin-left: 180px;">
        <div class="grid gap-10 row-gap-3 lg:grid-cols-2">
            <div class="flex flex-col justify-center">
                <div class="max-w-xl mb-6">
                    <h2
                        class="max-w-lg mb-6 font-sans text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl sm:leading-none" style="font-size: 2.8rem; margin-bottom: 80px">
                        Welcome to ABC Temple <br class="hidden md:block" />
                    </h2>
                    <p class="text-base text-gray-700 md:text-lg text-justify" style="font-size: 1.4rem; margin-bottom: 80px; margin-right: 50px ">
                    ABC Temple is a revered spiritual sanctuary, celebrated for its breathtaking architecture and peaceful ambiance. Established centuries ago, it stands as a monument of rich cultural heritage, attracting visitors from across the globe. The temple’s intricate carvings and majestic design reflect the devotion of its creators and inspire awe in all who visit. Surrounded by serene gardens and quiet courtyards, it offers a space for meditation and reflection. The temple hosts vibrant festivals and ceremonies that honor local traditions, creating a lively community atmosphere. For those seeking spirituality, cultural immersion, or simply a moment of peace, ABC Temple is a must-visit.
                    </p>
                </div>
            </div>
            <div>
                <img class="object-cover w-500 h-100 rounded shadow-lg" style="width: 1000px; height: 600px;"
                    src="./images/hindu.webp?auto=compress&amp;cs=tinysrgb&amp;dpr=3&amp;h=750&amp;w=1400" alt="" />
            </div>
        </div>
    </div>

    <div class="main-heading">
    <h1>Seva Offered in ABC Temple</h1>
    </div>

<div id="sevaDiv" class="container" style="background-color: #f0f0f0; border: 1px solid #ccc; padding: 20px; border-radius: 8px; max-width: 1200px; margin-bottom: 40px;">
    <?php
    // Array of image data
    $images = [
        [
            'src' => 'images/aarthi.png',
            'heading' => 'Aarthi',
        ],
        [
            'src' => 'images/parasada.png',
            'heading' => 'Parasada',

        ],
    ];

    // Loop through each image and create a card
    foreach ($images as $image) {
        echo '<div class="card" style="margin-bottom: 15px; padding: 10px; border: 1px solid #ddd; border-radius: 5px; background-color: white;">';
        echo '<img src="' . htmlspecialchars($image['src']) . '" alt="' . htmlspecialchars($image['heading']) . '" style="width: 100%; border-radius: 4px;">';
        echo '<h2>' . htmlspecialchars($image['heading']) . '</h2>';
        echo '</div>';
    }
    ?>
</div>


    <!-- Footer -->
    <?php
    include("./footer.php")
        ?>

</body>

</html>