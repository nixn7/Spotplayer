<?php
include("../../config.php");

if (isset($_POST['playlistId'])) {
    $playlistId = $_POST['playlistId'];

    $deletePlaylist = $con->prepare("DELETE FROM playlists WHERE id = ?");
    $deletePlaylist->bind_param("i", $playlistId);

    $deleteSongs = $con->prepare("DELETE FROM playlistSongs WHERE playlistId = ?");
    $deleteSongs->bind_param("i", $playlistId);

    if ($deletePlaylist->execute() && $deleteSongs->execute()) {
        echo ""; // Success: empty 
    } else {
        echo "Error deleting playlist: " . $con->error; 
    }
    $deletePlaylist->close();
    $deleteSongs->close();
} else {
    echo "PlaylistId was not passed into file"; 
}

$con->close();
?>