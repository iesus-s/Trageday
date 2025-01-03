<?php     
include "../db_conn.php";
// Begin session for global variables
session_start();

// Set default values for session variables if not set
$_SESSION['image'] = isset($_SESSION['image']) ? $_SESSION['image'] : '../pic_uploads/default.jpg';
$_SESSION['month'] = isset($_SESSION['month']) ? $_SESSION['month'] : date('F');
$_SESSION['day'] = isset($_SESSION['day']) ? $_SESSION['day'] : ltrim(date('d'), '0');
$_SESSION['year'] = isset($_SESSION['year']) ? $_SESSION['year'] : date('Y');

// Output the page content  
// Header & Title
echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel='stylesheet' type='text/css' href='../styles/style.css?v=1'> 
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH' crossorigin='anonymous'>
    <title>Trageday</title> 
</head>";

// Background & Navbar
echo "<body class='image' style='background-image: url(" . $_SESSION['image'] . ");'> 
<div class='container-fluid thetitle'>
    <h1 class='thetitle'><strong>T R A G E D A Y . C O M</strong></h1>  
</div> 
<div class='container-sm summary'> 
    <h2 class='dater'>" . $_SESSION['month'] . " " . $_SESSION['day'] . ", 
    " . $_SESSION['year'] . "</h2>
</div>
    <ul class='nav'>
        <li class='nav-item'>
            <a class='nav-link active' href='../index.php'>Home</a>
        </li>
        <li class='nav-item'>
            <a class='nav-link' href='tragedies.php'>Tragedies</a>
        </li> 
        <li class='nav-item'>
            <a class='nav-link' href='/pages/currentevents.php?'>Current Tragedies</a>
        </li> 
        <li class='nav-item'>
            <a class='nav-link' href='aboutus.php'>About Us</a>
        </li>
        <li class='nav-item'>
            <a class='nav-link' href='#'>Contact Us</a>
        </li> 
    </ul>";

// Contact Form
echo "<div class='container-sm contact'>
        <h2>Contact Us</h2>
    </div>";

    if(isset($_GET['error'])) {  
        echo '<div class="container-sm text-center errorx">';
        echo '<p class="error">' . $_GET['error'] . '</p>';
        echo '</div>';
    }
    if(isset($_GET['success'])) {   
        echo '<div class="container-sm text-center successx">';
        echo '<p class="success">' . $_GET['success'] . '</p>';
        echo '</div>';
    } 

echo "<form class='container-sm contactform' action='../posts/post_contact.php?' method='POST'>
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
<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js' 
integrity='sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz' crossorigin='anonymous'></script>";

    // Footer 
    echo '<footer class="container-fluid">
            <div class="row d-flex justify-content-between align-items-center"> 
                <div class="col-sm">
                    Copyright &copy; Trageday 2001
                </div> 
                <div class="col-sm fot">
                    <a className="btn btn-light btn-social mx-2" href="https://www.potentiamaxima.com" target="_blank"
                            rel="noopener noreferrer" aria-label="Potentia Maxima LLC"><img
                        src="../pic_uploads/gear.png" alt=""/></a>
                    <a className="btn btn-light btn-social mx-2" href="https://github.com/iesus-s" target="_blank"
                            rel="noopener noreferrer" aria-label="GitHub"><img
                        src="../pic_uploads/cathub.png" alt=""/></a>
                    <a className="btn btn-light btn-social mx-2" href="https://www.linkedin.com/in/jesussandoval4"
                            rel="noopener noreferrer" target="_blank" aria-label="LinkedIn"><img
                        src="../pic_uploads/linkedin.png" alt=""/></a>
                </div> 
                <div class="col-sm">
                    <a class="text-decoration-none me-3" href="#!">Privacy Policy</a>
                    <a class="text-decoration-none" href="#!">Terms of Use</a>
                </div>
            </div>
        </footer>';

echo "</body></html>";  
?>
