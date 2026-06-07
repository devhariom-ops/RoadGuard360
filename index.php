<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Set path prefix to empty since we are at root
define('BASE_PATH', '');
include_once 'includes/header.php';
?>

<!-- 3D Hero Section -->
<section class="hero-section text-center text-md-start">
    <canvas id="heroCanvas" class="hero-canvas"></canvas>
    <div class="container hero-container">
        <div class="row align-items-center">
            <div class="col-lg-7 text-white">
                <span class="badge bg-danger mb-3 px-3 py-2 text-uppercase fw-bold letter-spacing-1">
                    <i class="fa-solid fa-triangle-exclamation me-1"></i> Road Safety Awareness
                </span>
                <h1 class="display-3 fw-extrabold mb-3 text-shadow font-heading" data-lang-key="hero_title">
                    Riding Responsibly Saves Lives.
                </h1>
                <p class="lead text-secondary mb-4 col-md-10" data-lang-key="hero_desc">
                    SafeRoads is an interactive 3D platform designed to educate, test, and empower drivers and pedestrians. Dive into virtual traffic simulators, master signs, and earn your road safety certification.
                </p>
                <div class="d-flex flex-column flex-sm-row gap-3">
                    <a href="pages/simulator.php" class="btn btn-info btn-lg text-dark fw-bold" data-lang-key="hero_cta_sim">
                        <i class="fa-solid fa-cube me-2"></i>Try 3D Simulator
                    </a>
                    <a href="pages/quiz.php" class="btn btn-outline-info btn-lg text-white" data-lang-key="hero_cta_quiz">
                        <i class="fa-solid fa-graduation-cap me-2"></i>Take Safety Quiz
                    </a>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block">
                <!-- Visual highlight or secondary card -->
                <div class="card glass-card p-4 border-info border-opacity-10">
                    <div class="card-body text-center">
                        <div class="logo-icon mb-3">
                            <i class="fa-solid fa-shield-halved text-info" style="font-size: 60px;"></i>
                        </div>
                        <h4 class="text-white fw-bold mb-2">Join the Movement</h4>
                        <p class="text-secondary small mb-4">
                            Help us reduce global traffic fatalities. Take the pledge, learn safety regulations, and report hazardous streets in your city.
                        </p>
                        <div class="d-flex justify-content-around text-start border-top border-secondary pt-3">
                            <div>
                                <h6 class="text-info fw-bold mb-0">100%</h6>
                                <small class="text-muted">Interactive</small>
                            </div>
                            <div>
                                <h6 class="text-info fw-bold mb-0">Free</h6>
                                <small class="text-muted">Open Education</small>
                            </div>
                            <div>
                                <h6 class="text-info fw-bold mb-0">Official</h6>
                                <small class="text-muted">Certificate</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Counters -->
<section class="py-5 bg-dark border-bottom border-secondary border-opacity-20">
    <div class="container text-center">
        <h2 class="text-white mb-5 font-heading text-center" data-lang-key="section_stats_title">The Grim Reality of Road Accidents</h2>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="card glass-card stat-card h-100">
                    <div class="stat-number" data-target="3200">0</div>
                    <div class="text-secondary font-heading fw-semibold mb-2" data-lang-key="stats_daily">Daily Accidents</div>
                    <p class="text-muted small m-0">Occurring globally on roads every single day.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card glass-card stat-card h-100">
                    <div class="stat-number" data-target="1350000">0</div>
                    <div class="text-secondary font-heading fw-semibold mb-2" data-lang-key="stats_fatal">Fatal Accidents</div>
                    <p class="text-muted small m-0">Lives lost in road collisions each year.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card glass-card stat-card h-100">
                    <div class="stat-number" data-target="50000000">0</div>
                    <div class="text-secondary font-heading fw-semibold mb-2" data-lang-key="stats_injuries">Injuries Per Year</div>
                    <p class="text-muted small m-0">Resulting in physical disabilities and pain.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card glass-card stat-card h-100">
                    <div class="stat-number" data-target="120000">0</div>
                    <div class="text-secondary font-heading fw-semibold mb-2" data-lang-key="stats_saved">Lives Saved (Helmets)</div>
                    <p class="text-muted small m-0">Saved globally through consistent helmet wear.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Core Features Grid -->
