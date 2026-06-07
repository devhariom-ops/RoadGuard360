/* SafeRoads 3D Interactive Simulations Engine (Three.js & GSAP) */

// Global state trackers
let heroScene, trafficScene, accidentScene, helmetScene, seatbeltScene;

// Helper to create a basic low-poly car group
function createLowPolyCar(color = 0xef4444) {
    const carGroup = new THREE.Group();
    
    // Chassis (Lower body)
    const chassisGeo = new THREE.BoxGeometry(2, 0.4, 0.9);
    const chassisMat = new THREE.MeshStandardMaterial({ color: color, roughness: 0.2 });
    const chassis = new THREE.Mesh(chassisGeo, chassisMat);
    chassis.position.y = 0.25;
    chassis.castShadow = true;
    chassis.receiveShadow = true;
    carGroup.add(chassis);
    
    // Cabin (Upper body)
    const cabinGeo = new THREE.BoxGeometry(1.1, 0.5, 0.8);
    const cabinMat = new THREE.MeshStandardMaterial({ color: 0x111827, roughness: 0.1 });
    const cabin = new THREE.Mesh(cabinGeo, cabinMat);
    cabin.position.set(-0.1, 0.65, 0);
    cabin.castShadow = true;
    carGroup.add(cabin);
    
    // Wheels (4 cylinders)
    const wheelGeo = new THREE.CylinderGeometry(0.25, 0.25, 0.15, 12);
    const wheelMat = new THREE.MeshStandardMaterial({ color: 0x374151, roughness: 0.8 });
    
    const wheelPositions = [
        [-0.6, 0.15, 0.45],
        [0.6, 0.15, 0.45],
        [-0.6, 0.15, -0.45],
        [0.6, 0.15, -0.45]
    ];
    
    wheelPositions.forEach((pos) => {
        const wheel = new THREE.Mesh(wheelGeo, wheelMat);
        wheel.rotation.x = Math.PI / 2;
        wheel.position.set(pos[0], pos[1], pos[2]);
        wheel.castShadow = true;
        carGroup.add(wheel);
    });
    
    // Headlights
    const lightGeo = new THREE.BoxGeometry(0.05, 0.1, 0.15);
    const lightMat = new THREE.MeshBasicMaterial({ color: 0xffffff });
    const leftLight = new THREE.Mesh(lightGeo, lightMat);
    leftLight.position.set(1.0, 0.25, 0.3);
    const rightLight = leftLight.clone();
    rightLight.position.z = -0.3;
    carGroup.add(leftLight);
    carGroup.add(rightLight);
    
    return carGroup;
}

// ========================================================
// 1. HERO SECTION 3D ROAD LOOP
// ========================================================
function initHero3DScene(canvasId) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;
    
    const renderer = new THREE.WebGLRenderer({ canvas: canvas, antialias: true, alpha: true });
    renderer.setSize(canvas.clientWidth, canvas.clientHeight);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    
    const scene = new THREE.Scene();
    
    const camera = new THREE.PerspectiveCamera(40, canvas.clientWidth / canvas.clientHeight, 0.1, 100);
    camera.position.set(0, 5, 15);
    camera.lookAt(0, 0, -5);
    
    // Lights
    const ambientLight = new THREE.AmbientLight(0xffffff, 0.6);
    scene.add(ambientLight);
    
    const dirLight = new THREE.DirectionalLight(0x00f2fe, 1);
    dirLight.position.set(5, 10, 5);
    scene.add(dirLight);
    
    // Road Grid
    const gridHelper = new THREE.GridHelper(60, 30, 0x00f2fe, 0x1e293b);
    gridHelper.position.y = 0;
    scene.add(gridHelper);
    
    // Low-poly vehicles cruising
    const car1 = createLowPolyCar(0x00f2fe);
    const car2 = createLowPolyCar(0xef4444);
    const car3 = createLowPolyCar(0x10b981);
    
    car1.position.set(-2, 0, -10);
    car2.position.set(2, 0, -25);
    car3.position.set(-2, 0, 5);
    
    scene.add(car1);
    scene.add(car2);
    scene.add(car3);
    
    // Animation Loop
    function animate() {
        requestAnimationFrame(animate);
        
        // Move grid backward to simulate road movement
        gridHelper.position.z += 0.1;
        if (gridHelper.position.z > 2) {
            gridHelper.position.z = 0;
        }
        
        // Rotate wheels slowly
        car1.children.forEach(c => { if (c.geometry && c.geometry.type === 'CylinderGeometry') c.rotation.y += 0.1; });
        car2.children.forEach(c => { if (c.geometry && c.geometry.type === 'CylinderGeometry') c.rotation.y += 0.1; });
        car3.children.forEach(c => { if (c.geometry && c.geometry.type === 'CylinderGeometry') c.rotation.y += 0.1; });
        
        // Cruise lanes
        car1.position.z += 0.05;
        if (car1.position.z > 15) car1.position.z = -30;
        
        car2.position.z += 0.08;
        if (car2.position.z > 15) car2.position.z = -30;
        
        car3.position.z += 0.04;
        if (car3.position.z > 15) car3.position.z = -30;
        
        renderer.render(scene, camera);
    }
    
    animate();
    
    // Resize handler
    window.addEventListener('resize', () => {
        camera.aspect = canvas.clientWidth / canvas.clientHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(canvas.clientWidth, canvas.clientHeight);
    });
}

