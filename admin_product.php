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

 // Adding products to DB
 if(isset($_POST['add_product'])){
    $product_name   = mysqli_real_escape_string($conn, htmlspecialchars($_POST['name']));
    $product_price  = mysqli_real_escape_string($conn, htmlspecialchars($_POST['price']));
    $product_detail = mysqli_real_escape_string($conn, htmlspecialchars($_POST['details']));

    $image_name = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder   = 'image/' . $image_name;

    $select_product_name = mysqli_query($conn, "SELECT name FROM `products` WHERE name = '$product_name'") 
        or die('Query Failed: ' . mysqli_error($conn));

    if(mysqli_num_rows($select_product_name) > 0){
        $message[] = 'Product name already exists!';
    } else {
        $insert_product = mysqli_query($conn, "INSERT INTO `products` (`name`, `price`, `product_detail`, `images`) 
            VALUES ('$product_name', '$product_price', '$product_detail', '$image_name')") 
            or die('Insertion Failed: ' . mysqli_error($conn));

        if($insert_product){
            if($image_size > 2000000){
                $message[] = 'Image size is too large! Maximum limit is 2MB.';
            } else {
                if(move_uploaded_file($image_tmp_name, $image_folder)) {
                    $message[] = 'Product added successfully!';
                } else {
                    $message[] = 'Product saved, but image file upload failed.';
                }
            }
        }
    }
    
 }

 // Delete code logic handler
 if(isset($_GET['delete'])){
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete']);
    
    if(empty($delete_id)){
        die('Error: Deletion stopped because ID is empty.');
    }

    $select_delete_image = mysqli_query($conn, "SELECT `images` FROM `products` WHERE id = '$delete_id'") or die('query failed');
    $fetch_delete_image = mysqli_fetch_assoc($select_delete_image);
    
    if($fetch_delete_image && !empty($fetch_delete_image['images'])) {
        $file_to_delete = 'image/' . $fetch_delete_image['images'];
        if(file_exists($file_to_delete)) {
            unlink($file_to_delete);
        }
    }

    mysqli_query($conn, "DELETE FROM `products` WHERE id = '$delete_id'") or die('query failed');
    mysqli_query($conn, "DELETE FROM `cart` WHERE pid = '$delete_id'") or die('query failed');
    mysqli_query($conn, "DELETE FROM `wishlist` WHERE pid = '$delete_id'") or die('query failed');

    header('location:admin_product.php');
    exit();

    if(isset($_POST['update_product'])){
        $update_id = $_POST['update_id'];
        $update_name= $_POST['update_name'];
        $update_price= $_POST['update_price'];
        $update_detail=$_POST['update_detail'];
        $update_image=$_FILES['update_image']['name'];
        $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
        $update_image_folder='image/'.$update_image;


        $update_query= mysqli_query($conn,"UPDATE `products` SET `id`='$update_id',`name`='$update_name',`price`='$update_price',
        `product_detail`='$update_detail',`images`='$update_image' WHERE id=' $update_id'") or die('query filed');
        if($update_query){
            move_uploaded_file($update_image_tmp_name,$update_image_folder);
            header('location:admin_product.php');

        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" >
    <link rel='stylesheet' type="text/css" href="style.css">
    <title>admin product</title>
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
?>
    
<div class="line2"></div>

<section class="add-products-from-container">
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="input-filed">
            <label>product name</label>
            <input type="text" name="name" required>
        </div>
        <div class="input-filed">
            <label>product price</label>
            <input type="text" name="price" required>
        </div>
        <div class="input-filed">
            <label>product details</label>
            <textarea name="details" cols="30" rows="10" required></textarea>
        </div>
        <div class="input-filed">
            <label>product image</label>
            <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" required>
        </div>
        <input type="submit" name="add_product" value="add product" class="btn">
    </form>
</section>

<div class="linepop">
     <h1>products</h1>
</div>

<div class="line4">
<section class="show-products">
    <div class="box-container">
        <?php
        $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
        if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
        ?>
        <div class="boxx">
            <div class="img-wrapper">
                <img src="image/<?php echo $fetch_products['images']; ?>" class="product-thumb" alt="">
            </div>
            
            <div class="content-wrapper">
                <h4>🥧 <?php echo $fetch_products['name']; ?></h4>
                <p class="price-tag">💲 Price: <span><?php echo $fetch_products['price']; ?></span> 💸</p>
                <details class="product-desc">
                    <summary>View Details</summary>
                    <p><?php echo $fetch_products['product_detail']; ?></p>
                </details>
            </div>

            <div class="actions">
                <a href="admin_product.php?edit=<?php echo $fetch_products['id']; ?>" class="edit">Edit</a>
                <a href="admin_product.php?delete=<?php echo $fetch_products['id']; ?>" class="delete" onclick="return confirm('Want to delete this product?')">Delete</a>
            </div>
        </div>
        <?php
            } 
        } else {
            echo '<div class="empty">No products added yet!</div>';
        }
        ?>
    </div>
</section>
</div>



<?php
$fetch_edit = false;
if(isset($_GET['edit'])){
    $edit_i = mysqli_real_escape_string($conn, $_GET['edit']);
    $edit_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$edit_i'") or die('query failed');
    if(mysqli_num_rows($edit_query) > 0){
        $fetch_edit = mysqli_fetch_assoc($edit_query); 
    }
}
?>

<!-- 2. NOW THE SELECTION CHECK ON LINE 220 RUNS ERROR-FREE -->
<section class="update-container" style="display: <?php echo $fetch_edit ? 'block' : 'none'; ?>;">
    <?php if($fetch_edit) { ?>
        <form action="" method="POST" enctype="multipart/form-data">
            <?php if(!empty($fetch_edit['images']) && file_exists('image/'.$fetch_edit['images'])): ?>
                <img src="image/<?php echo $fetch_edit['images']; ?>" alt="Pastry Preview">
            <?php else: ?>
                <div class="no-image-circle">No Image</div>
            <?php endif; ?>
            
            <input type="hidden" name="update_id" value="<?php echo $fetch_edit['id']; ?>">
            <input type="hidden" name="update_old_image" value="<?php echo $fetch_edit['images']; ?>">
            
            <input type="text" name="name" value="<?php echo $fetch_edit['name']; ?>" required>
            <input type="number" name="price" min="0" value="<?php echo $fetch_edit['price']; ?>" required>
            <textarea name="product_detail" cols="30" rows="10" required><?php echo $fetch_edit['product_detail']; ?></textarea>
            
            <input type="file" name="image" accept="image/jpg, image/jpeg, image/png">
            
            <input type="submit" name="update_product" value="update" class="edit">
            <input type="reset" value="cancel" class="option-btn" id="close-form" onclick="window.location.href='admin_product.php'">
        </form>
    <?php
echo  " <script> document.querySelector('update-container').style.display='block'</script> "; } ?>
</section>

<script src="script.js"></script>
</body>
</html>

