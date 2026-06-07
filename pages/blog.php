<?php
include_once '../includes/header.php';

// Check if a specific blog ID is requested
$blog_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($blog_id > 0) {
    // 1. Fetch single blog post details
    $blog = db_query_first("SELECT * FROM blogs WHERE id = :id", ['id' => $blog_id]);
} else {
    // 2. Fetch all blog posts
    $blogs = db_query("SELECT * FROM blogs ORDER BY created_at DESC");
}
?>

<div class="container py-4">
    <?php if ($blog_id > 0): ?>
        <!-- ======================================================== -->
        <!-- DETAILED BLOG VIEW -->
        <!-- ======================================================== -->
        <?php if (!$blog): ?>
            <div class="text-center py-5 text-secondary">
                <i class="fa-solid fa-file-circle-xmark fs-1 mb-3 text-muted"></i>
                <p>The requested article could not be found.</p>
                <a href="blog.php" class="btn btn-outline-info">Back to Blog</a>
            </div>
        <?php else: ?>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <!-- Navigation back -->
                    <a href="blog.php" class="btn btn-link text-info text-decoration-none p-0 mb-4 fw-bold">
                        <i class="fa-solid fa-arrow-left me-1"></i> Back to Articles
                    </a>
                    
                    <!-- Article Body -->
                    <article class="blog-detail text-secondary">
                        <h1 class="text-white font-heading display-4 mb-3"><?php echo htmlspecialchars($blog['title']); ?></h1>
                        
                        <div class="d-flex align-items-center gap-3 mb-4 text-muted small">
                            <span><i class="fa-solid fa-user me-1"></i><?php echo htmlspecialchars($blog['author']); ?></span>
                            <span>&bull;</span>
                            <span><i class="fa-solid fa-calendar me-1"></i><?php echo date('d M Y', strtotime($blog['created_at'])); ?></span>
                        </div>
                        
                        <!-- Main text content -->
                        <div class="article-content fs-6 lh-lg mt-4 text-secondary">
                            <!-- Process and display content line breaks cleanly -->
                            <?php echo nl2br(htmlspecialchars($blog['content'])); ?>
                        </div>
                    </article>
                    
                    <!-- Warning / Call to action at bottom of blog -->
                    <div class="card glass-card p-4 border-info border-opacity-10 mt-5">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fa-solid fa-graduation-cap text-info fs-1"></i>
                            <div>
                                <h5 class="text-white font-heading m-0 mb-1">Knowledge is Safety!</h5>
                                <p class="text-muted small m-0">You've read about safety. Put your knowledge to test by trying our Evaluation Quiz and earning your certificate.</p>
                                <a href="quiz.php" class="text-info text-decoration-none small fw-bold d-inline-block mt-2">Take Safety Quiz <i class="fa-solid fa-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
    <?php else: ?>
        <!-- ======================================================== -->
        <!-- BLOG FEED LIST VIEW -->
        <!-- ======================================================== -->
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 font-heading text-white mb-2">Road Safety Campaign Blog</h1>
                <p class="text-secondary">Explore safety campaigns, insights into crash dynamics, safety tips, and newly updated regional traffic codes.</p>
                <hr class="border-info border-opacity-50 w-25 mx-auto">
            </div>
        </div>
        
        <?php if (empty($blogs)): ?>
            <div class="text-center py-5 text-secondary">
                <i class="fa-solid fa-newspaper fs-1 mb-3 text-muted"></i>
                <p>No blog posts published yet.</p>
            </div>
        <?php else: ?>
            <div class="row g-4 justify-content-center">
                <?php foreach ($blogs as $b): ?>
                    <div class="col-md-6 col-lg-5">
                        <div class="card glass-card p-3 h-100 border-info border-opacity-10 d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex align-items-center text-muted small mb-2 gap-3">
                                    <span><i class="fa-solid fa-user me-1 text-info text-opacity-50"></i><?php echo htmlspecialchars($b['author']); ?></span>
                                    <span>&bull;</span>
                                    <span><i class="fa-solid fa-calendar me-1"></i><?php echo date('d M Y', strtotime($b['created_at'])); ?></span>
                                </div>
                                <h3 class="text-white font-heading fs-4 mb-2"><?php echo htmlspecialchars($b['title']); ?></h3>
                                <p class="text-secondary small card-text mb-4"><?php echo htmlspecialchars($b['excerpt']); ?></p>
                            </div>
                            <div>
                                <a href="blog.php?id=<?php echo $b['id']; ?>" class="btn btn-outline-info btn-sm fw-bold">Read Full Post</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php include_once '../includes/footer.php'; ?>
