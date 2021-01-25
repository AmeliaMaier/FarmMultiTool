<?php
try {
    session_start(['cookie_lifetime' => 43200]);
    require_once "./../php_app/db.php";
    if (isset($_SESSION['user_id']) != "") {
        header("Location: dashboard.php");
    }
    if (isset($_POST['login'])) {
        $user_name = $_POST['user_name'];
        $password = $_POST['password'];

        include "./../php_app/password_operations.php";
        $result = compare_passwords($password, get_password_details($user_name));

        if ($result['success']) {
            $_SESSION['user_id'] = $result['user_id'];
            $_SESSION['user_name'] = $result['user_name'];
            $_SESSION['user_type'] = $result['user_type'];
            header("Location: dashboard.php");
        } else {
            $error_message = "Incorrect Username or Password!!!";
        }
    }
}catch(Exception $e) {
    $error_message = $e.getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Simple Login Form in PHP with Validation</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-lg-10">
            <div class="page-header">
                <h2>Login Form in PHP with Validation</h2>
            </div>
            <p>Please fill all fields in the form</p>
            <span class="text-danger"><?php if (isset($error_message)) echo $error_message; ?></span>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group ">
                    <label>Username</label>
                    <input type="text" name="user_name" class="form-control" value="" maxlength="50" required="">
                    <span class="text-danger"><?php if (isset($username_error)) echo $username_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" value="" maxlength="50" required="">
                    <span class="text-danger"><?php if (isset($password_error)) echo $password_error; ?></span>
                </div>
                <input type="submit" class="btn btn-primary" name="login" value="submit">

            </form>
        </div>
    </div>
</div>
</body>
</html>