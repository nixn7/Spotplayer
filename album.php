<?php include "includes/includedFile.php";

if (isset($_GET['id'])) {
    $albumId = $_GET['id'];

} else {
    header("Location: index.php");
    exit;
}

// ALBUM TITLE AND ARTIST DISPLAY 
$album = new Album($con, $albumId);
$artist = $album->getArtist();
?>

<!-- Include Color Thief Library -->
<script src="color-thief.umd.js"></script>

<script>
    // on page load and after AJAX navigation
    function initializeAlbumColors() {
        const albumArtwork = document.querySelector("#leftSection img");
        const albumInfo = document.querySelector("#albumInfo");
        
        if (!albumArtwork || !albumInfo) return;

        function setDominantColor(imageElement, container) {
            try {
                const colorThief = new ColorThief();
                const dominantColor = colorThief.getColor(imageElement);
                const rgbColor = `rgb(${dominantColor[0]}, ${dominantColor[1]}, ${dominantColor[2]})`;

                // gradient container
                container.style.background = `linear-gradient(to bottom, ${rgbColor}, rgba(18, 18, 18, 1))`;
                console.log("Color applied successfully:", rgbColor);
            } catch (error) {
                console.log("Error extracting color from artwork:", error);
                // default background
                container.style.background = 'rgb(18, 18, 18)';
                
                //  try again
                if (imageElement.complete && imageElement.naturalWidth === 0) {
                    console.log("Image not fully loaded, trying again in 300ms");
                    setTimeout(() => setDominantColor(imageElement, container), 300);
                }
            }
        }

        // Force the image 
        if (!albumArtwork.hasAttribute('crossorigin')) {
            albumArtwork.setAttribute('crossorigin', 'anonymous');
        }

        // Check if image is already loaded
        if (albumArtwork.complete && albumArtwork.naturalWidth !== 0) {
            console.log("Image already loaded, setting color now");
            setDominantColor(albumArtwork, albumInfo);
        } else {
            console.log("Image not loaded yet, waiting for load event");
            albumArtwork.onload = function() {
                console.log("Image loaded via onload event");
                setDominantColor(albumArtwork, albumInfo);
            };
        }
    }
    document.addEventListener("DOMContentLoaded", initializeAlbumColors);
    setTimeout(initializeAlbumColors, 100);
    setTimeout(initializeAlbumColors, 500);
</script>

<!-- MOUSE EVENT HOVER SCRIPT  -->
<script>
    function removeHoverBackground() {
        var trackListRows = document.querySelectorAll('.trackListRow');
        trackListRows.forEach(function (row) {
            row.classList.remove('hover:bg-[#2a2a2a]');
        });
    }

    // Add event listener to close options menu when clicked outside
    document.addEventListener('click', function(event) {
        const optionsMenu = document.querySelector('.optionsMenu');
        
        // Check if optionsMenu
        if (optionsMenu && !optionsMenu.classList.contains('hidden')) {
            // Check if the click was outside
            if (!optionsMenu.contains(event.target) && !event.target.classList.contains('optionButton')) {
                optionsMenu.classList.add('hidden');
                optionsMenu.classList.add('opacity-0');
                optionsMenu.classList.add('translate-y-2');
            }
        }
    });
</script>

<style>
    .trackListRow:hover .trackNumber {
        opacity: 0;
    }

    .trackListRow:hover .playButton {
        opacity: 1;
    }
</style>

