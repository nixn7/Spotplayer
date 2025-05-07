<?php
include("../../config.php");

if(isset($_GET['id'])) {
    $songId = $_GET['id'];
    
    $query = mysqli_query($con, "SELECT * FROM songs WHERE id='$songId'");
    
    if(mysqli_num_rows($query) == 0) {
        echo json_encode(["error" => "Song not found"]);
        exit();
    }
    
    $songData = mysqli_fetch_array($query);
    echo json_encode($songData);
}
else {
    echo json_encode(["error" => "Song ID not provided"]);
}
?>
