<?php
include_once '../includes/header.php';
require_admin();

$success = "";
$error = "";

// Handle Form Submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add') {
        $title = trim($_POST['title'] ?? '');
        $youtube_id = trim($_POST['youtube_id'] ?? '');
        $category = $_POST['category'] ?? 'Traffic Rules';
        $description = trim($_POST['description'] ?? '');
        
        if (empty($title) || empty($youtube_id) || empty($description)) {
            $error = "All fields are required.";
        } else {
            // Save video
            $inserted = db_execute(
                "INSERT INTO videos (title, youtube_id, category, description) VALUES (:title, :youtube_id, :category, :description)",
                [
                    'title' => $title,
                    'youtube_id' => $youtube_id,
                    'category' => $category,
                    'description' => $description
                ]
            );
            
            if ($inserted) {
                $success = "Video successfully added to library!";
            } else {
                $error = "Failed to add video.";
            }
        }
    } elseif ($action === 'delete') {
        $v_id = intval($_POST['video_id'] ?? 0);
        if ($v_id > 0) {
            $deleted = db_execute("DELETE FROM videos WHERE id = :id", ['id' => $v_id]);
            if ($deleted) {
                $success = "Video successfully deleted.";
            } else {
                $error = "Failed to delete video.";
            }
        }
    }
}

// Fetch all videos
$videos = db_query("SELECT * FROM videos ORDER BY created_at DESC");
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
                        <a href="manage-reports.php" class="list-group-item list-group-item-action bg-transparent border-0 text-secondary hover-info">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i>Manage Reports
                        </a>
                        <a href="manage-blogs.php" class="list-group-item list-group-item-action bg-transparent border-0 text-secondary hover-info">
                            <i class="fa-solid fa-newspaper me-2"></i>Manage Blogs
                        </a>
                        <a href="manage-quiz.php" class="list-group-item list-group-item-action bg-transparent border-0 text-secondary hover-info">
                            <i class="fa-solid fa-graduation-cap me-2"></i>Manage Quiz
                        </a>
                        <a href="manage-videos.php" class="list-group-item list-group-item-action bg-transparent border-0 text-info fw-bold">
                            <i class="fa-solid fa-play-circle me-2"></i>Manage Videos
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Manage Videos Content (Right) -->
        <div class="col-lg-9">
            <h2 class="text-white font-heading mb-4"><i class="fa-solid fa-play-circle text-info me-2"></i>Manage Educational Videos</h2>
            
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
            
            <div class="row g-4">
                <!-- Add Video Form (Left) -->
                <div class="col-md-5">
                    <div class="card glass-card p-4 border-info border-opacity-10">
                        <h5 class="text-white font-heading mb-3"><i class="fa-solid fa-square-plus text-success me-2"></i>Add Video</h5>
                        
                        <form method="POST" action="manage-videos.php">
                            <input type="hidden" name="action" value="add">
                            
                            <div class="mb-3">
                                <label for="title" class="form-label text-secondary">Video Title</label>
                                <input type="text" class="form-control bg-dark text-white border-secondary" id="title" name="title" required placeholder="e.g. Defensive Driving Tips">
                            </div>
                            
                            <div class="mb-3">
                                <label for="youtube_id" class="form-label text-secondary">YouTube Video ID</label>
                                <input type="text" class="form-control bg-dark text-white border-secondary" id="youtube_id" name="youtube_id" required placeholder="e.g. 8i6Xp4k2c3M">
                                <small class="text-muted">Extract from URL, e.g. youtube.com/watch?v=<b>8i6Xp4k2c3M</b></small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="category" class="form-label text-secondary">Category</label>
                                <select class="form-select bg-dark text-white border-secondary" id="category" name="category">
                                    <option value="Traffic Rules">Traffic Rules</option>
                                    <option value="Accident Prevention">Accident Prevention</option>
                                    <option value="Emergency Response">Emergency Response</option>
                                    <option value="Safe Driving Tips">Safe Driving Tips</option>
                                </select>
                            </div>
                            
                            <div class="mb-4">
                                <label for="description" class="form-label text-secondary">Video Description</label>
                                <textarea class="form-control bg-dark text-white border-secondary small" id="description" name="description" rows="3" required placeholder="Provide detail summary of instructions..."></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-info text-dark fw-bold w-100 py-2">
                                <i class="fa-solid fa-plus me-1"></i>Add Video
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Videos List (Right) -->
                <div class="col-md-7">
                    <div class="card glass-card p-4 border-info border-opacity-10 h-100">
                        <h5 class="text-white font-heading mb-3">Loaded Video Lectures</h5>
                        
                        <?php if (empty($videos)): ?>
                            <p class="text-secondary small text-center py-4">No videos logged in library.</p>
                        <?php else: ?>
                            <div class="row g-3">
                                <?php foreach ($videos as $v): ?>
                                    <div class="col-12 p-3 bg-dark bg-opacity-40 border border-secondary border-opacity-25 rounded d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-white font-heading small fw-bold mb-1"><?php echo htmlspecialchars($v['title']); ?></h6>
                                            <small class="text-info d-block mb-1"><?php echo htmlspecialchars($v['category']); ?></small>
                                            <small class="text-muted font-monospace" style="font-size: 0.75rem;">YouTube ID: <?php echo htmlspecialchars($v['youtube_id']); ?></small>
                                        </div>
                                        
                                        <form method="POST" action="manage-videos.php" onsubmit="return confirm('Are you sure you want to delete this video?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="video_id" value="<?php echo $v['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>
