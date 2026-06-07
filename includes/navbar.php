<?php
$user = get_logged_in_user();
?>
<nav class="navbar navbar-expand-lg sticky-top custom-navbar">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="<?php echo BASE_PATH; ?>index.php">
            <div class="logo-icon me-2">
                <i class="fa-solid fa-triangle-exclamation text-danger animated-logo"></i>
            </div>
            <span class="brand-text">Safe<span class="text-info">Roads</span></span>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
            <ul class="navbar-nav w-100 justify-content-evenly align-items-center mb-2 mb-lg-0 me-3">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_PATH; ?>index.php" data-lang-key="nav_home">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_PATH; ?>pages/about.php" data-lang-key="nav_about">About Safety</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_PATH; ?>pages/rules.php" data-lang-key="nav_rules">Traffic Rules</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_PATH; ?>pages/accidents.php" data-lang-key="nav_accidents">Accidents</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-warning fw-bold" href="<?php echo BASE_PATH; ?>pages/simulator.php">
                        <i class="fa-solid fa-cube me-1"></i> <span data-lang-key="nav_simulator">3D Zone</span>
                    </a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_PATH; ?>pages/videos.php" data-lang-key="nav_videos">Videos</a>
                </li> -->
                <li class="nav-item">
                    <a class="nav-link text-info" href="<?php echo BASE_PATH; ?>pages/report.php">
                        <i class="fa-solid fa-map-location-dot me-1"></i> <span data-lang-key="nav_report">Report Hazards</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_PATH; ?>pages/emergency.php" data-lang-key="nav_emergency">Emergency</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_PATH; ?>pages/feedback.php" data-lang-key="nav_contact">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-sm btn-outline-danger text-danger border-danger border-opacity-40 fw-bold px-3" href="<?php echo BASE_PATH; ?>admin/dashboard.php">
                        <i class="fa-solid fa-gauge me-1"></i> Admin Panel
                    </a>
                </li>
            </ul>
            
            <div class="d-flex align-items-center gap-2 navbar-controls">
                <!-- Multi-Language Selector -->
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle d-flex align-items-center gap-1" type="button" id="langDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-globe"></i> <span id="currentLangLabel">EN</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="langDropdown">
                        <li><a class="dropdown-item" href="#" onclick="changeLanguage('en')">English (EN)</a></li>
                        <li><a class="dropdown-item" href="#" onclick="changeLanguage('es')">Español (ES)</a></li>
                        <li><a class="dropdown-item" href="#" onclick="changeLanguage('fr')">Français (FR)</a></li>
                        <li><a class="dropdown-item" href="#" onclick="changeLanguage('hi')">हिन्दी (HI)</a></li>
                    </ul>
                </div>
                
                <!-- Dark Mode Toggle Button -->
                <button class="btn btn-outline-secondary btn-sm" id="themeToggleBtn" onclick="toggleTheme()" title="Toggle Theme">
                    <i class="fa-solid fa-moon theme-icon-moon"></i>
                    <i class="fa-solid fa-sun theme-icon-sun d-none"></i>
                </button>
            </div>
        </div>
    </div>
</nav>
