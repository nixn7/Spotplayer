<?php
session_start();
include "includes/config.php";

// Include getID3 library for extracting song duration
require_once('getid3/getid3.php');

// Check if the form was submitted
if (isset($_POST['addSong'])) {
    $errors = [];

    // Validate and collect song data
    $title = $_POST['title'] ?? '';
    if (empty($title)) {
        $errors[] = "Song title is required";
    }

    // Handle artist (existing or new)
    $artistId = null;
    if (!empty($_POST['artist'])) {
        $artistId = $_POST['artist'];
    } elseif (!empty($_POST['newArtist'])) {
        // Insert new artist
        $newArtistName = mysqli_real_escape_string($con, $_POST['newArtist']);
        $artistInsert = mysqli_query($con, "INSERT INTO artists (name) VALUES ('$newArtistName')");
        if ($artistInsert) {
            $artistId = mysqli_insert_id($con);
        } else {
            $errors[] = "Failed to add new artist";
        }
    } else {
        $errors[] = "Artist is required";
    }

    // Handle album (existing or new)
    $albumId = null;
    if (!empty($_POST['album'])) {
        $albumId = $_POST['album'];

        // Check if album has artwork
        $albumQuery = mysqli_query($con, "SELECT artworkPath FROM albums WHERE id = '$albumId'");
        $albumData = mysqli_fetch_assoc($albumQuery);
        $hasArtwork = !empty($albumData['artworkPath']);

        // Process new artwork if needed
        if (!$hasArtwork && isset($_FILES['albumArtwork']) && $_FILES['albumArtwork']['error'] == 0) {
            $artworkPath = processAlbumArtwork($_FILES['albumArtwork']);
            if ($artworkPath) {
                // Update album with new artwork
                mysqli_query($con, "UPDATE albums SET artworkPath = '$artworkPath' WHERE id = '$albumId'");
            }
        }
    } elseif (!empty($_POST['newAlbum'])) {
        // Insert new album
        $newAlbumTitle = mysqli_real_escape_string($con, $_POST['newAlbum']);
        $artworkPath = '';

        // Process album artwork
        if (isset($_FILES['albumArtwork']) && $_FILES['albumArtwork']['error'] == 0) {
            $artworkPath = processAlbumArtwork($_FILES['albumArtwork']);
        }

        $albumInsert = mysqli_query($con, "INSERT INTO albums (title, artist, artworkPath) 
                                          VALUES ('$newAlbumTitle', '$artistId', '$artworkPath')");
        if ($albumInsert) {
            $albumId = mysqli_insert_id($con);
        } else {
            $errors[] = "Failed to add new album";
        }
    } else {
        $errors[] = "Album is required";
    }

    // Handle genre
    $genreId = null;
    if (!empty($_POST['genre'])) {
        $genreId = mysqli_real_escape_string($con, $_POST['genre']);
    } else {
        $errors[] = "Genre is required";
    }

    // Handle song file upload
    $songPath = 'uploads/songs/'; 
    if (isset($_FILES['songFile']) && $_FILES['songFile']['error'] == 0) {
        // Pass son
        $songPath = processSongFile($_FILES['songFile'], $title);
        if (!$songPath) {
            $errors[] = "Failed to upload song file";
        }
    } else {
        $errors[] = "Song file is required";
    }

    if (empty($errors)) {
        // getID3 library
        try {
            $getID3 = new getID3();
            $fileInfo = $getID3->analyze($songPath);
            
            $durationSeconds = isset($fileInfo['playtime_seconds']) ? $fileInfo['playtime_seconds'] : 0;
            
            $minutes = floor($durationSeconds / 60);
            $seconds = str_pad(floor($durationSeconds % 60), 2, '0', STR_PAD_LEFT);
            $duration = "$minutes:$seconds";
        } catch (Exception $e) {
            error_log("Error extracting song duration: " . $e->getMessage());
            $duration = "0:00"; // Default format if extraction fails
        }

        // Properly escape all values to prevent SQL injection
        $titleEscaped = mysqli_real_escape_string($con, $title);
        $songPathEscaped = mysqli_real_escape_string($con, $songPath);
        $durationEscaped = mysqli_real_escape_string($con, $duration);

        // Insert song with formatted duration
        $query = "INSERT INTO songs (title, artist, album, genre, duration, path) 
                  VALUES ('$titleEscaped', $artistId, $albumId, $genreId, '$durationEscaped', '$songPathEscaped')";

        if (mysqli_query($con, $query)) {
            $_SESSION['success'] = "Song '$title' added successfully!";
        } else {
            $_SESSION['errors'] = ["Database error: " . mysqli_error($con)];
            // Add debugging info to see the exact query that failed
            error_log("Failed SQL Query: " . $query);
            error_log("SQL Error: " . mysqli_error($con));
        }
    } else {
        $_SESSION['errors'] = $errors;
    }

    // Redirect back to admin page
    header("Location: admin.php");
    exit;
}

/**
 * Process album artwork upload
 * 
 * @param array $file The $_FILES['albumArtwork'] array
 * @return string|false The path to the saved artwork or false on failure
 */
function processAlbumArtwork($file)
{
    $targetDir = "includes/assets/images/albumart/";

    // Create the directory if it doesn't exist
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Generate unique filename
    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $targetFile = $targetDir . uniqid() . "." . $fileExtension;

    // Check if file is an image
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
        return false;
    }

    // Upload the file
    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        return $targetFile;
    }

    return false;
}

/**
 * Process song file upload
 * 
 * @param array $file The $_FILES['songFile'] array
 * @param string $songTitle The song title to use in the filename
 * @return string|false The path to the saved song file or false on failure
 */
function processSongFile($file, $songTitle = '')
{
    $targetDir = "uploads/songs/";

    // Create 
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // file extension
    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
    

    if (!empty($songTitle)) {
        $safeTitle = preg_replace('/[^\w\s.-]/', '', $songTitle);
        $safeTitle = str_replace(' ', '_', $safeTitle);
        
        $uniqueId = substr(uniqid(), -5);
        $filename = $safeTitle . '_' . $uniqueId . '.' . $fileExtension;
    } else {
        $filename = uniqid() . '.' . $fileExtension;
    }
    
    $targetFile = $targetDir . $filename;

    
    $allowedExtensions = ['mp3', 'wav', 'ogg', 'm4a', 'aac', 'flac']; 
    if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
        error_log("Invalid file extension: " . $fileExtension);
        return false;
    }

    // Upload the file
    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        return $targetFile;
    }

    // Log upload errors
    error_log("Failed to upload song file: " . print_r($file, true));
    return false;
}
?>