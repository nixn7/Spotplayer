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

if(isset($_POST['albumId'])) {
    $albumId = $_POST['albumId'];
    $title = $_POST['albumTitle'];
    $artist = $_POST['albumArtist'];
    $genre = $_POST['albumGenre'];
    
    // Validate inputs
    if(empty($title) || empty($artist) || empty($genre)) {
        echo json_encode(["success" => false, "message" => "All fields are required"]);
        exit();
    }
    
    // Update album in database
    $query = mysqli_query($con, "UPDATE albums SET 
                title = '$title',
                artist = '$artist',
                genre = '$genre'
                WHERE id = '$albumId'");
                
    if($query) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Database error: " . mysqli_error($con)]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Album ID not provided"]);
}
?>
