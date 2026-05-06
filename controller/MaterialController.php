<?php
include '../database/dbconn.php';

/**
 * Handle Class Material Uploads
 */

if (isset($_POST['uploadMaterial'])) {
    $moduleId    = mysqli_real_escape_string($conn, $_POST['moduleId']);
    $title       = mysqli_real_escape_string($conn, $_POST['materialTitle']);
    $description = mysqli_real_escape_string($conn, $_POST['materialDescription']);
    
    // Validate File
    if (!isset($_FILES['materialFile']) || $_FILES['materialFile']['error'] !== 0) {
        redirectWithError($moduleId, "Please select a valid file.");
    }

    $targetDir = "../uploads/materials/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $originalName = basename($_FILES["materialFile"]["name"]);
    $fileName     = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "_", $originalName);
    $targetPath   = $targetDir . $fileName;

    if (move_uploaded_file($_FILES["materialFile"]["tmp_name"], $targetPath)) {
        $sql = "INSERT INTO `materials` (module_id, title, description, file_path) 
                VALUES ('$moduleId', '$title', '$description', '$fileName')";
        
        if (mysqli_query($conn, $sql)) {
            header("Location: ../view-module.php?id=$moduleId&status=success&msg=Material Uploaded");
            exit();
        } else {
            // Cleanup file if DB insert fails
            if (file_exists($targetPath)) unlink($targetPath);
            redirectWithError($moduleId, "Database error: Unable to save material info.");
        }
    } else {
        redirectWithError($moduleId, "Failed to move uploaded file.");
    }
}

/**
 * Helper to redirect back with an error message
 */
function redirectWithError($id, $message) {
    header("Location: ../view-module.php?id=$id&status=error&msg=" . urlencode($message));
    exit();
}
?>
