<?php
include_once '../includes/header.php';

// Check if user is logged in (strongly recommended to prevent spam)
$user = get_logged_in_user();

$error = "";
$success = "";

// Ensure upload directory exists
$upload_dir = dirname(__DIR__) . '/uploads';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $latitude = floatval($_POST['latitude'] ?? 0);
    $longitude = floatval($_POST['longitude'] ?? 0);
    $image_name = null;
    
    if (empty($title) || empty($description) || $latitude === 0.0 || $longitude === 0.0) {
        $error = "All fields (including map selection) are required.";
    } else {
        // Handle file upload
        if (isset($_FILES['hazard_image']) && $_FILES['hazard_image']['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['hazard_image']['tmp_name'];
            $file_name = $_FILES['hazard_image']['name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            
            $allowed_exts = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array($file_ext, $allowed_exts)) {
                $error = "Invalid image extension. Only JPG, PNG, and WEBP allowed.";
            } else {
                // Generate unique filename
                $image_name = 'hazard_' . time() . '_' . rand(1000, 9999) . '.' . $file_ext;
                $dest_path = $upload_dir . '/' . $image_name;
                
                if (!move_uploaded_file($file_tmp, $dest_path)) {
                    $image_name = null; // upload failed but we can still submit
                }
            }
        }
        
        if (empty($error)) {
            // Logged-in user or null
            $user_id = $user ? $user['id'] : null;
            
            $submitted = db_execute(
                "INSERT INTO reports (user_id, title, description, latitude, longitude, image_path, status) 
                 VALUES (:user_id, :title, :description, :latitude, :longitude, :image_path, 'pending')",
                [
                    'user_id' => $user_id,
                    'title' => $title,
                    'description' => $description,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'image_path' => $image_name
                ]
            );
            
            if ($submitted) {
                $success = "Hazard reported successfully! Authorities and drivers have been notified.";
                // Clear fields
                $title = $description = "";
                $latitude = $longitude = 0.0;
            } else {
                $error = "Failed to submit report. Please try again.";
            }
        }
    }
}

// Fetch all reports to draw on the map as markers
$all_reports = db_query("SELECT * FROM reports");
?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col-lg-8 mx-auto text-center">
            <h1 class="display-4 font-heading text-white mb-2">Report Dangerous Road Hazards</h1>
            <p class="text-secondary">Pin potholes, missing signs, or faulty traffic lights on our crowdsourced map to warning oncoming motorists and alert city repair teams.</p>
        </div>
    </div>
    
    <div class="row g-4">
        <!-- Leaflet Map Panel -->
        <div class="col-lg-7">
            <div class="card glass-card p-3 h-100 border-info border-opacity-10">
                <div class="card-body p-0">
                    <h5 class="text-white font-heading mb-3"><i class="fa-solid fa-map-marked-alt text-info me-2"></i>Hazards Map</h5>
                    <p class="text-muted small">Click anywhere on the map to pin a new hazard location, or select existing warning markers to read details.</p>
                    <div id="hazardsMap" class="map-box mb-3" style="height: 420px; z-index: 1;"></div>
                </div>
            </div>
        </div>
        
        <!-- Report Hazard Form -->
        <div class="col-lg-5">
            <div class="card glass-card p-4 border-info border-opacity-10">
                <div class="card-body">
                    <h5 class="text-white font-heading mb-4"><i class="fa-solid fa-square-plus text-info me-2"></i>File New Hazard Report</h5>
                    
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger border-0 bg-danger-subtle text-danger small" role="alert">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i><?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success border-0 bg-success-subtle text-success small" role="alert">
                            <i class="fa-solid fa-circle-check me-2"></i><?php echo htmlspecialchars($success); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="report.php" enctype="multipart/form-data">
                        <!-- Hazard Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label text-secondary">Hazard Title</label>
                            <input type="text" class="form-control bg-dark text-white border-secondary" id="title" name="title" required placeholder="e.g. Giant potholes in left lane" value="<?php echo htmlspecialchars($title ?? ''); ?>">
                        </div>
                        
                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label text-secondary">Hazard Description</label>
                            <textarea class="form-control bg-dark text-white border-secondary small" id="description" name="description" rows="3" required placeholder="Provide details like safety impact or landmark references..."><?php echo htmlspecialchars($description ?? ''); ?></textarea>
                        </div>
                        
                        <!-- Coordinates (automatically loaded by clicking map) -->
                        <div class="row mb-3">
                            <div class="col-6">
                                <label for="latitude" class="form-label text-secondary">Latitude</label>
                                <input type="number" step="any" class="form-control bg-dark text-white border-secondary" id="latitude" name="latitude" required readonly placeholder="Click map" value="<?php echo $latitude ?? ''; ?>">
                            </div>
                            <div class="col-6">
                                <label for="longitude" class="form-label text-secondary">Longitude</label>
                                <input type="number" step="any" class="form-control bg-dark text-white border-secondary" id="longitude" name="longitude" required readonly placeholder="Click map" value="<?php echo $longitude ?? ''; ?>">
                            </div>
                        </div>
                        
                        <!-- Image Upload -->
                        <div class="mb-4">
                            <label for="hazard_image" class="form-label text-secondary">Upload Evidence Image (Optional)</label>
                            <input type="file" class="form-control bg-dark text-white border-secondary" id="hazard_image" name="hazard_image" accept="image/*">
                            <small class="text-muted text-nowrap">Accepted formats: JPG, PNG, WEBP.</small>
                        </div>
                        
                        <button type="submit" class="btn btn-info text-dark w-100 fw-bold py-2">
                            <i class="fa-solid fa-flag me-2"></i>File Report
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet Map Controller Script -->
<script>
// Center on standard coordinates (Bangalore/Default central location)
const centerLat = 12.971598;
const centerLng = 77.594562;

