<?php
try{
    session_start();
    include "./../php_app/plant_operations.php";
    include "./../php_app/source_operations.php";
    if(isset($_SESSION['user_id']) =="") {
        header("Location: login.php");
    }
    $html_table = get_plant_species_table();
    $source_dropdown = get_sources_dropdown();
    $species_dropdown = get_plant_species_dropdown($_SESSION['user_type']);

    if (isset($_POST['add'])) {
        $plant_species_name = $_POST['plant_species_name'];
        $source_id = (int) $_POST['source_id'];
        $growth_zone = $_POST['growth_zone'];
        $water_requirement = $_POST['water_requirement'];
        $sun_requirement = $_POST['sun_requirement'];
        $soil_requirement = $_POST['soil_requirement'];
        if (isset($_POST['perennial']) && isset($_POST['annual'])){
            $perennial_error = 'Cannot select both Perennial and Annual for the same species.';
        }else {
            if (isset($_POST['perennial'])) {
                $perennial = 1;
            }else {
                if(isset($_POST['annual'])){
                    $perennial = -1;
                }else{
                    $perennial = 0;
                }
            }
        }
        $height = (int) $_POST['height'];
        $height_units = $_POST['height_units'];
        if (isset($_POST['edible']) && isset($_POST['edible_no'])){
            $edible_error = 'Cannot select both Edible and Not Edible for the same species.';
        }else {
            if (isset($_POST['edible'])) {
                $edible = 1;
            }else {
                if(isset($_POST['edible_no'])){
                    $edible = -1;
                }else{
                    $edible = 0;
                }
            }
        }
        $harvest_days = (int) $_POST['harvest_days'];

        if (!isset($perennial_error) && !isset($edible_error)){
            $result = add_plant_species($_SESSION['user_id'], $plant_species_name, $source_id, $growth_zone,
                $water_requirement, $sun_requirement, $soil_requirement, $perennial, $height, $height_units,
                $edible, $harvest_days);

            if ($result['success']) {
                $add_success_message = 'Plant Species record added '.$plant_species_name;
                $html_table = get_plant_species_table();
                $species_dropdown = get_plant_species_dropdown($_SESSION['user_type']);
            } else {
                unset($add_success_message);
                $add_error_message = $result['error'];
            }
        }
    }

    if (isset($_POST['update'])) {
        $species_id = (int) $_POST['plant_species_id'];
        $growth_zone = $_POST['growth_zone'];
        $water_requirement = $_POST['water_requirement'];
        $sun_requirement = $_POST['sun_requirement'];
        $soil_requirement = $_POST['soil_requirement'];
        if (isset($_POST['perennial']) && isset($_POST['annual'])){
            $perennial_error = 'Cannot select both Perennial and Annual for the same species.';
        }else {
            if (isset($_POST['perennial'])) {
                $perennial = 1;
            }else {
                if(isset($_POST['annual'])){
                    $perennial = -1;
                }else{
                    $perennial = 0;
                }
            }
        }
        $height = (int) $_POST['height'];
        $height_units = $_POST['height_units'];
        if (isset($_POST['edible']) && isset($_POST['edible_no'])){
            $edible_error = 'Cannot select both Edible and Not Edible for the same species.';
        }else {
            if (isset($_POST['edible'])) {
                $edible = 1;
            }else {
                if(isset($_POST['edible_no'])){
                    $edible = -1;
                }else{
                    $edible = 0;
                }
            }
        }
        $harvest_days = (int) $_POST['harvest_days'];

        if (!isset($perennial_error) && !isset($edible_error)){
            $result = update_plant_species($species_id, $growth_zone, $water_requirement, $sun_requirement,
                $soil_requirement, $perennial, $height, $height_units, $edible, $harvest_days);

            if ($result['success']) {
                $update_success_message = 'Plant Species record added '.$plant_species_name;
                $html_table = get_plant_species_table();
                $species_dropdown = get_plant_species_dropdown($_SESSION['user_type']);
            } else {
                unset($update_success_message);
                $update_error_message = $result['error'];
            }
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
    <title>Add plant Species</title>
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<ul>
    <p class="text">Username : <?php echo $_SESSION['user_name']?></p>
    <p class="text">Type : <?php echo $_SESSION['user_type']?></p>
    <li> <a href="logout.php">Logout</a> </li>
    <li> <a href="dashboard.php">Home</a> </li>
    <li> <a href="source_table.php">Data Source</a> </li>
    <li> <a href="source_archive_table.php">Archived Data Source</a> </li>
    <li> <a href="animal_species_table.php">Animal Species</a> </li>
    <li> <a href="animal_breed_table.php">Animal Breed</a> </li>
    <li> <a class="active" href="plant_species_table.php">Plant Species</a> </li>
    <li> <a href="animal_food_plants_table.php">Animal Food: Plants</a> </li>
</ul>
<div style="margin-left:25%;padding:1px 16px;height:1000px;">
    <span class="text-danger"><?php if (isset($error_message)) echo $error_message; ?></span>
    <span class="text-success"><?php if (isset($success_message)) echo $success_message; ?></span>
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="col-lg-9">
                    <div class="page-header">
                        <h2>Existing Plant Species by Source</h2>
                    </div>
                    <span class="table-responsive"> <?php echo $html_table; ?> </span>
                </div>
            </div>
        </div>
    </div>
    <div class="card-group">
        <div class="card">
            <div class="card-body">
                <h2>Add Plant Species by Source</h2>
            </div>
            <p>Please fill all applicable fields in the form</p>
            <span class="text-danger"><?php if (isset($add_error_message)) echo $add_error_message; ?></span>
            <span class="text-success"><?php if (isset($add_success_message)) echo $add_success_message; ?></span>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label>Plant Species Name</label>
                    <input type="text" name="plant_species_name" class="form-control" value="" maxlength="250" required="">
                    <span class="text-danger"><?php if (isset($plant_species_name_error)) echo $plant_species_name_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Data Source</label>
                    <span class="custom-select"><?php echo $source_dropdown ?></span>
                    <span class="text-danger"><?php if (isset($data_source_error)) echo $data_source_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Primary Growth Zone</label><br>
                    <select name="growth_zone" id="growth_zone">
                        <option value="1A"> 1A </option>
                        <option value="1B"> 1B </option>
                        <option value="2A"> 2A </option>
                        <option value="2B"> 2B </option>
                        <option value="3A"> 3A </option>
                        <option value="3B"> 3B </option>
                        <option value="4A"> 4A </option>
                        <option value="4B"> 4B </option>
                        <option value="5A"> 5A </option>
                        <option value="5B"> 5B </option>
                        <option value="6A"> 6A </option>
                        <option value="6B"> 6B </option>
                        <option value="7A"> 7A </option>
                        <option value="7B"> 7B </option>
                        <option value="8A"> 8A </option>
                        <option value="8B"> 8B </option>
                        <option value="9A"> 9A </option>
                        <option value="9B"> 9B </option>
                        <option value="10A"> 10A </option>
                        <option value="10B"> 10B </option>
                        <option value="11A"> 11A </option>
                        <option value="11B"> 11B </option>
                        <option value="12A"> 12A </option>
                        <option value="12B"> 12B </option>
                        <option value="13A"> 13A </option>
                        <option value="13B"> 13B </option>
                        <option value="Most"> Most </option>
                    </select>
                    <span class="text-danger"><?php if (isset($growth_zone_error)) echo $growth_zone_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Water Requirements</label>
                    <input type="text" name="water_requirement" class="form-control" value="" maxlength="250" required="">
                    <span class="text-danger"><?php if (isset($water_requirement_error)) echo $water_requirement_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Sun Requirements</label>
                    <input type="text" name="sun_requirement" class="form-control" value="" maxlength="250" required="">
                    <span class="text-danger"><?php if (isset($sun_requirement_error)) echo $sun_requirement_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Soil Requirements</label>
                    <input type="text" name="soil_requirement" class="form-control" value="" maxlength="250" required="">
                    <span class="text-danger"><?php if (isset($soil_requirement_error)) echo $soil_requirement_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Perennial</label><br>
                    <input type="checkbox" id="perennial" name="perennial" value="perennial">
                    <label for="perennial"> Perennial </label>
                    <input type="checkbox" id="annual" name="annual" value="annual">
                    <label for="annual"> Annual </label><br>
                    <span class="text-danger"><?php if (isset($perennial_error)) echo $perennial_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Average Height</label>
                    <input type="number" name="height" class="form-control" value="" min="0" max="99999">
                    <span class="text-danger"><?php if (isset($height_error)) echo $height_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Height Units</label><br>
                    <select name="height_units" id="height_units">
                        <option value="inch">Inch (in)</option>
                        <option value="foot">Foot (ft)</option>
                    </select>
                    <span class="text-danger"><?php if (isset($height_unit_error)) echo $height_unit_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Edible For Humans</label><br>
                    <input type="checkbox" id="edible" name="edible" value="edible">
                    <label for="edible"> Edible </label>
                    <input type="checkbox" id="edible_no" name="edible_no" value="edible_no">
                    <label for="edible_no"> Not Edible </label><br>
                    <span class="text-danger"><?php if (isset($edible_error)) echo $edible_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Days to Harvest</label>
                    <input type="number" name="harvest_days" class="form-control" value="" min="0" max="99999">
                    <span class="text-danger"><?php if (isset($harvest_days_error)) echo $harvest_days_error; ?></span>
                </div>
                <input type="submit" class="btn btn-primary" name="add" value="submit">
            </form>
        </div>
        <div class="card">
            <div class="card-body">
                <h2>Update Plant Species</h2>
            </div>
            <p>Please fill all applicable fields in the form</p>
            <span class="text-danger"><?php if (isset($update_error_message)) echo $update_error_message; ?></span>
            <span class="text-success"><?php if (isset($update_success_message)) echo $update_success_message; ?></span>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label>Species to Update</label>
                    <span class="custom-select"><?php echo $species_dropdown ?></span>
                    <span class="text-danger"><?php if (isset($species_error)) echo $species_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Primary Growth Zone</label><br>
                    <select name="growth_zone" id="growth_zone">
                        <option value="1A"> 1A </option>
                        <option value="1B"> 1B </option>
                        <option value="2A"> 2A </option>
                        <option value="2B"> 2B </option>
                        <option value="3A"> 3A </option>
                        <option value="3B"> 3B </option>
                        <option value="4A"> 4A </option>
                        <option value="4B"> 4B </option>
                        <option value="5A"> 5A </option>
                        <option value="5B"> 5B </option>
                        <option value="6A"> 6A </option>
                        <option value="6B"> 6B </option>
                        <option value="7A"> 7A </option>
                        <option value="7B"> 7B </option>
                        <option value="8A"> 8A </option>
                        <option value="8B"> 8B </option>
                        <option value="9A"> 9A </option>
                        <option value="9B"> 9B </option>
                        <option value="10A"> 10A </option>
                        <option value="10B"> 10B </option>
                        <option value="11A"> 11A </option>
                        <option value="11B"> 11B </option>
                        <option value="12A"> 12A </option>
                        <option value="12B"> 12B </option>
                        <option value="13A"> 13A </option>
                        <option value="13B"> 13B </option>
                        <option value="Most"> Most </option>
                    </select>
                    <span class="text-danger"><?php if (isset($growth_zone_error)) echo $growth_zone_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Water Requirements</label>
                    <input type="text" name="water_requirement" class="form-control" value="" maxlength="250" required="">
                    <span class="text-danger"><?php if (isset($water_requirement_error)) echo $water_requirement_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Sun Requirements</label>
                    <input type="text" name="sun_requirement" class="form-control" value="" maxlength="250" required="">
                    <span class="text-danger"><?php if (isset($sun_requirement_error)) echo $sun_requirement_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Soil Requirements</label>
                    <input type="text" name="soil_requirement" class="form-control" value="" maxlength="250" required="">
                    <span class="text-danger"><?php if (isset($soil_requirement_error)) echo $soil_requirement_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Perennial</label><br>
                    <input type="checkbox" id="perennial" name="perennial" value="perennial">
                    <label for="perennial"> Perennial </label>
                    <input type="checkbox" id="annual" name="annual" value="annual">
                    <label for="annual"> Annual </label><br>
                    <span class="text-danger"><?php if (isset($perennial_error)) echo $perennial_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Average Height</label>
                    <input type="number" name="height" class="form-control" value="" min="0" max="99999">
                    <span class="text-danger"><?php if (isset($height_error)) echo $height_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Height Units</label><br>
                    <select name="height_units" id="height_units">
                        <option value="inch">Inch (in)</option>
                        <option value="foot">Foot (ft)</option>
                    </select>
                    <span class="text-danger"><?php if (isset($height_unit_error)) echo $height_unit_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Edible For Humans</label><br>
                    <input type="checkbox" id="edible" name="edible" value="edible">
                    <label for="edible"> Edible </label>
                    <input type="checkbox" id="edible_no" name="edible_no" value="edible_no">
                    <label for="edible_no"> Not Edible </label><br>
                    <span class="text-danger"><?php if (isset($edible_error)) echo $edible_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Days to Harvest</label>
                    <input type="number" name="harvest_days" class="form-control" value="" min="0" max="99999">
                    <span class="text-danger"><?php if (isset($harvest_days_error)) echo $harvest_days_error; ?></span>
                </div>
                <input type="submit" class="btn btn-primary" name="update" value="submit">
            </form>
        </div>
    </div>
</div>
</body>
</html>