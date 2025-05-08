<?php include "includes/includedFile.php" ?>

<!-- MAIN_CONTENT_CONTAINER  -->
<main id="mainContentContainer" class="w-full bg-[#121212] border border-[#2a2a2a] rounded-[8px] p-2 overflow-y-auto h-full max-h-[80vh]"
  style="min-width: 600px;">
  
  <h2 class="font-extrabold font-spmix text-white text-3xl p-2 pl-6 ">Popular Albums</h2>

  <!-- ALBUM_WRAPPER  -->
  <div class="overflow-x-auto grid grid-cols-5 gap-2 p-4 py-4">
    <!-- FETCHING & DISPLAY ALBUMs DATABASE  -->
    <?php
    $albumQuery = mysqli_query($con, "SELECT * FROM albums ORDER BY RAND() LIMIT 5");
    while ($row = mysqli_fetch_array($albumQuery)) {

      // CARDS
      echo "<div class='p-2 rounded-[8px] transition-all duration-300  hover:animate-pulse hover:shadow-[0px_0px_30px_1px_rgba(255,255,255,0.20)] h-[250px] w-[220px]'>
      <span role='link' tabindex='0' onclick='openPage(\"album.php?id=" . $row['id'] . "\")'>
                      <img class='rounded-md bg-cover overflow-hidden object-cover h-[200px] w-full' src='" . $row['artworkPath'] . "'>
                      <p class=' text-center font-spmix font-semibold text-[#b3b3b3] mt-2 '>" . $row['title'] . "</p>
                </span>
            </div>";
    }
    ?>
  </div><!-- ALBUM_WRAPPER_END  -->

  <h2 class="font-extrabold font-spmix text-white text-3xl p-2 pl-6 ">New Rising</h2>

  <div class="overflow-x-auto grid grid-cols-5 gap-2 p-4 py-4">
    <!-- FETCHING & DISPLAY ALBUMs DATABASE  -->
    <?php
    $albumQuery = mysqli_query($con, "SELECT * FROM albums ORDER BY RAND() LIMIT 5");
    while ($row = mysqli_fetch_array($albumQuery)) {

      // CARDS
      echo "<div class='p-2 rounded-[8px] transition-all duration-300  hover:animate-pulse hover:shadow-[0px_0px_30px_1px_rgba(255,255,255,0.20)] h-[250px] w-[220px]'>
      <span role='link' tabindex='0' onclick='openPage(\"album.php?id=" . $row['id'] . "\")'>
                      <img class='rounded-md bg-cover overflow-hidden object-cover h-[200px] w-full' src='" . $row['artworkPath'] . "'>
                      <p class=' text-center font-spmix font-semibold text-[#b3b3b3] mt-2 '>" . $row['title'] . "</p>
                </span>
            </div>";
    }
    ?>
  </div>

  <h2 class="font-extrabold font-spmix text-white text-3xl p-2 pl-6 ">Rock Specials</h2>

  <div class="overflow-x-auto grid grid-cols-5 gap-2 p-4 py-4">
    <!-- FETCHING & DISPLAY ALBUMs DATABASE  -->
    <?php
    $albumQuery = mysqli_query($con, "SELECT * FROM albums WHERE genre='1' ORDER BY RAND() LIMIT 5");
    while ($row = mysqli_fetch_array($albumQuery)) {

      // CARDS
      echo "<div class='p-2 rounded-[8px] transition-all duration-300  hover:animate-pulse hover:shadow-[0px_0px_30px_1px_rgba(255,255,255,0.20)] h-[250px] w-[220px]'>
      <span role='link' tabindex='0' onclick='openPage(\"album.php?id=" . $row['id'] . "\")'>
                      <img class='rounded-md bg-cover overflow-hidden object-cover h-[200px] w-full' src='" . $row['artworkPath'] . "'>
                      <p class=' text-center font-spmix font-semibold text-[#b3b3b3] mt-2 '>" . $row['title'] . "</p>
                </span>
            </div>";
    }
    ?>
  </div>

  <h2 class="font-extrabold font-spmix text-white text-3xl p-2 pl-6 ">Nu Metal Charts</h2>

  <div class="overflow-x-auto grid grid-cols-5 gap-2 p-4 py-4">
    <!-- FETCHING & DISPLAY ALBUMs DATABASE  -->
    <?php
    $albumQuery = mysqli_query($con, "SELECT * FROM albums WHERE genre='3' ORDER BY RAND() LIMIT 5");
    while ($row = mysqli_fetch_array($albumQuery)) {

      // CARDS
      echo "<div class='p-2 rounded-[8px] transition-all duration-300  hover:animate-pulse hover:shadow-[0px_0px_30px_1px_rgba(255,255,255,0.20)] h-[250px] w-[220px]'>
      <span role='link' tabindex='0' onclick='openPage(\"album.php?id=" . $row['id'] . "\")'>
                      <img class='rounded-md bg-cover overflow-hidden object-cover h-[200px] w-full' src='" . $row['artworkPath'] . "'>
                      <p class=' text-center font-spmix font-semibold text-[#b3b3b3] mt-2 '>" . $row['title'] . "</p>
                </span>
            </div>";
    }
    ?>
  </div>
  
</main>
<!--MAIN_CONTENT_CONTAINER_END  -->

</div><!-- MID ROW WRAPPER END  -->