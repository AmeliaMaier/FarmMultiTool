<?php

function get_html_pieces($user_name, $user_type, $page_path){
    return array(get_navbar($user_name, $user_type, get_page_name($page_path)),
                get_sources_archive_table(),
                get_archived_sources_dropdown($user_type));
}

function get_shared_inputs()
{
    return '<div class="form-group">
                <label>SFTP Folder Used</label>
                <span class="custom-select"><'.get_sftp_folder_dropdown().'></span>
                <span class="text-danger"><?php if (isset($sftp_folder_error)) echo $sftp_folder_error; ?></span>
            </div>
            <div class="form-group">
                <label>SFTP File Name</label>
                <input type="Text" name="sftp_file_name" class="form-control" value=""  maxlength="250" required="">
                <span class="text-danger"><?php if (isset($sftp_file_error)) echo $sftp_file_error; ?></span>
            </div>
            <div class="form-group">
                <label>SFTP Sharing URL</label>
                <input type="text" name="sftp_share_url" class="form-control" value="" maxlength="250" required="">
                <span class="text-danger"><?php if (isset($sftp_share_url_error)) echo $sftp_share_url_error; ?></span>
            </div>';
}

try{
    session_start();
    if(!function_exists('add_source_archive')){include "./../php_app/source_operations.php";}
    if(!function_exists('get_navbar')){include "./../php_app/shared_html.php";}
    if(!function_exists('get_page_name')){include "./../php_app/shared_functions.php";}

    if(isset($_SESSION['user_id']) =="") {
        header("Location: login.php");
    }
    list($nav_bar, $html_table, $archived_source_dropdown) =
        get_html_pieces($_SESSION['user_name'],$_SESSION['user_type'], $_SERVER['PHP_SELF']);


    if (isset($_POST['add'])) {
        $result = add_source_archive($_POST['archive_type'], (int) $_POST['source_id'], (int) $_POST['sftp_folder_id'],
                                    $_POST['sftp_file_name'], $_POST['sftp_share_url'], $_SESSION['user_id']);

        if ($result['success']) {
            $add_success_message = 'Data source archive record added'.$_POST['source_id'];
            list($nav_bar, $html_table, $archived_source_dropdown) =
                get_html_pieces($_SESSION['user_name'],$_SESSION['user_type'], $_SERVER['PHP_SELF']);
        } else {
            unset($add_success_message);
            $add_error_message = $result['error'];
        }
    }
    if (isset($_POST['update'])) {
        $result = update_source_archive((int) $_POST['archived_source_id'], (int) $_POST['sftp_folder_id'],
                                        $_POST['sftp_file_name'], $_POST['sftp_share_url']);

        if ($result['success']) {
            $update_success_message = 'Data source archive record added'.$_POST['archived_source_id'];
            list($nav_bar, $html_table,  $archived_source_dropdown) =
                get_html_pieces($_SESSION['user_name'],$_SESSION['user_type'], $_SERVER['PHP_SELF']);
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
    <title>Add Archived Data Source</title>
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
                            <h2>Existing Data Source Archive Records</h2>
                        </div>
                        <span class="table-responsive"> <?php echo $html_table; ?> </span>
                </div>
            </div>
    </div>
    <div class="card-group">
        <div class="card">
            <div class="card-body">
                <h2>Add Archived Data Source</h2>
            </div>
            <p>Please fill all fields in the form</p>
            <span class="text-danger"><?php if (isset($add_error_message)) echo $add_error_message; ?></span>
            <span class="text-success"><?php if (isset($add_success_message)) echo $add_success_message; ?></span>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group ">
                    <label>Archive Type</label>
                    <select name="archive_type" id="archive_type">
                        <option value="already">Manually Archived Data Source</option>
                        <option value="auto">Automatically Archive Based on URL - Not Implemented</option>
                    </select>
                    <span class="text-danger"><?php if (isset($archive_type_error)) echo $archive_type_error; ?></span>
                </div>
                <?php echo  get_source_dropdown_html(); ?>
                <?php echo  get_shared_inputs(); ?>
                <input type="submit" class="btn btn-primary" name="add" value="submit">
            </form>
        </div>
        <div class="card">
            <div class="card-body">
                <h2>Update Archived Data Source</h2>
            </div>
            <p>Please fill all fields in the form</p>
            <span class="text-danger"><?php if (isset($update_error_message)) echo $update_error_message; ?></span>
            <span class="text-success"><?php if (isset($update_success_message)) echo $update_success_message; ?></span>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <?php echo  get_dropdown_html('Data Source to Update', $archived_source_dropdown); ?>
                <?php echo  get_shared_inputs(); ?>
                <input type="submit" class="btn btn-primary" name="update" value="submit">
            </form>
        </div>
    </div>
</div>
</body>
</html>