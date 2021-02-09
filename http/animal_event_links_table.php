<?php

try{
    session_start();
    include "./../php_app/animal_operations.php";
    include "./../php_app/source_operations.php";
    if(isset($_SESSION['user_id']) =="") {
        header("Location: login.php");
    }
    $html_table = get_animal_event_links_table();
    $source_dropdown = get_sources_dropdown();
    $events_dropdown = get_animal_events_dropdown();
    $next_events_dropdown = get_animal_events_dropdown('unset', 'next_event_id', true);
    $event_links_dropdown = get_animal_event_links_dropdown($_SESSION['user_type']);
    if (isset($_POST['add'])) {
        $source_id = (int) $_POST['source_id'];
        $current_event_id = (int) $_POST['animal_event_id'];
        if($_POST['next_event_id'] == 'null'){
            $next_event_id = null;
        } else {
            $next_event_id = (int)$_POST['next_event_id'];
        }
        $time_between = (int) $_POST['time_between'];
        $time_units = $_POST['time_units'];
        $result = add_animal_event_link($_SESSION['user_id'], $source_id, $current_event_id, $next_event_id,
                    $time_between, $time_units);
        if ($result['success']) {
            $add_success_message = 'Event Link record added for Animal.';
            $html_table = get_animal_event_links_table();
            $source_dropdown = get_sources_dropdown();
            $events_dropdown = get_animal_events_dropdown();
            $next_events_dropdown = get_animal_events_dropdown('unset', 'next_event_id', true);
            $event_links_dropdown = get_animal_event_links_dropdown($_SESSION['user_type']);
        } else {
            unset($add_success_message);
            $add_error_message = $result['error'];
        }

    }

    if (isset($_POST['update'])) {
        $event_link_id = (int) $_POST['animal_event_link_id'];
        $time_between = (int) $_POST['time_between'];
        $time_units = $_POST['time_units'];
        $result = update_animal_event_link($event_link_id, $time_between, $time_units);
        if ($result['success']) {
            $add_success_message = 'Event Link record updated for Animal.';
            $html_table = get_animal_event_links_table();
            $source_dropdown = get_sources_dropdown();
            $events_dropdown = get_animal_events_dropdown();
            $next_events_dropdown = get_animal_events_dropdown('unset', 'next_event_id', true);
            $event_links_dropdown = get_animal_event_links_dropdown($_SESSION['user_type']);
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
    <title>Add Animal Event Link</title>
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
    <li> <a href="animal_events_table.php">Animal Events</a> </li>
    <li> <a class="active" href="animal_event_links_table.php">Animal Event Links</a> </li>
</ul>
<div style="margin-left:25%;padding:1px 16px;height:1000px;">
    <span class="text-danger"><?php if (isset($error_message)) echo $error_message; ?></span>
    <span class="text-success"><?php if (isset($success_message)) echo $success_message; ?></span>
    <div class="row">
        <div class="card">
            <div class="card-body">
                    <div class="page-header">
                        <h2>Existing Animal Event Links</h2>
                    </div>
                    <span class="table-responsive"> <?php echo $html_table; ?> </span>
            </div>
        </div>
    </div>
    <div class="card-group">
        <div class="card">
            <div class="card-body">
                    <div class="page-header">
                        <h2>Add an Animal Event Link</h2>
                    </div>
                    <p>Please fill all fields in the form</p>
                    <span class="text-danger"><?php if (isset($add_error_message)) echo $add_error_message; ?></span>
                    <span class="text-success"><?php if (isset($add_success_message)) echo $add_success_message; ?></span>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Data Source</label>
                            <span class="custom-select"><?php echo $source_dropdown ?></span>
                            <span class="text-danger"><?php if (isset($data_source_error)) echo $data_source_error; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Current Event</label>
                            <span class="custom-select"><?php echo $events_dropdown ?></span>
                            <span class="text-danger"><?php if (isset($event_error)) echo $event_error; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Next Event</label>
                            <span class="custom-select"><?php echo $next_events_dropdown ?></span>
                            <span class="text-danger"><?php if (isset($event_error)) echo $event_error; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Time Between Events</label>
                            <input type="number" name="time_between" class="form-control" value="" min="1" max="999999">
                            <span class="text-danger"><?php if (isset($time_between_error)) echo $time_between_error; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Time Units</label><br>
                            <select name="time_units" id="time_units">
                                <option value="minute">Minute (60 sec)</option>
                                <option value="hour">Hour (60 min)</option>
                                <option value="day">Day (24 hours)</option>
                                <option value="month">Month (Calendar)</option>
                                <option value="year">Year (Calendar)</option>
                            </select>
                            <span class="text-danger"><?php if (isset($time_units_error)) echo $time_units_error; ?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" name="add" value="submit">
                    </form>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="page-header">
                    <h2>Update an Animal Event Link</h2>
                </div>
                <p>Please fill all fields in the form</p>
                <span class="text-danger"><?php if (isset($update_error_message)) echo $update_error_message; ?></span>
                <span class="text-success"><?php if (isset($update_success_message)) echo $update_success_message; ?></span>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label>Event Link to Update</label>
                        <span class="custom-select"><?php echo $event_links_dropdown ?></span>
                        <span class="text-danger"><?php if (isset($event_links_error)) echo $event_links_error; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Time Between Events</label>
                        <input type="number" name="time_between" class="form-control" value="" min="1" max="999999">
                        <span class="text-danger"><?php if (isset($time_between_error)) echo $time_between_error; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Time Units</label><br>
                        <select name="time_units" id="time_units">
                            <option value="minute">Minute (60 sec)</option>
                            <option value="hour">Hour (60 min)</option>
                            <option value="day">Day (24 hours)</option>
                            <option value="month">Month (Calendar)</option>
                            <option value="year">Year (Calendar)</option>
                        </select>
                        <span class="text-danger"><?php if (isset($time_units_error)) echo $time_units_error; ?></span>
                    </div>
                    <input type="submit" class="btn btn-primary" name="update" value="submit">
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>