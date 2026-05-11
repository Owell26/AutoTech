<?php
if(isset($_SESSION['status']) && $_SESSION['status'] != ''): ?>
    <div class="alert alert-<?php echo $_SESSION['status'] == 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show border-0 shadow-sm mb-4 small" role="alert" style="border-radius: 10px;">
        <i class="bi <?php echo $_SESSION['status'] == 'success' ? 'bi-check-circle' : 'bi-exclamation-triangle'; ?> me-2"></i>
        <?php 
            echo $_SESSION['msg']; 
            unset($_SESSION['status']);
            unset($_SESSION['msg']);
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
