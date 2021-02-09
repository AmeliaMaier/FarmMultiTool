<?php
try{
    session_start();
    if(isset($_SESSION['user_id']) =="") {
        header("Location: login.php");
    }

}catch(Exception $e) {
    $error_message = $e.getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Info Dashboard</title>
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<ul>
    <p class="text">Username : <?php echo $_SESSION['user_name']?></p>
    <p class="text">Type : <?php echo $_SESSION['user_type']?></p>
    <li> <a href="logout.php">Logout</a> </li>
    <li> <a class="active" href="dashboard.php">Home</a> </li>
    <li> <a href="source_table.php">Data Source</a> </li>
    <li> <a href="source_archive_table.php">Archived Data Source</a> </li>
    <li> <a href="animal_species_table.php">Animal Species</a> </li>
    <li> <a href="animal_breed_table.php">Animal Breed</a> </li>
    <li> <a href="plant_species_table.php">Plant Species</a> </li>
    <li> <a href="animal_food_plants_table.php">Animal Food: Plants</a> </li>
</ul>
<div style="margin-left:25%;padding:1px 16px;height:1000px;">
    <span class="text-danger"><?php if (isset($error_message)) echo $error_message; ?></span>
    <span class="text-success"><?php if (isset($success_message)) echo $success_message; ?></span>
</body>
</html>