/* ====================================================
   SAFEROADS – MAIN JAVASCRIPT
   Three.js 3D | GSAP | Quiz | Chatbot | Forms | Animations
==================================================== */

'use strict';

// ── Utility: debounce ──────────────────────────────────────────
const debounce = (fn, ms) => { let t; return (...a) => { clearTimeout(t); t = setTimeout(() => fn(...a), ms); }; };

// ====================================================
// 1. PRELOADER
// ====================================================
window.addEventListener('load', () => {
  const preloader = document.getElementById('preloader');
  setTimeout(() => { preloader.classList.add('fade-out'); }, 1200);
});

// ====================================================
// 2. THEME TOGGLE
// ====================================================
const themeToggle = document.getElementById('themeToggle');
const themeIcon   = document.getElementById('themeIcon');
let currentTheme  = localStorage.getItem('saferoads-theme') || 'dark';

function applyTheme(theme) {
  document.documentElement.setAttribute('data-theme', theme);
  themeIcon.className = theme === 'dark' ? 'fas fa-moon' : 'fas fa-sun';
  localStorage.setItem('saferoads-theme', theme);
  currentTheme = theme;
}
applyTheme(currentTheme);
themeToggle.addEventListener('click', () => applyTheme(currentTheme === 'dark' ? 'light' : 'dark'));

// ====================================================
// 3. NAVBAR SCROLL EFFECT & ACTIVE LINK
// ====================================================
const mainNav = document.getElementById('mainNav');
const navLinks = document.querySelectorAll('.nav-link[href^="#"]');
const sections = document.querySelectorAll('section[id]');

window.addEventListener('scroll', debounce(() => {
  mainNav.classList.toggle('scrolled', window.scrollY > 50);
  scrollTopBtn.classList.toggle('show', window.scrollY > 400);

  // Active nav link
  let current = '';
  sections.forEach(sec => {
    if (window.scrollY >= sec.offsetTop - 100) current = sec.id;
  });
  navLinks.forEach(link => {
    link.classList.remove('active');
    if (link.getAttribute('href') === '#' + current) link.classList.add('active');
  });

  // Animate fade-in-up elements
  document.querySelectorAll('.fade-in-up:not(.visible)').forEach(el => {
    const rect = el.getBoundingClientRect();
    if (rect.top < window.innerHeight - 60) el.classList.add('visible');
  });
}, 80));

// Smooth scroll
navLinks.forEach(link => {
  link.addEventListener('click', e => {
    e.preventDefault();
    const target = document.querySelector(link.getAttribute('href'));
    if (target) {
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      // Close mobile menu
      const bsCollapse = document.getElementById('navbarNav');
      if (bsCollapse.classList.contains('show')) {
        document.querySelector('.navbar-toggler').click();
      }
    }
  });
});

// ====================================================
// 4. SCROLL-TO-TOP BUTTON
// ====================================================
const scrollTopBtn = document.getElementById('scrollTopBtn');
scrollTopBtn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

// ====================================================
// 5. ANIMATED STATS COUNTER
// ====================================================
function animateCounter(el, target) {
  let current = 0;
  const step  = Math.ceil(target / 80);
  const timer = setInterval(() => {
    current += step;
    if (current >= target) { current = target; clearInterval(timer); }
    el.textContent = current.toLocaleString('en-IN');
  }, 18);
}

const statsObserver = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.querySelectorAll('.stat-num').forEach(el => {
        animateCounter(el, parseInt(el.dataset.target));
      });
      statsObserver.unobserve(entry.target);
    }
  });
}, { threshold: 0.3 });

const statsBar = document.querySelector('.stats-bar');
if (statsBar) statsObserver.observe(statsBar);

// Progress bars
const progressObserver = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.querySelectorAll('.progress-bar').forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0';
        setTimeout(() => { bar.style.width = width; }, 200);
      });
      progressObserver.unobserve(entry.target);
    }
  });
}, { threshold: 0.2 });

document.querySelectorAll('#about .progress').forEach(p => progressObserver.observe(p.closest('.progress-stat')));

// ====================================================
// 6. HERO THREE.JS CANVAS – PARTICLE ROAD NETWORK
// ====================================================
(function initHeroCanvas() {
  const canvas = document.getElementById('heroCanvas');
  if (!canvas || typeof THREE === 'undefined') return;

  const renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true });
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
  renderer.setSize(canvas.clientWidth, canvas.clientHeight);

  const scene  = new THREE.Scene();
  const camera = new THREE.PerspectiveCamera(75, canvas.clientWidth / canvas.clientHeight, 0.1, 1000);
  camera.position.set(0, 0, 5);

  // Particles
  const count  = 2000;
  const geo    = new THREE.BufferGeometry();
  const pos    = new Float32Array(count * 3);
  const colors = new Float32Array(count * 3);
  const c1 = new THREE.Color('#3b82f6');
  const c2 = new THREE.Color('#10b981');

  for (let i = 0; i < count; i++) {
    pos[i*3]   = (Math.random()-0.5)*20;
    pos[i*3+1] = (Math.random()-0.5)*12;
    pos[i*3+2] = (Math.random()-0.5)*10;
    const t = Math.random();
    const c = c1.clone().lerp(c2, t);
    colors[i*3]   = c.r;
    colors[i*3+1] = c.g;
    colors[i*3+2] = c.b;
  }
  geo.setAttribute('position', new THREE.BufferAttribute(pos, 3));
  geo.setAttribute('color',    new THREE.BufferAttribute(colors, 3));

  const mat  = new THREE.PointsMaterial({ size: 0.04, vertexColors: true, transparent: true, opacity: 0.7 });
  const pts  = new THREE.Points(geo, mat);
  scene.add(pts);

  // Road grid lines
  const lineMat = new THREE.LineBasicMaterial({ color: 0x1e40af, transparent: true, opacity: 0.15 });
  for (let i = -5; i <= 5; i++) {
    const hGeo = new THREE.BufferGeometry().setFromPoints([new THREE.Vector3(-10,i*1.2,0), new THREE.Vector3(10,i*1.2,0)]);
    const vGeo = new THREE.BufferGeometry().setFromPoints([new THREE.Vector3(i*2,-6,0), new THREE.Vector3(i*2,6,0)]);
    scene.add(new THREE.Line(hGeo, lineMat));
    scene.add(new THREE.Line(vGeo, lineMat));
  }

  let mouseX = 0, mouseY = 0;
  document.addEventListener('mousemove', e => {
    mouseX = (e.clientX / window.innerWidth  - 0.5) * 0.5;
    mouseY = (e.clientY / window.innerHeight - 0.5) * 0.5;
  });

  function heroAnimate() {
    requestAnimationFrame(heroAnimate);
    pts.rotation.y += 0.0008 + mouseX * 0.001;
    pts.rotation.x += 0.0003 - mouseY * 0.0005;
    renderer.render(scene, camera);
  }
  heroAnimate();

  window.addEventListener('resize', debounce(() => {
    renderer.setSize(canvas.clientWidth, canvas.clientHeight);
    camera.aspect = canvas.clientWidth / canvas.clientHeight;
    camera.updateProjectionMatrix();
  }, 200));
})();

// ====================================================
// 7. MINI CAR 3D (Hero Side)
// ====================================================
(function initMiniCar() {
  const canvas = document.getElementById('miniCarCanvas');
  if (!canvas || typeof THREE === 'undefined') return;

  const renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true });
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
  renderer.setSize(canvas.clientWidth, canvas.clientHeight);
  renderer.shadowMap.enabled = true;

  const scene  = new THREE.Scene();
  const camera = new THREE.PerspectiveCamera(60, 1, 0.1, 100);
  camera.position.set(4, 3, 4);
  camera.lookAt(0, 0, 0);

  // Lights
  scene.add(new THREE.AmbientLight(0x404060, 0.6));
  const dirLight = new THREE.DirectionalLight(0x6699ff, 1.5);
  dirLight.position.set(5, 8, 5);
  dirLight.castShadow = true;
  scene.add(dirLight);
  scene.add(new THREE.PointLight(0x10b981, 1, 10));

  // Car body
  const carGroup = new THREE.Group();
  const bodyGeo  = new THREE.BoxGeometry(2, 0.6, 1);
  const bodyMat  = new THREE.MeshPhongMaterial({ color: 0x1e40af, shininess: 100 });
  const body     = new THREE.Mesh(bodyGeo, bodyMat);
  body.position.y = 0.4;
  carGroup.add(body);

  // Roof
  const roofGeo = new THREE.BoxGeometry(1.2, 0.45, 0.85);
  const roof    = new THREE.Mesh(roofGeo, new THREE.MeshPhongMaterial({ color: 0x1e3a8a, shininess: 120 }));
  roof.position.set(0, 0.85, 0);
  carGroup.add(roof);

  // Windshield
  const wGeo  = new THREE.BoxGeometry(0.05, 0.35, 0.7);
  const wMat  = new THREE.MeshPhongMaterial({ color: 0x93c5fd, transparent: true, opacity: 0.6 });
  const front = new THREE.Mesh(wGeo, wMat);
  front.position.set(0.62, 0.78, 0);
  carGroup.add(front);
  const back  = new THREE.Mesh(wGeo, wMat);
  back.position.set(-0.62, 0.78, 0);
  carGroup.add(back);

  // Wheels
  const wheelGeo = new THREE.CylinderGeometry(0.25, 0.25, 0.15, 16);
  const wheelMat = new THREE.MeshPhongMaterial({ color: 0x1a1a2e });
  const wheelPositions = [[0.85,0.18,0.55],[0.85,0.18,-0.55],[-0.85,0.18,0.55],[-0.85,0.18,-0.55]];
  const wheels = wheelPositions.map(([x,y,z]) => {
    const w = new THREE.Mesh(wheelGeo, wheelMat);
    w.rotation.x = Math.PI / 2;
    w.position.set(x, y, z);
    carGroup.add(w);
    return w;
  });

  // Headlights
  const hlGeo = new THREE.SphereGeometry(0.08, 8, 8);
  const hlMat = new THREE.MeshPhongMaterial({ color: 0xffffff, emissive: 0xffffaa, emissiveIntensity: 1 });
  [-0.3, 0.3].forEach(z => {
    const hl = new THREE.Mesh(hlGeo, hlMat);
    hl.position.set(1.02, 0.4, z);
    carGroup.add(hl);
    scene.add(new THREE.PointLight(0xffffaa, 0.5, 3, 2));
  });

  scene.add(carGroup);

  // Road platform
  const roadGeo = new THREE.PlaneGeometry(8, 8);
  const roadMat = new THREE.MeshPhongMaterial({ color: 0x0f172a });
  const road    = new THREE.Mesh(roadGeo, roadMat);
  road.rotation.x = -Math.PI / 2;
  road.position.y = -0.05;
  scene.add(road);

  // Dashed road lines
  for (let i = -3; i <= 3; i += 1.5) {
    const dGeo = new THREE.PlaneGeometry(0.8, 0.1);
    const dMat = new THREE.MeshPhongMaterial({ color: 0xfacc15 });
    const dash = new THREE.Mesh(dGeo, dMat);
    dash.rotation.x = -Math.PI / 2;
    dash.position.set(i, 0.01, 0);
    scene.add(dash);
  }

  // Floating emergency lights
  const lights = [];
  [0x3b82f6, 0xef4444].forEach((c, i) => {
    const lg = new THREE.SphereGeometry(0.08, 8, 8);
    const lm = new THREE.MeshPhongMaterial({ color: c, emissive: c, emissiveIntensity: 2 });
    const lmesh = new THREE.Mesh(lg, lm);
    lmesh.position.set(i === 0 ? -0.25 : 0.25, 1.15, 0);
    carGroup.add(lmesh);
    lights.push({ mesh: lmesh, base: c, phase: i * Math.PI });
  });

  let frame = 0;
  function miniAnimate() {
    requestAnimationFrame(miniAnimate);
    frame++;
    carGroup.rotation.y = Math.sin(frame * 0.008) * 0.4;
    carGroup.position.y = Math.sin(frame * 0.016) * 0.05;
    wheels.forEach(w => { w.rotation.y += 0.05; });
    lights.forEach((l, i) => {
      const on = Math.sin(frame * 0.08 + l.phase) > 0;
      l.mesh.material.emissiveIntensity = on ? 2 : 0.1;
    });
    renderer.render(scene, camera);
  }
  miniAnimate();
})();

