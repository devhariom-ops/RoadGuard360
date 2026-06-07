/* SafeRoads Accident Risk Prediction Calculator Algorithm */

function calculateAccidentRisk() {
    const speedInput = document.getElementById('calcSpeed');
    const weatherInput = document.getElementById('calcWeather');
    const vehicleInput = document.getElementById('calcVehicle');
    const roadInput = document.getElementById('calcRoad');
    
    if (!speedInput || !weatherInput || !vehicleInput || !roadInput) return;
    
    const speed = parseInt(speedInput.value);
    const weather = weatherInput.value;
    const vehicle = vehicleInput.value;
    const road = roadInput.value;
    
    // Base Risk Factor
    let risk = 10;
    
    // 1. Speed Impact (exponential)
    if (speed <= 40) {
        risk += (speed / 10) * 1.5;
    } else if (speed <= 70) {
        risk += 6 + (speed - 40) * 0.7; // up to 27
    } else if (speed <= 100) {
        risk += 27 + (speed - 70) * 1.2; // up to 63
    } else {
        risk += 63 + (speed - 100) * 2.2; // up to 173
    }
    
    // 2. Weather Impact
    let weatherModifier = 0;
    if (weather === 'rain') weatherModifier = 18;
    else if (weather === 'fog') weatherModifier = 24;
    else if (weather === 'snow') weatherModifier = 30;
    risk += weatherModifier;
    
    // 3. Vehicle Vulnerability
    let vehicleModifier = 0;
    if (vehicle === 'twowheeler') vehicleModifier = 22; // extreme vulnerability
    else if (vehicle === 'truck') vehicleModifier = 12; // slow braking
    else if (vehicle === 'suv') vehicleModifier = 6;
    else if (vehicle === 'sedan') vehicleModifier = 4;
    risk += vehicleModifier;
    
    // 4. Road Condition
    let roadModifier = 0;
    if (road === 'wet') roadModifier = 15;
    else if (road === 'potholes') roadModifier = 22;
    else if (road === 'construction') roadModifier = 10;
    risk += roadModifier;
    
    // Cap risk between 5% and 99%
    risk = Math.max(5, Math.min(99, Math.round(risk)));
    
    // Update HTML text
    const riskPercentageText = document.getElementById('calcRiskPercentage');
    if (riskPercentageText) {
        riskPercentageText.textContent = risk + '%';
    }
    
    // Update SVG Circular Ring
    const progressRing = document.getElementById('calcProgressRing');
    if (progressRing) {
        // Dasharray format is "percentage, 100"
        progressRing.setAttribute('stroke-dasharray', `${risk}, 100`);
    }
    
    // Update Safety Badge & Recommendation Info
    const badge = document.getElementById('calcSafetyBadge');
    const recText = document.getElementById('calcRecommendation');
    
    // Remove previous color classes
    badge.className = "badge fs-6 px-3 py-2";
    
    // Reset colors on ring and percentage
    riskPercentageText.className = "m-0 fw-extrabold font-heading";
    progressRing.className.baseVal = "";
    
    if (risk < 35) {
        badge.classList.add('bg-success');
        badge.textContent = "SAFE STATUS";
        riskPercentageText.classList.add('text-success');
        progressRing.classList.add('text-success');
        recText.innerHTML = `<i class="fa-solid fa-circle-check text-success me-1"></i> Under present conditions, your driving parameters are safe. Maintain safe following margins and keep cruising.`;
    } else if (risk < 60) {
        badge.classList.add('bg-warning', 'text-dark');
        badge.textContent = "MODERATE RISK";
        riskPercentageText.classList.add('text-warning');
        progressRing.classList.add('text-warning');
        
        let tips = [];
        if (weather !== 'clear') tips.push("wet/foggy weather");
        if (speed > 70) tips.push("speeds above 70 km/h");
        if (road === 'potholes') tips.push("damaged road surfaces");
        
        recText.innerHTML = `<i class="fa-solid fa-triangle-exclamation text-warning me-1"></i> Elevated risk detected due to ${tips.join(' and ')}. Reduce speed by 15-20 km/h and ensure headlights are active.`;
    } else if (risk < 80) {
        badge.classList.add('bg-danger');
        badge.textContent = "DANGEROUS ROADWAYS";
        riskPercentageText.classList.add('text-danger');
        progressRing.classList.add('text-danger');
        
        let customWarning = "Braking distance is quadrupled! ";
        if (vehicle === 'twowheeler') {
            customWarning += "Two-wheelers have high slide vulnerability. Ensure helmet is buckled.";
        } else {
            customWarning += "Keep seatbelts locked. Observe safety lanes.";
        }
        recText.innerHTML = `<i class="fa-solid fa-skull-crossbones text-danger me-1"></i> <strong>Danger:</strong> ${customWarning} Pull back speed immediately to bring risk below margins.`;
    } else {
        // Critical Risk
        badge.classList.add('bg-purple');
        badge.textContent = "CRITICAL HAZARD";
        riskPercentageText.classList.add('text-danger', 'blink-text'); // Blink indicator
        progressRing.classList.add('text-danger');
        
        recText.innerHTML = `<i class="fa-solid fa-radiation text-danger me-1"></i> <strong>HIGH FATALITY PROBABILITY.</strong> Overspeeding under adverse conditions (slippery roads or low visibility) renders vehicle control mathematically impossible. Slow down immediately!`;
    }
}
