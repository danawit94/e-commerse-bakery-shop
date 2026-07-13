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

 // FIXED: Moved your User Delete Handler to the absolute top of the file processing engine
 if(isset($_GET['delete'])){
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete']);
    
    if(empty($delete_id)){
        die('Error: Deletion stopped because ID is empty.');
    }

    // Target the 'users' table securely
    $delete_query = mysqli_query($conn, "DELETE FROM `users` WHERE id = '$delete_id'") or die('Query failed: ' . mysqli_error($conn));

    if($delete_query){
        header('location:admin_user.php');
        exit();
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
    <title>admin user</title> <!-- Fixed title naming -->
</head>
<body>
<?php include 'admin_header.php';?>

<?php
    // System warning alert message queue card renderer
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

<div class="linepo"></div>
<section class="users-container">
    <h1 class="title">Administrative Accounts</h1>
    
    <div class="box-container">
       <?php 
        $select_users = mysqli_query($conn, "SELECT * FROM `users` ORDER BY id DESC") or die('Query failed');
        
        if(mysqli_num_rows($select_users) > 0){
            while($fetch_users = mysqli_fetch_assoc($select_users)){
                $role_class = ($fetch_users['user_type'] == 'admin') ? 'role-admin' : 'role-user';
       ?>
        <div class="user-card-3d">
            <div class="glass-sphere"></div>
            
            <div class="card-inner">
                <div class="user-avatar">
                    <i class="bi bi-person-badge-fill"></i>
                </div>
                
                <h3 class="user-name"><?php echo htmlspecialchars($fetch_users['name']); ?></h3>
                
                <div class="metadata-block">
                    <p class="user-email">
                        <i class="bi bi-envelope-open-fill"></i> 
                        <span><?php echo htmlspecialchars($fetch_users['email']); ?></span>
                    </p>
                    <p class="user-id-tag">
                        <i class="bi bi-fingerprint"></i> 
                        Account ID: <span>#<?php echo $fetch_users['id']; ?></span>
                    </p>
                </div>

                <div class="role-badge <?php echo $role_class; ?>">
                    <?php echo strtoupper($fetch_users['user_type']); ?>
                </div>
            </div>

            <div class="card-actions-3d">
                <a href="admin_user.php?delete=<?php echo $fetch_users['id']; ?>" class="delete-btn-3d" onclick="return confirm('Delete this account permanently?')">
                    <i class="bi bi-person-x-fill"></i> Remove Account
                </a>
            </div>
        </div>
       <?php
            }
        } else {
            echo '<div class="empty-3d">No registered user metrics mapped yet!</div>';
        }
       ?>
    </div>
</section>

<script src="script.js"></script>
</body>
</html>