// ====================================================
// 8. 3D SCENE ZONE (Three.js interactive scenes)
// ====================================================
(function init3DZone() {
  const canvas = document.getElementById('scene3d');
  if (!canvas || typeof THREE === 'undefined') return;

  const renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: false });
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
  renderer.shadowMap.enabled = true;
  renderer.setClearColor(0x050810);

  const scene  = new THREE.Scene();
  const camera = new THREE.PerspectiveCamera(60, canvas.clientWidth / canvas.clientHeight, 0.1, 100);
  camera.position.set(0, 3, 8);
  camera.lookAt(0, 0, 0);

  // Lights
  const ambient = new THREE.AmbientLight(0x404060, 0.5);
  scene.add(ambient);
  const keyLight = new THREE.DirectionalLight(0xffffff, 1);
  keyLight.position.set(5, 10, 5);
  keyLight.castShadow = true;
  scene.add(keyLight);

  // Floor
  const floorGeo = new THREE.PlaneGeometry(20, 20);
  const floorMat = new THREE.MeshPhongMaterial({ color: 0x0a0a1a });
  const floor    = new THREE.Mesh(floorGeo, floorMat);
  floor.rotation.x = -Math.PI / 2;
  floor.receiveShadow = true;
  scene.add(floor);

  // Grid helper
  const grid = new THREE.GridHelper(20, 20, 0x1e3a8a, 0x0f1b36);
  scene.add(grid);

  // ── SCENE DATA ──────────────────────────────────────────────
  let currentScene  = 'traffic';
  let sceneObjects  = [];
  let animFrame     = null;
  let signalState   = 0; // 0=red, 1=yellow, 2=green
  let signalTimer   = 0;
  let helmetMode    = true;
  let drunkAngle    = 0;
  let crashPhase    = 0;
  let crashDir      = 1;

  function clearScene() {
    sceneObjects.forEach(obj => scene.remove(obj));
    sceneObjects = [];
  }

  // ── TRAFFIC SIGNAL SCENE ────────────────────────────────────
  function buildTrafficScene() {
    clearScene();
    signalState = 0;

    // Pole
    const poleGeo = new THREE.CylinderGeometry(0.08, 0.08, 5, 12);
    const poleMat = new THREE.MeshPhongMaterial({ color: 0x374151 });
    const pole    = new THREE.Mesh(poleGeo, poleMat);
    pole.position.set(0, 2.5, 0);
    scene.add(pole); sceneObjects.push(pole);

    // Housing
    const houseGeo = new THREE.BoxGeometry(0.8, 2.4, 0.5);
    const houseMat = new THREE.MeshPhongMaterial({ color: 0x1f2937 });
    const house    = new THREE.Mesh(houseGeo, houseMat);
    house.position.set(0, 5.3, 0);
    scene.add(house); sceneObjects.push(house);

    // Lights
    const signals = ['red', 'yellow', 'green'];
    const colors  = [0xff2222, 0xffcc00, 0x00cc44];
    const offColors = [0x440000, 0x443300, 0x004411];
    const lightMeshes = [];
    const pointLights = [];

    signals.forEach((name, i) => {
      const lGeo = new THREE.SphereGeometry(0.22, 16, 16);
      const lMat = new THREE.MeshPhongMaterial({ color: offColors[i], emissive: offColors[i], emissiveIntensity: 1 });
      const lMesh = new THREE.Mesh(lGeo, lMat);
      lMesh.position.set(0, 6.2 - i * 0.8, 0.2);
      scene.add(lMesh); sceneObjects.push(lMesh);
      lightMeshes.push(lMesh);

      const pLight = new THREE.PointLight(colors[i], 0, 3);
      pLight.position.copy(lMesh.position);
      scene.add(pLight); sceneObjects.push(pLight);
      pointLights.push(pLight);
    });

    // Road
    const roadGeo = new THREE.PlaneGeometry(4, 12);
    const roadMat = new THREE.MeshPhongMaterial({ color: 0x111827 });
    const road    = new THREE.Mesh(roadGeo, roadMat);
    road.rotation.x = -Math.PI / 2;
    road.position.set(3, 0.01, 0);
    scene.add(road); sceneObjects.push(road);

    // Zebra crossing
    for (let i = 0; i < 6; i++) {
      const zGeo = new THREE.PlaneGeometry(0.35, 1.8);
      const zMat = new THREE.MeshPhongMaterial({ color: i % 2 === 0 ? 0xffffff : 0x111827 });
      const z    = new THREE.Mesh(zGeo, zMat);
      z.rotation.x = -Math.PI / 2;
      z.position.set(1.5 + i * 0.36, 0.02, 4);
      scene.add(z); sceneObjects.push(z);
    }

    function updateSignal(state) {
      lightMeshes.forEach((m, i) => {
        const on = i === state;
        m.material.color.setHex(on ? colors[i] : offColors[i]);
        m.material.emissive.setHex(on ? colors[i] : 0x000000);
        m.material.emissiveIntensity = on ? 1.5 : 0;
        pointLights[i].intensity = on ? 2 : 0;
      });

      const names  = ['Traffic Signal: 🔴 RED – STOP', 'Traffic Signal: 🟡 YELLOW – GET READY', 'Traffic Signal: 🟢 GREEN – GO'];
      const label  = document.getElementById('sceneLabelText');
      if (label) label.textContent = names[state];
    }

    updateSignal(0);

    // Auto-cycle
    signalTimer = setInterval(() => {
      signalState = (signalState + 1) % 3;
      updateSignal(signalState);
    }, 3000);

    // Click to advance
    const btn = document.getElementById('sceneActionBtn');
    if (btn) {
      btn.onclick = () => {
        clearInterval(signalTimer);
        signalState = (signalState + 1) % 3;
        updateSignal(signalState);
        signalTimer = setInterval(() => {
          signalState = (signalState + 1) % 3;
          updateSignal(signalState);
        }, 3000);
      };
    }

    return { lightMeshes, pointLights, updateSignal };
  }

  // ── HELMET SCENE ────────────────────────────────────────────
  function buildHelmetScene() {
    clearScene();

    // Left: with helmet (safe)
    function makeRider(x, withHelmet) {
      const g = new THREE.Group();

      // Body
      const bodyGeo = new THREE.CylinderGeometry(0.25, 0.22, 1.2, 12);
      const bodyMat = new THREE.MeshPhongMaterial({ color: withHelmet ? 0x1e40af : 0x374151 });
      const body    = new THREE.Mesh(bodyGeo, bodyMat);
      body.position.y = 0.8;
      g.add(body);

      // Head
      const headGeo = new THREE.SphereGeometry(0.22, 16, 16);
      const headMat = new THREE.MeshPhongMaterial({ color: 0xfbbf24 });
      const head    = new THREE.Mesh(headGeo, headMat);
      head.position.y = 1.65;
      g.add(head);

      if (withHelmet) {
        // Helmet shell
        const hGeo = new THREE.SphereGeometry(0.28, 16, 16);
        const hMat = new THREE.MeshPhongMaterial({ color: 0x3b82f6, shininess: 200 });
        const hMesh = new THREE.Mesh(hGeo, hMat);
        hMesh.position.y = 1.68;
        g.add(hMesh);

        // Visor
        const vGeo = new THREE.SphereGeometry(0.22, 12, 12, 0, Math.PI, 0, Math.PI / 2);
        const vMat = new THREE.MeshPhongMaterial({ color: 0x93c5fd, transparent: true, opacity: 0.6 });
        const v    = new THREE.Mesh(vGeo, vMat);
        v.position.y = 1.65;
        g.add(v);

        // Safety glow
        const glow = new THREE.PointLight(0x10b981, 1.5, 2);
        glow.position.set(0, 1.8, 0);
        g.add(glow);

        // Floating text sprite
        const canvas2 = document.createElement('canvas');
        canvas2.width = 200; canvas2.height = 60;
        const ctx = canvas2.getContext('2d');
        ctx.fillStyle = 'rgba(16,185,129,0.9)';
        ctx.roundRect(0, 0, 200, 60, 10);
        ctx.fill();
        ctx.fillStyle = '#fff';
        ctx.font = 'bold 18px Arial';
        ctx.textAlign = 'center';
        ctx.fillText('✓ SAFE', 100, 38);
        const tex = new THREE.CanvasTexture(canvas2);
        const sprGeo = new THREE.PlaneGeometry(1.2, 0.35);
        const sprMat = new THREE.MeshBasicMaterial({ map: tex, transparent: true });
        const spr    = new THREE.Mesh(sprGeo, sprMat);
        spr.position.y = 2.4;
        g.add(spr);
        sceneObjects.push(spr);
      } else {
        // Danger glow
        const dglow = new THREE.PointLight(0xef4444, 1.5, 2);
        dglow.position.set(0, 1.8, 0);
        g.add(dglow);

        // Danger label
        const canvas2 = document.createElement('canvas');
        canvas2.width = 200; canvas2.height = 60;
        const ctx = canvas2.getContext('2d');
        ctx.fillStyle = 'rgba(239,68,68,0.9)';
        ctx.roundRect(0, 0, 200, 60, 10);
        ctx.fill();
        ctx.fillStyle = '#fff';
        ctx.font = 'bold 18px Arial';
        ctx.textAlign = 'center';
        ctx.fillText('✗ DANGER', 100, 38);
        const tex = new THREE.CanvasTexture(canvas2);
        const sprGeo = new THREE.PlaneGeometry(1.2, 0.35);
        const sprMat = new THREE.MeshBasicMaterial({ map: tex, transparent: true });
        const spr    = new THREE.Mesh(sprGeo, sprMat);
        spr.position.y = 2.4;
        g.add(spr);
        sceneObjects.push(spr);
      }

      g.position.set(x, 0.1, 0);
      scene.add(g);
      sceneObjects.push(g);
      return g;
    }

    const safeRider    = makeRider(-2.5, true);
    const dangerRider  = makeRider(2.5, false);

    // Divider
    const divGeo = new THREE.PlaneGeometry(0.05, 3);
    const divMat = new THREE.LineBasicMaterial({ color: 0x374151 });
    const divMesh = new THREE.Mesh(divGeo, new THREE.MeshPhongMaterial({ color: 0x374151 }));
    divMesh.position.set(0, 1.5, 0);
    scene.add(divMesh); sceneObjects.push(divMesh);

    const btn = document.getElementById('sceneActionBtn');
    if (btn) btn.onclick = () => { helmetMode = !helmetMode; };
    document.getElementById('sceneLabelText').textContent = 'Helmet Safety – Left: With Helmet | Right: Without Helmet';
    return { safeRider, dangerRider };
  }

  // ── SEATBELT SCENE ──────────────────────────────────────────
  function buildSeatbeltScene() {
    clearScene();
    crashPhase = 0; crashDir = 1;

    // Car
    const carGroup = new THREE.Group();

    const bodyGeo = new THREE.BoxGeometry(3, 0.8, 2);
    const body    = new THREE.Mesh(bodyGeo, new THREE.MeshPhongMaterial({ color: 0x1e40af, shininess: 120 }));
    body.position.y = 0.5;
    carGroup.add(body);

    const roofGeo = new THREE.BoxGeometry(2, 0.5, 1.8);
    const roof    = new THREE.Mesh(roofGeo, new THREE.MeshPhongMaterial({ color: 0x1e3a8a }));
    roof.position.y = 1.15;
    carGroup.add(roof);

    // Windshield
    const wGeo  = new THREE.BoxGeometry(0.05, 0.42, 1.6);
    const wMat  = new THREE.MeshPhongMaterial({ color: 0x93c5fd, transparent: true, opacity: 0.5 });
    const windshield = new THREE.Mesh(wGeo, wMat);
    windshield.position.set(1.02, 1.05, 0);
    carGroup.add(windshield);

    // Wheels
    const wGeom = new THREE.CylinderGeometry(0.3, 0.3, 0.2, 16);
    const wMat2 = new THREE.MeshPhongMaterial({ color: 0x111827 });
    [[1.1,0.3,1.1],[1.1,0.3,-1.1],[-1.1,0.3,1.1],[-1.1,0.3,-1.1]].forEach(([x,y,z]) => {
      const w = new THREE.Mesh(wGeom, wMat2);
      w.rotation.x = Math.PI/2;
      w.position.set(x,y,z);
      carGroup.add(w);
    });

    // Passenger (with seatbelt)
    const passGeo  = new THREE.CylinderGeometry(0.18, 0.16, 0.7, 10);
    const passMat  = new THREE.MeshPhongMaterial({ color: 0xfbbf24 });
    const passenger = new THREE.Mesh(passGeo, passMat);
    passenger.position.set(0.4, 1.2, 0);
    carGroup.add(passenger);

    // Seatbelt line
    const sbGeo = new THREE.CylinderGeometry(0.01, 0.01, 0.8, 4);
    const sbMat = new THREE.MeshPhongMaterial({ color: 0xfacc15 });
    const sb    = new THREE.Mesh(sbGeo, sbMat);
    sb.position.set(0.4, 1.1, 0.15);
    sb.rotation.z = 0.4;
    carGroup.add(sb);

    // Wall to crash into
    const wallGeo = new THREE.BoxGeometry(0.3, 3, 3);
    const wallMat = new THREE.MeshPhongMaterial({ color: 0xef4444, opacity: 0.8, transparent: true });
    const wall    = new THREE.Mesh(wallGeo, wallMat);
    wall.position.set(4, 1.5, 0);
    scene.add(wall); sceneObjects.push(wall);

    // Impact light
    const impactLight = new THREE.PointLight(0xef4444, 0, 4);
    impactLight.position.set(3, 1, 0);
    scene.add(impactLight); sceneObjects.push(impactLight);

    carGroup.position.set(-1, 0, 0);
    scene.add(carGroup); sceneObjects.push(carGroup);

    let crashing = false;
    const btn = document.getElementById('sceneActionBtn');
    if (btn) {
      btn.textContent = '🚗 Simulate Crash';
      btn.onclick = () => {
        crashing = true;
        crashPhase = 0;
      };
    }
    document.getElementById('sceneLabelText').textContent = 'Seatbelt Simulation – Click to simulate crash impact';

    return { carGroup, wall, impactLight, passenger, crashing: () => crashing, setCrash: v => { crashing = v; } };
  }

  // ── DRUNK DRIVING SCENE ──────────────────────────────────────
  function buildDrunkScene() {
    clearScene();
    drunkAngle = 0;

    // Road
    const rGeo = new THREE.PlaneGeometry(6, 20);
    const rMat = new THREE.MeshPhongMaterial({ color: 0x111827 });
    const road  = new THREE.Mesh(rGeo, rMat);
    road.rotation.x = -Math.PI / 2;
    road.position.set(0, 0.01, -3);
    scene.add(road); sceneObjects.push(road);

    // Lane markings
    for (let i = -8; i < 8; i += 2) {
      const lGeo = new THREE.PlaneGeometry(0.12, 0.8);
      const lMat = new THREE.MeshPhongMaterial({ color: 0xfacc15 });
      const lm = new THREE.Mesh(lGeo, lMat);
      lm.rotation.x = -Math.PI / 2;
      lm.position.set(0, 0.02, i);
      scene.add(lm); sceneObjects.push(lm);
    }

    // Left guardrail
    const grGeo = new THREE.BoxGeometry(0.1, 0.4, 20);
    const grMat = new THREE.MeshPhongMaterial({ color: 0x6b7280 });
    const grL   = new THREE.Mesh(grGeo, grMat);
    grL.position.set(-3, 0.2, -3);
    scene.add(grL); sceneObjects.push(grL);
    const grR = grL.clone(); grR.position.x = 3;
    scene.add(grR); sceneObjects.push(grR);

    // Drunk car
    const dCar = new THREE.Group();
    const dcBody = new THREE.BoxGeometry(2, 0.6, 1);
    const dcMat  = new THREE.MeshPhongMaterial({ color: 0xef4444, shininess: 100 });
    const dcBodyM = new THREE.Mesh(dcBody, dcMat);
    dcBodyM.position.y = 0.4; dCar.add(dcBodyM);

    const dcRoof = new THREE.BoxGeometry(1.2, 0.45, 0.85);
    const dcRoofM = new THREE.Mesh(dcRoof, new THREE.MeshPhongMaterial({ color: 0xb91c1c }));
    dcRoofM.position.y = 0.9; dCar.add(dcRoofM);

    [[0.75,0.18,0.55],[0.75,0.18,-0.55],[-0.75,0.18,0.55],[-0.75,0.18,-0.55]].forEach(([x,y,z]) => {
      const w = new THREE.Mesh(
        new THREE.CylinderGeometry(0.2, 0.2, 0.15, 12),
        new THREE.MeshPhongMaterial({ color: 0x1a1a2e })
      );
      w.rotation.x = Math.PI / 2; w.position.set(x, y, z);
      dCar.add(w);
    });

    dCar.position.set(0, 0, 5);
    dCar.rotation.y = Math.PI;
    scene.add(dCar); sceneObjects.push(dCar);

    // Beer bottle effect (wobble overlay light)
    const warnLight = new THREE.PointLight(0xef4444, 0.5, 6);
    scene.add(warnLight); sceneObjects.push(warnLight);

    // Oncoming car
    const oCar = dCar.clone();
    oCar.material = new THREE.MeshPhongMaterial({ color: 0x1e40af });
    oCar.rotation.y = 0;
    oCar.position.set(-1.5, 0, -12);
    scene.add(oCar); sceneObjects.push(oCar);

    document.getElementById('sceneActionBtn').textContent = '🍺 Toggle Effect';
    document.getElementById('sceneActionBtn').onclick = () => { drunkAngle = 0; };
    document.getElementById('sceneLabelText').textContent = 'Drunk Driving Simulation – See lane deviation effects';

    return { dCar, oCar, warnLight };
  }

  // ── SCENE MANAGER ────────────────────────────────────────────
  let sceneRefs = {};

  function loadScene(name) {
    clearInterval(signalTimer);
    currentScene = name;

    // Update UI
    document.querySelectorAll('.zone-tab-btn').forEach(b => b.classList.toggle('active', b.dataset.scene === name));
    document.querySelectorAll('.scene-desc-card').forEach(c => c.classList.toggle('active-scene', c.id === 'desc-' + name));

    const info = {
      traffic: {
        badge: '<i class="fas fa-traffic-light me-2"></i>Traffic Signal Simulator',
        title: 'Interactive Traffic Light System',
        desc: 'Watch the automatic signal cycle (Red→Yellow→Green). Click INTERACT to advance manually.',
        btnText: '<i class="fas fa-forward me-2"></i>Advance Signal',
      },
      helmet: {
        badge: '<i class="fas fa-hard-hat me-2"></i>Helmet Safety Demo',
        title: '3D Helmet vs No Helmet Comparison',
        desc: 'See the difference. Left: ISI certified helmet absorbs 69% impact. Right: No helmet = 7x death risk.',
        btnText: '<i class="fas fa-sync me-2"></i>Compare Mode',
      },
      seatbelt: {
        badge: '<i class="fas fa-chair me-2"></i>Seatbelt Crash Simulation',
        title: 'Seatbelt Impact Analysis',
        desc: 'Watch how a seatbelt prevents ejection during a 60 km/h collision. It reduces fatality by 45%.',
        btnText: '<i class="fas fa-car-crash me-2"></i>Simulate Crash',
      },
      drunk: {
        badge: '<i class="fas fa-beer me-2"></i>Drunk Driving Simulator',
        title: 'Impaired Driving Lane Deviation',
        desc: 'See how alcohol impairs lane control. At 0.08% BAC, reaction time slows by 50%. Wrong-side risk: 10x.',
        btnText: '<i class="fas fa-redo me-2"></i>Reset Simulation',
      }
    };

    const inf = info[name];
    document.querySelector('.scene-badge').innerHTML    = inf.badge;
    document.getElementById('sceneTitle').textContent  = inf.title;
    document.getElementById('sceneDesc').textContent   = inf.desc;
    document.getElementById('sceneActionBtn').innerHTML = inf.btnText;

    if (name === 'traffic') sceneRefs = buildTrafficScene();
    else if (name === 'helmet') sceneRefs = buildHelmetScene();
    else if (name === 'seatbelt') sceneRefs = buildSeatbeltScene();
    else if (name === 'drunk') sceneRefs = buildDrunkScene();
  }

  document.querySelectorAll('.zone-tab-btn').forEach(btn => {
    btn.addEventListener('click', () => loadScene(btn.dataset.scene));
  });
  document.querySelectorAll('.scene-desc-card').forEach(card => {
    const id = card.id.replace('desc-', '');
    card.addEventListener('click', () => loadScene(id));
  });

  loadScene('traffic');

  // ── MAIN 3D ANIMATE LOOP ─────────────────────────────────────
  let frame3d = 0;
  function animate3D() {
    requestAnimationFrame(animate3D);
    frame3d++;

    if (currentScene === 'traffic') {
      // Subtle camera orbit
      camera.position.x = Math.sin(frame3d * 0.004) * 3;
      camera.position.z = 6 + Math.sin(frame3d * 0.004) * 2;
      camera.lookAt(0, 3, 0);
    }
    else if (currentScene === 'helmet') {
      camera.position.set(0, 2.5, 6);
      camera.lookAt(0, 1, 0);
      // Bob helmet riders
      if (sceneRefs.safeRider)   sceneRefs.safeRider.position.y   = Math.sin(frame3d * 0.03) * 0.08;
      if (sceneRefs.dangerRider) sceneRefs.dangerRider.position.y = Math.sin(frame3d * 0.03 + 0.5) * 0.08;
    }
    else if (currentScene === 'seatbelt') {
      camera.position.set(0, 2, 8);
      camera.lookAt(0, 0.5, 0);
      if (sceneRefs.carGroup) {
        crashPhase += 0.015;
        const x = -1 + Math.min(crashPhase, 1) * 3.2;
        sceneRefs.carGroup.position.x = Math.min(x, 2.2);
        if (x >= 2 && sceneRefs.impactLight) {
          const intensity = Math.max(0, 1 - (crashPhase - 1.3) * 3);
          sceneRefs.impactLight.intensity = intensity * 3;
          // Shake
          sceneRefs.carGroup.rotation.z = Math.sin(frame3d * 0.5) * 0.05 * intensity;
        }
        if (crashPhase > 3) {
          crashPhase = 0;
          sceneRefs.carGroup.position.x = -1;
          sceneRefs.carGroup.rotation.z = 0;
          if (sceneRefs.impactLight) sceneRefs.impactLight.intensity = 0;
        }
      }
    }
    else if (currentScene === 'drunk') {
      camera.position.set(0, 4, 10);
      camera.lookAt(0, 0, 0);
      if (sceneRefs.dCar) {
        drunkAngle += 0.012;
        // Weaving motion
        sceneRefs.dCar.position.x = Math.sin(drunkAngle * 1.2) * 2;
        sceneRefs.dCar.rotation.y = Math.PI + Math.sin(drunkAngle * 1.5) * 0.15;
        sceneRefs.dCar.position.z -= 0.04;
        if (sceneRefs.dCar.position.z < -18) sceneRefs.dCar.position.z = 5;

        if (sceneRefs.oCar) {
          sceneRefs.oCar.position.z += 0.04;
          if (sceneRefs.oCar.position.z > 8) sceneRefs.oCar.position.z = -12;
        }
        if (sceneRefs.warnLight) {
          sceneRefs.warnLight.position.copy(sceneRefs.dCar.position);
          sceneRefs.warnLight.intensity = 0.4 + Math.abs(Math.sin(frame3d * 0.06)) * 0.6;
        }
      }
    }

    renderer.render(scene, camera);
  }
  animate3D();

  // Resize
  window.addEventListener('resize', debounce(() => {
    renderer.setSize(canvas.clientWidth, canvas.clientHeight);
    camera.aspect = canvas.clientWidth / canvas.clientHeight;
    camera.updateProjectionMatrix();
  }, 200));
})();

