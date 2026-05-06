<!-- Submit Assignment Modal -->
<div class="modal fade" id="submitAssignmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header border-bottom-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark" id="submitModalTitle">
                    <i class="bi bi-cloud-arrow-up me-2 text-primary"></i>Submit Assignment
                </h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <form action="controller/AssignmentController.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="module_id" value="<?php echo $moduleId; ?>">
                <input type="hidden" name="assignment_id" id="submitAssignmentId">
                <div class="modal-body px-4 pb-4">
                    <div class="alert alert-info py-2 small mb-4 border-0" style="border-radius: 10px;">
                        <i class="bi bi-info-circle me-2"></i>You can submit a file, text, or both.
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-uppercase text-muted">Submission Text (Optional)</label>
                        <textarea name="submission_text" class="form-control bg-light border-0 shadow-none py-2" rows="3" placeholder="Enter any comments or text work here..." style="border-radius: 10px;"></textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold small text-uppercase text-muted">Upload File (Optional)</label>
                        <input type="file" name="submissionFile" class="form-control bg-light border-0 shadow-none py-2" style="border-radius: 10px;">
                    </div>
                </div>
                <div class="modal-footer border-top-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="submitAssignment" class="btn btn-primary rounded-pill px-4 shadow-sm">Submit Work</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function setAssignmentId(id, title) {
    document.getElementById('submitAssignmentId').value = id;
    document.getElementById('submitModalTitle').innerHTML = '<i class="bi bi-cloud-arrow-up me-2 text-primary"></i>Submit: ' + title;
}
</script>
