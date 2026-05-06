<?php include 'includes/header.php'; ?>
<?php include 'database/dbconn.php'; ?>

<?php
$userId = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Fetch all modules for the current user (Instructor sees their own, Student sees enrolled)
if ($role == 'instructor') {
    $modules_sql = "SELECT module_id FROM module WHERE user_id = '$userId'";
} else {
    // Student sees schedules for modules they are accepted in
    $modules_sql = "SELECT module_id FROM enrollments WHERE user_id = '$userId' AND status = 'accepted'";
}

$modules_run = mysqli_query($conn, $modules_sql);
$module_ids = [];
while ($row = mysqli_fetch_assoc($modules_run)) {
    $module_ids[] = $row['module_id'];
}

$ids_string = count($module_ids) > 0 ? implode(',', $module_ids) : '0';

// Fetch all schedules for these modules
$sql = "SELECT s.*, m.module_description, m.section 
        FROM schedules s 
        JOIN module m ON s.module_id = m.module_id 
        WHERE s.module_id IN ($ids_string) 
        ORDER BY FIELD(s.day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), s.start_time ASC";
$result = mysqli_query($conn, $sql);
?>

<div class="d-flex" id="wrapper">
    <?php include 'includes/sidebar.php'; ?>

    <div id="page-content-wrapper" class="w-100">
        <nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom">
            <div class="container-fluid">
                <button class="btn btn-outline-secondary d-lg-none me-2" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-wrapper">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a class="navbar-brand" href="dashboard.php">AutoTech</a>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="dashboard.php" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Weekly Schedule</li>
                    </ol>
                </nav>
            </div>
        </nav>

        <div class="container-fluid p-4" style="background-color: #f8f9fa; min-height: 100vh;">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-dark mb-1">Weekly Schedule</h2>
                    <p class="text-muted mb-0 small">Keep track of all your class meeting times</p>
                </div>
            </div>

            <div class="row g-4">
                <?php
                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                
                // Group schedules by day
                $grouped_schedules = [];
                foreach ($days as $day) {
                    $grouped_schedules[$day] = [];
                }
                
                while ($row = mysqli_fetch_assoc($result)) {
                    $grouped_schedules[$row['day_of_week']][] = $row;
                }

                foreach ($days as $day):
                    $has_classes = count($grouped_schedules[$day]) > 0;
                ?>
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; overflow: hidden;">
                        <div class="card-header border-0 py-3 px-4 <?php echo $has_classes ? 'bg-primary text-white' : 'bg-white text-muted opacity-50'; ?>">
                            <h6 class="mb-0 fw-bold d-flex justify-content-between align-items-center">
                                <?php echo $day; ?>
                                <?php if($has_classes): ?>
                                    <span class="badge bg-white text-primary rounded-pill small"><?php echo count($grouped_schedules[$day]); ?> Classes</span>
                                <?php endif; ?>
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <?php if ($has_classes): ?>
                                <?php foreach ($grouped_schedules[$day] as $s): ?>
                                    <div class="mb-4 last-child-mb-0">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <h6 class="fw-bold text-dark mb-0 text-truncate" style="max-width: 70%;"><?php echo $s['module_description']; ?></h6>
                                            <span class="badge bg-light text-primary border rounded-pill fw-medium" style="font-size: 0.65rem;">
                                                <?php echo $s['meeting_type']; ?>
                                            </span>
                                        </div>
                                        <div class="d-flex align-items-center text-muted small mb-2">
                                            <i class="bi bi-clock me-2"></i>
                                            <span><?php echo date('h:i A', strtotime($s['start_time'])) . ' - ' . date('h:i A', strtotime($s['end_time'])); ?></span>
                                            <span class="mx-2">•</span>
                                            <span>Section <?php echo $s['section']; ?></span>
                                        </div>
                                        <div class="border-top pt-3 opacity-25"></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center py-4 opacity-50">
                                    <i class="bi bi-calendar-x display-4 mb-2 d-block"></i>
                                    <p class="small mb-0">No classes scheduled</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<style>
    .last-child-mb-0:last-child {
        margin-bottom: 0 !important;
    }
    .last-child-mb-0:last-child .border-top {
        display: none !important;
    }
</style>

<?php include 'includes/footer.php'; ?>
