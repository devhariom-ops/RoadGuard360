<?php
include_once '../includes/header.php';
?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col-lg-8 mx-auto text-center">
            <h1 class="display-4 font-heading text-white mb-2">3D Awareness & Learning Zone</h1>
            <p class="text-secondary">Interact with virtual physics simulators to see the direct results of traffic signals, helmet wear, seatbelts, and reckless driving behavior.</p>
        </div>
    </div>
    
    <!-- Row 1: Traffic Lights & Accident Simulator -->
    <div class="row g-4 mb-5">
        
        <!-- 3D Traffic Signal Simulator -->
        <div class="col-lg-5">
            <div class="card glass-card p-3 h-100 border-info border-opacity-10 d-flex flex-column justify-content-between">
                <div>
                    <h4 class="text-white font-heading mb-3"><i class="fa-solid fa-traffic-light text-info me-2"></i>Traffic Light Simulator</h4>
                    <div class="canvas-container mb-3" style="height: 300px;">
                        <canvas id="trafficSignalCanvas" class="w-100 h-100"></canvas>
                    </div>
                </div>
                <div>
                    <!-- Controls -->
                    <div class="d-flex justify-content-center gap-2 mb-3">
                        <button class="btn btn-danger btn-sm px-3 fw-bold" onclick="setTrafficLightState('red')">RED</button>
                        <button class="btn btn-warning btn-sm px-3 fw-bold text-dark" onclick="setTrafficLightState('yellow')">YELLOW</button>
                        <button class="btn btn-success btn-sm px-3 fw-bold" onclick="setTrafficLightState('green')">GREEN</button>
                    </div>
                    <!-- Explanation -->
                    <div id="signalExplanation" class="p-3 bg-dark bg-opacity-60 rounded border border-secondary border-opacity-20 text-secondary" style="min-height: 100px;">
                        <!-- Updated by JS -->
                    </div>
                </div>
            </div>
        </div>
        
        <!-- 3D Accident Simulation -->
        <div class="col-lg-7">
            <div class="card glass-card p-3 h-100 border-info border-opacity-10 d-flex flex-column justify-content-between">
                <div>
                    <h4 class="text-white font-heading mb-3"><i class="fa-solid fa-car-burst text-danger me-2"></i>3D Accident Simulation</h4>
                    <div class="canvas-container mb-3" style="height: 300px;">
                        <canvas id="accidentSimCanvas" class="w-100 h-100"></canvas>
                        
                        <!-- Phone overlay for distracted texting simulation -->
                        <div id="phoneOverlay" class="d-none position-absolute top-50 start-50 translate-middle p-3 bg-dark border border-secondary rounded shadow text-center text-white" style="width: 200px; z-index: 10;">
                            <div class="card-header bg-gradient-info text-dark font-heading fw-bold py-1 px-2 mb-2 rounded" style="font-size: 0.75rem;">Messages</div>
                            <div class="text-start bg-secondary bg-opacity-35 p-2 rounded small mb-2" style="font-size: 0.7rem;">
                                <strong>Friend:</strong> "Bro where are you? Let's meet at..."
                            </div>
                            <small class="text-warning-emphasis fw-bold blink-text" style="font-size: 0.65rem;"><i class="fa-solid fa-eye-slash me-1"></i>Driver is looking down!</small>
                        </div>
                    </div>
                </div>
                <div>
                    <!-- Controls -->
                    <div class="d-flex flex-wrap justify-content-center gap-2 mb-3">
                        <button class="btn btn-outline-danger btn-sm fw-bold" onclick="runAccidentSimulation('overspeeding')">Overspeeding</button>
                        <button class="btn btn-outline-warning btn-sm fw-bold" onclick="runAccidentSimulation('drunk')">Drunk Driving</button>
                        <button class="btn btn-outline-warning btn-sm fw-bold" onclick="runAccidentSimulation('distracted')">Texting & Driving</button>
                        <button class="btn btn-outline-success btn-sm fw-bold" onclick="runAccidentSimulation('safe')">Safe Driving</button>
                    </div>
                    <!-- Explanation -->
                    <div id="accidentExplanation" class="p-3 bg-dark bg-opacity-60 rounded border border-secondary border-opacity-20 text-secondary" style="min-height: 100px;">
                        <h6 class="text-info fw-bold m-0"><i class="fa-solid fa-circle-play"></i> Select a driving mode above</h6>
                        <p class="text-secondary small m-0">Simulate how speeding, drunk driving, cellular distractions, and defensive safety controls affect collision physics.</p>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    
    <!-- Row 2: Helmet safety & Seatbelt simulator -->
    <div class="row g-4 mb-5">
        
        <!-- 3D Helmet Safety Demo -->
        <div class="col-lg-6">
            <div class="card glass-card p-3 h-100 border-info border-opacity-10 d-flex flex-column justify-content-between">
                <div>
                    <h4 class="text-white font-heading mb-3"><i class="fa-solid fa-helmet-safety text-danger me-2"></i>3D Helmet Safety Demo</h4>
                    <div class="canvas-container mb-3" style="height: 300px;">
                        <canvas id="helmetSimCanvas" class="w-100 h-100"></canvas>
                    </div>
                </div>
                <div>
                    <!-- Controls -->
                    <div class="d-flex justify-content-center mb-3">
                        <button class="btn btn-info text-dark fw-bold btn-sm px-4" onclick="runHelmetDropTest()">
                            <i class="fa-solid fa-circle-chevron-down me-1"></i> Drop Head Impact Test
                        </button>
                    </div>
                    <!-- Explanation -->
                    <div id="helmetExplanation" class="p-3 bg-dark bg-opacity-60 rounded border border-secondary border-opacity-20 text-secondary" style="min-height: 100px;">
                        <h6 class="text-info fw-bold m-0"><i class="fa-solid fa-circle-play"></i> Start drop test</h6>
                        <p class="text-secondary small m-0">Compare the impact forces exerted on a bare skull vs a helmeted head falling from a height of 3 meters.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- 3D Seatbelt Simulation -->
        <div class="col-lg-6">
            <div class="card glass-card p-3 h-100 border-info border-opacity-10 d-flex flex-column justify-content-between">
                <div>
                    <h4 class="text-white font-heading mb-3"><i class="fa-solid fa-person-circle-check text-success me-2"></i>3D Seatbelt Simulation</h4>
                    <div class="canvas-container mb-3" style="height: 300px;">
                        <canvas id="seatbeltSimCanvas" class="w-100 h-100"></canvas>
                    </div>
                </div>
                <div>
                    <!-- Controls -->
                    <div class="d-flex justify-content-center gap-2 mb-3">
                        <button class="btn btn-outline-danger btn-sm fw-bold" onclick="runSeatbeltSimulation('unbuckled')">Crash (No Seatbelt)</button>
                        <button class="btn btn-outline-success btn-sm fw-bold" onclick="runSeatbeltSimulation('buckled')">Crash (With Seatbelt)</button>
                    </div>
                    <!-- Explanation -->
                    <div id="seatbeltExplanation" class="p-3 bg-dark bg-opacity-60 rounded border border-secondary border-opacity-20 text-secondary" style="min-height: 100px;">
                        <h6 class="text-info fw-bold m-0"><i class="fa-solid fa-circle-play"></i> Select seatbelt state</h6>
                        <p class="text-secondary small m-0">Simulate a frontal collision at 50 km/h to see how seatbelt restraints prevent occupants from striking components.</p>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    
    <!-- Row 3: Accident Risk Prediction Calculator Section -->
    <div class="row" id="risk-calculator-section">
        <div class="col-12">
            <div class="card glass-card p-4 border-info border-opacity-10">
                <div class="row align-items-center g-4">
                    <!-- Calculator Inputs -->
                    <div class="col-lg-7">
                        <h3 class="text-white font-heading mb-3"><i class="fa-solid fa-calculator text-info me-2"></i>Accident Risk Prediction Calculator</h3>
                        <p class="text-secondary mb-4">Select driving variables to estimate safety outcomes and accident risk percentages based on regression and probability weights.</p>
                        
                        <form id="riskCalcForm" onchange="calculateAccidentRisk()">
                            <div class="row g-3">
                                <!-- Speed -->
                                <div class="col-md-6 mb-2">
                                    <label for="calcSpeed" class="form-label text-secondary d-flex justify-content-between">
                                        <span>Driving Speed (km/h)</span>
                                        <span id="speedValueLabel" class="text-info fw-bold">60 km/h</span>
                                    </label>
                                    <input type="range" class="form-range" id="calcSpeed" min="10" max="150" value="60" oninput="document.getElementById('speedValueLabel').textContent = this.value + ' km/h'">
                                </div>
                                
                                <!-- Weather -->
                                <div class="col-md-6 mb-2">
                                    <label for="calcWeather" class="form-label text-secondary">Weather Condition</label>
                                    <select class="form-select bg-dark text-white border-secondary" id="calcWeather">
                                        <option value="clear">Clear Skies</option>
                                        <option value="rain">Heavy Rain</option>
                                        <option value="fog">Foggy (Low Visibility)</option>
                                        <option value="snow">Snow/Ice</option>
                                    </select>
                                </div>
                                
                                <!-- Vehicle Type -->
                                <div class="col-md-6 mb-2">
                                    <label for="calcVehicle" class="form-label text-secondary">Vehicle Type</label>
                                    <select class="form-select bg-dark text-white border-secondary" id="calcVehicle">
                                        <option value="sedan">Sedan / Hatchback</option>
                                        <option value="twowheeler">Two-Wheeler (Motorcycle)</option>
                                        <option value="suv">SUV / Off-roader</option>
                                        <option value="truck">Heavy Commercial Truck</option>
                                    </select>
                                </div>
                                
                                <!-- Road Condition -->
                                <div class="col-md-6 mb-2">
                                    <label for="calcRoad" class="form-label text-secondary">Road Surface Condition</label>
                                    <select class="form-select bg-dark text-white border-secondary" id="calcRoad">
                                        <option value="dry">Dry & Smooth</option>
                                        <option value="wet">Slippery & Wet</option>
                                        <option value="potholes">Potholes & Damaged</option>
                                        <option value="construction">Under Construction</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Calculator Output Card -->
                    <div class="col-lg-5 text-center">
                        <div class="p-4 bg-dark bg-opacity-40 rounded border border-secondary border-opacity-30">
                            <h5 class="text-white font-heading mb-4">Calculated Risk Factor</h5>
                            
                            <!-- Circular Ring Progress (Drawn in CSS/SVG) -->
                            <div class="position-relative d-inline-block mb-3" style="width: 140px; height: 140px;">
                                <svg class="w-100 h-100" viewBox="0 0 36 36">
                                    <path class="text-secondary" style="opacity: 0.1;" stroke="currentColor" stroke-width="3" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                    <path id="calcProgressRing" class="text-info" stroke="currentColor" stroke-width="3" stroke-dasharray="25, 100" stroke-linecap="round" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                </svg>
                                <div class="position-absolute top-50 start-50 translate-middle">
                                    <h2 id="calcRiskPercentage" class="m-0 fw-extrabold text-info font-heading">25%</h2>
                                    <small class="text-muted text-uppercase fw-bold" style="font-size: 9px;">Risk Ratio</small>
                                </div>
                            </div>
                            
                            <!-- Safety Badge -->
                            <div class="mb-3">
                                <span id="calcSafetyBadge" class="badge bg-success px-3 py-2 fs-6">SAFE STATUS</span>
                            </div>
                            
                            <!-- Detailed analysis -->
                            <p id="calcRecommendation" class="text-secondary small m-0">Variables reflect safe road cruising margins. Maintain present speeds.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts for launching simulators -->
<script src="<?php echo BASE_PATH; ?>assets/js/three-sims.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/risk-calc.js"></script>
<script>
window.addEventListener('DOMContentLoaded', () => {
    // Start simulations
    initTrafficSignalSim('trafficSignalCanvas');
    initAccidentSim('accidentSimCanvas');
    initHelmetSafetySim('helmetSimCanvas');
    initSeatbeltSim('seatbeltSimCanvas');
    
    // Run initial risk calculation
    calculateAccidentRisk();
});
</script>

<?php include_once '../includes/footer.php'; ?>
