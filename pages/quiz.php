<?php
include_once '../includes/header.php';

// Use hardcoded quiz questions
global $DATA_QUIZ_QUESTIONS, $DATA_QUIZ_ATTEMPTS, $DATA_USERS;

$questions = $DATA_QUIZ_QUESTIONS;
$total_questions = count($questions);

// Merge hardcoded + session attempts for leaderboard
$all_attempts = $DATA_QUIZ_ATTEMPTS;
if (isset($_SESSION['session_quiz_attempts'])) {
    $all_attempts = array_merge($all_attempts, $_SESSION['session_quiz_attempts']);
}

// Add user names and sort for leaderboard
foreach ($all_attempts as &$a) {
    $a['user_name'] = get_user_name_by_id($a['user_id']);
}
unset($a);
usort($all_attempts, function($x, $y) {
    return $y['percentage'] <=> $x['percentage'];
});
$leaderboard = array_slice($all_attempts, 0, 5);

$user = get_logged_in_user();
$score = null;
$percent = 0;
$attempt_saved = false;

// Handle score posting
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_score') {
    $score = intval($_POST['score'] ?? 0);
    $percent = round(($score / $total_questions) * 100, 2);
    
    if ($user) {
        // Save to session
        if (!isset($_SESSION['session_quiz_attempts'])) {
            $_SESSION['session_quiz_attempts'] = [];
        }
        $new_id = count($all_attempts) + 1;
        $new_attempt = [
            'id' => $new_id,
            'user_id' => $user['id'],
            'score' => $score,
            'total_questions' => $total_questions,
            'percentage' => $percent,
            'attempted_at' => date('Y-m-d H:i:s')
        ];
        $_SESSION['session_quiz_attempts'][] = $new_attempt;
        $attempt_saved = true;
        
        // Re-build leaderboard
        $all_attempts[] = $new_attempt;
        foreach ($all_attempts as &$a) {
            if (!isset($a['user_name'])) {
                $a['user_name'] = get_user_name_by_id($a['user_id']);
            }
        }
        unset($a);
        usort($all_attempts, function($x, $y) {
            return $y['percentage'] <=> $x['percentage'];
        });
        $leaderboard = array_slice($all_attempts, 0, 5);
    }
}
?>

