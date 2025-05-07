<?php
include("../../config.php");

if(isset($_GET['id'])) {
    $artistId = $_GET['id'];
    
    $query = mysqli_query($con, "SELECT * FROM artists WHERE id='$artistId'");
    
    if(mysqli_num_rows($query) == 0) {
        echo json_encode(["error" => "Artist not found"]);
        exit();
    }
    
    $artistData = mysqli_fetch_array($query);
    echo json_encode($artistData);
}
else {
    echo json_encode(["error" => "Artist ID not provided"]);
}
?>
