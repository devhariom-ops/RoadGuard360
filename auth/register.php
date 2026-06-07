<?php
include_once '../includes/header.php';

if (is_logged_in()) {
    header("Location: " . BASE_PATH . "auth/profile.php");
    exit();
}

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (empty($name) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if email already exists
        $existing = db_query_first("SELECT id FROM users WHERE email = :email", ['email' => $email]);
        
        if ($existing) {
            $error = "Email is already registered.";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert user
            $inserted = db_execute(
                "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, 'user')",
                [
                    'name' => $name,
                    'email' => $email,
                    'password' => $hashed_password
                ]
            );
            
            if ($inserted) {
                $success = "Registration successful! You can now login.";
                // Clear inputs
                $name = $email = "";
            } else {
                $error = "An error occurred during registration. Please try again.";
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
                        <i class="fa-solid fa-user-plus text-info me-2"></i>Create Account
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
                    
                    <form method="POST" action="register.php" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="name" class="form-label text-secondary">Full Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-dark border-secondary text-secondary"><i class="fa-solid fa-user"></i></span>
                                <input type="text" class="form-control bg-dark text-white border-secondary" id="name" name="name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required placeholder="John Doe">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label text-secondary">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-dark border-secondary text-secondary"><i class="fa-solid fa-envelope"></i></span>
                                <input type="email" class="form-control bg-dark text-white border-secondary" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required placeholder="john@example.com">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label text-secondary">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-dark border-secondary text-secondary"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" class="form-control bg-dark text-white border-secondary" id="password" name="password" required placeholder="Minimum 6 characters">
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="confirm_password" class="form-label text-secondary">Confirm Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-dark border-secondary text-secondary"><i class="fa-solid fa-shield-halved"></i></span>
                                <input type="password" class="form-control bg-dark text-white border-secondary" id="confirm_password" name="confirm_password" required placeholder="Re-type password">
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-info text-dark w-100 fw-bold py-2 mb-3">
                            <i class="fa-solid fa-user-plus me-2"></i>Register
                        </button>
                        
                        <div class="text-center text-secondary small">
                            Already have an account? <a href="login.php" class="text-info text-decoration-none">Login here</a>
                        </div>
                    </form>
                </div>
            </div>
            
            <?php if ($offline_mode): ?>
                <div class="alert alert-warning border-0 bg-warning-subtle text-dark mt-3 text-center small" role="alert">
                    <i class="fa-solid fa-circle-info me-1"></i> Running in Offline Mock Mode. Users will be saved in your session.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>
