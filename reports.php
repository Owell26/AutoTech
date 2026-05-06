<?php include 'includes/header.php'; ?>
<?php include 'database/dbconn.php'; ?>

<?php
$userId = $_SESSION['user_id'];
$role = $_SESSION['role'];

if ($role == 'instructor') {
    // Instructor Stats
    $total_modules = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM module WHERE user_id = '$userId'"))['total'];
    $total_students = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT e.user_id) as total FROM enrollments e JOIN module m ON e.module_id = m.module_id WHERE m.user_id = '$userId' AND e.status = 'accepted'"))['total'];
    $total_assignments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM assignments a JOIN module m ON a.module_id = m.module_id WHERE m.user_id = '$userId'"))['total'];
    $total_submissions = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM submissions s JOIN assignments a ON s.assignment_id = a.assignment_id JOIN module m ON a.module_id = m.module_id WHERE m.user_id = '$userId'"))['total'];
} else {
    // Student Stats
    $total_modules = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM enrollments WHERE user_id = '$userId' AND status = 'accepted'"))['total'];
    $total_assignments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM assignments a JOIN enrollments e ON a.module_id = e.module_id WHERE e.user_id = '$userId' AND e.status = 'accepted'"))['total'];
    $total_submissions = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM submissions WHERE user_id = '$userId'"))['total'];
    $pending_assignments = $total_assignments - $total_submissions;
}
?>

