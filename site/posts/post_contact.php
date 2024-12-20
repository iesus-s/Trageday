<?php 
include "../db_conn.php";
$image = isset($_GET['bg_image']) ? $_GET['bg_image'] : 'default_image_path.jpg';
$month = isset($_GET['month']) ? $_GET['month'] : null;
$day = isset($_GET['day']) ? $_GET['day'] : null;
$year = isset($_GET['year']) ? $_GET['year'] : null;
 
if ($_SERVER["REQUEST_METHOD"] === "POST") { 
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Insert data into the database
    $sql = "INSERT INTO contact_messages (name, email, message, submitted_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
        // Redirect with success message
        header("Location: ../contactus.php?bg_image=" . $image . "&month=" . $month . 
                        "&day=" . $day . "&year=" . $year . "");
    } else {
        // Redirect with error message
        header("Location: ./contactus.php?bg_image=" . $image . "&month=" . $month . 
                        "&day=" . $day . "&year=" . $year . "");
    }

    $stmt->close();
    $conn->close();
}
?>
