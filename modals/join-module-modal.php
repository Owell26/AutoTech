<!-- Join Module Modal -->
<div class="modal fade" id="joinModuleModal" tabindex="-1" aria-labelledby="joinModuleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header border-bottom-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark" id="joinModuleModalLabel">
                    <i class="bi bi-link-45deg me-2 text-primary"></i>Join a Module
                </h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="controller/EnrollmentController.php" method="post">
                <div class="modal-body px-4 pb-4">
                    <p class="text-muted small mb-4">Ask your instructor for the module code, then enter it below.</p>
                    
                    <div class="mb-0">
                        <label for="moduleCode" class="form-label fw-semibold small text-uppercase text-muted">Module Code</label>
                        <input type="text" class="form-control form-control-lg bg-light border-0 shadow-none fs-4 text-center fw-bold" 
                               id="moduleCode" name="moduleCode" 
                               placeholder="XXXXXX" 
                               style="border-radius: 10px; letter-spacing: 5px;" 
                               maxlength="6" required>
                    </div>
                </div>
                <div class="modal-footer border-top-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-medium" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-medium shadow-sm" name="joinWithCode">
                        Join Now
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function copyInviteLink(code) {
        const url = window.location.origin + window.location.pathname.replace('dashboard.php', '') + 'join.php?code=' + code;
        navigator.clipboard.writeText(url).then(() => {
            alert('Invite link copied to clipboard!');
        });
    }
</script>

<style>
    #joinModuleModal .form-control:focus {
        background-color: #fff !important;
        box-shadow: 0 0 0 0.25rem rgba(26, 115, 232, 0.1) !important;
        border: 1px solid #1a73e8 !important;
    }
</style>
