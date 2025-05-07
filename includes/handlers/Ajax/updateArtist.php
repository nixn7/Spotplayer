<?php
include("../../config.php");

// Check if user is admin
session_start();
if(!isset($_SESSION['userLoggedIn'])) {
    echo json_encode(["success" => false, "message" => "Not logged in"]);
    exit();
}

// Function to check if user is admin
function isAdmin($username) {
    global $con;
    $query = mysqli_query($con, "SELECT is_admin FROM users WHERE username='$username'");
    if(mysqli_num_rows($query) == 0) {
        return false;
    }
    $row = mysqli_fetch_array($query);
    return $row['is_admin'] == 1;
}

if(!isAdmin($_SESSION['userLoggedIn'])) {
    echo json_encode(["success" => false, "message" => "Admin privileges required"]);
    exit();
}

if(isset($_POST['artistId'])) {
    $artistId = $_POST['artistId'];
    $name = $_POST['artistName'];
    
    // Validate inputs
    if(empty($name)) {
        echo json_encode(["success" => false, "message" => "Artist name is required"]);
        exit();
    }
    
    // Update artist in database
    $query = mysqli_query($con, "UPDATE artists SET 
                name = '$name'
                WHERE id = '$artistId'");
                
    if($query) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Database error: " . mysqli_error($con)]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Artist ID not provided"]);
}
?>
