<?php
 include 'connection.php';

 session_start();

 $user_id = $_SESSION['user_id'] ?? '';
 $admin_id= $_SESSION['user_name'];

 if (!isset($admin_id)){
    header('location:login.php');
    exit() ;
 }
 if (isset($_POST['logout'])){
    session_destroy();
    header('location:login.php');
 }
 
    
// ==========================================================================
// 1. ADD TO WISHLIST WORKFLOW PROCESSING
// ==========================================================================
if (isset($_POST['add_to_wishlist'])) {
    // Basic verification: user must be logged in to modify database entries
    if (empty($user_id)) {
        header('location:login.php');
        exit();
    }

    $product_id    = mysqli_real_escape_string($conn, $_POST['product_id']);
    $product_name  = mysqli_real_escape_string($conn, $_POST['product_name']);
    // trim() strips accidental white space characters embedded inside input strings
    $product_price = mysqli_real_escape_string($conn, trim($_POST['product_price']));
    $product_image = mysqli_real_escape_string($conn, $_POST['product_image']);

    // Check database tracking tables to avoid duplicate asset entries
    $check_wishlist = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('Wishlist query failed');
    $check_cart     = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('Cart query failed');
    
    if (mysqli_num_rows($check_wishlist) > 0) {
        $message[] = 'Product already exists in your wishlist!';
    } else if (mysqli_num_rows($check_cart) > 0) {
        $message[] = 'Product already exists in your shopping cart!';
    } else {
        mysqli_query($conn, "INSERT INTO `wishlist` (`user_id`, `pid`, `name`, `price`, `image`) VALUES ('$user_id', '$product_id', '$product_name', '$product_price', '$product_image')") or die('Insert wishlist failed');
        $message[] = 'Product successfully added to your wishlist!';
    }
}

