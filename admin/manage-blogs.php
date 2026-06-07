<?php
include_once '../includes/header.php';
require_admin();

$success = "";
$error = "";

$edit_mode = false;
$edit_blog = null;

// Handle GET edit load
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    if ($edit_id > 0) {
        $edit_blog = db_query_first("SELECT * FROM blogs WHERE id = :id", ['id' => $edit_id]);
        if ($edit_blog) {
            $edit_mode = true;
        }
    }
}

// Handle Form Submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add') {
        $title = trim($_POST['title'] ?? '');
        $excerpt = trim($_POST['excerpt'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $author = trim($_POST['author'] ?? 'Admin');
        
        if (empty($title) || empty($excerpt) || empty($content)) {
            $error = "All form fields are required.";
        } else {
            $inserted = db_execute(
                "INSERT INTO blogs (title, excerpt, content, author) VALUES (:title, :excerpt, :content, :author)",
                [
                    'title' => $title,
                    'excerpt' => $excerpt,
                    'content' => $content,
                    'author' => $author
                ]
            );
            
            if ($inserted) {
                $success = "Blog article successfully published!";
            } else {
                $error = "Failed to publish article.";
            }
        }
    } elseif ($action === 'edit') {
        $blog_id = intval($_POST['blog_id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $excerpt = trim($_POST['excerpt'] ?? '');
        $content = trim($_POST['content'] ?? '');
        
        if (empty($title) || empty($excerpt) || empty($content) || $blog_id === 0) {
            $error = "All form fields are required.";
        } else {
            $updated = db_execute(
                "UPDATE blogs SET title = :title, excerpt = :excerpt, content = :content WHERE id = :id",
                [
                    'title' => $title,
                    'excerpt' => $excerpt,
                    'content' => $content,
                    'id' => $blog_id
                ]
            );
            
            if ($updated) {
                $success = "Article successfully updated.";
                $edit_mode = false;
                $edit_blog = null;
            } else {
                $error = "Failed to update article.";
            }
        }
    } elseif ($action === 'delete') {
        $blog_id = intval($_POST['blog_id'] ?? 0);
        if ($blog_id > 0) {
            $deleted = db_execute("DELETE FROM blogs WHERE id = :id", ['id' => $blog_id]);
            if ($deleted) {
                $success = "Article successfully deleted.";
            } else {
                $error = "Failed to delete article.";
            }
        }
    }
}

// Fetch all blogs
$blogs = db_query("SELECT id, title, author, created_at FROM blogs ORDER BY created_at DESC");
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
                        <a href="manage-blogs.php" class="list-group-item list-group-item-action bg-transparent border-0 text-info fw-bold">
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
        
        <!-- Manage Blogs Content (Right) -->
        <div class="col-lg-9">
            <h2 class="text-white font-heading mb-4"><i class="fa-solid fa-newspaper text-info me-2"></i>Manage Safety Blogs</h2>
            
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
                <!-- Blog Form (Left of detail) -->
                <div class="col-md-5">
                    <div class="card glass-card p-4 border-info border-opacity-10">
                        <h5 class="text-white font-heading mb-3">
                            <?php echo $edit_mode ? '<i class="fa-solid fa-file-pen text-warning me-2"></i>Edit Article' : '<i class="fa-solid fa-file-circle-plus text-success me-2"></i>Publish New Article'; ?>
                        </h5>
                        
                        <form method="POST" action="manage-blogs.php">
                            <input type="hidden" name="action" value="<?php echo $edit_mode ? 'edit' : 'add'; ?>">
                            <?php if ($edit_mode): ?>
                                <input type="hidden" name="blog_id" value="<?php echo $edit_blog['id']; ?>">
                            <?php endif; ?>
                            
                            <div class="mb-3">
                                <label for="title" class="form-label text-secondary">Article Title</label>
                                <input type="text" class="form-control bg-dark text-white border-secondary" id="title" name="title" required placeholder="Article title..." value="<?php echo htmlspecialchars($edit_blog['title'] ?? ''); ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="excerpt" class="form-label text-secondary">Short Excerpt (Summary)</label>
                                <input type="text" class="form-control bg-dark text-white border-secondary" id="excerpt" name="excerpt" required placeholder="A brief one-sentence summary..." value="<?php echo htmlspecialchars($edit_blog['excerpt'] ?? ''); ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="content" class="form-label text-secondary">Content Body</label>
                                <textarea class="form-control bg-dark text-white border-secondary small" id="content" name="content" rows="6" required placeholder="Write the full campaign / article body here..."><?php echo htmlspecialchars($edit_blog['content'] ?? ''); ?></textarea>
                            </div>
                            
                            <?php if (!$edit_mode): ?>
                                <div class="mb-3">
                                    <label for="author" class="form-label text-secondary">Author Signature</label>
                                    <input type="text" class="form-control bg-dark text-white border-secondary" id="author" name="author" placeholder="e.g. Safety Inspector" value="Admin">
                                </div>
                            <?php endif; ?>
                            
                            <button type="submit" class="btn btn-info text-dark fw-bold w-100 py-2">
                                <?php echo $edit_mode ? '<i class="fa-solid fa-check me-1"></i>Update Article' : '<i class="fa-solid fa-plus me-1"></i>Publish Article'; ?>
                            </button>
                            
                            <?php if ($edit_mode): ?>
                                <a href="manage-blogs.php" class="btn btn-outline-secondary btn-sm w-100 mt-2">Cancel Edit</a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
                
                <!-- Published Blogs Table (Right of form) -->
                <div class="col-md-7">
                    <div class="card glass-card p-4 border-info border-opacity-10 h-100">
                        <h5 class="text-white font-heading mb-3">Published Articles</h5>
                        
                        <?php if (empty($blogs)): ?>
                            <p class="text-secondary small text-center py-4">No published articles yet.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-dark table-hover align-middle custom-table small">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($blogs as $b): ?>
                                            <tr>
                                                <td class="text-white font-heading fw-bold"><?php echo htmlspecialchars($b['title']); ?></td>
                                                <td class="small text-muted"><?php echo date('d M Y', strtotime($b['created_at'])); ?></td>
                                                <td>
                                                    <div class="d-flex gap-1">
                                                        <a href="manage-blogs.php?edit=<?php echo $b['id']; ?>" class="btn btn-sm btn-outline-warning text-warning-emphasis">
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        </a>
                                                        
                                                        <form method="POST" action="manage-blogs.php" onsubmit="return confirm('Are you sure you want to delete this article?');">
                                                            <input type="hidden" name="action" value="delete">
                                                            <input type="hidden" name="blog_id" value="<?php echo $b['id']; ?>">
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                <i class="fa-solid fa-trash-can"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>
