<?php
include 'connection.php';
session_start();
$admin_id = $_SESSION['admin_name'];
if (!isset($admin_id)){ header('location:login.php'); exit(); }
if (isset($_POST['logout'])){ session_destroy(); header('location:login.php'); exit(); }

// 1. Update Payment Status Processing
if(isset($_POST['update_order'])){
    $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);
    $update_payment = mysqli_real_escape_string($conn, $_POST['update_payment']);
    mysqli_query($conn, "UPDATE `order` SET payment_status = '$update_payment' WHERE id = '$order_id'") or die('Update query failed');
    $message[] = 'Payment status updated!';
}

// 2. Delete Order Row Processing
if(isset($_GET['delete'])){
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete']);
    if(empty($delete_id)){ die('Error: ID is empty.'); }
    mysqli_query($conn, "DELETE FROM `order` WHERE id = '$delete_id'") or die('Delete query failed');
    header('location:admin_order.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://jsdelivr.net" >
    <link rel='stylesheet' type="text/css" href="style.css">
    <title>Admin Orders Dashboard</title>
    <style>
        .orders-container { max-width: 1200px; margin: 4rem auto; padding: 3rem 2rem; position: relative; background-image: linear-gradient(135deg, rgba(255,250,240,0.95), rgba(252,201,39,0.05)), url('img/sanbusa.jpg'); background-size: cover; background-position: center; background-attachment: fixed; border-radius: 24px; box-shadow: inset 0 0 100px rgba(61,37,22,0.08), 0 20px 50px rgba(0,0,0,0.05); }
        .orders-container .title { font-size: 2.6rem; color: #3d2516; text-align: center; font-weight: 800; margin-bottom: 4rem; text-shadow: 2px 4px 10px rgba(61,37,22,0.1); position: relative; }
        .orders-container .title::after { content: ''; display: block; width: 60px; height: 5px; background: #fcc927; border-radius: 10px; margin: 0.8rem auto 0 auto; box-shadow: 0 2px 5px rgba(252,201,39,0.4); }
        .orders-container .box-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 3rem; }
        .orders-container .order-card-3d { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.6); border-radius: 20px; padding: 2rem; position: relative; display: flex; flex-direction: column; justify-content: space-between; box-shadow: 0 5px 15px rgba(61, 37, 22, 0.05), 0 15px 35px rgba(61, 37, 22, 0.08); transform: perspective(1000px); transition: all 0.4s ease; }
        .orders-container .order-card-3d:hover { transform: perspective(1000px) translateY(-8px) rotateX(2deg); box-shadow: 0 20px 40px rgba(61, 37, 22, 0.15), 0 30px 70px rgba(61, 37, 22, 0.12); border-color: rgba(252, 201, 39, 0.4); }
        .orders-container .metadata-block { background: rgba(255, 255, 255, 0.6); border: 1px solid rgba(61, 37, 22, 0.05); border-radius: 12px; padding: 1.2rem; margin-bottom: 1.5rem; font-size: 0.95rem; color: #594235; line-height: 1.6; }
        .orders-container .metadata-block p { margin: 0.5rem 0; display: flex; align-items: flex-start; gap: 0.6rem; }
        .orders-container .metadata-block i { color: #fcc927; margin-top: 2px; }
        .orders-container .metadata-block span { font-weight: 600; color: #3d2516; }
        .orders-container .products-list { background: #fffcf4; border: 1px dashed #ebdccb; padding: 1rem; border-radius: 10px; font-style: italic; color: #705849; margin-bottom: 1.5rem; }
        .orders-container .status-form { display: flex; gap: 0.8rem; margin-bottom: 1rem; }
        .orders-container .status-dropdown { flex: 1; padding: 0.6rem; border-radius: 8px; border: 1px solid #d3c2b0; background: #ffffff; color: #3d2516; font-weight: 600; outline: none; }
        .orders-container .update-btn { background: #fcc927; color: #3d2516; border: none; padding: 0.6rem 1rem; border-radius: 8px; font-weight: 700; cursor: pointer; transition: background 0.2s; }
        .orders-container .update-btn:hover { background: #3d2516; color: #ffffff; }
        .orders-container .delete-btn-3d { width: 100%; padding: 0.8rem; background: #fff0f1; border: 1px solid #fcdbde; color: #d63447; font-size: 0.85rem; font-weight: 700; text-transform: uppercase; text-decoration: none; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; transition: all 0.2s ease-in-out; }
        .orders-container .delete-btn-3d:hover { background: #d63447; color: #ffffff; box-shadow: 0 6px 15px rgba(214, 52, 71, 0.3); }
    </style>
</head>
<body>

<?php include 'admin_header.php'; ?>

<?php
if (isset($message)) {
    foreach($message as $msg) {
        echo '<div class="message"><span>'.$msg.'</span><i class="bi bi-x-circle" onclick="this.parentElement.remove()" style="cursor:pointer;"></i></div>';
    }
}
?>

<div class="linepo"></div>

<section class="orders-container">
    <h1 class="title">Placed Customer Orders</h1>
    <div class="box-container">
        <?php
        $select_orders = mysqli_query($conn, "SELECT * FROM `order` ORDER BY id DESC") or die('Query failed');
        if(mysqli_num_rows($select_orders) > 0){
            while($fetch_orders = mysqli_fetch_assoc($select_orders)){
        ?>
        <div class="order-card-3d">
            <div class="card-inner">
                <div class="metadata-block">
                    <p><i class="bi bi-hash"></i> Order ID: <span>#<?php echo $fetch_orders['id']; ?></span></p>
                    <p><i class="bi bi-person-fill"></i> Customer: <span><?php echo htmlspecialchars($fetch_orders['name']); ?></span></p>
                    <p><i class="bi bi-person-circle"></i> User ID: <span>#<?php echo $fetch_orders['user_id']; ?></span></p>
                    <p><i class="bi bi-telephone-fill"></i> Phone: <span><?php echo htmlspecialchars($fetch_orders['number']); ?></span></p>
                    <p><i class="bi bi-envelope-fill"></i> Email: <span><?php echo htmlspecialchars($fetch_orders['email']); ?></span></p>
                    <p><i class="bi bi-geo-alt-fill"></i> Address: <span><?php echo htmlspecialchars($fetch_orders['address']); ?></span></p>
                    <p><i class="bi bi-credit-card-2-front-fill"></i> Method: <span><?php echo htmlspecialchars($fetch_orders['method']); ?></span></p>
                    <p><i class="bi bi-calendar-event-fill"></i> Date Placed: <span><?php echo $fetch_orders['placed_on']; ?></span></p>
                </div>
                <div class="products-list">
                    <i class="bi bi-bag-check-fill"></i> <strong>Items Ordered:</strong><br>
                    <?php echo htmlspecialchars($fetch_orders['total_products']); ?>
                </div>
                <div class="metadata-block" style="border-left: 4px solid #fcc927;">
                    <p style="font-size: 1.1rem; margin:0;"><i class="bi bi-cash-coin" style="color:#3d2516;"></i> Total: <span style="color:#d63447; font-size:1.2rem;">$<?php echo $fetch_orders['total_price']; ?>/-</span></p>
                </div>
                <form action="" method="POST" class="status-form">
                    <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
                    <select name="update_payment" class="status-dropdown">
                        <option value="pending" <?php if($fetch_orders['payment_status'] == 'pending'){ echo 'selected'; } ?>>Pending</option>
                        <option value="completed" <?php if($fetch_orders['payment_status'] == 'completed'){ echo 'selected'; } ?>>Completed</option>
                    </select>
                    <input type="submit" value="Update" name="update_order" class="update-btn">
                </form>
            </div>
            <div class="card-actions-3d">
                <a href="admin_order.php?delete=<?php echo $fetch_orders['id']; ?>" class="delete-btn-3d" onclick="return confirm('Delete order permanently?')"><i class="bi bi-trash3-fill"></i> Delete Order</a>
            </div>
        </div>
        <?php
            }
        } else {
            echo '<div style="grid-column: 1/-1;" class="empty-3d">No customer orders have been logged yet!</div>';
        }
        ?>
    </div>
</section>

<script src="script.js"></script>
</body>
</html>