<main id="mainContentContainer" class="w-full bg-[#121212] rounded-[8px] overflow-y-auto max-h-[80vh]">

    <!-- ALBUM HEAD INFO -->
    <div id="albumInfo"
        class="w-full h-[250px] flex flex-start gap-4 px-[18px] z-10 sticky top-0 shadow-lg">
        <div id="leftSection" class="flex-shrink-0 size-52 m-auto drop-shadow-lg">
            <img class="w-full h-full object-cover rounded-[6px]" src="<?php echo $album->getArtworkPath(); ?>"
                alt="Album Artwork" crossorigin="anonymous">
        </div>

        <div id="rightSection" class="flex flex-col justify-center items-start w-full">
            <h3 class="font-spmix font-extrabold text-white text-[85px] tracking-tighter leading-[75px]">
                <?php echo $album->getTitle(); ?>
            </h3>
            <p class="font-spmix font-extrabold text-white text-[16px] tracking-wide px-1 mt-3">
                <?php echo $artist->getName(); ?>
            </p>
            <p class="font-cirmd font-semibold  text-white opacity-70 text-xs justify-end tracking-wide px-1">Songs
                <?php echo $album->getTotalSongs(); ?>
            </p>
        </div>
    </div>
    <!-- ALBUM HEAD INFO END -->

    <!-- TRACKLIST HEADER  -->
    <div
        class="grid grid-cols-[40px_1fr_1fr_1fr_auto] z-20 h-11 px-6 bg-[#2a2a2a] text-[#b3b3b3] font-cirbook text-md items-center tracking-wider sticky top-[250px]">
        <span class='text-xs  '>#</span>
        <span class='text-xs  '>Song</span>
        <span class='text-xs px-2'>Artist</span>
        <span class='text-xs  '></span>
        <span class='' title="Duration"><img src="includes\assets\icons\duration.svg"></span>
    </div>
    <!-- TRACKLIST HEADER END  -->

    <!-- TRACKLIST -->
    <div id="trackListContainer" class=" bg-gradient-to-br from-[#121212] to-[#2a2a2a] min-h-screen ">
        <ul id="trackList" class="px-4 pt-1 grid gap-2 ">


            <?php
            $songIdArray = $album->getSongIds();

            $i = 1;
            foreach ($songIdArray as $songId) {

                $albumSong = new Song($con, $songId);
                $albumArtist = $albumSong->getArtist();

                echo "<li id='trackListRow'  class='grid grid-cols-[40px_1fr_1fr_1fr_auto] px-2 items-center hover:animate-pulses  hover:shadow-[0px_0px_10px_1px_rgba(255,255,255,0.20)]  duration-300  h-12 rounded-[4px] tracking-wider cursor-pointer group '>

                            <div id='trackCount' class='relative '>
                            <span id='trackNumber' class='text-xs absolute inset-0 flex items-center justify-center group-hover:opacity-0 transition-opacity duration-300 ' >$i</span>
                            <img id='playButton' class='absolute inset-0 w-4 h-4 m-auto opacity-0 group-hover:opacity-100 transition-opacity duration-300' src='includes/assets/icons/play.svg' onclick='setTrack(\"" . $albumSong->getId() . "\",tempPlaylist,true)'>
                            </div>

                            <div class='trackInfo '>
                                <span id='trackName' class='text-white text-sm font-spmix font-semibold truncate '>" . $albumSong->getTitle() . "</span>
                            </div>

                            <div class='flex'>
                                <img src='includes/assets/images/skulls.jpg' alt='' class='self-center w-5 h-5 rounded-full mx-1'/>
                                <span id='artistName' class='text-xs text-[#b3b3b3] truncate' >" . $albumArtist->getName() . "</span>
                            </div>

                        <div id='trackOptions'>
                            <input type='hidden' class='songId' value='" . $albumSong->getId() . "'>
                            <img class='optionButton size-7 hover:bg-[#4a4a4a] transition duration-300 ease-in-out p-2 rounded-full cursor-pointer'src='includes/assets/icons/plus.svg' onclick='showOptionsMenu(this)'>
                        </div>

                            <div id='trackDuration' class='text-xs'>
                                <span id='duration'>" . $albumSong->getDuration() . "</span>
                            </div>


                        </li>";
                $i++;


            }

            ?>
            <script>
                var tempSongIds = '<?php echo json_encode($songIdArray); ?>';
                tempPlaylist = JSON.parse(tempSongIds);
            </script>
        </ul>
    </div>



    <!-- <nav class="optionsMenu">
        <input type="hidden" class="songId">
   
        <div class="item">Item 2</div>
        <div class="item">Item 3</div>
    </nav> -->

    <nav
        class="optionsMenu fixed hidden bg-[#121212] w-48 p-2 rounded-lg border border-[#2a2a2a] z-50 shadow-xl transition-all duration-200 opacity-0 translate-y-2">
        <input type="hidden" class="songId">
        <?php echo Playlist::getPlaylistsDropdown($con, $userLoggedIn->getUsername()); ?>
    </nav>



</main>