<?php include 'includes/header.php'; ?>
<div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh; background-color: #f8f9fa;">
    <div class="card border-0 shadow-lg my-5" style="width: 100%; max-width: 450px; border-radius: 15px;">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <i class="bi bi-person-plus-fill text-white fs-2"></i>
                </div>
                <h3 class="fw-bold">Create Account</h3>
                <p class="text-muted">Join the AutoTech community</p>
            </div>

            <?php include 'includes/alerts.php'; ?>

            <form action="controller/AuthController.php" method="POST">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted text-uppercase">Full Name</label>
                    <input type="text" name="fullname" class="form-control form-control-lg bg-light border-0 fs-6 shadow-none" placeholder="John Doe" required style="border-radius: 10px;">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted text-uppercase">Email Address</label>
                    <input type="email" name="email" class="form-control form-control-lg bg-light border-0 fs-6 shadow-none" placeholder="name@example.com" required style="border-radius: 10px;">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted text-uppercase">Role</label>
                    <select name="role" id="roleSelect" class="form-select form-select-lg bg-light border-0 fs-6 shadow-none" required style="border-radius: 10px;">
                        <option value="" selected disabled>Select Role</option>
                        <option value="instructor">Instructor</option>
                        <option value="student">Student</option>
                    </select>
                </div>

                <!-- Student Specific Fields -->
                <div id="studentFields" style="display: none;">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Course</label>
                        <input type="text" name="course" class="form-control form-control-lg bg-light border-0 fs-6 shadow-none" placeholder="e.g., BS Automotive Technology" style="border-radius: 10px;">
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Year Level</label>
                            <select name="year_level" class="form-select form-select-lg bg-light border-0 fs-6 shadow-none" style="border-radius: 10px;">
                                <option value="" selected disabled>Select Year</option>
                                <option value="1st Year">1st Year</option>
                                <option value="2nd Year">2nd Year</option>
                                <option value="3rd Year">3rd Year</option>
                                <option value="4th Year">4th Year</option>
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Section</label>
                            <input type="text" name="section" class="form-control form-control-lg bg-light border-0 fs-6 shadow-none" placeholder="e.g., A" style="border-radius: 10px;">
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted text-uppercase">Password</label>
                    <input type="password" name="password" class="form-control form-control-lg bg-light border-0 fs-6 shadow-none" placeholder="Create password" required style="border-radius: 10px;">
                </div>

                <script>
                    document.getElementById('roleSelect').addEventListener('change', function() {
                        const studentFields = document.getElementById('studentFields');
                        if (this.value === 'student') {
                            studentFields.style.display = 'block';
                            studentFields.querySelectorAll('input, select').forEach(el => el.required = true);
                        } else {
                            studentFields.style.display = 'none';
                            studentFields.querySelectorAll('input, select').forEach(el => el.required = false);
                        }
                    });
                </script>
                <button type="submit" name="registerUser" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm mb-3" style="border-radius: 10px;">Register</button>
                <div class="text-center">
                    <p class="small text-muted mb-0">Already have an account? <a href="login" class="text-primary text-decoration-none fw-bold">Login</a></p>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