// ========================================================
// 2. 3D TRAFFIC SIGNAL SIMULATOR
// ========================================================
let signalRedMesh, signalYellowMesh, signalGreenMesh;

function initTrafficSignalSim(canvasId) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;
    
    const renderer = new THREE.WebGLRenderer({ canvas: canvas, antialias: true });
    renderer.setSize(canvas.clientWidth, canvas.clientHeight);
    renderer.setClearColor(0x0f172a);
    
    const scene = new THREE.Scene();
    
    const camera = new THREE.PerspectiveCamera(45, canvas.clientWidth / canvas.clientHeight, 0.1, 100);
    camera.position.set(0, 1.8, 5);
    
    // Orbit Controls
    const controls = new THREE.OrbitControls(camera, renderer.domElement);
    controls.enableZoom = false;
    controls.enablePan = false;
    controls.minPolarAngle = Math.PI/3;
    controls.maxPolarAngle = Math.PI/2;
    
    // Lighting
    const ambientLight = new THREE.AmbientLight(0xffffff, 0.7);
    scene.add(ambientLight);
    
    const dirLight = new THREE.DirectionalLight(0xffffff, 0.8);
    dirLight.position.set(5, 8, 5);
    scene.add(dirLight);
    
    // Model the Signal Post
    // Base Plate
    const baseGeo = new THREE.CylinderGeometry(0.6, 0.6, 0.1, 16);
    const darkMat = new THREE.MeshStandardMaterial({ color: 0x334155, metalness: 0.6, roughness: 0.2 });
    const base = new THREE.Mesh(baseGeo, darkMat);
    scene.add(base);
    
    // Main Pole
    const poleGeo = new THREE.CylinderGeometry(0.08, 0.08, 3.5, 16);
    const pole = new THREE.Mesh(poleGeo, darkMat);
    pole.position.y = 1.75;
    scene.add(pole);
    
    // Signal Box (Head)
    const boxGeo = new THREE.BoxGeometry(0.6, 1.4, 0.6);
    const box = new THREE.Mesh(boxGeo, darkMat);
    box.position.set(0, 2.6, 0);
    scene.add(box);
    
    // Lights (Spheres with emissive shaders)
    const lightGeo = new THREE.SphereGeometry(0.16, 16, 16);
    
    signalRedMesh = new THREE.Mesh(lightGeo, new THREE.MeshStandardMaterial({ color: 0x440000, emissive: 0x330000, roughness: 0.1 }));
    signalRedMesh.position.set(0, 3.0, 0.3);
    scene.add(signalRedMesh);
    
    signalYellowMesh = new THREE.Mesh(lightGeo, new THREE.MeshStandardMaterial({ color: 0x444400, emissive: 0x333300, roughness: 0.1 }));
    signalYellowMesh.position.set(0, 2.6, 0.3);
    scene.add(signalYellowMesh);
    
    signalGreenMesh = new THREE.Mesh(lightGeo, new THREE.MeshStandardMaterial({ color: 0x004400, emissive: 0x003300, roughness: 0.1 }));
    signalGreenMesh.position.set(0, 2.2, 0.3);
    scene.add(signalGreenMesh);
    
    // Turn red light on by default
    setTrafficLightState('red');
    
    function animate() {
        requestAnimationFrame(animate);
        controls.update();
        renderer.render(scene, camera);
    }
    animate();
    
    // Handle resizing
    window.addEventListener('resize', () => {
        camera.aspect = canvas.clientWidth / canvas.clientHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(canvas.clientWidth, canvas.clientHeight);
    });
}

