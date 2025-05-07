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
    $artistId = $_POST['id'];
    
    // Get albums by this artist
    $albumsQuery = mysqli_query($con, "SELECT id FROM albums WHERE artist = '$artistId'");
    $albumIds = [];
    
    while($albumRow = mysqli_fetch_array($albumsQuery)) {
        $albumIds[] = $albumRow['id'];
    }
    
    if(count($albumIds) > 0) {
        $albumIdList = implode(',', $albumIds);
        
        // Get songs in these albums
        $songsQuery = mysqli_query($con, "SELECT id FROM songs WHERE album IN ($albumIdList)");
        $songIds = [];
        
        while($songRow = mysqli_fetch_array($songsQuery)) {
            $songIds[] = $songRow['id'];
        }
        
        if(count($songIds) > 0) {
            $songIdList = implode(',', $songIds);
            
            // Delete playlist entries for these songs
            mysqli_query($con, "DELETE FROM playlistsongs WHERE songId IN ($songIdList)");
            
            // Delete the songs
            mysqli_query($con, "DELETE FROM songs WHERE id IN ($songIdList)");
        }
        
        // Delete albums by this artist
        mysqli_query($con, "DELETE FROM albums WHERE artist = '$artistId'");
    }
    
    // Also delete songs by this artist that might not be in the albums
    $otherSongsQuery = mysqli_query($con, "SELECT id FROM songs WHERE artist = '$artistId'");
    $otherSongIds = [];
    
    while($songRow = mysqli_fetch_array($otherSongsQuery)) {
        $otherSongIds[] = $songRow['id'];
    }
    
    if(count($otherSongIds) > 0) {
        $otherSongIdList = implode(',', $otherSongIds);
        
        // Delete playlist entries for these songs
        mysqli_query($con, "DELETE FROM playlistsongs WHERE songId IN ($otherSongIdList)");
        
        // Delete the songs
        mysqli_query($con, "DELETE FROM songs WHERE id IN ($otherSongIdList)");
    }
    
    // Finally, delete the artist
    $deleteQuery = mysqli_query($con, "DELETE FROM artists WHERE id = '$artistId'");
                
    if($deleteQuery) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Database error: " . mysqli_error($con)]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Artist ID not provided"]);
}
?>
