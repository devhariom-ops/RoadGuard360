<?php
include_once '../includes/header.php';

if (is_logged_in()) {
    header("Location: " . BASE_PATH . "auth/profile.php");
    exit();
}

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        // Query user details
        $user = db_query_first("SELECT * FROM users WHERE email = :email", ['email' => $email]);
        
        if ($user && password_verify($password, $user['password'])) {
            // Login success
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
            
            // Redirect to target URL or profile
            $redirect = $_SESSION['redirect_url'] ?? (BASE_PATH . "auth/profile.php");
            unset($_SESSION['redirect_url']);
            
            header("Location: " . $redirect);
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card glass-card p-4">
                <div class="card-body">
                    <h3 class="card-title text-center mb-4 text-white font-heading">
                        <i class="fa-solid fa-right-to-bracket text-info me-2"></i>User Login
                    </h3>
                    
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger border-0 bg-danger-subtle text-danger" role="alert">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i><?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="login.php">
                        <div class="mb-3">
                            <label for="email" class="form-label text-secondary">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-dark border-secondary text-secondary"><i class="fa-solid fa-envelope"></i></span>
                                <input type="email" class="form-control bg-dark text-white border-secondary" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required placeholder="john@example.com">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <label for="password" class="form-label text-secondary">Password</label>
                                <a href="forgot-password.php" class="text-info text-decoration-none small">Forgot Password?</a>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-dark border-secondary text-secondary"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" class="form-control bg-dark text-white border-secondary" id="password" name="password" required placeholder="Enter your password">
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-info text-dark w-100 fw-bold py-2 mb-3">
                            <i class="fa-solid fa-right-to-bracket me-2"></i>Login
                        </button>
                        
                        <div class="text-center text-secondary small">
                            Don't have an account? <a href="register.php" class="text-info text-decoration-none">Register here</a>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Credentials Assist Panel for Demo/Testing -->
            <div class="card glass-card p-3 mt-4 border-warning border-opacity-25 bg-warning-subtle text-dark">
                <h6 class="fw-bold mb-2 text-warning-emphasis"><i class="fa-solid fa-key me-2"></i>Quick Demo Credentials:</h6>
                <div class="small">
                    <div class="mb-1">
                        <strong>Admin Access:</strong> <br>
                        Email: <code class="text-dark bg-light px-1 rounded">admin@saferoads.org</code> | Password: <code class="text-dark bg-light px-1 rounded">admin123</code>
                    </div>
                    <div>
                        <strong>User Access:</strong> <br>
                        Email: <code class="text-dark bg-light px-1 rounded">jane@example.com</code> | Password: <code class="text-dark bg-light px-1 rounded">user123</code>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>