function setTrafficLightState(state) {
    if (!signalRedMesh || !signalYellowMesh || !signalGreenMesh) return;
    
    // Dim all lights first
    signalRedMesh.material.emissive.setHex(0x220000);
    signalRedMesh.material.color.setHex(0x440000);
    
    signalYellowMesh.material.emissive.setHex(0x222200);
    signalYellowMesh.material.color.setHex(0x444400);
    
    signalGreenMesh.material.emissive.setHex(0x002200);
    signalGreenMesh.material.color.setHex(0x004400);
    
    const infoText = document.getElementById('signalExplanation');
    
    if (state === 'red') {
        signalRedMesh.material.emissive.setHex(0xff0000);
        signalRedMesh.material.color.setHex(0xff3333);
        if (infoText) {
            infoText.innerHTML = `<h5 class="text-danger font-heading"><i class="fa-solid fa-hand me-2"></i>RED SIGNAL: STOP COMPLETE</h5>
                                  <p class="text-secondary small m-0">Drivers MUST bring their vehicles to a complete stop behind the stop line. Turning on red is illegal unless specified. Pedestrians may cross safely using the crosswalk.</p>`;
        }
    } else if (state === 'yellow') {
        signalYellowMesh.material.emissive.setHex(0xffaa00);
        signalYellowMesh.material.color.setHex(0xffcc33);
        if (infoText) {
            infoText.innerHTML = `<h5 class="text-warning font-heading"><i class="fa-solid fa-triangle-exclamation me-2"></i>YELLOW SIGNAL: CAUTION</h5>
                                  <p class="text-secondary small m-0">Indicates the signal is about to turn red. If you are already in the intersection, clear it safely. If you are behind the stop line, decelerate and stop. Do NOT speed up to cross.</p>`;
        }
    } else if (state === 'green') {
        signalGreenMesh.material.emissive.setHex(0x00ff00);
        signalGreenMesh.material.color.setHex(0x33ff33);
        if (infoText) {
            infoText.innerHTML = `<h5 class="text-success font-heading"><i class="fa-solid fa-circle-check me-2"></i>GREEN SIGNAL: GO CAUTIOUSLY</h5>
                                  <p class="text-secondary small m-0">You may proceed straight or turn if the lane is clear. You must still yield to crossing pedestrians or oncoming vehicles turning right. Stay alert.</p>`;
        }
    }
}

// ========================================================
// 3. 3D ACCIDENT SIMULATION
// ========================================================
let accidentCar, obstacleWall, particleExplosionGroup;

function initAccidentSim(canvasId) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;
    
    const renderer = new THREE.WebGLRenderer({ canvas: canvas, antialias: true });
    renderer.setSize(canvas.clientWidth, canvas.clientHeight);
    renderer.setClearColor(0x0f172a);
    renderer.shadowMap.enabled = true;
    
    const scene = new THREE.Scene();
    
    const camera = new THREE.PerspectiveCamera(40, canvas.clientWidth / canvas.clientHeight, 0.1, 100);
    camera.position.set(-6, 4, 10);
    camera.lookAt(2, 0.5, 0);
    
    const controls = new THREE.OrbitControls(camera, renderer.domElement);
    controls.enableZoom = true;
    controls.maxDistance = 20;
    controls.minDistance = 5;
    
    // Lights
    scene.add(new THREE.AmbientLight(0xffffff, 0.5));
    
    const light = new THREE.DirectionalLight(0xffffff, 0.8);
    light.position.set(5, 10, 2);
    light.castShadow = true;
    light.shadow.mapSize.width = 1024;
    light.shadow.mapSize.height = 1024;
    scene.add(light);
    
    // Road Plane
    const roadGeo = new THREE.PlaneGeometry(30, 6);
    const roadMat = new THREE.MeshStandardMaterial({ color: 0x1e293b, roughness: 0.6 });
    const road = new THREE.Mesh(roadGeo, roadMat);
    road.rotation.x = -Math.PI / 2;
    road.receiveShadow = true;
    scene.add(road);
    
    // White lanes lines
    const lineGeo = new THREE.PlaneGeometry(30, 0.1);
    const lineMat = new THREE.MeshBasicMaterial({ color: 0xffffff });
    const centerLine = new THREE.Mesh(lineGeo, lineMat);
    centerLine.rotation.x = -Math.PI/2;
    centerLine.position.y = 0.01;
    scene.add(centerLine);
    
    // Wall Obstacle
    const wallGeo = new THREE.BoxGeometry(0.8, 2, 4);
    const wallMat = new THREE.MeshStandardMaterial({ color: 0x475569, roughness: 0.8 });
    obstacleWall = new THREE.Mesh(wallGeo, wallMat);
    obstacleWall.position.set(8, 1, 0);
    obstacleWall.castShadow = true;
    scene.add(obstacleWall);
    
    // Create Low poly car
    accidentCar = createLowPolyCar(0xef4444);
    accidentCar.position.set(-8, 0, 0);
    scene.add(accidentCar);
    
    // Particle Group for Crash Sparks
    particleExplosionGroup = new THREE.Group();
    scene.add(particleExplosionGroup);
    
    function animate() {
        requestAnimationFrame(animate);
        controls.update();
        renderer.render(scene, camera);
    }
    animate();
    
    window.addEventListener('resize', () => {
        camera.aspect = canvas.clientWidth / canvas.clientHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(canvas.clientWidth, canvas.clientHeight);
    });
}