// ====================================================
// 9. TRAFFIC SIGNS FILTER
// ====================================================
document.querySelectorAll('.signs-filter-btn').forEach(btn => {
  btn.addEventListener('click', function () {
    document.querySelectorAll('.signs-filter-btn').forEach(b => b.classList.remove('active'));
    this.classList.add('active');
    const filter = this.dataset.filter;
    document.querySelectorAll('.sign-item').forEach(item => {
      if (filter === 'all' || item.classList.contains(filter)) {
        item.classList.remove('hidden');
        item.style.animation = 'none';
        void item.offsetWidth;
        item.style.animation = 'fadeIn .4s ease';
      } else {
        item.classList.add('hidden');
      }
    });
  });
});

// ====================================================
// 10. QUIZ SYSTEM
// ====================================================
const quizQuestions = [
  { q: "What does a RED traffic signal indicate?", opts: ["Speed up to cross", "STOP immediately and wait", "Slow down and continue", "Flash headlights"], ans: 1, exp: "Red signal means STOP completely. Proceed only when signal turns green." },
  { q: "What is the legal Blood Alcohol Concentration (BAC) limit for driving in India?", opts: ["50mg/100ml", "80mg/100ml", "30mg/100ml", "10mg/100ml"], ans: 2, exp: "The legal BAC limit in India is 30mg/100ml of blood under the Motor Vehicles Act." },
  { q: "Which helmet certification is mandatory for two-wheelers in India?", opts: ["BIS/ISI", "ISO 9001", "CE Mark", "DOT Certified"], ans: 0, exp: "BIS/ISI certified helmets are mandatory under the Motor Vehicles Act 2019 in India." },
  { q: "What should you do when an ambulance approaches from behind?", opts: ["Speed up to stay ahead", "Pull over to the left and stop", "Flash your hazard lights", "Continue at normal speed"], ans: 1, exp: "Always pull over to the left side and stop to allow emergency vehicles to pass." },
  { q: "A triangular road sign with a red border indicates:", opts: ["Mandatory instruction", "Informative direction", "Warning / Hazard ahead", "Prohibition"], ans: 2, exp: "Triangular signs with red borders are WARNING signs indicating potential hazards ahead." },
  { q: "What is the minimum safe following distance on a highway?", opts: ["10 meters", "20 meters", "50 meters", "5 meters"], ans: 2, exp: "At highway speeds (80-100 km/h), maintain at least 50 meters following distance." },
  { q: "Which lane should slow-moving vehicles use on a multi-lane road?", opts: ["Right lane", "Middle lane", "Left lane", "Any lane"], ans: 2, exp: "Slow vehicles must always drive in the left lane in India (left-hand traffic)." },
  { q: "What does a circular sign with a RED border and white interior indicate?", opts: ["Advisory speed limit", "Mandatory speed limit", "Prohibition (No entry, no parking, etc.)", "Warning sign"], ans: 2, exp: "Circular signs with red borders are PROHIBITION signs (e.g., No Entry, No Parking, Speed Limit)." },
  { q: "When should you use HIGH BEAM headlights?", opts: ["Always while driving at night", "In city traffic at night", "On dark roads with no oncoming traffic", "During rain"], ans: 2, exp: "Use high beams only on dark roads without oncoming traffic. Switch to low beam when vehicles approach." },
  { q: "What is the first thing to do after a road accident?", opts: ["Move vehicles immediately", "Take photos for insurance", "Ensure scene safety and call 108/112", "Find the at-fault driver"], ans: 2, exp: "First ensure scene safety, then immediately call 108 (ambulance) and 112 (emergency police)." },
  { q: "What is the speed limit for two-wheelers on national highways in India?", opts: ["60 km/h", "80 km/h", "100 km/h", "120 km/h"], ans: 1, exp: "The maximum speed for two-wheelers on national highways is 80 km/h." },
  { q: "How much does a seatbelt reduce the risk of death in a crash?", opts: ["10%", "25%", "45%", "60%"], ans: 2, exp: "Seatbelts reduce the risk of death by approximately 45% and serious injury by 50%." },
];

