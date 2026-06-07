<?php
include_once '../includes/header.php';

// Check if user is logged in (needed for liking/commenting)
$user = get_logged_in_user();

// Fetch all videos
$videos = db_query("SELECT * FROM videos");

// Get selected video or default to first
$selected_id = isset($_GET['id']) ? intval($_GET['id']) : (count($videos) > 0 ? $videos[0]['id'] : 0);

$selected_video = null;
foreach ($videos as $v) {
    if ($v['id'] == $selected_id) {
        $selected_video = $v;
        break;
    }
}

// Handle like, bookmark, comment posting
$message_notice = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user) {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'comment') {
        $comment_text = trim($_POST['comment_text'] ?? '');
        if (!empty($comment_text)) {
            // Save comment
            db_execute(
                "INSERT INTO video_interactions (user_id, video_id, is_liked, is_bookmarked, comment) VALUES (:user_id, :video_id, 0, 0, :comment)",
                [
                    'user_id' => $user['id'],
                    'video_id' => $selected_id,
                    'comment' => $comment_text
                ]
            );
            $message_notice = "Comment posted successfully!";
        }
    } elseif ($action === 'like') {
        // Check if already liked
        $existing = db_query_first(
            "SELECT id, is_liked FROM video_interactions WHERE user_id = :user_id AND video_id = :video_id AND is_liked = 1",
            ['user_id' => $user['id'], 'video_id' => $selected_id]
        );
        
        if ($existing) {
            // Unlike (set is_liked = 0)
            // In a real DB we might delete or update. In our mock/live we'll update
            db_execute(
                "UPDATE video_interactions SET is_liked = 0 WHERE user_id = :user_id AND video_id = :video_id",
                ['user_id' => $user['id'], 'video_id' => $selected_id]
            );
        } else {
            // Like (insert or update)
            db_execute(
                "INSERT INTO video_interactions (user_id, video_id, is_liked) VALUES (:user_id, :video_id, 1) ON DUPLICATE KEY UPDATE is_liked = 1",
                ['user_id' => $user['id'], 'video_id' => $selected_id]
            );
        }
    } elseif ($action === 'bookmark') {
        // Toggle bookmark
        $existing = db_query_first(
            "SELECT id, is_bookmarked FROM video_interactions WHERE user_id = :user_id AND video_id = :video_id AND is_bookmarked = 1",
            ['user_id' => $user['id'], 'video_id' => $selected_id]
        );
        
        if ($existing) {
            db_execute(
                "UPDATE video_interactions SET is_bookmarked = 0 WHERE user_id = :user_id AND video_id = :video_id",
                ['user_id' => $user['id'], 'video_id' => $selected_id]
            );
        } else {
            db_execute(
                "INSERT INTO video_interactions (user_id, video_id, is_bookmarked) VALUES (:user_id, :video_id, 0, 1) ON DUPLICATE KEY UPDATE is_bookmarked = 1",
                ['user_id' => $user['id'], 'video_id' => $selected_id]
            );
        }
    }
}

// Fetch stats for the selected video
$likes_count = 0;
$comments = [];
$is_currently_liked = false;
$is_currently_bookmarked = false;