<div class="container py-4">
    <div class="row">
        <!-- Main Quiz Interface (Left) -->
        <div class="col-lg-8 mb-4">
            
            <!-- Quiz Landing Panel -->
            <div id="quizLandingPanel" class="card glass-card p-4 text-center border-info border-opacity-10 <?php echo ($score !== null) ? 'd-none' : ''; ?>">
                <div class="card-body">
                    <i class="fa-solid fa-graduation-cap text-info mb-3" style="font-size: 60px;"></i>
                    <h2 class="text-white font-heading mb-3">Road Safety Evaluation</h2>
                    <p class="text-secondary mb-4 col-md-10 mx-auto">
                        Test your knowledge of traffic rules, warnings, mandatory signboards, and emergency protocols. Answer 8 multiple choice questions.
                    </p>
                    
                    <div class="card bg-dark bg-opacity-40 border-secondary border-opacity-20 p-3 mb-4 text-start col-md-8 mx-auto">
                        <h6 class="text-white font-heading fw-bold mb-2"><i class="fa-solid fa-circle-info text-info me-2"></i>Quiz Guidelines:</h6>
                        <ul class="text-secondary small mb-0">
                            <li class="mb-1"><strong>Time Limit:</strong> 20 seconds per question.</li>
                            <li class="mb-1"><strong>Passing Ratio:</strong> Score 70% (at least 6/8 correct answers).</li>
                            <li class="mb-1"><strong>Reward:</strong> Instant customizable achievement certificate.</li>
                        </ul>
                    </div>
                    
                    <button class="btn btn-info text-dark fw-bold btn-lg px-4" onclick="startQuizRunner()">
                        <i class="fa-solid fa-circle-play me-2"></i>Start Evaluation
                    </button>
                </div>
            </div>
            
            <!-- Quiz Running Panel (Hidden by default) -->
            <div id="quizRunnerPanel" class="card glass-card p-4 border-info border-opacity-10 d-none">
                <div class="card-body">
                    <!-- Progress and Timer Header -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-dark border border-secondary text-secondary" id="questionCounter">Question 1 of 8</span>
                        <div class="d-flex align-items-center text-warning gap-1">
                            <i class="fa-solid fa-hourglass-half"></i>
                            <span id="quizTimer" class="fw-bold fs-5">20s</span>
                        </div>
                    </div>
                    
                    <div class="progress mb-4 bg-dark" style="height: 5px;">
                        <div class="progress-bar bg-info" id="quizProgressBar" role="progressbar" style="width: 12.5%;"></div>
                    </div>
                    
                    <!-- Question Text -->
                    <h4 class="text-white font-heading mb-4" id="quizQuestionText">Loading Question...</h4>
                    
                    <!-- Options Grid -->
                    <div id="quizOptionsContainer" class="mb-4">
                        <button class="quiz-option" id="optA" onclick="selectQuizOption('A')">A. Loading Option...</button>
                        <button class="quiz-option" id="optB" onclick="selectQuizOption('B')">B. Loading Option...</button>
                        <button class="quiz-option" id="optC" onclick="selectQuizOption('C')">C. Loading Option...</button>
                        <button class="quiz-option" id="optD" onclick="selectQuizOption('D')">D. Loading Option...</button>
                    </div>
                    
                    <!-- Explanation Display -->
                    <div id="quizExplanationBox" class="alert alert-secondary border-0 bg-dark bg-opacity-70 text-secondary d-none mb-4 small">
                        <strong class="text-white d-block mb-1" id="quizAnswerHeader">Correct!</strong>
                        <span id="quizExplanationText">Explanation...</span>
                    </div>
                    
                    <!-- Actions -->
                    <button class="btn btn-info text-dark fw-bold w-100 py-2 d-none" id="quizNextBtn" onclick="nextQuizQuestion()">
                        Next Question <i class="fa-solid fa-circle-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>
            
            <!-- Quiz Results Panel -->
            <?php if ($score !== null): ?>
                <div id="quizResultsPanel" class="card glass-card p-4 text-center border-info border-opacity-10">
                    <div class="card-body">
                        <?php if ($percent >= 70): ?>
                            <i class="fa-solid fa-trophy text-warning mb-3" style="font-size: 65px;"></i>
                            <h2 class="text-white font-heading mb-2">Congratulations! You Passed!</h2>
                            <p class="text-secondary small mb-4">You scored <strong><?php echo $score; ?> / <?php echo $total_questions; ?></strong> (<?php echo $percent; ?>%) and have demonstrated excellent safety awareness.</p>
                            
                            <!-- Certificate download button -->
                            <div class="p-3 bg-dark bg-opacity-40 border border-secondary border-opacity-30 rounded col-md-8 mx-auto mb-4">
                                <h6 class="text-white fw-bold mb-2">Road Safety Certificate Unlocked:</h6>
                                <button class="btn btn-success fw-bold text-white mb-2" onclick="showCertModal('<?php echo htmlspecialchars($user ? $user['name'] : 'Guest Student'); ?>', '<?php echo $score; ?>', '<?php echo date('d M Y'); ?>')">
                                    <i class="fa-solid fa-certificate me-2"></i>Generate & Download Certificate
                                </button>
                                <p class="text-muted small m-0">Available for printing or adding to digital portfolios.</p>
                            </div>
                        <?php else: ?>
                            <i class="fa-solid fa-circle-xmark text-danger mb-3" style="font-size: 65px;"></i>
                            <h2 class="text-white font-heading mb-2">Quiz Completed</h2>
                            <p class="text-secondary small mb-4">You scored <strong><?php echo $score; ?> / <?php echo $total_questions; ?></strong> (<?php echo $percent; ?>%). The passing threshold is 70% (6/8 correct).</p>
                            <a href="quiz.php" class="btn btn-outline-info">Try Again</a>
                        <?php endif; ?>
                        
                        <?php if (!$user && $percent >= 70): ?>
                            <div class="alert alert-warning border-0 bg-warning-subtle text-dark small col-md-8 mx-auto" role="alert">
                                <i class="fa-solid fa-circle-exclamation me-1"></i> <strong>Note:</strong> You completed the quiz as a Guest. To save this attempt permanently on your profile dashboard, please <a href="../auth/login.php" class="fw-bold text-dark text-decoration-underline">Login</a> or <a href="../auth/register.php" class="fw-bold text-dark text-decoration-underline">Register</a>.
                            </div>
                        <?php endif; ?>
                        
                        <a href="quiz.php" class="btn btn-link text-info text-decoration-none small mt-3">Reset evaluation panel</a>
                    </div>
                </div>
            <?php endif; ?>
            
        </div>
        
        <!-- Leaderboard Table (Right) -->
        <div class="col-lg-4">
            <div class="card glass-card p-3 border-info border-opacity-10 mb-4">
                <h5 class="text-white font-heading mb-3"><i class="fa-solid fa-crown text-warning me-2"></i>Quiz Leaderboard</h5>
                
                <?php if (empty($leaderboard)): ?>
                    <p class="text-secondary small m-0 text-center py-4">No top scores logged yet. Be the first!</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-dark table-sm custom-table text-secondary align-middle small m-0">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Name</th>
                                    <th>Score</th>
                                    <th>Ratio</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($leaderboard as $index => $row): ?>
                                    <tr>
                                        <td class="fw-bold text-white">#<?php echo ($index + 1); ?></td>
                                        <td class="text-white"><?php echo htmlspecialchars($row['user_name']); ?></td>
                                        <td><strong><?php echo $row['score']; ?>/8</strong></td>
                                        <td class="text-info"><?php echo $row['percentage']; ?>%</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="card glass-card p-3 border-info border-opacity-10 text-secondary small">
                <h6 class="text-white font-heading fw-bold mb-2">Quiz Categories covered:</h6>
                <ul class="mb-0 ps-3">
                    <li class="mb-1">Traffic sign categories</li>
                    <li class="mb-1">Double yellow lines & lane markings</li>
                    <li class="mb-1">Blood alcohol concentration limits</li>
                    <li class="mb-1">Safe emergency margins (3-Second Rule)</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Hidden score posting form -->
