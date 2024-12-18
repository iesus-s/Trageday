<?php
session_start();
include "../db_conn.php"; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    $user_id = $_SESSION['id'];
    $target_dir = "../pic_uploads/";
    $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
    $upload_ok = 1;
    $image_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // get current profile picture
    $query = "SELECT profile_pic FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($current_pic);
    $stmt->fetch();
    $stmt->close();

    // check if the file is an actual image
    $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
    if ($check === false) {
        echo "file is not an image.";
        $upload_ok = 0;
    }

    // check file size (limit to 2MB)
    if ($_FILES["profile_picture"]["size"] > 2000000) {
        echo "sorry, your file is too large.";
        $upload_ok = 0;
    }

    // allow only certain file formats
    if (!in_array($image_type, ['jpg', 'jpeg', 'png', 'gif'])) {
        echo "sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $upload_ok = 0;
    }

    // attempt to upload the file
    if ($upload_ok && move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
        // delete current profile picture if not default
        if (!empty($current_pic) && file_exists($current_pic) && $current_pic !== "../pic_uploads/default.jpg") {
            unlink($current_pic);
        }
        // insert user data into the database
        $stmt = $conn->prepare("UPDATE users SET profile_pic = (?) WHERE id = (?)");
        $stmt->bind_param("si", $target_file, $user_id);

        if ($stmt->execute()) {
            header("Location: ../pages/home.php?success=picture updated!");
            exit();
        } else {
            header("Location: ../pages/home.php?error=sorry, there was an error uploading your file.");
            exit();
        }

        $stmt->close();
    } else {
        header("Location: ../pages/home.php?error=sorry, there was an error uploading your file.");
        exit(); 
    }
}

$conn->close();
?>
