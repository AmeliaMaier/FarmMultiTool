<?php
return;

function add_source($source_type, $address_isbn, $title, $user_id){
    include "db.php";
    if (source_exists($address_isbn)){
        return array("success"=>false, "error"=>"A data source already exists with address/isbn ".$address_isbn);
    }
    if(insert_source($source_type, $address_isbn, $title, $user_id)){
        return array("success"=>true);
    }
    return array("success"=>false, "error"=>"An error occurred while saving the data source.");
}

function source_exists($address_isbn){
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

function insert_source($source_type, $address_isbn, $title, $user_id){
    $conn = get_db_connection();
    $stmt = $conn->prepare(
        'INSERT INTO `core_sources`
                (`user_id`, `source_type`, `address_isbn`, `title`, `created_dt`) 
                VALUES 
                (?, ?, ?, ?, CURRENT_DATE)');
    $stmt->bind_param('isss', $user_id, $source_type, $address_isbn, $title);
    return $stmt->execute();
}

function get_sources_table(){
    $conn = get_db_connection();
    $query = "SELECT source_type, address_isbn, title
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
?>