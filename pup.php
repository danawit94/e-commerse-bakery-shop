<?php
// Start the session
session_start();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>pup</title>
</head>
<body>
    
<?php
// Set session variables
$_SESSION["favcolor"] = "green";
$_SESSION["favanimal"] = "cat";
echo "Session variables are set.";
?>
    <img src="img/—Pngtree—a decadent multi-layered chocolate cake_20747329.png" alt="">
    <img src="img/—Pngtree—french bread basket bakery food_14698351.png" alt="">
    <img src="img/image_f5960324.png" alt="">
    <style>
        body{
            background:(--beckery-orange);
        }
        img{
            height:50vh;
        }
    </style>
</body>
</html>