<section class="py-5">
    <div class="container">
        <h3 class="text-white font-heading text-center mb-5">Features & Tools</h3>
        <div class="row g-4">
            
            <!-- Card 1 -->
            <div class="col-md-6 col-lg-4">
                <div class="card glass-card p-3 h-100">
                    <div class="card-body">
                        <div class="text-info fs-3 mb-3"><i class="fa-solid fa-cube"></i></div>
                        <h5 class="text-white font-heading fw-bold mb-2">3D Awareness Zone</h5>
                        <p class="text-secondary small mb-4">Interact with 3D traffic light panels, helmet safety tests, and simulate crash scenarios to see the physics of collisions.</p>
                        <a href="pages/simulator.php" class="text-info text-decoration-none small fw-bold">Open Simulator <i class="fa-solid fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
            
            <!-- Card 2 -->
            <div class="col-md-6 col-lg-4">
                <div class="card glass-card p-3 h-100">
                    <div class="card-body">
                        <div class="text-info fs-3 mb-3"><i class="fa-solid fa-traffic-light"></i></div>
                        <h5 class="text-white font-heading fw-bold mb-2">Signs & Rules Guide</h5>
                        <p class="text-secondary small mb-4">Study warning, mandatory, and informative traffic signs modeled in 3D. Learn safety rules for pedestrians, two-wheelers, and highways.</p>
                        <a href="pages/rules.php" class="text-info text-decoration-none small fw-bold">Learn Traffic Rules <i class="fa-solid fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
            
            <!-- Card 3 -->
            <div class="col-md-6 col-lg-4">
                <div class="card glass-card p-3 h-100">
                    <div class="card-body">
                        <div class="text-info fs-3 mb-3"><i class="fa-solid fa-map-location-dot"></i></div>
                        <h5 class="text-white font-heading fw-bold mb-2">Hazard Reporting Map</h5>
                        <p class="text-secondary small mb-4">Spot a pothole, open manhole, or broken traffic light? Pin it on our map and upload an image to alert the authorities and general drivers.</p>
                        <a href="pages/report.php" class="text-info text-decoration-none small fw-bold">Report Danger Zone <i class="fa-solid fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
            
            <!-- Card 4 -->
            <div class="col-md-6 col-lg-4">
                <div class="card glass-card p-3 h-100">
                    <div class="card-body">
                        <div class="text-info fs-3 mb-3"><i class="fa-solid fa-user-shield"></i></div>
                        <h5 class="text-white font-heading fw-bold mb-2">Quiz & Licensing Prep</h5>
                        <p class="text-secondary small mb-4">Test your knowledge with our MCQ evaluation covering road safety, laws, and signs. Score 70% or more to download your achievement certificate.</p>
                        <a href="pages/quiz.php" class="text-info text-decoration-none small fw-bold">Start Evaluation <i class="fa-solid fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
            
            <!-- Card 5 -->
            <div class="col-md-6 col-lg-4">
                <div class="card glass-card p-3 h-100">
                    <div class="card-body">
                        <div class="text-info fs-3 mb-3"><i class="fa-solid fa-calculator"></i></div>
                        <h5 class="text-white font-heading fw-bold mb-2">Risk Prediction Calculator</h5>
                        <p class="text-secondary small mb-4">Use our safety prediction algorithm to estimate your accident risk percentage based on speed, weather, and road conditions.</p>
                        <a href="pages/simulator.php#risk-calculator-section" class="text-info text-decoration-none small fw-bold">Calculate Risk <i class="fa-solid fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
            
            <!-- Card 6 -->
            <div class="col-md-6 col-lg-4">
                <div class="card glass-card p-3 h-100">
                    <div class="card-body">
                        <div class="text-info fs-3 mb-3"><i class="fa-solid fa-truck-medical"></i></div>
                        <h5 class="text-white font-heading fw-bold mb-2">Emergency Hub</h5>
                        <p class="text-secondary small mb-4">One-click emergency helpline dialing, local hospital directions on an OSM map, and essential first aid guides for road crash victims.</p>
                        <a href="pages/emergency.php" class="text-info text-decoration-none small fw-bold">Emergency Center <i class="fa-solid fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>

<!-- Campaign Showcase (Banner) -->
<section class="py-5 bg-gradient-info text-dark">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h3 class="fw-bold mb-3 font-heading"><i class="fa-solid fa-hand-holding-heart me-2 text-danger"></i>SafeRoads Awareness Campaign</h3>
                <h4 class="display-6 fw-bold mb-4">"Zero Alcohol, Zero Speeding, Zero Deaths"</h4>
                <p class="mb-4 fw-medium text-dark-50">
                    Accidents are not inevitable occurrences. 93% of all traffic collisions occur due to preventable human errors, including distracted driving, drunk driving, overspeeding, and failure to use seatbelts or helmets. By changing our behaviors, we can bring road deaths down to zero.
                </p>
                <div class="d-flex flex-wrap gap-2 justify-content-center">
                    <span class="badge bg-dark text-white px-3 py-2">Wear Helmets</span>
                    <span class="badge bg-dark text-white px-3 py-2">Buckle Up</span>
                    <span class="badge bg-dark text-white px-3 py-2">Don't Text & Drive</span>
                    <span class="badge bg-dark text-white px-3 py-2">Observe Speed Limits</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Background Road Loop JS Trigger -->
<script>
// Trigger script for the 3D road loop hero animation on page load
window.addEventListener('DOMContentLoaded', () => {
    if (typeof initHero3DScene === 'function') {
        initHero3DScene('heroCanvas');
    }
});
</script>

<?php include_once 'includes/footer.php'; ?>
