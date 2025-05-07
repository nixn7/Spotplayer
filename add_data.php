<?php include("includes/includedfile.php"); ?>

<div class="add_container ">
    <h2 class="font-extrabold text-center mb-4 font-spotimix text-white text-xl p-2 pl-6">
        ADD<span class="text-green"> ARTISTS, SONGS & ALBUMS.</span>
    </h2>
</div>

<?php
// Display both success and error messages
if (isset($_SESSION['success'])) {
    echo '<div class="bg-green-600 text-white p-3 rounded-lg w-2/3 mx-auto mb-4">';
    echo $_SESSION['success'];
    echo '</div>';
    unset($_SESSION['success']);
}

if (isset($_SESSION['errors'])) {
    echo '<div class="bg-red-600 text-white p-3 rounded-lg w-2/3 mx-auto mb-4">';
    echo '<ul class="list-disc pl-5">';
    foreach ($_SESSION['errors'] as $error) {
        echo "<li>{$error}</li>";
    }
    echo '</ul>';
    echo '</div>';
    unset($_SESSION['errors']);
}
?>

<div class="add_form text-white p-6 w-2/3 mx-auto bg-[#121212] rounded-lg shadow-lg">
    <form action="admin_process.php" method="POST" enctype="multipart/form-data" id="songForm">
        <label class="block mb-2 font-bold">Song Title: <span class="text-red-500">*</span></label>
        <input type="text" name="title" required class="w-full p-2 rounded bg-[#2a2a2a] text-white">

        <div class="mt-4 mb-2">
            <label class="block font-bold">Artist: <span class="text-red-500">*</span></label>
            <div class="artist-input-group" data-required="true">
                <select name="artist" id="artistSelect" class="w-full p-2 rounded bg-[#2a2a2a] text-white">
                    <option value="">-- Select Artist --</option>
                    <?php
                    $artistQuery = mysqli_query($con, "SELECT * FROM artists");
                    while ($row = mysqli_fetch_assoc($artistQuery)) {
                        echo "<option value='{$row['id']}'>{$row['name']}</option>";
                    }
                    ?>
                </select>
                <div class="text-xs text-gray-400 mt-1 text-center">OR</div>
                <input type="text" name="newArtist" id="newArtist" placeholder="Enter new artist" class="w-full p-2 mt-1 rounded bg-[#2a2a2a] text-white">
                <div id="artistError" class="text-red-500 text-xs mt-1 hidden">Please select an existing artist or enter a new one</div>
            </div>
            <div class="text-xs text-gray-400 mt-1">You must either select an existing artist or enter a new one</div>
        </div>

        <div class="mt-4 mb-2">
            <label class="block font-bold">Album: <span class="text-red-500">*</span></label>
            <div class="album-input-group" data-required="true">
                <select name="album" id="albumSelect" class="w-full p-2 rounded bg-[#2a2a2a] text-white">
                    <option value="">-- Select Album --</option>
                    <?php
                    $albumQuery = mysqli_query($con, "SELECT * FROM albums");
                    while ($row = mysqli_fetch_assoc($albumQuery)) {
                        echo "<option value='{$row['id']}'>{$row['title']}</option>";
                    }
                    ?>
                </select>
                <div class="text-xs text-gray-400 mt-1 text-center">OR</div>
                <input type="text" name="newAlbum" id="newAlbum" placeholder="Enter new album" class="w-full p-2 mt-1 rounded bg-[#2a2a2a] text-white">
                <div id="albumError" class="text-red-500 text-xs mt-1 hidden">Please select an existing album or enter a new one</div>
            </div>
            <div class="text-xs text-gray-400 mt-1">You must either select an existing album or enter a new one</div>
        </div>

        <label class="block mt-4 mb-2 font-bold">Genre: <span class="text-red-500">*</span></label>
        <select name="genre" id="genreSelect" required class="w-full p-2 rounded bg-[#2a2a2a] text-white">
            <option value="">-- Select Genre --</option>
            <?php
            $genreQuery = mysqli_query($con, "SELECT * FROM genres");
            while ($row = mysqli_fetch_assoc($genreQuery)) {
                echo "<option value='{$row['id']}'>{$row['name']}</option>";
            }
            ?>
        </select>
        <div id="genreError" class="text-red-500 text-xs mt-1 hidden">Please select a genre</div>

        <label class="block mt-4 mb-2 font-bold">Upload Song File: <span class="text-red-500">*</span></label>
        <input type="file" name="songFile" id="songFile" required accept=".mp3,.wav,.flac" class="w-full p-2 rounded bg-[#2a2a2a] text-white">
        <div class="text-xs text-gray-400 mt-1"></div>

        <button type="submit" name="addSong" class="mt-4 p-2 bg-green-500 hover:bg-green-600 rounded text-white w-full font-bold">
            Add Song
        </button>
    </form>
