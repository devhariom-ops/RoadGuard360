<?php
include_once '../includes/header.php';
require_admin(); // Restrict to admins only

// Query stats counts
$total_users = count(db_query("SELECT id FROM users"));
$total_reports = count(db_query("SELECT id FROM reports"));
$total_attempts = count(db_query("SELECT id FROM quiz_attempts"));
$total_feedbacks = count(db_query("SELECT id FROM feedbacks"));

// Fetch recent hazard reports
$recent_reports = db_query("SELECT * FROM reports ORDER BY created_at DESC LIMIT 5");

// Fetch recent feedbacks
$recent_feedbacks = db_query("SELECT * FROM feedbacks ORDER BY created_at DESC LIMIT 5");

// Handle inline Resolve report action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'resolve_report') {
    $report_id = intval($_POST['report_id'] ?? 0);
    if ($report_id > 0) {
        $updated = db_execute("UPDATE reports SET status = 'resolved' WHERE id = :id", ['id' => $report_id]);
        if ($updated) {
            // Refresh counts
            header("Location: dashboard.php?notice=resolved");
            exit();
        }
    }
}
?>

<div class="container py-4">
    <div class="row">
        <!-- Sidebar Navigation (Left) -->
        <div class="col-lg-3 mb-4">
            <div class="card glass-card p-3 border-danger border-opacity-10 h-100">
                <div class="card-body">
                    <h5 class="text-white font-heading mb-4"><i class="fa-solid fa-lock text-danger me-2"></i>Admin Panel</h5>
                    <div class="list-group list-group-flush gap-2">
                        <a href="dashboard.php" class="list-group-item list-group-item-action bg-transparent border-0 text-info fw-bold">
                            <i class="fa-solid fa-gauge me-2"></i>Overview
                        </a>
                        <a href="manage-users.php" class="list-group-item list-group-item-action bg-transparent border-0 text-secondary hover-info">
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
        
        <!-- Dashboard Content (Right) -->
        <div class="col-lg-9">
            <h2 class="text-white font-heading mb-4">Dashboard Overview</h2>
            
            <?php if (isset($_GET['notice']) && $_GET['notice'] === 'resolved'): ?>
                <div class="alert alert-success border-0 bg-success-subtle text-success small mb-4" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i>Hazard status successfully marked as Resolved.
                </div>
            <?php endif; ?>
            
            <!-- Statistics Cards -->
            <div class="row g-3 mb-5">
                <div class="col-md-3">
                    <div class="card glass-card p-3 text-center border-info border-opacity-10">
                        <div class="fs-3 text-info"><i class="fa-solid fa-users"></i></div>
                        <h4 class="text-white font-heading mt-2 mb-0"><?php echo $total_users; ?></h4>
                        <small class="text-muted text-uppercase">Total Users</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card glass-card p-3 text-center border-warning border-opacity-10">
                        <div class="fs-3 text-warning"><i class="fa-solid fa-map-location-dot"></i></div>
                        <h4 class="text-white font-heading mt-2 mb-0"><?php echo $total_reports; ?></h4>
                        <small class="text-muted text-uppercase">Hazard Reports</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card glass-card p-3 text-center border-purple border-opacity-10">
                        <div class="fs-3 text-purple"><i class="fa-solid fa-graduation-cap"></i></div>
                        <h4 class="text-white font-heading mt-2 mb-0"><?php echo $total_attempts; ?></h4>
                        <small class="text-muted text-uppercase">Quiz Attempts</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card glass-card p-3 text-center border-success border-opacity-10">
                        <div class="fs-3 text-success"><i class="fa-solid fa-comments"></i></div>
                        <h4 class="text-white font-heading mt-2 mb-0"><?php echo $total_feedbacks; ?></h4>
                        <small class="text-muted text-uppercase">Feedbacks</small>
                    </div>
                </div>
            </div>
            
            <!-- Recent Reports Queue -->
            <div class="card glass-card p-4 border-info border-opacity-10 mb-4">
                <h5 class="text-white font-heading mb-3"><i class="fa-solid fa-triangle-exclamation text-warning me-2"></i>Pending Hazard Reports Queue</h5>
                
                <?php if (empty($recent_reports)): ?>
                    <p class="text-secondary small m-0 text-center py-3">No hazard reports filed.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-dark table-hover align-middle custom-table small">
                            <thead>
                                <tr>
                                    <th>Report Title</th>
                                    <th>Coords</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_reports as $rep): ?>
                                    <tr>
                                        <td class="text-white font-heading fw-bold"><?php echo htmlspecialchars($rep['title']); ?></td>
                                        <td class="small"><?php echo round($rep['latitude'], 4); ?>, <?php echo round($rep['longitude'], 4); ?></td>
                                        <td>
                                            <?php if ($rep['status'] === 'resolved'): ?>
                                                <span class="badge bg-success-subtle text-success border border-success border-opacity-20">Resolved</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning-subtle text-warning border border-warning border-opacity-20">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="small text-muted"><?php echo date('d M Y', strtotime($rep['created_at'])); ?></td>
                                        <td>
                                            <?php if ($rep['status'] === 'pending'): ?>
                                                <form method="POST" action="dashboard.php">
                                                    <input type="hidden" name="action" value="resolve_report">
                                                    <input type="hidden" name="report_id" value="<?php echo $rep['id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-success">
                                                        <i class="fa-solid fa-check"></i> Resolve
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <span class="text-muted small">No Action</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- User Feedbacks Panel -->
            <div class="card glass-card p-4 border-info border-opacity-10">
                <h5 class="text-white font-heading mb-3"><i class="fa-solid fa-comments text-info me-2"></i>Recent Inquiries & Feedbacks</h5>
                
                <?php if (empty($recent_feedbacks)): ?>
                    <p class="text-secondary small m-0 text-center py-3">No user feedback messages recorded.</p>
                <?php else: ?>
                    <div class="row g-3">
                        <?php foreach ($recent_feedbacks as $f): ?>
                            <div class="col-12 p-3 bg-dark bg-opacity-40 border border-secondary border-opacity-25 rounded">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <strong class="text-info small"><?php echo htmlspecialchars($f['name']); ?> <span class="text-muted">(&lt;<?php echo htmlspecialchars($f['email']); ?>&gt;)</span></strong>
                                    <small class="text-muted" style="font-size: 0.75rem;"><?php echo date('d M Y, h:i A', strtotime($f['created_at'])); ?></small>
                                </div>
                                <h6 class="text-white small fw-bold mb-2">Subject: <?php echo htmlspecialchars($f['subject']); ?></h6>
                                <p class="text-secondary small m-0">"<?php echo htmlspecialchars($f['message']); ?>"</p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            
        </div>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>
