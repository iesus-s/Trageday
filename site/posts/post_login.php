<?php
session_start();
include "../db_conn.php";

// Function to sanitize user input
function validate($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uname = validate($_POST['username']);
    $pass = validate($_POST['password']);

    // Validate input fields
    if (empty($uname)) {
        header("Location: ../index.php?error=username is required");
        exit();
    }
    if (empty($pass)) {
        header("Location: ../index.php?error=password is required");
        exit();
    }

    // Prepare the SQL statement to fetch user details
    $sql = "SELECT * FROM users WHERE user_name = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $uname);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the user exists
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();

            // Verify the hashed password
            if (password_verify($pass, $row['password'])) {
                // Check if the user account is active
                if ($row['user_name'] === $uname) {
                    // Login successful, set session variables
                    $_SESSION['user_name'] = $row['user_name']; 
                    $_SESSION['id'] = $row['id'];

                    header("Location: ../pages/home.php"); // redirect to a protected page
                    exit();
                } else {
                    // user account is not active
                    header("Location: ../index.php?error=your account is not active");
                    exit();
                }
            } else {
                // invalid password
                header("Location: ../index.php?error=incorrect username or password $");
                exit();
            }
        } else {
            // user not found
            header("Location: ../index.php?error=incorrect username or password");
            exit();
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
} else {
    // invalid request
    header("Location: ./pages/index.php?error=invalid request");
    exit();
}
?>
