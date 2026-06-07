<?php
include_once '../includes/header.php';
?>

<div class="container py-4">
    <div class="row mb-5">
        <div class="col-lg-8 mx-auto text-center">
            <h1 class="display-4 font-heading text-white mb-2"><i class="fa-solid fa-life-ring text-danger me-2"></i>Emergency Help Center</h1>
            <p class="text-secondary">Get immediate access to medical helplines, identify nearest hospitals, and learn fundamental first-aid actions to support victims before paramedics arrive.</p>
        </div>
    </div>
    
    <!-- Row 1: Direct Hotline Calling -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card glass-card text-center p-4 border-danger border-opacity-20">
                <div class="card-body">
                    <i class="fa-solid fa-truck-medical text-danger mb-3" style="font-size: 50px;"></i>
                    <h4 class="text-white font-heading mb-1">Ambulance Services</h4>
                    <p class="text-muted small mb-4">Dial immediately for trauma care, critical injuries, or life-support transport.</p>
                    <a href="tel:108" class="btn btn-danger btn-emergency w-100 fw-bold py-2 fs-5">
                        <i class="fa-solid fa-phone me-2"></i>Call 108
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card glass-card text-center p-4 border-info border-opacity-20">
                <div class="card-body">
                    <i class="fa-solid fa-shield-halved text-info mb-3" style="font-size: 50px;"></i>
                    <h4 class="text-white font-heading mb-1">Police Assistance</h4>
                    <p class="text-muted small mb-4">Dial to report active crashes, blockages, or traffic violations.</p>
                    <a href="tel:112" class="btn btn-info text-dark btn-emergency w-100 fw-bold py-2 fs-5">
                        <i class="fa-solid fa-phone me-2"></i>Call 112 / 100
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card glass-card text-center p-4 border-warning border-opacity-20">
                <div class="card-body">
                    <i class="fa-solid fa-fire-extinguisher text-warning mb-3" style="font-size: 50px;"></i>
                    <h4 class="text-white font-heading mb-1">Fire & Rescue</h4>
                    <p class="text-muted small mb-4">Dial for vehicle entrapment extraction, fire spills, or hazardous leaks.</p>
                    <a href="tel:101" class="btn btn-warning text-dark btn-emergency w-100 fw-bold py-2 fs-5">
                        <i class="fa-solid fa-phone me-2"></i>Call 101
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Row 2: Nearest Hospitals Map Finder -->
    <div class="row g-4 mb-5">
        <div class="col-12">
            <div class="card glass-card p-4 border-info border-opacity-10">
                <div class="card-body p-0">
                    <h3 class="text-white font-heading mb-3"><i class="fa-solid fa-hospital-user text-info me-2"></i>Nearest Trauma Care & Hospitals</h3>
                    <p class="text-secondary small mb-4">Locate emergency rooms and trauma centers in your vicinity. Markers show hospitals with operational emergency departments.</p>
                    <div id="hospitalMap" class="map-box" style="height: 400px; z-index: 1;"></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Row 3: Emergency First Aid Guide -->
    <div class="row">
        <div class="col-12">
            <div class="card glass-card p-4">
                <div class="card-body">
                    <h3 class="text-white font-heading mb-4 text-center"><i class="fa-solid fa-kit-medical text-danger me-2"></i>Basic First-Aid for Road Crash Victims</h3>
                    
                    <div class="row g-4">
                        <!-- Step 1 -->
                        <div class="col-md-6 col-lg-3">
                            <div class="p-3 bg-dark bg-opacity-40 border border-secondary border-opacity-20 rounded h-100">
                                <h5 class="text-info font-heading mb-2">1. Secure the Area</h5>
                                <p class="text-secondary small m-0">Park your vehicle safely and turn on hazard flashers. Place safety triangles to alert oncoming traffic, ensuring you do not become a victim yourself.</p>
                            </div>
                        </div>
                        
                        <!-- Step 2 -->
                        <div class="col-md-6 col-lg-3">
                            <div class="p-3 bg-dark bg-opacity-40 border border-secondary border-opacity-20 rounded h-100">
                                <h5 class="text-info font-heading mb-2">2. Check Responsiveness</h5>
                                <p class="text-secondary small m-0">Gently shake the victim's shoulders and ask "Are you okay?". Check if they are breathing. Do NOT move the victim unless there is an active danger of fire or explosion, to prevent spinal cord damage.</p>
                            </div>
                        </div>
                        
                        <!-- Step 3 -->
                        <div class="col-md-6 col-lg-3">
                            <div class="p-3 bg-dark bg-opacity-40 border border-secondary border-opacity-20 rounded h-100">
                                <h5 class="text-info font-heading mb-2">3. Control Bleeding</h5>
                                <p class="text-secondary small m-0">Apply firm, direct pressure on wounds using clean gauze, a cloth, or a bandage. Elevate the bleeding limb if possible to reduce pressure.</p>
                            </div>
                        </div>
                        
                        <!-- Step 4 -->
                        <div class="col-md-6 col-lg-3">
                            <div class="p-3 bg-dark bg-opacity-40 border border-secondary border-opacity-20 rounded h-100">
                                <h5 class="text-info font-heading mb-2">4. Support the Neck</h5>
                                <p class="text-secondary small m-0">Hold the victim's head and neck straight in the position you found them. Restrict movement of the head to prevent worsening of potential neck fractures.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Good Samaritan Protection Banner -->
                    <div class="alert alert-success border-0 bg-success-subtle text-dark mt-4 text-center small mb-0" role="alert">
                        <i class="fa-solid fa-scale-balanced me-2"></i><strong>Good Samaritan Protection:</strong> By law, any citizen who offers medical or non-medical helper assistance to a crash victim in good faith is protected from civil or criminal liability. Do not hesitate to help save a life.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet Hospital Map Script -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Center of search (Bangalore coordinates)
    const mapLat = 12.971598;
    const mapLng = 77.594562;
    
    const map = L.map('hospitalMap').setView([mapLat, mapLng], 14);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    
    // User marker
    L.marker([mapLat, mapLng], {
        icon: L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-blue.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        })
    }).addTo(map).bindPopup("<strong style='color:#3b82f6;'>Your Current Location</strong>").openPopup();
    
    // Mock nearby Hospital nodes
    const hospitals = [
        { name: "Fortis Emergency Hospital", lat: 12.975598, lng: 77.599562, tel: "+91 80 6660 0666" },
        { name: "Manipal Trauma & Accident Care", lat: 12.964598, lng: 77.585562, tel: "+91 80 2502 4444" },
        { name: "St. Martha's General Hospital", lat: 12.969598, lng: 77.591562, tel: "+91 80 2222 4567" },
        { name: "Apollo ER Department", lat: 12.981598, lng: 77.589562, tel: "+91 80 4668 8888" }
    ];
    
    // Plot hospitals
    hospitals.forEach((hosp) => {
        const hospIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });
        
        const content = `
            <div style="font-family: 'Inter', sans-serif;">
                <h6 style="margin:0 0 5px 0; font-weight:700; color:#dc2626;"><i class="fa-solid fa-hospital-user me-1"></i>${hosp.name}</h6>
                <p style="margin:0 0 5px 0; font-size:11px; color:#4b5563;"><i class="fa-solid fa-phone me-1"></i>Emergency contact: <a href="tel:${hosp.tel}" style="color:#2563eb; font-weight:600;">${hosp.tel}</a></p>
                <span style="font-size:10px; color:#9ca3af; font-weight:500;">Status: <strong>Open 24/7 ER</strong></span>
            </div>
        `;
        
        L.marker([hosp.lat, hosp.lng], { icon: hospIcon }).addTo(map).bindPopup(content);
    });
});
</script>

<?php include_once '../includes/header.php'; ?>
