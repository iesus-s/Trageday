<?php
session_start();
include "../db_conn.php";

// enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// function to sanitize user input
function validate($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// ensure the request is POST and data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uname = validate($_POST['username']);
    $email = validate($_POST['email']);
    $pass = validate($_POST['password']);
    $cpass = validate($_POST['cpassword']);

    // validate input fields
    if (empty($uname)) {
        header("Location: ../pages/create.php?error=username is required");
        exit();
    }
    if (empty($email)) {
        header("Location: ../pages/create.php?error=email is required");
        exit();
    }
    if (empty($pass)) {
        header("Location: ../pages/create.php?error=password is required");
        exit();
    }
    if (empty($cpass)) {
        header("Location: ../pages/create.php?error=confirm password is required");
        exit();
    }
    if ($pass != $cpass) {
        header("Location: ../pages/create.php?error=passwords do not match");
        exit();
    }

    // hash the password before storing it
    $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (user_name, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sss", $uname, $email, $hashed_pass);

        // Execute the query and check for success
        if ($stmt->execute()) {
            header("Location: ../index.php?success=account created!"); 
            exit();
        } 
        else {
            echo "error occurred: " . $stmt->error;
        }
        $stmt->close();
    } 
    else {
        echo "error preparing statement: " . $conn->error;
    }
} 
else {
    header("Location: ../create.php?error=invalid request");
    exit();
}
?>
