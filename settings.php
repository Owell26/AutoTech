<?php include 'includes/header.php'; ?>
<?php include 'database/dbconn.php'; ?>

<?php
$userId = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Fetch user current data
$user_sql = "SELECT * FROM users WHERE user_id = '$userId'";
$user = mysqli_fetch_assoc(mysqli_query($conn, $user_sql));
?>

<div class="d-flex" id="wrapper">
    <?php include 'includes/sidebar.php'; ?>

    <div id="page-content-wrapper" class="w-100">
        <nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom">
            <div class="container-fluid">
                <button class="btn btn-outline-secondary d-lg-none me-2" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-wrapper">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a class="navbar-brand" href="dashboard.php">AutoTech Settings</a>
            </div>
        </nav>

        <div class="container-fluid p-4" style="background-color: #f8f9fa; min-height: 100vh;">
            
            <div class="mb-4">
                <h2 class="fw-bold text-dark mb-1">Account Settings</h2>
                <p class="text-muted">Manage your profile, security, and preferences.</p>
            </div>

            <?php if(isset($_GET['status'])): ?>
                <div class="alert alert-<?php echo $_GET['status'] == 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
                    <i class="bi <?php echo $_GET['status'] == 'success' ? 'bi-check-circle' : 'bi-exclamation-triangle'; ?> me-2"></i>
                    <?php echo isset($_GET['msg']) ? $_GET['msg'] : ''; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row g-4">
                <!-- Navigation Tabs -->
                <div class="col-lg-3">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="list-group list-group-flush" id="settings-tabs" role="tablist">
                            <a class="list-group-item list-group-item-action py-3 px-4 active border-0" id="profile-tab-link" data-bs-toggle="pill" href="#profile" role="tab" aria-controls="profile" aria-selected="true">
                                <i class="bi bi-person-circle me-2"></i> My Profile
                            </a>
                            <a class="list-group-item list-group-item-action py-3 px-4 border-0" id="security-tab-link" data-bs-toggle="pill" href="#security" role="tab" aria-controls="security" aria-selected="false">
                                <i class="bi bi-shield-lock me-2"></i> Security
                            </a>
                            <a class="list-group-item list-group-item-action py-3 px-4 border-0" id="preferences-tab-link" data-bs-toggle="pill" href="#preferences" role="tab" aria-controls="preferences" aria-selected="false">
                                <i class="bi bi-sliders me-2"></i> Preferences
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Settings Content -->
                <div class="col-lg-9">
                    <div class="tab-content" id="settings-tabContent">
                        
                        <!-- Profile Tab -->
                        <div class="tab-pane fade show active" id="profile" role="tabpanel">
                            <div class="card border-0 shadow-sm rounded-4 p-2">
                                <div class="card-body p-4">
                                    <h5 class="fw-bold mb-4">Personal Information</h5>
                                    <div class="d-flex align-items-center mb-5">
                                        <div class="position-relative me-4">
                                            <?php if($user['profile_pic']): ?>
                                                <img src="uploads/profile_pics/<?php echo $user['profile_pic']; ?>" class="rounded-circle shadow-sm border" width="100" height="100" style="object-fit: cover;" id="profile-preview">
                                            <?php else: ?>
                                                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($user['fullname']); ?>&background=0D6EFD&color=fff" class="rounded-circle shadow-sm" width="100" id="profile-preview">
                                            <?php endif; ?>
                                            <div class="position-absolute bottom-0 end-0">
                                                <button class="btn btn-sm btn-primary rounded-circle border-4 border-white shadow-sm" onclick="document.getElementById('photoInput').click()"><i class="bi bi-camera-fill"></i></button>
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">Profile Photo</h6>
                                            <p class="small text-muted mb-3">JPG, PNG allowed. Max size 2MB.</p>
                                            <form action="controller/SettingsController.php" method="POST" enctype="multipart/form-data">
                                                <input type="file" name="profile_pic" id="photoInput" class="d-none" accept="image/*" onchange="previewImage(this)">
                                                <button type="submit" name="updatePhoto" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-medium">Save New Photo</button>
                                            </form>
                                        </div>
                                    </div>
                                    <form action="controller/SettingsController.php" method="POST">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold text-muted text-uppercase">Full Name</label>
                                                <input type="text" class="form-control bg-light border-0 py-2 fs-6 opacity-75" value="<?php echo $user['fullname']; ?>" style="border-radius: 10px;" readonly>
                                                <small class="text-muted" style="font-size: 0.65rem;">Contact administrator to change name.</small>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold text-muted text-uppercase">Email Address</label>
                                                <input type="email" class="form-control bg-light border-0 py-2 fs-6 opacity-75" value="<?php echo $user['email']; ?>" style="border-radius: 10px;" readonly>
                                                <small class="text-muted" style="font-size: 0.65rem;">Email address is linked to your account.</small>
                                            </div>
                                            
                                            <?php if($role == 'student'): ?>
                                                <div class="col-md-4">
                                                    <label class="form-label small fw-bold text-muted text-uppercase">Course</label>
                                                    <input type="text" class="form-control bg-light border-0 py-2 fs-6 opacity-75" value="<?php echo $user['course']; ?>" style="border-radius: 10px;" readonly>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small fw-bold text-muted text-uppercase">Year Level</label>
                                                    <input type="text" class="form-control bg-light border-0 py-2 fs-6 opacity-75" value="<?php echo $user['year_level']; ?>" style="border-radius: 10px;" readonly>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small fw-bold text-muted text-uppercase">Section</label>
                                                    <input type="text" class="form-control bg-light border-0 py-2 fs-6 opacity-75" value="<?php echo $user['section']; ?>" style="border-radius: 10px;" readonly>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                                            <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm fw-medium disabled" title="Photo upload coming soon">Update Photo</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Security Tab -->
                        <div class="tab-pane fade" id="security" role="tabpanel">
                            <div class="card border-0 shadow-sm rounded-4 p-2">
                                <div class="card-body p-4">
                                    <h5 class="fw-bold mb-4">Password & Security</h5>
                                    <form action="controller/SettingsController.php" method="POST">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label small fw-bold text-muted text-uppercase">Current Password</label>
                                                <input type="password" name="current_password" class="form-control bg-light border-0 py-2 fs-6" placeholder="Enter current password" style="border-radius: 10px;" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold text-muted text-uppercase">New Password</label>
                                                <input type="password" name="new_password" class="form-control bg-light border-0 py-2 fs-6" placeholder="At least 6 characters" style="border-radius: 10px;" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold text-muted text-uppercase">Confirm New Password</label>
                                                <input type="password" name="confirm_password" class="form-control bg-light border-0 py-2 fs-6" placeholder="Repeat new password" style="border-radius: 10px;" required>
                                            </div>
                                        </div>
                                        <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                                            <button type="submit" name="updatePassword" class="btn btn-primary rounded-pill px-4 shadow-sm fw-medium">Update Password</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Preferences Tab -->
                        <div class="tab-pane fade" id="preferences" role="tabpanel">
                            <div class="card border-0 shadow-sm rounded-4 p-2">
                                <div class="card-body p-4">
                                    <h5 class="fw-bold mb-4">System Preferences</h5>
                                    <div class="mb-4">
                                        <h6 class="fw-bold small text-muted text-uppercase mb-3">Email Notifications</h6>
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" checked id="notifAssignments">
                                            <label class="form-check-label small" for="notifAssignments">New assignments and materials</label>
                                        </div>
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" checked id="notifDiscussions">
                                            <label class="form-check-label small" for="notifDiscussions">Discussion replies</label>
                                        </div>
                                    </div>
                                    <div class="border-top pt-4">
                                        <h6 class="fw-bold small text-muted text-uppercase mb-3">Appearance</h6>
                                        <div class="d-flex gap-3">
                                            <div class="p-3 border rounded-3 text-center bg-light" style="width: 100px; cursor: pointer;">
                                                <i class="bi bi-sun fs-4 d-block mb-1"></i>
                                                <span class="small fw-bold">Light</span>
                                            </div>
                                            <div class="p-3 border rounded-3 text-center opacity-50" style="width: 100px; cursor: pointer;">
                                                <i class="bi bi-moon-stars fs-4 d-block mb-1"></i>
                                                <span class="small fw-bold">Dark</span>
                                            </div>
                                        </div>
                                        <small class="text-muted d-block mt-2">Dark mode is coming soon.</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profile-preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Handle tab persistence through URL hash
    document.addEventListener('DOMContentLoaded', function() {
        function showTabFromHash() {
            let hash = window.location.hash;
            if (hash) {
                let triggerEl = document.querySelector('#settings-tabs a[href="' + hash + '"]');
                if (triggerEl) {
                    let tab = new bootstrap.Tab(triggerEl);
                    tab.show();
                }
            }
        }

        showTabFromHash();

        document.querySelectorAll('#settings-tabs a').forEach(function(el) {
            el.addEventListener('shown.bs.tab', function(e) {
                window.location.hash = this.getAttribute('href');
            });
        });

        window.addEventListener('hashchange', showTabFromHash);
    });
</script>

<style>
    #settings-tabs .list-group-item.active {
        background-color: #e8f0fe !important;
        color: #0d6efd !important;
        font-weight: 600;
        border-left: 4px solid #0d6efd !important;
    }
    #settings-tabs .list-group-item {
        color: #6c757d;
        transition: all 0.2s ease;
    }
    #settings-tabs .list-group-item:hover:not(.active) {
        background-color: #f8f9fa;
        color: #0d6efd;
    }
</style>

<?php include 'includes/footer.php'; ?>
