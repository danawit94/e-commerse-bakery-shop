<?php
include 'connection.php';

session_start();

// FIX: Standardize on user_id to prevent the admin/user locking bug
$user_id = $_SESSION['user_id'] ?? '';

if (empty($user_id)) {
    header('location:login.php');
    exit(); 
}

// FIX: Add missing exit() to stop script execution during logout
if (isset($_POST['logout'])) {
    session_destroy();
    header('location:login.php');
    exit();
}

$message = [];

// ==========================================================================
// 1. ADD TO WISHLIST WORKFLOW PROCESSING (Prepared Statements)
// ==========================================================================
if (isset($_POST['add_to_wishlist'])) {
    $product_id    = $_POST['product_id'];
    $product_name  = $_POST['product_name'];
    $product_price = trim($_POST['product_price']);
    $product_image = $_POST['product_image'];

    // Secure checking using Prepared Statements
    $stmt = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
    $stmt->bind_param("ss", $product_name, $user_id);
    $stmt->execute();
    $check_wishlist = $stmt->get_result();

    $stmt2 = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
    $stmt2->bind_param("ss", $product_name, $user_id);
    $stmt2->execute();
    $check_cart = $stmt2->get_result();
    
    if ($check_wishlist->num_rows > 0) {
        $message[] = 'Product already exists in your wishlist!';
    } else if ($check_cart->num_rows > 0) {
        $message[] = 'Product already exists in your shopping cart!';
    } else {
        $insert = $conn->prepare("INSERT INTO `wishlist` (`user_id`, `pid`, `name`, `price`, `image`) VALUES (?, ?, ?, ?, ?)");
        $insert->bind_param("sssss", $user_id, $product_id, $product_name, $product_price, $product_image);
        $insert->execute();
        $message[] = 'Product successfully added to your wishlist!';
    }
}

// ==========================================================================
// 2. ADD TO SHOPPING CART WORKFLOW PROCESSING (Prepared Statements)
// ==========================================================================
if (isset($_POST['add_to_cart'])) {
    $product_id       = $_POST['product_id'];
    $product_name     = $_POST['product_name'];
    $product_price    = trim($_POST['product_price']);
    $product_image    = $_POST['product_image'];
    $product_quantity = trim($_POST['product_quantity']);

    $stmt = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
    $stmt->bind_param("ss", $product_name, $user_id);
    $stmt->execute();
    $check_cart = $stmt->get_result();
    
    if ($check_cart->num_rows > 0) {
        $message[] = 'Product already exists in your shopping cart!';
    } else {
        $insert = $conn->prepare("INSERT INTO `cart` (`user_id`, `pid`, `name`, `price`, `quantity`, `image`) VALUES (?, ?, ?, ?, ?, ?)");
        $insert->bind_param("ssssss", $user_id, $product_id, $product_name, $product_price, $product_quantity, $product_image);
        $insert->execute();
        $message[] = 'Product successfully added to your cart!';
    }
}

// ==========================================================================
// 3. COUNTER STATE VARIABLE GENERATION
// ==========================================================================
$wishlist_num_rows = 0;
$cart_num_rows = 0;

if (!empty($user_id)) {
    $stmt = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $wishlist_num_rows = $stmt->get_result()->num_rows;

    $stmt2 = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
    $stmt2->bind_param("s", $user_id);
    $stmt2->execute();
    $cart_num_rows = $stmt2->get_result()->num_rows;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="main.css">
    <title>view_page</title>
</head>
<body>
    <?php 
    include 'header.php';
    
    // Display feedback message notices if they exist
    if (!empty($message)) {
        foreach ($message as $msg) {
            echo '<div class="alert-msg" style="padding:10px; background:#fff5f5; color:#e63946; text-align:center; border-bottom:1px solid #ffccd5;">' . htmlspecialchars($msg) . '</div>';
        }
    }
    ?>

    <div class="banne">
        <h1>product details</h1>
        <p>Every Ambasha is handcrafted using traditional recipes and the finest ingredients. Our passion is bringing families together through the aroma and taste of freshly baked bread.</p>
        <a href="index.php">home</a><span>/about us</span>
    </div>

    <section class="popul">
        <section class="shop"><h2>product detail</h2></section>
    
        <div class="view_page">
            <?php 
            if (isset($_GET['pid'])) {
                $pid = $_GET['pid'];
                
                // FIX: Secured the URL parameter against SQL injection attacks
                $stmt = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
                $stmt->bind_param("s", $pid);
                $stmt->execute();
                $select_products = $stmt->get_result();

                if ($select_products->num_rows > 0) {
                    while ($fetch_products = $select_products->fetch_assoc()) {
            ?>
            
            <!-- Individual Product Entry Card -->
            <form action="" class="canu" method="post">
                <img src="image/<?php echo htmlspecialchars($fetch_products['images']); ?>" alt="<?php echo htmlspecialchars($fetch_products['image'] ?? 'product'); ?>">
                
                <div class="detaill">
                    <div class="price">$<?php echo htmlspecialchars($fetch_products['price']); ?></div>
                    <div class="name"><?php echo htmlspecialchars($fetch_products['name']); ?></div>
                    <div class="detail"><?php echo htmlspecialchars($fetch_products['product_detail']); ?></div>
                    
                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($fetch_products['id']); ?>">
                    <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($fetch_products['name']); ?>">
                    <input type="hidden" name="product_price" value="<?php echo htmlspecialchars($fetch_products['price']); ?>">
                    <input type="hidden" name="product_image" value="<?php echo htmlspecialchars($fetch_products['images']); ?>">
                </div>
                
                <div class="iicon">
                    <button type="submit" name="add_to_wishlist" class="bi bi-heart"></button>
                    <input type="number" name="product_quantity" value="1" min="0" class="quantity">
                    <button type="submit" name="add_to_cart" class="bi bi-cart"></button>
                </div>
            </form>
            
            <?php
                    }
                } else {
                    echo "<p>Product not found.</p>";
                }
            }
            ?>
        </div>
    </section>

    <?php include 'footer.php'; ?>
</body>
</html>