function runAccidentSimulation(mode) {
    if (!accidentCar || !obstacleWall || !particleExplosionGroup) return;
    
    // Kill existing animations
    gsap.killTweensOf(accidentCar.position);
    gsap.killTweensOf(accidentCar.rotation);
    
    // Clean up crash particles
    while (particleExplosionGroup.children.length > 0) {
        particleExplosionGroup.remove(particleExplosionGroup.children[0]);
    }
    
    // Reset positions
    accidentCar.position.set(-8, 0, 0);
    accidentCar.rotation.set(0, 0, 0);
    accidentCar.scale.set(1, 1, 1);
    
    const infoBox = document.getElementById('accidentExplanation');
    
    if (mode === 'overspeeding') {
        infoBox.innerHTML = `<span class="spinner-grow spinner-grow-sm text-danger" role="status"></span> <strong class="text-danger">Simulating: Overspeeding...</strong>`;
        
        // Fast move & crash
        gsap.to(accidentCar.position, {
            x: 6.9,
            duration: 1.0,
            ease: "power2.in",
            onComplete: () => {
                // Crash effect: shake camera or vibrate, compress car
                gsap.to(accidentCar.scale, { x: 0.65, y: 1.1, duration: 0.1, yoyo: true, repeat: 1 });
                gsap.to(accidentCar.position, { x: 6.5, duration: 0.1 });
                accidentCar.rotation.z = 0.2;
                
                // Explode sparks
                triggerCrashSparks(6.9, 0.4, 0);
                
                infoBox.innerHTML = `<h6 class="text-danger fw-bold m-0"><i class="fa-solid fa-triangle-exclamation"></i> OVERSPEED CRASH RESULTS: FATAL</h6>
                                     <p class="text-secondary small m-0">Impact speed was too high. The braking distance required exceeded human reflexes, smashing the vehicle cab. Kinetic energy multiplies by the square of speed.</p>`;
            }
        });
        
    } else if (mode === 'drunk') {
        infoBox.innerHTML = `<span class="spinner-grow spinner-grow-sm text-warning" role="status"></span> <strong class="text-warning">Simulating: Drunk driving...</strong>`;
        
        // Swerving left & right, then slow crash
        const timeline = gsap.timeline({
            onComplete: () => {
                triggerCrashSparks(7.0, 0.4, 0.5);
                gsap.to(accidentCar.scale, { x: 0.8, duration: 0.1 });
                accidentCar.rotation.y = 0.5;
                
                infoBox.innerHTML = `<h6 class="text-danger fw-bold m-0"><i class="fa-solid fa-wine-glass"></i> DRUNK DRIVING CRASH: SEVERE</h6>
                                     <p class="text-secondary small m-0">Alcohol dulled driving focus, causing lateral drifting and zig-zag lanes. The driver failed to notice the barrier and swerved right into it without braking.</p>`;
            }
        });
        
        timeline.to(accidentCar.position, { x: -4, z: 1.2, duration: 1.0, ease: "sine.inOut" });
        timeline.to(accidentCar.position, { x: 0, z: -1.2, duration: 1.0, ease: "sine.inOut" });
        timeline.to(accidentCar.position, { x: 4, z: 0.8, duration: 1.0, ease: "sine.inOut" });
        timeline.to(accidentCar.position, { x: 7.0, z: 0.5, duration: 0.7, ease: "power1.in" });
        
    } else if (mode === 'distracted') {
        infoBox.innerHTML = `<span class="spinner-grow spinner-grow-sm text-warning" role="status"></span> <strong class="text-warning">Simulating: Distracted Driving (Texting)...</strong>`;
        
        // Simulate phone pop-up on page
        const phoneOverlay = document.getElementById('phoneOverlay');
        if (phoneOverlay) phoneOverlay.classList.remove('d-none');
        
        gsap.to(accidentCar.position, {
            x: 7.0,
            z: -0.6,
            duration: 3.5,
            ease: "none",
            onComplete: () => {
                if (phoneOverlay) phoneOverlay.classList.add('d-none');
                triggerCrashSparks(7.0, 0.4, -0.6);
                gsap.to(accidentCar.scale, { x: 0.75, duration: 0.1 });
                accidentCar.rotation.y = -0.3;
                
                infoBox.innerHTML = `<h6 class="text-danger fw-bold m-0"><i class="fa-solid fa-mobile-button"></i> DISTRACTED CRASH: SEVERE</h6>
                                     <p class="text-secondary small m-0">Looking down at a mobile text message for 3 seconds leaves the car traveling blindly. The car slowly drifted out of lane and crashed head-on without applying brakes.</p>`;
            }
        });
        
    } else if (mode === 'safe') {
        infoBox.innerHTML = `<span class="spinner-grow spinner-grow-sm text-success" role="status"></span> <strong class="text-success">Simulating: Defensive Safe Driving...</strong>`;
        
        // Steady move, detect and brake cleanly
        gsap.to(accidentCar.position, {
            x: 4.8,
            duration: 2.5,
            ease: "power1.out",
            onComplete: () => {
                // Hazard lights flashing
                flashCarHazards();
                
                infoBox.innerHTML = `<h6 class="text-success fw-bold m-0"><i class="fa-solid fa-circle-check"></i> SAFE STOPPING: SUCCESS</h6>
                                     <p class="text-secondary small m-0">Vehicle maintained the legal speed limit. When the obstruction was identified, the driver reacted inside 0.7 seconds and stopped safely 3 feet clear of the wall.</p>`;
            }
        });
    }
}

