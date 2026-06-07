<?php
include_once '../includes/header.php';
require_login();

$user = get_logged_in_user();

// Fetch Quiz Attempts for this user
$attempts = db_query(
    "SELECT * FROM quiz_attempts WHERE user_id = :user_id ORDER BY attempted_at DESC",
    ['user_id' => $user['id']]
);

// Fetch Hazards reported by this user
$reports = db_query(
    "SELECT * FROM reports WHERE user_id = :user_id ORDER BY created_at DESC",
    ['user_id' => $user['id']]
);

// Calculate Quiz statistics
$total_attempts = count($attempts);
$high_score = 0;
$avg_score = 0;
$passed_attempts = 0;

if ($total_attempts > 0) {
    $total_score = 0;
    foreach ($attempts as $a) {
        $total_score += $a['score'];
        if ($a['score'] > $high_score) {
            $high_score = $a['score'];
        }
        if ($a['percentage'] >= 70) {
            $passed_attempts++;
        }
    }
    $avg_score = round($total_score / $total_attempts, 1);
}
?>

<div class="container py-4">
    <div class="row">
        <!-- User Information Column -->
        <div class="col-lg-4 mb-4">
            <div class="card glass-card p-3 text-center mb-4">
                <div class="card-body">
                    <div class="position-relative d-inline-block mb-3">
                        <i class="fa-solid fa-user-circle text-info" style="font-size: 80px;"></i>
                        <?php if ($user['role'] === 'admin'): ?>
                            <span class="position-absolute bottom-0 end-0 badge rounded-pill bg-danger border border-dark">Admin</span>
                        <?php endif; ?>
                    </div>
                    <h4 class="text-white font-heading mb-1"><?php echo htmlspecialchars($user['name']); ?></h4>
                    <p class="text-secondary small mb-3"><?php echo htmlspecialchars($user['email']); ?></p>
                    
                    <hr class="border-secondary my-3">
                    
                    <div class="text-start mb-4">
                        <h6 class="text-info fw-bold mb-2">Account Details:</h6>
                        <div class="small text-secondary mb-2">
                            <i class="fa-solid fa-calendar-alt me-2 text-muted"></i>Joined: SafeRoads User
                        </div>
                        <div class="small text-secondary">
                            <i class="fa-solid fa-shield me-2 text-muted"></i>Role: <?php echo ucfirst($user['role']); ?>
                        </div>
                    </div>
                    
                    <?php if ($user['role'] === 'admin'): ?>
                        <a href="<?php echo BASE_PATH; ?>admin/dashboard.php" class="btn btn-danger w-100 fw-bold">
                            <i class="fa-solid fa-gauge me-2"></i>Go to Admin Panel
                        </a>
                    <?php else: ?>
                        <a href="<?php echo BASE_PATH; ?>pages/quiz.php" class="btn btn-info text-dark w-100 fw-bold">
                            <i class="fa-solid fa-graduation-cap me-2"></i>Take New Quiz
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Quick Quiz Performance Stats -->
            <div class="card glass-card p-3">
                <div class="card-body">
                    <h5 class="text-white font-heading mb-3"><i class="fa-solid fa-award text-warning me-2"></i>Road Safety Stats</h5>
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="text-muted small">Attempts</div>
                            <div class="fs-4 fw-bold text-info"><?php echo $total_attempts; ?></div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="text-muted small">High Score</div>
                            <div class="fs-4 fw-bold text-warning"><?php echo $high_score; ?>/8</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Avg Score</div>
                            <div class="fs-4 fw-bold text-purple"><?php echo $avg_score; ?>/8</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Certificates</div>
                            <div class="fs-4 fw-bold text-success"><?php echo $passed_attempts; ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- User Activity Log Columns -->
        <div class="col-lg-8">
            <div class="card glass-card p-4">
                <div class="card-body p-0">
                    <!-- Nav Tabs -->
                    <ul class="nav nav-pills mb-4" id="profileTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active me-2" id="quiz-tab" data-bs-toggle="pill" data-bs-target="#quiz" type="button" role="tab" aria-controls="quiz" aria-selected="true">
                                <i class="fa-solid fa-certificate me-2"></i>Quiz History & Certs
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="hazard-tab" data-bs-toggle="pill" data-bs-target="#hazard" type="button" role="tab" aria-controls="hazard" aria-selected="false">
                                <i class="fa-solid fa-triangle-exclamation me-2"></i>Hazard Reports (<?php echo count($reports); ?>)
                            </button>
                        </li>
                    </ul>
                    
                    <!-- Tab Panes -->
                    <div class="tab-content" id="profileTabContent">
                        
                        <!-- Quiz Panel -->
                        <div class="tab-pane fade show active" id="quiz" role="tablist" aria-labelledby="quiz-tab">
                            <h5 class="text-white mb-3">Your Quiz Achievements</h5>
                            
                            <?php if (empty($attempts)): ?>
                                <div class="text-center py-5 text-secondary">
                                    <i class="fa-solid fa-graduation-cap fs-1 mb-3 text-muted"></i>
                                    <p>You haven't attempted any road safety quizzes yet.</p>
                                    <a href="<?php echo BASE_PATH; ?>pages/quiz.php" class="btn btn-outline-info">Take Safety Quiz</a>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-dark table-hover custom-table align-middle">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Score</th>
                                                <th>Percentage</th>
                                                <th>Status</th>
                                                <th>Certificate</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($attempts as $a): ?>
                                                <tr>
                                                    <td class="small text-secondary"><?php echo date('d M Y, h:i A', strtotime($a['attempted_at'])); ?></td>
                                                    <td class="fw-bold text-white"><?php echo $a['score']; ?> / <?php echo $a['total_questions']; ?></td>
                                                    <td class="text-info"><?php echo $a['percentage']; ?>%</td>
                                                    <td>
                                                        <?php if ($a['percentage'] >= 70): ?>
                                                            <span class="badge bg-success-subtle text-success border border-success border-opacity-20 px-2 py-1">PASSED</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-danger-subtle text-danger border border-danger border-opacity-20 px-2 py-1">FAILED</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($a['percentage'] >= 70): ?>
                                                            <button class="btn btn-sm btn-info text-dark fw-bold" onclick="showCertModal('<?php echo htmlspecialchars($user['name']); ?>', '<?php echo $a['score']; ?>', '<?php echo date('d M Y', strtotime($a['attempted_at'])); ?>')">
                                                                <i class="fa-solid fa-download me-1"></i> Get Cert
                                                            </button>
                                                        <?php else: ?>
                                                            <span class="text-muted small">Score 70% to earn</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Hazard Panel -->
                        <div class="tab-pane fade" id="hazard" role="tabpanel" aria-labelledby="hazard-tab">
                            <h5 class="text-white mb-3">Hazardous Road Reports</h5>
                            
                            <?php if (empty($reports)): ?>
                                <div class="text-center py-5 text-secondary">
                                    <i class="fa-solid fa-map-location-dot fs-1 mb-3 text-muted"></i>
                                    <p>You haven't reported any road hazards yet.</p>
                                    <a href="<?php echo BASE_PATH; ?>pages/report.php" class="btn btn-outline-info">Report Road Hazard</a>
                                </div>
                            <?php else: ?>
                                <div class="row">
                                    <?php foreach ($reports as $r): ?>
                                        <div class="col-md-6 mb-3">
                                            <div class="card bg-dark border-secondary text-white h-100 shadow-sm">
                                                <div class="card-body d-flex flex-column justify-content-between">
                                                    <div>
                                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                                            <h6 class="fw-bold text-info mb-0"><?php echo htmlspecialchars($r['title']); ?></h6>
                                                            <?php if ($r['status'] === 'resolved'): ?>
                                                                <span class="badge bg-success-subtle text-success border border-success border-opacity-20">Resolved</span>
                                                            <?php else: ?>
                                                                <span class="badge bg-warning-subtle text-warning border border-warning border-opacity-20">Pending</span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <p class="text-secondary small card-text text-truncate-3"><?php echo htmlspecialchars($r['description']); ?></p>
                                                    </div>
                                                    <div class="mt-3 pt-2 border-top border-secondary small text-muted d-flex justify-content-between">
                                                        <span><i class="fa-solid fa-location-dot me-1"></i><?php echo round($r['latitude'], 4); ?>, <?php echo round($r['longitude'], 4); ?></span>
                                                        <span><?php echo date('d M Y', strtotime($r['created_at'])); ?></span>
                                                    </div>
                                                </div>
                                            </div>
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
</div>

