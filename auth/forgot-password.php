<?php
include_once '../includes/header.php';

$error = "";
$success = "";
$step = 1;
$email = "";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $email = trim($_POST['email'] ?? '');
    
    if ($action === 'verify_email') {
        if (empty($email)) {
            $error = "Please enter your email address.";
        } else {
            $user = db_query_first("SELECT * FROM users WHERE email = :email", ['email' => $email]);
            if ($user) {
                $_SESSION['reset_email'] = $email;
                $step = 2; // Move to security question
            } else {
                $error = "Email address not found.";
            }
        }
    } elseif ($action === 'verify_security') {
        $email = $_SESSION['reset_email'] ?? '';
        $answer = trim($_POST['security_answer'] ?? '');
        
        if (empty($email)) {
            $error = "Session expired. Please start over.";
            $step = 1;
        } elseif (strtolower($answer) !== 'responsibly') {
            $error = "Incorrect answer to security challenge. Hint: Look at the platform tagline on the homepage.";
            $step = 2;
        } else {
            $step = 3; // Move to password reset
        }
    } elseif ($action === 'reset_password') {
        $email = $_SESSION['reset_email'] ?? '';
        $new_pass = $_POST['new_password'] ?? '';
        $conf_pass = $_POST['confirm_new_password'] ?? '';
        
        if (empty($email)) {
            $error = "Session expired. Please start over.";
            $step = 1;
        } elseif (strlen($new_pass) < 6) {
            $error = "Password must be at least 6 characters long.";
            $step = 3;
        } elseif ($new_pass !== $conf_pass) {
            $error = "Passwords do not match.";
            $step = 3;
        } else {
            // Update in DB
            $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
            
            // Check if live or mock
            if (!$offline_mode && $pdo !== null) {
                $updated = db_execute("UPDATE users SET password = :password WHERE email = :email", [
                    'password' => $hashed,
                    'email' => $email
                ]);
            } else {
                // Mock update
                $updated = false;
                foreach ($_SESSION['mock_users'] as &$u) {
                    if (strtolower($u['email']) === strtolower($email)) {
                        $u['password'] = $hashed;
                        $updated = true;
                        break;
                    }
                }
            }
            
            if ($updated) {
                $success = "Password successfully reset! You can now log in.";
                unset($_SESSION['reset_email']);
                $step = 4; // Complete
            } else {
                $error = "Failed to update password. Please try again.";
                $step = 3;
            }
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
                        <i class="fa-solid fa-key text-info me-2"></i>Reset Password
                    </h3>
                    
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger border-0 bg-danger-subtle text-danger" role="alert">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i><?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success border-0 bg-success-subtle text-success" role="alert">
                            <i class="fa-solid fa-circle-check me-2"></i><?php echo htmlspecialchars($success); ?>
                            <div class="mt-2">
                                <a href="login.php" class="btn btn-sm btn-success text-white">Go to Login</a>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($step === 1): ?>
                        <!-- Step 1: Request Email -->
                        <form method="POST" action="forgot-password.php">
                            <input type="hidden" name="action" value="verify_email">
                            <div class="mb-4">
                                <label for="email" class="form-label text-secondary">Registered Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark border-secondary text-secondary"><i class="fa-solid fa-envelope"></i></span>
                                    <input type="email" class="form-control bg-dark text-white border-secondary" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required placeholder="john@example.com">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-info text-dark w-100 fw-bold py-2 mb-3">
                                Continue <i class="fa-solid fa-arrow-right ms-2"></i>
                            </button>
                        </form>
                        
                    <?php elseif ($step === 2): ?>
                        <!-- Step 2: Security Question -->
                        <form method="POST" action="forgot-password.php">
                            <input type="hidden" name="action" value="verify_security">
                            <p class="text-secondary small mb-3">Please verify your identity by answering our standard safety challenge question:</p>
                            
                            <div class="mb-4">
                                <label for="security_answer" class="form-label text-white fw-semibold">What is the official tagline of SafeRoads?</label>
                                <p class="text-muted small mb-2"><em>Hint: Riding __________ Saves Lives. (Single word)</em></p>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark border-secondary text-secondary"><i class="fa-solid fa-circle-question"></i></span>
                                    <input type="text" class="form-control bg-dark text-white border-secondary" id="security_answer" name="security_answer" required placeholder="Type answer here">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-info text-dark w-100 fw-bold py-2 mb-3">
                                Verify Identity <i class="fa-solid fa-arrow-right ms-2"></i>
                            </button>
                        </form>
                        
                    <?php elseif ($step === 3): ?>
                        <!-- Step 3: New Password Form -->
                        <form method="POST" action="forgot-password.php">
                            <input type="hidden" name="action" value="reset_password">
                            <div class="mb-3">
                                <label for="new_password" class="form-label text-secondary">New Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark border-secondary text-secondary"><i class="fa-solid fa-lock"></i></span>
                                    <input type="password" class="form-control bg-dark text-white border-secondary" id="new_password" name="new_password" required placeholder="Minimum 6 characters">
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="confirm_new_password" class="form-label text-secondary">Confirm New Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark border-secondary text-secondary"><i class="fa-solid fa-shield-halved"></i></span>
                                    <input type="password" class="form-control bg-dark text-white border-secondary" id="confirm_new_password" name="confirm_new_password" required placeholder="Re-type new password">
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-info text-dark w-100 fw-bold py-2 mb-3">
                                Reset Password <i class="fa-solid fa-check ms-2"></i>
                            </button>
                        </form>
                    <?php endif; ?>
                    
                    <div class="text-center text-secondary small">
                        Remembered your password? <a href="login.php" class="text-info text-decoration-none">Back to Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>
