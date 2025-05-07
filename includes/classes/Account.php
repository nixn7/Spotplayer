<?php

class Account
{
    private $con;
    private $errorArray = array();
    public function __construct($con)
    {
        $this->con = $con;
        $this->errorArray = array();
    }

    // LOGIN FUNCTION -----------------------------------------------
    public function login($un, $pw)
    {
        $pw = md5($pw); 
        $query = mysqli_query($this->con, "SELECT * FROM users WHERE username = '$un' AND password = '$pw'");
    
        if (mysqli_num_rows($query) == 1) {
            $userData = mysqli_fetch_assoc($query);
            // Check if the user is an admin
            if ($userData['is_admin'] == 1) {
                return array('status' => 1, 'is_admin' => true);
            } else {
                return array('status' => 1, 'is_admin' => false);
            }
        } else {
            array_push($this->errorArray, Constants::$loginFailed);
            return array('status' => 0, 'is_admin' => false);
        }
    }

    public function register($un, $fn, $ln, $em, $pw, $pw2)
    {
        $this->validateUsername($un);
        $this->validateFirstName($fn);
        $this->validateLastName($ln);
        $this->validateEmail($em);
        $this->validatePasswords($pw, $pw2);

        if (empty($this->errorArray) == true) {
            //INSERT INTO DB
            return $this->insertUserDetails($un, $fn, $ln, $em, $pw);
        } else {
            return false;
        }
    }

    public function getError($error)
    {
        if (!in_array($error, $this->errorArray)) {
            $error = "";
        }
        return "<span class='errorMessage'>$error</span>";
    }
    // WORK ON THIS ------------------------------------------------------------------------------>
    // public function getProfilePic($username){
    //     $profilepicquery = mysqli_query($this->con,"SELECT profilePic FROM users WHERE username='$username' ");
    //     $row = mysqli_fetch_array($profilepicquery);
    //     return $row['profilePic'];
    // }

    public function getUsername() {
        return $this->username;
    }

    private function insertUserDetails($un, $fn, $ln, $em, $pw)
    {

        // ----------------------------------------------------------------------------------------------------------------------------------------
        //PASSWORD ENCRYPTION ( decent : find better )
        $encryptedPw = md5($pw);

        // DEFAULT PROFILE PIC ADDITION 
        $profilePic = "assets/images/profile-pics/human_icon.png";

        // DATE FORMATTING 
        $date = date("Y-m-d");

        // INSERTION TO DB 
        $result = mysqli_query($this->con, "INSERT INTO users VALUES('','$un','$fn','$ln','$em','$encryptedPw','$date','$profilePic',0)");
        return $result;
    }

    private function validateUsername($un)
    {
        if (strlen($un) > 25 || strlen($un) < 4) {
            array_push($this->errorArray, Constants::$userNameCharacters);
            return;
        }

        $checkUsernameQuery = mysqli_query($this->con, "SELECT username FROM users WHERE username = '$un'");
        if (mysqli_num_rows($checkUsernameQuery) != 0) {
            array_push($this->errorArray, Constants::$usernameTaken);
        }
    }
    private function validateFirstName($fn)
    {
        if (strlen($fn) > 25 || strlen($fn) < 2) {
            array_push($this->errorArray, Constants::$firstNameCharacters);
            return;
        }
    }
    private function validateLastName($ln)
    {
        if (strlen($ln) > 25 || strlen($ln) < 2) {
            array_push($this->errorArray, Constants::$lastNameCharacters);
            return;
        }
    }
    private function validateEmail($em)
    {

        if (!filter_var($em, FILTER_VALIDATE_EMAIL)) {
            array_push($this->errorArray, Constants::$emailInvalid);
            return;
        }

        $checkEmailQuery = mysqli_query($this->con, "SELECT email FROM users WHERE email = '$em'");
        if (mysqli_num_rows($checkEmailQuery) != 0) {
            array_push($this->errorArray, Constants::$emailTaken);
        }

    }

    private function validatePasswords($pw, $pw2)
    {

        if ($pw != $pw2) {
            array_push($this->errorArray, Constants::$passwordsDoNotMatch);
            return;
        }

        if (preg_match('/[^A-Za-z0-9]/', $pw)) {
            array_push($this->errorArray, Constants::$passwordsNotAlphanumeric);
            return;
        }

        if (strlen($pw) > 25 || strlen($pw) < 5) {
            array_push($this->errorArray, Constants::$passwordsCharacters);
            return;
        }
    }
}

?>