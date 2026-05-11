<?php include 'includes/header.php'; ?>
<div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh; background-color: #f8f9fa;">
    <div class="card border-0 shadow-lg" style="width: 100%; max-width: 400px; border-radius: 15px;">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <i class="bi bi-cpu-fill text-white fs-2"></i>
                </div>
                <h3 class="fw-bold">AutoTech</h3>
                <p class="text-muted">Sign in to your account</p>
            </div>

            <?php include 'includes/alerts.php'; ?>

            <form action="controller/AuthController.php" method="POST">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted text-uppercase">Email Address</label>
                    <input type="email" name="email" class="form-control form-control-lg bg-light border-0 fs-6 shadow-none" placeholder="name@example.com" required style="border-radius: 10px;">
                </div>
                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted text-uppercase">Password</label>
                    <input type="password" name="password" class="form-control form-control-lg bg-light border-0 fs-6 shadow-none" placeholder="Enter password" required style="border-radius: 10px;">
                </div>
                <div class="mb-4 form-check">
                    <input type="checkbox" name="remember_me" class="form-check-input" id="rememberMe">
                    <label class="form-check-label small text-muted" for="rememberMe">Remember me on this device</label>
                </div>
                <button type="submit" name="loginUser" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm mb-3" style="border-radius: 10px;">Login</button>
                <div class="text-center">
                    <p class="small text-muted mb-0">Don't have an account? <a href="register.php" class="text-primary text-decoration-none fw-bold">Register</a></p>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
