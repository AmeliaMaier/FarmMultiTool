<?php
return;

function get_db_connection(){
    $strJsonFileContents = file_get_contents("./../secret_store/core_database.json");
    $arrayJsonFileContents = json_decode($strJsonFileContents, true);
    $servername='localhost';
    $dbname = "farmmult_core";
    $connection=mysqli_connect($servername,$arrayJsonFileContents["username"],$arrayJsonFileContents["password"],"$dbname");
    if(!$connection){
        die('Could not Connect MySql Server:'.mysqli_connect_error());
    }
    return $connection;
}
?>