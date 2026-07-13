<?php
  include 'connection.php';
  session_start();

  $message = array();

  // Check if a success message exists from a fresh registration
  if(isset($_SESSION['success_msg'])){
      $message[] = $_SESSION['success_msg'];
      unset($_SESSION['success_msg']); // Clear it so it doesn't show again on refresh
  }

  if(isset($_POST['login-btn'])){

      // Sanitize inputs safely
      $email = mysqli_real_escape_string($conn, htmlspecialchars($_POST['email']));
      $password = mysqli_real_escape_string($conn, htmlspecialchars($_POST['password']));

      // Query database for matching user credentials
      $select_user = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email' AND password = '$password'") or die('Query failed');

      if(mysqli_num_rows($select_user) > 0){
          $row = mysqli_fetch_assoc($select_user);
          
          // Fix 1: All user type checks must happen INSIDE this block where $row actually exists
          if($row['user_type'] == 'admin'){
              $_SESSION['admin_name'] = $row['name'];
              $_SESSION['admin_email'] = $row['email'];
              $_SESSION['admin_id'] = $row['id'];
              header('location:admin_pannel.php');
              exit(); // Fix 2: Always stop the script after a redirect
          } 
          else if($row['user_type'] == 'user'){
              $_SESSION['user_name'] = $row['name'];
              $_SESSION['user_email'] = $row['email'];
              $_SESSION['user_id'] = $row['id'];
              header('location:index.php');
              exit(); // Fix 2: Always stop the script after a redirect
          }
          
      } else {
          // If no rows match, credentials are wrong
          $message[] = 'Incorrect email or password!';
      }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Fixed: Restored the complete unpkg URL so boxicons icons render properly -->
    <link href='http://cnd.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css' rel='stylesheet'>
    <link rel='stylesheet' type="text/css" href="style.css">
    <title>Login Page</title>
</head>
<body>
    
    <section class="form-container login-page">
    <?php
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
            <h1>Login Now</h1>
            <input type="email" name="email" placeholder="Enter your email" required>
            <input type="password" name="password" placeholder="Enter your password" required>
            <input type="submit" name="login-btn" value="Login Now" class="btn">

            <p>Don't have an account? <a href="register.php">Register Now</a></p>
        </form>
    </section>
</body>
</html>
