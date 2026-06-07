<?php
include_once '../includes/header.php';
require_admin();

$success = "";
$error = "";

// Handle status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $report_id = intval($_POST['report_id'] ?? 0);
    
    if ($_POST['action'] === 'resolve') {
        $updated = db_execute("UPDATE reports SET status = 'resolved' WHERE id = :id", ['id' => $report_id]);
        if ($updated) {
            $success = "Report status updated to Resolved.";
        }
    } elseif ($_POST['action'] === 'delete') {
        // Find if report has image to delete it from disk
        $rep = db_query_first("SELECT image_path FROM reports WHERE id = :id", ['id' => $report_id]);
        if ($rep && !empty($rep['image_path'])) {
            $file_path = dirname(__DIR__) . '/uploads/' . $rep['image_path'];
            if (file_exists($file_path)) {
                @unlink($file_path);
            }
        }
        
        $deleted = db_execute("DELETE FROM reports WHERE id = :id", ['id' => $report_id]);
        if ($deleted) {
            $success = "Report successfully deleted.";
        } else {
            $error = "Failed to delete report.";
        }
    }
}

// Fetch all reports
$reports = db_query("SELECT r.*, u.name as user_name FROM reports r LEFT JOIN users u ON r.user_id = u.id ORDER BY r.created_at DESC");
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
                        <a href="manage-users.php" class="list-group-item list-group-item-action bg-transparent border-0 text-secondary hover-info">
                            <i class="fa-solid fa-users me-2"></i>Manage Users
                        </a>
                        <a href="manage-reports.php" class="list-group-item list-group-item-action bg-transparent border-0 text-info fw-bold">
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
        
        <!-- Manage Reports Panel (Right) -->
        <div class="col-lg-9">
            <h2 class="text-white font-heading mb-4"><i class="fa-solid fa-triangle-exclamation text-info me-2"></i>Manage Road Reports</h2>
            
            <?php if (!empty($success)): ?>
                <div class="alert alert-success border-0 bg-success-subtle text-success small mb-4" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i><?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger border-0 bg-danger-subtle text-danger small mb-4" role="alert">
                    <i class="fa-solid fa-circle-xmark me-2"></i><?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <?php if (empty($reports)): ?>
                <div class="card glass-card p-5 text-center text-secondary border-info border-opacity-10">
                    <i class="fa-solid fa-map-location-dot fs-1 text-muted mb-3"></i>
                    <p class="m-0">No road hazard reports filed yet.</p>
                </div>
            <?php else: ?>
                <div class="row g-3">
                    <?php foreach ($reports as $r): ?>
                        <div class="col-md-6">
                            <div class="card glass-card p-3 h-100 border-info border-opacity-10 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="text-white font-heading mb-0"><?php echo htmlspecialchars($r['title']); ?></h5>
                                        <?php if ($r['status'] === 'resolved'): ?>
                                            <span class="badge bg-success">Resolved</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="text-muted small mb-3">
                                        <span>By: <strong><?php echo htmlspecialchars($r['user_name'] ?? 'Anonymous'); ?></strong></span> | 
                                        <span>Date: <?php echo date('d M Y', strtotime($r['created_at'])); ?></span>
                                    </div>
                                    
                                    <p class="text-secondary small mb-3"><?php echo htmlspecialchars($r['description']); ?></p>
                                    
                                    <div class="bg-dark bg-opacity-40 p-2 rounded mb-3 small text-muted border border-secondary border-opacity-20">
                                        <i class="fa-solid fa-location-dot text-info me-1"></i>Coordinates: <strong><?php echo $r['latitude']; ?>, <?php echo $r['longitude']; ?></strong>
                                    </div>
                                    
                                    <?php if (!empty($r['image_path'])): ?>
                                        <div class="mb-3">
                                            <a href="../uploads/<?php echo $r['image_path']; ?>" target="_blank" class="btn btn-outline-secondary btn-sm w-100 text-info border-info border-opacity-20">
                                                <i class="fa-solid fa-image me-1"></i> View Evidence Image
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="d-flex gap-2 pt-2 border-top border-secondary border-opacity-30">
                                    <?php if ($r['status'] === 'pending'): ?>
                                        <form method="POST" action="manage-reports.php" class="flex-grow-1">
                                            <input type="hidden" name="action" value="resolve">
                                            <input type="hidden" name="report_id" value="<?php echo $r['id']; ?>">
                                            <button type="submit" class="btn btn-success btn-sm w-100 text-white font-heading fw-bold">
                                                <i class="fa-solid fa-check"></i> Resolve
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    
                                    <form method="POST" action="manage-reports.php" class="flex-grow-1" onsubmit="return confirm('Are you sure you want to delete this report? This will delete any uploaded image file.');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="report_id" value="<?php echo $r['id']; ?>">
                                        <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                            <i class="fa-solid fa-trash-can"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>
