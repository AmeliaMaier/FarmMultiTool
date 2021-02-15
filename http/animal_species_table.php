<?php
try{
    session_start();
    include "./../php_app/animal_operations.php";
    include "./../php_app/source_operations.php";
    include "./../php_app/shared_html.php";
    if(isset($_SESSION['user_id']) =="") {
        header("Location: login.php");
    }
    $html_table = get_animal_species_table();
    $source_dropdown = get_sources_dropdown();
    $species_dropdown = get_animal_species_dropdown($_SESSION['user_type']);
    $nav_bar = get_navbar($_SESSION['user_name'], $_SESSION['user_type'], 'animal_species_table');

    if (isset($_POST['add'])) {
        $animal_species_name = $_POST['animal_species_name'];
        $source_id = (int) $_POST['source_id'];
        if($_POST['difficulty_level']=='null'){
            $difficulty_level = null;
        }else {
            $difficulty_level = $_POST['difficulty_level'];
        }
        if (isset($_POST['housing_cage']) && isset($_POST['housing_cage_no'])){
            $housing_type_error = 'Cannot select both Cage Happy and Cage Unhappy for the same species.';
        }else {
            if (isset($_POST['housing_cage'])) {
                $cage_happy = 1;
            }else {
                if(isset($_POST['housing_cage_no'])){
                    $cage_happy = -1;
                }else{
                    $cage_happy = 0;
                }
            }
        }
        if (isset($_POST['housing_pasture']) && ($_POST['housing_pasture_no'])){
            if(isset($housing_type_error)){
                $housing_type_error .= ' Cannot select both Pasture Happy and Pasture Unhappy for the same species.';
            }else {
                $housing_type_error = 'Cannot select both Pasture Happy and Pasture Unhappy for the same species.';
            }
        }else {
            if (isset($_POST['housing_pasture'])) {
                $pasture_happy = 1;
            }else {
                if(isset($_POST['housing_pasture_no'])){
                    $pasture_happy = -1;
                }else{
                    $pasture_happy = 0;
                }
            }
        }
        if (isset($_POST['food_meat']) && isset($_POST['food_meat_no'])){
            $food_type_error = 'Cannot select both Eats Meat and Can\'t Eat Meat for the same species.';
        }else {
            if (isset($_POST['food_meat'])) {
                $food_meat = 1;
            }else {
                if(isset($_POST['food_meat_no'])){
                    $food_meat = -1;
                }else{
                    $food_meat = 0;
                }
            }
        }
        if (isset($_POST['food_bug']) && isset($_POST['food_bug_no'])){
            if(isset($food_type_error)){
                $food_type_error .= ' Cannot select both Eats Bugs and Can\'t Eat Bugs for the same species.';
            }else {
                $food_type_error = 'Cannot select both Eats Bugs and Can\'t Eat Bugs for the same species.';
            }
        }else {
            if(isset($_POST['food_bug'])) {
                $food_bug = 1;
            }else {
                if(isset($_POST['food_bug_no'])){
                    $food_bug = -1;
                }else{
                    $food_bug = 0;
                }
            }
        }
        if (isset($_POST['food_plant']) && isset($_POST['food_plant_no'])){
            if(isset($food_type_error)){
                $food_type_error .= ' Cannot select both Eats Plants and Can\'t Eat Plants for the same species.';
            }else {
                $food_type_error = 'Cannot select both Eats Plants and Can\'t Eat Plants for the same species.';
            }
        }else {
            if (isset($_POST['food_plant'])) {
                $food_plant = 1;
            }else {
                if(isset($_POST['food_plant_no'])){
                    $food_plant = -1;
                }else{
                    $food_plant = 0;
                }
            }
        }
        $daily_feed = (float) $_POST['daily_food_amount'];
        if($_POST['daily_food_unit'] == 'null'){
            $daily_feed_unit = null;
        } else {
            $daily_feed_unit = $_POST['daily_food_unit'];
        }
        if($_POST['daily_food_per_unit'] == 'null'){
            $daily_feed_unit_per = null;
        } else {
            $daily_feed_unit_per = $_POST['daily_food_per_unit'];
        }
        if (isset($_POST['source_meat']) && isset($_POST['source_meat_no'])){
            $source_of_error = 'Cannot select both Raised for Meat and Not Raised for Meat for the same species.';
        }else {
            if (isset($_POST['source_meat'])) {
                $source_meat = 1;
            }else {
                if(isset($_POST['source_meat_no'])){
                    $source_meat = -1;
                }else{
                    $source_meat = 0;
                }
            }
        }
        if (isset($_POST['source_egg']) && isset($_POST['source_egg_no'])){
            if(isset($source_of_error)){
                $source_of_error .= ' Cannot select both Raised for Eggs and Not Raised for Eggs for the same species.';
            }else {
                $source_of_error = 'Cannot select both Raised for Eggs and Not Raised for Eggs for the same species.';
            }
        }else {
            if (isset($_POST['source_egg'])) {
                $source_egg = 1;
            }else {
                if(isset($_POST['source_egg_no'])){
                    $source_egg = -1;
                }else{
                    $source_egg = 0;
                }
            }
        }
        if (isset($_POST['source_milk']) && isset($_POST['source_milk_no'])){
            if(isset($source_of_error)){
                $source_of_error .= ' Cannot select both Raised for Milk and Not Raised for Milk for the same species.';
            }else {
                $source_of_error = 'Cannot select both Raised for Milk and Not Raised for Milk for the same species.';
            }
        }else {
            if (isset($_POST['source_milk'])) {
                $source_milk = 1;
            }else {
                if(isset($_POST['source_milk_no'])){
                    $source_milk = -1;
                }else{
                    $source_milk = 0;
                }
            }
        }
        if (isset($_POST['source_fiber']) && isset($_POST['source_fiber_no'])){
            if(isset($source_of_error)){
                $source_of_error .= ' Cannot select both Raised for Fiber and Not Raised for Fiber for the same species.';
            }else {
                $source_of_error = 'Cannot select both Raised for Fiber and Not Raised for Fiber for the same species.';
            }
        }else {
            if (isset($_POST['source_fiber'])) {
                $source_fiber = 1;
            }else {
                if(isset($_POST['source_fiber_no'])){
                    $source_fiber = -1;
                }else{
                    $source_fiber = 0;
                }
            }
        }
        if (isset($_POST['source_pelt']) && isset($_POST['source_pelt_no'])){
            if(isset($source_of_error)){
                $source_of_error .= ' Cannot select both Raised for Pelt and Not Raised for Pelt for the same species.';
            }else {
                $source_of_error = 'Cannot select both Raised for Pelt and Not Raised for Pelt for the same species.';
            }
        }else {
            if (isset($_POST['source_pelt'])) {
                $source_pelt = 1;
            }else {
                if(isset($_POST['source_pelt_no'])){
                    $source_pelt = -1;
                }else{
                    $source_pelt = 0;
                }
            }
        }

        if (isset($_POST['hot_fert']) && isset($_POST['cold_fert'])){
            if(isset($source_of_error)){
                $source_of_error .= ' Cannot select both Raised for Hot Fertilizer and Raised for Cold Fertilizer for the same species.';
            }else {
                $source_of_error = 'Cannot select both Raised for Hot Fertilizer and Raised for Cold Fertilizer for the same species.';
            }
        }else {
            if (isset($_POST['hot_fert'])) {
                $source_hot_fert = 1;
                $source_cold_fert = -1;
            }else {
                if(isset($_POST['cold_fert'])){
                    $source_hot_fert = -1;
                    $source_cold_fert = 1;
                }else{
                    $source_hot_fert = 0;
                    $source_cold_fert = 0;
                }
            }
        }
        $gestation_days = (int) $_POST['gestation_days'];
        $min_temp = (int) $_POST['min_temp'];
        $max_temp = (int) $_POST['max_temp'];
        if (isset($_POST['vaccines']) && isset($_POST['vaccines_no'])){
            $vaccines_error = 'Cannot select both Has Vaccines and Does Not Have Vaccies for the same species.';
        }else {
            if (isset($_POST['vaccines'])) {
                $vaccines = 1;
            }else {
                if(isset($_POST['vaccines_no'])){
                    $vaccines = -1;
                }else{
                    $vaccines = 0;
                }
            }
        }

        if (!isset($housing_type_error) && !isset($food_type_error) && !isset($source_of_error) && !isset($vaccines_error)){
            $result = add_animal_species($_SESSION['user_id'], $animal_species_name, $source_id, $difficulty_level,
                                            $cage_happy, $pasture_happy, $food_meat, $food_bug, $food_plant, $source_meat,
                                            $source_egg, $source_milk, $source_fiber, $gestation_days, $min_temp, $max_temp,
                                            $vaccines, $daily_feed, $daily_feed_unit, $daily_feed_unit_per, $source_pelt,
                                            $source_hot_fert, $source_cold_fert);

            if ($result['success']) {
                $add_success_message = 'Animal Species record added '.$animal_species_name;
                $html_table = get_animal_species_table();
                $species_dropdown = get_animal_species_dropdown($_SESSION['user_type']);
                $nav_bar = get_navbar($_SESSION['user_name'], $_SESSION['user_type'], 'animal_species_table');
            } else {
                unset($add_success_message);
                $add_error_message = $result['error'];
            }
        }
    }

    if (isset($_POST['update'])) {
        $species_id = (int) $_POST['species_id'];
        if($_POST['difficulty_level']=='null'){
            $difficulty_level = null;
        }else {
            $difficulty_level = $_POST['difficulty_level'];
        }
        if (isset($_POST['housing_cage']) && isset($_POST['housing_cage_no'])){
            $housing_type_error = 'Cannot select both Cage Happy and Cage Unhappy for the same species.';
        }else {
            if (isset($_POST['housing_cage'])) {
                $cage_happy = 1;
            }else {
                if(isset($_POST['housing_cage_no'])){
                    $cage_happy = -1;
                }else{
                    $cage_happy = 0;
                }
            }
        }
        if (isset($_POST['housing_pasture']) && ($_POST['housing_pasture_no'])){
            if(isset($housing_type_error)){
                $housing_type_error .= ' Cannot select both Pasture Happy and Pasture Unhappy for the same species.';
            }else {
                $housing_type_error = 'Cannot select both Pasture Happy and Pasture Unhappy for the same species.';
            }
        }else {
            if (isset($_POST['housing_pasture'])) {
                $pasture_happy = 1;
            }else {
                if(isset($_POST['housing_pasture_no'])){
                    $pasture_happy = -1;
                }else{
                    $pasture_happy = 0;
                }
            }
        }
        if (isset($_POST['food_meat']) && isset($_POST['food_meat_no'])){
            $food_type_error = 'Cannot select both Eats Meat and Can\'t Eat Meat for the same species.';
        }else {
            if (isset($_POST['food_meat'])) {
                $food_meat = 1;
            }else {
                if(isset($_POST['food_meat_no'])){
                    $food_meat = -1;
                }else{
                    $food_meat = 0;
                }
            }
        }
        if (isset($_POST['food_bug']) && isset($_POST['food_bug_no'])){
            if(isset($food_type_error)){
                $food_type_error .= ' Cannot select both Eats Bugs and Can\'t Eat Bugs for the same species.';
            }else {
                $food_type_error = 'Cannot select both Eats Bugs and Can\'t Eat Bugs for the same species.';
            }
        }else {
            if(isset($_POST['food_bug'])) {
                $food_bug = 1;
            }else {
                if(isset($_POST['food_bug_no'])){
                    $food_bug = -1;
                }else{
                    $food_bug = 0;
                }
            }
        }
        if (isset($_POST['food_plant']) && isset($_POST['food_plant_no'])){
            if(isset($food_type_error)){
                $food_type_error .= ' Cannot select both Eats Plants and Can\'t Eat Plants for the same species.';
            }else {
                $food_type_error = 'Cannot select both Eats Plants and Can\'t Eat Plants for the same species.';
            }
        }else {
            if (isset($_POST['food_plant'])) {
                $food_plant = 1;
            }else {
                if(isset($_POST['food_plant_no'])){
                    $food_plant = -1;
                }else{
                    $food_plant = 0;
                }
            }
        }
        $daily_feed = (float) $_POST['daily_food_amount'];
        if($_POST['daily_food_unit'] == 'null'){
            $daily_feed_unit = null;
        } else {
            $daily_feed_unit = $_POST['daily_food_unit'];
        }
        if($_POST['daily_food_per_unit'] == 'null'){
            $daily_feed_unit_per = null;
        } else {
            $daily_feed_unit_per = $_POST['daily_food_per_unit'];
        }
        if (isset($_POST['source_meat']) && isset($_POST['source_meat_no'])){
            $source_of_error = 'Cannot select both Raised for Meat and Not Raised for Meat for the same species.';
        }else {
            if (isset($_POST['source_meat'])) {
                $source_meat = 1;
            }else {
                if(isset($_POST['source_meat_no'])){
                    $source_meat = -1;
                }else{
                    $source_meat = 0;
                }
            }
        }
        if (isset($_POST['source_egg']) && isset($_POST['source_egg_no'])){
            if(isset($source_of_error)){
                $source_of_error .= ' Cannot select both Raised for Eggs and Not Raised for Eggs for the same species.';
            }else {
                $source_of_error = 'Cannot select both Raised for Eggs and Not Raised for Eggs for the same species.';
            }
        }else {
            if (isset($_POST['source_egg'])) {
                $source_egg = 1;
            }else {
                if(isset($_POST['source_egg_no'])){
                    $source_egg = -1;
                }else{
                    $source_egg = 0;
                }
            }
        }
        if (isset($_POST['source_milk']) && isset($_POST['source_milk_no'])){
            if(isset($source_of_error)){
                $source_of_error .= ' Cannot select both Raised for Milk and Not Raised for Milk for the same species.';
            }else {
                $source_of_error = 'Cannot select both Raised for Milk and Not Raised for Milk for the same species.';
            }
        }else {
            if (isset($_POST['source_milk'])) {
                $source_milk = 1;
            }else {
                if(isset($_POST['source_milk_no'])){
                    $source_milk = -1;
                }else{
                    $source_milk = 0;
                }
            }
        }
        if (isset($_POST['source_fiber']) && isset($_POST['source_fiber_no'])){
            if(isset($source_of_error)){
                $source_of_error .= ' Cannot select both Raised for Fiber and Not Raised for Fiber for the same species.';
            }else {
                $source_of_error = 'Cannot select both Raised for Fiber and Not Raised for Fiber for the same species.';
            }
        }else {
            if (isset($_POST['source_fiber'])) {
                $source_fiber = 1;
            }else {
                if(isset($_POST['source_fiber_no'])){
                    $source_fiber = -1;
                }else{
                    $source_fiber = 0;
                }
            }
        }
        if (isset($_POST['source_pelt']) && isset($_POST['source_pelt_no'])){
            if(isset($source_of_error)){
                $source_of_error .= ' Cannot select both Raised for Pelt and Not Raised for Pelt for the same species.';
            }else {
                $source_of_error = 'Cannot select both Raised for Pelt and Not Raised for Pelt for the same species.';
            }
        }else {
            if (isset($_POST['source_pelt'])) {
                $source_pelt = 1;
            }else {
                if(isset($_POST['source_pelt_no'])){
                    $source_pelt = -1;
                }else{
                    $source_pelt = 0;
                }
            }
        }
        if (isset($_POST['hot_fert']) && isset($_POST['cold_fert'])){
            if(isset($source_of_error)){
                $source_of_error .= ' Cannot select both Raised for Hot Fertilizer and Raised for Cold Fertilizer for the same species.';
            }else {
                $source_of_error = 'Cannot select both Raised for Hot Fertilizer and Raised for Cold Fertilizer for the same species.';
            }
        }else {
            if (isset($_POST['hot_fert'])) {
                $source_hot_fert = 1;
                $source_cold_fert = -1;
            }else {
                if(isset($_POST['cold_fert'])){
                    $source_hot_fert = -1;
                    $source_cold_fert = 1;
                }else{
                    $source_hot_fert = 0;
                    $source_cold_fert = 0;
                }
            }
        }
        $gestation_days = (int) $_POST['gestation_days'];
        $min_temp = (int) $_POST['min_temp'];
        $max_temp = (int) $_POST['max_temp'];
        if (isset($_POST['vaccines']) && isset($_POST['vaccines_no'])){
            $vaccines_error = 'Cannot select both Has Vaccines and Does Not Have Vaccies for the same species.';
        }else {
            if (isset($_POST['vaccines'])) {
                $vaccines = 1;
            }else {
                if(isset($_POST['vaccines_no'])){
                    $vaccines = -1;
                }else{
                    $vaccines = 0;
                }
            }
        }

        if (!isset($housing_type_error) && !isset($food_type_error) && !isset($source_of_error) && !isset($vaccines_error)){
            $result = update_animal_species($species_id, $difficulty_level,
                $cage_happy, $pasture_happy, $food_meat, $food_bug, $food_plant, $source_meat,
                $source_egg, $source_milk, $source_fiber, $gestation_days, $min_temp, $max_temp,
                $vaccines, $daily_feed, $daily_feed_unit, $daily_feed_unit_per, $source_pelt,
                $source_hot_fert, $source_cold_fert);

            if ($result['success']) {
                $update_success_message = 'Animal Species record added '.$animal_species_name;
                $html_table = get_animal_species_table();
                $species_dropdown = get_animal_species_dropdown($_SESSION['user_type']);
                $nav_bar = get_navbar($_SESSION['user_name'], $_SESSION['user_type'], 'animal_species_table');
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
    <title>Add Animal Species</title>
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
                        <h2>Existing Animal Species by Source</h2>
                    </div>
                    <span class="table-responsive"> <?php echo $html_table; ?> </span>
            </div>
        </div>
    </div>
    <div class="card-group">
        <div class="card">
            <div class="card-body">
                <h2>Add Animal Species by Source</h2>
            </div>
            <p>Please fill all applicable fields in the form</p>
            <span class="text-danger"><?php if (isset($add_error_message)) echo $add_error_message; ?></span>
            <span class="text-success"><?php if (isset($add_success_message)) echo $add_success_message; ?></span>
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
                    <select name="difficulty_level" id="difficulty_level">
                        <option value="null">Unknown</option>
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
                    <span class="text-danger"><?php if (isset($food_type_error)) echo $food_type_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Daily Food Amount Per Weight Unit</label>
                    <input type="number" name="daily_food_amount" class="form-control" value="" min="0.00" max="730.00" step="any">
                    <span class="text-danger"><?php if (isset($daily_food_error)) echo $daily_food_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Daily Food Unit</label>
                    <select name="daily_food_unit" id="daily_food_unit">
                        <option value="null">Unknown</option>
                        <option value="gram">Gram</option>
                        <option value="cup">Cup</option>
                        <option value="ounce">Ounce</option>
                        <option value="quart">Quart</option>
                        <option value="pound">Pound</option>
                    </select>
                    <span class="text-danger"><?php if (isset($daily_food_error)) echo $daily_food_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Daily Food Per Weight Unit</label>
                    <select name="daily_food_per_unit" id="daily_food_per_unit">
                        <option value="null">Unknown</option>
                        <option value="ounce">Ounce</option>
                        <option value="pound">Pound</option>
                    </select>
                    <span class="text-danger"><?php if (isset($daily_food_error)) echo $daily_food_error; ?></span>
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
                    <input type="checkbox" id="source_pelt" name="source_pelt" value="source_pelt">
                        <label for="source_pelt"> Raised for Pelt </label>
                    <input type="checkbox" id="source_pelt_no" name="source_pelt_no" value="source_pelt_no">
                        <label for="source_pelt_no"> Not Raised for Pelt </label><br>
                    <input type="checkbox" id="hot_fert" name="hot_fert" value="hot_fert">
                        <label for="hot_fert"> Raised for Hot Fertilizer </label>
                    <input type="checkbox" id="cold_fert" name="cold_fert" value="cold_fert">
                        <label for="cold_fert"> Raised for Cold Fertilizer </label><br>
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
        <div class="card">
            <div class="card-body">
                <h2>Update Animal Species</h2>
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
                    <label>Difficulty Level</label><br>
                    <select name="difficulty_level" id="difficulty_level">
                        <option value="null">Unknown</option>
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
                    <span class="text-danger"><?php if (isset($food_type_error)) echo $food_type_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Daily Food Amount Per Weight Unit</label>
                    <input type="number" name="daily_food_amount" class="form-control" value="" min="0.00" max="730.00" step="any">
                    <span class="text-danger"><?php if (isset($daily_food_error)) echo $daily_food_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Daily Food Unit</label>
                    <select name="daily_food_unit" id="daily_food_unit">
                        <option value="null">Unknown</option>
                        <option value="gram">Gram</option>
                        <option value="cup">Cup</option>
                        <option value="ounce">Ounce</option>
                        <option value="quart">Quart</option>
                        <option value="pound">Pound</option>
                    </select>
                    <span class="text-danger"><?php if (isset($daily_food_error)) echo $daily_food_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Daily Food Per Weight Unit</label>
                    <select name="daily_food_per_unit" id="daily_food_per_unit">
                        <option value="null">Unknown</option>
                        <option value="ounce">Ounce</option>
                        <option value="pound">Pound</option>
                    </select>
                    <span class="text-danger"><?php if (isset($daily_food_error)) echo $daily_food_error; ?></span>
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
                    <input type="checkbox" id="source_pelt" name="source_pelt" value="source_pelt">
                    <label for="source_pelt"> Raised for Pelt </label>
                    <input type="checkbox" id="source_pelt_no" name="source_pelt_no" value="source_pelt_no">
                    <label for="source_pelt_no"> Not Raised for Pelt </label><br>
                    <input type="checkbox" id="hot_fert" name="hot_fert" value="hot_fert">
                    <label for="hot_fert"> Raised for Hot Fertilizer </label>
                    <input type="checkbox" id="cold_fert" name="cold_fert" value="cold_fert">
                    <label for="cold_fert"> Raised for Cold Fertilizer </label><br>
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
                <input type="submit" class="btn btn-primary" name="update" value="submit">
            </form>
        </div>
    </div>
</div>
</body>
</html>