let quizData = {
  playerName: '',
  currentQ:   0,
  score:       0,
  answered:    false,
  timerInterval: null,
  timeLeft:    30,
  leaderboard: JSON.parse(localStorage.getItem('saferoads-leaderboard') || '[]'),
};

function showScreen(id) {
  document.querySelectorAll('.quiz-screen').forEach(s => s.classList.remove('active'));
  document.getElementById(id).classList.add('active');
}

function startQuiz() {
  const name = document.getElementById('quizPlayerName').value.trim();
  if (!name) { document.getElementById('quizPlayerName').focus(); return; }
  quizData.playerName = name;
  quizData.currentQ   = 0;
  quizData.score      = 0;
  showScreen('quizQuestion');
  loadQuestion();
}

function loadQuestion() {
  const qData = quizQuestions[quizData.currentQ];
  quizData.answered = false;
  const letters = ['A', 'B', 'C', 'D'];

  document.getElementById('questionText').textContent = `Q${quizData.currentQ + 1}. ${qData.q}`;
  document.getElementById('qNum').textContent         = `Question ${quizData.currentQ + 1}/${quizQuestions.length}`;
  document.getElementById('qProgress').style.width    = `${((quizData.currentQ) / quizQuestions.length) * 100}%`;
  document.getElementById('liveScore').textContent    = quizData.score;
  document.getElementById('quizFeedback').style.display = 'none';
  document.getElementById('nextQBtn').style.display    = 'none';

  const optsDiv = document.getElementById('quizOptions');
  optsDiv.innerHTML = '';
  qData.opts.forEach((opt, i) => {
    const btn = document.createElement('button');
    btn.className = 'quiz-opt';
    btn.innerHTML = `<span class="opt-letter">${letters[i]}</span>${opt}`;
    btn.addEventListener('click', () => selectAnswer(i));
    optsDiv.appendChild(btn);
  });

  startTimer();
}

