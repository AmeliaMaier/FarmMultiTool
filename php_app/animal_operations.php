<?php
return;

function add_animal_species($user_id, $animal_species_name, $source_id, $difficulty_level,
                                            $cage_happy, $pasture_happy, $food_meat, $food_bug, $food_plant, $source_meat,
                                            $source_egg, $source_milk, $source_fiber, $gestation_days, $min_temp, $max_temp,
                                            $vaccines){
    if (animal_species_exists($animal_species_name, $source_id)){
        return array("success"=>false, "error"=>"A record already exists for species ".$animal_species_name." with source id ".$source_id);
    }
    if(insert_animal_species($user_id, $animal_species_name, $source_id, $difficulty_level,
        $cage_happy, $pasture_happy, $food_meat, $food_bug, $food_plant, $source_meat,
        $source_egg, $source_milk, $source_fiber, $gestation_days, $min_temp, $max_temp,
        $vaccines)){
        return array("success"=>true);
    }
    return array("success"=>false, "error"=>"An error occurred while saving the species.");

}

function animal_species_exists($animal_species_name, $source_id){
    if(!function_exists('get_db_connection')){include "db.php";}
    $conn = get_db_connection();
    $stmt = $conn->prepare(
        'SELECT cas.* 
                FROM core_animal_species cas 
                    INNER JOIN core_sources cs 
                        ON cas.core_source_id = cs.id 
                        AND cs.deleted_dt IS NULL 
                WHERE cas.species_name = ? 
                  AND cas.core_source_id = ? 
                  AND cas.deleted_dt IS NULL ');
    $stmt->bind_param('si', $animal_species_name, $source_id);
    $stmt->execute();
    $stmt -> store_result();
    $count = $stmt -> num_rows;
    return $count > 0;
}

function insert_animal_species($user_id, $animal_species_name, $source_id, $difficulty_level,
                               $cage_happy, $pasture_happy, $food_meat, $food_bug, $food_plant, $source_meat,
                               $source_egg, $source_milk, $source_fiber, $gestation_days, $min_temp, $max_temp,
                               $vaccines){
    if(!function_exists('get_db_connection')){include "db.php";}
    $conn = get_db_connection();
    $stmt = $conn->prepare(
        'INSERT INTO `core_animal_species`(`user_id`, `core_source_id`, `species_name`, `meat_source`, 
                                  `fiber_source`, `milk_source`, `egg_source`, `cage_happy`, `pasture_happy`, 
                                  `difficulty_level`, `eats_bugs`, `eats_meat`, `eats_plants`, `min_temp`, `max_temp`, 
                                  `vaccine_schedule`, `gestation_days`, `created_dt`) 
                VALUES 
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_DATE)');
    $stmt->bind_param('iisiiiiiisiiiiiii', $user_id, $source_id, $animal_species_name, $source_meat,
                        $source_fiber, $source_milk, $source_egg, $cage_happy, $pasture_happy, $difficulty_level,
                        $food_bug, $food_meat, $food_plant, $min_temp, $max_temp, $vaccines, $gestation_days);
    return $stmt->execute();
}

function get_animal_species_table(){
    if(!function_exists('get_db_connection')){include "db.php";}
    $conn = get_db_connection();
    $query = "SELECT cas.id as animal_species_id, 
                     cas.species_name, 
                     cs.title as source_name, 
                     cas.difficulty_level, 
                     cas.cage_happy, 
                     cas.pasture_happy, 
                     cas.eats_bugs, 
                     cas.eats_meat, 
                     cas.eats_plants, 
                     cas.fiber_source, 
                     cas.meat_source, 
                     cas.milk_source, 
                     cas.egg_source, 
                     cas.gestation_days, 
                     cas.min_temp, 
                     cas.max_temp, 
                     cas.vaccine_schedule 
            FROM core_animal_species cas 
                INNER JOIN core_sources cs 
                    on cas.core_source_id = cs.id 
                    and cs.deleted_dt IS NULL 
            WHERE cas.deleted_dt IS NULL ";
    $result = $conn->query($query);

    $html_table = '<table> <tr> 
                                <td> Animal_Species_Name </td> 
                                <td> Source_Name </td>  
                                <td> Difficulty_Level </td>  
                                <td> Cage_Happy </td>  
                                <td> Pasture_Happy </td>  
                                <td> Eats_Bugs </td>  
                                <td> Eats_Meat </td>  
                                <td> Eats_Plants </td>  
                                <td> Fiber_Source </td>  
                                <td> Meat_Source </td>  
                                <td> Milk_Source </td>   
                                <td> Egg_Source </td>   
                                <td> Gestation_Days </td>   
                                <td> Min_Temp </td>   
                                <td> Max_Temp </td>   
                                <td> Vaccine_Schedule </td> 
                           </tr>';

    if ($result !== false) {
        foreach($result as $row) {
            $field2name = $row["species_name"];
            $field3name = $row["source_name"];
            $field4name = $row["difficulty_level"];
            $field5name = $row["cage_happy"];
            $field6name = $row["pasture_happy"];
            $field7name = $row["eats_bugs"];
            $field8name = $row["eats_meat"];
            $field9name = $row["eats_plants"];
            $field10name = $row["fiber_source"];
            $field11name = $row["meat_source"];
            $field12name = $row["milk_source"];
            $field13name = $row["egg_source"];
            $field14name = $row["gestation_days"];
            $field15name = $row["min_temp"];
            $field16name = $row["max_temp"];
            $field17name = $row["vaccine_schedule"];

            $html_table .= '<tr> 
                                  <td>'.$field2name.'</td> 
                                  <td>'.$field3name.'</td> 
                                  <td>'.$field4name.'</td> 
                                  <td>'.$field5name.'</td> 
                                  <td>'.$field6name.'</td> 
                                  <td>'.$field7name.'</td> 
                                  <td>'.$field8name.'</td> 
                                  <td>'.$field9name.'</td> 
                                  <td>'.$field10name.'</td> 
                                  <td>'.$field11name.'</td> 
                                  <td>'.$field12name.'</td> 
                                  <td>'.$field13name.'</td> 
                                  <td>'.$field14name.'</td> 
                                  <td>'.$field15name.'</td> 
                                  <td>'.$field16name.'</td> 
                                  <td>'.$field17name.'</td> 
                              </tr>';
        }
        $result->free();
    }
    $html_table .= ' </table>';
    return $html_table;
}
?>