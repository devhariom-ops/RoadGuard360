<?php
include_once '../includes/header.php';
require_admin();

$success = "";
$error = "";

// Handle user deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_user') {
    $delete_id = intval($_POST['user_id'] ?? 0);
    
    // Safety checks: Cannot delete self or final admin
    if ($delete_id === intval($_SESSION['user_id'])) {
        $error = "You cannot delete your own admin account.";
    } else {
        // Find if target is admin
        $target = db_query_first("SELECT role FROM users WHERE id = :id", ['id' => $delete_id]);
        if ($target && $target['role'] === 'admin') {
            $error = "For security reasons, other Administrators cannot be deleted.";
        } else {
            $deleted = db_execute("DELETE FROM users WHERE id = :id", ['id' => $delete_id]);
            if ($deleted) {
                $success = "User account successfully deleted.";
            } else {
                $error = "Failed to delete user account. Try again.";
            }
        }
    }
}

// Fetch all users
$all_users = db_query("SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC");
?>

<div class="container py-4">
    <div class="row">
        <!-- Sidebar Navigation (Left) -->
        <div class="col-lg-3 mb-4">
            <div class="card glass-card p-3 border-danger border-opacity-10 h-100">
                <div class="card-body">
                    <h5 class="text-white font-heading mb-4"><i class="fa-solid fa-lock text-danger me-2"></i>Admin Panel</h5>
                    <div class="list-group list-group-flush gap-2">
                        <a href="dashboard.php" class="list-group-item list-group-item-action bg-transparent border-0 text-secondary hover-info">
                            <i class="fa-solid fa-gauge me-2"></i>Overview
                        </a>
                        <a href="manage-users.php" class="list-group-item list-group-item-action bg-transparent border-0 text-info fw-bold">
                            <i class="fa-solid fa-users me-2"></i>Manage Users
                        </a>
                        <a href="manage-reports.php" class="list-group-item list-group-item-action bg-transparent border-0 text-secondary hover-info">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i>Manage Reports
                        </a>
                        <a href="manage-blogs.php" class="list-group-item list-group-item-action bg-transparent border-0 text-secondary hover-info">
                            <i class="fa-solid fa-newspaper me-2"></i>Manage Blogs
                        </a>
                        <a href="manage-quiz.php" class="list-group-item list-group-item-action bg-transparent border-0 text-secondary hover-info">
                            <i class="fa-solid fa-graduation-cap me-2"></i>Manage Quiz
                        </a>
                        <a href="manage-videos.php" class="list-group-item list-group-item-action bg-transparent border-0 text-secondary hover-info">
                            <i class="fa-solid fa-play-circle me-2"></i>Manage Videos
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Manage Users Panel (Right) -->
        <div class="col-lg-9">
            <h2 class="text-white font-heading mb-4"><i class="fa-solid fa-users text-info me-2"></i>Manage User Accounts</h2>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger border-0 bg-danger-subtle text-danger small mb-4" role="alert">
                    <i class="fa-solid fa-circle-xmark me-2"></i><?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="alert alert-success border-0 bg-success-subtle text-success small mb-4" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i><?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>
            
            <div class="card glass-card p-4 border-info border-opacity-10">
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle custom-table m-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Registration Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($all_users as $u): ?>
                                <tr>
                                    <td class="fw-bold text-info">#<?php echo $u['id']; ?></td>
                                    <td class="text-white font-heading"><?php echo htmlspecialchars($u['name']); ?></td>
                                    <td><?php echo htmlspecialchars($u['email']); ?></td>
                                    <td>
                                        <?php if ($u['role'] === 'admin'): ?>
                                            <span class="badge bg-danger">Admin</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">User</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="small text-muted"><?php echo date('d M Y, h:i A', strtotime($u['created_at'])); ?></td>
                                    <td>
                                        <?php if ($u['role'] !== 'admin'): ?>
                                            <form method="POST" action="manage-users.php" onsubmit="return confirm('Are you sure you want to delete this user? This will erase their quiz attempts and certificates.');">
                                                <input type="hidden" name="action" value="delete_user">
                                                <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fa-solid fa-trash-can"></i> Delete
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <span class="text-muted small">Protected</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>
