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
                <a href="admin_pannel.php">home</a>
                <a href="admin_product.php">products</a>
                <a href="admin_order.php">order</a>
                <a href="admin_user.php">users</a>
                <a href="admin_message.php">messages</a>

            </nav>
            <div class="icons">
                <i class="bi bi-person" id="user-btn"></i>
                <i class="bi bi-list" id="menu-btn"></i>

            </div>
            <!-- Inside admin_header.php nav block -->
<a href="admin_message.php" class="message-link">
    <i class="bi bi-envelope"></i> Messages
    <?php
        $select_message_count = mysqli_query($conn, "SELECT id FROM `message`") or die('query failed');
        $message_rows = mysqli_num_rows($select_message_count);
        
        if($message_rows > 0){
            echo '<span class="counter-badge">' . $message_rows . '</span>';
        }
    ?>
</a>

            <div class="user-box">
    <p>username: <span><?php echo $_SESSION['admin_name']; ?></span></p>
    <p>email : <span><?php echo $_SESSION['admin_email']; ?></span></p>
    <!-- Moved the button INSIDE the form tag so it actually submits -->
    <form method="post">
        <button type="submit" name="logout" class="logout-btn">log out</button>
    </form>
</div>


        </div>
        </header>
<div class="banner">
    <div class="detail">
        <h1>admin dashboard</h1>
        <p>Every morning, our ovens come alive to craft the perfect
              Ambasha—honoring the intricate, geometric wheel patterns carved by generations before us. To us,
               baking is not just about ingredients; it is a celebration of heritage, community, and the timeless 
               joy of sharing a breaking of bread with the people we love.  Step inside, catch the sweet aroma of golden-baked
                  tradition, and let us make your day a little brighter, one delicious slice at a time.
         </p>
    </div>


</div>
<div class="line">

</div>
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