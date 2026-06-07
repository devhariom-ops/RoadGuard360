<?php
include_once '../includes/header.php';

$error = "";
$success = "";
$name = "";
$email = "";
$subject = "";
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } else {
        // Submit feedback
        $submitted = db_execute(
            "INSERT INTO feedbacks (name, email, subject, message) VALUES (:name, :email, :subject, :message)",
            [
                'name' => $name,
                'email' => $email,
                'subject' => $subject,
                'message' => $message
            ]
        );
        
        if ($submitted) {
            $success = "Thank you! Your feedback has been recorded successfully.";
            // Clear inputs
            $name = $email = $subject = $message = "";
        } else {
            $error = "Could not record feedback. Please try again.";
        }
    }
}
?>

<div class="container py-4">
    <div class="row mb-5">
        <div class="col-lg-8 mx-auto text-center">
            <h1 class="display-4 font-heading text-white mb-2">Contact Us & Feedback</h1>
            <p class="text-secondary">Have questions, suggestions, or want to partner with the SafeRoads campaign? Drop us a message.</p>
        </div>
    </div>
    
    <div class="row g-4">
        <!-- Contact Information Card -->
        <div class="col-md-5">
            <div class="card glass-card p-4 h-100 border-info border-opacity-10">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h3 class="text-white font-heading mb-4"><i class="fa-solid fa-address-book text-info me-2"></i>Get In Touch</h3>
                        
                        <div class="d-flex align-items-start mb-4">
                            <div class="fs-4 text-info me-3"><i class="fa-solid fa-map-location-dot"></i></div>
                            <div>
                                <h6 class="text-white font-heading mb-1">Office Address</h6>
                                <p class="text-secondary small m-0">SafeRoads Safety Council, Suite 405, Metro Plaza, Bangalore, India</p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-start mb-4">
                            <div class="fs-4 text-info me-3"><i class="fa-solid fa-envelope"></i></div>
                            <div>
                                <h6 class="text-white font-heading mb-1">Email Support</h6>
                                <p class="text-secondary small m-0"><a href="mailto:info@saferoads.org" class="text-info text-decoration-none">info@saferoads.org</a></p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-start mb-4">
                            <div class="fs-4 text-info me-3"><i class="fa-solid fa-phone-volume"></i></div>
                            <div>
                                <h6 class="text-white font-heading mb-1">Public Inquiries</h6>
                                <p class="text-secondary small m-0"><a href="tel:+918012345678" class="text-info text-decoration-none">+91 80 1234 5678</a></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="pt-4 border-top border-secondary">
                        <h6 class="text-white font-heading mb-2">Our Operating Hours</h6>
                        <p class="text-secondary small m-0">Monday – Friday: 9:00 AM – 5:00 PM IST</p>
                        <p class="text-muted small m-0">Closed on weekends and national holidays.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Feedback Form Card -->
        <div class="col-md-7">
            <div class="card glass-card p-4 border-info border-opacity-10">
                <div class="card-body">
                    <h3 class="text-white font-heading mb-4"><i class="fa-solid fa-comment-dots text-info me-2"></i>Send Feedback</h3>
                    
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger border-0 bg-danger-subtle text-danger" role="alert">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i><?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success border-0 bg-success-subtle text-success" role="alert">
                            <i class="fa-solid fa-circle-check me-2"></i><?php echo htmlspecialchars($success); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="feedback.php">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label text-secondary">Your Name</label>
                                <input type="text" class="form-control bg-dark text-white border-secondary" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required placeholder="John Doe">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label text-secondary">Your Email</label>
                                <input type="email" class="form-control bg-dark text-white border-secondary" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required placeholder="john@example.com">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="subject" class="form-label text-secondary">Subject</label>
                            <input type="text" class="form-control bg-dark text-white border-secondary" id="subject" name="subject" value="<?php echo htmlspecialchars($subject); ?>" required placeholder="e.g. Suggestion for 3D simulator">
                        </div>
                        
                        <div class="mb-4">
                            <label for="message" class="form-label text-secondary">Message</label>
                            <textarea class="form-control bg-dark text-white border-secondary" id="message" name="message" rows="5" required placeholder="Write your comments, suggestions, or queries here..."><?php echo htmlspecialchars($message); ?></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-info text-dark w-100 fw-bold py-2">
                            <i class="fa-solid fa-paper-plane me-2"></i>Submit Feedback
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>
