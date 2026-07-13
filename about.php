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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" >
    <link rel='stylesheet' type="text/css" href="main.css">
    <title>about</title>
</head>
<body>
<?php include 'header.php' ?>
    <div class="banne">
        <h1>about us</h1>
      
        <p> Every Ambasha is handcrafted using traditional recipes and the finest ingredients. Our passion is bringing families together through the aroma and taste of freshly baked bread. </p>
     <a href="index.php">home</a><span>/about us</span>
    </div>
    <div class="about-us">
        <div class="row">
            <div class="box">
                <div class="title">
                    <span>ABOUT OUR ONLINE STORE</span>
                    <h1>hello, with 25 years of expriance</h1>
                </div>
                <p>Hello and welcome! For a quarter of a century, our ovens have never grown cold. With over 25 years of master baking experience, we have dedicated our lives to perfecting the art of traditional, handcrafted Ambasha. Every single loaf we bake carries the rich aroma of heritage, sweetened with spices, and crafted from the finest organic ingredients. From our family kitchen directly to your morning table,
                     we don't just bake bread—we bring families together, one delicious bite at a time.</p>
            </div>
            <div class="img-box">
                <img src="img/chef.jpg" alt="">
            </div>
        </div>
        <a href="shop.php" class="about-btn">Explore Our Bakery</a>
    </div>
    <div class="about-stats">

    <div>
        <h2>25+</h2>
        <span>Years</span>
    </div>

    <div>
        <h2>15K</h2>
        <span>Customers</span>
    </div>

    <div>
        <h2>40+</h2>
        <span>Products</span>
    </div>

</div>
    <div class="features">
        <div class="title">
            <h1>complete customer ideas</h1>
            <span>best features</span>

        </div>
        <div class="row">
            <div class="box">
            <span>01</span>

                <img src="img/pngtree.png" alt="">
                <h4> Handcrafted Fresh Daily</h4>
                <p>No artificial shortcuts, no compromise. Our professional chefs hand-knead every single batch using premium natural ingredients, baking from scratch every morning to bring rich heritage directly to your kitchen table.</p>
            </div>
            <div class="box">
            <span>02</span>

                <img src="img/baklava.jpg" alt="">
                <h4> Warm Doorstep Delivery</h4>
                <p>  Experience bakery-fresh indulgence without leaving your home. Our rapid custom shipping channels guarantee your orders arrive fast, safe, and beautifully warm—just like walking into our kitchen..</p>
            </div>
            <div class="box">
            <span>03</span>

                <img src="img/pastry.jpg" alt="">
                <h4> Guaranteed 24/7 Support</h4>
                <p>: From large custom event orders to simple morning requests, our professional kitchen concierge team is standing by around the clock.</p>
            </div>
           >
        </div>
    </div>

    <div class="team">
        <div class="title">
            <h1>
            Meet Our Professional Team
            </h1>
            <span>best team</span>
        </div>
        <div class="row">
            <div class="box">
                <div class="img-box">
                    <img src="img/p.jpg" alt="">
              </div> 
                    <span>finance manager</span>
                    <h4>henok mengistu</h4>
                    <div class="icons social">
                      <i class="bi bi-instagram" ></i>
                      <i class="bi bi-youtube" ></i> 
                      <i class="bi bi-twitter" ></i> 
                       
                      <i class="bi bi-whatsapp" ></i> 
                      <i class="bi bi-telegram" ></i>
                     
                </div>
            </div>
            <div class="box">
                <div class="img-box">
                    <img src="img/q.jpg" alt="">
               </div> 
                    <span>marketing management</span>
                    <h4>mekides aychalew</h4>
                    <div class="icons social">
                      <i class="bi bi-instagram" ></i>
                      <i class="bi bi-youtube" ></i> 
                      <i class="bi bi-twitter" ></i> 
                       
                      <i class="bi bi-whatsapp" ></i> 
                      <i class="bi bi-telegram" ></i>
                     
                      </div>
            </div>
            <div class="box">
                <div class="img-box">
                    <img src="img/r.jpg" alt="">
                </div> 
                    <span>chef of baking </span>
                    <h4>hiwot dinkineh</h4>
                    <div class="icons social">
                      <i class="bi bi-instagram" ></i>
                      <i class="bi bi-youtube" ></i> 
                      <i class="bi bi-twitter" ></i> 
                       
                      <i class="bi bi-whatsapp" ></i> 
                      <i class="bi bi-telegram" ></i>
                     
                   </div>
            </div>
            <div class="box">
                <div class="img-box ">
                    <img src="img/3,jpg" alt="">
                </div> 
                    <span>finance manager</span>
                    <h4>wibnesh meshesha</h4>
                    <div class="icons social">
                      <i class="bi bi-instagram" ></i>
                      <i class="bi bi-youtube" ></i> 
                      <i class="bi bi-twitter" ></i> 
                       
                      <i class="bi bi-whatsapp" ></i> 
                      <i class="bi bi-telegram" ></i>
                     
                </div>
            </div>
            <div class="box">
                <div class="img-box">
                    <img src="img/5.jpg" alt="">
                </div> 
                    <span>web design and development</span>
                    <h4>danu mes</h4>
                    <div class="icons social">
                      <i class="bi bi-instagram" ></i>
                      <i class="bi bi-youtube" ></i> 
                      <i class="bi bi-twitter" ></i> 
                       
                      <i class="bi bi-whatsapp" ></i> 
                      <i class="bi bi-telegram" ></i>
                   </div>
                </div>
            </div>
        </div>
    </div>
    <div class="project">

    <span>Our Gallery</span>
    <h1>Fresh From Our Bakery</h1>

