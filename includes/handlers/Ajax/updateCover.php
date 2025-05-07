<?php
include "../../config.php";
include "../../includes/includedFile.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
    exit;
}

$playlistId = $_POST['playlistId'] ?? null;
$user = $_SESSION['userLoggedIn'] ?? null;

// Validate ownership
$playlist = new Playlist($con, $playlistId);
if ($playlist->getOwner() !== $user) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

// Process upload
$targetDir = "uploads/playlists/$playlistId/";
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

$extension = pathinfo($_FILES['coverPhoto']['name'], PATHINFO_EXTENSION);
$targetFile = $targetDir . 'cover.' . $extension;

if (move_uploaded_file($_FILES['coverPhoto']['tmp_name'], $targetFile)) {
    // Update database
    mysqli_query($con, "UPDATE playlists SET coverPhoto='$targetFile' WHERE id='$playlistId'");
    echo json_encode(['success' => true, 'newPath' => $targetFile]);
} else {
    echo json_encode(['success' => false, 'error' => 'Upload failed']);
}