document.addEventListener('DOMContentLoaded', () => {
    // Initialize Leaflet Map
    const map = L.map('hazardsMap').setView([centerLat, centerLng], 13);
    
    // Load OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    
    // Track selected pin marker
    let selectMarker = null;
    
    // Capture Map Click Event
    map.on('click', (e) => {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;
        
        // Update form fields
        document.getElementById('latitude').value = lat.toFixed(6);
        document.getElementById('longitude').value = lng.toFixed(6);
        
        // Move selection pin marker on map
        if (selectMarker) {
            selectMarker.setLatLng(e.latlng);
        } else {
            selectMarker = L.marker(e.latlng, {
                draggable: true,
                icon: L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                })
            }).addTo(map);
            
            // Listen to drag event on selectMarker
            selectMarker.on('dragend', (de) => {
                const draggedPos = selectMarker.getLatLng();
                document.getElementById('latitude').value = draggedPos.lat.toFixed(6);
                document.getElementById('longitude').value = draggedPos.lng.toFixed(6);
            });
        }
        
        showNotification('Marker Placed!', 'Coordinates captured. Fill in the form fields to report.', 'success');
    });
    
    // Render existing reports as Orange Warning markers on the map
    const reports = <?php echo json_encode($all_reports); ?>;
    reports.forEach((rep) => {
        const markerColor = (rep.status === 'resolved') ? 'green' : 'orange';
        const markerIcon = L.icon({
            iconUrl: `https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-${markerColor}.png`,
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });
        
        const popupText = `
            <div style="font-family: 'Inter', sans-serif; min-width: 150px;">
                <h6 style="margin:0 0 5px 0; font-weight:700; color:#1e293b;">${escapeHtml(rep.title)}</h6>
                <p style="margin:0 0 10px 0; font-size:11px; color:#475569;">${escapeHtml(rep.description)}</p>
                <div style="font-size:10px; display:flex; justify-content:space-between; color:#94a3b8;">
                    <span>Status: <strong style="color:${(rep.status === 'resolved') ? '#10b981' : '#f59e0b'};">${rep.status.toUpperCase()}</strong></span>
                </div>
            </div>
        `;
        
        L.marker([rep.latitude, rep.longitude], { icon: markerIcon })
            .addTo(map)
            .bindPopup(popupText);
    });
});

function escapeHtml(text) {
    if (!text) return "";
    return text
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}
</script>

<?php include_once '../includes/footer.php'; ?>
