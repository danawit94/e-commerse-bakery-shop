<?php
 include 'connection.php';

 session_start();
 $admin_id= $_SESSION['admin_name'];

 if (!isset($admin_id)){
    header('location:login.php');
    exit() ;
 }
 if (isset($_POST['logout'])){
    session_destroy();
    header('location:login.php');
 }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" >


    <link rel='stylesheet' type="text/css" href="style.css">
    
   
    <title>admin pannel</title>
</head>
<body>
    <?php include 'admin_header.php';?>
   
    <section class="dashboard">
    <div class="box-container">
        
        <!-- 1. Total Pendings Box -->
        <div class="box">
            <?php  
            $total_pendings = 0;
            $select_pendings = mysqli_query($conn, "SELECT * FROM `order` WHERE payment_status='pending'") or die('query failed');
            while($fetch_pending = mysqli_fetch_assoc($select_pendings)) {
                $total_pendings += $fetch_pending['total_price'];
            }
            ?>
            <!-- Fixed: Changed variable name from $total_pendings to match your loop variable $total_pending -->
            <h3><?php echo $total_pendings; ?> ETB</h3>
            <p>total pendings</p>
        </div>

        <!-- 2. Total Completes Box -->
        <div class="box">
            <?php  
            $total_completes = 0;
            $select_completes = mysqli_query($conn, "SELECT * FROM `order` WHERE payment_status='completed'") or die('query failed'); // Fixed 'completes' to standard 'completed' if your DB uses that
            while($fetch_completes = mysqli_fetch_assoc($select_completes)) {
                $total_completes += $fetch_completes['total_price'];
            }
            ?>
            <h3><?php echo $total_completes; ?> ETB</h3>
            <p>total completes</p>
        </div>

        <!-- 3. Orders Placed Box -->
        <div class="box">
            <?php  
            $select_orders = mysqli_query($conn, "SELECT * FROM `order`") or die('query failed');
            $num_of_orders = mysqli_num_rows($select_orders);
            ?>
            <h3><?php echo $num_of_orders; ?></h3>
            <p>orders placed</p>
        </div>

        <!-- 4. Products Added Box -->
        <div class="box">
            <?php  
            // Fixed: Changed table from `orders` to your actual `products` storage table
            $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
            $num_of_products = mysqli_num_rows($select_products);
            ?>
            <h3><?php echo $num_of_products; ?></h3>
            <p>products added</p>
        </div>

        <!-- 5. Normal Users Box -->
        <div class="box">
            <?php  
            $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'user'") or die('query failed');
            $num_of_users = mysqli_num_rows($select_users);
            ?>
            <h3><?php echo $num_of_users; ?></h3>
            <p>total normal users</p>
        </div>

        <!-- 6. Total Admins Box -->
        <div class="box">
            <?php  
            $select_admins = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'admin'") or die('query failed'); // Fixed matching string 'admins' to 'admin'
            $num_of_admins = mysqli_num_rows($select_admins);
            ?>
            <!-- Fixed: Changed from non-existent $total_admins to your computed value $num_of_admins -->
            <h3><?php echo $num_of_admins; ?></h3>
            <p>total admins</p>
        </div>

        <!-- 7. Total Registered Accounts Box -->
        <div class="box">
            <?php  
            $select_total_users = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
            $num_of_total_users = mysqli_num_rows($select_total_users);
            ?>
            <h3><?php echo $num_of_total_users; ?></h3>
            <p>total accounts</p>
        </div>

        <!-- 8. New Messages Box -->
        <div class="box">
            <?php  
            // Fixed: Selected from your `message` table instead of filtering the `users` list
            $select_message = mysqli_query($conn, "SELECT * FROM `message`") or die('query failed');
            $num_of_message = mysqli_num_rows($select_message);
            ?>
            <h3><?php echo $num_of_message; ?></h3>
            <p>new messages</p>
        </div>

    </div> <!-- Added missing closing container div -->
</section>


<style>
.dashboard .box-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    padding:1rem 2rem;
    padding-bottom:2rem;
    max-width: 1200px;
    margin: 0 auto;
    background-color: orange ;
}
.dashboard .box-container .box {
    background: #fff;
    border: 2px solid #e1d3c1;
    padding: 2rem;
    text-align: center;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}
.dashboard .box-container .box h3 {
    font-size: 2rem;
    color: var(--chocolate);
    margin-bottom: 0.5rem;
}
.dashboard .box-container .box p {
    text-transform: uppercase;
    font-size: 0.85rem;
    color: var(--orange);
    font-weight: 700;
}
.line2 {
    width: 100%;
    height: 120px;
    background-image: url(img/sanbusa.jpg);
    margin-bottom: 2rem;
}
</style>


</body>
</html>