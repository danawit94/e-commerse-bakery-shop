<?php
include 'connection.php';

session_start();

$user_id = $_SESSION['user_id'] ?? '';

// Check if user is logged in
if (empty($user_id)) {
    header('location:login.php');
    exit(); 
}

// User logout operation
if (isset($_POST['logout'])) {
    session_destroy();
    header('location:login.php');
    exit();
}
$wishlist_num_rows = 0;
$cart_num_rows = 0;

$message = [];

// ==========================================================================
// CONTACT FORM TRANSACTION PROCESSING (Prepared Statements)
// ==========================================================================
if (isset($_POST['send_msg'])) {
    // Clean and validate inputs
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $number  = trim($_POST['number']);
    $msg     = trim($_POST['msg']);

    if (empty($name) || empty($email) || empty($msg)) {
        $message[] = 'Please fill out all required fields!';
    } else {
        // Securely check if the exact same message was already sent by this user
        $stmt = $conn->prepare("SELECT * FROM `message` WHERE user_id = ? AND name = ? AND email = ? AND number = ? AND message = ?");
        $stmt->bind_param("sssss", $user_id, $name, $email, $number, $msg);
        $stmt->execute();
        $check_msg = $stmt->get_result();

        if ($check_msg->num_rows > 0) {
            $message[] = 'This message has already been sent!';
        } else {
            // Insert securely into messages tracking database table
            $insert = $conn->prepare("INSERT INTO `message` (`user_id`, `name`, `email`, `number`, `message`) VALUES (?, ?, ?, ?, ?)");
            $insert->bind_param("sssss", $user_id, $name, $email, $number, $msg);
            $insert->execute();
            $message[] = 'Your message has been sent successfully!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://jsdelivr.net">
    <link rel="stylesheet" type="text/css" href="main.css">
    <title>Contact Us</title>
</head>
<body>
    <?php include 'header.php'; ?>

    <!-- Banner Component -->
    <div class="banne">
        <h1>Contact Us</h1>
        <p>Have questions about our traditional recipes or wholesale delivery orders? Drop us a message, and our bakery support team will respond quickly.</p>
        <a href="index.php">home</a><span>/contact</span>
    </div>
    <div class="idea-container">

<div class="idea-card">

    <div class="icon">
        <i class="bi bi-basket2-fill"></i>
    </div>

    <h2>Fresh Every Morning</h2>

    <p>
        Every sunrise begins in our kitchen where our master bakers
        prepare Ambasha, cakes and pastries completely from scratch.
    </p>

</div>

<div class="idea-card">

    <div class="icon">
        <i class="bi bi-heart-fill"></i>
    </div>

    <h2>Baked With Passion</h2>

    <p>
        We believe baking is an art. Every loaf carries tradition,
        patience and love passed through generations.
    </p>

</div>

<div class="idea-card">

    <div class="icon">
        <i class="bi bi-truck"></i>
    </div>

    <h2>Fast Delivery</h2>

    <p>
        Enjoy warm bakery products delivered directly from our ovens
        to your doorstep with exceptional customer service.
    </p>

</div>

</div>
    <!-- Contact Form Container Area Section -->
    <section class="contact-section">
        <div class="cont">
            <section class="shop">
                <h2>Get in Touch</h2>
            </section>

            <?php
            if (!empty($message)) {
                foreach ($message as $msg) {
                    echo '
                    <div class="message" style="padding:12px; background:#fff5f5; color:#e63946; display:flex; justify-content:space-between; margin-bottom:20px; border-radius:6px; font-family:\'Inter\', sans-serif; font-size:0.95rem; border:1px solid #ffccd5;">
                          <span>'.htmlspecialchars($msg).'</span>
                          <i class="bi bi-x-circle" onclick="this.parentElement.remove()" style="cursor:pointer;"></i>
                    </div>
                    ';
                }
            }
            ?>

            <form action="" method="post">
                <div class="input-group">
                    <label for="name">Your Name *</label>
                    <input type="text" id="name" name="name" placeholder="Enter your full name" required class="box">
                </div>

                <div class="input-group">
                    <label for="email">Your Email *</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email address" required class="box">
                </div>

                <div class="input-group">
                    <label for="number">Phone Number</label>
                    <input type="tel" id="number" name="number" placeholder="Enter your mobile number" class="box">
                </div>

                <div class="input-group">
                    <label for="msg">Your Message *</label>
                    <textarea id="msg" name="msg" placeholder="Write your message here..." rows="5" required class="box"></textarea>
                </div>

                <button type="submit" name="send_msg" class="btn">Send Message</button>
            </form>
        </div>
    </section>
<!-- Business Contact Information Panel Grid Row -->
<div class="contact-info-grid">

    <!-- Card 1: Retail Shop Address location -->
    <div class="info-card">
        <div class="info-icon">
            <i class="bi bi-geo-alt-fill"></i>
        </div>
        <div class="info-details">
            <h3>Our Bakery</h3>
            <p>123 Traditional Bread Lane,</p>
            <p>Addis Ababa, Ethiopia</p>
        </div>
    </div>

    <!-- Card 2: Voice Support Phone Links Line -->
    <div class="info-card">
        <div class="info-icon">
            <i class="bi bi-telephone-fill"></i>
        </div>
        <div class="info-details">
            <h3>Call Us</h3>
            <p><a href="tel:+251911000000">+251 911 000000</a></p>
            <p><a href="tel:+251912000000">+251 912 000000</a></p>
        </div>
    </div>

    <!-- Card 3: Digital Electronic Mail Delivery -->
    <div class="info-card">
        <div class="info-icon">
            <i class="bi bi-envelope-fill"></i>
        </div>
        <div class="info-details">
            <h3>Email Us</h3>
            <p><a href="mailto:info@ambashabakery.com">info@ambashabakery.com</a></p>
            <p><a href="mailto:support@ambashabakery.com">support@ambashabakery.com</a></p>
        </div>
    </div>

</div>

    <?php include 'footer.php'; ?>
</body>
</html>
