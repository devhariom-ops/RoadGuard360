/* SafeRoads Core Utility Scripts */

// Multi-language translation dictionary
const translations = {
    'en': {
        'nav_home': 'Home',
        'nav_about': 'About Safety',
        'nav_rules': 'Traffic Rules',
        'nav_accidents': 'Accident Types',
        'nav_simulator': '3D Learning Zone',
        'nav_videos': 'Awareness Videos',
        'nav_quiz': 'Quiz & Cert',
        'nav_report': 'Report Hazards',
        'nav_emergency': 'Emergency Help',
        'nav_contact': 'Contact Us',
        'nav_login': 'Login',
        'nav_register': 'Register',
        'foot_quick_links': 'Quick Links',
        'foot_emergency_title': 'Emergency Hotlines',
        'foot_ambulance': 'Ambulance',
        'foot_police': 'Police',
        'foot_fire': 'Fire Brigade',
        'foot_feedback': 'Feedback',
        'foot_privacy': 'Privacy Policy',
        'hero_title': 'Riding Responsibly Saves Lives.',
        'hero_desc': 'SafeRoads is an interactive 3D platform designed to educate, test, and empower drivers and pedestrians. Dive into virtual traffic simulators, master signs, and earn your road safety certification.',
        'hero_cta_sim': 'Try 3D Simulator',
        'hero_cta_quiz': 'Take Safety Quiz',
        'stats_daily': 'Daily Accidents',
        'stats_fatal': 'Fatal Accidents',
        'stats_injuries': 'Injuries Per Year',
        'stats_saved': 'Lives Saved (Helmets)',
        'section_stats_title': 'The Grim Reality of Road Accidents',
        'report_hazard_btn': 'Report Dangerous Road'
    },
    'es': {
        'nav_home': 'Inicio',
        'nav_about': 'Sobre Seguridad',
        'nav_rules': 'Reglas de Tránsito',
        'nav_accidents': 'Tipos de Accidentes',
        'nav_simulator': 'Zona de Aprendizaje 3D',
        'nav_videos': 'Videos de Concienciación',
        'nav_quiz': 'Cuestionario y Certificado',
        'nav_report': 'Reportar Peligros',
        'nav_emergency': 'Ayuda de Emergencia',
        'nav_contact': 'Contáctenos',
        'nav_login': 'Iniciar Sesión',
        'nav_register': 'Registrarse',
        'foot_quick_links': 'Enlaces Rápidos',
        'foot_emergency_title': 'Líneas de Emergencia',
        'foot_ambulance': 'Ambulancia',
        'foot_police': 'Policía',
        'foot_fire': 'Cuerpo de Bomberos',
        'foot_feedback': 'Comentarios',
        'foot_privacy': 'Política de Privacidad',
        'hero_title': 'Conducir con Responsabilidad Salva Vidas.',
        'hero_desc': 'SafeRoads es una plataforma interactiva en 3D diseñada para educar, evaluar y capacitar a conductores y peatones. Sumérgete en simuladores virtuales, domina las señales y obtén tu certificación.',
        'hero_cta_sim': 'Probar simulador 3D',
        'hero_cta_quiz': 'Hacer el cuestionario',
        'stats_daily': 'Accidentes Diarios',
        'stats_fatal': 'Accidentes Fatales',
        'stats_injuries': 'Lesiones por Año',
        'stats_saved': 'Vidas Salvadas (Cascos)',
        'section_stats_title': 'La Cruda Realidad de los Accidentes de Tránsito',
        'report_hazard_btn': 'Reportar Vía Peligrosa'
    },
    'fr': {
        'nav_home': 'Accueil',
        'nav_about': 'Sécurité Routière',
        'nav_rules': 'Code de la Route',
        'nav_accidents': 'Types d\'Accidents',
        'nav_simulator': 'Zone d\'Apprentissage 3D',
        'nav_videos': 'Vidéos de Sensibilisation',
        'nav_quiz': 'Quiz & Certificat',
        'nav_report': 'Signaler un Danger',
        'nav_emergency': 'Urgences',
        'nav_contact': 'Nous Contacter',
        'nav_login': 'Connexion',
        'nav_register': 'S\'inscrire',
        'foot_quick_links': 'Liens Rapides',
        'foot_emergency_title': 'Numéros d\'Urgence',
        'foot_ambulance': 'Ambulance',
        'foot_police': 'Police',
        'foot_fire': 'Sapeurs-Pompiers',
        'foot_feedback': 'Commentaires',
        'foot_privacy': 'Charte de Confidentialité',
        'hero_title': 'Conduire Responsable Sauve des Vies.',
        'hero_desc': 'SafeRoads est une plateforme 3D interactive conçue pour éduquer, tester et responsabiliser les usagers. Découvrez nos simulateurs, maîtrisez les panneaux et obtenez votre certificat.',
        'hero_cta_sim': 'Essayer le simulateur 3D',
        'hero_cta_quiz': 'Faire le Quiz',
        'stats_daily': 'Accidents Quotidiens',
        'stats_fatal': 'Accidents Mortels',
        'stats_injuries': 'Blessés par An',
        'stats_saved': 'Vies Sauvées (Casque)',
        'section_stats_title': 'La Triste Réalité des Accidents de la Route',
        'report_hazard_btn': 'Signaler une Route Dangereuse'
    },
    'hi': {
        'nav_home': 'होम',
        'nav_about': 'सड़क सुरक्षा',
        'nav_rules': 'यातायात नियम',
        'nav_accidents': 'दुर्घटना के प्रकार',
        'nav_simulator': '3D सिमुलेशन जोन',
        'nav_videos': 'जागरूकता वीडियो',
        'nav_quiz': 'क्विज और प्रमाण-पत्र',
        'nav_report': 'खतरनाक सड़क रिपोर्ट',
        'nav_emergency': 'आपातकालीन सहायता',
        'nav_contact': 'संपर्क करें',
        'nav_login': 'लॉगिन',
        'nav_register': 'रजिस्टर',
        'foot_quick_links': 'क्विक लिंक्स',
        'foot_emergency_title': 'आपातकालीन नंबर',
        'foot_ambulance': 'एम्बुलेंस',
        'foot_police': 'पुलिस',
        'foot_fire': 'दमकल विभाग',
        'foot_feedback': 'प्रतिक्रिया',
        'foot_privacy': 'गोपनीयता नीति',
        'hero_title': 'जिम्मेदारी से गाड़ी चलाना जीवन बचाता है।',
        'hero_desc': 'SafeRoads चालकों और पैदल यात्रियों को शिक्षित, परीक्षित और सशक्त बनाने के लिए एक 3D इंटरैक्टिव प्लेटफॉर्म है। ट्रैफिक सिमुलेटर में गोता लगाएँ, संकेतों में महारत हासिल करें और सर्टिफिकेट प्राप्त करें।',
        'hero_cta_sim': '3D सिमुलेटर का प्रयास करें',
        'hero_cta_quiz': 'सुरक्षा क्विज लें',
        'stats_daily': 'दैनिक दुर्घटनाएँ',
        'stats_fatal': 'घातक दुर्घटनाएँ',
        'stats_injuries': 'प्रतिवर्ष चोटें',
        'stats_saved': 'बचे हुए जीवन (हेलमेट)',
        'section_stats_title': 'सड़क दुर्घटनाओं की कड़वी सच्चाई',
        'report_hazard_btn': 'खतरनाक सड़क की रिपोर्ट करें'
    }
};