function triggerCrashSparks(x, y, z) {
    const particleGeo = new THREE.SphereGeometry(0.06, 6, 6);
    const particleMat = new THREE.MeshBasicMaterial({ color: 0xff9900 });
    
    for (let i = 0; i < 25; i++) {
        const p = new THREE.Mesh(particleGeo, particleMat);
        p.position.set(x, y, z);
        particleExplosionGroup.add(p);
        
        // Random vector
        const vx = (Math.random() - 1.5) * 4;
        const vy = (Math.random() + 0.5) * 3;
        const vz = (Math.random() - 0.5) * 3;
        
        gsap.to(p.position, {
            x: x + vx,
            y: y + vy,
            z: z + vz,
            duration: 0.8,
            ease: "power1.out"
        });
        gsap.to(p.scale, {
            x: 0, y: 0, z: 0,
            duration: 0.8,
            onComplete: () => {
                particleExplosionGroup.remove(p);
            }
        });
    }
}

function flashCarHazards() {
    let flashes = 0;
    const interval = setInterval(() => {
        // Find lights inside car group
        accidentCar.children.forEach(child => {
            if (child.material && child.material.color.getHex() === 0xffffff) {
                // toggle color to amber and white
                child.material.color.setHex(child.material.color.getHex() === 0xffffff ? 0xffaa00 : 0xffffff);
            }
        });
        flashes++;
        if (flashes > 12) {
            clearInterval(interval);
            // Reset lights to white
            accidentCar.children.forEach(child => {
                if (child.material && child.material.color.getHex() === 0xffaa00) {
                    child.material.color.setHex(0xffffff);
                }
            });
        }
    }, 250);
}

// ========================================================
// 4. 3D HELMET SAFETY DEMO
// ========================================================
let headNoHelmet, headWithHelmet;

function initHelmetSafetySim(canvasId) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;
    
    const renderer = new THREE.WebGLRenderer({ canvas: canvas, antialias: true });
    renderer.setSize(canvas.clientWidth, canvas.clientHeight);
    renderer.setClearColor(0x0f172a);
    renderer.shadowMap.enabled = true;
    
    const scene = new THREE.Scene();
    
    const camera = new THREE.PerspectiveCamera(40, canvas.clientWidth / canvas.clientHeight, 0.1, 100);
    camera.position.set(0, 3, 7);
    camera.lookAt(0, 1.2, 0);
    
    // Lights
    scene.add(new THREE.AmbientLight(0xffffff, 0.6));
    const dLight = new THREE.DirectionalLight(0xffffff, 0.8);
    dLight.position.set(2, 6, 3);
    dLight.castShadow = true;
    scene.add(dLight);
    
    // Ground
    const floorGeo = new THREE.BoxGeometry(6, 0.2, 3);
    const floorMat = new THREE.MeshStandardMaterial({ color: 0x1e293b, roughness: 0.6 });
    const floor = new THREE.Mesh(floorGeo, floorMat);
    floor.position.y = 0.1;
    floor.receiveShadow = true;
    scene.add(floor);
    
    // Separator line
    const sepGeo = new THREE.BoxGeometry(0.05, 0.22, 3);
    const sepMat = new THREE.MeshBasicMaterial({ color: 0x334155 });
    const sep = new THREE.Mesh(sepGeo, sepMat);
    sep.position.set(0, 0.1, 0);
    scene.add(sep);
    
    // Head No Helmet (Left side)
    const headGeo = new THREE.SphereGeometry(0.4, 32, 32);
    const headMat = new THREE.MeshStandardMaterial({ color: 0xffd1a9, roughness: 0.4 });
    headNoHelmet = new THREE.Mesh(headGeo, headMat);
    headNoHelmet.position.set(-1.5, 3.0, 0);
    headNoHelmet.castShadow = true;
    scene.add(headNoHelmet);
    
    // Head With Helmet (Right side)
    headWithHelmet = new THREE.Group();
    const innerHead = new THREE.Mesh(headGeo, headMat.clone());
    innerHead.position.set(0, 0, 0);
    headWithHelmet.add(innerHead);
    
    // Helmet Outer Layer (a slightly larger sphere sliced or scaled, painted red)
    const helmetGeo = new THREE.SphereGeometry(0.46, 32, 16, 0, Math.PI * 2, 0, Math.PI * 0.7);
    const helmetMat = new THREE.MeshStandardMaterial({ color: 0xef4444, roughness: 0.1, metalness: 0.3 });
    const helmetShell = new THREE.Mesh(helmetGeo, helmetMat);
    helmetShell.position.set(0, 0.05, 0);
    helmetShell.rotation.x = -0.2; // tilt helmet slightly
    headWithHelmet.add(helmetShell);
    
    headWithHelmet.position.set(1.5, 3.0, 0);
    scene.add(headWithHelmet);
    
    function animate() {
        requestAnimationFrame(animate);
        renderer.render(scene, camera);
    }
    animate();
    
    window.addEventListener('resize', () => {
        camera.aspect = canvas.clientWidth / canvas.clientHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(canvas.clientWidth, canvas.clientHeight);
    });
}

