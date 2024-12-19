<?php 
include "db_conn.php";
$accessKey = 'ly8i2THJhTEPlz5d5gyb6SkrBuWBsh0Fvf0caABuTo4';

// Get today's month and day
date_default_timezone_set('America/Los_Angeles');
$currentMonth = date('F');   
$currentDay = date('d');       
$imageDir = '../pic_uploads/';
$imageName = 'background.jpg';

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
            <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH' crossorigin='anonymous'>
            <title>Trageday</title> 
        </head>
        <body class='image' style='background-image: url(\"$imageUrl\");'>
            <div class='maintitle'>
                <h1>TRAGEDAY . COM</h1>
            </div> 
            <div class='container-sm summary'>
                <h2>" . $row['month'] . " " . $row['day'] . ", " . $row['year'] . "</h2>
            </div>
            <div class='container-sm summary'>
                <p><strong>" . $row['title'] . "</strong></p>
                <p>" . $row['summary'] . "</p><br>
            </div>
            <div class='container-sm where'>
                <h2>Where Did It Happen?</h2>
            </div>
            <div class='container-fluid map'>
                <!-- Add custom Bootstrap height utility for the map -->
                <div class='embed-responsive'>
                    <iframe
                        class='container-sm'
                        src='https://www.google.com/maps?q=" . urlencode($row['title']) . "&output=embed'
                        allowfullscreen>
                    </iframe>
                </div>
            </div>
            <div class='container-sm thoughts'>
                <h2>What are your thoughts?</h2>
            </div>
            <div class='container text-center form'>
                <form action='posts/post_message.php' method='POST'> 
                    <label class='form-inside'> Name: </label>
                    <input type='text' name='name' placeholder='Name'><br><br> 
                    <textarea name='message' rows='4' cols='50' placeholder='Write your message here...' required></textarea><br>
                    <button type='submit' class='form-outside'>Post Message</button>
                </form>
            </div>
            <div class='container-sm posts text-center'>
                <h2>Messages</h2>
            </div>
        <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js' integrity='sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz' crossorigin='anonymous'></script>
        </body>
        </html>";

        if(isset($_GET['error'])) {  
            echo '<div class="container-sm text-center errorx">';
            echo '<p class="error">' . $_GET['error'] . '</p>';
            echo '</div>';
        }
        if(isset($_GET['success'])) {   
            echo '<p class="success">' . $_GET['success'] . '</p>';
        } 

        $sql = "SELECT * FROM messages ORDER BY timestamp DESC";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            // Output each message
            echo '<div class="container-sm posts text-center">';
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="name">';
                echo '<p style="font-weight: bold">' . htmlspecialchars($row['name']) . '</p>';
                echo '<div class="message">';
                echo '<p>' . htmlspecialchars($row['message']) . '</p>';
                echo '<small>' . $row['timestamp'] . '</small>';
                echo '</div><hr>';
            }
        } else {
            echo '<p class="container-sm posts text-center">No messages yet.</p>';
        }
    }
} else {
    echo "No tragedies found for today.";
}

$stmt->close();
$conn->close();
?>
