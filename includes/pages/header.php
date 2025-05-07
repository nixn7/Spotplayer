<?php
include "includes/config.php";
include "includes/classes/Playlist.php";
include "includes/classes/Account.php";
include "includes/classes/User.php";
// Check if user is admin
$isAdmin = false;
if (isset($_SESSION['userLoggedIn'])) {
  $userLoggedIn = $_SESSION['userLoggedIn'];
  $username = $userLoggedIn;
  $query = mysqli_query($con, "SELECT is_admin FROM users WHERE username='$username'");
  if ($query) {
    $row = mysqli_fetch_array($query);
    if ($row && $row['is_admin'] == 1) {
      $isAdmin = true;
    }
  }
}
include "includes/classes/Artist.php";
include "includes/classes/Album.php";
include "includes/classes/Song.php";



if (isset($_SESSION['userLoggedIn'])) {
  $userLoggedIn = $_SESSION['userLoggedIn'];
  echo "<script>userLoggedIn = '$userLoggedIn';</script>";
} else
  header("Location: register.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="tailwind.css">
  <link rel="stylesheet" href="includes/assets/csss/index_style.css" />
  <!-- <link rel="stylesheet" href="includes/assets/csss/index_style.css"> -->
  <link rel="stylesheet" href="album_input.css" />
  <link rel="stylesheet" href="tw3.4.5.css" />
  <script src="includes/js/Tone.min.js"></script>
  <script src="includes/js/jquery.min.js"></script>
  <script src="includes/js/Audio.js"></script>
  <script src="includes/js/script.js"></script>
  <script>
    if (typeof jQuery == 'undefined') {
      alert('jQuery is not loaded');
    }
  </script>
  <title>Sounx : Your Sounds, Amplified!</title>

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

<body class="box-border flex w-full min-h-screen p-0 m-0 font-medium font-spmix">


  <!-- MAIN DIV -->
  <div #id="mainContainer"
    class=" flex flex-col gap-2 h-screen w-full text-[#b3b3b3] bg-black overflow-hidden px-[4px] ">
    <!-- TOPBAR -->
    <section #id="navTopBar" class="flex items-center justify-center w-full mt-2  bg-black" style="min-width: 800px;">

      <div #id="topWrapper" class="flex items-center justify-between w-full gap-2 ">

        <!-- TOP CORNER LOGO  -->
        <a href="index.php" role="link">
          <div #id="brand_left" class="flex  ">
            <button #id="spotify_button" class="flex gap-2 ">
              <img src="includes/assets/icons/sounx-white.svg" class="ml-4" alt="spotify_icon" />
              <span class="text-2xl tracking-wider text-white font-extrabold">SOUNX</span>
            </button>
          </div>
        </a>

        <div #id="top_center" class="relative flex items-center justify-center  cursor-pointer gap-2 select-none">
          <span role="link" tabindex="0" onclick="openPage('index.php')">
            <div #id="home"
              class="bg-[#1f1f1f] hover:bg-[#2a2a2a] m-auto p-[12px] rounded-full transition duration-300 ">

              <img src="includes/assets/icons/home.svg" alt="" />

            </div>
          </span>
          <!-- SEARCH BAR  -->
          <!-- SEARCH BAR FOCUS SCRIPT  -->
          <script>
            function focusSearchbox() {
              document.getElementById("searchbox").focus();
            }

          </script>

          <img src="includes/assets/icons/Search Icon.svg" class="absolute cursor-pointer inset-x-16 h-9 w-9"
            onclick="focusSearchbox()" for="searchbox" alt="" />
          <input type="text" id="searchbox" name="searchbox" placeholder="What do you want to play?"
            class="text-white font-cirbook font-semibold placeholder:font-cirbo placeholder:font-semibold placeholder:text-[14px] placeholder-[#b3b3b3] bg-[#1f1f1f] h-[48px] w-[475px] rounded-full py-[12px] px-12 m-auto hover:bg-[#2a2a2a] transition duration-300"
            onkeyup="performSearch(this.value)" autocomplete="off" />
          <div id="searchResults"
            class="absolute bg-[#121212]  top-[48px] text-black w-[1303.8px]  mt-2 p-2 rounded shadow-md hidden">
          </div>
        </div>


        <!-- SEARCH SCRIPT  -->
        <script>
          function performSearch(query) {
            let mainContent = document.getElementById("mainContentContainer");

            if (query.trim().length === 0) {
              openPage("index.php");
              // mainContent.innerHTML = "<h2 class='text-white h-full text-center text-xl font-extrabold '>Start typing to search...</h2>";
              return;
            }

            let xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
              if (xhr.readyState == 4 && xhr.status == 200) {
                mainContent.innerHTML = xhr.responseText;
              }
            };
            xhr.open("GET", "search.php?term=" + encodeURIComponent(query), true);
            xhr.send();
          }
        </script>

        <!-- SEARCH SCRIPT ENDS  -->

        <!-- ADMIN FEATURE  -->
        <?php if ($isAdmin) { ?>
          <span role='link' tabindex='0' onclick='openPage("admin.php")'>
            <div
              class='bg-[#1f1f1f] hover:bg-[#2a2a2a] flex p-[5px] items-center rounded-full mr-2 cursor-pointer transition duration-300'>
              <span class='Px-4 py-1 font-bold font-spmix text-white'>ADD</span>

            </div>
          </span>
          <span role='link' tabindex='0' onclick='openPage("edit_data.php")'>
            <div
              class='bg-[#1f1f1f] hover:bg-[#2a2a2a] flex p-[5px] items-center rounded-full mr-2 cursor-pointer transition duration-300'>
              <span class='Px-4 py-1 font-bold font-spmix text-white'>EDIT</span>

            </div>
          </span>
        <?php } ?>

        <!-- PROFILE  -->
        <div
          class="bg-[#1f1f1f] hover:bg-[#2a2a2a] flex p-[5px] items-center rounded-full mr-2 cursor-pointer transition duration-300  ">


          <span class="px-2 py-1 flex items-center justify-center font-extrabold font-spmix text-[#1db945] "><?php echo $userLoggedIn ?></span>
          <button>
            <!-- <img src="includes/assets/images/swan.jpeg" alt="user_Profile_picture" class="rounded-full size-8"> -->
          </button>
        </div>
      </div>
      <div
        class="bg-[#1f1f1f] hover:bg-[#2a2a2a] flex p-[5px] items-center rounded-full mr-2 cursor-pointer transition duration-300  ">
        <button class="rounded-full size-[28px] p-[2px] flex items-center justify-center"><a href="logout.php"><img
              src="includes/assets/icons/logout2.svg" alt=""></a></button>
      </div>
    </section>

    <!-- MID ROW WRAPPER  -->
    <div #id="sideMainWrapper" class="flex gap-2 px-[6px] overflow-hidden">
      <!-- SIDEBAR -->
      <section #id="sidebar"
        class=" flex flex-col bg-[#121212] border border-[#2a2a2a] w-1/5 rounded-[8px] overflow-hidden h-[80vh]"
        style="min-width: 200px;  max-width: 400px;">


        <!-- YOUR LIBRARY  -->
        <div #id="playlistTop" class="w-full h-36 px-4 pt-4  ">

          <div class="flex justify-between ">
            <span role="link" tabindex="0" onclick="openPage('yourlibrary.php')">
              <div #id="_wrap" class="flex gap-4 hover:text-white ">
                <img src="includes/assets/icons/library.svg" alt="Your library" />
                <p class="font-cirbo font-bold text-[18px] transition duration-300 cursor-pointer hover:text-white">
                  Your Library</p>
              </div>
            </span>
            <img src="includes/assets/icons/plus.svg" alt="addtoplaylist" onclick="openPage('createPlaylist.php')"
              class="hover:text-white hover:bg-[#2a2a2a] p-2 rounded-full hover:rotate-90 duration-300 " />
          </div>

          <span role="link" tabindex="0" onclick="openPage('index.php')">
            <div #id="_wrap" class="flex items-center gap-6  hover:text-white w-full cursor-pointer ">
              <img class="h-5" src="includes/assets/icons/Back Button.svg" alt="Home" />
              <p class="font-cirbo font-bold text-[18px] mt-1  transition duration-300 ">Browse</p>
            </div>
          </span>

          <hr class="mt-4 border-[#2a2a2a]">

        </div>

        <!-- PLAYLIST-->
        <div class="playlistBox flex flex-col p-2 tracking-wide overflow-y-auto h-[500px] rounded-[8px]">

          <!-- PLAYLIST BOXES  -->
          <?php
          // Direct database query to get playlists for the current user
          if (isset($userLoggedIn)) {
            // Get username
            $username = is_object($userLoggedIn) ? $userLoggedIn->getUsername() : $userLoggedIn;

            // Query to fetch playlists
            $playlistQuery = mysqli_query($con, "SELECT * FROM playlists WHERE owner='$username' ORDER BY name");

            if (mysqli_num_rows($playlistQuery) > 0) {
              while ($row = mysqli_fetch_array($playlistQuery)) {
                $playlistId = $row['id'];
                $name = $row['name'];
                $owner = $row['owner'];
                $coverPath = isset($row['coverPhoto']) ? $row['coverPhoto'] : null;
                $defaultImage = 'includes/assets/images/default-playlist.jpg';
                ?>
                <div
                  class="boxes flex flex-shrink-0 items-center w-full h-16 px-2 rounded-[6px] transiti duration-300 cursor-pointer hover:bg-[#2a2a2a]"
                  role="link" tabindex="0" onclick="openPage('playlist.php?id=<?php echo $playlistId; ?>')">
                  <div class="size-10 bg-cover bg-center bg-no-repeat rounded-[4px]"
                    style="background-image: url('<?php echo $coverPath ?: $defaultImage; ?>');">
                  </div>
                  <div class="ml-3 try">
                    <h4 class="text-[16px] font-spmix font-semibold text-white"><?php echo $name; ?></h4>
                    <h4 class="text-[12px] font-cirmd font-semibold tracking-widest "><?php echo $owner; ?></h4>
                  </div>
                </div>
                <?php
              }
            } else {
              ?>
              <div class="text-center py-4">
                <p>No playlists found</p>
                <span role="link" tabindex="0" onclick="openPage('createPlaylist.php')"
                  class="text-white hover:underline cursor-pointer">
                  Create one?
                </span>
              </div>
              <?php
            }
          }
          ?>














          <!-- <div class="boxes flex flex-shrink-0 items-center w-full h-16 px-2 rounded-[6px] hover:bg-[#2a2a2a]">
            <div class="size-10 bg-cover bg-center bg-no-repeat rounded-[4px]"
              style="background-image: url('includes/assets/images/sasuke\ uchiha-cool.jpg');">
            </div>
            <div class="ml-3">
              <h4 class="text-[16px] font-spmix font-semibold text-green">Braindead</h4>
              <h4 class="text-[12px] font-cirmd font-semibold tracking-widest ">NIXN</h4>
            </div>
          </div> -->



          <!-- PLAYLIST BOXES  -->

        </div>
        <!--PLAYLIST DIV END-->
      </section>