function startTimer() {
  clearInterval(quizData.timerInterval);
  quizData.timeLeft = 30;
  const timerEl = document.getElementById('qTimer');
  timerEl.textContent = 30;
  timerEl.parentElement.classList.remove('warning');

  quizData.timerInterval = setInterval(() => {
    quizData.timeLeft--;
    timerEl.textContent = quizData.timeLeft;
    if (quizData.timeLeft <= 10) timerEl.parentElement.classList.add('warning');
    if (quizData.timeLeft <= 0) {
      clearInterval(quizData.timerInterval);
      if (!quizData.answered) {
        quizData.answered = true;
        showFeedback(false, -1, 'Time up! ⏰ ' + quizQuestions[quizData.currentQ].exp);
      }
    }
  }, 1000);
}

function selectAnswer(idx) {
  if (quizData.answered) return;
  quizData.answered = true;
  clearInterval(quizData.timerInterval);

  const correct = quizData.questions ? quizData.questions[quizData.currentQ].ans : quizQuestions[quizData.currentQ].ans;
  const isCorrect = idx === quizQuestions[quizData.currentQ].ans;
  if (isCorrect) quizData.score++;

  const opts = document.querySelectorAll('.quiz-opt');
  opts.forEach((opt, i) => {
    if (i === quizQuestions[quizData.currentQ].ans) opt.classList.add('correct');
    else if (i === idx && !isCorrect) opt.classList.add('wrong');
    opt.disabled = true;
  });

  document.getElementById('liveScore').textContent = quizData.score;
  showFeedback(isCorrect, idx, quizQuestions[quizData.currentQ].exp);
}

function showFeedback(correct, chosen, explanation) {
  const fb = document.getElementById('quizFeedback');
  fb.className = 'quiz-feedback ' + (correct ? 'correct' : 'wrong');
  fb.innerHTML = `${correct ? '✅ Correct!' : '❌ Wrong!'} — ${explanation}`;
  fb.style.display = 'block';
  document.getElementById('nextQBtn').style.display = 'inline-block';
}

document.getElementById('nextQBtn')?.addEventListener('click', () => {
  quizData.currentQ++;
  if (quizData.currentQ >= quizQuestions.length) {
    showResults();
  } else {
    loadQuestion();
  }
});

function showResults() {
  showScreen('quizResult');
  const score  = quizData.score;
  const total  = quizQuestions.length;
  const pct    = Math.round((score / total) * 100);

  // Emojis & messages
  let icon, title, msg;
  if (pct >= 90)      { icon = '🏆'; title = 'Road Safety Champion!';   msg = 'Outstanding! You are a true road safety expert.'; }
  else if (pct >= 70) { icon = '⭐'; title = 'Great Performance!';       msg = 'Well done! You have a strong understanding of road safety.'; }
  else if (pct >= 50) { icon = '👍'; title = 'Good Effort!';            msg = 'Keep learning. Road safety knowledge can save your life.'; }
  else                { icon = '📚'; title = 'Keep Practicing!';        msg = 'Review the traffic rules module and try again.'; }

  document.getElementById('resultIcon').textContent    = icon;
  document.getElementById('resultTitle').textContent   = title;
  document.getElementById('resultScore').textContent   = `${score}/${total}`;
  document.getElementById('resultMsg').textContent     = msg;

  // Breakdown
  document.getElementById('resultBreakdown').innerHTML = `
    <div class="res-item"><strong style="color:var(--accent-green)">${score}</strong><small>Correct</small></div>
    <div class="res-item"><strong style="color:var(--accent-red)">${total - score}</strong><small>Wrong</small></div>
    <div class="res-item"><strong style="color:var(--accent-blue)">${pct}%</strong><small>Score</small></div>
  `;

  // Certificate for 50%+
  if (pct >= 50) {
    document.getElementById('certBox').style.display = 'block';
    document.getElementById('certName').textContent  = quizData.playerName;
    document.getElementById('certScore').textContent = `${pct}% (${score}/${total})`;
    document.getElementById('certDate').textContent  = 'Issued on: ' + new Date().toLocaleDateString('en-IN', { year:'numeric', month:'long', day:'numeric' });
  } else {
    document.getElementById('certBox').style.display = 'none';
  }

  // Save to leaderboard
  quizData.leaderboard.push({ name: quizData.playerName, score, pct, date: new Date().toLocaleDateString() });
  quizData.leaderboard.sort((a, b) => b.score - a.score);
  quizData.leaderboard = quizData.leaderboard.slice(0, 10);
  localStorage.setItem('saferoads-leaderboard', JSON.stringify(quizData.leaderboard));
}

function downloadCert() {
  const cert = document.getElementById('certificate');
  const original = cert.style.cssText;
  cert.style.maxWidth = '100%';
  window.print();
  cert.style.cssText = original;
}
window.downloadCert = downloadCert;

function buildLeaderboard() {
  const list = document.getElementById('leaderboardList');
  if (!quizData.leaderboard.length) {
    list.innerHTML = '<p class="text-center text-muted">No scores yet. Take the quiz to appear here!</p>';
    return;
  }
  const medals = ['gold', 'silver', 'bronze'];
  list.innerHTML = quizData.leaderboard.map((entry, i) => `
    <div class="lb-item">
      <div class="lb-rank ${medals[i] || ''}">${i + 1}</div>
      <div class="lb-name">${entry.name}</div>
      <div style="font-size:.8rem;color:var(--text-muted)">${entry.date}</div>
      <div class="lb-score">${entry.score}/10 (${entry.pct}%)</div>
    </div>
  `).join('');
}

document.getElementById('startQuizBtn')?.addEventListener('click', startQuiz);
document.getElementById('retakeBtn')?.addEventListener('click', () => { showScreen('quizStart'); });
document.getElementById('leaderboardBtn')?.addEventListener('click', () => { buildLeaderboard(); showScreen('quizLeaderboard'); });
document.getElementById('backToQuizBtn')?.addEventListener('click', () => showScreen('quizResult'));