<form id="hiddenScoreForm" method="POST" action="quiz.php" class="d-none">
    <input type="hidden" name="action" value="save_score">
    <input type="hidden" name="score" id="formScoreInput" value="0">
</form>

<!-- Include Canvas Cert rendering utilities -->
<?php include_once '../auth/profile.php'; ?>

<!-- Client-side Quiz Engine Logic -->
<script>
// Quiz questions array (rendered dynamically from PHP array)
const questionsData = <?php echo json_encode($questions); ?>;

let currentQuestionIndex = 0;
let userScore = 0;
let timerSeconds = 20;
let timerInterval = null;
let optionSelected = false;

function startQuizRunner() {
    if (questionsData.length === 0) {
        alert("No quiz questions loaded.");
        return;
    }
    
    document.getElementById('quizLandingPanel').classList.add('d-none');
    document.getElementById('quizRunnerPanel').classList.remove('d-none');
    
    currentQuestionIndex = 0;
    userScore = 0;
    
    loadQuizQuestion(currentQuestionIndex);
}

function loadQuizQuestion(index) {
    optionSelected = false;
    
    // Hide Next button, hide explanation box
    document.getElementById('quizNextBtn').classList.add('d-none');
    document.getElementById('quizExplanationBox').classList.add('d-none');
    
    // Enable and clear options
    const options = ['optA', 'optB', 'optC', 'optD'];
    options.forEach(optId => {
        const btn = document.getElementById(optId);
        btn.disabled = false;
        btn.className = "quiz-option";
    });
    
    const q = questionsData[index];
    
    // Set text
    document.getElementById('questionCounter').textContent = `Question ${index + 1} of ${questionsData.length}`;
    document.getElementById('quizProgressBar').style.width = ((index + 1) / questionsData.length * 100) + '%';
    document.getElementById('quizQuestionText').textContent = q.question;
    
    document.getElementById('optA').textContent = "A. " + q.option_a;
    document.getElementById('optB').textContent = "B. " + q.option_b;
    document.getElementById('optC').textContent = "C. " + q.option_c;
    document.getElementById('optD').textContent = "D. " + q.option_d;
    
    // Start timer
    timerSeconds = 20;
    document.getElementById('quizTimer').textContent = timerSeconds + 's';
    
    clearInterval(timerInterval);
    timerInterval = setInterval(() => {
        timerSeconds--;
        document.getElementById('quizTimer').textContent = timerSeconds + 's';
        if (timerSeconds <= 0) {
            clearInterval(timerInterval);
            // Autocomplete timeout (mark incorrect)
            revealCorrectAnswer(null);
        }
    }, 1000);
}

