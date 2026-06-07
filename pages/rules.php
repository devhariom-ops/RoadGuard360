<?php
include_once '../includes/header.php';
?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col-lg-8 mx-auto text-center">
            <h1 class="display-4 font-heading text-white mb-2">Traffic Rules Learning Center</h1>
            <p class="text-secondary">Master the essential road regulations to protect yourself and others. Click each category below to learn more.</p>
            
            <!-- Voice reading button -->
            <button class="btn btn-outline-purple btn-sm mt-2" onclick="speakText('Learn essential traffic rules on SafeRoads. Select a category below.')">
                <i class="fa-solid fa-volume-high me-1"></i> Read Page Overview
            </button>
        </div>
    </div>
    
    <div class="row justify-content-center mb-5">
        <div class="col-lg-10">
            <!-- Tabs Navigation -->
            <ul class="nav nav-pills justify-content-center mb-4" id="rulesTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active me-2 mb-2" id="pedestrian-tab" data-bs-toggle="pill" data-bs-target="#pedestrian" type="button" role="tab" aria-controls="pedestrian" aria-selected="true">
                        <i class="fa-solid fa-person-walking me-2"></i>Pedestrian Rules
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link me-2 mb-2" id="twowheeler-tab" data-bs-toggle="pill" data-bs-target="#twowheeler" type="button" role="tab" aria-controls="twowheeler" aria-selected="false">
                        <i class="fa-solid fa-motorcycle me-2"></i>Two-Wheeler Rules
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link me-2 mb-2" id="fourwheeler-tab" data-bs-toggle="pill" data-bs-target="#fourwheeler" type="button" role="tab" aria-controls="fourwheeler" aria-selected="false">
                        <i class="fa-solid fa-car me-2"></i>Four-Wheeler Rules
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link mb-2" id="highway-tab" data-bs-toggle="pill" data-bs-target="#highway" type="button" role="tab" aria-controls="highway" aria-selected="false">
                        <i class="fa-solid fa-road me-2"></i>Highway Rules
                    </button>
                </li>
            </ul>
            
            <!-- Tabs Content -->
            <div class="tab-content card glass-card p-4 border-info border-opacity-10" id="rulesTabContent">
                
                <!-- Pedestrian Tab -->
                <div class="tab-pane fade show active" id="pedestrian" role="tabpanel" aria-labelledby="pedestrian-tab">
                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom border-secondary pb-3">
                        <h4 class="text-white font-heading mb-0"><i class="fa-solid fa-person-walking text-info me-2"></i>Pedestrian Safety Regulations</h4>
                        <button class="btn btn-sm btn-outline-info" onclick="speakSection('pedestrian-content')">
                            <i class="fa-solid fa-volume-high me-1"></i> Listen
                        </button>
                    </div>
                    <div id="pedestrian-content" class="row g-4 text-secondary">
                        <div class="col-md-6">
                            <h5 class="text-white font-heading mb-2"><i class="fa-solid fa-square-person-confined text-info me-2"></i>Zebra Crossing Rules</h5>
                            <p>Pedestrians must always cross the road only at designated Zebra Crossings or pedestrian subways. When approaching a zebra crossing, wait on the curb until vehicles stop. Never step onto the crossing if cars are too close to halt safely.</p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-white font-heading mb-2"><i class="fa-solid fa-traffic-light text-warning me-2"></i>Walking Signals</h5>
                            <p>Always obey the pedestrian signals. A green walking figure indicates you may cross when traffic stops. A flashing or steady red figure means do not start crossing, as vehicles have the green light.</p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-white font-heading mb-2"><i class="fa-solid fa-road-barrier text-success me-2"></i>Sidewalk Walkways</h5>
                            <p>Always walk on sidewalks where available. If there is no sidewalk, walk on the right edge of the road facing oncoming traffic. This allows you to see approaching vehicles and step out of the way if needed.</p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-white font-heading mb-2"><i class="fa-solid fa-eye text-danger me-2"></i>Be Visible at Night</h5>
                            <p>Wear light-colored or reflective clothing when walking at night. Avoid sudden entries onto roads from behind parked vehicles or large objects, as drivers will not have time to react.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Two-Wheeler Tab -->
                <div class="tab-pane fade" id="twowheeler" role="tabpanel" aria-labelledby="twowheeler-tab">
                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom border-secondary pb-3">
                        <h4 class="text-white font-heading mb-0"><i class="fa-solid fa-motorcycle text-warning me-2"></i>Two-Wheeler Rules (Motorcycles & Scooters)</h4>
                        <button class="btn btn-sm btn-outline-info" onclick="speakSection('twowheeler-content')">
                            <i class="fa-solid fa-volume-high me-1"></i> Listen
                        </button>
                    </div>
                    <div id="twowheeler-content" class="row g-4 text-secondary">
                        <div class="col-md-6">
                            <h5 class="text-white font-heading mb-2"><i class="fa-solid fa-helmet-safety text-danger me-2"></i>Mandatory Helmet Law</h5>
                            <p>Both the rider and the pillion passenger must wear certified, high-quality safety helmets. Ensure the chinstrap is securely buckled. An unbuckled helmet can fly off before impact, offering zero head protection.</p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-white font-heading mb-2"><i class="fa-solid fa-gauge-high text-warning me-2"></i>Speed Limits & Control</h5>
                            <p>Observe lower speed limits designated for two-wheelers, typically 40-50 km/h in urban areas. Motorcycles have less traction and stability, and speeding increases the risk of skidding, especially on wet roads.</p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-white font-heading mb-2"><i class="fa-solid fa-users-slash text-success me-2"></i>Double Riding Limit</h5>
                            <p>Only one pillion passenger is permitted. Overloading a two-wheeler with multiple passengers or bulky luggage severely affects stability, balancing, braking distance, and turning dynamics.</p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-white font-heading mb-2"><i class="fa-solid fa-eye-slash text-info me-2"></i>Stay Out of Blind Spots</h5>
                            <p>Avoid riding closely behind large trucks, containers, or buses. If you cannot see the truck's side mirrors, the truck driver cannot see you. Use headlights and horns to indicate your presence.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Four-Wheeler Tab -->
                <div class="tab-pane fade" id="fourwheeler" role="tabpanel" aria-labelledby="fourwheeler-tab">
                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom border-secondary pb-3">
                        <h4 class="text-white font-heading mb-0"><i class="fa-solid fa-car text-info me-2"></i>Four-Wheeler Rules (Cars & SUVs)</h4>
                        <button class="btn btn-sm btn-outline-info" onclick="speakSection('fourwheeler-content')">
                            <i class="fa-solid fa-volume-high me-1"></i> Listen
                        </button>
                    </div>
                    <div id="fourwheeler-content" class="row g-4 text-secondary">
                        <div class="col-md-6">
                            <h5 class="text-white font-heading mb-2"><i class="fa-solid fa-person-circle-check text-success me-2"></i>Seat Belt Mandatory</h5>
                            <p>All occupants inside the vehicle, including back-seat passengers, must wear seat belts while the car is moving. During a crash, seat belts hold passengers in position, preventing impact with the dashboard/windshield or ejection from the vehicle.</p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-white font-heading mb-2"><i class="fa-solid fa-left-right text-info me-2"></i>Indicator & Lane Changing</h5>
                            <p>Always activate indicators (blinker lights) at least 3 seconds before changing lanes, overtaking, or turning. Check your mirrors and blind spots carefully before steering.</p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-white font-heading mb-2"><i class="fa-solid fa-ban text-danger me-2"></i>Safe Overtaking</h5>
                            <p>Overtake only from the right side. Never overtake on narrow bridges, sharp turns, blind curves, or when solid double yellow center lines are marked on the asphalt.</p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-white font-heading mb-2"><i class="fa-solid fa-mobile-button text-warning me-2"></i>Zero Mobile Usage</h5>
                            <p>Never hold a phone, text, or browse while driving. If you must answer an urgent call, pull over safely to the left edge of the road and switch off the ignition.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Highway Tab -->
                <div class="tab-pane fade" id="highway" role="tabpanel" aria-labelledby="highway-tab">
                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom border-secondary pb-3">
                        <h4 class="text-white font-heading mb-0"><i class="fa-solid fa-road text-success me-2"></i>Expressway & Highway Rules</h4>
                        <button class="btn btn-sm btn-outline-info" onclick="speakSection('highway-content')">
                            <i class="fa-solid fa-volume-high me-1"></i> Listen
                        </button>
                    </div>
                    <div id="highway-content" class="row g-4 text-secondary">
                        <div class="col-md-6">
                            <h5 class="text-white font-heading mb-2"><i class="fa-solid fa-arrows-up-down text-info me-2"></i>Lane Discipline</h5>
                            <p>Highways have designated lane speeds. Keep left for slower speeds and heavy trucks. Use the center lane for cruising, and reservation of the rightmost lane is STRICTLY for overtaking. Never weave in and out of lanes.</p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-white font-heading mb-2"><i class="fa-solid fa-truck-medical text-danger me-2"></i>Emergency Lane (Hard Shoulder)</h5>
                            <p>The emergency shoulder on the far left is strictly reserved for broke-down vehicles, emergency vehicles, or police. Driving on it to skip traffic is illegal and highly dangerous.</p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-white font-heading mb-2"><i class="fa-solid fa-circle-exclamation text-warning me-2"></i>Safe Following Distance (100km/h+)</h5>
                            <p>At high speeds, stopping distances are huge. Apply the 3-second rule. Under wet, foggy, or night conditions, increase this to 6-8 seconds to allow enough reaction space.</p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-white font-heading mb-2"><i class="fa-solid fa-moon text-purple me-2"></i>Highway Hypnosis</h5>
                            <p>Monotonous highway driving causes fatigue and a trance-like state known as highway hypnosis. Take a 15-minute rest break every 2 hours or 150 km to refresh your reflexes.</p>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

<script>
// Text-to-speech rule narrator
function speakText(text) {
    if ('speechSynthesis' in window) {
        // Stop any running speech first
        window.speechSynthesis.cancel();
        
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.rate = 0.95;
        window.speechSynthesis.speak(utterance);
    } else {
        alert("Text-to-speech is not supported in this browser.");
    }
}

function speakSection(sectionId) {
    const section = document.getElementById(sectionId);
    if (!section) return;
    
    // Parse text inside the section, filtering out headers slightly to read nicely
    let speakStr = "";
    const headers = section.querySelectorAll('h5');
    const paras = section.querySelectorAll('p');
    
    for (let i = 0; i < paras.length; i++) {
        const title = headers[i] ? headers[i].textContent : "";
        const body = paras[i] ? paras[i].textContent : "";
        speakStr += title + ". " + body + " ";
    }
    
    speakText(speakStr);
}
</script>

<?php include_once '../includes/footer.php'; ?>
