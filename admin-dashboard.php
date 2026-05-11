<?php include 'includes/header.php'; ?>
<?php
if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

// Fetch Statistics
$instructors_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role = 'instructor'"))['total'];
$students_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role = 'student'"))['total'];
$modules_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM module"))['total'];
?>

<div class="d-flex" id="wrapper">
    <?php include 'includes/sidebar.php'; ?>

    <div id="page-content-wrapper" class="w-100">
        <nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom">
            <div class="container-fluid">
                <button class="btn btn-outline-secondary d-lg-none me-2" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-wrapper">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a class="navbar-brand fw-bold" href="#"><?php echo $system_name; ?> Admin</a>
            </div>
        </nav>

        <div class="container-fluid p-4" style="background-color: #f8f9fa; min-height: 100vh;">
            <div class="mb-4">
                <h2 class="fw-bold text-dark mb-1">Admin Dashboard</h2>
                <p class="text-muted small">System monitoring and configuration</p>
            </div>

            <?php include 'includes/alerts.php'; ?>

            <!-- Stats Row -->
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                                    <i class="bi bi-person-badge text-primary fs-4"></i>
                                </div>
                                <h6 class="fw-bold text-muted mb-0">Total Instructors</h6>
                            </div>
                            <h2 class="fw-bold mb-0"><?php echo $instructors_count; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3">
                                    <i class="bi bi-people text-success fs-4"></i>
                                </div>
                                <h6 class="fw-bold text-muted mb-0">Total Students</h6>
                            </div>
                            <h2 class="fw-bold mb-0"><?php echo $students_count; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-info bg-opacity-10 p-3 rounded-circle me-3">
                                    <i class="bi bi-book text-info fs-4"></i>
                                </div>
                                <h6 class="fw-bold text-muted mb-0">Total Modules</h6>
                            </div>
                            <h2 class="fw-bold mb-0"><?php echo $modules_count; ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Settings -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">System Configuration</h5>
                            <form action="controller/AdminController.php" method="POST" enctype="multipart/form-data">
                                <div class="mb-4">
                                    <label class="form-label small fw-bold text-muted text-uppercase">System Name</label>
                                    <input type="text" name="system_name" class="form-control bg-light border-0 py-2 fs-6" value="<?php echo htmlspecialchars($system_name); ?>" required style="border-radius: 10px;">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label small fw-bold text-muted text-uppercase">System Logo</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <?php if($system_logo): ?>
                                            <img src="uploads/system/<?php echo $system_logo; ?>" class="rounded shadow-sm border" width="80" height="80" style="object-fit: cover;">
                                        <?php endif; ?>
                                        <input type="file" name="system_logo" class="form-control bg-light border-0 py-2 fs-6" style="border-radius: 10px;">
                                    </div>
                                    <small class="text-muted d-block mt-2">Recommended size: 200x200px. JPG, PNG allowed.</small>
                                </div>
                                <div class="mt-4 pt-3 border-top">
                                    <button type="submit" name="updateSettings" class="btn btn-primary rounded-pill px-5 shadow-sm fw-medium">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Admin: Recent Feedback -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">User Feedback</h5>
                            <div class="row g-3">
                                <?php
                                $all_f_sql = "SELECT f.*, u.fullname FROM feedback f JOIN users u ON f.user_id = u.user_id ORDER BY f.created_at DESC LIMIT 10";
                                $all_f_run = mysqli_query($conn, $all_f_sql);

                                if(mysqli_num_rows($all_f_run) > 0):
                                    while($f = mysqli_fetch_assoc($all_f_run)):
                                        $f_status_color = ($f['status'] == 'pending') ? 'warning' : (($f['status'] == 'reviewed') ? 'info' : 'success');
                                ?>
                                    <div class="col-12">
                                        <div class="bg-light p-3 rounded-3 mb-2 border-0">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <small class="fw-bold text-primary"><?php echo htmlspecialchars($f['fullname']); ?></small>
                                                <span class="badge bg-<?php echo $f_status_color; ?> bg-opacity-10 text-<?php echo $f_status_color; ?> rounded-pill px-2 py-1" style="font-size: 0.6rem;">
                                                    <?php echo $f['status']; ?>
                                                </span>
                                            </div>
                                            <h6 class="fw-bold small mb-1"><?php echo htmlspecialchars($f['subject']); ?></h6>
                                            <p class="text-muted mb-2" style="font-size: 0.75rem;"><?php echo htmlspecialchars($f['message']); ?></p>
                                            
                                            <?php if($f['status'] !== 'resolved'): ?>
                                                <div class="dropdown">
                                                    <button class="btn btn-white btn-sm py-0 small dropdown-toggle border-0" data-bs-toggle="dropdown">Action</button>
                                                    <ul class="dropdown-menu shadow border-0 small">
                                                        <li><a class="dropdown-item" href="controller/FeedbackController.php?update_status=1&id=<?php echo $f['feedback_id']; ?>&status_val=reviewed">Mark Reviewed</a></li>
                                                        <li><a class="dropdown-item" href="controller/FeedbackController.php?update_status=1&id=<?php echo $f['feedback_id']; ?>&status_val=resolved">Mark Resolved</a></li>
                                                    </ul>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endwhile; else: ?>
                                    <p class="text-muted small text-center">No feedback yet.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
