<?php include 'includes/header.php'; ?>
<?php include 'database/dbconn.php'; ?>

<?php
if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$assignmentId = mysqli_real_escape_string($conn, $_GET['id']);
$userId = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Only instructors can access this page
if ($role !== 'instructor') {
    header("Location: dashboard.php");
    exit();
}

// Fetch assignment details
$assign_sql = "SELECT a.*, m.module_description FROM assignments a 
              JOIN module m ON a.module_id = m.module_id 
              WHERE a.assignment_id = '$assignmentId' LIMIT 1";
$assign_run = mysqli_query($conn, $assign_sql);

if (mysqli_num_rows($assign_run) > 0) {
    $assignment = mysqli_fetch_assoc($assign_run);
    $moduleId = $assignment['module_id'];
} else {
    header("Location: dashboard.php");
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
                        <li class="breadcrumb-item"><a href="dashboard.php" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="view-module.php?id=<?php echo $moduleId; ?>#assignments" class="text-decoration-none"><?php echo $assignment['module_description']; ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Submissions</li>
                    </ol>
                </nav>
            </div>
        </nav>

        <div class="container-fluid p-4" style="background-color: #f8f9fa; min-height: 100vh;">
            
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h3 class="fw-bold text-dark mb-1"><?php echo $assignment['title']; ?></h3>
                            <p class="text-muted mb-0 small">Reviewing student submissions</p>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                Deadline: <?php echo date('M d, Y • h:i A', strtotime($assignment['deadline'])); ?>
                            </span>
                        </div>
                    </div>
                    <div class="bg-light p-3 rounded-3 mb-0">
                        <p class="small text-muted mb-0"><strong>Instructions:</strong> <?php echo nl2br($assignment['instructions']); ?></p>
                    </div>
                </div>
            </div>

            <!-- Submissions Table -->
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3 border-0 small text-uppercase fw-bold text-muted">Student Name</th>
                                    <th class="py-3 border-0 small text-uppercase fw-bold text-muted">Submitted At</th>
                                    <th class="py-3 border-0 small text-uppercase fw-bold text-muted">Status</th>
                                    <th class="py-3 border-0 small text-uppercase fw-bold text-muted text-end pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sub_sql = "SELECT s.*, u.fullname, u.email, u.course, u.year_level, u.section 
                                           FROM submissions s 
                                           JOIN users u ON s.user_id = u.user_id 
                                           WHERE s.assignment_id = '$assignmentId' 
                                           ORDER BY s.submitted_at DESC";
                                $sub_run = mysqli_query($conn, $sub_sql);

                                if (mysqli_num_rows($sub_run) > 0) {
                                    while ($sub = mysqli_fetch_assoc($sub_run)) {
                                        $submittedAt = strtotime($sub['submitted_at']);
                                        $deadline = strtotime($assignment['deadline']);
                                        $isLate = $submittedAt > $deadline;
                                        ?>
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($sub['fullname']); ?>&background=random&color=fff" class="rounded-circle me-3" width="35">
                                                    <div>
                                                        <h6 class="mb-0 fw-bold small"><?php echo $sub['fullname']; ?></h6>
                                                        <small class="text-muted" style="font-size: 0.7rem;"><?php echo $sub['course']; ?> - <?php echo $sub['year_level']; ?> (<?php echo $sub['section']; ?>)</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="small text-dark"><?php echo date('M d, Y • h:i A', $submittedAt); ?></span>
                                            </td>
                                            <td>
                                                <?php if($isLate): ?>
                                                    <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-1 rounded-pill small">Late</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-1 rounded-pill small">On Time</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end pe-4">
                                                <button class="btn btn-primary btn-sm rounded-pill px-3 shadow-none" 
                                                        onclick='viewSubmissionDetails(<?php echo json_encode($sub); ?>, <?php echo $isLate ? "true" : "false"; ?>)'>
                                                    <i class="bi bi-eye me-1"></i> Review
                                                </button>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <div class="opacity-25 mb-2">
                                                <i class="bi bi-inbox display-4"></i>
                                            </div>
                                            <h6 class="text-muted">No submissions yet</h6>
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
        </div>
    </div>
</div>

<!-- View Submission Details Modal -->
<div class="modal fade" id="submissionDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header border-bottom-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="bi bi-person-badge me-2 text-primary"></i>Review Submission
                </h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 pb-4">
                <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                    <img id="modalAvatar" src="" class="rounded-circle me-3" width="50">
                    <div>
                        <h5 id="modalStudentName" class="mb-0 fw-bold text-dark"></h5>
                        <small id="modalStudentInfo" class="text-muted"></small>
                    </div>
                    <div class="ms-auto text-end">
                        <div id="modalStatusBadge"></div>
                        <small id="modalSubmittedTime" class="text-muted small d-block mt-1"></small>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold small text-uppercase text-muted">Submission Text</label>
                    <div id="modalSubmissionText" class="bg-light p-3 rounded-3 small text-dark" style="min-height: 100px; white-space: pre-wrap;"></div>
                </div>

                <div id="modalFileSection">
                    <label class="form-label fw-semibold small text-uppercase text-muted">Attached File</label>
                    <div class="card border-0 bg-light" style="border-radius: 10px;">
                        <div class="card-body p-3 d-flex align-items-center">
                            <div id="modalFileIcon" class="bg-white p-2 rounded-3 me-3">
                                <i class="bi bi-file-earmark-text fs-4 text-primary"></i>
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <h6 id="modalFileName" class="mb-0 text-dark fw-bold text-truncate small"></h6>
                                <small class="text-muted small">Click button to preview or download</small>
                            </div>
                            <div class="d-flex gap-2">
                                <a id="modalDownloadBtn" href="" class="btn btn-white btn-sm rounded-circle shadow-sm" download title="Download"><i class="bi bi-download text-primary"></i></a>
                                <button id="modalPreviewBtn" class="btn btn-primary btn-sm rounded-pill px-3 shadow-none" onclick="openFilePreviewFromSubmission()">Preview</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 px-4 pb-4 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4 fw-medium" data-bs-dismiss="modal">Close Review</button>
            </div>
        </div>
    </div>
</div>

<script>
let currentSubFile = '';
let currentSubTitle = '';

function viewSubmissionDetails(sub, isLate) {
    const modal = new bootstrap.Modal(document.getElementById('submissionDetailsModal'));
    
    document.getElementById('modalStudentName').innerText = sub.fullname;
    document.getElementById('modalStudentInfo').innerText = sub.course + ' - ' + sub.year_level + ' (' + sub.section + ')';
    document.getElementById('modalAvatar').src = `https://ui-avatars.com/api/?name=${encodeURIComponent(sub.fullname)}&background=random&color=fff`;
    document.getElementById('modalSubmittedTime').innerText = 'Submitted: ' + new Date(sub.submitted_at).toLocaleString();
    
    // Status Badge
    const badgeDiv = document.getElementById('modalStatusBadge');
    if (isLate) {
        badgeDiv.innerHTML = '<span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill small fw-bold">Late Submission</span>';
    } else {
        badgeDiv.innerHTML = '<span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill small fw-bold">On Time</span>';
    }

    // Submission Text
    const textDiv = document.getElementById('modalSubmissionText');
    textDiv.innerText = sub.submission_text || 'No text content provided.';

    // File Section
    const fileSection = document.getElementById('modalFileSection');
    if (sub.file_path) {
        fileSection.style.display = 'block';
        document.getElementById('modalFileName').innerText = sub.file_path.split('_').slice(1).join('_');
        document.getElementById('modalDownloadBtn').href = 'uploads/submissions/' + sub.file_path;
        currentSubFile = 'uploads/submissions/' + sub.file_path;
        currentSubTitle = sub.fullname + ' - Submission';
    } else {
        fileSection.style.display = 'none';
    }

    modal.show();
}

function openFilePreviewFromSubmission() {
    const ext = currentSubFile.split('.').pop();
    previewFile(currentSubFile, currentSubTitle, ext);
    // Hide details modal briefly so preview shows on top
    bootstrap.Modal.getInstance(document.getElementById('submissionDetailsModal')).hide();
}
</script>

<?php include 'modals/file-preview.php'; ?>
<?php include 'includes/footer.php'; ?>
