<?php
include("../../config.php");

if(isset($_GET['id'])) {
    $albumId = $_GET['id'];
    
    $query = mysqli_query($con, "SELECT * FROM albums WHERE id='$albumId'");
    
    if(mysqli_num_rows($query) == 0) {
        echo json_encode(["error" => "Album not found"]);
        exit();
    }
    
    $albumData = mysqli_fetch_array($query);
    echo json_encode($albumData);
}
else {
    echo json_encode(["error" => "Album ID not provided"]);
}
?>
