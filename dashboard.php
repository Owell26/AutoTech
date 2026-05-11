<?php include 'includes/header.php'; ?>


<div class="d-flex" id="wrapper">
    <?php include 'includes/sidebar.php'; ?>

    <div id="page-content-wrapper" class="w-100">
        <!-- YOUR NAVBAR GOES HERE -->
        <nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom">
            <div class="container-fluid">
                <button class="btn btn-outline-secondary d-lg-none me-2" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-wrapper">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <?php if($system_logo): ?>
                    <img src="uploads/system/<?php echo $system_logo; ?>" width="40" height="40" class="rounded-circle me-2">
                <?php else: ?>
                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                        <i class="bi bi-cpu-fill text-white fs-5"></i>
                    </div>
                <?php endif; ?>
                <a class="navbar-brand fw-bold" href="dashboard.php"><?php echo $system_name; ?></a>
                <!-- Rest of your navbar code... -->
            </div>
        </nav>

        <div class="container-fluid p-4" style="background-color: #f8f9fa; min-height: 100vh;">
            
            <?php if(isset($_GET['status'])): ?>
                <div class="alert alert-<?php echo $_GET['status'] == 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <i class="bi <?php echo $_GET['status'] == 'success' ? 'bi-check-circle' : 'bi-exclamation-triangle'; ?> me-2"></i>
                    <?php echo isset($_GET['msg']) ? $_GET['msg'] : ($_GET['status'] == 'success' ? 'Operation successful!' : 'Something went wrong.'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Header: Modern Learning Dashboard -->
            <div class="row align-items-center mb-4">
                <div class="col-md-6 mb-3 mb-md-0">
                    <h2 class="fw-bold text-dark mb-1">My Modules</h2>
                    <p class="text-muted mb-0 small"><?php echo $_SESSION['role'] == 'instructor' ? 'Manage your classes and course materials' : 'Access your enrolled courses'; ?></p>
                </div>
                <div class="col-md-6">
                    <div class="d-flex flex-column flex-md-row gap-2 justify-content-md-end">
                        <form action="" method="GET" class="flex-grow-1" style="max-width: 400px;">
                            <div class="input-group bg-white shadow-sm rounded-pill overflow-hidden">
                                <span class="input-group-text bg-white border-0 ps-3"><i class="bi bi-search text-muted"></i></span>
                                <input type="text" name="search" id="moduleSearch" class="form-control border-0 shadow-none py-2" placeholder="Search modules..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" oninput="autoSearch()">
                                <?php if(isset($_GET['search']) && $_GET['search'] != ''): ?>
                                    <a href="dashboard.php" class="btn btn-white border-0 py-2 pe-3"><i class="bi bi-x-circle text-muted"></i></a>
                                <?php endif; ?>
                            </div>
                        </form>
                        
                        <div class="d-flex gap-2">
                            <?php if($_SESSION['role'] == 'student'): ?>
                                <button class="btn btn-outline-primary rounded-pill shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#joinModuleModal">
                                    <i class="bi bi-link-45deg me-2"></i> Join
                                </button>
                            <?php endif; ?>
                            
                            <?php if($_SESSION['role'] == 'instructor'): ?>
                            <div class="dropdown">
                                <button class="btn btn-primary rounded-pill shadow-sm px-4" data-bs-toggle="dropdown">
                                    <i class="bi bi-plus-lg me-2"></i> Create
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3">
                                    <li><a class="dropdown-item py-2" href="#" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-folder-plus me-2 text-primary"></i> New Module</a></li>
                                    <li><a class="dropdown-item py-2" href="#"><i class="bi bi-megaphone me-2 text-info"></i> Announcement</a></li>
                                </ul>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Main Content (Modules) -->
                <div class="<?php echo ($_SESSION['role'] == 'instructor') ? 'col-lg-8' : 'col-12'; ?>">
                    <div class="row g-4">
                        <?php
                        include 'database/dbconn.php';
                        $userId = $_SESSION['user_id'];
                        $role = $_SESSION['role'];
                        $search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

                        if ($role == 'instructor') {
                            $sql = "SELECT * FROM module WHERE user_id = '$userId'";
                            if($search != '') {
                                $sql .= " AND (module_description LIKE '%$search%' OR section LIKE '%$search%')";
                            }
                            $sql .= " ORDER BY module_id DESC";
                        } else {
                            // Students ONLY see modules they are enrolled in (pending or accepted)
                            $sql = "SELECT m.*, e.status as enrollment_status 
                                    FROM module m 
                                    INNER JOIN enrollments e ON m.module_id = e.module_id 
                                    WHERE e.user_id = '$userId'";
                            if($search != '') {
                                $sql .= " AND (m.module_description LIKE '%$search%' OR m.section LIKE '%$search%')";
                            }
                            $sql .= " ORDER BY m.module_id DESC";
                        }
                        
                        $sql_run = mysqli_query($conn, $sql);
                        
                        if ($sql_run && mysqli_num_rows($sql_run) > 0) {
                            while ($row = mysqli_fetch_assoc($sql_run)) {
                                $moduleId = $row['module_id'];
                                $moduleDescription = $row['module_description'];
                                $section = $row['section'];
                                $year = $row['year'];
                                $moduleCode = isset($row['module_code']) ? $row['module_code'] : 'N/A';
                                $isEnrolled = isset($row['enrollment_status']) && $row['enrollment_status'] == 'accepted';
                                $enrollmentStatus = isset($row['enrollment_status']) ? $row['enrollment_status'] : null;

                                $cardLink = ($role == 'instructor' || $isEnrolled) ? "view-module.php?id=$moduleId" : "#";
                                $onClick = ($role == 'instructor' || $isEnrolled) ? "window.location.href='$cardLink'" : "";
                                ?>
                                <div class='col-6 col-md-6 col-lg-4 col-xl-4 px-2 mb-3'>
                                    <div class='card h-100 border-0 shadow-sm transition-hover overflow-hidden' style='border-radius: 12px;'>
                                        <div class='card-header border-0 p-3 p-md-4' style='background: linear-gradient(135deg, #1a73e8 0%, #4285f4 100%); min-height: 100px;'>
                                            <div class='d-flex justify-content-between align-items-start'>
                                                <a href='<?php echo $cardLink; ?>' class='text-white text-decoration-none d-block text-truncate fw-bold' style='font-size: 0.9rem;'>
                                                    <?php echo $moduleDescription; ?>
                                                </a>
                                                <?php if($role == 'instructor'): ?>
                                                <div class='dropdown'>
                                                    <button class='btn btn-link text-white p-0' data-bs-toggle='dropdown'><i class='bi bi-three-dots-vertical'></i></button>
                                                    <ul class='dropdown-menu dropdown-menu-end shadow border-0'>
                                                        <li><a class='dropdown-item py-2' href='#' onclick="copyInviteLink('<?php echo $moduleCode; ?>')"><i class='bi bi-link-45deg me-2'></i> Copy Link</a></li>
                                                        <li><a class='dropdown-item py-2' href='#'><i class='bi bi-pencil me-2'></i> Edit</a></li>
                                                        <li><a class='dropdown-item py-2 text-danger' href='#'><i class='bi bi-trash me-2'></i> Delete</a></li>
                                                    </ul>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                            <small class='text-white-50 d-block mt-1'>Section <?php echo $section; ?> • <?php echo $year; ?></small>
                                            <?php if($role == 'instructor'): ?>
                                                <div class="mt-2">
                                                    <span class="badge bg-white bg-opacity-25 text-white fw-medium py-2 px-3" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                                        CODE: <?php echo $moduleCode; ?>
                                                    </span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class='card-body position-relative pb-4' onclick="<?php echo $onClick; ?>" style='cursor: <?php echo ($role == 'instructor' || $isEnrolled) ? 'pointer' : 'default'; ?>;'>
                                            <div class='position-absolute' style='top: -35px; right: 20px;'>
                                                <img src='https://ui-avatars.com/api/?name=<?php echo urlencode($moduleDescription); ?>&background=random&color=fff' class='rounded-circle border border-4 border-white shadow-sm' width='65' alt='Avatar'>
                                            </div>
                                            <div class='mt-4'>
                                                <?php if($role == 'student' && !$isEnrolled): ?>
                                                    <?php if($enrollmentStatus == 'pending'): ?>
                                                        <div class="alert alert-warning py-2 mb-0 small rounded-pill text-center border-0">
                                                            <i class="bi bi-clock-history me-2"></i>Waiting for Approval
                                                        </div>
                                                    <?php elseif($enrollmentStatus == 'rejected'): ?>
                                                        <div class="alert alert-danger py-2 mb-0 small rounded-pill text-center border-0">
                                                            <i class="bi bi-x-circle me-2"></i>Enrollment Rejected
                                                        </div>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <div class='bg-light p-2 rounded-3 border-0 mt-2'>
                                                        <p class='small text-dark mb-0 fw-medium text-center'><i class='bi bi-file-earmark-text me-2'></i>Access Granted</p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        
                                        <?php if($role == 'instructor' || $isEnrolled): ?>
                                        <div class='card-footer bg-transparent border-top-0 d-flex justify-content-end p-3'>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            ?>
                            <div class="col-12 text-center py-5">
                                <div class="bg-light rounded-circle p-4 d-inline-block mb-3">
                                    <i class="bi bi-journal-x fs-1 text-muted"></i>
                                </div>
                                <h5 class="text-muted fw-bold">No Modules Found</h5>
                                <p class="text-muted small"><?php echo $role == 'instructor' ? 'Start by creating your first module.' : 'Join a module using a code from your instructor.'; ?></p>
                            </div>
                            <?php
                        }
                        ?>

                        <?php if($role == 'instructor'): ?>
                        <div class="col-6 col-md-6 col-lg-4 col-xl-4 px-2 mb-3">
                            <div class="card h-100 border-2 border-dashed d-flex align-items-center justify-content-center bg-white py-4 py-md-5 transition-hover" 
                                 style="border-style: dashed !important; border-radius: 12px; cursor: pointer;"
                                 data-bs-toggle="modal" data-bs-target="#exampleModal">
                                <div class="text-center py-2">
                                    <div class="bg-light rounded-circle p-2 p-md-3 mb-2 d-inline-block">
                                        <i class="bi bi-plus-circle-dotted fs-3 text-primary"></i>
                                    </div>
                                    <h6 class="text-dark fw-bold mb-1 small">Add Module</h6>
                                    <p class="small text-muted px-2 d-none d-md-block" style="font-size: 0.7rem;">Create a new class</p>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if($role == 'instructor'): ?>
                <!-- Sidebar (Enrollment Requests) -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
                        <div class="card-body p-4">
                            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-person-check me-2 text-primary"></i> Enrollment Requests</h6>
                            <?php
                            $requests_sql = "SELECT e.*, u.fullname, m.module_description 
                                           FROM enrollments e 
                                           JOIN users u ON e.user_id = u.user_id 
                                           JOIN module m ON e.module_id = m.module_id 
                                           WHERE m.user_id = '$userId' AND e.status = 'pending'";
                            $requests_run = mysqli_query($conn, $requests_sql);

                            if ($requests_run && mysqli_num_rows($requests_run) > 0):
                                while($req = mysqli_fetch_assoc($requests_run)): ?>
                                <div class="bg-light p-3 rounded-3 mb-2 border-0">
                                    <p class="small mb-1"><strong><?php echo $req['fullname']; ?></strong> wants to join <strong><?php echo $req['module_description']; ?></strong></p>
                                    <div class="d-flex gap-2">
                                        <a href="controller/EnrollmentController.php?approve=<?php echo $req['enrollment_id']; ?>" class="btn btn-success btn-sm rounded-pill px-3 shadow-none" style="font-size: 0.7rem;">Accept</a>
                                        <a href="controller/EnrollmentController.php?reject=<?php echo $req['enrollment_id']; ?>" class="btn btn-outline-danger btn-sm rounded-pill px-3 shadow-none" style="font-size: 0.7rem;">Reject</a>
                                    </div>
                                </div>
                            <?php endwhile; 
                            else: ?>
                                <p class="small text-muted mb-0 text-center py-2">No pending requests.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
                        <div class="card-body p-4 text-center">
                            <h6 class="fw-bold text-dark mb-3">Quick Actions</h6>
                            <div class="d-grid gap-2">
                                <a href="students.php" class="btn btn-outline-primary rounded-pill btn-sm py-2"><i class="bi bi-people me-2"></i> View Students</a>
                                <a href="schedule.php" class="btn btn-outline-info rounded-pill btn-sm py-2"><i class="bi bi-calendar-check me-2"></i> Schedule</a>
                                <a href="reports.php" class="btn btn-outline-secondary rounded-pill btn-sm py-2"><i class="bi bi-bar-chart-steps me-2"></i> Reports</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <style>
            .transition-hover {
                transition: all 0.3s cubic-bezier(.25,.8,.25,1);
            }
            .transition-hover:hover {
                transform: translateY(-5px);
                box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;
            }
            .border-dashed {
                border-color: #dee2e6 !important;
            }
            .border-dashed:hover {
                border-color: #1a73e8 !important;
                background-color: #f0f7ff !important;
            }
        </style>
    </div>
</div>
<?php include 'modals/create-new-module.php'; ?>
<?php include 'modals/join-module-modal.php'; ?>
<?php include 'includes/footer.php'; ?>