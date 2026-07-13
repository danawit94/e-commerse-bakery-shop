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
    <title>our shop</title>
</head>
<body>
    <?php include 'header.php' ?>
    <div class="banne">
        <h1>product detail</h1>
      
        <p> Every Ambasha is handcrafted using traditional recipes and the finest ingredients. Our passion is bringing families together through the aroma and taste of freshly baked bread. </p>
     <a href="index.php">home</a><span>/about us</span>
    </div>
    <section class="popular">
    <h2>Popular Products</h2>
    
    <!-- Custom Navigation Chevron Triggers -->
    <div class="control">
        <i class="bi bi-chevron-left left-arrow"></i>
        <i class="bi bi-chevron-right right-arrow"></i>
    </div>
    
    <!-- Slick Slider Core Row Mount -->
    <div class="popular-slider-track">
        <?php 
        if(isset($_GET['pid'])){
            $pid =$_GET['pid'];
            $select_products = mysqli_query($conn,"SELECT * FROM `products` id= '$pid'")or die('query filed')
        }
        ?>
        
        <!-- Individual Product Entry Card -->
        <form action="" class="car" method="post">
            <img src="image/<?php echo $fetch_products['images']; ?>" alt="<?php echo $fetch_products['name']; ?>">
            <div class="price">$<?php echo $fetch_products['price']; ?></div>
            <div class="name"><?php echo $fetch_products['name']; ?></div>
            
            <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
            <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
            <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
            <input type="hidden" name="product_quantity" value="1">
            <input type="hidden" name="product_image" value="<?php echo $fetch_products['images']; ?>">
            
            <div class="icon">
                <a href="view_page.php?pid=<?php echo $fetch_products['id']; ?>" class="bi bi-eye-fill"></a>
                <button type="submit" name="add_to_wishlist" class="bi bi-heart"></button>
                <button type="submit" name="add_to_cart" class="bi bi-cart"></button>
            </div>
        </form>
        
        <?php
            }
        } else {
            echo '<p class="empty">No products added yet</p>';
        }
        ?>
    <?php include 'footer.php';?>
</body>
</html>