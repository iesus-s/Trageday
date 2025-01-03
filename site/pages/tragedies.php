<?php 
include "../db_conn.php";
// Begin session for global variables
session_start();

// Output the page content
echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel='stylesheet' type='text/css' href='../styles/style.css?v=1'> 
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet' 
    integrity='sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH' crossorigin='anonymous'>
    <title>Trageday</title> 
    <style>
        /* Custom styles for the calendar table */
        .calendar-table {
            border-radius: 8px; /* Add border radius */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Add shadow */
            overflow: hidden; /* Ensures border radius is applied to corners */
        }
        .calendar td, .calendar th {
            text-align: center;
        }
    </style>
</head>";

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
            <a class='nav-link' href='../pages/tragedies.php?'>Tragedies</a>
        </li> 
        <li class='nav-item'>
            <a class='nav-link' href='../pages/aboutus.php'>About Us</a>
        </li>
        <li class='nav-item'>
            <a class='nav-link' href='../pages/contactus.php'>Contact Us</a>
        </li> 
    </ul>
    <div class='container-sm alltragedies'>
        <h2>Tragedies</h2>
    </div>";

// Calendar
if (isset($_GET['month'])) {
    $month = $_GET['month'];
} else {
    $month = date('m');
}

// Calendar Variables (Starting in 2024)
$first_day_of_month = new DateTime("2024-$month-01"); 
$days_in_month = $first_day_of_month->format('t');
$starting_day = $first_day_of_month->format('w');
$days_of_week = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

// Calculate previous and next month
$prev_month = $month - 1;
$next_month = $month + 1;
if ($prev_month < 1) { $prev_month = 12; }
if ($next_month > 12) { $next_month = 1; }

echo '<div class="container mt-5">
        <div class="row"> 
            <a href="?month=' . $prev_month . '" class="col btn months">Previous Month</a>
            <h3 class="col text-center themonth">' . date('F', mktime(0, 0, 0, $month, 10)) . '</h3>
            <a href="?month=' . $next_month . '" class="col btn ml-2 months">Next Month</a>
        </div>
    </div>
        <br>
    <div class="row">
        <div class="col calendar-border">
            <table class="table table-bordered calendar-table">  <!-- Apply the new class here -->
                <thead>
                    <tr>';
foreach ($days_of_week as $day) {
    echo '<th class="calendar-days">' . $day . '</th>';
}
echo '</tr>';
echo '</thead>';
echo '<tbody>';

$day_counter = 1;
for ($i = 0; $i < 6; $i++) {
    echo '<tr>';
    for ($j = 0; $j < 7; $j++) {
        if ($i == 0 && $j < $starting_day) {
            echo '<td class="calendar"></td>'; // Empty cells before the first day
        } elseif ($day_counter <= $days_in_month) {
            $day = str_pad($day_counter, 2, '0', STR_PAD_LEFT);
            echo '<td class="calendar">
                    <a href="../pages/tragedies.php?day=' . $day . '&month=' . $month . '">' . $day_counter . '</a>
                  </td>';
            $day_counter++;
        } else {
            echo '<td class="calendar"></td>'; // Empty cells after the last day
        }
    }
    echo '</tr>';
}

echo '</tbody>
    </table>
    </div>
    </div>
    </div>'; 

// Display Desired Tragedy
if (isset($_GET['day']) && isset($_GET['month'])) {
    $selected_day = $_GET['day'];
    $selected_month = date("F", mktime(0, 0, 0, $_GET['month'], 10));

    // Fetch tragedies where the month and day match today's month and day
    $sql = "SELECT * FROM tragedies WHERE month = ? AND day = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si",  $selected_month, $selected_day);  
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch image for tragedy title inside the loop
    while ($row = $result->fetch_assoc()) {
        echo "<div class='container-fluid t_title'>
                <h1 class='t_title'><strong>" . $row['title'] . "</strong></h1>
            </div>
            <div class='container-sm summary'> 
                <h2 class='dater'>" . $row['month'] . " " . $row['day'] . ", 
                " . $row['year'] . "</h2>
            </div>
            <div class='container-sm summary2'> 
                <p>" . $row['summary'] . "</p><br>
            </div>
            ";
        // Use the title to search for an image on Unsplash
        $searchQuery = urlencode($row['title']); // URL encode the title
        
        // Unsplash API URL to search for images
        $unsplashApiUrl = "https://api.unsplash.com/search/photos?query=$searchQuery&client_id=$accessKey&per_page=1";
        
        // Fetch image data
        $imageData = file_get_contents($unsplashApiUrl); 
        $imageArray = json_decode($imageData, true); 
        
        // Get the first image from the results or fallback to a default image
        if (!empty($imageArray['results'])) {
            $imageUrl = $imageArray['results'][0]['urls']['regular']; // You can use 'full' for a higher resolution image
        } else {
            $imageUrl = '../pic_uploads/default.png'; // Fallback image if no result is found
        }   

        $image = !empty($imageArray['results']) ? $imageArray['results'][0]['urls']['regular'] : '../pic_uploads/default.png'; 
        
        // Display Image 
        echo "<div class='container-sm text-center'>
                <img src='" . $imageUrl . "' class='img-fluid background' alt=''>
            </div>";
        
        // Google Map 
        echo "<div class='container-sm where'>
                <h2>Where Did It Happen?</h2>
            </div>
            <div class='container-fluid map'>
                <!-- Add custom Bootstrap height utility for the map -->
                <div class='embed-responsive'>
                    <iframe
                        class='container-sm frame'
                        src='https://www.google.com/maps?q=" . urlencode($row['map']) . "&output=embed'
                        allowfullscreen>
                    </iframe>
                </div>
            </div>";
    }
}

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
