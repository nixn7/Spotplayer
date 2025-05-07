<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    include "includes/config.php";
    include "includes/classes/User.php";
    include "includes/classes/Account.php";
    include "includes/classes/Artist.php";
    include "includes/classes/Album.php";
    include "includes/classes/Song.php";
    include "includes/classes/Playlist.php";

    if(isset($_SESSION['userLoggedIn'])){
        $userLoggedIn = new User($con, $_SESSION['userLoggedIn']);
    } else {
        echo "Username variable was not passed into page. Check the openPage JS function"; 
    }

} else {
    include("includes/pages/header.php");
    include("includes/pages/footer.php");

    $url = $_SERVER['REQUEST_URI'];
    echo "<script>openPage('$url')</script>";
    // die();
}


?>