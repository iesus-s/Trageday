<?php
// Include the database connection
include "../db_conn.php";  // Ensure this points to the correct database connection

// Check if score and name are passed via POST
if (isset($_POST['score']) && isset($_POST['name'])) {
    $score = (int)$_POST['score'];  // Ensure the score is an integer
    $name = mysqli_real_escape_string($conn, $_POST['name']);  // Sanitize the name to avoid SQL injection

    // Insert score and name into the 'score' table
    $sql = "INSERT INTO score (score, name) VALUES ('$score', '$name')";
    
    if (mysqli_query($conn, $sql)) {
        echo "Score saved successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Score or name is missing.";
}
?>
