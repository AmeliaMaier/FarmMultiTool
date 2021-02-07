<?php
return;

function get_password_details($user_name){
     include "db.php";
    $conn = get_db_connection();
    $stmt = $conn->prepare("SELECT user_logins.id as user_id, 
                  user_password, 
                  user_salt, 
                  type as user_type 
                  FROM user_logins 
                  INNER JOIN user_type ON user_logins.id = user_type.user_id 
                  WHERE user_name = ?
                  AND user_logins.deleted_dt IS NULL 
                  AND user_type.deleted_dt IS NULL
                  LIMIT 1");
    $stmt->bind_param('s', $user_name);
    $stmt->execute();

    /* bind result variables */
    $stmt->bind_result($user_id, $password, $salt, $user_type);

    /* fetch values */
    while ($stmt->fetch()) {
        return array("user_id"=>$user_id,
            "user_name"=>$user_name,
            "password"=>$password,
            "salt"=>$salt,
            "user_type"=>$user_type,
            "success"=>true);
    }
    /* nothing was found */
    return array("success"=>false);
}

function compare_passwords($password, $db_record){
    include "db.php";
    if(!$db_record['success']){
        /* nothing was found */
        return array("success"=>false);
    }
    if (hash_hmac("sha256", $password, $db_record['salt']) === $db_record['password'])
        /* user_name found, password matches */
        return array("success"=>true,
            "user_id"=>$db_record["user_id"],
            "user_name"=>$db_record["user_name"],
            "user_type"=>$db_record["user_type"]);
    /* user_name found, password not match */
    return array("success"=>false);
}

?>