function runHelmetDropTest() {
    if (!headNoHelmet || !headWithHelmet) return;
    
    // Reset positions
    gsap.killTweensOf(headNoHelmet.position);
    gsap.killTweensOf(headNoHelmet.scale);
    gsap.killTweensOf(headWithHelmet.position);
    headNoHelmet.position.set(-1.5, 3.0, 0);
    headNoHelmet.scale.set(1, 1, 1);
    headNoHelmet.material.color.setHex(0xffd1a9);
    
    headWithHelmet.position.set(1.5, 3.0, 0);
    
    const info = document.getElementById('helmetExplanation');
    info.innerHTML = `<span class="spinner-grow spinner-grow-sm text-info" role="status"></span> <strong>Simulating crash impact...</strong>`;
    
    // Drop No Helmet (Crashes and squashes, turns red)
    gsap.to(headNoHelmet.position, {
        y: 0.5,
        duration: 0.6,
        ease: "power2.in",
        onComplete: () => {
            gsap.to(headNoHelmet.scale, { x: 1.3, y: 0.45, z: 1.3, duration: 0.1 });
            headNoHelmet.material.color.setHex(0xef4444);
            
            info.innerHTML = `<h6 class="text-danger fw-bold m-0"><i class="fa-solid fa-triangle-exclamation"></i> IMPACT RESULTS</h6>
                              <div class="row text-center mt-2 small">
                                <div class="col-6 text-danger border-end border-secondary border-opacity-30">
                                    <strong>No Helmet:</strong><br>
                                    Impact: 100% force<br>
                                    Skull Fractures: Severe<br>
                                    Brain Trauma: Fatal
                                </div>
                                <div class="col-6 text-success">
                                    <strong>With Helmet:</strong><br>
                                    Impact: 10% force<br>
                                    Skull Fractures: None<br>
                                    Brain Trauma: Protected
                                </div>
                              </div>`;
        }
    });
    
    // Drop With Helmet (Bounces back slightly, no damage)
    gsap.to(headWithHelmet.position, {
        y: 0.56,
        duration: 0.6,
        ease: "power2.in",
        onComplete: () => {
            // Bounce upward
            gsap.to(headWithHelmet.position, {
                y: 1.1,
                duration: 0.25,
                ease: "power1.out",
                yoyo: true,
                repeat: 1
            });
        }
    });
}

// ========================================================
// 5. 3D SEATBELT CRASH SIMULATION
// ========================================================
let seatbeltGroup, dummyModel, seatbeltStrapMesh;

