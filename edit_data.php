<?php
include("includes/includedFile.php");

// Check if user is an admin
if (!isset($_SESSION['userLoggedIn']) || !checkIfAdmin($_SESSION['userLoggedIn'])) {
    echo "<script>alert('Access denied. Admin privileges required.');
          location.href='index.php';</script>";
    exit();
}

function checkIfAdmin($username)
{
    $query = mysqli_query($GLOBALS['con'], "SELECT is_admin FROM users WHERE username='$username'");
    if (mysqli_num_rows($query) == 0) {
        return false;
    }
    $row = mysqli_fetch_array($query);
    return $row['is_admin'] == 1;
}
?>

<!-- Wrap admin content in a specific container to scope styles -->
<div class="adminPage">
    <div class="adminDashboard px-4">
        <h1 class="text-green text-3xl text-center font-extrabold p-2">Admin Dashboard</h1>

        <div class="entityNavigation">
            <button class="entityButton active" onclick="openEntity('songs')">Manage Songs</button>
            <button class="entityButton" onclick="openEntity('albums')">Manage Albums</button>
            <button class="entityButton" onclick="openEntity('artists')">Manage Artists</button>
        </div>

        <!-- Songs Management Section -->
        <div id="songs" class="entityContent">
            <input type="text" id="songSearchInput" onkeyup="searchSongs()" placeholder="Search for songs...">

            <table id="songsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Artist</th>
                        <th>Album</th>
                        <th>Duration</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $songQuery = mysqli_query($con, "SELECT s.id, s.title, ar.name as artist, al.title as album, s.duration 
                                               FROM songs s
                                               LEFT JOIN artists ar ON s.artist = ar.id
                                               LEFT JOIN albums al ON s.album = al.id
                                               ORDER BY s.title ASC");

                    while ($row = mysqli_fetch_array($songQuery)) {
                        echo "<tr>
                                <td>" . $row['id'] . "</td>
                                <td>" . $row['title'] . "</td>
                                <td>" . $row['artist'] . "</td>
                                <td>" . $row['album'] . "</td>
                                <td>" . $row['duration'] . "</td>
                                <td>
                                    <button class='editSong' onclick='editSong(" . $row['id'] . ")'>Edit</button>
                                    <button class='deleteSong' onclick='deleteSong(" . $row['id'] . ")'>Delete</button>
                                </td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Albums Management Section -->
        <div id="albums" class="entityContent" style="display:none;">
           
            <input type="text" id="albumSearchInput" onkeyup="searchAlbums()" placeholder="Search for albums...">

            <table id="albumsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Artist</th>
                        <th>Genre</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $albumQuery = mysqli_query($con, "SELECT al.id, al.title, ar.name as artist, g.name as genre
                                               FROM albums al
                                               LEFT JOIN artists ar ON al.artist = ar.id
                                               LEFT JOIN genres g ON al.genre = g.id
                                               ORDER BY al.title ASC");

                    while ($row = mysqli_fetch_array($albumQuery)) {
                        echo "<tr>
                                <td>" . $row['id'] . "</td>
                                <td>" . $row['title'] . "</td>
                                <td>" . $row['artist'] . "</td>
                                <td>" . $row['genre'] . "</td>
                                <td>
                                    <button onclick='editAlbum(" . $row['id'] . ")'>Edit</button>
                                    <button onclick='deleteAlbum(" . $row['id'] . ")'>Delete</button>
                                </td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Artists Management Section -->
        <div id="artists" class="entityContent" style="display:none;">
            
            <input type="text" id="artistSearchInput" onkeyup="searchArtists()" placeholder="Search for artists...">

            <table id="artistsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $artistQuery = mysqli_query($con, "SELECT id, name FROM artists ORDER BY name ASC");

                    while ($row = mysqli_fetch_array($artistQuery)) {
                        echo "<tr>
                                <td>" . $row['id'] . "</td>
                                <td>" . $row['name'] . "</td>
                                <td>
                                    <button  onclick='editArtist(" . $row['id'] . ")'>Edit</button>
                                    <button  onclick='deleteArtist(" . $row['id'] . ")'>Delete</button>
                                </td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Edit Song Modal -->
        <div id="editSongModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('editSongModal')">&times;</span>
                <h2>Edit Song</h2>
                <form id="editSongForm">
                    <input type="hidden" id="songId" name="songId">
                    <div class="form-group">
                        <label for="songTitle">Title:</label>
                        <input type="text" id="songTitle" name="songTitle" required>
                    </div>
                    <div class="form-group">
                        <label for="songArtist">Artist:</label>
                        <select id="songArtist" name="songArtist" required>
                            <?php
                            $artistQuery = mysqli_query($con, "SELECT id, name FROM artists ORDER BY name ASC");
                            while ($row = mysqli_fetch_array($artistQuery)) {
                                echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="songAlbum">Album:</label>
                        <select id="songAlbum" name="songAlbum" required>
                            <?php
                            $albumQuery = mysqli_query($con, "SELECT id, title FROM albums ORDER BY title ASC");
                            while ($row = mysqli_fetch_array($albumQuery)) {
                                echo "<option value='" . $row['id'] . "'>" . $row['title'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="songGenre">Genre:</label>
                        <select id="songGenre" name="songGenre" required>
                            <?php
                            $genreQuery = mysqli_query($con, "SELECT id, name FROM genres ORDER BY name ASC");
                            while ($row = mysqli_fetch_array($genreQuery)) {
                                echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="songDuration">Duration:</label>
                        <input type="text" id="songDuration" name="songDuration" pattern="[0-9]{2}:[0-9]{2}"
                            placeholder="MM:SS" required>
                    </div>
                    <button type="button" onclick="updateSong()">Update Song</button>
                </form>
            </div>
        </div>

        <!-- Edit Album Modal -->
        <div id="editAlbumModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('editAlbumModal')">&times;</span>
                <h2>Edit Album</h2>
                <form id="editAlbumForm">
                    <input type="hidden" id="albumId" name="albumId">
                    <div class="form-group">
                        <label for="albumTitle">Title:</label>
                        <input type="text" id="albumTitle" name="albumTitle" required>
                    </div>
                    <div class="form-group">
                        <label for="albumArtist">Artist:</label>
                        <select id="albumArtist" name="albumArtist" required>
                            <?php
                            $artistQuery = mysqli_query($con, "SELECT id, name FROM artists ORDER BY name ASC");
                            while ($row = mysqli_fetch_array($artistQuery)) {
                                echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="albumGenre">Genre:</label>
                        <select id="albumGenre" name="albumGenre" required>
                            <?php
                            $genreQuery = mysqli_query($con, "SELECT id, name FROM genres ORDER BY name ASC");
                            while ($row = mysqli_fetch_array($genreQuery)) {
                                echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="button" onclick="updateAlbum()">Update Album</button>
                </form>
            </div>
        </div>

        <!-- Edit Artist Modal -->
        <div id="editArtistModal" class="modal">s
            <div class="modal-content">
                <span class="close" onclick="closeModal('editArtistModal')">&times;</span>
                <h2>Edit Artist</h2>
                <form id="editArtistForm">
                    <input type="hidden" id="artistId" name="artistId">
                    <div class="form-group">
                        <label for="artistName">Name:</label>
                        <input type="text" id="artistName" name="artistName" required>
                    </div>
                    <button type="button" onclick="updateArtist()">Update Artist</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Scope all admin styles to prevent conflicts with header/footer */
    .adminPage {
        width: 100%;
        padding: 20px 0;
    }
    
    .adminDashboard {
        background: #121212;
        border-radius: 8px;
        color: white;
        width: 100%;
        min-height: 500px;
        max-width: 95%;
        margin: 0 auto;
    }

    .entityNavigation {
        margin-bottom: 20px;
        border-bottom: 2px solid #333;
    }

    /* Use more specific selectors to avoid conflicts */
    .adminDashboard .entityButton {
        background: #333;
        color: white;
        border: none;
        padding: 10px 20px;
        margin-right: 5px;
        cursor: pointer;
        border-radius: 5px 5px 0 0;
    }

    .adminDashboard .entityButton.active {
        background: #1db945;
    }

    .entityContent {
        margin-top: 20px;
        width: 100%;
        display: none;
    }

    #songs {
        display: block;
    }

    .adminDashboard input[type="text"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        background: #222;
        font-weight: bold;
        color: white;
        border: none;
        outline: none;
        border-radius: 12px;
    }

    .adminDashboard table {
        width: 100%;
        border-collapse: collapse;
        color: white;
        margin-bottom: 20px;
    }

    .adminDashboard th,
    .adminDashboard td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #444;
    }

    .adminDashboard th {
        background-color: #222;
    }

    .adminDashboard tr:hover {
        background-color: #333;
    }

    /* Specifically target admin action buttons to avoid conflicts with player controls */
    .adminDashboard button {
        background: #121212;
        color: white;
        padding: 8px 12px;
        margin-right: 5px;
        cursor: pointer;
        border-radius: 9999px;
        border: 1px solid #2a2a2a;
    }
    
    .adminDashboard button:hover {
        background: #282828;
    }

    .adminDashboard .editSong,
    .adminDashboard .deleteSong {
        display: inline-block;
        margin: 2px;
    }

    /* Modal styles - add specificity */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
    }

    .modal-content {
        background-color: #282828;
        margin: 10% auto;
        padding: 20px;
        width: 70%;
        max-width: 600px;
        border-radius: 8px;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 8px;
        background: #333;
        color: white;
        border: none;
        border-radius: 4px;
    }
    
    /* Override any TailwindCSS conflicts that might affect the admin area */
    .adminPage * {
        box-sizing: border-box;
    }
</style>

<script>
    function openEntity(entityName) {
        // Hide all entity contents
        let contents = document.getElementsByClassName('entityContent');
        for (let i = 0; i < contents.length; i++) {
            contents[i].style.display = "none";
        }

        // Show the selected entity content
        document.getElementById(entityName).style.display = "block";

        // Update active button
        let buttons = document.getElementsByClassName('entityButton');
        for (let i = 0; i < buttons.length; i++) {
            buttons[i].classList.remove("active");
        }

        event.target.classList.add("active");
    }

    function searchSongs() {
        let input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("songSearchInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("songsTable");
        tr = table.getElementsByTagName("tr");

        for (i = 1; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[1]; // Title column
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }

    function searchAlbums() {
        let input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("albumSearchInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("albumsTable");
        tr = table.getElementsByTagName("tr");

        for (i = 1; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[1]; // Title column
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }

    function searchArtists() {
        let input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("artistSearchInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("artistsTable");
        tr = table.getElementsByTagName("tr");

        for (i = 1; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[1]; // Name column
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }

    // Edit functions
    function editSong(id) {
        // Fetch song data
        fetch('includes/handlers/ajax/getSongInfo.php?id=' + id)
            .then(response => response.json())
            .then(data => {
                document.getElementById('songId').value = data.id;
                document.getElementById('songTitle').value = data.title;
                document.getElementById('songArtist').value = data.artist;
                document.getElementById('songAlbum').value = data.album;
                document.getElementById('songGenre').value = data.genre;
                document.getElementById('songDuration').value = data.duration;

                document.getElementById('editSongModal').style.display = 'block';
            });
    }

    function editAlbum(id) {
        // Fetch album data
        fetch('includes/handlers/ajax/getAlbumInfo.php?id=' + id)
            .then(response => response.json())
            .then(data => {
                document.getElementById('albumId').value = data.id;
                document.getElementById('albumTitle').value = data.title;
                document.getElementById('albumArtist').value = data.artist;
                document.getElementById('albumGenre').value = data.genre;

                document.getElementById('editAlbumModal').style.display = 'block';
            });
    }

    function editArtist(id) {
        // Fetch artist data
        fetch('includes/handlers/ajax/getArtistInfo.php?id=' + id)
            .then(response => response.json())
            .then(data => {
                document.getElementById('artistId').value = data.id;
                document.getElementById('artistName').value = data.name;

                document.getElementById('editArtistModal').style.display = 'block';
            });
    }

    // Update functions
    function updateSong() {
        const formData = new FormData(document.getElementById('editSongForm'));

        fetch('includes/handlers/ajax/updateSong.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Song updated successfully');
                    location.reload();
                } else {
                    alert('Error updating song: ' + data.message);
                }
            });
    }

    function updateAlbum() {
        const formData = new FormData(document.getElementById('editAlbumForm'));

        fetch('includes/handlers/ajax/updateAlbum.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Album updated successfully');
                    location.reload();
                } else {
                    alert('Error updating album: ' + data.message);
                }
            });
    }

    function updateArtist() {
        const formData = new FormData(document.getElementById('editArtistForm'));

        fetch('includes/handlers/ajax/updateArtist.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Artist updated successfully');
                    location.reload();
                } else {
                    alert('Error updating artist: ' + data.message);
                }
            });
    }

    // Delete functions
    function deleteSong(id) {
        if (confirm('Are you sure you want to delete this song? This action cannot be undone.')) {
            fetch('includes/handlers/ajax/deleteSong.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'id=' + id
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Song deleted successfully');
                        location.reload();
                    } else {
                        alert('Error deleting song: ' + data.message);
                    }
                });
        }
    }

    function deleteAlbum(id) {
        if (confirm('Are you sure you want to delete this album? This action cannot be undone and may affect associated songs.')) {
            fetch('includes/handlers/ajax/deleteAlbum.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'id=' + id
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Album deleted successfully');
                        location.reload();
                    } else {
                        alert('Error deleting album: ' + data.message);
                    }
                });
        }
    }

    function deleteArtist(id) {
        if (confirm('Are you sure you want to delete this artist? This action cannot be undone and may affect associated albums and songs.')) {
            fetch('includes/handlers/ajax/deleteArtist.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'id=' + id
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Artist deleted successfully');
                        location.reload();
                    } else {
                        alert('Error deleting artist: ' + data.message);
                    }
                });
        }
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    // Close modal
    window.onclick = function (event) {
        if (event.target.className === 'modal') {
            event.target.style.display = 'none';
        }
    }
</script>