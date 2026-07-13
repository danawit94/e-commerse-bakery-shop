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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://jsdelivr.net">
    <link rel="stylesheet" type="text/css" href="main.css">
    <title>My Orders</title>
</head>
<body>
    <?php include 'header.php'; ?>

    <!-- Banner Component -->
    <div class="banne">
        <h1>My Orders</h1>
        <p>Track your freshly baked bread shipments or review your past traditional bakery purchase summaries here.</p>
        <a href="index.php">home</a><span>/orders</span>
    </div>

    <!-- Orders Main Display Section -->
    <section class="orders-section">
        <section class="shop">
            <h2>Your Order History</h2>
        </section>
        
        <div class="orders-container">
            <?php
            // Securely select tracking entries tied directly to this specific client account
            $order_query = mysqli_query($conn, "SELECT * FROM `order` WHERE user_id = '$user_id' ORDER BY id DESC") or die('Orders query failed');
            
            if (mysqli_num_rows($order_query) > 0) {
                while ($fetch_orders = mysqli_fetch_assoc($order_query)) {
                    // Normalize payment processing and delivery status flags for CSS matching
                    $status = strtolower(trim($fetch_orders['payment_status']));
            ?>
            
            <!-- Individual Order Summary Summary Record Card -->
            <div class="order-card">
                <div class="order-header">
                    <span class="order-date"><i class="bi bi-calendar3"></i> Placed on: <?php echo htmlspecialchars($fetch_orders['placed_on']); ?></span>
                    <!-- Dynamic state status color tag mapping framework element -->
                    <span class="status-badge <?php echo $status; ?>">
                        <?php echo htmlspecialchars($fetch_orders['payment_status']); ?>
                    </span>
                </div>

                <div class="order-body">
                    <p class="order-detail-row"><strong>Name:</strong> <span><?php echo htmlspecialchars($fetch_orders['name']); ?></span></p>
                    <p class="order-detail-row"><strong>Number:</strong> <span><?php echo htmlspecialchars($fetch_orders['number']); ?></span></p>
                    <p class="order-detail-row"><strong>Email:</strong> <span><?php echo htmlspecialchars($fetch_orders['email']); ?></span></p>
                    <p class="order-detail-row"><strong>Address:</strong> <span><?php echo htmlspecialchars($fetch_orders['address']); ?></span></p>
                    <p class="order-detail-row"><strong>Method:</strong> <span><?php echo htmlspecialchars($fetch_orders['method']); ?></span></p>
                    
                    <!-- Line item text break decoration block separator line -->
                    <div class="order-divider"></div>
                    
                    <p class="order-products"><strong>Products Ordered:</strong><br>
                        <span class="product-string-list"><?php echo htmlspecialchars($fetch_orders['total_products']); ?></span>
                    </p>
                </div>

                <div class="order-footer">
                    <span class="total-label">Total Price:</span>
                    <span class="total-amount">$<?php echo htmlspecialchars($fetch_orders['total_price']); ?>/-</span>
                </div>
            </div>
            
            <?php
                }
            } else {
                echo '<p class="empty" style="text-align:center; padding:40px; color:#64748b; font-family:\'Inter\', sans-serif; width:100%;">You haven\'t placed any orders yet!</p>';
            }
            ?>
        </div>
    </section>

    <?php include 'footer.php'; ?>
</body>
</html>