function initSeatbeltSim(canvasId) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;
    
    const renderer = new THREE.WebGLRenderer({ canvas: canvas, antialias: true });
    renderer.setSize(canvas.clientWidth, canvas.clientHeight);
    renderer.setClearColor(0x0f172a);
    
    const scene = new THREE.Scene();
    
    const camera = new THREE.PerspectiveCamera(40, canvas.clientWidth / canvas.clientHeight, 0.1, 100);
    camera.position.set(-4, 2, 6);
    camera.lookAt(0.5, 0.8, 0);
    
    const controls = new THREE.OrbitControls(camera, renderer.domElement);
    controls.enableZoom = false;
    
    scene.add(new THREE.AmbientLight(0xffffff, 0.7));
    
    // Draw Seat
    const seatGroup = new THREE.Group();
    const darkSlate = new THREE.MeshStandardMaterial({ color: 0x334155, roughness: 0.6 });
    
    // Cushion
    const cushion = new THREE.Mesh(new THREE.BoxGeometry(1.2, 0.2, 1.2), darkSlate);
    cushion.position.set(0, 0.2, 0);
    seatGroup.add(cushion);
    
    // Backrest
    const backrest = new THREE.Mesh(new THREE.BoxGeometry(0.2, 1.4, 1.1), darkSlate);
    backrest.position.set(-0.5, 0.9, 0);
    seatGroup.add(backrest);
    
    scene.add(seatGroup);
    
    // Dummy Driver Model
    dummyModel = new THREE.Group();
    const dummyMat = new THREE.MeshStandardMaterial({ color: 0x94a3b8, roughness: 0.4 });
    
    // Torso
    const torso = new THREE.Mesh(new THREE.BoxGeometry(0.4, 0.8, 0.6), dummyMat);
    torso.position.set(0, 0.7, 0);
    dummyModel.add(torso);
    
    // Head
    const head = new THREE.Mesh(new THREE.SphereGeometry(0.2, 16, 16), dummyMat);
    head.position.set(0.05, 1.25, 0);
    dummyModel.add(head);
    
    // Legs (Thighs)
    const legGeo = new THREE.BoxGeometry(0.7, 0.2, 0.2);
    const leftLeg = new THREE.Mesh(legGeo, dummyMat);
    leftLeg.position.set(0.35, 0.35, 0.2);
    const rightLeg = leftLeg.clone();
    rightLeg.position.z = -0.2;
    dummyModel.add(leftLeg);
    dummyModel.add(rightLeg);
    
    dummyModel.position.set(0, 0, 0);
    scene.add(dummyModel);
    
    // Seatbelt strap (drawn as a diagonal band)
    const beltGeo = new THREE.BoxGeometry(0.02, 1.0, 0.4);
    const beltMat = new THREE.MeshBasicMaterial({ color: 0xef4444, transparent: true, opacity: 0 });
    seatbeltStrapMesh = new THREE.Mesh(beltGeo, beltMat);
    // Position belt diagonal across torso
    seatbeltStrapMesh.position.set(0.12, 0.75, 0);
    seatbeltStrapMesh.rotation.z = -0.4;
    scene.add(seatbeltStrapMesh);
    
    function animate() {
        requestAnimationFrame(animate);
        renderer.render(scene, camera);
    }
    animate();
    
    window.addEventListener('resize', () => {
        camera.aspect = canvas.clientWidth / canvas.clientHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(canvas.clientWidth, canvas.clientHeight);
    });
}

function runSeatbeltSimulation(beltState) {
    if (!dummyModel || !seatbeltStrapMesh) return;
    
    // Kill Tweens
    gsap.killTweensOf(dummyModel.position);
    gsap.killTweensOf(dummyModel.rotation);
    
    // Reset positions
    dummyModel.position.set(0, 0, 0);
    dummyModel.rotation.set(0, 0, 0);
    
    const info = document.getElementById('seatbeltExplanation');
    
    if (beltState === 'unbuckled') {
        seatbeltStrapMesh.material.opacity = 0;
        info.innerHTML = `<span class="spinner-grow spinner-grow-sm text-danger" role="status"></span> <strong>Simulating crash without seatbelt...</strong>`;
        
        // Crash impact: dummy is thrown forward violently
        const tl = gsap.timeline({
            onComplete: () => {
                info.innerHTML = `<h6 class="text-danger fw-bold m-0"><i class="fa-solid fa-skull"></i> UNBUCKLED RESULT: EJECTED & CRITICAL</h6>
                                  <p class="text-secondary small m-0">Without a restraint, the occupant's forward inertia continues. The dummy flies forward out of the seat, colliding head-first with the windshield/dashboard at full velocity.</p>`;
            }
        });
        
        // Jolt slightly, then fly forward
        tl.to(dummyModel.position, { x: 0.1, duration: 0.2 });
        tl.to(dummyModel.position, { x: 2.2, y: 0.6, duration: 0.4, ease: "power1.out" });
        tl.to(dummyModel.rotation, { z: -1.2, duration: 0.4, ease: "power1.out" }, "<");
        
    } else if (beltState === 'buckled') {
        // Show seatbelt strap
        seatbeltStrapMesh.material.opacity = 0.95;
        info.innerHTML = `<span class="spinner-grow spinner-grow-sm text-success" role="status"></span> <strong>Simulating crash with seatbelt...</strong>`;
        
        const tl = gsap.timeline({
            onComplete: () => {
                info.innerHTML = `<h6 class="text-success fw-bold m-0"><i class="fa-solid fa-circle-check"></i> BUCKLED RESULT: SECURELY HELD</h6>
                                  <p class="text-secondary small m-0">The seatbelt holds the torso firmly in the seat. The occupant experiences a slight forward displacement (allowed by strap stretch) but is protected from structural impacts.</p>`;
            }
        });
        
        // Jolt forward slightly, then bounce back safely
        tl.to(dummyModel.position, { x: 0.35, duration: 0.2, ease: "power1.out" });
        tl.to(dummyModel.rotation, { z: -0.25, duration: 0.2, ease: "power1.out" }, "<");
        // Pull back
        tl.to(dummyModel.position, { x: 0, duration: 0.3, ease: "bounce.out" });
        tl.to(dummyModel.rotation, { z: 0, duration: 0.3, ease: "bounce.out" }, "<");
    }
}

