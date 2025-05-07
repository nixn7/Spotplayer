<?php
session_start([
    'read_and_close' => true  // ← Unlocks session immediately
]);
include "includes/includedFile.php";
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["playlistName"]) && !empty($_POST["playlistName"])) {
        $playlistName = mysqli_real_escape_string($con, $_POST["playlistName"]);
        $owner = $_SESSION["userLoggedIn"] ?? "guest"; // Change this to get the logged-in username
        $coverPhotoPath = NULL; // Default NULL if no image is uploaded
        $dateCreated = date("Y-m-d H:i:s");

        // Insert
        $insertQuery = "INSERT INTO playlists (name, owner, dateCreated, coverPhoto) 
                        VALUES ('$playlistName', '$owner', '$dateCreated', NULL)";

        if (mysqli_query($con, $insertQuery)) {
            $playlistId = mysqli_insert_id($con); // Get last inserted ID

            // folder
            $playlistFolder = "uploads/playlists/$playlistId/";
            if (!is_dir($playlistFolder)) {
                mkdir($playlistFolder, 0777, true);
            }

            //  photo upload
            if (!empty($_FILES["coverPhoto"]["name"])) {
                $fileTmpPath = $_FILES["coverPhoto"]["tmp_name"];
                $fileName = basename($_FILES["coverPhoto"]["name"]);
                $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                $newFileName = "cover.$fileExtension"; // Rename file to 'cover.ext'
                $destination = $playlistFolder . $newFileName;

                if (move_uploaded_file($fileTmpPath, $destination)) {
                    $coverPhotoPath = $destination;

                    //  cover photo path
                    $updateQuery = "UPDATE playlists SET coverPhoto='$coverPhotoPath' WHERE id='$playlistId'";
                    mysqli_query($con, $updateQuery);
                }
            }

            // Redirect to playlists page
            echo json_encode([
                'status' => 'success',
                'message' => 'Playlist created successfully!',
                'playlistId' => $playlistId // Optional: Useful for debugging
            ]);
            exit();
        } else {
            echo "Error creating playlist: " . mysqli_error($con);
        }
    } else {
        echo "Playlist name is required!";
    }
} else {
    echo "Invalid request!";
}
?>