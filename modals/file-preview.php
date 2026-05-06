<!-- File Preview Modal -->
<div class="modal fade" id="filePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px; height: 90vh;">
            <div class="modal-header border-bottom-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center">
                    <div id="previewIcon" class="me-2 text-primary"></div>
                    <span id="previewTitle" class="text-truncate" style="max-width: 70vw;">File Preview</span>
                </h5>
                <div class="ms-auto d-flex align-items-center">
                    <a id="previewDownloadBtn" href="#" class="btn btn-outline-primary btn-sm rounded-pill px-3 me-2 shadow-none" download>
                        <i class="bi bi-download me-1"></i> Download
                    </a>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                </div>
            </div>
            <div class="modal-body p-0 d-flex flex-column bg-light" style="overflow-y: auto;">
                <div id="previewContent" class="flex-grow-1 d-flex align-items-center justify-content-center p-0">
                    <!-- Preview content will be injected here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewFile(filePath, title, ext) {
    const previewModal = new bootstrap.Modal(document.getElementById('filePreviewModal'));
    const contentDiv = document.getElementById('previewContent');
    const titleSpan = document.getElementById('previewTitle');
    const iconDiv = document.getElementById('previewIcon');
    const downloadBtn = document.getElementById('previewDownloadBtn');
    
    titleSpan.innerText = title;
    downloadBtn.href = filePath;
    
    // Clear content
    contentDiv.innerHTML = '';
    
    const extension = ext.toLowerCase();
    
    if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(extension)) {
        iconDiv.innerHTML = '<i class="bi bi-file-earmark-image fs-4"></i>';
        contentDiv.innerHTML = `<img src="${filePath}" class="img-fluid rounded" style="width: 100%; height: auto; display: block; margin: 0 auto;">`;
        contentDiv.parentElement.style.padding = '0'; // Remove padding for images to maximize space
    } 
    else if (extension === 'pdf') {
        iconDiv.innerHTML = '<i class="bi bi-file-earmark-pdf fs-4 text-danger"></i>';
        contentDiv.innerHTML = `<iframe src="${filePath}" style="width: 100%; height: 100%; border: none; border-radius: 0;"></iframe>`;
        contentDiv.parentElement.style.padding = '0';
    }
    else {
        // Fallback for unsupported preview types (Doc, Zip, etc.)
        let icon = 'bi-file-earmark';
        if(['doc', 'docx'].includes(extension)) icon = 'bi-file-earmark-word text-primary';
        if(['zip', 'rar'].includes(extension)) icon = 'bi-file-earmark-zip text-warning';
        
        iconDiv.innerHTML = `<i class="bi ${icon} fs-4"></i>`;
        contentDiv.innerHTML = `
            <div class="text-center p-5">
                <i class="bi ${icon.split(' ')[0]} display-1 text-muted opacity-25 mb-4"></i>
                <h4 class="text-muted">Preview not available</h4>
                <p class="text-muted mb-4">This file type (.${extension}) cannot be previewed directly in the browser.</p>
                <a href="${filePath}" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm" download>
                    <i class="bi bi-download me-2"></i> Download File
                </a>
            </div>
        `;
    }
    
    previewModal.show();
}
</script>

<style>
    #filePreviewModal .modal-xl {
        max-width: 95%;
    }
    #previewContent iframe {
        background: #fff;
    }
</style>