// ==========================================================================
// 2. ADD TO SHOPPING CART WORKFLOW PROCESSING
// ==========================================================================
if (isset($_POST['add_to_cart'])) {
    if (empty($user_id)) {
        header('location:login.php');
        exit();
    }

    $product_id       = mysqli_real_escape_string($conn, $_POST['product_id']);
    $product_name     = mysqli_real_escape_string($conn, $_POST['product_name']);
    $product_price    = mysqli_real_escape_string($conn, trim($_POST['product_price']));
    $product_image    = mysqli_real_escape_string($conn, $_POST['product_image']);
    $product_quantity = mysqli_real_escape_string($conn, trim($_POST['product_quantity']));

    // Verify item counts before committing updates
    $check_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('Cart query failed');
    
    if (mysqli_num_rows($check_cart) > 0) {
        $message[] = 'Product already exists in your shopping cart!';
    } else {
        mysqli_query($conn, "INSERT INTO `cart` (`user_id`, `pid`, `name`, `price`, `quantity`, `image`) VALUES ('$user_id', '$product_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('Insert cart failed');
        $message[] = 'Product successfully added to your cart!';
    }
}

// ==========================================================================
// 3. COUNTER STATE VARIABLE GENERATION
// ==========================================================================
$wishlist_num_rows = 0;
$cart_num_rows = 0;

if (!empty($user_id)) {
    $select_wishlist   = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE user_id = '$user_id'") or die('Wishlist counter failed');
    $wishlist_num_rows = mysqli_num_rows($select_wishlist);

    $select_cart   = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('Cart counter failed');
    $cart_num_rows = mysqli_num_rows($select_cart);
}
?>


<style type="text/css">
    <?php 
    include 'main.css';
?>
</style>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>home page</title>
</head>
<body>
<?php include 'header.php' ?>
<div class="hero">

    <div class="hero-slider">

        <!-- Slide 1 -->
        <div class="slider-item active">
            <img src="img/page_pic.jpg" alt="">
            <div class="overlay"></div>

            <div class="slider-caption">

                <span>Freshly Baked Every Morning</span>

                <h1>
                    Handcrafted <br>
                    Artisan Bakery
                </h1>

                <p>
                    Experience the rich aroma of freshly baked Ambasha,
                    cakes and pastries made from premium natural ingredients.
                </p>

                <div class="hero-btns">
                    <a href="shop.php" class="btn">Order Now</a>
                    <a href="#story" class="btn2">Our Story</a>
                </div>

            </div>
        </div>

        <!-- Slide 2 -->
        <div class="slider-item">

            <img src="img/pastry.jpg" alt="">
            <div class="overlay"></div>

            <div class="slider-caption">

                <span>Made With Love</span>

                <h1>
                    Sweet Moments <br>
                    Start Here
                </h1>

                <p>
                    From traditional Ambasha to delicious pastries,
                    every bite is baked with passion.
                </p>

                <div class="hero-btns">
                    <a href="shop.php" class="btn">Shop Now</a>
                    <a href="#story" class="btn2">Learn More</a>
                </div>

            </div>

        </div>

    </div>

    <!-- Controls -->
    <div class="controls">
        <button class="prev">&#10094;</button>
        <button class="next">&#10095;</button>
    </div>

    

</div>
<!-- CORRECT STRUCTURE: SEPARATED CLEANLY -->
<div class="dot" id="dynamicDotsContainer"></div>

<div class="services">
    <div class="row">
        <div class="box">
            <img src="img/pastry2.jpg" alt="">
            <div>
                <h1>Free & Fast Delivery</h1>
                <p>Freshly baked every day and delivered warm to your doorstep.</p>
            </div>
        </div>
        <div class="box">
            <img src="img/em.jpg" alt="">
            <div>
                <h1>Freshness Guaranteed</h1>
                <p>We use premium ingredients and guarantee the quality of every loaf.</p>
            </div>
        </div>
        <div class="box">
            <img src="img/ned.jpg" alt="">
            <div>
                <h1>24/7 Online Support</h1>
                <p>Our team is always ready to help with your orders and custom bakery requests.</p>
            </div>
        </div>
    </div>
</div>


 
 <br>
 <div class="story">
    <div class="ours">
        <div class="boxx">
        <span>Our Story</span>

<h1>Crafting Fresh Ambasha with Love Since Day One</h1>

<p>
Every Ambasha is handcrafted using traditional recipes and the finest ingredients.
Our passion is bringing families together through the aroma and taste of freshly baked bread.
From our bakery to your table, every loaf is baked with care, quality, and love.
</p>
              <a href="shop.php" class="btn">shop now</a>
        </div>
          <div class="box">
            <img src="img/well.jpg" alt="">
          </div>
    </div>
 </div>



 <div class="testimonial-fluid">

    <span class="section-tag">Happy Customers</span>
    <h1 class="title">Fresh Smiles, Fresh Bread</h1>

    <div class="testimonial-slider">

        <div class="testimonial-item">

            <div class="customer-img">
                <img src="img/r.jpg">
            </div>

            <div class="testimonial-caption">

                <div class="stars">
                    ★★★★★
                </div>

                <p>
                    "The Ambasha is always warm and incredibly soft.
                    Every visit feels like coming home."
                </p>

                <h3>Abebe Kebede</h3>

                <span>Regular Customer</span>

            </div>

        </div>


        <div class="testimonial-item">

            <div class="customer-img">
                <img src="img/p.jpg">
            </div>

            <div class="testimonial-caption">

                <div class="stars">
                    ★★★★★
                </div>

                <p>
                    "The cakes are beautiful and delicious.
                    Perfect for birthdays and special occasions."
                </p>

                <h3>Selam Tesfaye</h3>

                <span>Verified Buyer</span>

            </div>

        </div>

    </div>

    <div class="control">
        <button class="prev1">&#10094;</button>
        <button class="next1">&#10095;</button>
    </div>

</div>
 
 <div class="discover">
    <div class="details">
        <h1 class="title">
            fresh beck
        </h1>
        <span> buy now and save 30% off</span>
        <p> ambasha shop is siimple has been the industy's standard evry sincebthe 1500</p>
        <a href="shop.php"> discover now</a>
    </div>
    <div class="img-box">
        <img src="img/pastry.jpg" alt="">
    </div>
 </div>
 <?php include 'homeshop.php' ?>
 <div class="newslatter">

    <h1 class="title"> join our to newslatter</h1>
    <p>get ur 15% off your next order be first to learn about promtion special events</p>
    <input type="text " name="" placeholder="your email adress....">
    <button>subscribe now</button>
 </div>
 
<?php include 'footer.php' ?>
<script src="https://jquery.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.css">
    <script src="script2.js"></script>

       
    </script>
    

    <script>
document.addEventListener("DOMContentLoaded", function () {
    const slides = document.querySelectorAll(".slider-item");
    const nextBtn = document.querySelector(".next");
    const prevBtn = document.querySelector(".prev");
    let currentIndex = 0;
    let slideInterval;

    function showSlide(index) {
        // Remove active class from current slide
        slides[currentIndex].classList.remove("active");
        
        // Update index with loop boundaries
        currentIndex = (index + slides.length) % slides.length;
        
        // Add active class to new slide
        slides[currentIndex].classList.add("active");
    }

    function nextSlide() {
        showSlide(currentIndex + 1);
    }

    function prevSlide() {
        showSlide(currentIndex - 1);
    }

    // Button click events
    nextBtn.addEventListener("click", () => {
        nextSlide();
        resetTimer();
    });

    prevBtn.addEventListener("click", () => {
        prevSlide();
        resetTimer();
    });

    // Auto play setup (Changes slides every 5 seconds)
    function startTimer() {
        slideInterval = setInterval(nextSlide, 5000);
    }

    function resetTimer() {
        clearInterval(slideInterval);
        startTimer();
    }

    // Initialize auto play
    startTimer();
});
</script>

</body>
</html>