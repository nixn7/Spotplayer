<?php
// Catch any PHP errors
error_reporting(0);
ini_set('display_errors', 0);

// Set response header early to ensure it's always sent
header('Content-Type: application/json');

try {
    include("../../config.php");

    $response = [];

    if(isset($_POST['playlistId']) && isset($_FILES['coverImage'])) {
        $playlistId = $_POST['playlistId'];
        
        // File upload handling
        $file = $_FILES['coverImage'];
        
        // Check for errors
        if($file['error'] !== 0) {
            $response['success'] = false;
            $response['error'] = "File upload error: " . $file['error'];
            echo json_encode($response);
            exit();
        }
        
        // Validate file type (only images allowed)
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
        if(!in_array($file['type'], $allowedTypes)) {
            $response['success'] = false;
            $response['error'] = "Invalid file type. Only JPG, PNG, and GIF are allowed.";
            echo json_encode($response);
            exit();
        }
        
        // Create target directory if not exists
        // Use a proper path relative to the site root
        $targetDir = $_SERVER['DOCUMENT_ROOT'] . "/sounx/includes/assets/images/playlist_covers/"; 
        if(!file_exists($targetDir)) {
            if(!mkdir($targetDir, 0755, true)) {
                $response['success'] = false;
                $response['error'] = "Failed to create directory. Check permissions.";
                echo json_encode($response);
                exit();
            }
        }
        
        // Generate unique filename
        $fileName = uniqid('playlist_cover_') . '_' . $playlistId . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
        $targetPath = $targetDir . $fileName;
        
        // Move the uploaded file to the destination
        if(move_uploaded_file($file['tmp_name'], $targetPath)) {
            // Update database with a web-accessible path
            $relativePath = "includes/assets/images/playlist_covers/" . $fileName;
            
            // Use the correct column name: coverPhoto instead of coverPath
            $updateQuery = mysqli_prepare($con, "UPDATE playlists SET coverPhoto = ? WHERE id = ?");
            mysqli_stmt_bind_param($updateQuery, "si", $relativePath, $playlistId);
            
            if(mysqli_stmt_execute($updateQuery)) {
                $response['success'] = true;
                $response['imagePath'] = $relativePath;
                echo json_encode($response);
                exit();
            } else {
                $response['success'] = false;
                $response['error'] = "Database update failed: " . mysqli_error($con);
                echo json_encode($response);
                exit();
            }
        } else {
            $phpError = error_get_last();
            $response['success'] = false;
            $response['error'] = "Failed to move uploaded file. Error: " . ($phpError ? $phpError['message'] : 'Unknown error');
            echo json_encode($response);
            exit();
        }
    } else {
        $response['success'] = false;
        $response['error'] = "Invalid request parameters";
        echo json_encode($response);
        exit();
    }
} catch (Exception $e) {
    // Catch any exceptions and return as JSON
    $response = [
        'success' => false,
        'error' => "Server error: " . $e->getMessage()
    ];
    echo json_encode($response);
    exit();
}
?>
