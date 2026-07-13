<?php
include 'connection.php';

session_start();

$user_id = $_SESSION['user_id'] ?? '';

// Direct user to login if session token is missing
if (empty($user_id)) {
    header('location:login.php');
    exit();
}

// Global user session logout operation
if (isset($_POST['logout'])) {
    session_destroy();
    header('location:login.php');
    exit();
}
 
$message = [];

// ==========================================================================
// 1. UPDATE QUANTITY WORKFLOW PROCESSING
// ==========================================================================
if (isset($_POST['update_qty'])) {
    $cart_id = mysqli_real_escape_string($conn, $_POST['cart_id']);
    $qty     = mysqli_real_escape_string($conn, trim($_POST['p_qty']));
    
    // Ensure quantity can never drop below zero
    if ($qty >= 0) {
        mysqli_query($conn, "UPDATE `cart` SET quantity = '$qty' WHERE id = '$cart_id' AND user_id = '$user_id'") or die('Update query failed');
        $message[] = 'Cart quantity updated successfully!';
    }
}
$wishlist_num_rows = 0;
$cart_num_rows = 0;

// ==========================================================================
// 2. DELETE SINGLE CART ITEM
// ==========================================================================
if (isset($_GET['delete'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete']);
    
    mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$delete_id' AND user_id = '$user_id'") or die('Single delete query failed');
    header('location:cart.php');
    exit();
}

// ==========================================================================
// 3. CLEAR ENTIRE SHOPPING CART
// ==========================================================================
if (isset($_GET['delete_all'])) {
    mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('Clear cart query failed');
    header('location:cart.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://jsdelivr.net">
    <link rel="stylesheet" type="text/css" href="main.css">
    <title>Shopping Cart</title>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="banne">
        <h1>Shopping Cart</h1>
        <p>Every Ambasha is handcrafted using traditional recipes and the finest ingredients. Our passion is bringing families together through the aroma and taste of freshly baked bread.</p>
        <a href="index.php">home</a><span>/cart</span>
    </div>

    <section class="shop">
        <h2>Products in my Cart</h2>

        <?php
        if (!empty($message)) {
            foreach ($message as $msg) {
                echo '
                <div class="message" style="padding:10px; background:#fff5f5; color:#e63946; display:flex; justify-content:space-between; margin-bottom:15px; border-radius:6px;">
                      <span>'.$msg.'</span>
                      <i class="bi bi-x-circle" onclick="this.parentElement.remove()" style="cursor:pointer;"></i>
                </div>
                ';
            }
        }
        ?>

        <div class="box-bottle">
            <?php
            $grand_total = 0;
            // Select cart items belonging strictly to the logged-in customer
            $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('Cart loop query failed');
            
            if (mysqli_num_rows($select_cart) > 0) {
                while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                    // Multiply item unit price by current user selection quantity
                    $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']);
                    $grand_total += $sub_total;
            ?>
            
            <!-- Individual Product Entry Card -->
            <form action="" class="brar" method="post">
                <img src="image/<?php echo $fetch_cart['image']; ?>" alt="<?php echo $fetch_cart['name']; ?>">
                <div class="price">$<?php echo $fetch_cart['price']; ?></div>
                <div class="name"><?php echo $fetch_cart['name']; ?></div>
                
                <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
                
                <!-- Dynamic Interactive Line Item Controls -->
                <div class="qty-control" style="margin: 10px 0; display: flex; justify-content: center; gap: 8px;">
                    <input type="number" name="p_qty" value="<?php echo $fetch_cart['quantity']; ?>" min="1" class="quantity" style="width:30%; text-align:center;">
                    <button type="submit" name="update_qty" class="bi bi-arrow-clockwise" style="background:none; border:none; cursor:pointer; font-size:1.2rem; color:var(--orange, #ff4500);"></button>
                </div>

                <div class="subtotal" style="font-weight: 600; color: #475569; margin-bottom: 10px;">
                    Subtotal: <span style="color:#0f172a;">$<?php echo $sub_total; ?></span>
                </div>
                
                <div class="icon">
                    <a href="view_page.php?pid=<?php echo $fetch_cart['pid']; ?>" class="bi bi-eye-fill"></a>
                    <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>" class="bi bi-x" onclick="return confirm('Do you want to remove this product from your cart?')"></a>
                </div>
            </form>
            
            <?php
                }
            } else {
                echo '<p class="empty" style="text-align:center; padding:40px; color:#64748b; width:100%;">Your shopping cart is completely empty!</p>';
            }
            ?>
        </div>

        <!-- Wishlist Total Re-utilized Styling Component -->
        <!-- Wishlist/Cart Total Component Block -->
<div class="wishlist_total">
    <p>Total Amount Payable: <span>$<?php echo $grand_total; ?>/-</span></p>
    
    <div class="checkout-actions" style="display: flex; gap: 15px; flex-wrap: wrap; width: 100%; justify-content: flex-end;">
        <a href="shop.php" style="background-color: transparent; color: #475569; border: 2px solid #cbd5e1;">Continue Shopping</a>
        
        <!-- NEW: Proceed to Checkout Button Link -->
        <a href="<?php echo ($grand_total > 0) ? 'checkout.php' : '#'; ?>" 
           class="btn-checkout <?php echo ($grand_total > 0) ? '' : 'disabled'; ?>" 
           style="background-color: var(--orange, #ff4500); color: #ffffff; border: none; text-align: center; <?php echo ($grand_total > 0) ? '' : 'background-color: #cbd5e1; cursor: not-allowed; pointer-events: none; opacity: 0.7;'; ?>">
           Proceed to Checkout
        </a>
        
        <a href="cart.php?delete_all" class="btn2 <?php echo ($grand_total > 0) ? '' : 'disabled' ?> " onclick="return confirm('Do you want to clear your entire shopping cart?')"></a>
    </div>
</div>

    </section>

    <?php include 'footer.php'; ?>
</body>
</html>
