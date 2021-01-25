<?php
try{
    session_start();
    if(isset($_SESSION['user_id']) =="") {
        header("Location: login.php");
    }
    if (isset($_POST['logout'])) {
        session_destroy();
        header('Location: login.php');
    }
}catch(Exception $e) {
    $error_message = $e.getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Info Dashboard | Tutsmake.com</title>
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <span class="text-danger"><?php if (isset($error_message)) echo $error_message; ?></span>
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Name :- <?php echo $_SESSION['user_name']?></h5>
                    <p class="card-text">Account_Type :- <?php echo $_SESSION['user_type']?></p>
                    <input type="submit" class="btn btn-primary" name="logout" value="Logout">
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>