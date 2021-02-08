<?php
try{
    session_start();
    include "./../php_app/source_operations.php";
    if(isset($_SESSION['user_id']) =="") {
        header("Location: login.php");
    }
    $html_table = get_sources_table();
    if (isset($_POST['add'])) {
        $source_type = $_POST['source_type'];
        $address_isbn = $_POST['address_isbn'];
        $title = $_POST['title'];

        $result = add_source($source_type, $address_isbn, $title, $_SESSION['user_id']);

        if ($result['success']) {
            $success_message = 'Data source added for Address/ISBN '.$address_isbn;
            $html_table = get_sources_table();
        } else {
            unset($success_message);
            $error_message = $result['error'];
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
    <title>Add Data Source</title>
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="card-header-tabs">
            <div class="card-body">
                <p> <a href="dashboard.php">Dashboard</a> </p>
                <p> <a href="source_table.php">Add Data Source</a> </p>
                <p> <a href="source_archive_table.php">Add Archived Data Source</a> </p>
                <p> <a href="animal_species_table.php">Add Animal Species</a> </p>
                <p> <a href="animal_breed_table.php">Add Animal Breed</a> </p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="col-lg-9">
                    <div class="page-header">
                        <h2>Existing Data Sources</h2>
                    </div>
                    <span class="table-responsive"> <?php echo $html_table; ?> </span>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-lg-10">
                    <div class="page-header">
                        <h2>Add a Data Source</h2>
                    </div>
                    <p>Please fill all fields in the form</p>
                    <span class="text-danger"><?php if (isset($error_message)) echo $error_message; ?></span>
                    <span class="text-success"><?php if (isset($success_message)) echo $success_message; ?></span>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group ">
                            <label>Source Type</label>
                            <select name="source_type" id="source_type">
                                <option value="book">Book</option>
                                <option value="webpage">Webpage</option>
                            </select>
                            <span class="text-danger"><?php if (isset($source_type_error)) echo $source_type_error; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Web Address or ISBN</label>
                            <input type="text" name="address_isbn" class="form-control" value="" maxlength="250" required="">
                            <span class="text-danger"><?php if (isset($address_isbn_error)) echo $address_isbn_error; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control" value="" maxlength="250" required="">
                            <span class="text-danger"><?php if (isset($title_error)) echo $title_error; ?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" name="add" value="submit">

                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="card-footer">
            <div class="card-body">
                <h5 class="card-title">Account </h5>
                <p class="card-text">Account Name : <?php echo $_SESSION['user_type']?></p>
                <p class="card-text">Account Type : <?php echo $_SESSION['user_type']?></p>
                <p> <a href="logout.php">Logout</a> </p>
            </div>
        </div>
    </div>
</div>
</body>
</html>