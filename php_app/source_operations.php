<?php
return;

function add_source($source_type, $address_isbn, $title, $user_id){
    if (source_exists($address_isbn)){
        return array("success"=>false, "error"=>"A data source already exists with address/isbn ".$address_isbn);
    }
    if(insert_source($source_type, $address_isbn, $title, $user_id)){
        return array("success"=>true);
    }
    return array("success"=>false, "error"=>"An error occurred while saving the data source.");
}

function add_source_archive($archive_type, $source_id, $sftp_folder_id, $sftp_file_name, $sftp_share_url, $user_id){
    if ($archive_type == 'auto'){
        return array("success"=>false, "error"=>"Auto Archive is not yet Implemented. Please re-add Manually.");
    }
    if (source_archive_exists($source_id)){
        return array("success"=>false, "error"=>"Data Source ".$source_id." is already archived. No changes saved.");
    }
    if(insert_source_archive($source_id, $sftp_folder_id, $sftp_file_name, $sftp_share_url, $user_id)){
        return array("success"=>true);
    }
    return array("success"=>false, "error"=>"An error occurred while saving the data source's archive record.");

}

function source_exists($address_isbn){
    if(!function_exists('get_db_connection')){include "db.php";}
    $conn = get_db_connection();
    $stmt = $conn->prepare(
        'SELECT * 
                FROM core_sources 
                WHERE `address_isbn` = ?
                AND deleted_dt IS NULL');
    $stmt->bind_param('s', $address_isbn);
    $stmt->execute();
    $stmt -> store_result();
    $count = $stmt -> num_rows;
    return $count > 0;
}

function source_archive_exists($source_id){
    if(!function_exists('get_db_connection')){include "db.php";}
    $conn = get_db_connection();
    $stmt = $conn->prepare(
        'SELECT * 
                FROM core_source_archive 
                WHERE `source_id` = ?
                AND deleted_dt IS NULL');
    $stmt->bind_param('i', $source_id);
    $stmt->execute();
    $stmt -> store_result();
    $count = $stmt -> num_rows;
    return $count > 0;
}


function update_source($source_type, $source_id, $title){
    if(!function_exists('get_db_connection')){include "db.php";}
    $conn = get_db_connection();
    $stmt = $conn->prepare(
        'UPDATE core_sources 
                SET title = ?, source_type = ?
                WHERE id = ?');
    $stmt->bind_param('ssi', $title, $source_type, $source_id);
    if($stmt->execute()){
        return array("success"=>true);
    }
    return array("success"=>false,  "error"=>"An error occurred while updating the data source's record.");
}


function insert_source($source_type, $address_isbn, $title, $user_id){
    if(!function_exists('get_db_connection')){include "db.php";}
    $conn = get_db_connection();
    $stmt = $conn->prepare(
        'INSERT INTO `core_sources`
                (`user_id`, `source_type`, `address_isbn`, `title`, `created_dt`) 
                VALUES 
                (?, ?, ?, ?, CURRENT_DATE)');
    $stmt->bind_param('isss', $user_id, $source_type, $address_isbn, $title);
    return $stmt->execute();
}

function update_source_archive($source_id, $sftp_folder_id, $sftp_file_name, $sftp_share_url){
    if(!function_exists('get_db_connection')){include "db.php";}
    $conn = get_db_connection();
    $stmt = $conn->prepare(
        'UPDATE core_source_archive
                SET sftp_file_name = ?, sftp_folder_id = ?, share_url = ?
                WHERE source_id = ?');
    $stmt->bind_param('sisi', $sftp_file_name, $sftp_folder_id, $sftp_share_url, $source_id);
    if($stmt->execute()){
        return array("success"=>true);
    }
    return array("success"=>false,  "error"=>"An error occurred while updating the data source's archive record.");

}

function insert_source_archive($source_id, $sftp_folder_id, $sftp_file_name, $sftp_share_url, $user_id){
    if(!function_exists('get_db_connection')){include "db.php";}
    $conn = get_db_connection();
    $stmt = $conn->prepare(
        'INSERT INTO `core_source_archive`
                (`user_id`, `source_id`, `sftp_folder_id`, `sftp_file_name`, `share_url`, `created_dt`) 
                VALUES 
                (?, ?, ?, ?, ?, CURRENT_DATE)');
    $stmt->bind_param('iiiss', $user_id, $source_id, $sftp_folder_id, $sftp_file_name, $sftp_share_url);
    return $stmt->execute();
}

function get_sources_table(){
    if(!function_exists('get_db_connection')){include "db.php";}
    $conn = get_db_connection();
    $query = "SELECT id, source_type, address_isbn, title
                FROM core_sources 
                WHERE deleted_dt IS NULL";
    $result = $conn->query($query);

    $html_table = '<table> <tr> <td> Source Type </td> <td> Address or ISBN </td> <td> Title </td> </tr>';

    if ($result !== false) {
        foreach($result as $row) {
            $field1name = $row["source_type"];
            $field2name = $row["address_isbn"];
            $field3name = $row["title"];

            $html_table .= '<tr> 
                                  <td>'.$field1name.'</td> 
                                  <td>'.$field2name.'</td> 
                                  <td>'.$field3name.'</td> 
                              </tr>';
        }
        $result->free();
    }
    $html_table .= ' </table>';
    return $html_table;
}

function get_sources_archive_table(){
    if(!function_exists('get_db_connection')){include "db.php";}
    $conn = get_db_connection();
    $query = "SELECT cs.id as source_id, 
                        cs.source_type, 
                        cs.address_isbn, 
                        cs.title, 
                        csa.share_url, 
                        sf.name as sftp_folder_name,
                        csa.sftp_file_name
                FROM core_sources cs
                LEFT JOIN core_source_archive csa
                    ON cs.id = csa.source_id
                    AND csa.deleted_dt IS NULL
                LEFT JOIN sftp_folders sf 
                    ON csa.sftp_folder_id = sf.sftp_folder_id
                    AND sf.deleted_dt IS NULL
                WHERE cs.deleted_dt IS NULL";
    $result = $conn->query($query);

    $html_table = '<table> <tr> <td> Source ID </td> <td> Source Type </td> <td> Address or ISBN </td> <td> Title </td> <td> Share URL </td> <td> SFTP Folder Name </td> <td> SFTP File Name </td></tr>';

    if ($result !== false) {
        foreach($result as $row) {
            $field1name = $row["source_id"];
            $field2name = $row["source_type"];
            $field3name = $row["address_isbn"];
            $field4name = $row["title"];
            $field5name = $row["share_url"];
            $field6name = $row["sftp_folder_name"];
            $field7name = $row["sftp_file_name"];

            $html_table .= '<tr> 
                                  <td>'.$field1name.'</td> 
                                  <td>'.$field2name.'</td> 
                                  <td>'.$field3name.'</td> 
                                  <td>'.$field4name.'</td> 
                                  <td>'.$field5name.'</td> 
                                  <td>'.$field6name.'</td> 
                                  <td>'.$field7name.'</td> 
                              </tr>';
        }
        $result->free();
    }
    $html_table .= ' </table>';
    return $html_table;
}

function get_sources_dropdown($user_type='unset'){
    if(!function_exists('get_db_connection')){include "db.php";}
    $conn = get_db_connection();
    if ($user_type == 'unset') {
        $query = "SELECT id, source_type, CONCAT(address_isbn, ' | ', title) title
                FROM core_sources 
                WHERE deleted_dt IS NULL";
    }else{
        $query = "SELECT cs.id, cs.source_type, CONCAT(cs.address_isbn, ' | ', cs.title) title
                    FROM core_sources cs
                    INNER JOIN user_type ut 
                        ON cs.user_id = ut.user_id
                        AND ut.deleted_dt is NULL
                    WHERE cs.deleted_dt IS NULL
                    AND ut.type = '".$user_type."'";
    }
    $result = $conn->query($query);

    $html_dropdown = "<select name='source_id'>";
    if ($result !== false) {
        foreach ($result as $row) {
            $html_dropdown .= "<option value='" . $row['id'] . "'>" . $row['title'] . "</option>";
        }
    }
    $html_dropdown .= " </select>";
    return $html_dropdown;
}

function get_archived_sources_dropdown($user_type='unset'){
    if(!function_exists('get_db_connection')){include "db.php";}
    $conn = get_db_connection();
    if ($user_type == 'unset') {
        $query = "SELECT cs.id, cs.source_type, CONCAT(cs.address_isbn, ' | ', cs.title) title
                    FROM core_sources cs
                    INNER JOIN core_source_archive csa
                        ON cs.id = csa.source_id
                        AND csa.deleted_dt IS NULL
                    WHERE cs.deleted_dt IS NULL";
    }else{
        $query = "SELECT cs.id, cs.source_type, CONCAT(cs.address_isbn, ' | ', cs.title) title
                    FROM core_sources cs
                    INNER JOIN core_source_archive csa
                        ON cs.id = csa.source_id
                        AND csa.deleted_dt IS NULL
                    INNER JOIN user_type ut 
                        ON csa.user_id = ut.user_id
                        AND ut.deleted_dt is NULL
                    WHERE cs.deleted_dt IS NULL
                    AND ut.type = '".$user_type."'";
    }
    $result = $conn->query($query);

    $html_dropdown = "<select name='archived_source_id'>";
    if ($result !== false) {
        foreach ($result as $row) {
            $html_dropdown .= "<option value='" . $row['id'] . "'>" . $row['title'] . "</option>";
        }
    }
    $html_dropdown .= " </select>";
    return $html_dropdown;
}

function get_sftp_folder_dropdown(){
    if(!function_exists('get_db_connection')){include "db.php";}
    $conn = get_db_connection();
    $query = "SELECT sftp_folder_id, name 
                FROM sftp_folders 
                WHERE deleted_dt IS NULL ";
    $result = $conn->query($query);

    $html_dropdown = "<select name='sftp_folder_id'>";
    if ($result !== false) {
        foreach ($result as $row) {
            $html_dropdown .= "<option value='" . $row['sftp_folder_id'] . "'>" . $row['name'] . "</option>";
        }
    }
    $html_dropdown .= " </select>";
    return $html_dropdown;
}
?>