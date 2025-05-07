


<!-- PHP CODE  -->
<?php
$albumQuery = mysqli_query($con, "SELECT * FROM albums ORDER BY RAND() LIMIT 10");
while ($row = mysqli_fetch_array($albumQuery)) {

  // ALBUM DISPLAY DIV CODE --------------------------------------------
  echo "<div id='gridViewItem' class='grid grid-cols-2 gap-6'>
                    <img class='mb-2 size-16' src='" . $row['artworkPath'] . "'> 
                      <div id='gridViewInfo'>
                        " . $row['title'] . "
                      </div>
                  </div>";
}
?>







<div #id="sideMainWrapper" class=" flex  flex-1 gap-2 px-[6px] overflow-hidden ">
  <!-- sidebar  -->
  code here
  <!-- MAIN CONTENT  -->
  <main id="mainContentContainer" class="w-full  bg-[#121212] rounded-[8px] p-2 overflow-y-auto h-full max-h-[80vh]">
            <div class="container border mx-auto px-4 py-8">
              <div class="grid grid-cols-5 gap-4">
                
                <?php 
                  $albumQuery = mysqli_query($con,"SELECT * FROM albums ORDER BY RAND() LIMIT 10");
                while ($row = mysqli_fetch_array($albumQuery)) {
                  echo "<div id='wrapData' class=' mx-auto'><img class='' src='".$row['artworkPath']."'>
                        <p class=' mx-auto'>".$row['title']."</p>
                        </div>
                        ";
                }
                ?>
              </div>
            </div>
      </main>



</div>






          <?php
            $albumQuery = mysqli_query($con, "SELECT * FROM albums ORDER BY RAND() LIMIT 10");
            while ($row = mysqli_fetch_array($albumQuery)) {
              echo "<div id='wrapData' class=' mx-auto'><img class='' src='" . $row['artworkPath'] . "'>
                        <p class=' mx-auto'>" . $row['title'] . "</p>
                        </div>
                        ";
            }
          ?>




<main id="mainContentContainer"
        class="w-full bg-[#121212] rounded-[8px] p-2 overflow-y-auto h-full max-h-[80vh]  ">
        <h2 class="font-bold font-spmix text-white text-3xl p-2 pl-6 ">Popular nowadays</h2>
        <div class=" grid grid-cols-5 justify-center container px-4 py-4 min-h-screen  border">

            <!-- FETCHING & DISPLAY ALBUMs DATABASE  -->
          <?php
          $albumQuery = mysqli_query($con, "SELECT * FROM albums ORDER BY RAND() LIMIT 5");
          while ($row = mysqli_fetch_array($albumQuery)) {
            echo "<div class='flex flex-col w-[200px] h-[200px] justify-center p-2 rounded-[8px] transition duration-300 hover:bg-[#2a2a2a]  '>
                              <a href='album.php?id=" . $row['id'] . "'>
                                <img class='aspect-square flex rounded-md size-full  justify-center' src='" . $row['artworkPath'] . "'>
                                <p class='text-center font-spmix font-extraboldbold text-[#b3b3b3] mt-1 '>" . $row['title'] . "</p>
                              </a>
                  </div>";
          }
          ?><!--END-->
        </div>
      </main>





28 Sep 2024 --------------------------------
      <?php
            $songIdArray = $album->getSongIds();

            $i = 1;
            foreach ($songIdArray as $songId) {

                $albumSong = new Song($con, $songId);
                $albumArtist = $albumSong->getArtist();

                echo "<li id='trackListRow'>
                            <div id='trackCount'>
                                <img id='playButton' src='includes/assets/icons/play.svg' >
                                <span id='trackNumber'>$i</span>
                            </div>

                            <div class='trackInfo'>
                                <span id='trackName'>" . $albumSong->getTitle() . "</span>
                                <span id='artistName'>" . $albumArtist->getName() . "</span>
                            </div>

                            <div id='trackOptions'>
                                <img id='optionButtons' src='includes/assets/icons/plus.svg'>
                            </div>

                            <div id='trackDuration'>
                                <span id='duration'>".$albumSong->getDuration()."</span>
                            </div>


                        </li>";
                $i++;


            }

            ?>