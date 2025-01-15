<?php

include "../db_conn.php";
// Begin session for global variables
session_start();

// Set default values for session variables if not set
$_SESSION['image'] = isset($_SESSION['image']) ? $_SESSION['image'] : '../pic_uploads/default.jpg';
$_SESSION['month'] = isset($_SESSION['month']) ? $_SESSION['month'] : date('F');
$_SESSION['day'] = isset($_SESSION['day']) ? $_SESSION['day'] : ltrim(date('d'), '0');
$_SESSION['year'] = isset($_SESSION['year']) ? $_SESSION['year'] : date('Y');

// Check if the API key is set correctly
if (!isset($newsAPIKey) || empty($newsAPIKey)) {
    echo "API key is not set correctly.";
    exit;
}

$apiKey = $newsAPIKey;  // Use the valid NewsAPI key from your db_conn.php

// Modify the endpoint to include the 'q' parameter with the keyword "tragedy"
$keyword = 'tragedy';
$endpoint = 'https://newsapi.org/v2/everything?q=' . urlencode($keyword) . '&apiKey=' . $apiKey;

// Initialize cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $endpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Set the User-Agent header (You Choose)
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'User-Agent: Trageday.com'   
]);

// Execute cURL request and store the response
$response = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    echo 'cURL error: ' . curl_error($ch);
    curl_close($ch);
    exit;
}

curl_close($ch);

// Decode the JSON response
$data = json_decode($response, true);

// Check if the response is valid
if ($data === null) {
    echo "Error decoding JSON response.";
    exit;
}

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
    <ul class='nav container-sm'>
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
            <a class='nav-link' href='../pages/shnake.php'>Shnake</a>
        </li> 
        <li class='nav-item'>
            <a class='nav-link' href='../pages/aboutus.php'>About Us</a>
        </li>
        <li class='nav-item'>
            <a class='nav-link' href='../pages/contactus.php'>Contact Us</a>
        </li> 
    </ul>
    <div class='container-sm alltragedies'>
        <h2>Current Tragedies</h2>
    </div>";

// Check if the request was successful
if ($data['status'] == 'ok' && isset($data['articles'])) { 
    foreach ($data['articles'] as $article) { 
        echo "<div class='container-sm summary2'> 
                <h2 class='ct_title'>" . htmlspecialchars($article['title']) . "</h2>";
            
        // Display image if available
        if (isset($article['urlToImage']) && !empty($article['urlToImage'])) {
            echo "<div class='container-sm text-center'>
                    <img class='ct_image' src='" . htmlspecialchars($article['urlToImage']) . "' alt='Image for " 
                    . htmlspecialchars($article['title']) . "' style='max-width: 100%; height: auto;'><br><br>
                </div>";
        }
        // Display summary
        echo "<p class='ct_summary'>" . htmlspecialchars($article['description']) . "</p>
                <a class='btn ct_readmore' href='" . 
                htmlspecialchars($article['url']) . "' target='_blank'>Read more</a><br><br>
            </div>"; 
    }
} else {
    // Print the raw response in case of failure for debugging
    echo "<h2>Failed to retrieve news articles.</h2>";
    echo "<pre>";
    print_r($data);  // This will print the raw response from NewsAPI
    echo "</pre>";
}
?>