if ($selected_id > 0) {
    // Get all interactions for this video
    $interactions = db_query("SELECT * FROM video_interactions WHERE video_id = :video_id", ['video_id' => $selected_id]);
    
    foreach ($interactions as $inter) {
        if ($inter['is_liked'] == 1) {
            $likes_count++;
            if ($user && $inter['user_id'] == $user['id']) {
                $is_currently_liked = true;
            }
        }
        if ($inter['is_bookmarked'] == 1 && $user && $inter['user_id'] == $user['id']) {
            $is_currently_bookmarked = true;
        }
        if (!empty($inter['comment'])) {
            $comments[] = $inter;
        }
    }
}
?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col-lg-8 mx-auto text-center">
            <h1 class="display-4 font-heading text-white mb-2">Road Safety Video Library</h1>
            <p class="text-secondary">Watch visual crash physics breakdowns, sign guides, and basic emergency response tutorials.</p>
        </div>
    </div>
    
    <?php if (empty($videos)): ?>
        <div class="text-center py-5 text-secondary">
            <i class="fa-solid fa-video-slash fs-1 mb-3 text-muted"></i>
            <p>No safety videos found in the database.</p>
        </div>
    <?php else: ?>
        <div class="row">
            <!-- Main Video Player and Comments (Left) -->
            <div class="col-lg-8 mb-4">
                <?php if ($selected_video): ?>
                    <div class="card glass-card border-info border-opacity-10 overflow-hidden mb-4">
                        <div class="video-card-iframe">
                            <iframe src="https://www.youtube.com/embed/<?php echo $selected_video['youtube_id']; ?>?rel=0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                        </div>
                        
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h3 class="text-white font-heading mb-0"><?php echo htmlspecialchars($selected_video['title']); ?></h3>
                                <span class="badge bg-info text-dark fw-bold"><?php echo htmlspecialchars($selected_video['category']); ?></span>
                            </div>
                            <p class="text-secondary small"><?php echo htmlspecialchars($selected_video['description']); ?></p>
                            
                            <!-- Video Controls (Like, Comment, Share, Bookmark) -->
                            <div class="d-flex flex-wrap gap-2 pt-3 border-top border-secondary">
                                <!-- Like form -->
                                <form method="POST" action="videos.php?id=<?php echo $selected_id; ?>">
                                    <input type="hidden" name="action" value="like">
                                    <button type="submit" class="btn btn-sm <?php echo $is_currently_liked ? 'btn-info text-dark' : 'btn-outline-info'; ?> fw-semibold" <?php echo !$user ? 'disabled title="Login to like"' : ''; ?>>
                                        <i class="fa-solid fa-thumbs-up me-1"></i> Like (<?php echo $likes_count; ?>)
                                    </button>
                                </form>
                                
                                <!-- Bookmark form -->
                                <form method="POST" action="videos.php?id=<?php echo $selected_id; ?>">
                                    <input type="hidden" name="action" value="bookmark">
                                    <button type="submit" class="btn btn-sm <?php echo $is_currently_bookmarked ? 'btn-warning text-dark' : 'btn-outline-warning'; ?> fw-semibold" <?php echo !$user ? 'disabled title="Login to bookmark"' : ''; ?>>
                                        <i class="fa-solid fa-bookmark me-1"></i> <?php echo $is_currently_bookmarked ? 'Bookmarked' : 'Bookmark'; ?>
                                    </button>
                                </form>
                                
                                <!-- Share trigger -->
                                <button type="button" class="btn btn-outline-light btn-sm fw-semibold" onclick="shareVideo('<?php echo htmlspecialchars($selected_video['title']); ?>')">
                                    <i class="fa-solid fa-share-nodes me-1"></i> Share
                                </button>
                                
                                <?php if (!$user): ?>
                                    <span class="text-muted small align-self-center ms-auto">
                                        <a href="../auth/login.php" class="text-info text-decoration-none">Login</a> to interact with videos.
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Comments Panel -->
                    <div class="card glass-card p-4 border-info border-opacity-10">
                        <h4 class="text-white font-heading mb-4"><i class="fa-solid fa-comments text-info me-2"></i>Comments (<?php echo count($comments); ?>)</h4>
                        
                        <!-- Write Comment Form -->
                        <?php if ($user): ?>
                            <form method="POST" action="videos.php?id=<?php echo $selected_id; ?>" class="mb-4">
                                <input type="hidden" name="action" value="comment">
                                <div class="mb-2">
                                    <textarea class="form-control bg-dark text-white border-secondary small" name="comment_text" rows="3" placeholder="Write an educational comment or note..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-info btn-sm text-dark fw-bold">Post Comment</button>
                            </form>
                        <?php endif; ?>
                        
                        <!-- Comments List -->
                        <div class="comments-list">
                            <?php if (empty($comments)): ?>
                                <p class="text-secondary small text-center m-0">No comments posted yet. Be the first!</p>
                            <?php else: ?>
                                <?php foreach ($comments as $c): ?>
                                    <div class="p-3 bg-dark bg-opacity-40 border border-secondary border-opacity-30 rounded mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <strong class="text-info small"><?php echo htmlspecialchars($c['user_name']); ?></strong>
                                            <span class="text-muted small" style="font-size: 0.7rem;"><?php echo date('d M Y, h:i A', strtotime($c['created_at'])); ?></span>
                                        </div>
                                        <p class="text-secondary small m-0"><?php echo htmlspecialchars($c['comment']); ?></p>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Video Playlist Sidebar (Right) -->
            <div class="col-lg-4">
                <div class="card glass-card p-3 border-info border-opacity-10">
                    <h5 class="text-white font-heading mb-3"><i class="fa-solid fa-list-ul text-info me-2"></i>Video Lectures</h5>
                    <div class="list-group list-group-flush">
                        <?php foreach ($videos as $v): ?>
                            <a href="videos.php?id=<?php echo $v['id']; ?>" class="list-group-item list-group-item-action bg-transparent border-secondary border-opacity-20 py-3 <?php echo $v['id'] == $selected_id ? 'active text-info fw-bold bg-dark bg-opacity-30' : 'text-secondary'; ?>">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="m-0 font-heading text-white"><?php echo htmlspecialchars($v['title']); ?></h6>
                                    <i class="fa-solid fa-play-circle text-muted fs-6"></i>
                                </div>
                                <small class="text-muted d-block"><?php echo htmlspecialchars($v['category']); ?></small>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
// Clipboard link sharing
function shareVideo(title) {
    const videoUrl = window.location.href;
    
    if (navigator.share) {
        navigator.share({
            title: title + ' - SafeRoads',
            text: 'Check out this road safety educational video:',
            url: videoUrl
        }).catch(err => {
            console.log("Error sharing", err);
        });
    } else {
        navigator.clipboard.writeText(videoUrl).then(() => {
            showNotification('Link Copied!', 'Video reference link has been copied to your clipboard.', 'success');
        }).catch(err => {
            alert('Failed to copy: ' + err);
        });
    }
}
</script>

<?php include_once '../includes/footer.php'; ?>
