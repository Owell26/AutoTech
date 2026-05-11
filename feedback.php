<?php include 'includes/header.php'; ?>

<div class="d-flex" id="wrapper">
    <?php include 'includes/sidebar.php'; ?>

    <div id="page-content-wrapper" class="w-100">
        <nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom">
            <div class="container-fluid">
                <button class="btn btn-outline-secondary d-lg-none me-2" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-wrapper">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a class="navbar-brand fw-bold" href="#"><?php echo $system_name; ?> Feedback</a>
            </div>
        </nav>

        <div class="container-fluid p-4" style="background-color: #f8f9fa; min-height: 100vh;">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <?php if($_SESSION['role'] === 'admin'): ?>
                        <!-- Admin View: All Feedback Table -->
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                            <div class="card-header bg-white p-4 border-bottom">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h4 class="fw-bold mb-1 text-dark"><i class="bi bi-shield-check me-2 text-primary"></i> Feedback Management</h4>
                                        <p class="mb-0 text-muted small">Review and resolve system feedback from users.</p>
                                    </div>
                                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2">
                                        <?php 
                                        $count_sql = "SELECT COUNT(*) as total FROM feedback WHERE status = 'pending'";
                                        $count_res = mysqli_fetch_assoc(mysqli_query($conn, $count_sql));
                                        echo $count_res['total'];
                                        ?> Pending Reports
                                    </span>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive" style="overflow: visible;">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead>
                                            <tr class="bg-light">
                                                <th class="ps-4 py-3 border-0 text-uppercase small fw-bold text-muted" style="width: 20%;">User</th>
                                                <th class="py-3 border-0 text-uppercase small fw-bold text-muted" style="width: 15%;">Subject</th>
                                                <th class="py-3 border-0 text-uppercase small fw-bold text-muted" style="width: 35%;">Message</th>
                                                <th class="py-3 border-0 text-uppercase small fw-bold text-muted" style="width: 15%;">Status</th>
                                                <th class="pe-4 py-3 border-0 text-uppercase small fw-bold text-muted text-end" style="width: 15%;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="border-top-0">
                                            <?php
                                            $all_f_sql = "SELECT f.*, u.fullname, u.role as user_role FROM feedback f JOIN users u ON f.user_id = u.user_id ORDER BY f.created_at DESC";
                                            $all_f_run = mysqli_query($conn, $all_f_sql);

                                            if(mysqli_num_rows($all_f_run) > 0):
                                                while($f = mysqli_fetch_assoc($all_f_run)):
                                                    $f_status_color = ($f['status'] == 'pending') ? 'warning' : (($f['status'] == 'reviewed') ? 'info' : 'success');
                                                    $status_icon = ($f['status'] == 'pending') ? 'bi-clock-history' : (($f['status'] == 'reviewed') ? 'bi-eye' : 'bi-check-all');
                                            ?>
                                                <tr>
                                                    <td class="ps-4 py-3">
                                                        <div class="d-flex align-items-center">
                                                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($f['fullname']); ?>&background=random&color=fff" class="rounded-circle me-2" width="32" height="32">
                                                            <div>
                                                                <div class="fw-bold text-dark small"><?php echo htmlspecialchars($f['fullname']); ?></div>
                                                                <div class="text-muted" style="font-size: 0.7rem;"><?php echo ucfirst($f['user_role']); ?></div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="small fw-medium text-dark"><?php echo htmlspecialchars($f['subject']); ?></span>
                                                        <div class="text-muted mt-1" style="font-size: 0.65rem;"><?php echo date('M d, Y', strtotime($f['created_at'])); ?></div>
                                                    </td>
                                                    <td>
                                                        <div class="text-muted small lh-sm" style="max-width: 300px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                            <?php echo htmlspecialchars($f['message']); ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-inline-flex align-items-center bg-<?php echo $f_status_color; ?> bg-opacity-10 text-<?php echo $f_status_color; ?> px-3 py-1 rounded-pill small fw-bold" style="font-size: 0.7rem;">
                                                            <i class="bi <?php echo $status_icon; ?> me-1"></i>
                                                            <?php echo ucfirst($f['status']); ?>
                                                        </div>
                                                    </td>
                                                    <td class="pe-4 text-end">
                                                        <?php if($f['status'] !== 'resolved'): ?>
                                                            <div class="d-flex justify-content-end gap-2">
                                                                <?php if($f['status'] === 'pending'): ?>
                                                                <a href="controller/FeedbackController.php?update_status=1&id=<?php echo $f['feedback_id']; ?>&status_val=reviewed" 
                                                                   class="btn btn-sm btn-light rounded-circle p-2 shadow-none border" 
                                                                   title="Mark as Reviewed">
                                                                    <i class="bi bi-eye text-info"></i>
                                                                </a>
                                                                <?php endif; ?>
                                                                <a href="controller/FeedbackController.php?update_status=1&id=<?php echo $f['feedback_id']; ?>&status_val=resolved" 
                                                                   class="btn btn-sm btn-light rounded-circle p-2 shadow-none border" 
                                                                   title="Mark as Resolved">
                                                                    <i class="bi bi-check-lg text-success"></i>
                                                                </a>
                                                            </div>
                                                        <?php else: ?>
                                                            <span class="text-success small fw-bold">
                                                                <i class="bi bi-check-all fs-5"></i> Solved
                                                            </span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endwhile; else: ?>
                                                <tr>
                                                    <td colspan="5" class="text-center py-5">
                                                        <i class="bi bi-inbox text-muted display-4 d-block mb-3"></i>
                                                        <p class="text-muted small">No feedback reports found.</p>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- User View: Submission Form -->
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                            <div class="card-header bg-primary p-4 text-white border-0">
                                <h4 class="fw-bold mb-1"><i class="bi bi-chat-right-text me-2"></i> System Feedback</h4>
                                <p class="mb-0 opacity-75 small">Help us improve the portal by sending your suggestions or reporting bugs.</p>
                            </div>
                            <div class="card-body p-4">
                            <?php include 'includes/alerts.php'; ?>

                            <form action="controller/FeedbackController.php" method="POST">
                                    <div class="mb-4">
                                        <label class="form-label small fw-bold text-muted text-uppercase">Subject</label>
                                        <select name="subject" class="form-select bg-light border-0 py-2 fs-6" required style="border-radius: 10px;">
                                            <option value="" selected disabled>Choose a subject</option>
                                            <option value="Bug Report">Bug Report</option>
                                            <option value="Suggestion">Suggestion</option>
                                            <option value="UI/UX Feedback">UI/UX Feedback</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label small fw-bold text-muted text-uppercase">Your Message</label>
                                        <textarea name="message" class="form-control bg-light border-0 py-3 fs-6" rows="5" placeholder="Tell us more about your feedback..." required style="border-radius: 10px;"></textarea>
                                    </div>
                                    <div class="mt-4 pt-3 border-top">
                                        <button type="submit" name="sendFeedback" class="btn btn-primary rounded-pill px-5 shadow-sm fw-medium">
                                            <i class="bi bi-send me-2"></i>Submit Feedback
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- User View: My Recent Feedback -->
                        <div class="mt-5">
                            <h5 class="fw-bold mb-4">My Recent Feedback</h5>
                            <div class="row g-3">
                                <?php
                                $u_id = $_SESSION['user_id'];
                                $f_sql = "SELECT * FROM feedback WHERE user_id = '$u_id' ORDER BY created_at DESC LIMIT 5";
                                $f_run = mysqli_query($conn, $f_sql);

                                if(mysqli_num_rows($f_run) > 0):
                                    while($row = mysqli_fetch_assoc($f_run)):
                                        $status_color = ($row['status'] == 'pending') ? 'warning' : (($row['status'] == 'reviewed') ? 'info' : 'success');
                                ?>
                                    <div class="col-12">
                                        <div class="card border-0 shadow-sm rounded-4 p-3">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <span class="badge bg-<?php echo $status_color; ?> bg-opacity-10 text-<?php echo $status_color; ?> rounded-pill px-3 py-1 small text-capitalize mb-2 d-inline-block">
                                                        <?php echo $row['status']; ?>
                                                    </span>
                                                    <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($row['subject']); ?></h6>
                                                </div>
                                                <small class="text-muted" style="font-size: 0.7rem;"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></small>
                                            </div>
                                            <p class="text-muted small mb-0"><?php echo htmlspecialchars($row['message']); ?></p>
                                        </div>
                                    </div>
                                <?php endwhile; else: ?>
                                    <div class="col-12 text-center py-4">
                                        <p class="text-muted small">No feedback sent yet.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
