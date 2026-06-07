<?php
include_once '../includes/header.php';
?>

<div class="container py-4">
    <div class="row mb-5">
        <div class="col-lg-8 mx-auto text-center">
            <h1 class="display-4 font-heading text-white mb-2">Traffic Signs & Signboards</h1>
            <p class="text-secondary">Explore warning, mandatory, and informative traffic signboards. Drag your cursor to rotate and view these signs as 3D models.</p>
        </div>
    </div>
    
    <!-- Mandatory Signs -->
    <div class="mb-5">
        <h3 class="text-white font-heading mb-4 border-bottom border-secondary pb-2">
            <span class="text-danger"><i class="fa-solid fa-circle-minus me-2"></i>Mandatory Signs</span> 
            <span class="text-muted small fs-6">(Must be followed strictly; violation is a legal offense)</span>
        </h3>
        <div class="row g-4">
            
            <div class="col-md-6 col-lg-3">
                <div class="card glass-card sign-card h-100">
                    <canvas id="signStopCanvas" class="sign-3d-canvas"></canvas>
                    <h5 class="text-white font-heading mb-1">Stop Sign</h5>
                    <p class="text-secondary small m-0">Indicates that drivers must bring their vehicles to a complete stop and yield before crossing.</p>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="card glass-card sign-card h-100">
                    <canvas id="signSpeed50Canvas" class="sign-3d-canvas"></canvas>
                    <h5 class="text-white font-heading mb-1">Speed Limit 50</h5>
                    <p class="text-secondary small m-0">Restricts maximum speed to 50 km/h under normal driving conditions in this sector.</p>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="card glass-card sign-card h-100">
                    <canvas id="signNoEntryCanvas" class="sign-3d-canvas"></canvas>
                    <h5 class="text-white font-heading mb-1">No Entry</h5>
                    <p class="text-secondary small m-0">Prohibits all vehicles from entering this lane or street. Typically marks one-way roads.</p>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="card glass-card sign-card h-100">
                    <canvas id="signHornCanvas" class="sign-3d-canvas"></canvas>
                    <h5 class="text-white font-heading mb-1">Silence Zone</h5>
                    <p class="text-secondary small m-0">Prohibits honking of horns. Placed around hospitals, schools, and courtrooms.</p>
                </div>
            </div>
            
        </div>
    </div>
    
    <!-- Warning Signs -->
    <div class="mb-5">
        <h3 class="text-white font-heading mb-4 border-bottom border-secondary pb-2">
            <span class="text-warning"><i class="fa-solid fa-triangle-exclamation me-2"></i>Cautionary & Warning Signs</span>
            <span class="text-muted small fs-6">(Alerts drivers to upcoming hazardous conditions)</span>
        </h3>
        <div class="row g-4">
            
            <div class="col-md-6 col-lg-3">
                <div class="card glass-card sign-card h-100">
                    <canvas id="warningSchoolCanvas" class="sign-3d-canvas"></canvas>
                    <h5 class="text-white font-heading mb-1">School Crossing</h5>
                    <p class="text-secondary small m-0">Warns drivers that children frequently cross ahead. Reduce speed and prepare to stop.</p>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="card glass-card sign-card h-100">
                    <canvas id="warningWorkCanvas" class="sign-3d-canvas"></canvas>
                    <h5 class="text-white font-heading mb-1">Road Construction</h5>
                    <p class="text-secondary small m-0">Indicates that road workers or excavators are operating ahead. Lane limits may narrow.</p>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="card glass-card sign-card h-100">
                    <canvas id="warningSlipCanvas" class="sign-3d-canvas"></canvas>
                    <h5 class="text-white font-heading mb-1">Slippery Road</h5>
                    <p class="text-secondary small m-0">Alerts that the asphalt surface can be slick when wet. Decelerate to avoid skidding.</p>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="card glass-card sign-card h-100">
                    <canvas id="warningGeneralCanvas" class="sign-3d-canvas"></canvas>
                    <h5 class="text-white font-heading mb-1">Danger Ahead</h5>
                    <p class="text-secondary small m-0">Alerts to non-specific hazards ahead. Be alert and scan surroundings.</p>
                </div>
            </div>
            
        </div>
    </div>
    
    <!-- Informative Signs -->
    <div class="mb-5">
        <h3 class="text-white font-heading mb-4 border-bottom border-secondary pb-2">
            <span class="text-info"><i class="fa-solid fa-square-info me-2"></i>Informative Signs</span>
            <span class="text-muted small fs-6">(Provides directions, parking, and facility locations)</span>
        </h3>
        <div class="row g-4">
            
            <div class="col-md-6 col-lg-3">
                <div class="card glass-card sign-card h-100">
                    <canvas id="infoHospitalCanvas" class="sign-3d-canvas"></canvas>
                    <h5 class="text-white font-heading mb-1">Hospital Zone</h5>
                    <p class="text-secondary small m-0">Indicates a medical hospital is nearby. Keep quiet, watch for ambulance entries.</p>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="card glass-card sign-card h-100">
                    <canvas id="infoParkingCanvas" class="sign-3d-canvas"></canvas>
                    <h5 class="text-white font-heading mb-1">Parking Lot</h5>
                    <p class="text-secondary small m-0">Marks a designated parking zone where vehicles may stop without impeding roads.</p>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="card glass-card sign-card h-100">
                    <canvas id="infoFuelCanvas" class="sign-3d-canvas"></canvas>
                    <h5 class="text-white font-heading mb-1">Fuel Station</h5>
                    <p class="text-secondary small m-0">Marks availability of gasoline/diesel service stations ahead on the highway.</p>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="card glass-card sign-card h-100">
                    <canvas id="infoPhoneCanvas" class="sign-3d-canvas"></canvas>
                    <h5 class="text-white font-heading mb-1">Public Telephone</h5>
                    <p class="text-secondary small m-0">Marks telephone facilities, useful on highways with zero cellular coverage.</p>
                </div>
            </div>
            
        </div>
    </div>
</div>

<script src="<?php echo BASE_PATH; ?>assets/js/three-sims.js"></script>
<script>
window.addEventListener('DOMContentLoaded', () => {
    if (typeof init3DSignModel === 'function') {
        // Mandatory
        init3DSignModel('signStopCanvas', 'mandatory', 'STOP');
        init3DSignModel('signSpeed50Canvas', 'mandatory', '50');
        init3DSignModel('signNoEntryCanvas', 'mandatory', '⛔');
        init3DSignModel('signHornCanvas', 'mandatory', '🔕');
        
        // Warning
        init3DSignModel('warningSchoolCanvas', 'warning', '🚸');
        init3DSignModel('warningWorkCanvas', 'warning', '🚧');
        init3DSignModel('warningSlipCanvas', 'warning', '🚗');
        init3DSignModel('warningGeneralCanvas', 'warning', '⚠️');
        
        // Informative
        init3DSignModel('infoHospitalCanvas', 'informative', 'H');
        init3DSignModel('infoParkingCanvas', 'informative', 'P');
        init3DSignModel('infoFuelCanvas', 'informative', '⛽');
        init3DSignModel('infoPhoneCanvas', 'informative', '📞');
    }
});
</script>

<?php include_once '../includes/footer.php'; ?>
