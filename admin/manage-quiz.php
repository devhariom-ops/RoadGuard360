<?php
include_once '../includes/header.php';
require_admin();

$success = "";
$error = "";

// Handle Form Submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add') {
        $question = trim($_POST['question'] ?? '');
        $optA = trim($_POST['option_a'] ?? '');
        $optB = trim($_POST['option_b'] ?? '');
        $optC = trim($_POST['option_c'] ?? '');
        $optD = trim($_POST['option_d'] ?? '');
        $correct = $_POST['correct_option'] ?? 'A';
        $explanation = trim($_POST['explanation'] ?? '');
        
        if (empty($question) || empty($optA) || empty($optB) || empty($optC) || empty($optD)) {
            $error = "All fields except explanation are required.";
        } else {
            // Save question
            $inserted = db_execute(
                "INSERT INTO quiz_questions (question, option_a, option_b, option_c, option_d, correct_option, explanation) 
                 VALUES (:question, :option_a, :option_b, :option_c, :option_d, :correct_option, :explanation)",
                [
                    'question' => $question,
                    'option_a' => $optA,
                    'option_b' => $optB,
                    'option_c' => $optC,
                    'option_d' => $optD,
                    'correct_option' => $correct,
                    'explanation' => $explanation
                ]
            );
            
            if ($inserted) {
                $success = "Quiz question successfully added!";
            } else {
                $error = "Failed to add question.";
            }
        }
    } elseif ($action === 'delete') {
        $q_id = intval($_POST['question_id'] ?? 0);
        if ($q_id > 0) {
            $deleted = db_execute("DELETE FROM quiz_questions WHERE id = :id", ['id' => $q_id]);
            if ($deleted) {
                $success = "Question successfully deleted.";
            } else {
                $error = "Failed to delete question.";
            }
        }
    }
}

// Fetch all questions
$questions = db_query("SELECT * FROM quiz_questions");
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
                        <a href="manage-quiz.php" class="list-group-item list-group-item-action bg-transparent border-0 text-info fw-bold">
                            <i class="fa-solid fa-graduation-cap me-2"></i>Manage Quiz
                        </a>
                        <a href="manage-videos.php" class="list-group-item list-group-item-action bg-transparent border-0 text-secondary hover-info">
                            <i class="fa-solid fa-play-circle me-2"></i>Manage Videos
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Manage Quiz Content (Right) -->
        <div class="col-lg-9">
            <h2 class="text-white font-heading mb-4"><i class="fa-solid fa-graduation-cap text-info me-2"></i>Manage Quiz Questions</h2>
            
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
                <!-- Add Question Form (Left) -->
                <div class="col-md-5">
                    <div class="card glass-card p-4 border-info border-opacity-10">
                        <h5 class="text-white font-heading mb-3"><i class="fa-solid fa-square-plus text-success me-2"></i>Add Question</h5>
                        
                        <form method="POST" action="manage-quiz.php">
                            <input type="hidden" name="action" value="add">
                            
                            <div class="mb-3">
                                <label for="question" class="form-label text-secondary">Question Text</label>
                                <textarea class="form-control bg-dark text-white border-secondary small" id="question" name="question" rows="3" required placeholder="Question text..."></textarea>
                            </div>
                            
                            <div class="mb-2">
                                <label class="form-label text-secondary">Options (A-D)</label>
                                <input type="text" class="form-control form-control-sm bg-dark text-white border-secondary mb-2" name="option_a" required placeholder="Option A">
                                <input type="text" class="form-control form-control-sm bg-dark text-white border-secondary mb-2" name="option_b" required placeholder="Option B">
                                <input type="text" class="form-control form-control-sm bg-dark text-white border-secondary mb-2" name="option_c" required placeholder="Option C">
                                <input type="text" class="form-control form-control-sm bg-dark text-white border-secondary mb-2" name="option_d" required placeholder="Option D">
                            </div>
                            
                            <div class="mb-3">
                                <label for="correct_option" class="form-label text-secondary">Correct Choice</label>
                                <select class="form-select bg-dark text-white border-secondary" id="correct_option" name="correct_option">
                                    <option value="A">Option A</option>
                                    <option value="B">Option B</option>
                                    <option value="C">Option C</option>
                                    <option value="D">Option D</option>
                                </select>
                            </div>
                            
                            <div class="mb-4">
                                <label for="explanation" class="form-label text-secondary">Explanation (Educational notes)</label>
                                <textarea class="form-control bg-dark text-white border-secondary small" id="explanation" name="explanation" rows="2" placeholder="Why is this option correct?..."></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-info text-dark fw-bold w-100 py-2">
                                <i class="fa-solid fa-plus me-1"></i>Save Question
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Questions List (Right) -->
                <div class="col-md-7">
                    <div class="card glass-card p-4 border-info border-opacity-10 h-100">
                        <h5 class="text-white font-heading mb-3">Loaded Evaluation Questions</h5>
                        
                        <?php if (empty($questions)): ?>
                            <p class="text-secondary small text-center py-4">No questions logged in database.</p>
                        <?php else: ?>
                            <div class="row g-3">
                                <?php foreach ($questions as $q): ?>
                                    <div class="col-12 p-3 bg-dark bg-opacity-40 border border-secondary border-opacity-25 rounded d-flex justify-content-between align-items-start">
                                        <div class="pe-2">
                                            <h6 class="text-white font-heading small fw-bold mb-2">Q: <?php echo htmlspecialchars($q['question']); ?></h6>
                                            <ul class="text-muted small ps-3 mb-2" style="font-size: 0.8rem;">
                                                <li>A: <?php echo htmlspecialchars($q['option_a']); ?></li>
                                                <li>B: <?php echo htmlspecialchars($q['option_b']); ?></li>
                                                <li>C: <?php echo htmlspecialchars($q['option_c']); ?></li>
                                                <li>D: <?php echo htmlspecialchars($q['option_d']); ?></li>
                                            </ul>
                                            <div class="text-success small mb-1">Correct: <strong>Option <?php echo $q['correct_option']; ?></strong></div>
                                            <p class="text-secondary small m-0" style="font-size: 0.75rem;"><em>Notes: <?php echo htmlspecialchars($q['explanation'] ?? 'None'); ?></em></p>
                                        </div>
                                        
                                        <form method="POST" action="manage-quiz.php" onsubmit="return confirm('Are you sure you want to delete this question?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="question_id" value="<?php echo $q['id']; ?>">
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
