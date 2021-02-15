<?php
try{
    session_start();
    include "./../php_app/shared_html.php";
    include "./../php_app/shared_functions.php";
    if(isset($_SESSION['user_id']) =="") {
        header("Location: login.php");
    }
    $nav_bar = get_navbar($_SESSION['user_name'], $_SESSION['user_type'], get_page_name($_SERVER['PHP_SELF']));

}catch(Exception $e) {
    $error_message = $e.getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Info Dashboard</title>
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<?php echo  $nav_bar; ?>
<div style="margin-left:25%;padding:1px 16px;height:1000px;">
    <span class="text-danger"><?php if (isset($error_message)) echo $error_message; ?></span>
    <span class="text-success"><?php if (isset($success_message)) echo $success_message; ?></span>
</body>
</html>