</div>

<div class="text-center text-white mt-4 mb-8">
    <p><span class="text-red-500">*</span> Required fields</p>
</div>

<script>
// Fixed form validation script
document.addEventListener('DOMContentLoaded', function() {
    // Get the form element
    const form = document.getElementById('songForm');
    
    // Add submit event listener
    form.addEventListener('submit', function(event) {
        let formValid = true;
        
        // Title validation - already has HTML5 required attribute
        const title = document.querySelector('input[name="title"]');
        if (!title.value.trim()) {
            formValid = false;
        }
        
        // Artist validation (either select or new artist input is required)
        const artistSelect = document.getElementById('artistSelect');
        const newArtist = document.getElementById('newArtist');
        const artistError = document.getElementById('artistError');
        
        if (artistSelect.value === '' && !newArtist.value.trim()) {
            formValid = false;
            artistError.classList.remove('hidden');
            artistSelect.classList.add('border', 'border-red-500');
            newArtist.classList.add('border', 'border-red-500');
        } else {
            artistError.classList.add('hidden');
            artistSelect.classList.remove('border', 'border-red-500');
            newArtist.classList.remove('border', 'border-red-500');
        }
        
        // Album validation (either select or new album input is required)
        const albumSelect = document.getElementById('albumSelect');
        const newAlbum = document.getElementById('newAlbum');
        const albumError = document.getElementById('albumError');
        
        if (albumSelect.value === '' && !newAlbum.value.trim()) {
            formValid = false;
            albumError.classList.remove('hidden');
            albumSelect.classList.add('border', 'border-red-500');
            newAlbum.classList.add('border', 'border-red-500');
        } else {
            albumError.classList.add('hidden');
            albumSelect.classList.remove('border', 'border-red-500');
            newAlbum.classList.remove('border', 'border-red-500');
        }
        
        // Genre validation
        const genreSelect = document.getElementById('genreSelect');
        const genreError = document.getElementById('genreError');
        
        if (genreSelect.value === '') {
            formValid = false;
            genreError.classList.remove('hidden');
            genreSelect.classList.add('border', 'border-red-500');
        } else {
            genreError.classList.add('hidden');
            genreSelect.classList.remove('border', 'border-red-500');
        }
        
        // File validation - already has HTML5 required attribute
        const songFile = document.getElementById('songFile');
        if (!songFile.files || songFile.files.length === 0) {
            formValid = false;
        }
        
        // If form is not valid, prevent submission
        if (!formValid) {
            event.preventDefault();
            console.log('Form validation failed');
        }
    });
    
    // Clear validation styles when inputs change
    document.getElementById('artistSelect').addEventListener('change', function() {
        document.getElementById('artistError').classList.add('hidden');
        this.classList.remove('border', 'border-red-500');
        document.getElementById('newArtist').classList.remove('border', 'border-red-500');
        
        if (this.value !== '') {
            document.getElementById('newArtist').value = '';
        }
    });
    
    document.getElementById('newArtist').addEventListener('input', function() {
        document.getElementById('artistError').classList.add('hidden');
        this.classList.remove('border', 'border-red-500');
        document.getElementById('artistSelect').classList.remove('border', 'border-red-500');
        
        if (this.value.trim() !== '') {
            document.getElementById('artistSelect').selectedIndex = 0;
        }
    });
    
    document.getElementById('albumSelect').addEventListener('change', function() {
        document.getElementById('albumError').classList.add('hidden');
        this.classList.remove('border', 'border-red-500');
        document.getElementById('newAlbum').classList.remove('border', 'border-red-500');
        
        if (this.value !== '') {
            document.getElementById('newAlbum').value = '';
        }
    });
    
    document.getElementById('newAlbum').addEventListener('input', function() {
        document.getElementById('albumError').classList.add('hidden');
        this.classList.remove('border', 'border-red-500');
        document.getElementById('albumSelect').classList.remove('border', 'border-red-500');
        
        if (this.value.trim() !== '') {
            document.getElementById('albumSelect').selectedIndex = 0;
        }
    });
    
    document.getElementById('genreSelect').addEventListener('change', function() {
        document.getElementById('genreError').classList.add('hidden');
        this.classList.remove('border', 'border-red-500');
    });
});
</script>