<div class="d-flex" id="wrapper">
    <?php include 'includes/sidebar.php'; ?>

    <div id="page-content-wrapper" class="w-100">
        <nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom">
            <div class="container-fluid">
                <button class="btn btn-outline-secondary d-lg-none me-2" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-wrapper">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a class="navbar-brand" href="dashboard.php">AutoTech Reports</a>
            </div>
        </nav>

        <div class="container-fluid p-4" style="background-color: #f8f9fa; min-height: 100vh;">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-dark mb-1"><?php echo ($role == 'instructor') ? 'Academic Overview' : 'My Learning Progress'; ?></h2>
                    <p class="text-muted mb-0"><?php echo ($role == 'instructor') ? 'Data-driven insights for your modules and students.' : 'Track your performance across all enrolled modules.'; ?></p>
                </div>
            </div>

            <!-- Quick Summary Cards -->
            <div class="row g-2 g-md-4 mb-5">
                <?php if($role == 'instructor'): ?>
                    <!-- Instructor Cards -->
                    <div class="col-6 col-md-3">
                        <a href="dashboard.php" class="text-decoration-none transition-hover d-block">
                            <div class="card border-0 shadow-sm" style="border-radius: 15px; background: linear-gradient(45deg, #4e73df, #224abe);">
                                <div class="card-body p-3 p-md-4 text-white">
                                    <div class="d-flex justify-content-between align-items-center mb-2 mb-md-3">
                                        <i class="bi bi-journal-bookmark fs-2 fs-md-1 opacity-50"></i>
                                        <h2 class="fw-bold mb-0" style="font-size: 1.5rem;"><?php echo $total_modules; ?></h2>
                                    </div>
                                    <h6 class="mb-0 small fw-medium">Total Modules</h6>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="students.php" class="text-decoration-none transition-hover d-block">
                            <div class="card border-0 shadow-sm" style="border-radius: 15px; background: linear-gradient(45deg, #1cc88a, #13855c);">
                                <div class="card-body p-3 p-md-4 text-white">
                                    <div class="d-flex justify-content-between align-items-center mb-2 mb-md-3">
                                        <i class="bi bi-people fs-2 fs-md-1 opacity-50"></i>
                                        <h2 class="fw-bold mb-0" style="font-size: 1.5rem;"><?php echo $total_students; ?></h2>
                                    </div>
                                    <h6 class="mb-0 small fw-medium">Enrolled Students</h6>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="dashboard.php" class="text-decoration-none transition-hover d-block">
                            <div class="card border-0 shadow-sm" style="border-radius: 15px; background: linear-gradient(45deg, #f6c23e, #dda20a);">
                                <div class="card-body p-3 p-md-4 text-white">
                                    <div class="d-flex justify-content-between align-items-center mb-2 mb-md-3">
                                        <i class="bi bi-journal-check fs-2 fs-md-1 opacity-50"></i>
                                        <h2 class="fw-bold mb-0" style="font-size: 1.5rem;"><?php echo $total_assignments; ?></h2>
                                    </div>
                                    <h6 class="mb-0 small fw-medium">Assignments Posted</h6>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="dashboard.php" class="text-decoration-none transition-hover d-block">
                            <div class="card border-0 shadow-sm" style="border-radius: 15px; background: linear-gradient(45deg, #e74a3b, #be2617);">
                                <div class="card-body p-3 p-md-4 text-white">
                                    <div class="d-flex justify-content-between align-items-center mb-2 mb-md-3">
                                        <i class="bi bi-cloud-arrow-up fs-2 fs-md-1 opacity-50"></i>
                                        <h2 class="fw-bold mb-0" style="font-size: 1.5rem;"><?php echo $total_submissions; ?></h2>
                                    </div>
                                    <h6 class="mb-0 small fw-medium">Total Submissions</h6>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php else: ?>
                    <!-- Student Cards -->
                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm" style="border-radius: 15px; background: linear-gradient(45deg, #4e73df, #224abe);">
                            <div class="card-body p-3 p-md-4 text-white">
                                <div class="d-flex justify-content-between align-items-center mb-2 mb-md-3">
                                    <i class="bi bi-journal-bookmark fs-2 fs-md-1 opacity-50"></i>
                                    <h2 class="fw-bold mb-0" style="font-size: 1.5rem;"><?php echo $total_modules; ?></h2>
                                </div>
                                <h6 class="mb-0 small fw-medium">Enrolled Modules</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm" style="border-radius: 15px; background: linear-gradient(45deg, #1cc88a, #13855c);">
                            <div class="card-body p-3 p-md-4 text-white">
                                <div class="d-flex justify-content-between align-items-center mb-2 mb-md-3">
                                    <i class="bi bi-journal-check fs-2 fs-md-1 opacity-50"></i>
                                    <h2 class="fw-bold mb-0" style="font-size: 1.5rem;"><?php echo $total_assignments; ?></h2>
                                </div>
                                <h6 class="mb-0 small fw-medium">Total Tasks</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm" style="border-radius: 15px; background: linear-gradient(45deg, #dda20a, #f6c23e);">
                            <div class="card-body p-3 p-md-4 text-white">
                                <div class="d-flex justify-content-between align-items-center mb-2 mb-md-3">
                                    <i class="bi bi-check-circle fs-2 fs-md-1 opacity-50"></i>
                                    <h2 class="fw-bold mb-0" style="font-size: 1.5rem;"><?php echo $total_submissions; ?></h2>
                                </div>
                                <h6 class="mb-0 small fw-medium">Submissions</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm" style="border-radius: 15px; background: linear-gradient(45deg, #e74a3b, #be2617);">
                            <div class="card-body p-3 p-md-4 text-white">
                                <div class="d-flex justify-content-between align-items-center mb-2 mb-md-3">
                                    <i class="bi bi-clock-history fs-2 fs-md-1 opacity-50"></i>
                                    <h2 class="fw-bold mb-0" style="font-size: 1.5rem;"><?php echo $pending_assignments; ?></h2>
                                </div>
                                <h6 class="mb-0 small fw-medium">Pending Tasks</h6>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <style>
                .transition-hover {
                    transition: all 0.3s ease;
                }
                .transition-hover:hover {
                    transform: translateY(-5px);
                    filter: brightness(1.1);
                }
            </style>

            <div class="row g-4">
                <!-- Performance Table -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                        <div class="card-header bg-white border-0 pt-4 px-4">
                            <h5 class="fw-bold text-dark mb-0"><?php echo ($role == 'instructor') ? 'Module Performance' : 'My Module Progress'; ?></h5>
                        </div>
                        <div class="card-body px-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-4 small text-uppercase fw-bold text-muted">Module Name</th>
                                            <th class="text-center small text-uppercase fw-bold text-muted"><?php echo ($role == 'instructor') ? 'Students' : 'Tasks'; ?></th>
                                            <th class="text-center small text-uppercase fw-bold text-muted">Submissions</th>
                                            <th class="text-center small text-uppercase fw-bold text-muted">Completion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($role == 'instructor') {
                                            $performance_sql = "SELECT m.module_id, m.module_description, 
                                                               (SELECT COUNT(*) FROM enrollments e WHERE e.module_id = m.module_id AND e.status = 'accepted') as main_count,
                                                               (SELECT COUNT(*) FROM assignments a WHERE a.module_id = m.module_id) as assign_count,
                                                               (SELECT COUNT(*) FROM submissions s JOIN assignments a ON s.assignment_id = a.assignment_id WHERE a.module_id = m.module_id) as sub_count
                                                               FROM module m WHERE m.user_id = '$userId'";
                                        } else {
                                            $performance_sql = "SELECT m.module_id, m.module_description, 
                                                               (SELECT COUNT(*) FROM assignments a WHERE a.module_id = m.module_id) as main_count,
                                                               (SELECT COUNT(*) FROM submissions s JOIN assignments a ON s.assignment_id = a.assignment_id WHERE a.module_id = m.module_id AND s.user_id = '$userId') as sub_count
                                                               FROM module m 
                                                               JOIN enrollments e ON m.module_id = e.module_id 
                                                               WHERE e.user_id = '$userId' AND e.status = 'accepted'";
                                        }
                                        $perf_res = mysqli_query($conn, $performance_sql);
                                        
                                        while($p = mysqli_fetch_assoc($perf_res)):
                                            if ($role == 'instructor') {
                                                $possible = $p['main_count'] * $p['assign_count'];
                                            } else {
                                                $possible = $p['main_count'];
                                            }
                                            $percentage = $possible > 0 ? round(($p['sub_count'] / $possible) * 100) : 0;
                                            $progColor = $percentage > 70 ? 'success' : ($percentage > 40 ? 'warning' : 'danger');
                                        ?>
                                        <tr>
                                            <td class="ps-4 fw-bold small text-dark"><?php echo $p['module_description']; ?></td>
                                            <td class="text-center small"><?php echo $p['main_count']; ?></td>
                                            <td class="text-center small"><?php echo $p['sub_count']; ?></td>
                                            <td class="text-center" style="width: 200px;">
                                                <div class="d-flex align-items-center px-3">
                                                    <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                                        <div class="progress-bar bg-<?php echo $progColor; ?>" role="progressbar" style="width: <?php echo $percentage; ?>%"></div>
                                                    </div>
                                                    <span class="small fw-bold text-<?php echo $progColor; ?>"><?php echo $percentage; ?>%</span>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity Panel -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                        <div class="card-header bg-white border-0 pt-4 px-4">
                            <h5 class="fw-bold text-dark mb-0"><?php echo ($role == 'instructor') ? 'Recent Submissions' : 'My Recent Activity'; ?></h5>
                        </div>
                        <div class="card-body">
                            <?php
                            if ($role == 'instructor') {
                                $recent_sql = "SELECT s.*, u.fullname, a.title 
                                              FROM submissions s 
                                              JOIN users u ON s.user_id = u.user_id 
                                              JOIN assignments a ON s.assignment_id = a.assignment_id 
                                              JOIN module m ON a.module_id = m.module_id 
                                              WHERE m.user_id = '$userId' 
                                              ORDER BY s.submitted_at DESC LIMIT 5";
                            } else {
                                $recent_sql = "SELECT s.*, a.title 
                                              FROM submissions s 
                                              JOIN assignments a ON s.assignment_id = a.assignment_id 
                                              WHERE s.user_id = '$userId' 
                                              ORDER BY s.submitted_at DESC LIMIT 5";
                            }
                            $recent_res = mysqli_query($conn, $recent_sql);
                            
                            if(mysqli_num_rows($recent_res) > 0):
                                while($r = mysqli_fetch_assoc($recent_res)): ?>
                                <div class="d-flex align-items-start mb-4">
                                    <div class="bg-light rounded-circle p-2 me-3">
                                        <i class="bi bi-file-earmark-check text-primary"></i>
                                    </div>
                                    <div>
                                        <?php if($role == 'instructor'): ?>
                                            <h6 class="mb-0 small fw-bold text-dark"><?php echo $r['fullname']; ?></h6>
                                        <?php endif; ?>
                                        <p class="small text-muted mb-0"><?php echo ($role == 'instructor') ? 'Submitted' : 'You submitted'; ?> <span class="text-primary fw-medium"><?php echo $r['title']; ?></span></p>
                                        <small class="text-muted" style="font-size: 0.7rem;"><?php echo date('M d • h:i A', strtotime($r['submitted_at'])); ?></small>
                                    </div>
                                </div>
                            <?php endwhile; 
                            else: ?>
                                <p class="text-muted text-center py-5 small">No recent activity.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php if($role == 'instructor'): ?>
            <!-- Inactive Students Report -->
            <div class="row g-4 mt-2">
                <div class="col-12">
                    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                        <div class="card-header bg-white border-0 pt-4 px-4">
                            <h5 class="fw-bold text-dark mb-0"><i class="bi bi-person-exclamation me-2 text-danger"></i> At-Risk Students</h5>
                            <p class="text-muted small mb-0">Students with zero submissions across their enrolled modules.</p>
                        </div>
                        <div class="card-body px-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-4 small text-uppercase fw-bold text-muted">Student Name</th>
                                            <th class="small text-uppercase fw-bold text-muted">Course & Section</th>
                                            <th class="text-center small text-uppercase fw-bold text-muted">Enrolled In</th>
                                            <th class="text-center small text-uppercase fw-bold text-muted text-end pe-4">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Students with 0 submissions but are accepted in modules
                                        $at_risk_sql = "SELECT u.fullname, u.course, u.year_level, u.section, 
                                                       (SELECT COUNT(*) FROM enrollments e WHERE e.user_id = u.user_id AND e.status = 'accepted') as mod_count
                                                       FROM users u 
                                                       WHERE u.role = 'student' 
                                                       AND (SELECT COUNT(*) FROM enrollments e JOIN module m ON e.module_id = m.module_id WHERE e.user_id = u.user_id AND m.user_id = '$userId' AND e.status = 'accepted') > 0
                                                       AND (SELECT COUNT(*) FROM submissions s JOIN assignments a ON s.assignment_id = a.assignment_id JOIN module m ON a.module_id = m.module_id WHERE s.user_id = u.user_id AND m.user_id = '$userId') = 0
                                                       ORDER BY u.fullname ASC";
                                        $at_risk_res = mysqli_query($conn, $at_risk_sql);

                                        if(mysqli_num_rows($at_risk_res) > 0):
                                            while($at = mysqli_fetch_assoc($at_risk_res)): ?>
                                            <tr>
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center">
                                                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($at['fullname']); ?>&background=F8D7DA&color=721C24" class="rounded-circle me-3" width="30">
                                                        <span class="fw-bold small"><?php echo $at['fullname']; ?></span>
                                                    </div>
                                                </td>
                                                <td class="small"><?php echo $at['course']; ?> - <?php echo $at['year_level']; ?> (<?php echo $at['section']; ?>)</td>
                                                <td class="text-center small"><?php echo $at['mod_count']; ?> Module(s)</td>
                                                <td class="text-end pe-4">
                                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-1 fw-bold" style="font-size: 0.65rem;">INACTIVE</span>
                                                </td>
                                            </tr>
                                            <?php endwhile;
                                        else: ?>
                                            <tr>
                                                <td colspan="4" class="text-center py-4 text-muted small">No at-risk students found. Great job!</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
