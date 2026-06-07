<?php
include_once '../includes/header.php';

$accidents = [
    [
        'title' => 'Head-on Collision',
        'icon' => 'fa-solid fa-car-burst text-danger',
        'causes' => 'Wrong-side driving, drifting out of lane, or reckless overtaking on two-lane undivided roads.',
        'example' => 'A driver attempts to overtake a slow-moving truck on a curve, enters the opposite lane, and collides with an oncoming car.',
        'prevention' => 'Never overtake on curves, blind spots, or bridges. Only overtake when double yellow center lines are broken and the road ahead is completely visible.'
    ],
    [
        'title' => 'Rear-end Collision',
        'icon' => 'fa-solid fa-truck-monster text-warning',
        'causes' => 'Tailgating, sudden braking, distracted driving, or weather-induced slippery roads.',
        'example' => 'A vehicle slams on the brakes due to a pedestrian, and the tailgating car behind slams into its bumper because it did not have reaction space.',
        'prevention' => 'Always maintain the 3-second rule following distance. Increase this margin to 6 seconds in rainy, wet, or foggy weather.'
    ],
    [
        'title' => 'Side-impact Collision (T-Bone)',
        'icon' => 'fa-solid fa-border-all text-info',
        'causes' => 'Jumping red lights, failing to yield at unsignalled intersections, or ignoring stop signs.',
        'example' => 'A driver speeds up to beat a turning yellow light and broadsides a turning SUV that already had the right of way.',
        'prevention' => 'Slow down when approaching intersections. Stop completely at STOP signs and yield to vehicles inside the roundabout before merging.'
    ],
    [
        'title' => 'Rollover Accident',
        'icon' => 'fa-solid fa-arrows-spin text-danger',
        'causes' => 'Overspeeding on sharp curves, sudden swerving, or high-center-of-gravity vehicles (SUVs) sliding sideways off roads.',
        'example' => 'An SUV driver traveling at high speed swerves sharply to avoid hitting an animal on a wet road, causing the vehicle to trip and roll over.',
        'prevention' => 'Reduce speed significantly before entering sharp turns or ramps. Keep tires inflated and load cargo evenly.'
    ],
    [
        'title' => 'Motorcycle Accident',
        'icon' => 'fa-solid fa-motorcycle text-warning',
        'causes' => 'Car drivers failing to spot motorcycles, speeding, lane splitting, or road debris.',
        'example' => 'A car turns left at an intersection, cutting off a motorcyclist riding straight because the car driver only checked for larger vehicles.',
        'prevention' => 'Motorcyclists should wear bright reflective clothing and keep headlights on. Never split lanes or weave through heavy traffic.'
    ],
    [
        'title' => 'Pedestrian Accident',
        'icon' => 'fa-solid fa-person-walking text-info',
        'causes' => 'Jaywalking, distracted pedestrians (using phones/headphones), overspeeding in school zones, or poor road lighting.',
        'example' => 'A pedestrian wearing dark clothing crosses a poorly lit highway at night instead of walking to the nearest pedestrian bridge, and is struck by a vehicle.',
        'prevention' => 'Pedestrians should use designated zebra crossings or subways. Drivers must slow down to 20-30 km/h in residential and school areas.'
    ],
    [
        'title' => 'Drunk Driving Accident',
        'icon' => 'fa-solid fa-wine-glass-empty text-danger',
        'causes' => 'Driving with blood alcohol concentration (BAC) above legal limits, dulling reflexes and judgment.',
        'example' => 'A driver leaves a party intoxicated, fails to see a sharp bend in the road, drives off the cliff edge, and crashes.',
        'prevention' => 'Zero tolerance for drinking and driving. Designate a sober driver, book a taxi, or use public transport if you consume alcohol.'
    ],
    [
        'title' => 'Overspeeding Accident',
        'icon' => 'fa-solid fa-gauge-high text-danger',
        'causes' => 'Driving above speed limits, underestimating stopping distance, or drag racing.',
        'example' => 'A sports car racing on a highway fails to stop in time when traffic slows down ahead, resulting in a multi-vehicle pileup.',
        'prevention' => 'Respect the posted speed limits. Understand that speed limits are for ideal conditions; drive slower in rain, snow, or fog.'
    ],
    [
        'title' => 'Distracted Driving Accident',
        'icon' => 'fa-solid fa-mobile-button text-warning',
        'causes' => 'Texting, calls, setting GPS routes, eating, or turning to talk to passengers while driving.',
        'example' => 'A driver looks down at their phone to read a text message, drifts off the road, and collides with a concrete electrical pole.',
        'prevention' => 'Put your phone on "Do Not Disturb" mode while driving. Set your GPS destination and adjust music before starting the ignition.'
    ],
    [
        'title' => 'Night-time Accident',
        'icon' => 'fa-solid fa-moon text-purple',
        'causes' => 'Poor visibility, driver fatigue (drowsiness), blinding high beams, or animals crossing roads.',
        'example' => 'A tired driver falls asleep at the wheel at 3 AM, drifting into the opposite lane and colliding with a long-haul commercial truck.',
        'prevention' => 'Ensure headlights and wipers are clean. If feeling drowsy, pull over at a service station immediately and sleep. Dim high beams for oncoming cars.'
    ]
];
?>

