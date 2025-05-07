<?php
ob_start();

if (session_status() == PHP_SESSION_NONE) {

    if (session_status() == PHP_SESSION_NONE) {
        session_set_cookie_params([
            'lifetime' => 86400, // 1 day
            'path' => '/',
            'domain' => $_SERVER['HTTP_HOST'],
            'secure' => isset($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
        session_start();
    }; 
}

date_default_timezone_set("Asia/Calcutta");

$con = mysqli_connect("localhost", "root", "", "spotplayer");
if (mysqli_connect_errno()) {
    echo "Connection Error: Couldn't establish a connection! " . mysqli_connect_errno();
    exit();
}
?>