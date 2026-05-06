<!-- Manage Students Modal -->
<div class="modal fade" id="viewStudentsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header border-bottom-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="bi bi-people me-2 text-primary"></i>Manage Class Students
                </h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-0 pb-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 border-0 small text-uppercase fw-bold text-muted">Student</th>
                                <th class="py-3 border-0 small text-uppercase fw-bold text-muted">Course & Section</th>
                                <th class="py-3 border-0 small text-uppercase fw-bold text-muted text-center">Status</th>
                                <th class="py-3 border-0 small text-uppercase fw-bold text-muted text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $students_sql = "SELECT e.enrollment_id, e.status, u.fullname, u.course, u.year_level, u.section 
                                           FROM enrollments e 
                                           JOIN users u ON e.user_id = u.user_id 
                                           WHERE e.module_id = '$moduleId' 
                                           ORDER BY e.status ASC, u.fullname ASC";
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
                                            <span class="small text-dark"><?php echo $student['course']; ?></span><br>
                                            <small class="text-muted" style="font-size: 0.7rem;"><?php echo $student['year_level']; ?> (<?php echo $student['section']; ?>)</small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge <?php echo $badgeClass; ?> bg-opacity-10 text-<?php echo str_replace('bg-', '', $badgeClass); ?> px-3 py-1 rounded-pill small text-capitalize">
                                                <?php echo $status; ?>
                                            </span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="d-flex justify-content-end gap-2">
                                                <?php if($status == 'pending'): ?>
                                                    <a href="controller/EnrollmentController.php?approve=<?php echo $student['enrollment_id']; ?>&mod_id=<?php echo $moduleId; ?>" class="btn btn-success btn-sm rounded-pill px-3 shadow-none" style="font-size: 0.7rem;">Approve</a>
                                                <?php endif; ?>
                                                <a href="controller/EnrollmentController.php?remove=<?php echo $student['enrollment_id']; ?>&mod_id=<?php echo $moduleId; ?>" 
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
    </div>
</div>
