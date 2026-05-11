<!-- Sidebar -->
<div class="bg-white border-end shadow-sm sidebar-responsive" id="sidebar-wrapper">
    <div class="sidebar-heading p-4 d-flex align-items-center border-bottom bg-white">
        <div class="bg-primary rounded-3 p-2 me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
            <i class="bi bi-cpu-fill text-white fs-5"></i>
        </div>
        <div>
            <span class="fs-5 fw-bold text-dark d-block" style="letter-spacing: -0.5px;">AutoTech</span>
            <span class="text-muted small fw-medium">Learning Portal</span>
        </div>
        <button class="btn d-lg-none ms-auto border-0 shadow-none text-muted" type="button" id="close-sidebar">
            <i class="bi bi-x-lg fs-5"></i>
        </button>
    </div>
    
    <div class="list-group list-group-flush px-3 mt-4 custom-scrollbar">
        <label class="text-uppercase text-muted fw-bold mb-2 ps-3" style="font-size: 0.65rem; letter-spacing: 1px;">Main Menu</label>
        
        <a href="dashboard" class="list-group-item list-group-item-action border-0 rounded-3 py-3 px-3 mb-1 d-flex align-items-center transition-all <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active-link' : 'text-secondary'; ?>">
            <i class="bi bi-grid-1x2-fill me-3 fs-5"></i>
            <span class="fw-medium">Dashboard</span>
        </a>

        <?php if($_SESSION['role'] == 'admin'): ?>
        <a href="admin-dashboard" class="list-group-item list-group-item-action border-0 rounded-3 py-3 px-3 mb-1 d-flex align-items-center transition-all <?php echo (basename($_SERVER['PHP_SELF']) == 'admin-dashboard.php') ? 'active-link' : 'text-secondary'; ?>">
            <i class="bi bi-speedometer2 me-3 fs-5"></i>
            <span class="fw-medium">Admin Monitoring</span>
        </a>
        <?php endif; ?>

        <?php if($_SESSION['role'] !== 'admin'): ?>
        <a href="students" class="list-group-item list-group-item-action border-0 rounded-3 py-3 px-3 mb-1 d-flex align-items-center transition-all <?php echo (basename($_SERVER['PHP_SELF']) == 'students.php') ? 'active-link' : 'text-secondary'; ?>">
            <i class="bi bi-people-fill me-3 fs-5"></i>
            <span class="fw-medium">Students</span>
        </a>
        <?php endif; ?>

        <label class="text-uppercase text-muted fw-bold mt-4 mb-2 ps-3" style="font-size: 0.65rem; letter-spacing: 1px;">Academic</label>

        <a href="schedule" class="list-group-item list-group-item-action border-0 rounded-3 py-3 px-3 mb-1 d-flex align-items-center transition-all <?php echo (basename($_SERVER['PHP_SELF']) == 'schedule.php') ? 'active-link' : 'text-secondary'; ?>">
            <i class="bi bi-calendar-check-fill me-3 fs-5"></i>
            <span class="fw-medium">Schedule</span>
        </a>

        <a href="reports" class="list-group-item list-group-item-action border-0 rounded-3 py-3 px-3 mb-1 d-flex align-items-center transition-all <?php echo (basename($_SERVER['PHP_SELF']) == 'reports.php') ? 'active-link' : 'text-secondary'; ?>">
            <i class="bi bi-bar-chart-steps me-3 fs-5"></i>
            <span class="fw-medium">Reports</span>
        </a>

        <label class="text-uppercase text-muted fw-bold mt-4 mb-2 ps-3" style="font-size: 0.65rem; letter-spacing: 1px;">System</label>

        <a href="settings" class="list-group-item list-group-item-action border-0 rounded-3 py-3 px-3 mb-1 d-flex align-items-center transition-all <?php echo (basename($_SERVER['PHP_SELF']) == 'settings.php') ? 'active-link' : 'text-secondary'; ?>">
            <i class="bi bi-gear-fill me-3 fs-5"></i>
            <span class="fw-medium">Settings</span>
        </a>

        <a href="feedback" class="list-group-item list-group-item-action border-0 rounded-3 py-3 px-3 mb-1 d-flex align-items-center transition-all <?php echo (basename($_SERVER['PHP_SELF']) == 'feedback.php') ? 'active-link' : 'text-secondary'; ?>">
            <i class="bi bi-chat-right-quote-fill me-3 fs-5"></i>
            <span class="fw-medium">Feedback</span>
        </a>
    </div>

    <!-- Sidebar Footer/User Profile -->
    <div class="position-absolute bottom-0 w-100 p-3 border-top bg-white">
        <div class="d-flex align-items-center p-2 rounded-3">
            <?php 
                $u_id = $_SESSION['user_id'];
                // Fresh fetch to get latest profile pic
                include 'database/dbconn.php';
                $u_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT profile_pic, fullname FROM users WHERE user_id = '$u_id'"));
                if($u_data['profile_pic']): ?>
                <img src="uploads/profile_pics/<?php echo $u_data['profile_pic']; ?>" class="rounded-circle me-3 border shadow-sm" width="40" height="40" style="object-fit: cover;" alt="User">
            <?php else: ?>
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($u_data['fullname']); ?>&background=0D6EFD&color=fff" class="rounded-circle me-3" width="40" alt="User">
            <?php endif; ?>
            <div class="overflow-hidden">
                <h6 class="mb-0 text-dark fw-bold text-truncate" style="font-size: 0.9rem;"><?php echo $_SESSION['fullname']; ?></h6>
                <small class="text-muted d-block text-truncate text-capitalize" style="font-size: 0.75rem;">
                    <?php 
                        if ($_SESSION['role'] == 'student' && isset($_SESSION['course'])) {
                            echo $_SESSION['course'] . ' - ' . $_SESSION['year_level'];
                        } else {
                            echo $_SESSION['role'];
                        }
                    ?>
                </small>
            </div>
            <a href="controller/AuthController.php?logout=1" class="ms-auto text-secondary" title="Logout"><i class="bi bi-box-arrow-right fs-5"></i></a>
        </div>
    </div>
