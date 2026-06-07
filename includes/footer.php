</main> <!-- End of .main-content -->

<footer class="footer mt-auto py-4 custom-footer">
    <div class="container text-center text-md-start">
        <div class="row">
            <div class="col-md-4 mb-3 mb-md-0">
                <h5 class="text-white fw-bold mb-3">
                    <i class="fa-solid fa-triangle-exclamation text-danger me-2"></i>SafeRoads
                </h5>
                <p class="text-muted small">
                    An interactive, 3D educational platform raising awareness about road safety, traffic regulations, and accident prevention. Let's make roads safer together.
                </p>
                <div class="footer-socials d-flex gap-3 justify-content-center justify-content-md-start">
                    <a href="#" class="text-muted" title="Facebook"><i class="fa-brands fa-facebook fs-5"></i></a>
                    <a href="#" class="text-muted" title="Twitter"><i class="fa-brands fa-twitter fs-5"></i></a>
                    <a href="#" class="text-muted" title="YouTube"><i class="fa-brands fa-youtube fs-5"></i></a>
                    <a href="#" class="text-muted" title="LinkedIn"><i class="fa-brands fa-linkedin fs-5"></i></a>
                </div>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <h5 class="text-white fw-bold mb-3" data-lang-key="foot_quick_links">Quick Links</h5>
                <ul class="list-unstyled footer-links row">
                    <div class="col-6">
                        <li><a href="<?php echo BASE_PATH; ?>index.php" data-lang-key="nav_home">Home</a></li>
                        <li><a href="<?php echo BASE_PATH; ?>pages/about.php" data-lang-key="nav_about">About Safety</a></li>
                        <li><a href="<?php echo BASE_PATH; ?>pages/rules.php" data-lang-key="nav_rules">Traffic Rules</a></li>
                        <li><a href="<?php echo BASE_PATH; ?>pages/accidents.php" data-lang-key="nav_accidents">Accidents</a></li>
                    </div>
                    <div class="col-6">
                        <li><a href="<?php echo BASE_PATH; ?>pages/simulator.php" data-lang-key="nav_simulator">3D Zone</a></li>
                        <li><a href="<?php echo BASE_PATH; ?>pages/quiz.php" data-lang-key="nav_quiz">Take Quiz</a></li>
                        <li><a href="<?php echo BASE_PATH; ?>pages/report.php" data-lang-key="nav_report">Report Hazards</a></li>
                        <li><a href="<?php echo BASE_PATH; ?>pages/emergency.php" data-lang-key="nav_emergency">Emergency</a></li>
                    </div>
                </ul>
            </div>
            <div class="col-md-4">
                <h5 class="text-white fw-bold mb-3" data-lang-key="foot_emergency_title">Emergency Hotlines</h5>
                <ul class="list-unstyled text-muted small">
                    <li class="mb-2">
                        <i class="fa-solid fa-truck-medical text-danger me-2"></i>
                        <strong><span data-lang-key="foot_ambulance">Ambulance</span>:</strong> <a href="tel:108" class="text-danger fw-bold text-decoration-none">108</a>
                    </li>
                    <li class="mb-2">
                        <i class="fa-solid fa-shield-halved text-info me-2"></i>
                        <strong><span data-lang-key="foot_police">Police</span>:</strong> <a href="tel:112" class="text-info fw-bold text-decoration-none">112 / 100</a>
                    </li>
                    <li class="mb-2">
                        <i class="fa-solid fa-fire-extinguisher text-warning me-2"></i>
                        <strong><span data-lang-key="foot_fire">Fire Brigade</span>:</strong> <a href="tel:101" class="text-warning fw-bold text-decoration-none">101</a>
                    </li>
                </ul>
            </div>
        </div>
        <hr class="border-secondary my-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center text-muted small">
            <p class="mb-2 mb-md-0">&copy; 2026 SafeRoads Foundation. All rights reserved.</p>
            <div class="d-flex gap-3">
                <a href="<?php echo BASE_PATH; ?>pages/feedback.php" class="text-muted text-decoration-none" data-lang-key="foot_feedback">Feedback</a>
                <span>&bull;</span>
                <a href="#" class="text-muted text-decoration-none" data-lang-key="foot_privacy">Privacy Policy</a>
            </div>
        </div>
    </div>
</footer>

<!-- ======================================================== -->
<!-- FLOATING INTERACTIVE WIDGETS -->
<!-- ======================================================== -->

<!-- AI Chatbot Floating Trigger & Box (Bottom-Right) -->
<div class="chatbot-container position-fixed bottom-0 end-0 m-4 z-3">
    <!-- Trigger Button -->
    <button class="btn btn-chatbot shadow-lg rounded-circle d-flex align-items-center justify-content-center" id="chatbotToggleBtn" onclick="toggleChatbot()" title="Ask AI Assistant">
        <i class="fa-solid fa-robot fs-5"></i>
    </button>
    
    <!-- Chatbox Window -->
    <div class="chatbot-window card shadow-lg d-none border-secondary" id="chatbotWindow">
        <!-- Header -->
        <div class="card-header bg-gradient-info text-white d-flex justify-content-between align-items-center py-2 px-3">
            <div class="d-flex align-items-center gap-2">
                <i class="fa-solid fa-robot text-dark fs-5"></i>
                <div>
                    <h6 class="m-0 text-dark fw-bold">Roady AI</h6>
                    <small class="m-0 text-dark-50" style="font-size: 10px;">Road Safety Assistant</small>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-link btn-sm text-dark p-0" onclick="readLastChatResponse()" title="Read last answer aloud">
                    <i class="fa-solid fa-volume-high"></i>
                </button>
                <button class="btn btn-close btn-close-white btn-sm" onclick="toggleChatbot()" aria-label="Close"></button>
            </div>
        </div>
        
        <!-- Messages Area -->
        <div class="card-body chatbot-messages p-3" id="chatbotMessages">
            <div class="chatbot-msg bot-msg">
                <div class="msg-bubble">
                    Hello! I'm Roady, your road safety assistant. How can I help you today? You can ask me about speed limits, traffic signs, what to do in accidents, or the 3-second rule!
                </div>
                <div class="msg-time"><?php echo date('H:i'); ?></div>
            </div>
        </div>
        
        <!-- Input Area -->
        <div class="card-footer bg-transparent p-2 border-top border-secondary">
            <form id="chatbotForm" onsubmit="sendChatbotMessage(event)">
                <div class="input-group">
                    <input type="text" class="form-control form-control-sm bg-dark text-white border-secondary" id="chatbotInput" placeholder="Ask about road rules..." autocomplete="off">
                    <button class="btn btn-info btn-sm text-dark px-3 fw-semibold" type="submit">
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- GLOBAL SCRIPTS AND LIBS -->
<!-- ======================================================== -->

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- GSAP Animations -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

<!-- Three.js & OrbitControls (Core 3D framework) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>

<!-- Leaflet JS Map (OSM) -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<!-- SweetAlert2 Popups -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Application JS Modules -->
<script src="<?php echo BASE_PATH; ?>assets/js/main.js"></script>
<script src="<?php echo BASE_PATH; ?>assets/js/chatbot.js"></script>

</body>
</html>