<div class="container py-4">
    <div class="row mb-5">
        <div class="col-lg-8 mx-auto text-center">
            <h1 class="display-4 font-heading text-white mb-3">Types of Road Accidents</h1>
            <p class="lead text-secondary">Analyzing the leading causes of traffic incidents, real-life hazard scenarios, and methods to prevent them.</p>
            <hr class="border-info border-opacity-50 w-25 mx-auto">
        </div>
    </div>
    
    <div class="row g-4">
        <?php foreach ($accidents as $index => $acc): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card glass-card p-3 h-100 border-info border-opacity-10 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="fs-2 me-3">
                                <i class="<?php echo $acc['icon']; ?>"></i>
                            </div>
                            <h4 class="text-white font-heading m-0"><?php echo htmlspecialchars($acc['title']); ?></h4>
                        </div>
                        
                        <div class="mb-3">
                            <h6 class="text-info font-heading mb-1"><i class="fa-solid fa-circle-question me-1 text-muted"></i> Primary Causes:</h6>
                            <p class="text-secondary small mb-0"><?php echo htmlspecialchars($acc['causes']); ?></p>
                        </div>
                        
                        <div class="mb-3 p-2 bg-dark bg-opacity-50 rounded border border-secondary border-opacity-20">
                            <h6 class="text-warning font-heading mb-1" style="font-size: 0.85rem;"><i class="fa-solid fa-triangle-exclamation me-1"></i> Real-Life Scenario:</h6>
                            <p class="text-muted small mb-0"><em>"<?php echo htmlspecialchars($acc['example']); ?>"</em></p>
                        </div>
                    </div>
                    
                    <div class="pt-2 border-top border-secondary border-opacity-30">
                        <h6 class="text-success font-heading mb-1"><i class="fa-solid fa-shield-halved me-1 text-muted"></i> How to Prevent:</h6>
                        <p class="text-secondary small mb-0"><?php echo htmlspecialchars($acc['prevention']); ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <div class="row mt-5">
        <div class="col-12 text-center">
            <div class="card glass-card p-4 border-warning border-opacity-20 bg-warning-subtle text-dark">
                <h4 class="fw-bold mb-2 text-warning-emphasis"><i class="fa-solid fa-circle-exclamation me-2"></i>Speed Kills!</h4>
                <p class="m-0 text-dark-50">
                    A pedestrian hit by a car at 30 km/h has a 90% chance of survival. Hit at 50 km/h, the survival rate drops to less than 15%. Always respect city speed limits to protect walkers.
                </p>
            </div>
        </div>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>