// ====================================================
// 11. ACCIDENT MODAL
// ====================================================
const accidentData = {
  headon: {
    title: '🚗💥🚗 Head-on Collision',
    causes: ['Wrong side driving', 'Driver fatigue', 'Overtaking on blind curves', 'Drunk driving', 'Night driving without lights'],
    examples: ['Accounts for 30% of all road deaths', 'Most fatal at speeds above 80 km/h', 'Highest severity of all accident types', 'Common on undivided highways'],
    prevention: ['Always drive on the left side', 'Never overtake on curves or hills', 'Use high beam signals on narrow roads', 'Never drive drowsy', 'Mandatory dividers on all highways']
  },
  rearend: {
    title: '🚗💥 Rear-end Collision',
    causes: ['Tailgating', 'Sudden braking', 'Phone use while driving', 'Brake failure', 'Fog/Poor visibility'],
    examples: ['Most common accident type in India', '2-second rule: maintain 2-second gap', 'Whiplash injury in 80% of rear-end cases', 'Common in peak traffic hours'],
    prevention: ['Maintain safe following distance', 'Do not use phone while driving', 'Service brakes regularly', 'Use fog lights in poor visibility', 'Avoid sudden braking']
  },
  sideimpact: {
    title: '🔀 Side-impact (T-Bone) Collision',
    causes: ['Red light jumping', 'Failure to give way', 'Speeding through intersections', 'Blind spots', 'Poor visibility'],
    examples: ['65% of intersection deaths are T-bone', 'Side offers least crash protection', 'Child passengers most vulnerable', 'Common at uncontrolled intersections'],
    prevention: ['Always obey traffic signals', 'Check both ways before crossing', 'Slow at all intersections', 'Install side airbags', 'Improve intersection visibility']
  },
  rollover: {
    title: '🔄 Rollover Accident',
    causes: ['Excessive speed on curves', 'Tire blowout at high speed', 'Sharp steering correction', 'Overloaded vehicles', 'SUV/van instability'],
    examples: ['SUVs are 3x more likely to rollover', 'Seatbelt prevents ejection in 95% of cases', 'Tire blowouts cause 7% of highway deaths', 'Often occur on elevated highways'],
    prevention: ['Maintain proper tire pressure', 'Do not exceed speed limits', 'Avoid sharp swerving at speed', 'Always wear seatbelt', 'Regular vehicle maintenance']
  },
  motorcycle: {
    title: '🏍️ Motorcycle Accident',
    causes: ['No helmet', 'Speeding', 'Weaving through traffic', 'Road hazards (potholes)', 'Poor visibility'],
    examples: ['29x more likely to die than car occupants', 'Helmet reduces head injury by 69%', '40% of deaths involve non-helmeted riders', 'Most common: 18-35 age group males'],
    prevention: ['Always wear ISI certified helmet', 'Wear full protective gear (jacket, gloves)', 'Never weave through traffic', 'Use headlights always', 'Avoid riding in blind spots']
  },
  pedestrian: {
    title: '🚶 Pedestrian Accident',
    causes: ['Jaywalking / No crosswalk use', 'Phone distraction', 'Poor visibility', 'Vehicles mounting footpaths', 'No pedestrian infrastructure'],
    examples: ['16% of all road fatalities are pedestrians', 'Risk doubles at night without reflective gear', '60% accidents in urban areas', '30% involve elderly pedestrians'],
    prevention: ['Always use zebra crossings', 'Wait for green pedestrian signal', 'Make eye contact with drivers', 'Wear bright clothing at night', 'Do not use phone while crossing']
  },
  drunk: {
    title: '🍺 Drunk Driving Accident',
    causes: ['Blood Alcohol above 30mg/100ml', 'Impaired reaction time', 'Poor judgment', 'Overconfidence', 'Night driving after drinking'],
    examples: ['15,000+ deaths annually from drunk driving', 'Reaction time up 50% at 0.08% BAC', 'Fine: ₹10,000 + 6 months jail', '40% of fatal highway accidents involve alcohol'],
    prevention: ['Zero tolerance: do not drink and drive', 'Plan a designated driver', 'Use cab/auto service', 'Breathalyzer self-test devices', 'Support police checkpoints']
  },
  speed: {
    title: '💨 Overspeeding Accident',
    causes: ['Ignoring speed limits', 'Racing behavior', 'Empty roads overconfidence', 'Highway hypnosis', 'Delayed response time at speed'],
    examples: ['62% of all road deaths involve overspeeding', 'At 100 km/h: stopping distance = 100m', '1000 km/h increase = 3x death risk', 'Speed cameras reduced accidents by 40%'],
    prevention: ['Strictly follow speed limits', 'Use cruise control on highways', 'Allow extra time for journeys', 'Respect speed humps', 'Never race on public roads']
  },
  distracted: {
    title: '📱 Distracted Driving Accident',
    causes: ['Mobile phone usage', 'Eating while driving', 'Music/infotainment', 'Talking to passengers', 'Navigation device usage'],
    examples: ['5 sec phone use = 110m blind at 80 km/h', '28% of accidents involve distraction', 'Texting: 23x crash risk increase', 'Fine: ₹5,000 + license suspension'],
    prevention: ['Phone on silent / Do Not Disturb mode', 'Use hands-free/voice commands only', 'Pull over to eat or make calls', 'Pre-set GPS before driving', 'Install driving mode apps']
  },
  night: {
    title: '🌙 Night-time Accident',
    causes: ['Poor visibility', 'Driver fatigue', 'High beam blinding', 'Reduced road marking visibility', 'Animals on road'],
    examples: ['40% of fatal accidents happen at night', 'Only 25% of traffic is at night', '3x higher fatality rate at night', '50% involve alcohol at night'],
    prevention: ['Drive slower at night', 'Use headlights at all times', 'Take rest breaks every 2 hours', 'Keep windshield clean', 'Avoid driving 2-4 AM (peak fatigue)']
  }
};

document.querySelectorAll('.accident-card').forEach(card => {
  card.addEventListener('click', () => {
    const type = card.dataset.accident;
    const data = accidentData[type];
    if (!data) return;

    document.getElementById('modalAccTitle').textContent = data.title;
    document.getElementById('modalCauses').innerHTML = data.causes.map(c => `<li>${c}</li>`).join('');
    document.getElementById('modalExamples').innerHTML = data.examples.map(e => `<li>${e}</li>`).join('');
    document.getElementById('modalPrevention').innerHTML = data.prevention.map(p => `<li>${p}</li>`).join('');

    new bootstrap.Modal(document.getElementById('accidentModal')).show();
  });
});

// ====================================================
// 12. REPORT FORM
// ====================================================
const fileUploadArea = document.getElementById('fileUploadArea');
fileUploadArea?.addEventListener('click', () => document.getElementById('repImage').click());
fileUploadArea?.addEventListener('dragover', e => { e.preventDefault(); fileUploadArea.style.borderColor = 'var(--accent-blue)'; });
fileUploadArea?.addEventListener('dragleave', () => { fileUploadArea.style.borderColor = ''; });
fileUploadArea?.addEventListener('drop', e => {
  e.preventDefault();
  fileUploadArea.style.borderColor = '';
  const file = e.dataTransfer.files[0];
  if (file) handleImageFile(file);
});

document.getElementById('repImage')?.addEventListener('change', e => {
  if (e.target.files[0]) handleImageFile(e.target.files[0]);
});

function handleImageFile(file) {
  if (file.size > 5 * 1024 * 1024) { alert('File too large. Max 5MB.'); return; }
  const reader = new FileReader();
  reader.onload = e => {
    document.getElementById('imgPreview').src = e.target.result;
    document.getElementById('imgPreviewWrap').style.display = 'block';
    document.getElementById('fileUploadArea').style.display = 'none';
  };
  reader.readAsDataURL(file);
}
document.getElementById('removeImg')?.addEventListener('click', () => {
  document.getElementById('imgPreviewWrap').style.display = 'none';
  document.getElementById('fileUploadArea').style.display = 'block';
  document.getElementById('repImage').value = '';
});

document.getElementById('reportForm')?.addEventListener('submit', e => {
  e.preventDefault();
  document.getElementById('reportForm').style.display = 'none';
  document.getElementById('reportSuccess').style.display = 'block';
  // Add to recent reports
  const type = document.getElementById('repType').value;
  const loc  = document.getElementById('repLocation').value;
  const sev  = document.querySelector('input[name="severity"]:checked')?.value || 'high';
  const newReport = document.createElement('div');
  newReport.className = 'report-item';
  newReport.innerHTML = `<span class="rep-badge ${sev}">${sev}</span><div><strong>${type}</strong><small>${loc} — just now</small></div>`;
  document.getElementById('recentReports').prepend(newReport);
});

window.resetReport = function() {
  document.getElementById('reportForm').style.display = 'block';
  document.getElementById('reportSuccess').style.display = 'none';
  document.getElementById('reportForm').reset();
  document.getElementById('imgPreviewWrap').style.display = 'none';
  document.getElementById('fileUploadArea').style.display = 'block';
};

// ====================================================
// 13. VOLUNTEER FORM VALIDATION
// ====================================================
document.getElementById('volunteerForm')?.addEventListener('submit', e => {
  e.preventDefault();
  let valid = true;

  const fields = [
    { id: 'volName',  errId: 'volNameErr',  rule: v => v.length >= 2,  msg: 'Please enter your full name (min 2 chars)' },
    { id: 'volEmail', errId: 'volEmailErr', rule: v => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v), msg: 'Please enter a valid email address' },
    { id: 'volPhone', errId: 'volPhoneErr', rule: v => /^[\+]?[\d\s\-]{10,15}$/.test(v), msg: 'Please enter a valid phone number (10+ digits)' },
    { id: 'volCity',  errId: 'volCityErr',  rule: v => v.length >= 2,  msg: 'Please enter your city' },
    { id: 'volCause', errId: 'volCauseErr', rule: v => v !== '',        msg: 'Please select a cause' },
  ];

  fields.forEach(f => {
    const el  = document.getElementById(f.id);
    const err = document.getElementById(f.errId);
    if (!f.rule(el.value.trim())) {
      err.textContent = f.msg;
      el.closest('.input-group-custom').querySelector('input, select').style.borderColor = 'var(--accent-red)';
      valid = false;
    } else {
      err.textContent = '';
      el.closest('.input-group-custom').querySelector('input, select').style.borderColor = '';
    }
  });

  if (valid) {
    document.getElementById('volunteerForm').style.display = 'none';
    document.getElementById('volunteerSuccess').style.display = 'block';
  }
});

// ====================================================
// 14. CONTACT FORM
// ====================================================
document.getElementById('contactForm')?.addEventListener('submit', e => {
  e.preventDefault();
  const msgDiv = document.getElementById('contactMsg');
  msgDiv.style.display = 'block';
  setTimeout(() => { msgDiv.style.display = 'none'; e.target.reset(); }, 4000);
});

