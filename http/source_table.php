<?php

function get_html_pieces($user_name, $user_type, $page_path){
    return array(get_navbar($user_name, $user_type, get_page_name($page_path)),
                 get_sources_table(),
                 get_sources_dropdown($user_type));
}

function get_shared_inputs(){
    return '<div class="form-group ">
        <label>Source Type</label>
        <select name="source_type" id="source_type">
            <option value="book">Book</option>
            <option value="webpage">Webpage</option>
        </select>
        <span class="text-danger"><?php if (isset($source_type_error)) echo $source_type_error; ?></span>
    </div>
    <div class="form-group">
        <label>Title</label>
        <input type="text" name="title" class="form-control" value="" maxlength="250" required="">
        <span class="text-danger"><?php if (isset($title_error)) echo $title_error; ?></span>
    </div>';
}

try{
    session_start();
    include "./../php_app/source_operations.php";
    include "./../php_app/shared_html.php";
    include "./../php_app/shared_functions.php";

    if(isset($_SESSION['user_id']) =="") {
        header("Location: login.php");
    }

    list($nav_bar, $html_table, $source_dropdown) = get_html_pieces($_SESSION['user_name'],$_SESSION['user_type'], $_SERVER['PHP_SELF']);

    if (isset($_POST['add'])) {
        $result = add_source($_POST['source_type'], $_POST['address_isbn'], $_POST['title'], $_SESSION['user_id']);

        if ($result['success']) {
            $add_success_message = 'Data source added for Address/ISBN '.$_POST['address_isbn'];
            list($nav_bar, $html_table, $source_dropdown) = get_html_pieces($_SESSION['user_name'],$_SESSION['user_type'], $_SERVER['PHP_SELF']);
        } else {
            unset($add_success_message);
            $add_error_message = $result['error'];
        }
    }

    if (isset($_POST['update'])) {
        $result = update_source($_POST['source_type'], (int) $_POST['source_id'], $_POST['title']);

        if ($result['success']) {
            $update_success_message = 'Data source updated for Title'.$_POST['title'];
            list($nav_bar, $html_table, $source_dropdown) = get_html_pieces($_SESSION['user_name'],$_SESSION['user_type'], $_SERVER['PHP_SELF']);
        } else {
            unset($update_success_message);
            $update_error_message = $result['error'];
        }
    }
}catch(Exception $e) {
    $error_message = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Data Source</title>
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<?php echo  $nav_bar; ?>
<div style="margin-left:25%;padding:1px 16px;height:1000px;">
    <span class="text-danger"><?php if (isset($error_message)) echo $error_message; ?></span>
    <span class="text-success"><?php if (isset($success_message)) echo $success_message; ?></span>
    <div class="row">
        <div class="card">
            <div class="card-body">
                    <div class="page-header">
                        <h2>Existing Data Sources</h2>
                    </div>
                    <span class="table-responsive"> <?php echo $html_table; ?> </span>
            </div>
        </div>
    </div>
    <div class="card-group">
        <div class="card">
            <div class="card-body">
                    <div class="page-header">
                        <h2>Add a Data Source</h2>
                    </div>
                    <p>Please fill all fields in the form</p>
                    <span class="text-danger"><?php if (isset($add_error_message)) echo $add_error_message; ?></span>
                    <span class="text-success"><?php if (isset($add_success_message)) echo $add_success_message; ?></span>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Web Address or ISBN</label>
                            <input type="text" name="address_isbn" class="form-control" value="" maxlength="250" required="">
                            <span class="text-danger"><?php if (isset($address_isbn_error)) echo $address_isbn_error; ?></span>
                        </div>
                        <?php echo  get_shared_inputs(); ?>
                        <input type="submit" class="btn btn-primary" name="add" value="submit">
                    </form>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="page-header">
                    <h2>Update a Data Source</h2>
                </div>
                <p>Please fill all fields in the form</p>
                <span class="text-danger"><?php if (isset($update_error_message)) echo $update_error_message; ?></span>
                <span class="text-success"><?php if (isset($update_success_message)) echo $update_success_message; ?></span>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label>Address/ISBN to Update</label>
                        <span class="custom-select"><?php echo $source_dropdown ?></span>
                        <span class="text-danger"><?php if (isset($data_source_error)) echo $data_source_error; ?></span>
                    </div>
                    <?php echo  get_shared_inputs(); ?>
                    <input type="submit" class="btn btn-primary" name="update" value="submit">
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>