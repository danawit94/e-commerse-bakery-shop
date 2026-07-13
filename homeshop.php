<?php 
include 'connection.php'; 
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ambasha-home page</title>
    <!-- Point this directly to your main style file -->
    <link rel="stylesheet" href="main.css"> 
</head>

<body>

<section class="popullar">
    <h2>Popular Products</h2>
    
    <!-- Custom Navigation Chevron Triggers -->
    <div class="controll">
        <i class="bi bi-chevron-left left-arrow"></i>
        <i class="bi bi-chevron-right right-arrow"></i>
    </div>
    
    <!-- Slick Slider Core Row Mount -->
    <div class="popular-slider-track">
        <?php 
        $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('Query failed');
        if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
        ?>
        
        <!-- Individual Product Entry Card -->
        <form action="" class="carr" method="post">
            <img src="image/<?php echo $fetch_products['images']; ?>" alt="<?php echo $fetch_products['name']; ?>">
            <div class="price"><?php echo $fetch_products['price']; ?></div>
            <div class="name"><?php echo $fetch_products['name']; ?></div>
            
            <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
            <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
            <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
            <input type="hidden" name="product_quantity" value="1">
            <input type="hidden" name="product_image" value="<?php echo $fetch_products['images']; ?>">
            
            <div class="icocn">
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
    </div>
</section>



<script src="https://jquery.com"></script>
<!-- Make sure to also add the Slick JavaScript library right below it -->
<script src="https://cloudflare.com"></script>

    <script src="script2.js"></script>
 
  
</body>
</html>
