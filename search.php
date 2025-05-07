<?php

include("includes/config.php");

if (isset($_SESSION["userLoggedIn"])) {
    $userLoggedInStr = $_SESSION["userLoggedIn"];
} else {
    header("Location: register.php");
    exit();
}

include "includes/classes/Account.php";
include "includes/classes/Artist.php";
include "includes/classes/Album.php";
include "includes/classes/Song.php";
include "includes/classes/Playlist.php";

// Create Account object using the session username
$userLoggedIn = new Account( $userLoggedInStr);

if (isset($_GET['term'])) {
    $term = urldecode($_GET['term']);
} else {
    $term = "";
}
echo "<h2 class='text-white opacity-50 text-center text-xl font-extrabold'>Results for : " . htmlspecialchars($term) . "</h2>";
?>

<script>
    $(function () {
        var timer;
        $("#searchbox").keyup(function () {
            clearTimeout(timer);
            timer = setTimeout(function () {
                var val = $("#searchbox").val();
                openPage("search.php?term=" + val);
            }, 1000);
        })
    })
</script>


<!-- SONG SEARCH  -->

<h2 class="font-extrabold font-spmix text-white text-2xl p-2 ">Songs</h2>
<div class="gridViewContainer ">
    <?php
    $songQuery = mysqli_query($con, "SELECT * FROM songs WHERE title LIKE '$term%' ");
    if (mysqli_num_rows($songQuery) == 0) {
        echo "<span class='font-semibold font-spmix text-white opacity-50 text-md pl-2 '>No matching Songs : " . $term . "</span>";
    }
    echo "<div class='grid  grid-cols-[25px_1fr_1fr_1fr_auto] z-20 h-11 px-6 bg-[#2a2a2a] text-[#b3b3b3] font-cirbook text-md items-center tracking-wider'>                
                <span class='text-xs w-fit '>#</span>
                <span class='text-xs  pr-4'>Song</span>
                <span class='text-xs  pl-[28px]'>Artist</span>
                <span class='text-xs  '></span>
                <span class='text-xs  ' title='Duration'><img src='includes\assets\icons\duration.svg'></span>
    </div>";

    $songIdArray = array();
    $i = 1;
    while ($row = mysqli_fetch_array($songQuery)) {
        if ($i > 15) {
            break;
        }

        array_push($songIdArray, $row['id']);

        $albumSong = new Song($con, $row['id']);
        $albumArtist = $albumSong->getArtist();


        echo "<li id='trackListRow'  class='grid grid-cols-[40px_1fr_1fr_1fr_auto] px-2 items-center hover:animate-pulses  hover:shadow-[0px_0px_10px_1px_rgba(255,255,255,0.20)]  duration-300  h-12 rounded-[4px] tracking-wider cursor-pointer group '>

                            <div id='trackCount' class='relative '>
                            <span id='trackNumber' class='text-xs absolute inset-0 flex items-center justify-center group-hover:opacity-0 transition-opacity duration-300 ' >$i</span>
                            <img id='playButton' class='absolute inset-0 w-4 h-4 m-auto opacity-0 group-hover:opacity-100 transition-opacity duration-300' src='includes/assets/icons/play.svg' onclick='setTrack(\"" . $albumSong->getId() . "\",tempPlaylist,true)'>
                            </div>

                            <div class='trackInfo '>
                                <span id='trackName' class='text-[#b3b3b3] text-sm font-spmix font-semibold truncate '>" . $albumSong->getTitle() . "</span>
                            </div>

                            <div class='flex'>
                                <img src='includes/assets/images/skulls.jpg' alt='' class='self-center w-5 h-5 rounded-full mx-1'/>
                                <span id='artistName' class='text-xs text-[#b3b3b3] truncate' >" . $albumArtist->getName() . "</span>
                            </div>

                            <div id='trackOptions'>
                            <input type='hidden' class='songId' value='" . $albumSong->getId() . "'>
                            <img class='optionButton  size-7 hover:bg-[#4a4a4a] transition duration-300 ease-in-out p-2 rounded-full cursor-pointer'src='includes/assets/icons/plus.svg' onclick='showOptionsMenu(this)'>
                        </div>

                            <div id='trackDuration' class='text-xs'>
                                <span id='duration'>" . $albumSong->getDuration() . "</span>
                            </div>


                        </li>";
        $i++;
    }
    ?>

    <script>
        var tempSongIds = '<?php echo json_encode($songIdArray) ?>'
        tempPlaylist = JSON.parse(tempSongIds);
    </script>

    <!-- SONG SEARCH END  -->

    <!-- ALBUMS SEARCH  -->
    <h2 class="font-extrabold font-spmix text-white text-2xl p-2 ">Albums</h2>
    <div class="gridViewContainer flex">
        <?php
        $albumQuery = mysqli_query($con, "SELECT * FROM albums WHERE title LIKE '$term%' ");
        if (mysqli_num_rows($albumQuery) == 0) {
            echo "<span class='font-semibold font-spmix text-white opacity-50 text-md pl-2 '>No Albums found matching : " . $term . "</span>";
        }
        while ($row = mysqli_fetch_array($albumQuery)) {
            echo "<div class='p-2 rounded-[8px] transition-all duration-300  hover:animate-pulse hover:shadow-[0px_0px_30px_1px_rgba(255,255,255,0.20)] h-[250px] w-[220px]'>
					<span role='link' tabindex='0' onclick='openPage(\"album.php?id=" . $row['id'] . "\")'>
						<img class='rounded-md bg-cover overflow-hidden object-cover h-[200px] w-full' src='" . $row['artworkPath'] . "'>

						<div class='text-center font-spmix font-semibold text-[#b3b3b3] mt-2 '>"
                . $row['title'] .
                "</div>
					</span>

				</div>";
        }
        ?>
        <!-- ALBUM SEARCH END  -->


    </div>

    <nav
        class="optionsMenu fixed hidden bg-[#121212] w-48 p-2 rounded-lg border border-[#2a2a2a] z-50 shadow-xl transition-all duration-200 opacity-0 translate-y-2">

        <input type="hidden" class="songId">
        <?php echo Playlist::getPlaylistsDropdown($con, $userLoggedInStr); ?>
    </nav>