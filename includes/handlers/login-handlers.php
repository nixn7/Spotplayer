



<?php
if (isset($_POST['loginButton'])) {
    $username = $_POST['loginUsername'];
    $password = $_POST['loginPassword'];

    $result = $account->login($username, $password);

    if ($result['status'] == 1) {
        $_SESSION['userLoggedIn'] = $username;
        
        if ($result['is_admin']) {
            
            $_SESSION['isAdmin'] = true;
            header("Location: index.php"); 
        } else {
            header("Location: index.php");
        }
    } 
}
?>
