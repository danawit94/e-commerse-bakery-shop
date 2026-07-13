<?php


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" >
    <link rel='stylesheet' type="text/css" href="style.css">
    <title>Document</title>
</head>
<body>
    <header class="header">
        <div class="flex">
            <a href="admin_pannel.php" class="logo"><img src="img/logo.jpg"></a>
            <nav class="navbar">
                <a href="index.php">HOME</a>
                <a href="about.php">ABOUT US</a>
                <a href="shop.php">SHOP</a>
                <a href="order.php">ORDER</a>
                <a href="contact.php">CONTACT</a>

            </nav>
            <div class="icons">
            <i class="bi bi-person" id="user-btn"></i>
    
    <!-- Wishlist Notification Badge Indicator Element -->
    <a href="wishlist.php" class="icon-link">
        <i class="bi bi-heart"></i>
        <?php if($wishlist_num_rows > 0): ?>
            <sup class="badge"><?php echo $wishlist_num_rows; ?></sup>
        <?php endif; ?>
    </a>
    
    <!-- Active Shopping Cart Badge Indicator Element -->
    <a href="cart.php" class="icon-link">
        <i class="bi bi-cart"></i>
        <?php if($cart_num_rows > 0): ?>
            <sup class="badge"><?php echo $cart_num_rows; ?></sup>
        <?php endif; ?>
    </a>
                <i class="bi bi-list" id="menu-btn"></i>

            </div>
            <!-- Inside admin_header.php nav block -->


            <div class="user-box">
    <p>username: <span><?php echo $_SESSION['user_name']; ?></span></p>
    <p>email : <span><?php echo $_SESSION['user_email']; ?></span></p>
    <!-- Moved the button INSIDE the form tag so it actually submits -->
    <form method="post">
        <button type="submit" name="logout" class="logout-btn">log out</button>
    </form>
</div>


 </div>
</header>



</div>
<script type="text/javascript" src="script2.js" ></script>
<script>
    let menu = document.querySelector('.navbar');
let menuBtn = document.querySelector('#menu-btn');

menuBtn.onclick = () =>{
    menu.classList.toggle('active');
}

let userBox = document.querySelector('.user-box');
let userBtn = document.querySelector('#user-btn');

userBtn.onclick = () =>{
    userBox.classList.toggle('active');
}

window.onscroll = ()=>{
    menu.classList.remove('active');
    userBox.classList.remove('active');
}
</script>

</body>
</html>