<?php include "includes/includedFile.php" ?>

<!-- MAIN_CONTENT_CONTAINER  -->
<main id="mainContentContainer" class="w-full bg-[#121212] rounded-[8px] p-2 overflow-y-auto h-full max-h-[80vh]"
    style="min-width: 600px;">
    <h2 class="font-extrabold font-spmix text-white text-3xl p-2 pl-6 ">Popular Albums</h2>

    <!-- ALBUM_WRAPPER  -->
    <div class=" grid grid-cols-5 gap-2 p-4 py-4">
        <!-- FETCHING & DISPLAY ALBUMs DATABASE  -->
        <?php
        $albumQuery = mysqli_query($con, "SELECT * FROM albums ORDER BY RAND() LIMIT 20");
        while ($row = mysqli_fetch_array($albumQuery)) {

            // CARDS
            echo "<div class='p-2 rounded-[8px] transition-all duration-300  hover:animate-pulse hover:shadow-[0px_0px_30px_1px_rgba(255,255,255,0.20)] h-[300px] w-[220px]'>
      
      <span role='link' tabindex='0' onclick='openPage(\"album.php?id=" . $row['id'] . "\")'>

                      <img class='rounded-md' src='" . $row['artworkPath'] . "'>
                      <p class=' text-center font-spmix font-semibold text-[#b3b3b3] mt-2 '>" . $row['title'] . "</p>
                </span>
            </div>";
        }
        ?>
    </div><!-- ALBUM_WRAPPER_END  -->

</main>
<!--MAIN_CONTENT_CONTAINER_END  -->

</div><!-- MID ROW WRAPPER END  -->