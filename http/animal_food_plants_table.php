<?php

try{
    session_start();
    include "./../php_app/animal_operations.php";
    include "./../php_app/plant_operations.php";
    include "./../php_app/source_operations.php";
    if(isset($_SESSION['user_id']) =="") {
        header("Location: login.php");
    }
    $html_table = get_animal_food_plants_table();
    $source_dropdown = get_sources_dropdown();
    $animal_dropdown = get_animal_species_dropdown();
    $plant_dropdown = get_plant_species_dropdown();
    $food_dropdown = get_animal_food_plants_dropdown($_SESSION['user_type']);

    if (isset($_POST['add'])) {
        $animal_species_id = (int) $_POST['species_id'];
        $plant_species_id = (int) $_POST['plant_species_id'];
        $source_id = (int) $_POST['source_id'];
        if (isset($_POST['medical'])) {
            $medical = 1;
        }else {
            $medical = 0;
        }
        if (isset($_POST['limit_access']) && isset($_POST['free_feed'])){
            $access_error = 'Cannot select both Limit Access and Free Feed for the same species.';
        }else {
            if (isset($_POST['limit_access'])) {
                $limit_access = 1;
            }else {
                $limit_access = 0;
            }
            if (isset($_POST['free_feed'])) {
                $free_feed = 1;
            }else {
                $free_feed = 0;
            }
        }
        if (isset($_POST['teething'])) {
            $teething = 1;
        }else {
            $teething = 0;
        }
        if (isset($_POST['grit'])) {
            $grit = 1;
        }else {
            $grit = 0;
        }
        $notes = $_POST['notes'];
        if (!isset($access_error)) {
            $result = add_animal_food_plants($_SESSION['user_id'], $source_id, $animal_species_id, $plant_species_id, $medical, $limit_access,
                $free_feed, $teething, $grit, $notes);
            if ($result['success']) {
                $add_success_message = 'Data source added for Address/ISBN ' . $address_isbn;

                $html_table = get_animal_food_plants_table();
                $animal_dropdown = get_animal_species_dropdown();
                $plant_dropdown = get_plant_species_dropdown();
                $food_dropdown = get_animal_food_plants_dropdown($_SESSION['user_type']);
            } else {
                unset($add_success_message);
                $add_error_message = $result['error'];
            }
        }
    }

    if (isset($_POST['update'])) {
        $animal_food_plants_id = (int)$_POST['animal_food_plants_id'];
        if (isset($_POST['medical'])) {
            $medical = 1;
        }else {
            $medical = 0;
        }
        if (isset($_POST['limit_access']) && isset($_POST['free_feed'])){
            $access_error = 'Cannot select both Limit Access and Free Feed for the same species.';
        }else {
            if (isset($_POST['limit_access'])) {
                $limit_access = 1;
            }else {
                $limit_access = 0;
            }
            if (isset($_POST['free_feed'])) {
                $free_feed = 1;
            }else {
                $free_feed = 0;
            }
        }
        if (isset($_POST['teething'])) {
            $teething = 1;
        }else {
            $teething = 0;
        }
        if (isset($_POST['grit'])) {
            $grit = 1;
        }else {
            $grit = 0;
        }
        $notes = $_POST['notes'];
        if (!isset($access_error)) {
            $result = update_animal_food_plants($animal_food_plants_id, $medical, $limit_access,
                $free_feed, $teething, $grit, $notes);
            if ($result['success']) {
                $update_success_message = 'Data source updated for Address/ISBN ' . $address_isbn;

                $html_table = get_animal_food_plants_table();
                $animal_dropdown = get_animal_species_dropdown();
                $plant_dropdown = get_plant_species_dropdown();
                $food_dropdown = get_animal_food_plants_dropdown($_SESSION['user_type']);
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
    <title>Add Animal Food: Plants</title>
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
    <li> <a href="plant_species_table.php">Plant Species</a> </li>
    <li> <a class="active" href="animal_food_plants_table.php">Animal Food: Plants</a> </li>
</ul>
<div style="margin-left:25%;padding:1px 16px;height:1000px;">
    <span class="text-danger"><?php if (isset($error_message)) echo $error_message; ?></span>
    <span class="text-success"><?php if (isset($success_message)) echo $success_message; ?></span>
    <div class="row">
        <div class="card">
            <div class="card-body">
                    <div class="page-header">
                        <h2>Existing Animal Food: Plants</h2>
                    </div>
                    <span class="table-responsive"> <?php echo $html_table; ?> </span>
            </div>
        </div>
    </div>
    <div class="card-group">
        <div class="card">
            <div class="card-body">
                    <div class="page-header">
                        <h2>Add a Animal Food: Plants</h2>
                    </div>
                    <p>Please fill all fields in the form</p>
                    <span class="text-danger"><?php if (isset($add_error_message)) echo $add_error_message; ?></span>
                    <span class="text-success"><?php if (isset($add_success_message)) echo $add_success_message; ?></span>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Animal Species</label>
                            <span class="custom-select"><?php echo $animal_dropdown ?></span>
                            <span class="text-danger"><?php if (isset($animal_error)) echo $animal_error; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Plant Species</label>
                            <span class="custom-select"><?php echo $plant_dropdown ?></span>
                            <span class="text-danger"><?php if (isset($plant_error)) echo $plant_error; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Data Source</label>
                            <span class="custom-select"><?php echo $source_dropdown ?></span>
                            <span class="text-danger"><?php if (isset($data_source_error)) echo $data_source_error; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Uses</label><br>
                            <input type="checkbox" id="medical" name="medical" value="medical">
                            <label for="medical"> Medical </label>
                            <input type="checkbox" id="limit_access" name="limit_access" value="limit_access">
                            <label for="limit_access"> Limit Access </label><br>
                            <input type="checkbox" id="free_feed" name="free_feed" value="free_feed">
                            <label for="free_feed"> Free Feed </label><br>
                            <input type="checkbox" id="teething" name="teething" value="teething">
                            <label for="teething"> Teething </label><br>
                            <input type="checkbox" id="grit" name="grit" value="grit">
                            <label for="grit"> Grit </label><br>
                            <span class="text-danger"><?php if (isset($access_error)) echo $access_error; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Notes</label>
                            <input type="text" name="notes" class="form-control" value="" maxlength="250" required="">
                            <span class="text-danger"><?php if (isset($notes_error)) echo $notes_error; ?></span>
                        </div>
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
                        <label>Animal Food: Plant to Update</label>
                        <span class="custom-select"><?php echo $food_dropdown ?></span>
                        <span class="text-danger"><?php if (isset($food_error)) echo $food_error; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Uses</label><br>
                        <input type="checkbox" id="medical" name="medical" value="medical">
                        <label for="medical"> Medical </label>
                        <input type="checkbox" id="limit_access" name="limit_access" value="limit_access">
                        <label for="limit_access"> Limit Access </label><br>
                        <input type="checkbox" id="free_feed" name="free_feed" value="free_feed">
                        <label for="free_feed"> Free Feed </label><br>
                        <input type="checkbox" id="teething" name="teething" value="teething">
                        <label for="teething"> Teething </label><br>
                        <input type="checkbox" id="grit" name="grit" value="grit">
                        <label for="grit"> Grit </label><br>
                        <span class="text-danger"><?php if (isset($access_error)) echo $access_error; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Notes</label>
                        <input type="text" name="notes" class="form-control" value="" maxlength="250" required="">
                        <span class="text-danger"><?php if (isset($notes_error)) echo $notes_error; ?></span>
                    </div>
                    <input type="submit" class="btn btn-primary" name="update" value="submit">
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>