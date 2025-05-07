<?php
include("../../config.php");

if (isset($_POST['songId']) && isset($_POST['playlistId'])) {
    $songId = $_POST['songId'];
    $playlistId = $_POST['playlistId'];

    $query = $con->prepare("DELETE FROM playlistSongs WHERE songId = ? AND playlistId = ?");
    $query->bind_param("ii", $songId, $playlistId);

    if ($query->execute()) {
        echo "Success";
    } else {
        echo "Error: " . $query->error;
    }

    $query->close();
} else {
    echo "Invalid request.";
}
?>
