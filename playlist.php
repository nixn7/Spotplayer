<?php include "includes/includedFile.php";

if (isset($_GET['id'])) {
    $playlistId = $_GET['id'];

} else {
    header("Location: index.php");
}

$playlist = new Playlist($con, $playlistId);
$owner = new User($con, $playlist->getOwner());
?>

<!-- MOUSE EVENT HOVER SCRIPT  -->
<!-- <script>
    function removeHoverBackground() {
        var trackListRows = document.querySelectorAll('#trackListRow');
        trackListRows.forEach(function (row) {
            row.classList.remove('hover:bg-[#2a2a2a]');
        });
    }
</script> -->

<style>
    .trackListRow:hover .trackNumber {
        opacity: 0;
    }

    .trackListRow:hover .playButton {
        opacity: 1;
    }
</style>



<main id="mainContentContainer" class="w-full bg-[#121212] rounded-[8px] overflow-y-auto max-h-[80vh]">

    <!-- Notification Container -->
    <div id="notificationContainer" class="fixed top-0 left-0 w-full hidden">
        <div class="bg-green-500 text-white text-center py-2 font-bold" id="notificationMessage"></div>
    </div>

    <!-- Remove Song Modal -->
    <div id="removeSongModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="bg-[#121212] border border-[#2a2a2a] rounded-lg shadow-lg p-6 max-w-sm w-1/3 relative z-50">
            <h2 class="text-xl font-bold mb-4">Remove Song</h2>
            <p class="mb-4">Are you sure you want to remove this song from the playlist?</p>
            <div class="flex justify-end">
                <button id="cancelRemove" class="px-4 py-2 bg-[#121212] border border-[#2a2a2a] hover:bg-[#2a2a2a] rounded mr-2">Cancel</button>
                <button id="confirmRemove" class="px-4 py-2 bg-red-500 hover:bg-red-700 text-white rounded">Remove</button>
            </div>
        </div>
        <div id="modalOverlay" class="fixed inset-0 bg-black opacity-50 z-40"></div>
    </div>

    <!-- ALBUM HEAD INFO  -->
    <div id="albumInfo"
        class="bg-[#2a2a2a] w-full h-[250px] flex flex-start gap-4 px-[18px] z-10 sticky top-0 shadow-lg ">
        <div id="leftSection" class="flex-shrink-0 size-52 m-auto drop-shadow-lg  ">
            <?php
            $coverPath = $playlist->getCoverPath();
            $defaultImage = 'includes/assets/images/default-playlist.jpg';
            ?>
            <img class="w-full h-full object-cover rounded-[6px]" src="<?php echo $coverPath ?: $defaultImage; ?>"
                onerror="this.src='<?php echo $defaultImage; ?>'"
                alt="<?php echo htmlspecialchars($playlist->getName()); ?> Cover">
        </div>

        <div id="rightSection" class="flex flex-col justify-center items-start w-full ">
            <h3 class="font-spmix font-extrabold text-white text-[85px] tracking-tighter leading-[75px]">
                <?php echo $playlist->getName(); ?>
            </h3>
            <p class="font-spmix font-extrabold text-white text-[16px] tracking-wide px-1 mt-3">
                <?php echo $playlist->getOwner(); ?>
            </p>
            <p class="font-cirmd font-medium text-[#b3b3b3] text-xs justify-end tracking-wide px-1">Songs
                <?php echo $playlist->getNumberOfSongs(); ?>
            </p>

            <div class="flex gap-2 mt-2">
                <!-- cover photo update  -->
                <button
                    class="bg-[#1f1f1f] mt-1 border border-[#2a2a2a] font-spmix text-[#b3b3b3] font-bold text-xs py-2 px-4 rounded-full transition"
                    onclick="document.getElementById('coverUpload').click()">
                    EDIT COVER
                </button>
                <input type="file" id="coverUpload" class="hidden" accept="image/*" onchange="updateCover(this)">

                <!-- paylist dlte btn  -->
                <button
                    class="bg-[#1f1f1f] border border-[#2a2a2a] mt-1 text-[#b3b3b3] font-spmix font-bold text-xs py-2 px-4 rounded-full transition"
                    onclick="deletePlaylist('<?php echo $playlistId; ?>')">
                    DELETE PLAYLIST
                </button>
            </div>

        </div>
    </div>
    <!-- ALBUM HEAD INFO END  -->

    <!-- Delete Playlist Modal -->
    <div id="deletePlaylistModal" class="fixed flex inset-0 items-center justify-center z-50 hidden">
        <!-- Modal Box -->
        <div class="bg-[#121212] border border-[#2a2a2a] rounded-lg shadow-lg p-6 max-w-sm w-1/3  relative z-50">
            <h2 class="text-xl font-bold mb-4">Delete Playlist</h2>
            <p class="mb-4">Are you sure you want to delete this playlist?</p>
            <div class="flex justify-end">
                <button id="cancelDelete" class="px-4 py-2 bg-[#121212] hover:bg-[#2a2a2a] border border-[#2a2a2a] rounded mr-2">Cancel</button>
                <button id="confirmDelete" class="px-4 py-2 bg-red-500 hover:bg-red-700 text-white rounded">Delete</button>
            </div>
        </div>
        <!-- Overlay -->
        <div id="modalOverlay" class="fixed inset-0 bg-black opacity-50 z-40"></div>
    </div>



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
            $songIdArray = $playlist->getSongIds();

            $i = 1;
            foreach ($songIdArray as $songId) {

                $playlistSong = new Song($con, $songId);
                $songArtist = $playlistSong->getArtist();

                echo "<li id='trackListRow' class='grid grid-cols-[40px_1fr_1fr_40px_1fr_auto] px-2 items-center hover:animate-pulses hover:shadow-[0px_0px_10px_1px_rgba(255,255,255,0.20)] duration-300 h-12 rounded-[4px] tracking-wider cursor-pointer group '>

                            <div id='trackCount' class='relative '>
                            <span id='trackNumber' class='text-xs absolute inset-0 flex items-center justify-center group-hover:opacity-0 transition-opacity duration-300 ' >$i</span>
                            <img id='playButton' class='absolute inset-0 w-4 h-4 m-auto opacity-0 group-hover:opacity-100 transition-opacity duration-300' src='includes/assets/icons/play.svg' onclick='setTrack(\"" . $playlistSong->getId() . "\",tempPlaylist,true)'>
                            </div>

                            <div class='trackInfo '>
                                <span id='trackName' class='text-white text-sm font-spmix font-semibold truncate '>" . $playlistSong->getTitle() . "</span>
                            </div>

                            <div class='flex'>
                                <img src='includes/assets/images/skulls.jpg' alt='' class='self-center w-5 h-5 rounded-full mx-1'/>
                                <span id='artistName' class='text-xs text-[#b3b3b3] truncate' >" . $songArtist->getName() . "</span>
                            </div>

                            <div id='trackOptions'>
                            <input type='hidden' class='songId' value='" . $playlistSong->getId() . "'>
                            <img class='optionButton size-7 hover:bg-[#4a4a4a] transition duration-300 ease-in-out p-2 rounded-full cursor-pointer'src='includes/assets/icons/plus.svg' onclick='showOptionsMenu(this)'>
                            </div>

                            <div id='removeSong' class='pl-6'>
                                <img class='removeButton size-7 hover:bg-[#4a4a4a] transition duration-300 ease-in-out p-2 rounded-full cursor-pointer' src='includes/assets/icons/CLOSE.svg' onclick='removeSongFromPlaylist(\"" . $playlistSong->getId() . "\", \"" . $playlistId . "\")'>
                            </div>

                            <div id='trackDuration' class='text-xs text-right'>
                                <span id='duration'>" . $playlistSong->getDuration() . "</span>
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
    <!-- TRACKLIST END  -->

    <nav
        class="optionsMenu fixed hidden bg-[#121212] w-48 p-2 rounded-lg border border-[#2a2a2a] z-50 shadow-xl transition-all duration-200 opacity-0 translate-y-2">

        <input type="hidden" class="songId">
        <?php echo Playlist::getPlaylistsDropdown($con, $userLoggedIn->getUsername()); ?>
    </nav>

    <script>
        let songToRemove = null;
        let playlistToRemoveFrom = null;

        function updateCover(input) {
            if (input.files && input.files[0]) {
                const playlistId = '<?php echo $playlistId; ?>';
                const formData = new FormData();
                formData.append('playlistId', playlistId);
                formData.append('coverImage', input.files[0]);

                // Display loading indicator or notification
                const coverImg = document.querySelector('#leftSection img');
                coverImg.style.opacity = '0.5';

                fetch('includes/handlers/Ajax/updatePlaylistCover.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => {
                        // First check if response is ok
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }

                        // Then check the content type
                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            // Log the actual response text for debugging
                            return response.text().then(text => {
                                console.error('Invalid response format:', text);
                                throw new Error('Invalid response format: expected JSON');
                            });
                        }

                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Update the image with the new path (add timestamp to prevent caching)
                            coverImg.src = data.imagePath + '?t=' + new Date().getTime();
                            openPage("playlist.php?id=" + playlistId);
                        } else {
                            console.error('Server error:', data.error);
                            alert("Error: " + data.error);
                        }
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                        alert("An error occurred while updating the cover image: " + error.message);
                    })
                    .finally(() => {
                        coverImg.style.opacity = '1';
                    });
            }
        }

        function deletePlaylist(playlistId) {
            document.getElementById('deletePlaylistModal').classList.remove('hidden');

            document.getElementById('cancelDelete').onclick = function () {
                document.getElementById('deletePlaylistModal').classList.add('hidden');
            };

            document.getElementById('confirmDelete').onclick = function () {


                fetch('includes/handlers/Ajax/deletePlaylist.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'playlistId=' + encodeURIComponent(playlistId)
                })
                    .then(response => {
                        if (!response.ok) {

                            return response.text().then(text => {
                                throw new Error(text || `HTTP error! Status: ${response.status}`);
                            });
                        }
                        return response.text();
                    })
                    .then(text => {
                        if (text.trim() === "") {
                            console.log("Playlist deleted successfully.");
                            openPage("yourlibrary.php");
                        } else {
                            console.error("Error deleting playlist:", text);
                            alert("Error deleting playlist: " + text);
                        }
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                        alert("An error occurred while deleting the playlist: " + error.message);
                    })
                    .finally(() => {
                        document.getElementById('deletePlaylistModal').classList.add('hidden');
                    });
            };
        }

        // function showNotification(message, type = "success") {
        //     const notificationContainer = document.getElementById("notificationContainer");
        //     const notificationMessage = document.getElementById("notificationMessage");

        //     // Set message and style based on type
        //     notificationMessage.textContent = message;
        //     notificationContainer.classList.remove("hidden");
        //     notificationContainer.classList.add(type === "error" ? "bg-red-500" : "bg-green-500");

        //     // Hide notification after 5 seconds (increased from 3 seconds)
        //     setTimeout(() => {
        //         notificationContainer.classList.add("hidden");
        //     }, 5000); // 5000ms = 5 seconds
        // }

        function removeSongFromPlaylist(songId, playlistId) {
            songToRemove = songId;
            playlistToRemoveFrom = playlistId;

            // Show the modal
            document.getElementById('removeSongModal').classList.remove('hidden');

            // Handle cancel button
            document.getElementById('cancelRemove').onclick = function () {
                document.getElementById('removeSongModal').classList.add('hidden');
                songToRemove = null;
                playlistToRemoveFrom = null;
            };

            // Handle confirm button
            document.getElementById('confirmRemove').onclick = function () {
                const url = 'includes/handlers/Ajax/removeSongFromPlaylist.php';

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'songId=' + encodeURIComponent(songToRemove) + '&playlistId=' + encodeURIComponent(playlistToRemoveFrom)
                })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => {
                            throw new Error(text || `HTTP error! Status: ${response.status}`);
                        });
                    }
                    return response.text();
                })
                .then(text => {
                    if (text.trim() === "Success") {
                        console.log("Song removed successfully.");
                        showNotification("Song removed successfully.");
                        openPage("playlist.php?id=" + playlistToRemoveFrom);
                    } else {
                        console.error("Error removing song:", text);
                        showNotification("Error removing song: " + text, "error");
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    showNotification("An error occurred while removing the song: " + error.message, "error");
                })
                .finally(() => {
                    document.getElementById('removeSongModal').classList.add('hidden');
                    songToRemove = null;
                    playlistToRemoveFrom = null;
                });
            };
        }
    </script>
</main>