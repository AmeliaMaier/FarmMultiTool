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
                    <label>Difficulty Level</label><br>
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
                <div class="form-group">
                    <label>Food Type</label><br>
                    <input type="checkbox" id="food_meat" name="food_meat" value="food_meat">
                    <label for="food_meat"> Eats Meat </label>
                    <input type="checkbox" id="food_meat_no" name="food_meat_no" value="food_meat_no">
                    <label for="food_meat_no"> Can't Eat Meat </label><br>
                    <input type="checkbox" id="food_bug" name="food_bug" value="food_bug">
                    <label for="food_bug"> Eats Bugs </label>
                    <input type="checkbox" id="food_bug_no" name="food_bug_no" value="food_bug_no">
                    <label for="food_bug_no"> Can't Eat Bugs </label><br>
                    <input type="checkbox" id="food_plant" name="food_plant" value="food_plant">
                    <label for="food_plant"> Eats Plants </label>
                    <input type="checkbox" id="food_plant_no" name="food_plant_no" value="food_plant_no">
                    <label for="food_plant_no"> Can't Eat Plants </label><br>
                    <span class="text-danger"><?php if (isset($housing_type_error)) echo $housing_type_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Source Of:</label><br>
                    <input type="checkbox" id="source_meat" name="source_meat" value="source_meat">
                    <label for="source_meat"> Raised for Meat </label>
                    <input type="checkbox" id="source_meat_no" name="source_meat_no" value="source_meat_no">
                    <label for="source_meat_no"> Not Raised for Meat </label><br>
                    <input type="checkbox" id="source_egg" name="source_egg" value="source_egg">
                    <label for="source_egg"> Raised for Eggs </label>
                    <input type="checkbox" id="source_egg_no" name="source_egg_no" value="source_egg_no">
                    <label for="source_egg_no"> Not Raised for Eggs </label><br>
                    <input type="checkbox" id="source_milk" name="source_milk" value="source_milk">
                    <label for="source_milk"> Raised for Milk </label>
                    <input type="checkbox" id="source_milk_no" name="source_milk_no" value="source_milk_no">
                    <label for="source_milk_no"> Not Raised for Milk </label><br>
                    <input type="checkbox" id="source_fiber" name="source_fiber" value="source_fiber">
                    <label for="source_fiber"> Raised for Fiber </label>
                    <input type="checkbox" id="source_fiber_no" name="source_fiber_no" value="source_fiber_no">
                    <label for="source_fiber_no"> Not Raised for Fiber </label><br>
                    <span class="text-danger"><?php if (isset($source_of_error)) echo $source_of_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Gestation Days</label>
                    <input type="number" name="gestation_days" class="form-control" value="" min="1" max="730">
                    <span class="text-danger"><?php if (isset($gestation_days_error)) echo $gestation_days_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Minimum Healthy Environment Tempature</label>
                    <input type="number" name="min_temp" class="form-control" value="" min="-100" max="150">
                    <span class="text-danger"><?php if (isset($min_temp_error)) echo $min_temp_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Maximum Healthy Environment Tempature</label>
                    <input type="number" name="max_temp" class="form-control" value="" min="-100" max="150">
                    <span class="text-danger"><?php if (isset($max_temp_error)) echo $max_temp_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Vaccines</label><br>
                    <input type="checkbox" id="vaccines" name="vaccines" value="vaccines">
                    <label for="vaccines"> Has Vaccines </label>
                    <input type="checkbox" id="vaccines_no" name="vaccines_no" value="vaccines_no">
                    <label for="vaccines_no"> Does Not Have Vaccines </label><br>
                    <span class="text-danger"><?php if (isset($vaccines_error)) echo $vaccines_error; ?></span>
                </div>
                <input type="submit" class="btn btn-primary" name="add" value="submit">
            </form>
        </div>
    </div>
</div>
</body>
</html>