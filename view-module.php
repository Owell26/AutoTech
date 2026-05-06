<?php include 'includes/header.php'; ?>
<?php include 'database/dbconn.php'; ?>

<?php
if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$moduleId = $_GET['id'];
$userId = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Check if user has access
if ($role == 'student') {
    $check_access = "SELECT status FROM enrollments WHERE user_id = '$userId' AND module_id = '$moduleId' AND status = 'accepted'";
    $access_run = mysqli_query($conn, $check_access);
    if (mysqli_num_rows($access_run) == 0) {
        header("Location: dashboard.php?status=error&msg=Access Denied: You must be enrolled and accepted.");
        exit();
    }
}

$sql = "SELECT * FROM module WHERE module_id = '$moduleId'";
$sql_run = mysqli_query($conn, $sql);

if (mysqli_num_rows($sql_run) > 0) {
    $module = mysqli_fetch_assoc($sql_run);
} else {
    header("Location: index.php");
    exit();
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
                <a class="navbar-brand" href="dashboard.php">AutoTech</a>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="dashboard.php" class="text-decoration-none">Modules</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo $module['module_description']; ?></li>
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

            <!-- Module Header Banner: Clean & Elegant -->
            <div class="rounded-4 p-5 mb-4 text-white shadow-sm" style="background: linear-gradient(135deg, #1a73e8 0%, #4285f4 100%); position: relative; overflow: hidden;">
                <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                <div class="position-relative">
                    <h1 class="display-5 fw-bold mb-2"><?php echo $module['module_description']; ?></h1>
                    <p class="lead mb-0 opacity-75">Section <?php echo $module['section']; ?> • <?php echo $module['year']; ?></p>
                    <?php if($role == 'instructor'): ?>
                        <div class="mt-3">
                            <span class="badge bg-white bg-opacity-25 py-2 px-3 fw-medium">
                                <i class="bi bi-link-45deg me-1"></i> Code: <?php echo $module['module_code']; ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row g-4">
                <!-- Main Content Section -->
                <div class="col-lg-8">
                    
                    <!-- Navigation Tabs -->
                    <ul class="nav nav-pills mb-4 ps-2" id="moduleTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-pill px-4 fw-medium me-2 shadow-none border-0" id="materials-tab" data-bs-toggle="pill" data-bs-target="#materials-content" type="button" role="tab">
                                <i class="bi bi-folder2 me-2"></i>Materials
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill px-4 fw-medium shadow-none border-0" id="assignments-tab" data-bs-toggle="pill" data-bs-target="#assignments-content" type="button" role="tab">
                                <i class="bi bi-journal-check me-2"></i>Assignments
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill px-4 fw-medium shadow-none border-0" id="discussions-tab" data-bs-toggle="pill" data-bs-target="#discussions-content" type="button" role="tab">
                                <i class="bi bi-chat-dots me-2"></i>Discussions
                            </button>
                        </li>
                    </ul>

                    <script>
                        // Persistent Tabs Logic
                        document.addEventListener('DOMContentLoaded', function() {
                            // Restore tab from URL hash
                            const hash = window.location.hash;
                            if (hash) {
                                const targetTab = document.querySelector(`button[data-bs-target="${hash}-content"]`);
                                if (targetTab) {
                                    const tab = new bootstrap.Tab(targetTab);
                                    tab.show();
                                }
                            }

                            // Update hash when tab is clicked
                            const tabButtons = document.querySelectorAll('button[data-bs-toggle="pill"]');
                            tabButtons.forEach(button => {
                                button.addEventListener('shown.bs.tab', function(event) {
                                    const targetId = event.target.getAttribute('data-bs-target').replace('-content', '');
                                    window.location.hash = targetId;
                                });
                            });
                        });
                    </script>

                    <div class="tab-content" id="moduleTabsContent">
                        <!-- Materials Tab -->
                        <div class="tab-pane fade show active" id="materials-content" role="tabpanel">
                            <?php if($role == 'instructor'): ?>
                            <!-- Instructor Quick Upload Area -->
                            <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['fullname']); ?>&background=0D6EFD&color=fff" class="rounded-circle me-3" width="45" alt="User">
                                        <button class="btn btn-light bg-light text-muted w-100 text-start rounded-pill px-4 border-0 shadow-none py-2" 
                                                data-bs-toggle="modal" data-bs-target="#uploadMaterialModal">
                                            Announce something to your class or upload a file...
                                        </button>
                                    </div>
                                    <div class="d-flex justify-content-end gap-2 border-top pt-3 mt-2">
                                        <button class="btn btn-light btn-sm rounded-pill px-3 fw-medium text-primary border-0 shadow-none" data-bs-toggle="modal" data-bs-target="#uploadMaterialModal">
                                            <i class="bi bi-file-earmark-plus me-1"></i> Add Material
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- Materials List -->
                            <div class="d-flex justify-content-between align-items-center mb-4 ps-2">
                                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-collection me-2 text-primary"></i> Class Materials</h5>
                                <div class="d-flex gap-2">
                                    <div class="input-group input-group-sm" style="width: 250px;">
                                        <span class="input-group-text bg-white border-0 shadow-sm rounded-start-pill ps-3"><i class="bi bi-search text-muted"></i></span>
                                        <input type="text" id="materialSearch" class="form-control border-0 shadow-sm rounded-end-pill py-2" placeholder="Search materials...">
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-white btn-sm rounded-circle shadow-sm border-0 bg-white" data-bs-toggle="dropdown" title="Filter & Sort">
                                            <i class="bi bi-filter"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-3" style="min-width: 200px; border-radius: 12px;">
                                            <li><h6 class="dropdown-header px-0 mb-2 text-uppercase small fw-bold">Filter by Type</h6></li>
                                            <li>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input type-filter" type="checkbox" value="all" id="typeAll" checked>
                                                    <label class="form-check-label small" for="typeAll">All Types</label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input type-filter" type="checkbox" value="pdf" id="typePdf">
                                                    <label class="form-check-label small" for="typePdf">PDF Documents</label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input type-filter" type="checkbox" value="word" id="typeWord">
                                                    <label class="form-check-label small" for="typeWord">Word Files</label>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input type-filter" type="checkbox" value="image" id="typeImage">
                                                    <label class="form-check-label small" for="typeImage">Images</label>
                                                </div>
                                            </li>
                                            <li><hr class="dropdown-divider mx-n3"></li>
                                            <li><h6 class="dropdown-header px-0 mb-2 mt-2 text-uppercase small fw-bold">Sort by</h6></li>
                                            <li><a class="dropdown-item rounded-3 mb-1 small sort-option active" href="#" data-sort="date-desc">Newest First</a></li>
                                            <li><a class="dropdown-item rounded-3 mb-1 small sort-option" href="#" data-sort="title-asc">Title (A-Z)</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-4" id="materialsContainer">
                            <?php
                            $material_sql = "SELECT * FROM materials WHERE module_id = '$moduleId' ORDER BY created_at DESC";
                            $material_run = mysqli_query($conn, $material_sql);

                            if ($material_run && mysqli_num_rows($material_run) > 0) {
                                while ($material = mysqli_fetch_assoc($material_run)) {
                                    $fileExt = strtolower(pathinfo($material['file_path'], PATHINFO_EXTENSION));
                                    $iconClass = 'bi-file-earmark';
                                    $iconColor = '#5f6368';
                                    $bgColor = '#f8f9fa';
                                    $typeCategory = 'other';

                                    if(in_array($fileExt, ['pdf'])) { 
                                        $iconClass = 'bi-file-earmark-pdf'; $iconColor = '#ea4335'; $bgColor = '#fef7f6'; $typeCategory = 'pdf';
                                    }
                                    elseif(in_array($fileExt, ['doc', 'docx'])) { 
                                        $iconClass = 'bi-file-earmark-word'; $iconColor = '#4285f4'; $bgColor = '#f4f8fe'; $typeCategory = 'word';
                                    }
                                    elseif(in_array($fileExt, ['jpg', 'png', 'jpeg'])) { 
                                        $iconClass = 'bi-file-earmark-image'; $iconColor = '#34a853'; $bgColor = '#f6fdf8'; $typeCategory = 'image';
                                    }

                                    ?>
                                    <div class="col-12 material-item" 
                                         data-title="<?php echo strtolower($material['title']); ?>" 
                                         data-type="<?php echo $typeCategory; ?>"
                                         data-date="<?php echo strtotime($material['created_at']); ?>">
                                        <div class="card border-0 shadow-sm transition-hover" style="border-radius: 12px;">
                                            <div class="card-body p-3 d-flex align-items-center">
                                                <div class="rounded-3 p-3 me-3 d-flex align-items-center justify-content-center" style="background-color: <?php echo $bgColor; ?>; width: 55px; height: 55px;">
                                                    <i class="bi <?php echo $iconClass; ?> fs-3" style="color: <?php echo $iconColor; ?>;"></i>
                                                </div>
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <h6 class="mb-1 text-dark fw-bold text-truncate material-title"><?php echo $material['title']; ?></h6>
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-calendar3 me-1 small text-muted"></i>
                                                        <small class="text-muted" style="font-size: 0.75rem;">Published <?php echo date('M d, Y', strtotime($material['created_at'])); ?></small>
                                                    </div>
                                                </div>
                                                <div class="dropdown">
                                                    <button class="btn btn-light btn-sm rounded-circle border-0 me-2" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></button>
                                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg">
                                                        <?php if($role == 'instructor'): ?>
                                                            <li><a class="dropdown-item py-2" href="#"><i class="bi bi-pencil me-2"></i> Edit</a></li>
                                                            <li><a class="dropdown-item py-2 text-danger" href="#"><i class="bi bi-trash me-2"></i> Remove</a></li>
                                                            <li><hr class="dropdown-divider"></li>
                                                        <?php endif; ?>
                                                        <li><a class="dropdown-item py-2" href="uploads/materials/<?php echo $material['file_path']; ?>" download><i class="bi bi-download me-2"></i> Download</a></li>
                                                    </ul>
                                                </div>
                                                <button class="btn btn-primary btn-sm rounded-pill px-3 shadow-none" 
                                                        onclick="previewFile('uploads/materials/<?php echo $material['file_path']; ?>', '<?php echo addslashes($material['title']); ?>', '<?php echo $fileExt; ?>')">
                                                    <i class="bi bi-eye me-1"></i> Preview
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            } else {
                                ?>
                                <div class="col-12">
                                    <div class="card border-0 shadow-sm text-center py-5" style="border-radius: 12px;">
                                        <div class="card-body">
                                            <i class="bi bi-cloud-upload text-muted display-4 mb-3 d-block"></i>
                                            <h6 class="text-dark fw-bold">No materials yet</h6>
                                            <p class="small text-muted mb-3"><?php echo ($role == 'instructor') ? 'Upload your first lesson or resource to share with students.' : 'No materials have been uploaded by the instructor yet.'; ?></p>
                                            <?php if($role == 'instructor'): ?>
                                                <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#uploadMaterialModal">Upload Now</button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                            </div>
                        </div>

                        <!-- Assignments Tab -->
                        <div class="tab-pane fade" id="assignments-content" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-4 ps-2">
                                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-journal-text me-2 text-primary"></i> Class Assignments</h5>
                                <?php if($role == 'instructor'): ?>
                                    <button class="btn btn-primary btn-sm rounded-pill px-3 fw-medium shadow-sm" data-bs-toggle="modal" data-bs-target="#createAssignmentModal">
                                        <i class="bi bi-plus-lg me-1"></i> Create Assignment
                                    </button>
                                <?php endif; ?>
                            </div>

                            <div class="row g-4">
                            <?php
                            $assign_sql = "SELECT * FROM assignments WHERE module_id = '$moduleId' ORDER BY deadline ASC";
                            $assign_run = mysqli_query($conn, $assign_sql);

                            if ($assign_run && mysqli_num_rows($assign_run) > 0) {
                                while ($assign = mysqli_fetch_assoc($assign_run)) {
                                    $assignId = $assign['assignment_id'];
                                    $deadline = strtotime($assign['deadline']);
                                    $now = time();
                                    
                                    // Check if student has submitted
                                    $status = "Missing";
                                    $statusColor = "danger";
                                    $submitted = false;

                                    if ($role == 'student') {
                                        $sub_check = "SELECT * FROM submissions WHERE assignment_id = '$assignId' AND user_id = '$userId' LIMIT 1";
                                        $sub_run = mysqli_query($conn, $sub_check);
                                        if (mysqli_num_rows($sub_run) > 0) {
                                            $submission = mysqli_fetch_assoc($sub_run);
                                            $submitted = true;
                                            if (strtotime($submission['submitted_at']) > $deadline) {
                                                $status = "Late";
                                                $statusColor = "warning";
                                            } else {
                                                $status = "Submitted";
                                                $statusColor = "success";
                                            }
                                        } elseif ($now > $deadline) {
                                            $status = "Missing";
                                            $statusColor = "danger";
                                        } else {
                                            $status = "Pending";
                                            $statusColor = "secondary";
                                        }
                                    } else {
                                        // Instructor view: Show submission count
                                        $sub_count_sql = "SELECT COUNT(*) as total FROM submissions WHERE assignment_id = '$assignId'";
                                        $sub_count_run = mysqli_query($conn, $sub_count_sql);
                                        $sub_count = mysqli_fetch_assoc($sub_count_run)['total'];
                                        $status = "$sub_count Submissions";
                                        $statusColor = "primary";
                                    }
                                    ?>
                                    <div class="col-12">
                                        <div class="card border-0 shadow-sm transition-hover" style="border-radius: 12px;">
                                            <div class="card-body p-4">
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <div>
                                                        <h6 class="fw-bold text-dark mb-1 fs-5"><?php echo $assign['title']; ?></h6>
                                                        <span class="badge bg-<?php echo $statusColor; ?> bg-opacity-10 text-<?php echo $statusColor; ?> rounded-pill px-3 py-2 small fw-bold">
                                                            <i class="bi bi-info-circle me-1"></i><?php echo $status; ?>
                                                        </span>
                                                    </div>
                                                    <div class="text-end">
                                                        <small class="text-muted d-block small text-uppercase fw-bold mb-1">Due Date</small>
                                                        <span class="text-<?php echo ($now > $deadline && !$submitted) ? 'danger' : 'dark'; ?> fw-bold">
                                                            <?php echo date('M d, Y • h:i A', $deadline); ?>
                                                        </span>
                                                    </div>
                                                </div>
                                                <p class="text-muted small mb-4 line-clamp-2"><?php echo $assign['instructions']; ?></p>
                                                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-clock me-2 text-muted"></i>
                                                        <small class="text-muted">
                                                            <?php 
                                                            if ($now > $deadline) echo "Deadline passed";
                                                            else {
                                                                $diff = $deadline - $now;
                                                                $days = floor($diff / 86400);
                                                                echo ($days > 0) ? "$days days left" : "Due today";
                                                            }
                                                            ?>
                                                        </small>
                                                    </div>
                                                    <?php if($role == 'student'): ?>
                                                        <?php if(!$submitted): ?>
                                                            <button class="btn btn-primary rounded-pill px-4 btn-sm shadow-none" 
                                                                    data-bs-toggle="modal" data-bs-target="#submitAssignmentModal"
                                                                    onclick="setAssignmentId('<?php echo $assignId; ?>', '<?php echo addslashes($assign['title']); ?>')">
                                                                Submit Work
                                                            </button>
                                                        <?php else: ?>
                                                            <button class="btn btn-outline-success rounded-pill px-4 btn-sm border-2 shadow-none"
                                                                    onclick='viewMySubmission(<?php echo json_encode($submission); ?>, "<?php echo addslashes($assign['title']); ?>")'>
                                                                <i class="bi bi-check2 me-1"></i>View Submission
                                                            </button>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <a href="view-submissions.php?id=<?php echo $assignId; ?>" class="btn btn-primary rounded-pill px-4 btn-sm shadow-none">
                                                            <i class="bi bi-eye me-1"></i> Review Submissions
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            } else {
                                ?>
                                <div class="col-12">
                                    <div class="card border-0 shadow-sm text-center py-5" style="border-radius: 12px;">
                                        <div class="card-body">
                                            <i class="bi bi-journal-x text-muted display-4 mb-3 d-block"></i>
                                            <h6 class="text-dark fw-bold">No assignments yet</h6>
                                            <p class="small text-muted mb-0"><?php echo ($role == 'instructor') ? 'Click the button above to create your first assignment.' : 'No assignments have been posted for this module yet.'; ?></p>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                            </div>
                        </div>

                        <!-- Discussions Tab -->
                        <div class="tab-pane fade" id="discussions-content" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-4 ps-2">
                                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-chat-left-text me-2 text-primary"></i> Class Discussion</h5>
                            </div>

                            <!-- Post Question/Comment -->
                            <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
                                <div class="card-body p-4">
                                    <form action="controller/DiscussionController.php" method="POST">
                                        <input type="hidden" name="module_id" value="<?php echo $moduleId; ?>">
                                        <div class="d-flex align-items-start mb-3">
                                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['fullname']); ?>&background=0D6EFD&color=fff" class="rounded-circle me-3" width="45" alt="User">
                                            <div class="flex-grow-1">
                                                <textarea name="message" class="form-control bg-light border-0 shadow-none py-3 px-4" rows="2" placeholder="Ask a question or start a discussion..." style="border-radius: 15px;" required></textarea>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <button type="submit" name="postDiscussion" class="btn btn-primary rounded-pill px-4 shadow-sm fw-medium">
                                                <i class="bi bi-send me-1"></i> Post
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Discussion Feed -->
                            <div class="discussion-feed">
                                <?php
                                // Fetch main posts (parent_id = 0)
                                $disc_sql = "SELECT d.*, u.fullname, u.role 
                                           FROM discussions d 
                                           JOIN users u ON d.user_id = u.user_id 
                                           WHERE d.module_id = '$moduleId' AND d.parent_id = 0 
                                           ORDER BY d.created_at DESC";
                                $disc_run = mysqli_query($conn, $disc_sql);

                                if (mysqli_num_rows($disc_run) > 0) {
                                    while ($post = mysqli_fetch_assoc($disc_run)) {
                                        $postId = $post['discussion_id'];
                                        ?>
                                        <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
                                            <div class="card-body p-4">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <div class="d-flex align-items-center">
                                                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($post['fullname']); ?>&background=random&color=fff" class="rounded-circle me-3" width="40">
                                                        <div>
                                                            <h6 class="mb-0 fw-bold"><?php echo $post['fullname']; ?> 
                                                                <?php if($post['role'] == 'instructor'): ?>
                                                                    <span class="badge bg-primary bg-opacity-10 text-primary small ms-1 fw-medium" style="font-size: 0.65rem;">Teacher</span>
                                                                <?php endif; ?>
                                                            </h6>
                                                            <small class="text-muted" style="font-size: 0.7rem;"><?php echo date('M d, Y • h:i A', strtotime($post['created_at'])); ?></small>
                                                        </div>
                                                    </div>
                                                    <?php if($role == 'instructor' || $post['user_id'] == $userId): ?>
                                                        <a href="controller/DiscussionController.php?delete_id=<?php echo $postId; ?>&module_id=<?php echo $moduleId; ?>" class="text-danger opacity-50 hover-opacity-100" onclick="return confirm('Delete this post?')"><i class="bi bi-trash"></i></a>
                                                    <?php endif; ?>
                                                </div>
                                                <p class="text-dark mb-3 ps-5 ms-2"><?php echo nl2br($post['message']); ?></p>
                                                
                                                <!-- Replies Container -->
                                                <div class="ms-5 ps-3 border-start">
                                                    <?php
                                                    $reply_sql = "SELECT d.*, u.fullname, u.role 
                                                                FROM discussions d 
                                                                JOIN users u ON d.user_id = u.user_id 
                                                                WHERE d.parent_id = '$postId' 
                                                                ORDER BY d.created_at ASC";
                                                    $reply_run = mysqli_query($conn, $reply_sql);
                                                    while ($reply = mysqli_fetch_assoc($reply_run)):
                                                    ?>
                                                        <div class="mb-3">
                                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                                <div class="d-flex align-items-center">
                                                                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($reply['fullname']); ?>&background=random&color=fff" class="rounded-circle me-2" width="30">
                                                                    <div>
                                                                        <span class="small fw-bold d-block" style="line-height: 1;"><?php echo $reply['fullname']; ?>
                                                                            <?php if($reply['role'] == 'instructor'): ?>
                                                                                <span class="badge bg-primary bg-opacity-10 text-primary small fw-medium" style="font-size: 0.6rem;">Teacher</span>
                                                                            <?php endif; ?>
                                                                        </span>
                                                                        <small class="text-muted" style="font-size: 0.65rem;"><?php echo date('M d, Y • h:i A', strtotime($reply['created_at'])); ?></small>
                                                                    </div>
                                                                </div>
                                                                <?php if($role == 'instructor' || $reply['user_id'] == $userId): ?>
                                                                    <a href="controller/DiscussionController.php?delete_id=<?php echo $reply['discussion_id']; ?>&module_id=<?php echo $moduleId; ?>" class="text-danger small opacity-50" onclick="return confirm('Delete reply?')"><i class="bi bi-trash"></i></a>
                                                                <?php endif; ?>
                                                            </div>
                                                            <p class="small text-dark mb-0 ps-4 ms-2"><?php echo nl2br($reply['message']); ?></p>
                                                        </div>
                                                    <?php endwhile; ?>

                                                    <!-- Reply Input -->
                                                    <form action="controller/DiscussionController.php" method="POST" class="mt-3">
                                                        <input type="hidden" name="module_id" value="<?php echo $moduleId; ?>">
                                                        <input type="hidden" name="parent_id" value="<?php echo $postId; ?>">
                                                        <div class="input-group">
                                                            <input type="text" name="message" class="form-control bg-light border-0 shadow-none small" placeholder="Write a reply..." style="border-radius: 10px 0 0 10px; font-size: 0.85rem;" required>
                                                            <button class="btn btn-primary px-3" type="submit" name="postDiscussion" style="border-radius: 0 10px 10px 0;"><i class="bi bi-reply-fill"></i></button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <div class="text-center py-5">
                                        <i class="bi bi-chat-quote text-muted display-4 opacity-25"></i>
                                        <p class="text-muted mt-3">No discussions yet. Be the first to start one!</p>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar (Upcoming & Info) -->
                <div class="col-lg-4">
                    <?php if($role == 'instructor'): ?>
                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
                        <div class="card-body p-4 text-center">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                                <i class="bi bi-people fs-3 text-primary"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1">Class Stats</h6>
                            <p class="small text-muted mb-3">Keep track of your students</p>
                            <div class="d-flex justify-content-center gap-4 border-top pt-3">
                                <?php
                                    $count_students = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM enrollments WHERE module_id = '$moduleId' AND status = 'accepted'"))['total'];
                                    $count_pending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM enrollments WHERE module_id = '$moduleId' AND status = 'pending'"))['total'];
                                ?>
                                <div>
                                    <h5 class="fw-bold mb-0"><?php echo $count_students; ?></h5>
                                    <small class="text-muted small">Students</small>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-0"><?php echo $count_pending; ?></h5>
                                    <small class="text-muted small">Pending</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
                        <div class="card-body p-4">
                            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-calendar-event me-2 text-primary"></i> Upcoming Assignments</h6>
                            <?php
                            $upcoming_sql = "SELECT * FROM assignments 
                                           WHERE module_id = '$moduleId' 
                                           AND deadline >= NOW() 
                                           ORDER BY deadline ASC 
                                           LIMIT 3";
                            $upcoming_run = mysqli_query($conn, $upcoming_sql);

                            if (mysqli_num_rows($upcoming_run) > 0) {
                                while ($upcoming = mysqli_fetch_assoc($upcoming_run)) {
                                    $deadline = strtotime($upcoming['deadline']);
                                    
                                    // Check status for student
                                    $statusBadge = '';
                                    if ($role == 'student') {
                                        $upAssignId = $upcoming['assignment_id'];
                                        $sub_check = mysqli_query($conn, "SELECT submission_id FROM submissions WHERE assignment_id = '$upAssignId' AND user_id = '$userId' LIMIT 1");
                                        if (mysqli_num_rows($sub_check) > 0) {
                                            $statusBadge = '<span class="badge bg-success bg-opacity-10 text-success p-0 mt-1" style="font-size: 0.65rem;"><i class="bi bi-check-circle-fill me-1"></i>Submitted</span>';
                                        } else {
                                            $statusBadge = '<span class="badge bg-warning bg-opacity-10 text-warning p-0 mt-1" style="font-size: 0.65rem;"><i class="bi bi-clock-history me-1"></i>Pending</span>';
                                        }
                                    }
                                    ?>
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-light rounded-3 p-2 text-center me-3" style="min-width: 50px;">
                                            <span class="d-block fw-bold text-dark mb-0"><?php echo date('d', $deadline); ?></span>
                                            <small class="text-muted small text-uppercase"><?php echo date('M', $deadline); ?></small>
                                        </div>
                                        <div class="overflow-hidden">
                                            <h6 class="mb-0 small fw-bold text-truncate"><?php echo $upcoming['title']; ?></h6>
                                            <div class="d-flex flex-column">
                                                <small class="text-muted small"><?php echo date('h:i A', $deadline); ?></small>
                                                <?php echo $statusBadge; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            } else {
                                ?>
                                <p class="text-muted small text-center mb-0 py-2">No upcoming assignments.</p>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if($role == 'instructor'): ?>
    <?php include 'modals/upload-material.php'; ?>
    <?php include 'modals/create-assignment.php'; ?>
<?php else: ?>
    <?php include 'modals/submit-assignment.php'; ?>
    <?php include 'modals/student-view-submission.php'; ?>
<?php endif; ?>
<?php include 'modals/file-preview.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('materialSearch');
    const typeFilters = document.querySelectorAll('.type-filter');
    const sortOptions = document.querySelectorAll('.sort-option');
    const container = document.getElementById('materialsContainer');
    const items = Array.from(document.querySelectorAll('.material-item'));

    function filterAndSort() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedTypes = Array.from(typeFilters)
            .filter(cb => cb.checked && cb.value !== 'all')
            .map(cb => cb.value);
        const isAllSelected = document.getElementById('typeAll').checked;
        const activeSort = document.querySelector('.sort-option.active').dataset.sort;

        // 1. Filter
        let visibleCount = 0;
        items.forEach(item => {
            const title = item.dataset.title;
            const type = item.dataset.type;
            
            const matchesSearch = title.includes(searchTerm);
            const matchesType = isAllSelected || selectedTypes.includes(type);

            if (matchesSearch && matchesType) {
                item.style.display = 'block';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        // 2. Sort
        items.sort((a, b) => {
            if (activeSort === 'title-asc') {
                return a.dataset.title.localeCompare(b.dataset.title);
            } else { // date-desc
                return b.dataset.date - a.dataset.date;
            }
        });

        // Re-append sorted items
        items.forEach(item => container.appendChild(item));

        // 3. Show "No results" if needed
        let emptyState = document.getElementById('noResultsState');
        if (visibleCount === 0) {
            if (!emptyState) {
                emptyState = document.createElement('div');
                emptyState.id = 'noResultsState';
                emptyState.className = 'col-12 text-center py-5';
                emptyState.innerHTML = `
                    <div class="bg-light rounded-circle p-4 d-inline-block mb-3">
                        <i class="bi bi-search display-4 text-muted opacity-25"></i>
                    </div>
                    <h5 class="text-muted fw-bold">No matching materials</h5>
                    <p class="text-muted small">Try adjusting your search or filters.</p>
                `;
                container.appendChild(emptyState);
            }
        } else if (emptyState) {
            emptyState.remove();
        }
    }

    // Event Listeners
    searchInput.addEventListener('input', filterAndSort);

    typeFilters.forEach(filter => {
        filter.addEventListener('change', function() {
            if (this.id === 'typeAll' && this.checked) {
                // If "All" checked, uncheck others
                typeFilters.forEach(cb => { if(cb.id !== 'typeAll') cb.checked = false; });
            } else if (this.id !== 'typeAll' && this.checked) {
                // If specific type checked, uncheck "All"
                document.getElementById('typeAll').checked = false;
            }
            filterAndSort();
        });
    });

    sortOptions.forEach(option => {
        option.addEventListener('click', function(e) {
            e.preventDefault();
            sortOptions.forEach(opt => opt.classList.remove('active'));
            this.classList.add('active');
            filterAndSort();
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>
