<!-- Upload Material Modal -->
<div class="modal fade" id="uploadMaterialModal" tabindex="-1" aria-labelledby="uploadMaterialModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header border-bottom-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark" id="uploadMaterialModalLabel">
                    <i class="bi bi-cloud-arrow-up me-2 text-primary"></i>Upload Material
                </h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="controller/MaterialController.php" method="post" enctype="multipart/form-data">
                <div class="modal-body px-4 pb-4">
                    <p class="text-muted small mb-4">Add a new lesson, resource, or document to this module.</p>
                    
                    <!-- Hidden field to pass the module ID -->
                    <input type="hidden" name="moduleId" value="<?php echo $moduleId; ?>">
                    
                    <div class="mb-3">
                        <label for="materialTitle" class="form-label fw-semibold small text-uppercase text-muted">Material Title</label>
                        <input type="text" class="form-control form-control-lg bg-light border-0 shadow-none fs-6" 
                               id="materialTitle" name="materialTitle" 
                               placeholder="e.g., Lesson 1: Introduction to AI" 
                               style="border-radius: 10px;" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="materialDescription" class="form-label fw-semibold small text-uppercase text-muted">Description (Optional)</label>
                        <textarea class="form-control bg-light border-0 shadow-none fs-6" 
                                  id="materialDescription" name="materialDescription" 
                                  rows="3" placeholder="Briefly describe what this file is about..."
                                  style="border-radius: 10px;"></textarea>
                    </div>
                    
                    <div class="mb-0">
                        <label for="materialFile" class="form-label fw-semibold small text-uppercase text-muted">Select File</label>
                        <div class="input-group">
                            <input class="form-control bg-light border-0 shadow-none fs-6" 
                                   type="file" id="materialFile" name="materialFile" 
                                   style="border-top-left-radius: 10px; border-bottom-left-radius: 10px;" required>
                        </div>
                        <div class="form-text small mt-2">Accepted formats: PDF, DOCX, Images (Max 10MB)</div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-medium" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-medium shadow-sm" name="uploadMaterial">
                        Upload Now
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    #uploadMaterialModal .form-control:focus {
        background-color: #fff !important;
        box-shadow: 0 0 0 0.25rem rgba(26, 115, 232, 0.1) !important;
        border: 1px solid #1a73e8 !important;
    }
</style>