// ====================================================
// 15. NEWSLETTER
// ====================================================
document.getElementById('subscribeBtn')?.addEventListener('click', () => {
  const email = document.getElementById('newsletterEmail').value.trim();
  const msg   = document.getElementById('newsletterMsg');
  if (/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
    msg.style.display = 'block';
    document.getElementById('newsletterEmail').value = '';
    setTimeout(() => { msg.style.display = 'none'; }, 3000);
  } else {
    document.getElementById('newsletterEmail').style.borderColor = 'var(--accent-red)';
    setTimeout(() => { document.getElementById('newsletterEmail').style.borderColor = ''; }, 2000);
  }
});

// ====================================================
// 16. TESTIMONIALS SLIDER
// ====================================================
(function initTestiSlider() {
  const track = document.getElementById('testiTrack');
  const dots   = document.getElementById('testiDots');
  if (!track) return;

  const cards  = track.querySelectorAll('.testi-card');
  let current  = 0;
  let autoPlay = null;

  // Create dots
  cards.forEach((_, i) => {
    const dot = document.createElement('div');
    dot.className = 'testi-dot' + (i === 0 ? ' active' : '');
    dot.addEventListener('click', () => goTo(i));
    dots.appendChild(dot);
  });

  function goTo(n) {
    current = (n + cards.length) % cards.length;
    track.style.transform = `translateX(-${current * 100}%)`;
    document.querySelectorAll('.testi-dot').forEach((d, i) => d.classList.toggle('active', i === current));
  }

  document.getElementById('testiPrev')?.addEventListener('click', () => goTo(current - 1));
  document.getElementById('testiNext')?.addEventListener('click', () => goTo(current + 1));

  autoPlay = setInterval(() => goTo(current + 1), 5000);
  track.addEventListener('mouseenter', () => clearInterval(autoPlay));
  track.addEventListener('mouseleave', () => { autoPlay = setInterval(() => goTo(current + 1), 5000); });
})();

// ====================================================
// 17. AI CHATBOT
// ====================================================
(function initChatbot() {
  const panel   = document.getElementById('chatbot-panel');
  const toggle  = document.getElementById('chatbot-toggle');
  const closeBtn = document.getElementById('chatbot-close');
  const input   = document.getElementById('chatbot-input');
  const sendBtn = document.getElementById('chatbot-send');
  const messages = document.getElementById('chatbot-messages');

  const kb = {
    'helmet': 'Helmets are MANDATORY for all two-wheeler riders and pillion passengers in India. ✅ ISI/BIS certified helmets are required. 🪖 Penalty: ₹1,000 fine + 3 months license suspension. Helmets reduce head injury risk by 69% and death risk by 42%.',
    'accident': 'If you witness/are in an accident: 1) Call 108 (ambulance) & 112 (police) 📞 2) Ensure scene safety 🔦 3) Do NOT move injured persons unless immediate danger 4) Apply pressure to wounds 5) Begin CPR if unconscious and not breathing. You can be a Good Samaritan!',
    'speed': 'India Speed Limits 🚗: Urban roads: 50 km/h | Two-wheelers highway: 80 km/h | Cars highway: 100 km/h | Trucks: 80 km/h | School zones: 25 km/h. Overspeeding causes 62% of road deaths. Each 1% increase in speed = 4% more injury risk.',
    'emergency': '🚨 Emergency Numbers India: Ambulance: 108 | Emergency (All): 112 | Police: 100 | Fire Brigade: 101 | Road Accident Relief: 1033 | Safety Helpline: 14567 | Women Safety: 1091. Save these in your phone today!',
    'seatbelt': 'Seatbelts 🪑: Mandatory for ALL occupants (not just front seat). Reduces death risk by 45% and serious injury by 50%. Penalty for not wearing: ₹1,000 per person. In a 60 km/h crash, an unbelted person hits the windshield with 2-tonne force!',
    'drunk': 'Drunk Driving 🍺: Legal BAC limit = 30mg/100ml blood. Penalty: ₹10,000 + up to 6 months jail (1st offense). At 0.08% BAC, reaction time slows by 50%, vision narrows by 43%. Use a cab or designated driver — no excuses!',
    'traffic light': '🚦 Traffic Signals: RED = Stop completely, wait | YELLOW = Slow down, prepare to stop or go | GREEN = Proceed with caution. Yellow light: Most people accelerate — this causes many intersection accidents. Red light jump fine: ₹5,000.',
    'lane': 'Lane Discipline 🛣️: Slow vehicles: LEFT lane | Medium speed: CENTER | Fast/overtaking: RIGHT lane. Never straddle lanes. Use indicators BEFORE lane changes. Emergency lane ONLY for ambulances/police/fire. Fine for emergency lane misuse: ₹10,000.',
    'child': 'Child Safety 👶: Children under 4 years must use car safety seats. Children under 14 should not sit in front seat. Rear-facing seats for infants. Never leave children in parked car. Airbag + front seat = fatal combination for children.',
    'road sign': 'Road Sign Types 🪧: TRIANGULAR (Red border) = Warning/Hazard ahead | CIRCULAR (Red border) = Prohibition (No entry, speed limit) | CIRCULAR (Blue) = Mandatory (Must follow) | RECTANGULAR (Green) = Informative. Learn all signs at our Traffic Signs section!',
    'first aid': 'Post-Accident First Aid 🩹: 1) Ensure safety, use hazard lights 2) Call 108 & 112 3) Do NOT remove helmets unless unconscious 4) No food/water for injured 5) Pressure on wounds to stop bleeding 6) If unconscious: 30 chest compressions + 2 breaths (CPR) 7) Stay with victim till help arrives.',
    'mobile': 'Phone While Driving 📱: Using phone = 4x crash risk. Fine: ₹5,000 + 3-month license suspension. At 80 km/h, a 5-second glance at phone = driving 110m blindfolded. Use Do Not Disturb (DND) driving mode. Hands-free is also risky!',
    'fine': 'Traffic Violation Fines (India 2019 Act) 💰: No helmet: ₹1,000 | No seatbelt: ₹1,000 | Drunk driving: ₹10,000 + jail | Overspeeding: ₹1,000-₹2,000 | Phone driving: ₹5,000 | Red light jump: ₹5,000 | Dangerous driving: ₹5,000 | Emergency lane: ₹10,000.',
  };

  function getBotResponse(msg) {
    msg = msg.toLowerCase();
    for (const [key, val] of Object.entries(kb)) {
      if (msg.includes(key) || key.split(' ').every(w => msg.includes(w))) return val;
    }
    // Fallback
    const fallbacks = [
      "Great question! 🚦 Road safety education saves lives. Try asking about: helmet rules, speed limits, traffic signs, or emergency numbers.",
      "I can help with road safety queries! 🛡️ Ask me about: drunk driving, seatbelt rules, accident first aid, or traffic fines.",
      "SafeBot here! 🤖 I'm trained on Indian traffic laws and road safety. Try: 'What is the helmet rule?' or 'Emergency numbers in India?'",
      "For specific road safety guidance, try asking about: lane discipline, child safety, mobile phone rules, or traffic light meanings!",
    ];
    return fallbacks[Math.floor(Math.random() * fallbacks.length)];
  }

  function addMessage(text, isBot) {
    const div = document.createElement('div');
    div.className = isBot ? 'bot-msg' : 'user-msg';
    if (isBot) div.innerHTML = `<i class="fas fa-robot me-2" style="color:var(--accent-blue)"></i>${text}`;
    else div.textContent = text;
    messages.appendChild(div);
    messages.scrollTop = messages.scrollHeight;
  }

  function sendMessage() {
    const msg = input.value.trim();
    if (!msg) return;
    addMessage(msg, false);
    input.value = '';

    // Typing indicator
    const typing = document.createElement('div');
    typing.className = 'bot-msg';
    typing.innerHTML = '<i class="fas fa-robot me-2" style="color:var(--accent-blue)"></i><em>SafeBot is thinking...</em>';
    messages.appendChild(typing);
    messages.scrollTop = messages.scrollHeight;

    setTimeout(() => {
      messages.removeChild(typing);
      addMessage(getBotResponse(msg), true);
    }, 800 + Math.random() * 500);
  }

  toggle.addEventListener('click', () => panel.classList.toggle('open'));
  closeBtn.addEventListener('click', () => panel.classList.remove('open'));
  sendBtn.addEventListener('click', sendMessage);
  input.addEventListener('keydown', e => { if (e.key === 'Enter') sendMessage(); });
  document.querySelectorAll('.quick-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      input.value = btn.dataset.q;
      sendMessage();
      panel.classList.add('open');
    });
  });
})();

// ====================================================
// 18. GSAP SCROLL ANIMATIONS (if GSAP available)
// ====================================================
if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
  gsap.registerPlugin(ScrollTrigger);

  // Hero elements entrance
  gsap.from('.hero-badge',    { duration: .8, y: -30, opacity: 0, delay: .2 });
  gsap.from('.hero-title',    { duration: 1,  y: 50,  opacity: 0, delay: .4 });
  gsap.from('.hero-subtitle', { duration: .8, y: 30,  opacity: 0, delay: .8 });
  gsap.from('.hero-buttons',  { duration: .8, y: 30,  opacity: 0, delay: 1.1 });

  // Section headers
  gsap.utils.toArray('.section-header').forEach(el => {
    gsap.from(el, {
      scrollTrigger: { trigger: el, start: 'top 85%' },
      y: 40, opacity: 0, duration: .8
    });
  });

  // Cards stagger
  gsap.utils.toArray('.about-card, .rule-card, .accident-card, .sign-card, .blog-card, .first-aid-card').forEach((el, i) => {
    gsap.from(el, {
      scrollTrigger: { trigger: el, start: 'top 90%' },
      y: 50, opacity: 0, duration: .6, delay: (i % 4) * 0.1
    });
  });

  // Emergency cards
  gsap.utils.toArray('.emergency-card').forEach((el, i) => {
    gsap.from(el, {
      scrollTrigger: { trigger: el, start: 'top 85%' },
      scale: .9, opacity: 0, duration: .6, delay: i * 0.15
    });
  });
}

// ====================================================
// 19. FADE-IN OBSERVER (fallback)
// ====================================================
const fadeObserver = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('visible');
      fadeObserver.unobserve(entry.target);
    }
  });
}, { threshold: 0.12, rootMargin: '0px 0px -50px 0px' });

