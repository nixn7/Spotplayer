<?php
include "includes/includedFile.php";
?>

<div class="bg-[#121212] h-[80vh] flex items-center justify-center">
    <div class="playlistModal bg-[#121212] p-6 rounded-lg shadow-md w-full max-w-md border border-[#2a2a2a]">
        <h2 class="text-2xl font-semibold text-white mb-4">Create New Playlist</h2>
        <form id="createPlaylistForm" action="process_createPlaylist.php" method="POST" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label for="playlistName" class="block font-bold text-[#b3b3b3] mb-1">Playlist Name</label>
                <input type="text" id="playlistName" name="playlistName"
                    class="w-full p-2 focus:outline-none border border-[#2a2a2a]  rounded-lg bg-[#121212] text-white"
                    required>
            </div>

            <div>
                <label for="coverPhoto" class="block text-[#b3b3b3] font-bold mb-1">Cover Photo</label>
                <input type="file" id="coverPhoto" name="coverPhoto" accept="image/*"
                    class="w-full p-2 focus:outline-none border rounded-lg shadow-xl border-[#2a2a2a]  bg-[#121212] text-white">
            </div>

            <div class="flex justify-between">
                <button type="button" onclick="openPage('index.php')"
                    class=" text-white px-4 py-2 rounded-full hover:bg-[#2a2a2a] border border-[#2a2a2a] font-medium">Cancel</button>
                <button type="submit"
                    class="bg-[#1f1f1f] hover:bg-[#2a2a2a] hover:border-green text-white px-4 py-2 rounded-full border border-[#2a2a2a] font-medium">Create</button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#createPlaylistForm").submit(function(e) {
            e.preventDefault();
            
            var formData = new FormData(this);
            
            $.ajax({
                url: "process_createPlaylist.php",
                type: "POST",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.status === "success") {
                        // Redirect to yourLibrary.php after successful creation
                        openPage('index.php');
                    } else {
                        alert("Error: " + result.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert("An error occurred: " + error);
                }
            });
        });
    });
</script>