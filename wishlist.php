<?php
include 'connection.php';

session_start();

$user_id = $_SESSION['user_id'] ?? '';
$admin_id = $_SESSION['user_name'] ?? '';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit();
}

// Fixed: Added exit() to logout processing
if (isset($_POST['logout'])) {
    session_destroy();
    header('location:login.php');
    exit();
}
 
$message = [];

// ==========================================================================
// 1. ADD TO WISHLIST WORKFLOW PROCESSING
// ==========================================================================
if (isset($_POST['add_to_wishlist'])) {
    if (empty($user_id)) {
        header('location:login.php');
        exit();
    }

    $product_id    = mysqli_real_escape_string($conn, $_POST['product_id']);
    $product_name  = mysqli_real_escape_string($conn, $_POST['product_name']);
    $product_price = mysqli_real_escape_string($conn, trim($_POST['product_price']));
    $product_image = mysqli_real_escape_string($conn, $_POST['product_image']);

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
    $product_quantity = 1;

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

// Fixed single item deletion mapping verification
if (isset($_GET['delete'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete']);
    
    if (empty($delete_id)) {
        die('Error: Deletion stopped because ID is empty.');
    }

    mysqli_query($conn, "DELETE FROM `wishlist` WHERE id = '$delete_id' AND user_id = '$user_id'") or die('query failed');
    header('location:wishlist.php');
    exit();
}

// Fixed: Corrected variable context scope bugs inside delete_all 
if (isset($_GET['delete_all'])) {
    mysqli_query($conn, "DELETE FROM `wishlist` WHERE user_id = '$user_id'") or die('query failed');
    header('location:wishlist.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="main.css">
    <title>Wishlist</title>
</head>
<body>
    <?php include 'header.php' ?>
    <div class="banne">
        <h1>my wishlist</h1>
        <p>Every Ambasha is handcrafted using traditional recipes and the finest ingredients. Our passion is bringing families together through the aroma and taste of freshly baked bread.</p>
        <a href="index.php">home</a><span>/wishlist</span>
    </div>
    
    <section class="shop">
        <h2>products added in wishlist</h2>
        
        <?php
        $grand_total = 0;
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
            // Fixed: Only fetch items belonging to the current user
            $select_wishlist = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE user_id = '$user_id'") or die('Query failed');
            if (mysqli_num_rows($select_wishlist) > 0) {
                while ($fetch_wishlist = mysqli_fetch_assoc($select_wishlist)) {
            ?>
            
            <!-- Individual Product Entry Card -->
            <form action="" class="brar" method="post">
                <img src="image/<?php echo $fetch_wishlist['image']; ?>" alt="<?php echo $fetch_wishlist['name']; ?>">
                <div class="price">$<?php echo $fetch_wishlist['price']; ?></div>
                <div class="name"><?php echo $fetch_wishlist['name']; ?></div>
                
                <input type="hidden" name="product_id" value="<?php echo $fetch_wishlist['pid']; ?>">
                <input type="hidden" name="product_name" value="<?php echo $fetch_wishlist['name']; ?>">
                <input type="hidden" name="product_price" value="<?php echo $fetch_wishlist['price']; ?>">
                <input type="hidden" name="product_image" value="<?php echo $fetch_wishlist['image']; ?>">
                
                <div class="icon">
                    <a href="view_page.php?pid=<?php echo $fetch_wishlist['pid']; ?>" class="bi bi-eye-fill"></a>
                    <a href="wishlist.php?delete=<?php echo $fetch_wishlist['id']; ?>" class="bi bi-x" onclick="return confirm('do you want to delete this product from your wishlist')"></a>
                    <button type="submit" name="add_to_cart" class="bi bi-cart" id="only"></button>
                </div>
            </form>
            
            <?php
                $grand_total += $fetch_wishlist['price'];
                }
            } else {
                echo '<p class="empty" style="text-align:center; padding:20px; color:#64748b;">No products added yet</p>';
            }
            ?>
        </div> 

        <div class="wishlist_total">
            <p>total amount payable: <span>$<?php echo $grand_total; ?>/-</span></p>
            <a href="shop.php">continue shopping</a>
            <a href="wishlist.php?delete_all" class="btn2 <?php echo ($grand_total > 0) ? '' : 'disabled'; ?>" onclick="return confirm('do you want to delete all items in your wishlist')"></a>
        </div>
    </section>

    <?php include 'footer.php'; ?>
</body>
</html>