</div>

<style>
    .sidebar-responsive {
        min-width: 280px;
        max-width: 280px;
        height: 100vh;
        z-index: 1000;
        transition: all 0.3s ease;
        position: fixed;
        left: 0;
        top: 0;
    }

    /* Desktop View */
    @media (min-width: 992px) {
        #wrapper {
            padding-left: 280px;
        }
        .sidebar-responsive {
            position: fixed;
        }
    }

    /* Mobile View */
    @media (max-width: 991.98px) {
        .sidebar-responsive {
            left: -280px;
            position: fixed;
        }
        .sidebar-responsive.show {
            left: 0;
            box-shadow: 0 0 20px rgba(0,0,0,0.2) !important;
        }
        #page-content-wrapper {
            min-width: 100vw;
        }
    }

    .list-group-item {
        background-color: transparent !important;
        font-size: 0.95rem;
    }
    .list-group-item:hover {
        background-color: #f8f9fa !important;
        color: #1a73e8 !important;
    }
    .active-link {
        background-color: #e8f0fe !important;
        color: #1a73e8 !important;
    }
    .active-link i {
        color: #1a73e8 !important;
    }
    .transition-all {
        transition: all 0.2s ease-in-out;
    }
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #e0e0e0;
        border-radius: 10px;
    }
</style>

<script>
    // Toggle sidebar visibility on mobile
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.querySelector('[data-bs-target="#sidebar-wrapper"]');
        const closeBtn = document.querySelector('#close-sidebar');
        const sidebar = document.querySelector('#sidebar-wrapper');
        
        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                sidebar.classList.toggle('show');
            });
        }

        if (closeBtn && sidebar) {
            closeBtn.addEventListener('click', function() {
                sidebar.classList.remove('show');
            });
        }
            
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            if (window.innerWidth < 992 && sidebar && sidebar.classList.contains('show')) {
                if (!sidebar.contains(event.target) && (!toggleBtn || !toggleBtn.contains(event.target))) {
                    sidebar.classList.remove('show');
                }
            }
        });
    });
</script>