document.querySelectorAll('.fade-in-up').forEach(el => fadeObserver.observe(el));

// ====================================================
// 20. ACCIDENT RISK PREDICTOR (ML-style UI)
// ====================================================
(function initRiskPredictor() {
  // Inject predictor UI into contact section as bonus feature
  const riskHTML = `
  <div id="riskPredictor" class="section-pad">
    <div class="container">
      <div class="section-header text-center">
        <span class="section-tag">AI Feature</span>
        <h2 class="section-title">Accident Risk <span class="gradient-text">Predictor</span></h2>
        <p class="section-sub">ML-powered tool to estimate your road accident risk based on current conditions</p>
      </div>
      <div class="row justify-content-center mt-4">
        <div class="col-lg-8">
          <div class="risk-predictor-card">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Vehicle Speed (km/h)</label>
                <input type="range" id="rSpeed" min="0" max="200" value="60" class="risk-slider" />
                <div class="d-flex justify-content-between"><small>0</small><strong id="rSpeedVal">60 km/h</strong><small>200</small></div>
              </div>
              <div class="col-md-6">
                <label class="form-label">Weather Conditions</label>
                <select id="rWeather" class="form-control custom-input"><option value="clear">Clear & Sunny</option><option value="cloudy">Cloudy</option><option value="rain">Heavy Rain</option><option value="fog">Dense Fog</option><option value="night">Night</option></select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Vehicle Type</label>
                <select id="rVehicle" class="form-control custom-input"><option value="car">Car / SUV</option><option value="bike">Two-Wheeler</option><option value="truck">Truck / Heavy</option><option value="pedestrian">Pedestrian</option></select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Road Condition</label>
                <select id="rRoad" class="form-control custom-input"><option value="good">Good / Smooth</option><option value="pothole">Potholed</option><option value="wet">Wet / Slippery</option><option value="construction">Under Construction</option></select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Driver Condition</label>
                <select id="rDriver" class="form-control custom-input"><option value="alert">Fully Alert</option><option value="phone">Using Phone</option><option value="tired">Drowsy / Tired</option><option value="drunk">Alcohol Influenced</option></select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Traffic Density</label>
                <select id="rTraffic" class="form-control custom-input"><option value="low">Low Traffic</option><option value="medium">Medium Traffic</option><option value="high">High Traffic</option><option value="jam">Traffic Jam</option></select>
              </div>
              <div class="col-12">
                <button class="btn btn-report-submit w-100" id="predictRiskBtn">
                  <i class="fas fa-brain me-2"></i>Predict Accident Risk
                </button>
              </div>
            </div>
            <div id="riskResult" style="display:none" class="mt-4">
              <div class="risk-gauge-container">
                <div class="risk-gauge">
                  <div class="risk-needle" id="riskNeedle"></div>
                  <div class="risk-percentage" id="riskPct"></div>
                  <div class="risk-label-text" id="riskLevelLabel"></div>
                </div>
                <canvas id="riskGaugeCanvas" width="300" height="160"></canvas>
              </div>
              <div class="risk-advice" id="riskAdvice"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>`;

  const contactSection = document.getElementById('contact');
  if (contactSection) {
    const div = document.createElement('div');
    div.innerHTML = riskHTML;
    contactSection.insertAdjacentElement('beforebegin', div.firstChild);
  }

  // Slider
  setTimeout(() => {
    const slider = document.getElementById('rSpeed');
    const valEl  = document.getElementById('rSpeedVal');
    slider?.addEventListener('input', () => { valEl.textContent = slider.value + ' km/h'; });

    document.getElementById('predictRiskBtn')?.addEventListener('click', () => {
      const speed   = parseInt(document.getElementById('rSpeed').value);
      const weather = document.getElementById('rWeather').value;
      const vehicle = document.getElementById('rVehicle').value;
      const road    = document.getElementById('rRoad').value;
      const driver  = document.getElementById('rDriver').value;
      const traffic = document.getElementById('rTraffic').value;

      // Decision tree scoring
      let risk = 0;
      risk += Math.min(speed / 2, 40);
      risk += { clear: 0, cloudy: 5, rain: 20, fog: 30, night: 15 }[weather] || 0;
      risk += { car: 5, bike: 15, truck: 10, pedestrian: 20 }[vehicle] || 0;
      risk += { good: 0, pothole: 15, wet: 25, construction: 20 }[road] || 0;
      risk += { alert: 0, phone: 25, tired: 30, drunk: 45 }[driver] || 0;
      risk += { low: 0, medium: 5, high: 10, jam: 15 }[traffic] || 0;
      risk = Math.min(Math.round(risk), 100);

      // Draw gauge
      drawGauge(risk);

      const levelEl  = document.getElementById('riskLevelLabel');
      const adviceEl = document.getElementById('riskAdvice');

      let level, advice, color;
      if (risk < 25)       { level = 'LOW RISK';      color = '#10b981'; advice = '✅ Conditions are relatively safe. Stay alert, follow all traffic rules, and keep safe following distance.'; }
      else if (risk < 50)  { level = 'MODERATE RISK'; color = '#facc15'; advice = '⚠️ Moderate risk detected. Reduce speed by 20%, increase following distance, and stay extra alert for hazards.'; }
      else if (risk < 75)  { level = 'HIGH RISK';     color = '#f97316'; advice = '🚨 High risk! Significantly reduce speed. Consider stopping at the next safe location. Avoid this route if possible.'; }
      else                 { level = 'CRITICAL RISK';  color = '#ef4444'; advice = '🛑 CRITICAL: Do not drive under these conditions! Pull over immediately. Call for help. Your life is at risk.'; }

      levelEl.textContent = level;
      levelEl.style.color = color;
      document.getElementById('riskPct').textContent  = risk + '%';
      document.getElementById('riskPct').style.color  = color;
      adviceEl.innerHTML = `<div class="risk-advice-box" style="border-color:${color};background:${color}12"><p>${advice}</p></div>`;
      document.getElementById('riskResult').style.display = 'block';
    });

    function drawGauge(pct) {
      const canvas = document.getElementById('riskGaugeCanvas');
      if (!canvas) return;
      const ctx = canvas.getContext('2d');
      const cx = 150, cy = 140, r = 110;

      ctx.clearRect(0, 0, 300, 160);

      // Background arc
      ctx.beginPath(); ctx.arc(cx, cy, r, Math.PI, 2 * Math.PI, false);
      ctx.lineWidth = 20; ctx.strokeStyle = 'rgba(255,255,255,0.05)'; ctx.stroke();

      // Color segments
      const segs = [
        [0, 0.25, '#10b981'],
        [0.25, 0.5, '#facc15'],
        [0.5, 0.75, '#f97316'],
        [0.75, 1, '#ef4444'],
      ];
      segs.forEach(([s, e, c]) => {
        ctx.beginPath();
        ctx.arc(cx, cy, r, Math.PI + s * Math.PI, Math.PI + e * Math.PI, false);
        ctx.lineWidth = 20; ctx.strokeStyle = c + '80'; ctx.stroke();
      });

      // Progress arc
      const progress = pct / 100;
      ctx.beginPath();
      ctx.arc(cx, cy, r, Math.PI, Math.PI + progress * Math.PI, false);
      ctx.lineWidth = 20;
      const grad = ctx.createLinearGradient(0, 0, 300, 0);
      grad.addColorStop(0, '#10b981'); grad.addColorStop(0.5, '#facc15'); grad.addColorStop(1, '#ef4444');
      ctx.strokeStyle = grad; ctx.stroke();

      // Needle
      const angle = Math.PI + progress * Math.PI;
      const nx = cx + (r - 10) * Math.cos(angle);
      const ny = cy + (r - 10) * Math.sin(angle);
      ctx.beginPath(); ctx.moveTo(cx, cy); ctx.lineTo(nx, ny);
      ctx.lineWidth = 3; ctx.strokeStyle = '#fff'; ctx.stroke();
      ctx.beginPath(); ctx.arc(cx, cy, 8, 0, 2 * Math.PI);
      ctx.fillStyle = '#fff'; ctx.fill();
    }
  }, 500);
})();

// ====================================================
// 21. INITIALIZE FADE ELEMENTS
// ====================================================
document.addEventListener('DOMContentLoaded', () => {
  // Trigger initial check
  window.dispatchEvent(new Event('scroll'));

  // Add CSS for risk predictor
  const style = document.createElement('style');
  style.textContent = `
    .risk-predictor-card {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      padding: 32px;
    }
    .risk-slider {
      -webkit-appearance: none;
      width: 100%;
      height: 6px;
      border-radius: 3px;
      background: var(--bg-alt);
      outline: none;
      margin: 8px 0;
    }
    .risk-slider::-webkit-slider-thumb {
      -webkit-appearance: none;
      width: 20px; height: 20px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--accent-blue), var(--accent-green));
      cursor: pointer;
      box-shadow: 0 2px 8px rgba(59,130,246,.4);
    }
    .risk-gauge-container { display: flex; justify-content: center; margin-bottom: 20px; position: relative; }
    #riskGaugeCanvas { display: block; }
    .risk-percentage {
      position: absolute; bottom: 0; left: 50%; transform: translateX(-50%);
      font-family: 'Orbitron', sans-serif; font-size: 2rem; font-weight: 900;
    }
    .risk-label-text {
      position: absolute; bottom: -25px; left: 50%; transform: translateX(-50%);
      font-family: 'Orbitron', sans-serif; font-size: .8rem; font-weight: 700;
      letter-spacing: .1em; white-space: nowrap;
    }
    .risk-advice-box {
      border: 1px solid; border-radius: 12px; padding: 16px;
    }
    .risk-advice-box p { margin: 0; font-family: 'Rajdhani', sans-serif; font-size: 1rem; font-weight: 600; }
    @keyframes fadeIn { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }
  `;
  document.head.appendChild(style);
});
