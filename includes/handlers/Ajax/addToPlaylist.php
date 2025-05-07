<?php
header('Content-Type: application/json');
include("../../config.php");

if(isset($_POST['playlistId']) && isset($_POST['songId'])) {
    $playlistId = $_POST['playlistId'];
    $songId = $_POST['songId'];
    
    $orderIdQuery = mysqli_query($con, "SELECT COALESCE(MAX(playlistOrder), 0) + 1 as playlistOrder FROM playlistSongs WHERE playlistId='$playlistId'");
    $row = mysqli_fetch_array($orderIdQuery);
    $order = $row['playlistOrder'];
    
    $query = mysqli_query($con, "INSERT INTO playlistSongs (songId, playlistId, playlistOrder) VALUES('$songId', '$playlistId', '$order')");
    
    if($query) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($con)]);
    }
}
else {
    echo json_encode(["status" => "error", "message" => "PlaylistId or songId was not passed"]);
}
?>