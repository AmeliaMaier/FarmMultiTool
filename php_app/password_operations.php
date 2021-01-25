<?php
return;

function get_password_details($username){
    require_once "db.php";
    $stmt = $conn->prepare("SELECT user_logins.id as user_id, 
                   user_password, 
                   user_salt, 
                   type as user_type 
                   FROM user_logins 
                   INNER JOIN user_type ON user_logins.id = user_type.user_id 
                   WHERE user_name = :username
                   LIMIT 1;");
    $stmt->bindParam(':username', $username); 
    $stmt->execute();
    
     /* bind result variables */
    $stmt->bind_result($id, $password, $salt, $user_type);

    /* fetch values */
    while ($stmt->fetch()) {
        return array("id"=>$id,
                    "username"=>$username, 
                    "password"=>$password, 
                    "salt"=>$salt,
                    "user_type"=>$user_type,
                    "success"=>true);
    }
    /* nothing was found */
    return array("success"=>false);
}

function compare_passwords($password, $db_record){
    if(!$db_record['success']){
        /* nothing was found */
        return $db_record;
    }
    return;
}
?>