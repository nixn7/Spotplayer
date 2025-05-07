<?php
include "includes/config.php";

// session_destroy();

if (isset($_SESSION['userLoggedIn'])) {
    $userLoggedIn = $_SESSION['userLoggedIn'];
} else
    header("Location: register.php");

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="includes/assets/csss/index_style.css" />
    <title>Welcome to Spotify</title>

    <!-- WEBKIT SCROLLBAR STYLE  -->
    <style>
        ::-webkit-scrollbar {
            width: 1em;
        }

        ::-webkit-scrollbar-track {
            background-color: #121212;
            border-radius: 8px;
        }

        ::-webkit-scrollbar-thumb {
            background-color: #2a2a2a;
            border-radius: 8px;
        }
    </style>
</head>


<body>
    <div #id="mainContainer"
        class=" flex flex-col gap-2 h-screen w-full text-[#b3b3b3] bg-black overflow-hidden px-[4px] ">

        <!-- TOPBAR -->
        <section #id="navTopBar" class="flex items-center justify-center w-full mt-2 bg-black">

            <div #id="topWrapper" class="flex items-center justify-between w-full gap-2">

                <!-- TOP_LEFT/BRAND  -->
                <div #id="brand_left" class="">
                    <button #id="spotify_button" class="">
                        <img src="includes/assets/icons/Spotify.svg" class="ml-4" alt="spotify_icon" />
                    </button>
                </div>
                <!-- TOP_LEFT/BRAND_END  -->

                <!-- TOP_CENTER  -->
                <div #id="top_center" class="relative flex items-center justify-center gap-2">
                    <a href="index.php">
                        <div #id="home"
                            class="bg-[#1f1f1f] hover:bg-[#2a2a2a] m-auto p-[12px] rounded-full transition duration-300 ">
                            <img src="includes/assets/icons/home.svg" alt="" />
                        </div>
                    </a>
                    <!-- SEARCH BAR  -->
                    <!-- SEARCH BAR FOCUS SCRIPT  -->
                    <script>
                        function focusSearchbox() {
                            document.getElementById("topSearchbox").focus();
                        }
                    </script>

                    <img src="includes/assets/icons/Search Icon.svg" class="absolute cursor-pointer inset-x-16 h-9 w-9"
                        onclick="focusSearchbox()" for="searchbox" alt="" />
                    <input type="text" id="topSearchbox" name="searchbox" placeholder="What do you want to play?"
                        class="text-white font-cirbook font-semibold placeholder:font-cirbo placeholder:font-semibold placeholder:text-[14px] placeholder-[#b3b3b3] bg-[#1f1f1f] h-[48px] w-[475px] rounded-full py-[12px] px-12 m-auto hover:bg-[#2a2a2a] transition duration-300  " />
                </div>
                <!-- TOP_CENTER_END  -->

                <!-- PROFILE  -->
                <div
                    class="bg-[#1f1f1f] hover:bg-[#2a2a2a]  flex p-[5px] items-center rounded-full mr-2 cursor-pointer transition duration-300  ">
                    <span class="mx-2 font-extrabold font-spmix text-[#1db945] "><?php echo $userLoggedIn ?></span>
                    <button>
                        <img src="includes/assets/icons/Spotify.svg" alt="user_Profile_picture">
                    </button>
                </div>
                <!-- PROFILE_END  -->

            </div>
            <!-- TOPWRAPPER_END  -->
        </section>
        <!-- TOPBAR_END  -->








    </div>





</body>

</html>