<!-- Create Assignment Modal -->
<div class="modal fade" id="createAssignmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header border-bottom-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="bi bi-journal-plus me-2 text-primary"></i>Create Assignment
                </h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <form action="controller/AssignmentController.php" method="POST">
                <input type="hidden" name="module_id" value="<?php echo $moduleId; ?>">
                <div class="modal-body px-4 pb-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-uppercase text-muted">Assignment Title</label>
                        <input type="text" name="title" class="form-control bg-light border-0 shadow-none py-2" placeholder="e.g., Engine Diagram" style="border-radius: 10px;" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-uppercase text-muted">Instructions</label>
                        <textarea name="instructions" class="form-control bg-light border-0 shadow-none py-2" rows="4" placeholder="Describe what students need to do..." style="border-radius: 10px;" required></textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold small text-uppercase text-muted">Deadline</label>
                        <input type="datetime-local" name="deadline" class="form-control bg-light border-0 shadow-none py-2" style="border-radius: 10px;" required>
                    </div>
                </div>
                <div class="modal-footer border-top-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="createAssignment" class="btn btn-primary rounded-pill px-4 shadow-sm">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>