</div>

<div class="gallery">

    <div class="item large">
        <img src="img/ned.jpg" alt="">
        <div class="overlay">
            <h3>Traditional Ambasha</h3>
            <p>Fresh every morning</p>
        </div>
    </div>

    <div class="item">
        <img src="img/torta_cake.jpg" alt="">
        <div class="overlay">
            <h3>Birthday Cakes</h3>
            <p>Handcrafted desserts</p>
        </div>
    </div>

    <div class="item">
        <img src="img/pastry.jpg" alt="">
        <div class="overlay">
            <h3>Premium Pastries</h3>
            <p>Baked daily</p>
        </div>
    </div>

    <div class="item">
        <img src="img/pastry2.jpg" alt="">
        <div class="overlay">
            <h3>Organic Bakery</h3>
            <p>Healthy ingredients</p>
        </div>
    </div>

</div>

    <div class="ideas">
    <section class="ideas">

<div class="title">
    <span>Why Choose Us</span>
    <h1>More Than A Bakery,<br>We Create Memories</h1>
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

</section>
<div class="idea-corn">
        <div class="ro">
            <div class="bo">
                <i class="bi bi-stack" ></i>
                <div class="deta">
                    <h2 class="title"> what we really  do </h2>
                    <p>At our core, we don’t just mix flour and water—we bake memories.
                         Every single morning, our professional chefs handcraft
                         premium, organic Ambasha, delicate pastries, and luxury cakes 
                         from scratch using the finest natural ingredients</p>
                </div>
            </div>
            <div class="bo">
                <i class="bi bi-grid-1x2-fill" ></i>
                <div class="deta">
                    <h2 class="title" > history of begning </h2>
                    <p>It all started 25 years ago in a small family kitchen, driven by a
                         simple yet powerful passion: the unforgettable aroma of freshly baked, authentic Ambasha. We began with nothing but a single clay oven, a cherished traditional family recipe, and a deep respect for our heritage. 
                        Over the last quarter of a century, we have meticulously refined our 
                        craft—hand-kneading every batch, perfecting our spice blends, and learning exactly how to create the softest,
                         most comforting bread.</p>
                </div>
            </div>
            <div class="bo">
                <i class="bi bi-tropical-storm" ></i>
                <div class="deta">
                    <h2 class="title" > our vision </h2>
                    <p>Our vision is to protect and elevate the beautiful art of traditional
                         baking for generations to come. We strive to be more than just a bakery 
                         shop; we want to be the heart of your morning routine and the centerpiece
                          of your family gatherings. By blending our 25 years 
                          of master baking experience with modern, fast delivery
                          , we aim to share the authentic warmth of freshly baked
                           artisan Ambasha with homes everywhere—proving that no 
                           matter how fast the world moves, the timeless comfort of pure, 
                           handcrafted bread will never change.</p>
                </div>
            </div>
            

        </div>
 </div>
    <section class="timeline">

<h2>Our Journey</h2>

<div class="time">

<div class="year">

<h1>1999</h1>

<p>Family bakery founded.</p>

</div>

<div class="year">

<h1>2008</h1>

<p>Opened our first bakery shop.</p>

</div>

<div class="year">

<h1>2018</h1>

<p>Expanded into custom cakes.</p>

</div>

<div class="year">

<h1>2025</h1>

<p>Online ordering & delivery.</p>

</div>

</div>

</section>
    <?php include 'footer.php';?>
</body>
</html>