// Global Initialization
document.addEventListener('DOMContentLoaded', () => {
    // 1. Initialize Active Language
    const savedLang = localStorage.getItem('language') || 'en';
    applyLanguage(savedLang);
    
    // 2. Initialize Theme Toggler Buttons
    const currentTheme = localStorage.getItem('theme') || 'dark';
    updateThemeUI(currentTheme);
    
    // 3. Initialize Statistic Counter Animations
    animateStatsCounters();
    
    // 4. Activating current Nav Link
    const currentPath = window.location.pathname;
    document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
        const href = link.getAttribute('href');
        if (href && currentPath.includes(href.replace('../', ''))) {
            link.classList.add('active');
        }
    });
});

// ========================================================
// THEME SWITCHER LOGIC
// ========================================================
function toggleTheme() {
    const html = document.documentElement;
    const currentTheme = html.getAttribute('data-bs-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
    html.setAttribute('data-bs-theme', newTheme);
    html.className = newTheme === 'dark' ? 'dark-mode' : 'light-mode';
    localStorage.setItem('theme', newTheme);
    updateThemeUI(newTheme);
    
    // Dispatch custom event for 3D canvases to update colors if needed
    window.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme: newTheme } }));
}

function updateThemeUI(theme) {
    const moonIcon = document.querySelector('.theme-icon-moon');
    const sunIcon = document.querySelector('.theme-icon-sun');
    
    if (moonIcon && sunIcon) {
        if (theme === 'dark') {
            moonIcon.classList.add('d-none');
            sunIcon.classList.remove('d-none');
        } else {
            moonIcon.classList.remove('d-none');
            sunIcon.classList.add('d-none');
        }
    }
}

// ========================================================
// MULTI-LANGUAGE SYSTEM
// ========================================================
function changeLanguage(lang) {
    localStorage.setItem('language', lang);
    applyLanguage(lang);
}

function applyLanguage(lang) {
    const currentLangLabel = document.getElementById('currentLangLabel');
    if (currentLangLabel) {
        currentLangLabel.textContent = lang.toUpperCase();
    }
    
    const dictionary = translations[lang] || translations['en'];
    
    document.querySelectorAll('[data-lang-key]').forEach(el => {
        const key = el.getAttribute('data-lang-key');
        if (dictionary[key]) {
            if (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') {
                el.placeholder = dictionary[key];
            } else {
                el.textContent = dictionary[key];
            }
        }
    });
}

// ========================================================
// STATS COUNTER ANIMATION
// ========================================================
function animateStatsCounters() {
    const counters = document.querySelectorAll('.stat-number');
    if (counters.length === 0) return;
    
    const countUp = (counter) => {
        const target = +counter.getAttribute('data-target');
        const duration = 2000; // 2 seconds
        const stepTime = 30;
        const steps = duration / stepTime;
        const increment = target / steps;
        let current = 0;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                counter.textContent = formatNumber(target);
                clearInterval(timer);
            } else {
                counter.textContent = formatNumber(Math.floor(current));
            }
        }, stepTime);
    };
    
    const formatNumber = (num) => {
        if (num >= 1000000) return (num / 1000000).toFixed(1) + 'M+';
        if (num >= 1000) return num.toLocaleString();
        return num;
    };
    
    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                countUp(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });
    
    counters.forEach(counter => observer.observe(counter));
}

// ========================================================
// GLOBAL HELPER NOTIFICATIONS
// ========================================================
function showNotification(title, text, icon = 'success') {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: title,
            text: text,
            icon: icon,
            background: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#0B0F19' : '#FFFFFF',
            color: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#F3F4F6' : '#111827',
            confirmButtonColor: '#00F2FE'
        });
    } else {
        alert(title + ": " + text);
    }
}
