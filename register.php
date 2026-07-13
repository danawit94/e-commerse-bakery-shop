
<?php
  include 'connection.php';

  // 1. Always initialize $message as an empty array
  $message = array();

  if(isset($_POST['submit-btn'])){

    // 2. Fixed deprecated FILTER_SANITIZE_STRING (Using htmlspecialchars instead)
    $name = mysqli_real_escape_string($conn, htmlspecialchars($_POST['name']));
    $email = mysqli_real_escape_string($conn, htmlspecialchars($_POST['email']));
    $password = mysqli_real_escape_string($conn, htmlspecialchars($_POST['password']));
    $cpassword = mysqli_real_escape_string($conn, htmlspecialchars($_POST['cpassword']));

    $select_user = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'") or die('Query failed');

    if(mysqli_num_rows($select_user) > 0){
        // 3. Fixed: Appending to array correctly using []
        $message[] = 'User already exists!';
    } else {
        if($password != $cpassword){
            $message[] = 'Passwords do not match!';
        } else {
            // 4. Fixed: Changed illegal backticks (`) to standard single quotes (') around values
            mysqli_query($conn, "INSERT INTO `users` (`name`, `email`, `password`) VALUES ('$name', '$email', '$password')") or die('Query failed');
            
            // Start session to carry success message to login page
            session_start();
            $_SESSION['success_msg'] = 'Registered successfully!';
            header('location:login.php');
            exit(); // Always exit after a header redirect
        }
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='http://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel='stylesheet' type="text/css" href="style.css">
    <title>Register Page</title>
</head>
<body>
    
    <section class="form-container">
    <?php
    // 5. Fixed: Renamed loop variable to $msg to avoid breaking the array reference
    if (!empty($message)) {
        foreach($message as $msg) {
            echo '
            <div class="message">
                  <span>'.$msg.'</span>
                     <i class="bx bx-x-circle" onclick="this.parentElement.remove()" style="cursor:pointer;"></i>
            </div>
            ';
        }
    }
    ?>
        <form method="post">
            <h1>Register Now</h1>
            <input type="text" name="name" placeholder="enter your name" required>
            <input type="email" name="email" placeholder="enter your email" required>
            <input type="password" name="password" placeholder="Enter Your Password" required>
            <input type="password" name="cpassword" placeholder="Confirm your password" required>
            <input type="submit" name="submit-btn" value="Register Now" class="btn">

            <p> already have an account? <a href="login.php">Log in</a></p>
        </form>
    </section> <!-- Added missing closing tags -->
</body>
</html>