<!-- Certificate Preview Modal -->
<div class="modal fade" id="certModal" tabindex="-1" aria-labelledby="certModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-white font-heading" id="certModalLabel"><i class="fa-solid fa-certificate text-warning me-2"></i>Road Safety Certificate</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center bg-dark py-4">
                <!-- Canvas drawing the certificate -->
                <canvas id="certCanvas" width="800" height="560" class="certificate-preview mb-3"></canvas>
                <p class="text-secondary small">This certificate certifies that the user successfully completed and passed the SafeRoads comprehensive rules evaluation.</p>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info btn-sm text-dark fw-bold" onclick="downloadCert()">
                    <i class="fa-solid fa-download me-1"></i>Download Certificate
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Canvas Certificate Generation JS -->
<script>
let currentCertCanvas = null;

function showCertModal(name, score, date) {
    const modal = new bootstrap.Modal(document.getElementById('certModal'));
    const canvas = document.getElementById('certCanvas');
    currentCertCanvas = canvas;
    const ctx = canvas.getContext('2d');
    
    // Draw Certificate Background
    ctx.fillStyle = '#0F172A';
    ctx.fillRect(0, 0, 800, 560);
    
    // Draw Borders
    ctx.strokeStyle = '#00F2FE';
    ctx.lineWidth = 10;
    ctx.strokeRect(15, 15, 770, 530);
    
    ctx.strokeStyle = '#F59E0B';
    ctx.lineWidth = 2;
    ctx.strokeRect(25, 25, 750, 510);
    
    // Draw Corner Decorations
    ctx.fillStyle = '#F59E0B';
    // Top-Left
    ctx.fillRect(30, 30, 20, 20);
    // Top-Right
    ctx.fillRect(750, 30, 20, 20);
    // Bottom-Left
    ctx.fillRect(30, 510, 20, 20);
    // Bottom-Right
    ctx.fillRect(750, 510, 20, 20);
    
    // Title
    ctx.fillStyle = '#00F2FE';
    ctx.font = 'bold 36px Outfit, sans-serif';
    ctx.textAlign = 'center';
    ctx.fillText('CERTIFICATE OF ACHIEVEMENT', 400, 110);
    
    ctx.fillStyle = '#94A3B8';
    ctx.font = '16px Inter, sans-serif';
    ctx.fillText('THIS IS PROUDLY PRESENTED TO', 400, 170);
    
    // Recipient Name
    ctx.fillStyle = '#FFFFFF';
    ctx.font = 'italic bold 38px Outfit, sans-serif';
    ctx.fillText(name, 400, 230);
    
    // Divider
    ctx.strokeStyle = '#334155';
    ctx.lineWidth = 1;
    ctx.beginPath();
    ctx.moveTo(250, 260);
    ctx.lineTo(550, 260);
    ctx.stroke();
    
    // Description Text
    ctx.fillStyle = '#94A3B8';
    ctx.font = '18px Inter, sans-serif';
    ctx.fillText('for successfully passing the SafeRoads comprehensive evaluation on', 400, 300);
    ctx.fillText('Road Safety Rules, Accident Prevention, and Emergency Response Protocols', 400, 330);
    
    // Score Badge
    ctx.fillStyle = '#10B981';
    ctx.font = 'bold 22px Outfit, sans-serif';
    ctx.fillText('PASSED WITH A SCORE OF ' + score + ' / 8', 400, 385);
    
    // Date & Signature Lanes
    // Date
    ctx.fillStyle = '#64748B';
    ctx.font = '14px Inter, sans-serif';
    ctx.fillText('Date: ' + date, 250, 470);
    ctx.beginPath();
    ctx.moveTo(170, 450);
    ctx.lineTo(330, 450);
    ctx.strokeStyle = '#475569';
    ctx.stroke();
    
    // Signature
    ctx.fillStyle = '#64748B';
    ctx.font = '14px Inter, sans-serif';
    ctx.fillText('SafeRoads Director', 550, 470);
    ctx.fillStyle = '#FFFFFF';
    ctx.font = 'italic 20px "Great Vibes", cursive, sans-serif';
    ctx.fillText('SafeRoads Authority', 550, 435);
    ctx.beginPath();
    ctx.moveTo(470, 450);
    ctx.lineTo(630, 450);
    ctx.strokeStyle = '#475569';
    ctx.stroke();
    
    modal.show();
}

function downloadCert() {
    if (!currentCertCanvas) return;
    const link = document.createElement('a');
    link.download = 'saferoads_certificate.png';
    link.href = currentCertCanvas.toDataURL();
    link.click();
}
</script>

<?php include_once '../includes/footer.php'; ?>
