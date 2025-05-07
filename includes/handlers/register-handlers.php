<?php

function cleanFormUsername($inputText)
{
    $inputText = strip_tags($inputText);
    $inputText = str_replace(" ", "", $inputText);
    return $inputText;
}

function cleanFormLastnameString($inputText)
{
    $inputText = strip_tags($inputText);
    $inputText = str_replace(" ", "", $inputText);
    // $inputText = ucfirst(strtolower($inputText));
    return $inputText;
}
function cleanFormString($inputText)
{
    $inputText = strip_tags($inputText);
    $inputText = str_replace(" ", "", $inputText);
    // $inputText = ucfirst(strtolower($inputText));
    return $inputText;
}

function cleanFormEmailString($inputText)
{
    $inputText = strip_tags($inputText);
    $inputText = str_replace(" ", "", $inputText);
    // $inputText = strtolower($inputText);
    return $inputText;
}

function cleanFormPassword($inputText)
{
    $inputText = strip_tags($inputText);
    return $inputText;
}



if (isset($_POST['registerButton'])) {
    $username = cleanFormUsername($_POST['username']);
    $firstname = cleanFormString($_POST['firstname']);
    $lastname = cleanFormLastnameString($_POST['lastname']);
    $email = cleanFormEmailString($_POST['email']);
    $password = cleanFormPassword($_POST['password']);
    $password2 = cleanFormPassword($_POST['password2']);

    $wasSuccessful = $account->register($username, $firstname, $lastname, $email, $password, $password2);

    if ($wasSuccessful) {
        $_SESSION['userLoggedIn'] = $username;

        header("Location: register.php");

    }

}

?>