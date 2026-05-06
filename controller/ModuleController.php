<?php 
session_start();
include '../database/dbconn.php';

/**
 * Handle Module Related Actions
 */

// Create New Module
if (isset($_POST['createModule'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../login.php");
        exit();
    }
    
    $userId = $_SESSION['user_id'];
    $moduleDescription = mysqli_real_escape_string($conn, $_POST['moduleDescription']);
    $section = mysqli_real_escape_string($conn, $_POST['section']);
    $year = mysqli_real_escape_string($conn, $_POST['year']);
    
    // Generate a unique 6-character module code
    $moduleCode = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
    
    // Schedule details
    $day = mysqli_real_escape_string($conn, $_POST['day_of_week']);
    $start = mysqli_real_escape_string($conn, $_POST['start_time']);
    $end = mysqli_real_escape_string($conn, $_POST['end_time']);
    $type = mysqli_real_escape_string($conn, $_POST['meeting_type']);

    // Check for schedule conflict for this instructor
    $conflict_sql = "SELECT s.* FROM schedules s 
                    JOIN module m ON s.module_id = m.module_id 
                    WHERE m.user_id = '$userId' 
                    AND s.day_of_week = '$day' 
                    AND (
                        ('$start' >= s.start_time AND '$start' < s.end_time) OR 
                        ('$end' > s.start_time AND '$end' <= s.end_time) OR
                        (s.start_time >= '$start' AND s.start_time < '$end')
                    ) LIMIT 1";
    $conflict_run = mysqli_query($conn, $conflict_sql);

    if (mysqli_num_rows($conflict_run) > 0) {
        header("Location: ../dashboard.php?status=error&msg=Schedule Conflict! You already have a class at this time on $day.");
        exit();
    }

    $sql = "INSERT INTO module (module_description, user_id, section, year, module_code) VALUES ('$moduleDescription', '$userId', '$section', '$year', '$moduleCode')";
    
    if (mysqli_query($conn, $sql)) {
        $newModuleId = mysqli_insert_id($conn);
        
        // Save initial schedule
        $day = mysqli_real_escape_string($conn, $_POST['day_of_week']);
        $start = mysqli_real_escape_string($conn, $_POST['start_time']);
        $end = mysqli_real_escape_string($conn, $_POST['end_time']);
        $type = mysqli_real_escape_string($conn, $_POST['meeting_type']);
        
        $sched_sql = "INSERT INTO schedules (module_id, day_of_week, start_time, end_time, meeting_type) 
                     VALUES ('$newModuleId', '$day', '$start', '$end', '$type')";
        mysqli_query($conn, $sched_sql);

        header("Location: ../dashboard.php?status=success&msg=Module Created with Schedule");
        exit();
    } else {
        header("Location: ../dashboard.php?status=error&msg=Failed to Create Module");
        exit();
    }
}
?>