<?php    
$image = isset($_GET['bg_image']) ? $_GET['bg_image'] : 'default_image_path.jpg';
$month = isset($_GET['month']) ? $_GET['month'] : null;
$day = isset($_GET['day']) ? $_GET['day'] : null;
$year = isset($_GET['year']) ? $_GET['year'] : null;

// Output the page content with background image
echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel='stylesheet' type='text/css' href='../styles/style.css?v=1'> 
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH' crossorigin='anonymous'>
    <title>Trageday</title> 
</head>   
    <body class='image' style='background-image: url(\"$image\");'> 
    <div class='container-fluid thetitle'>
        <h1 class='thetitle'><strong>T R A G E D A Y . C O M</strong></h1>  
    </div> 
    <div class='container-sm summary'> 
        <h2 class='dater'>" . $month . " " . $day . ", " . $year . "</h2>
    </div> 
        <ul class='nav'>
            <li class='nav-item'>
                <a class='nav-link active' aria-current='page' href='index.php'>Home</a>
            </li>
            <li class='nav-item'>
                <a class='nav-link' href='#'>About Us</a>
            </li>
            <li class='nav-item'>
                <a class='nav-link' href='contactus.php?bg_image=" . $image . "&month=" . $month . 
                        "&day=" . $day . "&year=" . $year . "'>Contact Us</a>
            </li> 
        </ul>    
    <div class='container-sm who'>
        <h2>Who Are We?</h2>
    </div>  
    <div class='container-sm summary2'> 
        <p>I am a Computer Engineer with experience in large projects in the Oil & 
        Gas Industry where we sell, ship, and build Power Generators and Gas 
        Compressors. In addition to my career, I have a passion for technology and spend
        my free time exploring various projects. These include web development, web 
        scrapers, desigining FPGA projects, and working with Docker. I constantly seek 
        new challenges that allow me to push the boundaries of my technical knowledge.</p>
        <p>The idea for this website, Trageday.com, was born from a harmless brainstorming 
        session with some friends, where we often compe up with absurd and unique concepts.
        Trageday.com was one such idea, and its aim is to post major tragedies in history 
        that occurred on a specific day. We hope this website brings together history, 
        technology, and creativity in a way that allows users to learn about impactful 
        events. </p><br>
    </div>
<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js' integrity='sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz' crossorigin='anonymous'></script>
</body>
</html>";  
?>
