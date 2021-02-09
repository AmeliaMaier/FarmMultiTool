<?php

try{
    session_start();
    include "./../php_app/animal_operations.php";
    include "./../php_app/source_operations.php";
    if(isset($_SESSION['user_id']) =="") {
        header("Location: login.php");
    }
    $html_table = get_animal_events_table();
    $source_dropdown = get_sources_dropdown();
    $species_dropdown = get_animal_species_dropdown();
    $breed_dropdown = get_animal_breed_dropdown('unset', true);
    $events_dropdown = get_animal_events_dropdown($_SESSION['user_type']);

    if (isset($_POST['add'])) {
        $animal_species_id = (int) $_POST['species_id'];
        if($_POST['breed_id'] == 'null'){
            $animal_breed_id = null;
        } else{
            $animal_breed_id = (int) $_POST['breed_id'];
        }
        $source_id = (int) $_POST['source_id'];
        $event_name = $_POST['event_name'];

        $result = add_animal_event($_SESSION['user_id'], $source_id, $animal_species_id, $animal_breed_id, $event_name);
        if ($result['success']) {
            $add_success_message = 'Event record added for Animal.';
            $html_table = get_animal_events_table();
            $source_dropdown = get_sources_dropdown();
            $species_dropdown = get_animal_species_dropdown();
            $breed_dropdown = get_animal_breed_dropdown();
            $events_dropdown = get_animal_events_dropdown($_SESSION['user_type']);
        } else {
            unset($add_success_message);
            $add_error_message = $result['error'];
        }

    }

    if (isset($_POST['update'])) {
        $animal_event_id = (int) $_POST['animal_event_id'];

        $result = update_animal_event($animal_event_id);
        if ($result['success']) {
            $add_success_message = 'Event record updated for Animal.';
            $html_table = get_animal_events_table();
            $source_dropdown = get_sources_dropdown();
            $species_dropdown = get_animal_species_dropdown();
            $breed_dropdown = get_animal_breed_dropdown();
            $events_dropdown = get_animal_events_dropdown($_SESSION['user_type']);
        } else {
                unset($update_success_message);
                $update_error_message = $result['error'];
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
    <title>Add Animal Event</title>
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
    <li> <a href="animal_food_plants_table.php">Animal Food: Plants</a> </li>
    <li> <a class="active" href="animal_events_table.php">Animal Events</a> </li>
</ul>
<div style="margin-left:25%;padding:1px 16px;height:1000px;">
    <span class="text-danger"><?php if (isset($error_message)) echo $error_message; ?></span>
    <span class="text-success"><?php if (isset($success_message)) echo $success_message; ?></span>
    <div class="row">
        <div class="card">
            <div class="card-body">
                    <div class="page-header">
                        <h2>Existing Animal Events</h2>
                    </div>
                    <span class="table-responsive"> <?php echo $html_table; ?> </span>
            </div>
        </div>
    </div>
    <div class="card-group">
        <div class="card">
            <div class="card-body">
                    <div class="page-header">
                        <h2>Add an Animal Event</h2>
                    </div>
                    <p>Please fill all fields in the form</p>
                    <span class="text-danger"><?php if (isset($add_error_message)) echo $add_error_message; ?></span>
                    <span class="text-success"><?php if (isset($add_success_message)) echo $add_success_message; ?></span>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Animal Species</label>
                            <span class="custom-select"><?php echo $species_dropdown ?></span>
                            <span class="text-danger"><?php if (isset($species_error)) echo $species_error; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Animal Bread</label>
                            <span class="custom-select"><?php echo $breed_dropdown ?></span>
                            <span class="text-danger"><?php if (isset($breed_error)) echo $breed_error; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Data Source</label>
                            <span class="custom-select"><?php echo $source_dropdown ?></span>
                            <span class="text-danger"><?php if (isset($data_source_error)) echo $data_source_error; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Event Name</label>
                            <input type="text" name="event_name" class="form-control" value="" maxlength="250">
                            <span class="text-danger"><?php if (isset($event_name_error)) echo $event_name_error; ?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" name="add" value="submit">
                    </form>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="page-header">
                    <h2>Update an Animal Event</h2>
                    <h3>Currently Disabled Due to Lack of Field to Update</h3>
                </div>
                <p>Please fill all fields in the form</p>
                <span class="text-danger"><?php if (isset($update_error_message)) echo $update_error_message; ?></span>
                <span class="text-success"><?php if (isset($update_success_message)) echo $update_success_message; ?></span>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label>Animal Event to Update</label>
                        <span class="custom-select"><?php echo $events_dropdown ?></span>
                        <span class="text-danger"><?php if (isset($event_error)) echo $event_error; ?></span>
                    </div>
<!--                    <input type="submit" class="btn btn-primary" name="update" value="submit">-->
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>