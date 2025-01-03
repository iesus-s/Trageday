<?php 
include "../db_conn.php";  
// Begin session for global variables
session_start();

// Function to sanitize user input
function validate($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the user name and message
    $name = validate($_POST['name']); 
    $message = validate($_POST['message']);
    $trageday = validate($_SESSION['trageday']);

    // Ensure the message is not empty
    if (!empty($message)) {
        // Prepare and execute the SQL statement (prevents SQL injections)
        $sql = "INSERT INTO messages (name, message, trageday) VALUES (?, ?, ?)"; 
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sss", $name, $message, $trageday);

            // Execute the query and check for success
            if ($stmt->execute()) {
                header("Location: ../index.php?success=Message posted!#messagereturn");
                exit();
            } else {
                // Log the error
                error_log("Error executing statement: " . $stmt->error);
                header("Location: ../index.php?error=Error posting message");
                exit();
            }
            $stmt->close();    
        } else {
            // Log the error
            error_log("Error preparing statement: " . $conn->error);
            header("Location: ../index.php?error=Error posting message");
            exit();
        }
    } else {
        header("Location: ../index.php?error=Message cannot be empty");
        exit();
    }
} else {
    // Redirect to the main page if the user is not authenticated
    header("Location: ../index.php");
    exit();
}
?>
