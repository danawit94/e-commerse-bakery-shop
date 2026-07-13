<?php
 include 'connection.php';

 session_start();
 $admin_id = $_SESSION['admin_name'];

 if (!isset($admin_id)){
    header('location:login.php');
    exit();
 }
 if (isset($_POST['logout'])){
    session_destroy();
    header('location:login.php');
    exit();
 }

 // Processing Edit/Update function if submitted
 if(isset($_POST['update_product'])){
    $update_id = mysqli_real_escape_string($conn, $_POST['update_id']);
    $update_name = mysqli_real_escape_string($conn, htmlspecialchars($_POST['name']));
    $update_price = mysqli_real_escape_string($conn, htmlspecialchars($_POST['price']));
    $update_detail = mysqli_real_escape_string($conn, htmlspecialchars($_POST['product_detail']));

    mysqli_query($conn, "UPDATE `products` SET name = '$update_name', price = '$update_price', product_detail = '$update_detail' WHERE id = '$update_id'") or die('query failed');

    $update_image = $_FILES['image']['name'];
    $update_image_tmp_name = $_FILES['image']['tmp_name'];
    $update_image_size = $_FILES['image']['size'];
    $update_folder = 'image/'.$update_image;
    $old_image = $_POST['update_old_image'];

    if(!empty($update_image)){
       if($update_image_size > 2000000){
          $message[] = 'image file size is too large';
       }else{
          mysqli_query($conn, "UPDATE `products` SET images = '$update_image' WHERE id = '$update_id'") or die('query failed');
          move_uploaded_file($update_image_tmp_name, $update_folder);
          if(file_exists('image/'.$old_image) && !empty($old_image)){
             unlink('image/'.$old_image);
          }
          $message[] = 'product updated successfully!';
       }
    }
    header('location:admin_product.php');
    exit();
 }


 

 // Delete code logic handler
 if(isset($_GET['delete'])){
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete']);
    
    
    mysqli_query($conn, "DELETE FROM `wishlist` WHERE pid = '$delete_id'") or die('query failed');

    header('location:admin_product.php');
    exit();
 }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" >
    <link rel='stylesheet' type="text/css" href="style.css">
    <title>admin message</title>
</head>
<body>
<?php include 'admin_header.php';?>

<?php
    if (isset($message)) {
        foreach($message as $msg) {
            echo '
            <div class="message">
                  <span>'.$msg.'</span>
                     <i class="bi bi-x-circle" onclick="this.parentElement.remove()" style="cursor:pointer;"></i>
            </div>
            ';
        }
    }
    
    if(isset($_GET['delete'])){
        $delete_id = mysqli_real_escape_string($conn, $_GET['delete']);
        
        if(!empty($delete_id)){
            mysqli_query($conn, "DELETE FROM `message` WHERE id = '$delete_id'") or die('Query failed');
            header('location:admin_message.php');
            exit();
        }
    }
?>
<div class="linepo">

<section class="message-container">
    <h1 class="title">Unread Messages</h1>


        
    <div class="box-container">
       <?php 
        // 1. Fetches only unread messages from your database 'message' table
        // Assumes your table has a status or fallback tracker column
        $select_message = mysqli_query($conn, "SELECT * FROM `message` ORDER BY id DESC") or die('Query failed');
        
        if(mysqli_num_rows($select_message) > 0){
            while($fetch_message = mysqli_fetch_assoc($select_message)){
       ?>
        <!-- Inside the while loop block of admin_message.php -->
<div class="boxx">
    <div class="content-wrapper">
        <h4>✉️ From: <?php echo htmlspecialchars($fetch_message['name']); ?></h4>
        <p class="price-tag">📧 Email: <span><?php echo htmlspecialchars($fetch_message['email']); ?></span></p>
        <details class="product-desc" open>
            <summary>Message Content</summary>
            <p>"<?php echo htmlspecialchars($fetch_message['message']); ?>"</p>
        </details>
    </div>

    <div class="actions">
        <a href="mailto:<?php echo $fetch_message['email']; ?>" class="edit">
            <i class="bi bi-reply-fill"></i> Reply
        </a>
        <a href="admin_message.php?delete=<?php echo $fetch_message['id']; ?>" class="delete" onclick="return confirm('Delete this message permanently?')">
            <i class="bi bi-trash3-fill"></i> Delete
        </a>
    </div>
</div>

       <?php
            } // Closes the while loop matrix
        } else {
            // Displays your standard empty card indicator if zero entries are found
            echo '<div class="empty">You have no unread messages!</div>';
        }
       ?>
    </div>
   </div>





</section>

<style>
    .linepo {
    background-image: url('img/well.jpg'), url('img/ned.jpg');
    background-position: right top, center;
    background-repeat: no-repeat, no-repeat;
    background-size: 100px auto, cover; /* Set your top-right image width (e.g., 100px) */
    width: 100%;
    height: 100vh; /* Changed 'cover' to a valid height like 100vh or 500px */
}

</style>
<script src="script.js"></script>
</body>
</html>