// ========================================================
// 6. ROTATING 3D TRAFFIC SIGNS VIEWER
// ========================================================
function init3DSignModel(canvasId, type, label) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;
    
    const renderer = new THREE.WebGLRenderer({ canvas: canvas, antialias: true, alpha: true });
    renderer.setSize(canvas.clientWidth, canvas.clientHeight);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    
    const scene = new THREE.Scene();
    
    const camera = new THREE.PerspectiveCamera(40, 1, 0.1, 100);
    camera.position.set(0, 0, 3.2);
    
    // Lighting
    scene.add(new THREE.AmbientLight(0xffffff, 0.7));
    const light = new THREE.DirectionalLight(0xffffff, 0.8);
    light.position.set(2, 4, 3);
    scene.add(light);
    
    // Draw sign model based on type (warning, mandatory, informative)
    const signGroup = new THREE.Group();
    
    // Signpole
    const poleGeo = new THREE.CylinderGeometry(0.04, 0.04, 1.4, 8);
    const poleMat = new THREE.MeshStandardMaterial({ color: 0x475569, metalness: 0.5 });
    const pole = new THREE.Mesh(poleGeo, poleMat);
    pole.position.y = -0.4;
    signGroup.add(pole);
    
    // Generate Canvas Texture dynamically
    const texCanvas = document.createElement('canvas');
    texCanvas.width = 256;
    texCanvas.height = 256;
    const ctx = texCanvas.getContext('2d');
    
    // Clear canvas
    ctx.clearRect(0, 0, 256, 256);
    
    let signMesh;
    
    if (type === 'mandatory') {
        // Red circle with white background
        ctx.fillStyle = '#FFFFFF';
        ctx.beginPath();
        ctx.arc(128, 128, 110, 0, Math.PI * 2);
        ctx.fill();
        
        ctx.strokeStyle = '#EF4444';
        ctx.lineWidth = 18;
        ctx.stroke();
        
        // Draw icon text inside
        ctx.fillStyle = '#000000';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.font = 'bold 80px Outfit, sans-serif';
        ctx.fillText(label, 128, 128);
        
        const circleGeo = new THREE.CylinderGeometry(0.6, 0.6, 0.04, 32);
        circleGeo.rotateX(Math.PI/2);
        
        const texture = new THREE.CanvasTexture(texCanvas);
        const frontMat = new THREE.MeshStandardMaterial({ map: texture, roughness: 0.2 });
        const sideMat = new THREE.MeshStandardMaterial({ color: 0x94a3b8 });
        
        signMesh = new THREE.Mesh(circleGeo, [sideMat, frontMat, sideMat]); // front gets texture, sides gray
        
    } else if (type === 'warning') {
        // Yellow Triangle with black border
        ctx.fillStyle = '#F59E0B';
        ctx.beginPath();
        ctx.moveTo(128, 20);
        ctx.lineTo(236, 210);
        ctx.lineTo(20, 210);
        ctx.closePath();
        ctx.fill();
        
        ctx.strokeStyle = '#1E293B';
        ctx.lineWidth = 14;
        ctx.stroke();
        
        ctx.fillStyle = '#000000';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.font = 'bold 80px Outfit, sans-serif';
        ctx.fillText(label, 128, 140);
        
        // Draw triangular prism geometry manually or scale box
        const triangleGeo = new THREE.BoxGeometry(1.0, 1.0, 0.04);
        const texture = new THREE.CanvasTexture(texCanvas);
        const frontMat = new THREE.MeshStandardMaterial({ map: texture, roughness: 0.2 });
        const sideMat = new THREE.MeshStandardMaterial({ color: 0x94a3b8 });
        
        signMesh = new THREE.Mesh(triangleGeo, [sideMat, sideMat, sideMat, sideMat, frontMat, sideMat]);
        
    } else if (type === 'informative') {
        // Blue square
        ctx.fillStyle = '#3B82F6';
        ctx.fillRect(20, 20, 216, 216);
        ctx.strokeStyle = '#FFFFFF';
        ctx.lineWidth = 10;
        ctx.strokeRect(25, 25, 206, 206);
        
        ctx.fillStyle = '#FFFFFF';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.font = 'bold 85px Outfit, sans-serif';
        ctx.fillText(label, 128, 128);
        
        const rectGeo = new THREE.BoxGeometry(0.9, 0.9, 0.04);
        const texture = new THREE.CanvasTexture(texCanvas);
        const frontMat = new THREE.MeshStandardMaterial({ map: texture, roughness: 0.2 });
        const sideMat = new THREE.MeshStandardMaterial({ color: 0x94a3b8 });
        
        signMesh = new THREE.Mesh(rectGeo, [sideMat, sideMat, sideMat, sideMat, frontMat, sideMat]);
    }
    
    if (signMesh) {
        signMesh.position.y = 0.4;
        signGroup.add(signMesh);
    }
    
    scene.add(signGroup);
    
    function animate() {
        requestAnimationFrame(animate);
        // Rotate sign assembly slowly on Y axis
        signGroup.rotation.y += 0.015;
        renderer.render(scene, camera);
    }
    animate();
}
