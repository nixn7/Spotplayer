<?php 
 include("../../config.php");


 if(isset($_POST['name']) && isset($_POST['username'])){
    $name = $_POST['name'];
    $username = $_POST['username'];
    $date = date("Y-m-d H:i:s");
    $query = mysqli_query($con, "INSERT INTO playlists VALUES('', '$name', '$username', '$date')");

    if($query) {
        echo "success";
    }
    else {
        echo "Error creating playlist: " . mysqli_error($con);
    }
 }
 else{
    echo "Name or username parameters not passed into file";
 }
?>
