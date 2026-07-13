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

$message = [];
$wishlist_num_rows = 0;
$cart_num_rows = 0;
// ==========================================================================
// CHECKOUT ORDER PROCESSING TRANSACTION
// ==========================================================================
if (isset($_POST['order_btn'])) {
    // 1. Clean and validate user shipping details
    $name    = mysqli_real_escape_string($conn, trim($_POST['name']));
    $number  = mysqli_real_escape_string($conn, trim($_POST['number']));
    $email   = mysqli_real_escape_string($conn, trim($_POST['email']));
    $method  = mysqli_real_escape_string($conn, $_POST['method']);
    
    // Combine address parts neatly into a single line matching your table
    $address = mysqli_real_escape_string($conn, trim($_POST['flat'] . ', ' . $_POST['street'] . ', ' . $_POST['city'] . ', ' . $_POST['country']));

    // 2. Fetch all cart items to calculate final figures
    $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('Cart lookup failed');
    $price_total = 0;
    $cart_products = [];

    if (mysqli_num_rows($cart_query) > 0) {
        while ($cart_item = mysqli_fetch_assoc($cart_query)) {
            // Build the string list of items (e.g., "Ambasha (2), Special Mix (1)")
            $cart_products[] = $cart_item['name'] . ' (' . $cart_item['quantity'] . ')';
            $sub_total = ($cart_item['price'] * $cart_item['quantity']);
            $price_total += $sub_total;
        }
        
        // Convert array to a clean string split by commas
        $total_products = implode(', ', $cart_products);
        
        // Generate current timestamp or location string to match your placed_on expectation
        $placed_on = date('d-M-Y'); 
        
        // FIX: Insert clean word status tracking without trailing periods
        $payment_status = 'pending';

        // 3. Prevent duplicate order spam clicking
        $order_check = mysqli_query($conn, "SELECT * FROM `order` WHERE name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$address' AND total_products = '$total_products' AND total_price = '$price_total'") or die('Order check query failed');

        if (mysqli_num_rows($order_check) > 0) {
            $message[] = 'This order has already been placed!';
        } else {
            // 4. Securely commit to your orders table matching your database snapshot fields
            $insert_order = mysqli_query($conn, "INSERT INTO `order` (user_id, name, number, email, method, address, total_products, total_price, placed_on, payment_status) VALUES ('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$price_total', '$placed_on', '$payment_status')") or die('Insert order failed');
            
            if ($insert_order) {
                // 5. Instantly clear the user's temporary shopping cart upon success
                mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('Clear cart query failed');
                $message[] = 'Order placed successfully!';
                header('location:order.php'); // Redirect immediately to order status panel
                exit();
            }
        }
    } else {
        $message[] = 'Your shopping cart is empty! Cannot place an order.';
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
    <title>Checkout</title>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="banne">
        <h1>Checkout</h1>
        <p>Complete your shipping information details below to finalize your traditional freshly baked bread orders.</p>
        <a href="index.php">home</a><span>/checkout</span>
    </div>

    <!-- Summary Box & Input Fields Grid Rows Section Layout -->
    <section class="checkout-section" style="background-color:#f8fafc; padding:60px 20px; font-family:'Inter', sans-serif;">
        <div class="checkout-wrapper" style="max-width:1000px; margin:0 auto; display:flex; flex-direction:column; gap:30px;">
            
            <section class="shop" style="text-align:center;">
                <h2>Review Your Order</h2>
            </section>

            <?php
            if (!empty($message)) {
                foreach ($message as $msg) {
                    echo '
                    <div class="message" style="padding:12px; background:#fff5f5; color:#e63946; display:flex; justify-content:space-between; border-radius:6px; font-size:0.95rem; border:1px solid #ffccd5;">
                          <span>'.htmlspecialchars($msg).'</span>
                          <i class="bi bi-x-circle" onclick="this.parentElement.remove()" style="cursor:pointer;"></i>
                    </div>
                    ';
                }
            }
            ?>

            <!-- Top Grid Item: Inline Cart Items Total Display List -->
            <div class="display-order" style="background:#ffffff; border:1px solid #e2e8f0; border-radius:12px; padding:30px; box-shadow:0 4px 15px rgba(0,0,0,0.02); text-align:center;">
                <?php
                $grand_total = 0;
                $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('Cart loop failed');
                if (mysqli_num_rows($select_cart) > 0) {
                    while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                        $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
                        $grand_total += $total_price;
                ?>
                <span style="display:inline-block; background:#fff5f0; color:var(--orange, #ff4500); padding:8px 16px; border-radius:30px; font-size:0.95rem; font-weight:600; margin:5px;">
                    <?php echo htmlspecialchars($fetch_cart['name']); ?> (<?php echo $fetch_cart['quantity']; ?>) - $<?php echo $fetch_cart['price']; ?>
                </span>
                <?php
                    }
                } else {
                    echo '<p style="color:#64748b;">Your shopping cart is empty</p>';
                }
                ?>
                <div class="grand-total" style="margin-top:20px; font-size:1.3rem; font-weight:700; color:#0f172a;">
                    Grand Total: <span style="color:var(--orange, #ff4500);">$<?php echo $grand_total; ?>/-</span>
                </div>
            </div>

            <!-- Bottom Grid Item: Shipping Details Form Layout -->
            <form action="" method="post" style="background:#ffffff; border:1px solid #e2e8f0; border-radius:12px; padding:40px; box-shadow:0 4px 25px rgba(0,0,0,0.04);">
                <h3 style="font-size:1.3rem; font-weight:700; color:#0f172a; margin:0 0 25px 0; border-bottom:2px solid #fff5f0; padding-bottom:10px;">Shipping Information</h3>
                
                <div class="form-grid" style="display:grid; grid-template-columns:1fr; gap:20px;">
                    
                    <div class="input-group" style="display:flex; flex-direction:column; gap:8px;">
                        <label style="font-size:0.9rem; font-weight:600; color:#0f172a;">Your Name *</label>
                        <input type="text" name="name" placeholder="Enter your full name" required style="width:100%; padding:12px; border:1px solid #cbd5e1; border-radius:8px; font-size:0.95rem; outline:none;" onfocus="this.style.borderColor='var(--orange, #ff4500)'" onblur="this.style.borderColor='#cbd5e1'">
                    </div>

                    <div class="input-group" style="display:flex; flex-direction:column; gap:8px;">
                        <label style="font-size:0.9rem; font-weight:600; color:#0f172a;">Your Number *</label>
                        <input type="tel" name="number" placeholder="Enter your phone number" required style="width:100%; padding:12px; border:1px solid #cbd5e1; border-radius:8px; font-size:0.95rem; outline:none;" onfocus="this.style.borderColor='var(--orange, #ff4500)'" onblur="this.style.borderColor='#cbd5e1'">
                    </div>

                    <div class="input-group" style="display:flex; flex-direction:column; gap:8px;">
                        <label style="font-size:0.9rem; font-weight:600; color:#0f172a;">Your Email *</label>
                        <input type="email" name="email" placeholder="Enter your email address" required style="width:100%; padding:12px; border:1px solid #cbd5e1; border-radius:8px; font-size:0.95rem; outline:none;" onfocus="this.style.borderColor='var(--orange, #ff4500)'" onblur="this.style.borderColor='#cbd5e1'">
                    </div>

                    <div class="input-group" style="display:flex; flex-direction:column; gap:8px;">
                        <label style="font-size:0.9rem; font-weight:600; color:#0f172a;">Payment Method *</label>
                        <select name="method" required style="width:100%; padding:12px; border:1px solid #cbd5e1; border-radius:8px; font-size:0.95rem; outline:none; background:#ffffff;">
                            <option value="telebirr" selected>Telebirr</option>
                            <option value="cash on delivery">Cash on Delivery</option>
                            <option value="cbe birr">CBE Birr</option>
                        </select>
                    </div>

                   

                    <div class="input-group" style="display:flex; flex-direction:column; gap:8px;">
                        <label style="font-size:0.9rem; font-weight:600; color:#0f172a;">Flat No / Apartment Name *</label>
                        <input type="text" name="flat" placeholder="e.g. Flat No. 4B" required style="width:100%; padding:12px; border:1px solid #cbd5e1; border-radius:8px; font-size:0.95rem; outline:none;">
                    </div>

                    <!-- FIX: Added missing wrapper div component tags -->
                    <div class="input-group" style="display:flex; flex-direction:column; gap:8px;">
                        <label style="font-size:0.9rem; font-weight:600; color:#0f172a;">Street Name / Neighborhood *</label>
                        <input type="text" name="street" placeholder="e.g. Jemo Area" required style="width:100%; padding:12px; border:1px solid #cbd5e1; border-radius:8px; font-size:0.95rem; outline:none;">
                    </div>

                    <div class="input-group" style="display:flex; flex-direction:column; gap:8px;">
                        <label style="font-size:0.9rem; font-weight:600; color:#0f172a;">City *</label>
                        <input type="text" name="city" placeholder="e.g. Addis Ababa" required style="width:100%; padding:12px; border:1px solid #cbd5e1; border-radius:8px; font-size:0.95rem; outline:none;">
                    </div>

                    <div class="input-group" style="display:flex; flex-direction:column; gap:8px;">
                        <label style="font-size:0.9rem; font-weight:600; color:#0f172a;">Country *</label>
                        <input type="text" name="country" placeholder="e.g. Ethiopia" required style="width:100%; padding:12px; border:1px solid #cbd5e1; border-radius:8px; font-size:0.95rem; outline:none;">
                    </div>
                </div>

                <button type="submit" name="order_btn" class="btn" style="background-color:var(--orange, #ff4500); color:#ffffff; border:none; width:100%; padding:14px; font-size:1rem; font-weight:600; border-radius:8px; cursor:pointer; text-transform:uppercase; letter-spacing:0.5px; margin-top:30px; transition:background 0.2s;" onmouseover="this.style.backgroundColor='#e03d00'" onmouseout="this.style.backgroundColor='var(--orange, #ff4500)'" <?php echo ($grand_total > 0) ? '' : 'disabled style="background-color:#cbd5e1; cursor:not-allowed;"'; ?>>Place Order</button>
            </form>
        </div>
    </section>

    <style>
        @media(min-width: 768px) {
            .form-grid { grid-template-columns: repeat(2, 1fr) !important; }
        }
    </style>
    
    <?php include 'footer.php'; ?>
</body>
</html>

