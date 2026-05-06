<?php include 'includes/header.php'; ?>
<?php include 'database/dbconn.php'; ?>

<?php
$userId = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Only instructors can access this page
if ($role !== 'instructor') {
    header("Location: dashboard.php");
    exit();
}

// Get selected module ID if any
$selectedModuleId = isset($_GET['module_id']) ? mysqli_real_escape_string($conn, $_GET['module_id']) : null;
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
                        <li class="breadcrumb-item active" aria-current="page">Student Management</li>
                    </ol>
                </nav>
            </div>
        </nav>

        <div class="container-fluid p-4" style="background-color: #f8f9fa; min-height: 100vh;">
            
            <?php if(isset($_GET['status'])): ?>
                <div class="alert alert-<?php echo $_GET['status'] == 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 10px;">
                    <i class="bi <?php echo $_GET['status'] == 'success' ? 'bi-check-circle' : 'bi-exclamation-triangle'; ?> me-2"></i>
                    <?php echo isset($_GET['msg']) ? $_GET['msg'] : ''; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row g-4">
                <!-- Module Selection Sidebar -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                        <div class="card-body p-4">
                            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-journal-bookmark me-2 text-primary"></i> Select Module</h5>
                            <p class="text-muted small mb-4">Choose a module to view and manage its students.</p>
                            
                            <div class="list-group list-group-flush">
                                <?php
                                $modules_sql = "SELECT * FROM module WHERE user_id = '$userId' ORDER BY module_description ASC";
                                $modules_run = mysqli_query($conn, $modules_sql);
                                
                                if (mysqli_num_rows($modules_run) > 0) {
                                    while ($module = mysqli_fetch_assoc($modules_run)) {
                                        $isActive = ($selectedModuleId == $module['module_id']) ? 'active-module shadow-sm' : '';
                                        $count_sql = "SELECT COUNT(*) as total FROM enrollments WHERE module_id = '" . $module['module_id'] . "' AND status = 'accepted'";
                                        $student_count = mysqli_fetch_assoc(mysqli_query($conn, $count_sql))['total'];
                                        ?>
                                        <a href="students.php?module_id=<?php echo $module['module_id']; ?>" 
                                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 border-0 rounded-3 mb-2 <?php echo $isActive; ?>">
                                            <div>
                                                <h6 class="mb-0 fw-bold module-title"><?php echo $module['module_description']; ?></h6>
                                                <small class="module-subtitle">Section <?php echo $module['section']; ?></small>
                                            </div>
                                            <span class="badge rounded-pill module-badge">
                                                <?php echo $student_count; ?>
                                            </span>
                                        </a>
                                        <?php
                                    }
                                } else {
                                    echo '<p class="text-center text-muted small py-3">No modules found.</p>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Student List Area -->
                <div class="col-lg-8">
                    <?php if ($selectedModuleId): 
                        // Get module details
                        $mod_info = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM module WHERE module_id = '$selectedModuleId'"));
                        
                        // Search and Filter logic
                        $search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
                        $filter = isset($_GET['filter']) ? mysqli_real_escape_string($conn, $_GET['filter']) : 'all';
                        ?>
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                            <div class="card-header bg-white border-0 p-4">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <h5 class="fw-bold text-dark mb-1"><?php echo $mod_info['module_description']; ?></h5>
                                        <p class="text-muted small mb-0">Managing students for Section <?php echo $mod_info['section']; ?></p>
                                    </div>
                                    <div class="col-md-6 mt-3 mt-md-0">
                                        <form action="" method="GET" class="d-flex gap-2">
                                            <input type="hidden" name="module_id" value="<?php echo $selectedModuleId; ?>">
                                            <div class="input-group bg-light rounded-pill overflow-hidden border-0">
                                                <span class="input-group-text bg-transparent border-0 ps-3"><i class="bi bi-search small text-muted"></i></span>
                                                <input type="text" name="search" id="studentSearch" class="form-control bg-transparent border-0 shadow-none small" placeholder="Search students..." value="<?php echo htmlspecialchars($search); ?>" oninput="autoSearch()">
                                            </div>
                                            <select name="filter" class="form-select bg-light rounded-pill border-0 shadow-none small" onchange="this.form.submit()" style="max-width: 130px;">
                                                <option value="all" <?php echo $filter == 'all' ? 'selected' : ''; ?>>All Status</option>
                                                <option value="pending" <?php echo $filter == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                <option value="accepted" <?php echo $filter == 'accepted' ? 'selected' : ''; ?>>Accepted</option>
                                                <option value="rejected" <?php echo $filter == 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                                            </select>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="ps-4 py-3 small text-uppercase fw-bold text-muted">Student Name</th>
                                                <th class="py-3 small text-uppercase fw-bold text-muted">Course & Year</th>
                                                <th class="py-3 small text-uppercase fw-bold text-muted text-center">Status</th>
                                                <th class="py-3 small text-uppercase fw-bold text-muted text-end pe-4">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $students_sql = "SELECT e.enrollment_id, e.status, u.fullname, u.course, u.year_level, u.section as student_section 
                                                           FROM enrollments e 
                                                           JOIN users u ON e.user_id = u.user_id 
                                                           WHERE e.module_id = '$selectedModuleId'";
                                            
                                            if($search != '') {
                                                $students_sql .= " AND (u.fullname LIKE '%$search%' OR u.course LIKE '%$search%')";
                                            }
                                            
                                            if($filter != 'all') {
                                                $students_sql .= " AND e.status = '$filter'";
                                            }
                                            
                                            $students_sql .= " ORDER BY u.fullname ASC";
                                            $students_run = mysqli_query($conn, $students_sql);

                                            if (mysqli_num_rows($students_run) > 0) {
                                                while ($student = mysqli_fetch_assoc($students_run)) {
                                                    $status = $student['status'];
                                                    $badgeClass = ($status == 'accepted') ? 'bg-success' : (($status == 'pending') ? 'bg-warning' : 'bg-danger');
                                                    ?>
                                                    <tr>
                                                        <td class="ps-4">
                                                            <div class="d-flex align-items-center">
                                                                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($student['fullname']); ?>&background=random&color=fff" class="rounded-circle me-3" width="35">
                                                                <h6 class="mb-0 fw-bold small"><?php echo $student['fullname']; ?></h6>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="small text-dark fw-medium"><?php echo $student['course']; ?></span><br>
                                                            <small class="text-muted" style="font-size: 0.7rem;"><?php echo $student['year_level']; ?> (<?php echo $student['student_section']; ?>)</small>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge <?php echo $badgeClass; ?> bg-opacity-10 text-<?php echo str_replace('bg-', '', $badgeClass); ?> px-3 py-1 rounded-pill small text-capitalize">
                                                                <?php echo $status; ?>
                                                            </span>
                                                        </td>
                                                        <td class="text-end pe-4">
                                                            <div class="d-flex justify-content-end gap-2">
                                                                <?php if($status == 'pending'): ?>
                                                                    <a href="controller/EnrollmentController.php?approve=<?php echo $student['enrollment_id']; ?>&mod_id=<?php echo $selectedModuleId; ?>&from=students" class="btn btn-success btn-sm rounded-pill px-3 shadow-none" style="font-size: 0.7rem;">Approve</a>
                                                                <?php endif; ?>
                                                                <a href="controller/EnrollmentController.php?remove=<?php echo $student['enrollment_id']; ?>&mod_id=<?php echo $selectedModuleId; ?>&from=students" 
                                                                   class="btn btn-outline-danger btn-sm rounded-pill px-3 shadow-none" 
                                                                   style="font-size: 0.7rem;"
                                                                   onclick="return confirm('Are you sure you want to remove this student?')">
                                                                    Remove
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="4" class="text-center py-5">
                                                        <div class="opacity-25 mb-2"><i class="bi bi-people display-4"></i></div>
                                                        <p class="text-muted mb-0 small">No students enrolled in this module yet.</p>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                            <div class="card-body p-5 text-center d-flex flex-column align-items-center justify-content-center">
                                <div class="bg-light rounded-circle p-4 mb-4">
                                    <i class="bi bi-arrow-left-circle display-4 text-muted opacity-50"></i>
                                </div>
                                <h4 class="fw-bold text-dark mb-2">No Module Selected</h4>
                                <p class="text-muted px-lg-5">Please select a module from the left panel to manage its enrolled students and pending requests.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .active-module {
        background-color: #0d6efd !important;
        color: white !important;
    }
    .active-module .module-title,
    .active-module .module-subtitle {
        color: white !important;
    }
    .active-module .module-badge {
        background-color: rgba(255, 255, 255, 0.2) !important;
        color: white !important;
    }
    
    /* Default styles */
    .module-title { color: #212529; }
    .module-subtitle { color: #6c757d; }
    .module-badge { background-color: #f8f9fa; color: #6c757d; }
    
    .list-group-item-action:hover:not(.active-module) {
        background-color: #f0f7ff !important;
    }
</style>

<?php include 'includes/footer.php'; ?>
