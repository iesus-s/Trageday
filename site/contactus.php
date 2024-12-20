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
                <a class='nav-link' href='aboutus.php?bg_image=" . $image . "&month=" . $month . 
                        "&day=" . $day . "&year=" . $year . "'>About Us</a>
            </li>
            <li class='nav-item'>
                <a class='nav-link' href='#'>Contact Us</a>
            </li> 
        </ul>    
    <div class='container-sm contact'>
        <h2>Contact Us</h2>
    </div>  
    <form class='container-sm contactform' action='posts/post_contact.php?bg_image=" . $image . "&month=" . $month . 
                        "&day=" . $day . "&year=" . $year . "' method='POST'>
        <div class='mb-3'>
            <label for='name' class='form-label'>Name</label>
            <input type='text' class='form-control' id='name' name='name' placeholder='John Smith' required>
        </div>
        <div class='mb-3'>
            <label for='email' class='form-label'>Email Address</label>
            <input type='email' class='form-control' id='email' name='email' placeholder='name@example.com' required>
        </div>
        <div class='mb-3'>
            <label for='message' class='form-label'>Message</label>
            <textarea class='form-control' id='message' name='message' rows='3' required></textarea>
        </div>
        <button type='submit' class='btn custom-btn mb-3'>Submit</button>
    </form>
<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js' integrity='sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz' crossorigin='anonymous'></script>
</body>
</html>";  
?>
