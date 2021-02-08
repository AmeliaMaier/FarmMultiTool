<?php
return;

function add_plant_species($user_id, $plant_species_name, $source_id, $growth_zone,
                $water_requirement, $sun_requirement, $soil_requirement, $perennial, $height, $height_units,
                $edible, $harvest_days){
    if (plant_species_exists($plant_species_name, $source_id)) {
        return array("success" => false, "error" => "A record already exists for species " . $plant_species_name . " with source id " . $source_id);
    }
    if (insert_plant_species($user_id, $plant_species_name, $source_id, $growth_zone,
        $water_requirement, $sun_requirement, $soil_requirement, $perennial, $height, $height_units,
        $edible, $harvest_days)) {
        return array("success" => true);
    }
    return array("success" => false, "error" => "An error occurred while saving the species.");
}

function plant_species_exists($plant_species_name, $source_id)
{
    if (!function_exists('get_db_connection')) {
        include "db.php";
    }
    $conn = get_db_connection();
    $stmt = $conn->prepare(
        'SELECT cps.* 
                FROM core_plant_species cps 
                    INNER JOIN core_sources cs 
                        ON cps.core_source_id = cs.id 
                        AND cs.deleted_dt IS NULL 
                WHERE cps.species_name = ? 
                  AND cps.core_source_id = ? 
                  AND cps.deleted_dt IS NULL ');
    $stmt->bind_param('si', $plant_species_name, $source_id);
    $stmt->execute();
    $stmt->store_result();
    $count = $stmt->num_rows;
    return $count > 0;
}

function update_plant_species($species_id, $growth_zone, $water_requirement, $sun_requirement,
                              $soil_requirement, $perennial, $height, $height_units, $edible, $harvest_days){
    if (!function_exists('get_db_connection')) {
        include "db.php";
    }
    $conn = get_db_connection();
    $stmt = $conn->prepare(
        'UPDATE `core_plant_species` 
                SET 
                  `sun_level`= ?,
                  `growth_zone`= ?,
                  `water_requirements`= ?,
                  `height`= ?,
                  `height_unit`= ?,
                  `soil_type`= ?,
                  `human_edible`= ?,
                  `days_to_harvest`= ?,
                  `perennial`= ?
                  WHERE id = ?');
    $stmt->bind_param('sssissiiii', $sun_requirement, $growth_zone, $water_requirement,
        $height, $height_units, $soil_requirement, $edible, $harvest_days, $perennial, $species_id);
    if($stmt->execute()){
        return array("success"=>true);
    }
    return array("success"=>false,  "error"=>"An error occurred while updating the species' record.");

}

function insert_plant_species($user_id, $plant_species_name, $source_id, $growth_zone,
                              $water_requirement, $sun_requirement, $soil_requirement, $perennial, $height, $height_units,
                              $edible, $harvest_days){
    if (!function_exists('get_db_connection')) {
        include "db.php";
    }
    $conn = get_db_connection();
    $stmt = $conn->prepare(
        'INSERT INTO `core_plant_species`(`user_id`, `core_source_id`, `species_name`, 
                                 `sun_level`, `growth_zone`, `water_requirements`, `height`, `height_unit`, 
                                 `soil_type`, `human_edible`, `days_to_harvest`, `perennial`, `created_dt`)
                VALUES 
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_DATE)');
    $stmt->bind_param('iissssissiii', $user_id, $source_id, $plant_species_name, $sun_requirement,
        $growth_zone, $water_requirement, $height, $height_units, $soil_requirement, $edible, $harvest_days, $perennial);
    return $stmt->execute();
}

function get_plant_species_dropdown($user_type='unset'){
    if (!function_exists('get_db_connection')) {
        include "db.php";
    }
    $conn = get_db_connection();
    if ($user_type = 'unset') {
        $query = "SELECT 
                    cps.`id` as plant_species_id, 
                    CONCAT(cps.`species_name`, ' | ', cs.title ) as species
                    FROM `core_plant_species` cps
                    INNER JOIN core_sources cs 
                        ON cps.core_source_id = cs.id
                        AND cs.deleted_dt IS NULL
                    WHERE cps.deleted_dt IS NULL ";
    } else{
        $query = "SELECT 
                    cps.`id` as plant_species_id, 
                    CONCAT(cps.`species_name`, ' | ', cs.title ) as species
                    FROM `core_plant_species` cps
                    INNER JOIN core_sources cs 
                        ON cps.core_source_id = cs.id
                        AND cs.deleted_dt IS NULL
                    INNER JOIN user_type ut 
                    	ON cps.user_id = ut.user_id
                        AND ut.deleted_dt IS NULL
                    WHERE cps.deleted_dt IS NULL
                    AND ut.type = '".$user_type."'";
    }
    $result = $conn->query($query);

    $html_dropdown = "<select name='plant_species_id'>";
    if ($result !== false) {
        foreach ($result as $row) {
            $html_dropdown .= "<option value='" . $row['plant_species_id'] . "'> " . $row['species'] . " </option>";
        }
    }
    $html_dropdown .= " </select>";
    return $html_dropdown;
}


function get_plant_species_table(){
    if (!function_exists('get_db_connection')) {
        include "db.php";
    }
    $conn = get_db_connection();
    $query = "SELECT 
                cps.`id` as plant_species_id, 
                cs.title as source_name,
                cps.`species_name`, 
                cps.`sun_level`, 
                cps.`growth_zone`, 
                cps.`water_requirements`, 
                cps.`height`, 
                cps.`height_unit`, 
                cps.`soil_type`, 
                cps.`human_edible`, 
                cps.`days_to_harvest`, 
                cps.`perennial`
                FROM `core_plant_species` cps
                INNER JOIN core_sources cs 
                    ON cps.core_source_id = cs.id
                    AND cs.deleted_dt IS NULL
                WHERE cps.deleted_dt IS NULL ";
    $result = $conn->query($query);

    $html_table = '<table> <tr> 
                                <td> Plant_Species_Name </td> 
                                <td> Source_Name </td>  
                                <td> Sun_Level </td>  
                                <td> Growth_Zone </td>  
                                <td> Water_Requirements </td>  
                                <td> Height </td>  
                                <td> Height_Unit </td>  
                                <td> Soil_Type </td>  
                                <td> Human_Edible </td>  
                                <td> Days_To_Harvest </td>  
                                <td> Perennial </td>   
                           </tr>';

    if ($result !== false) {
        foreach ($result as $row) {
            $field2name = $row["species_name"];
            $field3name = $row["source_name"];
            $field4name = $row["sun_level"];
            $field5name = $row["growth_zone"];
            $field6name = $row["water_requirements"];
            $field7name = $row["height"];
            $field8name = $row["height_unit"];
            $field9name = $row["soil_type"];
            $field10name = $row["human_edible"];
            $field11name = $row["days_to_harvest"];
            $field12name = $row["perennial"];

            $html_table .= '<tr> 
                                  <td>' . $field2name . '</td> 
                                  <td>' . $field3name . '</td> 
                                  <td>' . $field4name . '</td> 
                                  <td>' . $field5name . '</td> 
                                  <td>' . $field6name . '</td> 
                                  <td>' . $field7name . '</td> 
                                  <td>' . $field8name . '</td> 
                                  <td>' . $field9name . '</td> 
                                  <td>' . $field10name . '</td> 
                                  <td>' . $field11name . '</td> 
                                  <td>' . $field12name . '</td> 
                              </tr>';
        }
        $result->free();
    }
    $html_table .= ' </table>';
    return $html_table;
}


?>
