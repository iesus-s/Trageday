<?php 
include "../db_conn.php";
// Begin session for global variables
session_start();

// Set default values for session variables if not set
$_SESSION['image'] = isset($_SESSION['image']) ? $_SESSION['image'] : '../pic_uploads/default.jpg';
$_SESSION['month'] = isset($_SESSION['month']) ? $_SESSION['month'] : date('F');
$_SESSION['day'] = isset($_SESSION['day']) ? $_SESSION['day'] : ltrim(date('d'), '0');
$_SESSION['year'] = isset($_SESSION['year']) ? $_SESSION['year'] : date('Y');

// Get Top Score from DB
$score = 0; // For score 
$sql = "SELECT * FROM score ORDER BY score DESC LIMIT 1";
$result = mysqli_query($conn, $sql);  
$topScore = $result->fetch_assoc(); 

// Output the page content
echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../styles/style.css?v=1"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Trageday</title>  
</head>';

echo "<body style='background-image: url(" . $_SESSION['image'] . ");'>
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
            <a class='nav-link' href='../pages/tragedies.php?'>Tragedies</a>
        </li> 
        <li class='nav-item'>
            <a class='nav-link' href='/pages/currentevents.php?'>Current Tragedies</a>
        </li> 
        <li class='nav-item'>
            <a class='nav-link' href='../pages/blappy.php'>Blappy Fird</a>
        </li> 
        <li class='nav-item'>
            <a class='nav-link' href='../pages/aboutus.php'>About Us</a>
        </li>
        <li class='nav-item'>
            <a class='nav-link' href='../pages/contactus.php'>Contact Us</a>
        </li> 
    </ul> 
    <div class='container-sm alltragedies'>
        <h2>Shnake</h2>
    </div>
    <div class='container-sm topscore'>
        <h2 class='score'>Top Score: ". $topScore["score"] ."   |   
                    User: ". $topScore["name"] ."</h2>
    </div>
    <div class='container-fluid p-0 game'> 
        <canvas id='gameCanvas'></canvas>
    </div>
    <div class='container-fluid text-center' id='controls'>
        <button class='btn custom-btnx mb-2' id='up'>&uarr;</button>
        <div>
            <button class='btn custom-btnx mb-2' id='left'>&larr;</button>
            <button class='btn custom-btnx mb-2' id='down'>&darr;</button>
            <button class='btn custom-btnx mb-2' id='right'>&rarr;</button>
        </div>
    </div> 
    <script src='shnake.js'></script>";
 

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

echo "</body>
</html>";

?>