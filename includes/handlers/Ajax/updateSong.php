<?php
include("../../config.php");

// Check if user is admin (add a session check here)
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

if(isset($_POST['songId'])) {
    $songId = $_POST['songId'];
    $title = $_POST['songTitle'];
    $artist = $_POST['songArtist'];
    $album = $_POST['songAlbum'];
    $genre = $_POST['songGenre'];
    $duration = $_POST['songDuration'];
    
    // Validate inputs (add more validation as needed)
    if(empty($title) || empty($artist) || empty($album) || empty($genre) || empty($duration)) {
        echo json_encode(["success" => false, "message" => "All fields are required"]);
        exit();
    }
    
    // Update song in database
    $query = mysqli_query($con, "UPDATE songs SET 
                title = '$title',
                artist = '$artist',
                album = '$album',
                genre = '$genre',
                duration = '$duration'
                WHERE id = '$songId'");
                
    if($query) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Database error: " . mysqli_error($con)]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Song ID not provided"]);
}
?>
