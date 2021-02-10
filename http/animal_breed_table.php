<?php
try{
    session_start();
    include "./../php_app/animal_operations.php";
    if(isset($_SESSION['user_id']) =="") {
        header("Location: login.php");
    }
    $html_table = get_animal_breed_table();
    $species_dropdown = get_animal_species_dropdown();
    $breed_dropdown = get_animal_breed_dropdown($_SESSION['user_type']);

    if (isset($_POST['add'])) {
        $species_id = (int) $_POST['species_id'];
        $animal_breed_name = $_POST['animal_breed_name'];
        if($_POST['difficulty_level']=='null'){
            $difficulty_level = null;
        }else {
            $difficulty_level = $_POST['difficulty_level'];
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
        $color = $_POST['color'];
        $min_size = (int) $_POST['min_size'];
        $max_size = (int) $_POST['max_size'];
        $size_unit = $_POST['size_units'];
        if (isset($_POST['summer']) && isset($_POST['summer_no'])){
            $weather_error = 'Cannot select both Summer Happy and Not Summer Happy for the same species.';
        }else {
            if (isset($_POST['summer'])) {
                $summer = 1;
            }else {
                if(isset($_POST['summer_no'])){
                    $summer = -1;
                }else{
                    $summer = 0;
                }
            }
        }
        if (isset($_POST['winter']) && isset($_POST['winter_no'])){
            if(isset($weather_error)){
                $weather_error .= ' Cannot select both Winter Happy and Not Winter Happy for the same species.';
            }else {
                $weather_error = 'Cannot select both Winter Happy and Not Winter Happy for the same species.';
            }
        }else {
            if (isset($_POST['winter'])) {
                $winter = 1;
            }else {
                if(isset($_POST['winter_no'])){
                    $winter = -1;
                }else{
                    $winter = 0;
                }
            }
        }
        if (isset($_POST['endangered']) && isset($_POST['endangered_no'])){
            $rearity_error = 'Cannot select both Endangered and Not Endangered for the same species.';
        }else {
            if (isset($_POST['endangered'])) {
                $endangered = 1;
            }else {
                if(isset($_POST['endangered_no'])){
                    $endangered = -1;
                }else{
                    $endangered = 0;
                }
            }
        }
        if (isset($_POST['exotic']) && isset($_POST['exotic_no'])){
            if(isset($rearity_error)){
                $rearity_error .= ' Cannot select both Exotic and Not Exotic for the same species.';
            }else {
                $rearity_error = 'Cannot select both Exotic and Not Exotic for the same species.';
            }
        }else {
            if (isset($_POST['exotic'])) {
                $exotic = 1;
            }else {
                if(isset($_POST['exotic_no'])){
                    $exotic = -1;
                }else{
                    $exotic = 0;
                }
            }
        }
        $price_child = (int) $_POST['price_child'];
        $price_adult = (int) $_POST['price_adult'];

        if (!isset($source_of_error) && !isset($weather_error) && !isset($rearity_error)){
            $result = add_animal_breed($_SESSION['user_id'], $species_id, $animal_breed_name, $difficulty_level, $source_meat,
                $source_egg, $source_milk, $source_fiber, $color, $min_size, $max_size, $size_unit, $summer, $winter, $endangered,
                $exotic, $price_child, $price_adult, $source_pelt);

            if ($result['success']) {
                $add_success_message = 'Data source archive record added'.$source_id;
                $html_table = get_animal_breed_table();
                $species_dropdown = get_animal_species_dropdown();
                $breed_dropdown = get_animal_breed_dropdown($_SESSION['user_type']);
            } else {
                unset($add_success_message);
                $add_error_message = $result['error'];
            }
        }
    }

    if (isset($_POST['update'])) {
        $breed_id = (int) $_POST['breed_id'];
        if($_POST['difficulty_level']=='null'){
            $difficulty_level = null;
        }else {
            $difficulty_level = $_POST['difficulty_level'];
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
        $color = $_POST['color'];
        $min_size = (int) $_POST['min_size'];
        $max_size = (int) $_POST['max_size'];
        $size_unit = $_POST['size_units'];
        if (isset($_POST['summer']) && isset($_POST['summer_no'])){
            $weather_error = 'Cannot select both Summer Happy and Not Summer Happy for the same species.';
        }else {
            if (isset($_POST['summer'])) {
                $summer = 1;
            }else {
                if(isset($_POST['summer_no'])){
                    $summer = -1;
                }else{
                    $summer = 0;
                }
            }
        }
        if (isset($_POST['winter']) && isset($_POST['winter_no'])){
            if(isset($weather_error)){
                $weather_error .= ' Cannot select both Winter Happy and Not Winter Happy for the same species.';
            }else {
                $weather_error = 'Cannot select both Winter Happy and Not Winter Happy for the same species.';
            }
        }else {
            if (isset($_POST['winter'])) {
                $winter = 1;
            }else {
                if(isset($_POST['winter_no'])){
                    $winter = -1;
                }else{
                    $winter = 0;
                }
            }
        }
        if (isset($_POST['endangered']) && isset($_POST['endangered_no'])){
            $rearity_error = 'Cannot select both Endangered and Not Endangered for the same species.';
        }else {
            if (isset($_POST['endangered'])) {
                $endangered = 1;
            }else {
                if(isset($_POST['endangered_no'])){
                    $endangered = -1;
                }else{
                    $endangered = 0;
                }
            }
        }
        if (isset($_POST['exotic']) && isset($_POST['exotic_no'])){
            if(isset($rearity_error)){
                $rearity_error .= ' Cannot select both Exotic and Not Exotic for the same species.';
            }else {
                $rearity_error = 'Cannot select both Exotic and Not Exotic for the same species.';
            }
        }else {
            if (isset($_POST['exotic'])) {
                $exotic = 1;
            }else {
                if(isset($_POST['exotic_no'])){
                    $exotic = -1;
                }else{
                    $exotic = 0;
                }
            }
        }
        $price_child = (int) $_POST['price_child'];
        $price_adult = (int) $_POST['price_adult'];

        if (!isset($source_of_error) && !isset($weather_error) && !isset($rearity_error)){
            $result = update_animal_breed($breed_id, $difficulty_level, $source_meat, $source_egg, $source_milk,
                $source_fiber, $color, $min_size, $max_size, $size_unit, $summer, $winter, $endangered, $exotic,
                $price_child, $price_adult, $source_pelt);

            if ($result['success']) {
                $update_success_message = 'Data source archive record added'.$source_id;
                $html_table = get_animal_breed_table();
                $species_dropdown = get_animal_species_dropdown();
                $breed_dropdown = get_animal_breed_dropdown($_SESSION['user_type']);
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
<ul>
    <p class="text">Username : <?php echo $_SESSION['user_name']?></p>
    <p class="text">Type : <?php echo $_SESSION['user_type']?></p>
    <li> <a href="logout.php">Logout</a> </li>
    <li> <a href="dashboard.php">Home</a> </li>
    <li> <a href="source_table.php">Data Source</a> </li>
    <li> <a href="source_archive_table.php">Archived Data Source</a> </li>
    <li> <a href="animal_species_table.php">Animal Species</a> </li>
    <li> <a class="active" href="animal_breed_table.php">Animal Breed</a> </li>
    <li> <a href="plant_species_table.php">Plant Species</a> </li>
    <li> <a href="animal_food_plants_table.php">Animal Food: Plants</a> </li>
    <li> <a href="animal_events_table.php">Animal Events</a> </li>
    <li> <a href="animal_event_links_table.php">Animal Event Links</a> </li>
</ul>
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
                <h2>Add Animal Breed by Source and Species</h2>
            </div>
            <p>Please fill all applicable fields in the form</p>
            <span class="text-danger"><?php if (isset($add_error_message)) echo $add_error_message; ?></span>
            <span class="text-success"><?php if (isset($add_success_message)) echo $add_success_message; ?></span>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label>Animal Species by Source</label>
                    <span class="custom-select"><?php echo $species_dropdown ?></span>
                    <span class="text-danger"><?php if (isset($species_error)) echo $species_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Animal Breed Name</label>
                    <input type="text" name="animal_breed_name" class="form-control" value="" maxlength="250" required="">
                    <span class="text-danger"><?php if (isset($animal_breed_name_error)) echo $animal_breed_name_error; ?></span>
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
                    <<span class="text-danger"><?php if (isset($source_of_error)) echo $source_of_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Color(s)</label>
                    <input type="text" name="color" class="form-control" value="" maxlength="250" required="">
                    <span class="text-danger"><?php if (isset($color_error)) echo $color_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Minimum Size</label>
                    <input type="number" name="min_size" class="form-control" value="" min="0" max="99999">
                    <span class="text-danger"><?php if (isset($min_size_error)) echo $min_size_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Maximum Size</label>
                    <input type="number" name="max_size" class="form-control" value="" min="0" max="99999">
                    <span class="text-danger"><?php if (isset($max_size_error)) echo $max_size_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Size Units</label><br>
                    <select name="size_units" id="size_units">
                        <option value="ounce">Ounce (1/16 pound)</option>
                        <option value="pound">Pound (16 ounces)</option>
                    </select>
                    <span class="text-danger"><?php if (isset($size_unit_error)) echo $size_unit_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Preferred Weather</label><br>
                    <input type="checkbox" id="summer" name="summer" value="summer">
                        <label for="summer"> Summer Happy </label>
                    <input type="checkbox" id="summer_no" name="summer_no" value="summer_no">
                        <label for="summer_no"> Not Summer Happy </label><br>
                    <input type="checkbox" id="winter" name="winter" value="winter">
                        <label for="winter"> Winter Happy </label>
                    <input type="checkbox" id="winter_no" name="winter_no" value="winter_no">
                        <label for="winter_no"> Not Winter Happy </label><br>
                    <span class="text-danger"><?php if (isset($weather_error)) echo $weather_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Rearity</label><br>
                    <input type="checkbox" id="endangered" name="endangered" value="endangered">
                    <label for="endangered"> Endangered </label>
                    <input type="checkbox" id="endangered_no" name="endangered_no" value="endangered_no">
                    <label for="endangered_no"> Not Endangerd </label><br>
                    <input type="checkbox" id="exotic" name="exotic" value="exotic">
                    <label for="exotic"> Exotic </label>
                    <input type="checkbox" id="exotic_no" name="exotic_no" value="exotic_no">
                    <label for="exotic_no"> Not Exotic </label><br>
                    <span class="text-danger"><?php if (isset($rearity_error)) echo $rearity_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Price Child ($)</label>
                    <input type="number" name="price_child" class="form-control" value="" min="0" max="99999">
                    <span class="text-danger"><?php if (isset($price_child_error)) echo $price_child_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Price Adult ($)</label>
                    <input type="number" name="price_adult" class="form-control" value="" min="0" max="99999">
                    <span class="text-danger"><?php if (isset($price_adult_error)) echo $price_adult_error; ?></span>
                </div>
                <input type="submit" class="btn btn-primary" name="add" value="submit">
            </form>
        </div>
        <div class="card">
            <div class="card-body">
                <h2>Update Animal Breed by Source and Species</h2>
            </div>
            <p>Please fill all applicable fields in the form</p>
            <span class="text-danger"><?php if (isset($update_error_message)) echo $update_error_message; ?></span>
            <span class="text-success"><?php if (isset($update_success_message)) echo $update_success_message; ?></span>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label>Animal Breed to Update</label>
                    <span class="custom-select"><?php echo $breed_dropdown ?></span>
                    <span class="text-danger"><?php if (isset($breed_error)) echo $breed_error; ?></span>
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
                    <<span class="text-danger"><?php if (isset($source_of_error)) echo $source_of_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Color(s)</label>
                    <input type="text" name="color" class="form-control" value="" maxlength="250" required="">
                    <span class="text-danger"><?php if (isset($color_error)) echo $color_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Minimum Size</label>
                    <input type="number" name="min_size" class="form-control" value="" min="0" max="99999">
                    <span class="text-danger"><?php if (isset($min_size_error)) echo $min_size_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Maximum Size</label>
                    <input type="number" name="max_size" class="form-control" value="" min="0" max="99999">
                    <span class="text-danger"><?php if (isset($max_size_error)) echo $max_size_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Size Units</label><br>
                    <select name="size_units" id="size_units">
                        <option value="ounce">Ounce (1/16 pound)</option>
                        <option value="pound">Pound (16 ounces)</option>
                    </select>
                    <span class="text-danger"><?php if (isset($size_unit_error)) echo $size_unit_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Preferred Weather</label><br>
                    <input type="checkbox" id="summer" name="summer" value="summer">
                    <label for="summer"> Summer Happy </label>
                    <input type="checkbox" id="summer_no" name="summer_no" value="summer_no">
                    <label for="summer_no"> Not Summer Happy </label><br>
                    <input type="checkbox" id="winter" name="winter" value="winter">
                    <label for="winter"> Winter Happy </label>
                    <input type="checkbox" id="winter_no" name="winter_no" value="winter_no">
                    <label for="winter_no"> Not Winter Happy </label><br>
                    <span class="text-danger"><?php if (isset($weather_error)) echo $weather_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Rearity</label><br>
                    <input type="checkbox" id="endangered" name="endangered" value="endangered">
                    <label for="endangered"> Endangered </label>
                    <input type="checkbox" id="endangered_no" name="endangered_no" value="endangered_no">
                    <label for="endangered_no"> Not Endangerd </label><br>
                    <input type="checkbox" id="exotic" name="exotic" value="exotic">
                    <label for="exotic"> Exotic </label>
                    <input type="checkbox" id="exotic_no" name="exotic_no" value="exotic_no">
                    <label for="exotic_no"> Not Exotic </label><br>
                    <span class="text-danger"><?php if (isset($rearity_error)) echo $rearity_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Price Child ($)</label>
                    <input type="number" name="price_child" class="form-control" value="" min="0" max="99999">
                    <span class="text-danger"><?php if (isset($price_child_error)) echo $price_child_error; ?></span>
                </div>
                <div class="form-group">
                    <label>Price Adult ($)</label>
                    <input type="number" name="price_adult" class="form-control" value="" min="0" max="99999">
                    <span class="text-danger"><?php if (isset($price_adult_error)) echo $price_adult_error; ?></span>
                </div>
                <input type="submit" class="btn btn-primary" name="update" value="submit">
            </form>
        </div>
    </div>
</div>
</body>
</html>