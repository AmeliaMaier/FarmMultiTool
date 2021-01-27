<?php
try{
    session_start();
    include "./../php_app/source_operations.php";
    if(isset($_SESSION['user_id']) =="") {
        header("Location: login.php");
    }
    $source_dropdown = get_sources_dropdown();
    if (isset($_POST['add'])) {
        $source_type = $_POST['source_type'];
        $address_isbn = $_POST['address_isbn'];
        $title = $_POST['title'];

        $result = add_source($source_type, $address_isbn, $title, $_SESSION['user_id']);

        if ($result['success']) {
            $success_message = 'Data source added for Address/ISBN '.$address_isbn;
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
    <title>Add Archived Data Source</title>
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Name : <?php echo $_SESSION['user_name']?></h5>
                    <p class="card-text">Account_Type : <?php echo $_SESSION['user_type']?></p>
                    <p> <a href="logout.php">Logout</a> </p>
                    <p> <a href="dashboard.php">Dashboard</a> </p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="card">
            <div class="card-body">
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
                <h2>Add Archived Data Source</h2>
            </div>
            <p>Please fill all fields in the form</p>
            <span class="text-danger"><?php if (isset($error_message)) echo $error_message; ?></span>
            <span class="text-success"><?php if (isset($success_message)) echo $success_message; ?></span>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group ">
                    <label>Archive Type</label>
                    <<select name="archive_type" id="archive_type">
                        <option value="auto">Automatically Archive Based on URL</option>
                        <option value="already">Manually Archived Data Source</option>
                    </select>>
                    <span class="text-danger"><?php if (isset($archive_type_error)) echo $archive_type_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Data Source</label>
                    <span class="custom-select"><?php echo $source_dropdown ?></span>
                    <span class="text-danger"><?php if (isset($data_source_error)) echo $data_source_error; ?></span>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>