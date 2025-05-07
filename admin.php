<?php
include "includes/includedFile.php";


if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>


<!-- MAIN_CONTENT_CONTAINER -->
<main id="mainContentContainer"
    class="w-full bg-[#121212] border border-[#2a2a2a] rounded-[8px] p-2 overflow-y-auto h-full max-h-[80vh]"
    style="min-width: 600px;">


    <!-- error messages  -->
    <?php if (isset($_SESSION['errors'])): ?>
        <div class="bg- opacity-20 text-white border flex gap-4 border-[#2a2a2a] p-3 rounded-lg w-2/3 mx-auto mb-4">
            <h4 class="font-bold mb-2">ERROR UPLOADING:</h4>
            <ul class="list-disc pl-5 ">
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <li class="text-[#b3b3b3] tracking-wider"><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>


    <!-- success notification -->
    <?php if (isset($_SESSION['success'])): ?>
        <div id="success-notification"
            class="bg-[#1db945] text-white font-extrabold font-spmix p-3 rounded-lg w-2/3 mx-auto mb-4 transition-opacity duration-500">
            <?php echo $_SESSION['success']; ?>
        </div>
        <script>
            // Auto-dismiss 2 seconds
            document.addEventListener('DOMContentLoaded', function () {
                const notification = document.getElementById('success-notification');
                if (notification) {
                    setTimeout(function () {
                        notification.style.opacity = '0';
                        setTimeout(function () {
                            notification.style.display = 'none';
                        }, 500);
                    }, 1500);
                }
            });
        </script>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>


    <div class="add_container">
        <h2 class="font-extrabold text-center mb-1 font-spotimix text-white text-xl p-2 pl-6">
            ADD<span class="text-green"> ARTISTS, SONGS & ALBUMS</span>
        </h2>
    </div>

    <div class="add_form text-white p-4 w-2/4 mx-auto bg-[#121212] rounded-lg shadow-lg">
        <form action="admin_process.php" method="POST" enctype="multipart/form-data">
            <!-- Song Title -->
            <label class="block mb-1 font-bold">Song Title:</label>
            <input type="text" name="title" required
                class="w-full  p-2 focus:outline-none rounded-lg bg-[#121212] border border-[#2a2a2a] text-white">

            <!-- Artist Dropdown -->
            <label class="block mt-2 mb-1 font-bold">Artist:</label>
            <select name="artist" class="w-full p-2 rounded-lg placeholder:text-green bg-[#2a2a2a] text-white">
                <option value=""
                    class="font-spmix font-bold focus:outline-none  rounded-lg bg-[#121212] border border-[#2a2a2a]">
                    Select Artist</option>
                <?php
                $artistQuery = mysqli_query($con, "SELECT * FROM artists");
                if ($artistQuery) {
                    while ($row = mysqli_fetch_assoc($artistQuery)) {
                        echo "<option class='font-spmix text-[#b3b3b3] font-bold focus:outline-none  rounded-lg bg-[#121212] border border-[#2a2a2a]' value='{$row['id']}'>{$row['name']}</option>";
                    }
                } else {
                    echo "<option value=''>No artists found</option>";
                }
                ?>
            </select>
            <input type="text" name="newArtist" placeholder="Or enter new artist"
                class="w-full p-2 mt-2 focus:outline-none rounded-lg bg-[#121212] border border-[#2a2a2a] text-white">

            <!-- Album Dropdown -->
            <label class="block mt-2 mb-1 font-bold">Album:</label>
            <select name="album" id="albumSelect" class="w-full p-2 rounded-lg bg-[#2a2a2a] text-white">
                <option value=""
                    class="font-spmix font-bold focus:outline-none rounded-lg bg-[#121212] border border-[#2a2a2a]">
                    Select Album</option>
                <?php
                $albumQuery = mysqli_query($con, "SELECT * FROM albums");
                $albumsWithArtwork = [];
                if ($albumQuery) {
                    while ($row = mysqli_fetch_assoc($albumQuery)) {
                        // Store albums with artwork for JavaScript use
                        if (!empty($row['artworkPath'])) {
                            $albumsWithArtwork[] = $row['id'];
                        }
                        echo "<option class='font-spmix font-bold text-[#b3b3b3] focus:outline-none rounded-lg bg-[#121212] border border-[#2a2a2a]' value='{$row['id']}'>{$row['title']}</option>";
                    }
                } else {
                    echo "<option value=''>No albums found</option>";
                }
                ?>
            </select>
            <input type="text" name="newAlbum" id="newAlbum" placeholder="Or enter new album"
                class="w-full p-2 mt-2 focus:outline-none rounded-lg bg-[#121212] border border-[#2a2a2a] text-white">

            <!-- Album Artwork Upload -->
            <div id="albumArtworkOption" class="hidden">
                <label class="block mt-2 mb-1 font-bold">Album Artwork:</label>
                <input type="file" name="albumArtwork" id="albumArtwork"
                    class="w-full p-2 rounded-lg bg-[#2a2a2a] text-white">
                <p class="text-xs text-[#b3b3b3] mt-1">Upload artwork for new albums.</p>
            </div>

            <!-- Genre Dropdown -->
            <label class="block mt-2 mb-1 font-bold">Genre:</label>
            <select name="genre" class="w-full p-2 rounded-lg bg-[#2a2a2a] text-white">
                <option value=""
                    class="font-spmix font-bold focus:outline-none rounded-lg bg-[#121212] border border-[#2a2a2a]">
                    Select Genre</option>
                <?php
                $genreQuery = mysqli_query($con, "SELECT * FROM genres");
                if ($genreQuery) {
                    while ($row = mysqli_fetch_assoc($genreQuery)) {
                        echo "<option class='font-spmix text-[#b3b3b3] font-bold focus:outline-none  rounded-lg bg-[#121212] border border-[#2a2a2a]' value='{$row['id']}'>{$row['name']}</option>";
                    }
                } else {
                    echo "<option value=''>No genres found</option>";
                }
                ?>
            </select>

            <!-- File Upload -->
            <label class="block mt-2 mb-1 font-bold">Upload Song File:</label>
            <input type="file" name="songFile" required class="w-full p-2 rounded-lg bg-[#2a2a2a] text-white">

            <!-- Submit Button -->
            <button type="submit" name="addSong"
                class="mt-2 p-2 focus:outline-none rounded-full bg-[#121212] border border-[#2a2a2a] text-green w-full font-bold">
                Add Song
            </button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const albumSelect = document.getElementById('albumSelect');
            const newAlbumInput = document.getElementById('newAlbum');
            const albumArtworkOption = document.getElementById('albumArtworkOption');

            const albumsWithArtwork = <?php echo json_encode($albumsWithArtwork); ?>;

            function updateArtworkVisibility() {
            
                if (newAlbumInput.value.trim() !== '') {
                    albumArtworkOption.classList.remove('hidden');
                    document.getElementById('albumArtwork').setAttribute('required', 'required');
                } else {
                    albumArtworkOption.classList.add('hidden');
                    document.getElementById('albumArtwork').removeAttribute('required');
                }
            }

            newAlbumInput.addEventListener('input', updateArtworkVisibility);
            
            albumSelect.addEventListener('change', function() {
                if (albumSelect.value !== '') {
                    newAlbumInput.value = '';
                    updateArtworkVisibility();
                }
            });

            updateArtworkVisibility();
        });
    </script>

    <script>
    (function() {
        const albumSelect = document.getElementById('albumSelect');
        const newAlbumInput = document.getElementById('newAlbum');
        const albumArtworkOption = document.getElementById('albumArtworkOption');

        if (!albumSelect || !newAlbumInput || !albumArtworkOption) {
            return; // Elements not found
        }

        const albumsWithArtwork = <?php echo json_encode($albumsWithArtwork); ?>;

        function updateArtworkVisibility() {
            if (newAlbumInput.value.trim() !== '') {
                albumArtworkOption.classList.remove('hidden');
                document.getElementById('albumArtwork').setAttribute('required', 'required');
            } else {
                albumArtworkOption.classList.add('hidden');
                document.getElementById('albumArtwork').removeAttribute('required');
            }
        }

        newAlbumInput.addEventListener('input', updateArtworkVisibility);
        
        albumSelect.addEventListener('change', function() {
            if (albumSelect.value !== '') {
                newAlbumInput.value = '';
                updateArtworkVisibility();
            }
        });

        updateArtworkVisibility();
    })(); 
    </script>

</main>
<!-- MAIN_CONTENT_CONTAINER_END -->

</div><!-- MID ROW WRAPPER END -->