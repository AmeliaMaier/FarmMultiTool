<?php
    $strJsonFileContents = file_get_contents("./../secret_store/core_database.json");
    $arrayJsonFileContents = json_decode($strJsonFileContents, true);
    $servername='localhost';
    $dbname = "farmmult_core";
    $conn=mysqli_connect($servername,$arrayJsonFileContents["username"],$arrayJsonFileContents["password"],"$dbname");
      if(!$conn){
          die('Could not Connect MySql Server:' .mysql_error());
        }
?>