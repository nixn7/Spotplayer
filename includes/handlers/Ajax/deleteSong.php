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

if(isset($_POST['id'])) {
    $songId = $_POST['id'];
    
    // Delete any playlist entries that contain this song
    $playlistQuery = mysqli_query($con, "DELETE FROM playlistsongs WHERE songId = '$songId'");
    
    // Delete the song
    $deleteQuery = mysqli_query($con, "DELETE FROM songs WHERE id = '$songId'");
                
    if($deleteQuery) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Database error: " . mysqli_error($con)]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Song ID not provided"]);
}
?>
