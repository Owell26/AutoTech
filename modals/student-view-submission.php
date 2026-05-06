<!-- Student View Submission Modal -->
<div class="modal fade" id="studentViewSubmissionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header border-bottom-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="bi bi-file-earmark-check me-2 text-success"></i>My Submission
                </h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 pb-4">
                <div class="mb-4">
                    <h6 id="mySubAssignmentTitle" class="fw-bold text-primary mb-1"></h6>
                    <small id="mySubTime" class="text-muted"></small>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold small text-uppercase text-muted">My Response</label>
                    <div id="mySubText" class="bg-light p-3 rounded-3 small text-dark" style="min-height: 100px; white-space: pre-wrap;"></div>
                </div>

                <div id="mySubFileSection">
                    <label class="form-label fw-semibold small text-uppercase text-muted">Attached File</label>
                    <div class="card border-0 bg-light" style="border-radius: 10px;">
                        <div class="card-body p-3 d-flex align-items-center">
                            <div class="bg-white p-2 rounded-3 me-3">
                                <i class="bi bi-file-earmark-text fs-4 text-primary"></i>
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <h6 id="mySubFileName" class="mb-0 text-dark fw-bold text-truncate small"></h6>
                            </div>
                            <div class="d-flex gap-2">
                                <a id="mySubDownloadBtn" href="" class="btn btn-white btn-sm rounded-circle shadow-sm" download title="Download"><i class="bi bi-download text-primary"></i></a>
                                <button id="mySubPreviewBtn" class="btn btn-primary btn-sm rounded-pill px-3 shadow-none" onclick="previewMyFile()">Preview</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 px-4 pb-4 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4 fw-medium" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
let mySubCurrentFile = '';
let mySubCurrentTitle = '';

function viewMySubmission(sub, assignTitle) {
    const modal = new bootstrap.Modal(document.getElementById('studentViewSubmissionModal'));
    
    document.getElementById('mySubAssignmentTitle').innerText = assignTitle;
    document.getElementById('mySubTime').innerText = 'Submitted on: ' + new Date(sub.submitted_at).toLocaleString();
    document.getElementById('mySubText').innerText = sub.submission_text || 'No text content provided.';

    const fileSection = document.getElementById('mySubFileSection');
    if (sub.file_path) {
        fileSection.style.display = 'block';
        document.getElementById('mySubFileName').innerText = sub.file_path.split('_').slice(1).join('_');
        document.getElementById('mySubDownloadBtn').href = 'uploads/submissions/' + sub.file_path;
        mySubCurrentFile = 'uploads/submissions/' + sub.file_path;
        mySubCurrentTitle = 'My Submission - ' + assignTitle;
    } else {
        fileSection.style.display = 'none';
    }

    modal.show();
}

function previewMyFile() {
    const ext = mySubCurrentFile.split('.').pop();
    previewFile(mySubCurrentFile, mySubCurrentTitle, ext);
    bootstrap.Modal.getInstance(document.getElementById('studentViewSubmissionModal')).hide();
}
</script>
