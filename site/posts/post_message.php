<?php
    session_start();
    include "../db_conn.php"; // ensure this file contains the database connection setup 

    // function to sanitize user input
    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
}

    if (isset($_SESSION['id']) && isset($_SESSION['user_name'])) { 
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // retrieve the user id and message
            $m_id = $_SESSION['id'];
            $m_user = $_SESSION['user_name'];
            $message = validate($_POST['message']);

            // Ensure the message is not empty
            if (!empty($message)) {
                // prepare and execute the SQL statement (prevents sql injections)
                $sql = "INSERT INTO messages (m_id, m_user, message) VALUES (?, ?, ?)"; 
                $stmt = $conn->prepare($sql);

                if ($stmt) {
                    $stmt->bind_param("iss", $m_id, $m_user, $message);

                    // Execute the query and check for success
                    if ($stmt->execute()) {
                        header("Location: ../pages/forum.php");
                        exit();
                    } 
                    else {
                        echo "error occurred: " . $stmt->error;
                    }
                    $stmt->close();    
                } 
                else {
                    echo "<h2>failed to send message: " . $stmt->error . "</h2>";
                } 
            } else {
                echo "<h2>message cannot be empty!</h2>";
            }
        }
    } 
    else {
        // redirect to login page if the user is not authenticated
        header("Location: ./pages/forum.php");
        exit();
    } 
?>
