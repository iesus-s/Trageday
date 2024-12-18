<?php 
include "db_conn.php";
$accessKey = 'ly8i2THJhTEPlz5d5gyb6SkrBuWBsh0Fvf0caABuTo4';

// Get today's month and day
$currentMonth = date('F');   
$currentDay = date('d') - 1;       

// Fetch tragedies where the month and day match today's month and day
$sql = "SELECT * FROM tragedies WHERE month = ? AND day = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $currentMonth, $currentDay);  
$stmt->execute();
$result = $stmt->get_result();

// Print result
if ($result->num_rows > 0) {
    // Fetch image for each tragedy title inside the loop
    while ($row = $result->fetch_assoc()) {
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

        // Output the page content with background image
        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <link rel='stylesheet' type='text/css' href='../styles/style.css?v=1'> 
            <title>Trageday</title> 
        </head>
        <body style='background-image: url(\"$imageUrl\");'>
            <div class='main_title'>
                <h1>TRAGEDAY . COM</h1>
            </div> 
            <div class='container'>
                <h2>" . $row['month'] . " " . $row['day'] . ", " . $row['year'] . "</h2>
                <p><strong>" . $row['title'] . "</strong></p>
                <p>" . $row['summary'] . "</p><br>
            </div>
        </body>
        </html>";
    }
} else {
    echo "No tragedies found for today.";
}

$stmt->close();
$conn->close();
?> 
