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
    <title>Add Animal Breed</title>
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
</div>
</body>
</html>