function selectQuizOption(selectedOption) {
    if (optionSelected) return;
    optionSelected = true;
    clearInterval(timerInterval);
    
    const q = questionsData[currentQuestionIndex];
    const isCorrect = (selectedOption === q.correct_option);
    
    if (isCorrect) {
        userScore++;
        document.getElementById('opt' + selectedOption).classList.add('correct');
        document.getElementById('quizAnswerHeader').textContent = "Correct!";
        document.getElementById('quizAnswerHeader').className = "text-success d-block mb-1";
    } else {
        document.getElementById('opt' + selectedOption).classList.add('incorrect');
        document.getElementById('opt' + q.correct_option).classList.add('correct');
        document.getElementById('quizAnswerHeader').textContent = "Incorrect";
        document.getElementById('quizAnswerHeader').className = "text-danger d-block mb-1";
    }
    
    // Lock all options
    const options = ['A', 'B', 'C', 'D'];
    options.forEach(opt => {
        document.getElementById('opt' + opt).disabled = true;
    });
    
    // Show explanation
    const expBox = document.getElementById('quizExplanationBox');
    expBox.classList.remove('d-none');
    document.getElementById('quizExplanationText').textContent = q.explanation;
    
    // Show next button
    document.getElementById('quizNextBtn').classList.remove('d-none');
}

function revealCorrectAnswer(timeoutOption) {
    optionSelected = true;
    const q = questionsData[currentQuestionIndex];
    
    // Show correct answer
    document.getElementById('opt' + q.correct_option).classList.add('correct');
    
    document.getElementById('quizAnswerHeader').textContent = "Time Expired";
    document.getElementById('quizAnswerHeader').className = "text-warning d-block mb-1";
    
    // Lock options
    const options = ['A', 'B', 'C', 'D'];
    options.forEach(opt => {
        document.getElementById('opt' + opt).disabled = true;
    });
    
    // Show explanation
    const expBox = document.getElementById('quizExplanationBox');
    expBox.classList.remove('d-none');
    document.getElementById('quizExplanationText').textContent = q.explanation;
    
    // Show next button
    document.getElementById('quizNextBtn').classList.remove('d-none');
}

function nextQuizQuestion() {
    currentQuestionIndex++;
    
    if (currentQuestionIndex < questionsData.length) {
        loadQuizQuestion(currentQuestionIndex);
    } else {
        // Finish Quiz: Post score to server
        document.getElementById('formScoreInput').value = userScore;
        document.getElementById('hiddenScoreForm').submit();
    }
}
</script>

<?php include_once '../includes/footer.php'; ?>
