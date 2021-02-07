<?php
try{
    session_start();
    include "./../php_app/animal_operations.php";
    include "./../php_app/source_operations.php";
    if(isset($_SESSION['user_id']) =="") {
        header("Location: login.php");
    }
    $html_table = get_animal_species_table();
    $source_dropdown = get_sources_dropdown();

    if (isset($_POST['add'])) {
        $archive_type = $_POST['archive_type'];
        $source_id = (int) $_POST['source_id'];
        $sftp_folder_id = (int) $_POST['sftp_folder_id'];
        $sftp_file_name = $_POST['sftp_file_name'];
        $sftp_share_url = $_POST['sftp_share_url'];

        $result = add_source_archive($archive_type, $source_id, $sftp_folder_id, $sftp_file_name, $sftp_share_url, $_SESSION['user_id']);

        if ($result['success']) {
            $success_message = 'Data source archive record added'.$source_id;
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
    <title>Add Animal Species</title>
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <span class="text-danger"><?php if (isset($error_message)) echo $error_message; ?></span>
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
                <div class="col-lg-9">
                    <div class="page-header">
                        <h2>Existing Animal Species by Source</h2>
                    </div>
                    <span class="table-responsive"> <?php echo $html_table; ?> </span>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h2>Add Animal Species by Source</h2>
            </div>
            <p>Please fill all applicable fields in the form</p>
            <span class="text-danger"><?php if (isset($error_message)) echo $error_message; ?></span>
            <span class="text-success"><?php if (isset($success_message)) echo $success_message; ?></span>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label>Animal Species Name</label>
                    <input type="text" name="animal_species_name" class="form-control" value="" maxlength="250" required="">
                    <span class="text-danger"><?php if (isset($animal_species_name_error)) echo $animal_species_name_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Data Source</label>
                    <span class="custom-select"><?php echo $source_dropdown ?></span>
                    <span class="text-danger"><?php if (isset($data_source_error)) echo $data_source_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Difficulty Level</label>
                    <select name="source_type" id="source_type">
                        <option value="beginner">Beginner</option>
                        <option value="intermediate">Intermediate</option>
                        <option value="expert">Expert</option>
                    </select>
                    <span class="text-danger"><?php if (isset($difficulty_level_error)) echo $difficulty_level_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Housing Type</label><br>
                    <input type="checkbox" id="housing_cage" name="housing_cage" value="cage">
                        <label for="housing_cage"> Cage Happy </label>
                    <input type="checkbox" id="housing_cage_no" name="housing_cage_no" value="cage_no">
                        <label for="housing_cage_no"> Cage Unhappy </label><br>
                    <input type="checkbox" id="housing_pasture" name="housing_pasture" value="pasture">
                        <label for="housing_pasture"> Pasture Happy </label>
                    <input type="checkbox" id="housing_pasture_no" name="housing_pasture_no" value="pasture_no">
                        <label for="housing_pasture_no"> Pasture Unhappy </label><br>
                    <span class="text-danger"><?php if (isset($housing_type_error)) echo $housing_type_error; ?></span>
                </div>

                <input type="submit" class="btn btn-primary" name="add" value="submit">
            </form>
        </div>
    </div>
</div>
</body>
</html>