<?php
include("includes/config.php");
include("includes/classes/Account.php");
include("includes/classes/Constants.php");

$account = new Account($con);

include("includes/handlers/register-handlers.php");
include("includes/handlers/login-handlers.php");

function getInputValue($name)
{
    if (isset($_POST[$name])) {
        echo $_POST[$name];
    }
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sounx Register</title>

    <!-- TAILWIND INIT ------------------------------------------- -->
    <!-- <link rel="stylesheet" href="register_style.css"> -->
    <link rel="stylesheet" href="tailwind.css">

    <!-- FONTS -->


    <script src="includes/js/jquery.min.js"></script>
    <script src="includes/js/register.js"></script>
</head>

<body class="font-cirmd">

    <!-- FORM show/hide on CLICK --------------------------------------------------->
    <?php

    if (isset($_POST['registerButton'])) {
        echo '<script>
        $(document).ready(function() {
            $("#loginForm").hide();
            $("#registerForm").show();
        });
        </script>';
    } else {
        echo '<script>
            $(document).ready(function() {
                $("#loginForm").show();
                $("#registerForm").hide();
            });
        </script>';
    }

    ?>

    <!-- BODY --------------------------------------------------------------------------->


    <!-- /* green base - #1ed760 */
/* green highlight - #3be477 */
/* green press - #1abc54 */no
/* dark - #121212 */
/* gray - #535353 */
/* light-gray - #b3b3b3 */ -->



    <div class="main_bg bg-[#121212] min-h-screen text-white flex justify-center ">

        <div class="flex w-4/6 p-5 shadow-lg wrapper ">

            <!-- CONTENT SECTION  -->

            <!-- IMAGE SECTION -->
            <div class="flex w-1/2 bg-center bg-no-repeat bg-cover image"
                style="background-image:url('includes/assets/images/swan.jpeg');  ">
                <div class="flex bottom-0 left-0 h-full items-end">
                    <P class="flex text-4xl pl-4 text-[#1db945] mb-4 font-spmix font-extrabold ">Sounx – Audiophile-grade Streaming. <br> No Ads. <br>No Noise.</P>
                </div>
            </div>

            <div id="inputContainer" class="flex justify-center w-1/2 p-5 ">


                <!-- LOGIN_FORM_HERE -->
                <form id="loginForm" action="register.php" method="POST"
                    class="flex flex-col items-center justify-center w-full p-5 ">


                    <div class="flex flex-col justify-center w-full top ">

                        <div
                            class="flex justify-center items-center  text-5xl font-extrabold gap-2 font-spmix mb-2 text-[#1db945] ">
                            <img src="includes/assets/icons/sounx-green.svg" alt="spotify_logo" class="w-10 h-10 mx-1 ">
                            <span>SOUNX</span>

                        </div>
                        <h4
                            class="flex  mx-auto justify-center mb-10 mt-2 text-2xl font-spmix font-extrabold text-[#b3b3b3] tracking-widest  ">
                            LOGIN</h4>
                    </div>

                    <div class="flex flex-col items-center justify-center w-full py-5 mt-5">

                        <div class="justify-center w-4/6 ">

                            <?php echo $account->getError(Constants::$loginFailed); ?>
                            <label for="loginUsername" class=" cursor-pointer block font-cirbo  text-[#1db945] text-xs tracking-wider"
                                required autocomplete="off">Username</label>
                            <input type="text" name="loginUsername" id="loginUsername" requireds
                                value="<?php getInputValue('loginUsername') ?>"
                                class=" bg-transparent  text-[#1db945] font-cirbook font-medium w-full tracking-wider focus:outline-none  border-b border-[#535353]   pl-2 pr-3  ">
                        </div>


                        <div class="justify-center w-4/6 mt-2 ">

                            <label for="loginPassword"
                                class=" cursor-pointer  block font-cirbo text-[#1db945] text-xs tracking-wider ">Password</label>
                            <input type="password" name="loginPassword" id="loginPassword" required
                                class=" bg-transparent font-cirbook text-[#1db945] font-medium w-full tracking-wider focus:outline-none  border-b border-[#535353]   pl-2 pr-3  ">
                        </div>


                        <!-- LOGIN BUTTON -->
                        <button type="submit" name="loginButton"
                            class=" block w-4/6 px-3 mt-8 py-2 text-sm text-black bg-[#1db945] hover:bg-opacity-50  font-cirbo font-bold  rounded-full  ">LOG
                            IN</button>

                        <h6 id="hideLogin"
                            class="mt-2 text-sm text-[#1db945]  tracking-wider cursor-pointer underline  decoration-solid  hover:text-opacity-50 ">
                                Don't have an Account ? Signup here.</h6>

                    </div>

                </form>

                <!-- REGISTERATION FORM HERE -->
                <form id="registerForm" action="register.php" method="POST"
                    class="flex-col items-center justify-center hidden h-full pt-8 ">

                    <!-- TOP BRANDING -->
                    <div class="flex flex-col top ">

                        <div
                            class="  flex justify-center items-center text-5xl font-extrabold  font-spmix mt-4 mb-4 text-[#1db945]  m-auto ">
                            <img src="spotify-main/assets/logo.png" alt="spotify_logo" class="mx-1 h-11 w-11 ">
                            <span>Spotify</span>

                        </div>
                    </div>
                    <div class="flex flex-col items-center mt-8 formElements ">
                        <h4 class="flex justify-center text-lg font-cirmd font-semibold text-[#b3b3b3] tracking-wide ">
                            Create a new account
                        </h4>
                        <!-- FIRST_NAME -->
                        <div class="flex flex-col justify-center w-4/6 mx-20 mt-8 ">
                            <?php echo $account->getError(Constants::$firstNameCharacters); ?>
                            <label class=" block cursor-pointer  text-[#1db945] text-sm font-cirbook font-semibold "
                                for="firstname">First
                                Name</label>
                            <input type=" text" name="firstname" id="firstname" placeholder="eg. John"
                                value="<?php getInputValue('firstname') ?>" required
                                class="  bg-transparent  text-[#1db945] font-cirbo font-semibold focus:outline-none  border-b border-[#] placeholder:font-bold placeholder:text-[#1db945] placeholder:text-opacity-50  placeholder:text-xs placeholder  pl-2 pr-3 ">
                        </div>
                        <!-- LAST_NAME -->
                        <div class="flex flex-col justify-center w-4/6 mx-20 mt-5 ">
                            <?php echo $account->getError(Constants::$lastNameCharacters); ?>
                            <label class=" block cursor-pointer  text-[#1db945] text-sm font-cirbook font-semibold "
                                for="lastname">Last Name</label>
                            <input type="text" name="lastname" id="lastname" placeholder="eg. Doe"
                                value="<?php getInputValue('lastname') ?>" required
                                class=" bg-transparent  text-[#1db945] font-cirbo font-semibold focus:outline-none  border-b border-[#]  placeholder:font-bold  placeholder:text-[#1db945] placeholder:text-opacity-50 placeholder:text-xs  pl-2 pr-3 ">
                        </div>
                        <!-- USER_NAME -->
                        <div class="flex flex-col justify-center w-4/6 mx-20 mt-5 ">
                            <?php echo $account->getError(Constants::$userNameCharacters); ?>
                            <?php echo $account->getError(Constants::$usernameTaken); ?>

                            <label class=" block cursor-pointer  text-[#1db945] text-sm font-cirbook font-semibold "
                                for="username">Username</label>
                            <input type="text" name="username" id="username" placeholder="eg. johndoe"
                                value="<?php getInputValue('username') ?>" required
                                class=" bg-transparent  text-[#1db945] font-cirbo font-semibold focus:outline-none  border-b border-[#] placeholder:font-bold   placeholder:text-[#1db945] placeholder:text-opacity-50 placeholder:text-xs  pl-2 pr-3 ">
                        </div>
                        <!-- EMAIL -->
                        <div class="flex flex-col justify-center w-4/6 mx-20 mt-5 ">
                            <?php echo $account->getError(Constants::$emailInvalid); ?>
                            <?php echo $account->getError(Constants::$emailTaken); ?>
                            <label class=" block cursor-pointer  text-[#1db945] text-sm font-cirbook font-semibold "
                                for="email">Email</label>
                            <input type="email" name="email" id="email" placeholder="eg.JohnDoe@gmail.com"
                                value="<?php getInputValue('email') ?>" required
                                class=" bg-transparent  text-[#1db945] font-cirbo font-semibold focus:outline-none  border-b border-[#] placeholder:font-bold   placeholder:text-[#1db945] placeholder:text-opacity-50 placeholder:text-xs  pl-2 pr-3">
                        </div>
                            <!-- PASSWORD -->
                        <div class="flex flex-col justify-center w-4/6 mx-20 mt-5 ">
                                <?php echo $account->getError(Constants::$passwordsDoNotMatch); ?>
                                <?php echo $account->getError(Constants::$passwordsCharacters); ?>
                                <?php echo $account->getError(Constants::$passwordsNotAlphanumeric); ?>
                                <label class=" block cursor-pointer  text-[#1db945] text-sm font-cirbook font-semibold "
                                    for="password">Password</label>
                                <input type="password" name="password" id="password" required
                                    class=" bg-transparent tracking-widest  text-[#1db945] font-cirbo font-semibold focus:outline-none  border-b border-[#]  pl-2 pr-3  ">
                        </div>
                            <!--CONFIRM_PASSWORD -->
                        <p class="flex flex-col justify-center w-4/6 mx-20 mt-5 ">
                                <label class=" block cursor-pointer  text-[#1db945] text-sm font-cirbook font-semibold "
                                    for="password2">Confirm Password</label>
                                <input type="password" name="password2" id="password2" required
                                    class="  bg-transparent tracking-widest  text-[#1db945] font-cirbo font-semibold focus:outline-none  border-b border-[#]     pl-2 pr-3  ">
                        </p>

                            <!-- SIGN_UP_BUTTON -->
                        <button type="Submit" name="registerButton"
                                class=" w-4/6 px-3 mt-8 py-2 text-sm text-[#121212]  hover:bg-opacity-50 bg-[#1db945] font-cirbo font-bold  rounded-full flex justify-center items-center ">SIGN UP
                        </button>
                            
                        <div class="hasAccountText">
                                <span id="hideRegister"
                                class="mt-2 text-sm text-[#1db945] cursor-pointer underline  decoration-solid  hover:text-opacity-50 ">Already
                                    have an Account ? Login here
                                </span>
                        </div>
                    </div>
                </form>



            </div>

        </div>
</body>

</html>