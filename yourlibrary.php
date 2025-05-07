<?php
include("includes/includedFile.php");

if (!isset($_SESSION['userLoggedIn']) || empty($_SESSION['userLoggedIn'])) {
	die("UserTry logging in again.");
}

$username = $_SESSION['userLoggedIn'];
?>

<div class="playlistsContainer  bg-[#121212]  p-4">

	<div class="gridViewContainer max-w-4xl ">
		<h2 class="text-3xl font-spmix font-extrabold text-[#b3b3b3] mb-4">Your Playlists</h2>

		<div class="buttonItems mb-4">
			<button
				class="bg-[#1f1f1f] border border-[#2a2a2a]  hover:bg-[#2a2a2a] font-spmix text-green font-extrabold py-2 px-4 rounded-full transition"
				onclick="openPage('createPlaylist.php')">
				CREATE NEW
			</button>
		</div>

		<div class="grid grid-cols-4 sm:grid-cols-3 md:grid-cols-4 gap-2">
			<?php
			if (!isset($userLoggedIn) || !is_object($userLoggedIn)) {
				die("User is not logged in. Try logging in again.");
			}

			$username = $userLoggedIn->getUsername();
			$playlistsQuery = mysqli_query($con, "SELECT * FROM playlists WHERE owner='$username'");

			if (mysqli_num_rows($playlistsQuery) == 0) {
				echo "<span class='text-gray-400'>No playlists found.</span>";
			}

			while ($row = mysqli_fetch_array($playlistsQuery)) {
				$playlist = new Playlist($con, $row);
				?>

				<div class="bg-[#1f1f1f] border border-[#2a2a2a] hover:bg-[#2a2a2a] p-2 rounded-md shadow-md cursor-pointer transition flex items-center"
					onclick="openPage('playlist.php?id=<?php echo $playlist->getId(); ?>')">

					<img src="<?php echo $playlist->getCoverPhoto() ?: 'includes/assets/images/default-playlist.jpg'; ?>" class="w-16 h-16 object-cover rounded-md" style="width: 64px;">
					<div class="text-white text-md font-bold truncate pl-3">
						<?php echo $playlist->getName(); ?>
					</div>
				</div>

			<?php } ?>
		</div>
	</div>
</div>

<!-- Include Color Thief Library -->
<script src="color-thief.umd.js"></script>

<script>
    // This function will run both on page load and after AJAX navigation
    function initializePlaylistColors() {
        const playlistContainers = document.querySelectorAll(".gridViewContainer .grid div");
        
        playlistContainers.forEach(container => {
            const playlistArtwork = container.querySelector("img");
            
            if (!playlistArtwork) return;

            function setDominantColor(imageElement, container) {
                try {
                    const colorThief = new ColorThief();
                    const dominantColor = colorThief.getColor(imageElement);
                    const rgbColor = `rgb(${dominantColor[0]}, ${dominantColor[1]}, ${dominantColor[2]})`;

                    // Apply gradient background
                    container.style.background = `linear-gradient(to right, ${rgbColor}, rgba(10, 10, 10, 0.5))`;
                    console.log("Color applied successfully:", rgbColor);
                } catch (error) {
                    console.log("Error extracting color from artwork:", error);
                    // Default background
                    container.style.background = 'rgb(18, 18, 18)';
                    
                    // Retry if image not fully loaded
                    if (imageElement.complete && imageElement.naturalWidth === 0) {
                        console.log("Image not fully loaded, trying again in 300ms");
                        setTimeout(() => setDominantColor(imageElement, container));
                    }
                }
            }

            // Force the image to load with crossOrigin attribute
            if (!playlistArtwork.hasAttribute('crossorigin')) {
                playlistArtwork.setAttribute('crossorigin', 'anonymous');
            }

            // Check if image is already loaded
            if (playlistArtwork.complete && playlistArtwork.naturalWidth !== 0) {
                console.log("Image already loaded, setting color now");
                setDominantColor(playlistArtwork, container);
            } else {
                console.log("Image not loaded yet, waiting for load event");
                playlistArtwork.onload = function() {
                    console.log("Image loaded via onload event");
                    setDominantColor(playlistArtwork, container);
                };
            }
        });
    }

    document.addEventListener("DOMContentLoaded", initializePlaylistColors);
    setTimeout(initializePlaylistColors, 100);
    setTimeout(initializePlaylistColors, 500);
</script>