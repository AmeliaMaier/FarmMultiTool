<?php
return;

function add_animal_species($user_id, $animal_species_name, $source_id, $difficulty_level,
                            $cage_happy, $pasture_happy, $food_meat, $food_bug, $food_plant, $source_meat,
                            $source_egg, $source_milk, $source_fiber, $gestation_days, $min_temp, $max_temp,
                            $vaccines)
{
    if (animal_species_exists($animal_species_name, $source_id)) {
        return array("success" => false, "error" => "A record already exists for species " . $animal_species_name . " with source id " . $source_id);
    }
    if (insert_animal_species($user_id, $animal_species_name, $source_id, $difficulty_level,
        $cage_happy, $pasture_happy, $food_meat, $food_bug, $food_plant, $source_meat,
        $source_egg, $source_milk, $source_fiber, $gestation_days, $min_temp, $max_temp,
        $vaccines)) {
        return array("success" => true);
    }
    return array("success" => false, "error" => "An error occurred while saving the species.");

}

function add_animal_breed($user_id, $species_id, $animal_breed_name, $difficulty_level, $source_meat,
                          $source_egg, $source_milk, $source_fiber, $color, $min_size, $max_size, $size_unit, $summer, $winter, $endangered,
                          $exotic, $price_child, $price_adult)
{
    if (animal_breed_exists($species_id, $animal_breed_name)) {
        return array("success" => false, "error" => "A record already exists for breed " . $animal_breed_name . " with species id " . $species_id);
    }
    if (insert_animal_breed($user_id, $species_id, $animal_breed_name, $difficulty_level, $source_meat,
        $source_egg, $source_milk, $source_fiber, $color, $min_size, $max_size, $size_unit, $summer, $winter, $endangered,
        $exotic, $price_child, $price_adult)) {
        return array("success" => true);
    }
    return array("success" => false, "error" => "An error occurred while saving the breed.");

}

function add_animal_food_plants($user_id, $source_id, $animal_species_id, $plant_species_id, $medical, $limit_access,
                $free_feed, $teething, $grit, $notes){
    if (animal_food_plants_exists($source_id, $animal_species_id, $plant_species_id)) {
        return array("success" => false, "error" => "A record already exists for breed " . $animal_breed_name . " with species id " . $species_id);
    }
    if (insert_animal_food_plants($user_id, $source_id, $animal_species_id, $plant_species_id, $medical, $limit_access,
        $free_feed, $teething, $grit, $notes)) {
        return array("success" => true);
    }
    return array("success" => false, "error" => "An error occurred while saving the Animal's food.");
}

function animal_species_exists($animal_species_name, $source_id)
{
    if (!function_exists('get_db_connection')) {
        include "db.php";
    }
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
    $stmt->store_result();
    $count = $stmt->num_rows;
    return $count > 0;
}

function animal_breed_exists($species_id, $animal_breed_name)
{
    if (!function_exists('get_db_connection')) {
        include "db.php";
    }
    $conn = get_db_connection();
    $stmt = $conn->prepare(
        'SELECT cab.* 
                FROM core_animal_breed cab
                inner join core_animal_species cas 
                on cab.species_id = cas.id
                AND cas.deleted_dt IS NULL
                WHERE cab.species_id = ? 
                  AND cab.breed_name = ? 
                  AND cas.deleted_dt IS NULL  ');
    $stmt->bind_param('is', $species_id, $animal_breed_name);
    $stmt->execute();
    $stmt->store_result();
    $count = $stmt->num_rows;
    return $count > 0;
}

function animal_food_plants_exists($source_id, $animal_species_id, $plant_species_id){
    if (!function_exists('get_db_connection')) {
        include "db.php";
    }
    $conn = get_db_connection();
    $stmt = $conn->prepare(
        'SELECT
                    cafp.*
                FROM `core_animal_food_plants` cafp
                INNER JOIN core_sources cs 
                    ON cafp.core_source_id = cs.id
                    AND cs.deleted_dt IS NULL
                INNER JOIN core_animal_species cas 
                    ON cafp.animal_species_id = cas.id
                    AND cas.deleted_dt IS NULL
                INNER JOIN core_plant_species cps 
                    ON cafp.plant_species_id = cps.id
                    AND cps.deleted_dt IS NULL
                WHERE cafp.deleted_dt IS NULL
                AND cafp.core_source_id = ?
                AND cas.id = ?
                AND cps.id = ?');
    $stmt->bind_param('is', $source_id, $animal_species_id, $plant_species_id);
    $stmt->execute();
    $stmt->store_result();
    $count = $stmt->num_rows;
    return $count > 0;
}

function insert_animal_species($user_id, $animal_species_name, $source_id, $difficulty_level,
                               $cage_happy, $pasture_happy, $food_meat, $food_bug, $food_plant, $source_meat,
                               $source_egg, $source_milk, $source_fiber, $gestation_days, $min_temp, $max_temp,
                               $vaccines)
{
    if (!function_exists('get_db_connection')) {
        include "db.php";
    }
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

function update_animal_species($species_id, $difficulty_level,
                               $cage_happy, $pasture_happy, $food_meat, $food_bug, $food_plant, $source_meat,
                               $source_egg, $source_milk, $source_fiber, $gestation_days, $min_temp, $max_temp,
                               $vaccines){
    if (!function_exists('get_db_connection')) {
        include "db.php";
    }
    $conn = get_db_connection();
    $stmt = $conn->prepare(
        'UPDATE `core_animal_species` 
                SET 
                  `meat_source`= ?,
                  `fiber_source`= ?,
                  `milk_source`= ?,
                  `egg_source`= ?,
                  `cage_happy`= ?,
                  `pasture_happy`= ?,
                  `difficulty_level`= ?,
                  `eats_bugs`= ?,
                  `eats_meat`= ?,
                  `eats_plants`= ?,
                  `min_temp`= ?,
                  `max_temp`= ?,
                  `vaccine_schedule`= ?,
                  `gestation_days`= ?
                  WHERE id = ?');
    $stmt->bind_param('iiiiiisiiiiiiii', $source_meat, $source_fiber, $source_milk, $source_egg,
                    $cage_happy, $pasture_happy, $difficulty_level, $food_bug, $food_meat, $food_plant, $min_temp,
                    $max_temp, $vaccines, $gestation_days, $species_id);
    if($stmt->execute()){
        return array("success"=>true);
    }
    return array("success"=>false,  "error"=>"An error occurred while updating the species' record.");

}

function insert_animal_breed($user_id, $species_id, $animal_breed_name, $difficulty_level, $source_meat,
                             $source_egg, $source_milk, $source_fiber, $color, $min_size, $max_size, $size_unit, $summer,
                             $winter, $endangered, $exotic, $price_child, $price_adult)
{
    if (!function_exists('get_db_connection')) {
        include "db.php";
    }
    $conn = get_db_connection();
    $stmt = $conn->prepare(
        'INSERT INTO `core_animal_breed`(`user_id`, `core_source_id`, `species_id`, `breed_name`, `min_size`, 
                                `max_size`, `size_units`, `meat_source`, `milk_source`, `egg_source`, `fiber_source`, 
                                `summer_happy`, `winter_happy`, `endangered`, `exotic`, `color`, `price_child`, 
                                `price_adult`, `difficulty_level`, `created_dt`) 
                (SELECT 
                    ? as user_id, cas.core_source_id, ? as species_id, ? as breed_name, ? as min_size,
                    ? as max_size, ? as size_units, ? as meat_source, ? as milk_source, ? as egg_source,
                    ? as fiber_source, ? as summer_happy, ? as winter_happy, ? as endangered, ? as exotic,
                    ? as color, ? as price_child, ? as price_adult, ? as difficulty_level, CURRENT_DATE as created_dt
                FROM core_animal_species cas 
                where cas.id = ?)');
    $stmt->bind_param('iisiisiiiiiiiisiisi', $user_id, $species_id, $animal_breed_name, $min_size,
        $max_size, $size_unit, $source_meat, $source_milk, $source_egg, $source_fiber, $summer,
        $winter, $endangered, $exotic, $color, $price_child, $price_adult, $difficulty_level, $species_id);
    return $stmt->execute();
}

function insert_animal_food_plants($user_id, $source_id, $animal_species_id, $plant_species_id, $medical, $limit_access,
    $free_feed, $teething, $grit, $notes){
    if (!function_exists('get_db_connection')) {
        include "db.php";
    }
    $conn = get_db_connection();
    $stmt = $conn->prepare(
        'INSERT INTO `core_animal_food_plants`(`animal_species_id`, 
                                      `plant_species_id`, `medical_use`, `limit_access`, 
                                      `free_feed`, `teething`, `grit`, `user_id`, 
                                      `core_source_id`, `notes`, `created_dt`)
              VALUES
                ( ?,?,?,?,?,?,?,?,?,?, CURRENT_DATE)');
    $stmt->bind_param('iiiiiiiiis', $animal_species_id, $plant_species_id, $medical, $limit_access,
                $free_feed, $teething, $grit, $user_id, $source_id, $notes);
    return $stmt->execute();
}

function update_animal_food_plants($animal_food_plants_id, $medical, $limit_access,
                                   $free_feed, $teething, $grit, $notes){
    if (!function_exists('get_db_connection')) {
        include "db.php";
    }
    $conn = get_db_connection();
    $stmt = $conn->prepare(
        'UPDATE `core_animal_food_plants` 
                SET 
                  `medical_use`= ?,
                  `limit_access`= ?,
                  `free_feed`= ?,
                  `teething`= ?,
                  `grit`= ?,
                  `notes`= ?
                  WHERE `id` = ?');
    $stmt->bind_param('iiiiisi', $medical, $limit_access, $free_feed, $teething,
                $grit, $notes);
    if($stmt->execute()){
        return array("success"=>true);
    }
    return array("success"=>false,  "error"=>"An error occurred while updating the food's record.");

}

function update_animal_breed($breed_id, $difficulty_level, $source_meat, $source_egg, $source_milk,
                             $source_fiber, $color, $min_size, $max_size, $size_unit, $summer, $winter, $endangered, $exotic,
                             $price_child, $price_adult)
{
    if (!function_exists('get_db_connection')) {
        include "db.php";
    }
    $conn = get_db_connection();
    $stmt = $conn->prepare(
        'UPDATE `core_animal_breed` 
                SET `min_size`= ?,
                  `max_size`= ?,
                  `size_units`= ?,
                  `meat_source`= ?,
                  `milk_source`= ?,
                  `egg_source`= ?,
                  `fiber_source`= ?,
                  `summer_happy`= ?,
                  `winter_happy`= ?,
                  `endangered`= ?,
                  `exotic`= ?,
                  `color`= ?,
                  `price_child`= ?,
                  `price_adult`= ?,
                  `difficulty_level`= ?
                  WHERE `id` = ?');
    $stmt->bind_param('iisiiiiiiiisiisi', $min_size, $max_size, $size_unit, $source_meat, $source_milk,
                    $source_egg, $source_fiber, $summer, $winter, $endangered, $exotic, $color, $price_child, $price_adult,
                    $difficulty_level, $breed_id);
    if($stmt->execute()){
        return array("success"=>true);
    }
    return array("success"=>false,  "error"=>"An error occurred while updating the breed's record.");

}

function get_animal_breed_table()
{
    if (!function_exists('get_db_connection')) {
        include "db.php";
    }
    $conn = get_db_connection();
    $query = "SELECT 
                     cs.title as source_name, 
                     cas.species_name, 
                     cab.breed_name,
                     cab.difficulty_level, 
                     cab.egg_source,
                     cab.meat_source,
                     cab.milk_source,
                     cab.fiber_source,
                     cab.color,
                     cab.min_size,
                     cab.max_size,
                     cab.size_units,
                     cab.summer_happy,
                     cab.winter_happy,
                     cab.endangered,
                     cab.exotic,
                     cab.price_adult,
                     cab.price_child
            FROM core_animal_breed cab
            INNER JOIN core_animal_species cas 
            	on cab.species_id = cas.id
                and cab.core_source_id = cas.core_source_id
                and cas.deleted_dt IS NULL
            INNER JOIN core_sources cs 
                on cab.core_source_id = cs.id 
                and cs.deleted_dt IS NULL 
            WHERE cab.deleted_dt IS NULL";
    $result = $conn->query($query);

    $html_table = '<table> <tr> 
                                <td> Source_Name </td>  
                                <td> Animal_Species_Name </td> 
                                <td> Animal_Breed_Name </td>  
                                <td> Difficulty_Level </td>  
                                <td> Fiber_Source </td>  
                                <td> Meat_Source </td>  
                                <td> Milk_Source </td>   
                                <td> Egg_Source </td>   
                                <td> Color </td>   
                                <td> Min_Size </td>   
                                <td> Max_Size </td>   
                                <td> Size_units </td> 
                                <td> Summer_Happy </td> 
                                <td> Winter_Happy </td> 
                                <td> Endangered </td> 
                                <td> Exotic </td> 
                                <td> Price_Adult </td> 
                                <td> Price_Child </td> 
                           </tr>';

    if ($result !== false) {
        foreach ($result as $row) {
            $field1name = $row["source_name"];
            $field2name = $row["species_name"];
            $field3name = $row["breed_name"];
            $field4name = $row["difficulty_level"];
            $field5name = $row["fiber_source"];
            $field6name = $row["meat_source"];
            $field7name = $row["milk_source"];
            $field8name = $row["egg_source"];
            $field9name = $row["color"];
            $field10name = $row["min_size"];
            $field11name = $row["max_size"];
            $field12name = $row["size_units"];
            $field13name = $row["summer_happy"];
            $field14name = $row["winter_happy"];
            $field15name = $row["endangered"];
            $field16name = $row["exotic"];
            $field17name = $row["price_adult"];
            $field18name = $row["price_child"];

            $html_table .= '<tr> 
                                  <td>' . $field1name . '</td> 
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
                                  <td>' . $field13name . '</td> 
                                  <td>' . $field14name . '</td> 
                                  <td>' . $field15name . '</td> 
                                  <td>' . $field16name . '</td> 
                                  <td>' . $field17name . '</td> 
                                  <td>' . $field18name . '</td> 
                              </tr>';
        }
        $result->free();
    }
    $html_table .= ' </table>';
    return $html_table;
}

function get_animal_species_table()
{
    if (!function_exists('get_db_connection')) {
        include "db.php";
    }
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
        foreach ($result as $row) {
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
                                  <td>' . $field13name . '</td> 
                                  <td>' . $field14name . '</td> 
                                  <td>' . $field15name . '</td> 
                                  <td>' . $field16name . '</td> 
                                  <td>' . $field17name . '</td> 
                              </tr>';
        }
        $result->free();
    }
    $html_table .= ' </table>';
    return $html_table;
}

function get_animal_food_plants_table(){
    if (!function_exists('get_db_connection')) {
        include "db.php";
    }
    $conn = get_db_connection();
    $query = "SELECT
                    cafp.`id`, 
                    cas.species_name as animal_species_name, 
                    cps.species_name as plant_species_name, 
                    cs.title as source_name,
                    cafp.`medical_use`, 
                    cafp.`limit_access`, 
                    cafp.`free_feed`, 
                    cafp.`teething`, 
                    cafp.`grit`, 
                    cafp.`notes`
                FROM `core_animal_food_plants` cafp
                INNER JOIN core_sources cs 
                    ON cafp.core_source_id = cs.id
                    AND cs.deleted_dt IS NULL
                INNER JOIN core_animal_species cas 
                    ON cafp.animal_species_id = cas.id
                    AND cas.deleted_dt IS NULL
                INNER JOIN core_plant_species cps 
                    ON cafp.plant_species_id = cps.id
                    AND cps.deleted_dt IS NULL
                WHERE cafp.deleted_dt IS NULL";
    $result = $conn->query($query);

    $html_table = '<table> <tr> 
                                <td> Animal_Species_Name </td> 
                                <td> Plant_Species_Name </td> 
                                <td> Source_Name </td>  
                                <td> Medical_Use </td>  
                                <td> Limit_Access </td>  
                                <td> Free_Feed </td>  
                                <td> Teething </td>  
                                <td> Grit </td>  
                                <td> Notes </td>  
                           </tr>';

    if ($result !== false) {
        foreach ($result as $row) {
            $field1name = $row["animal_species_name"];
            $field2name = $row["plant_species_name"];
            $field3name = $row["source_name"];
            $field4name = $row["medical_use"];
            $field5name = $row["limit_access"];
            $field6name = $row["free_feed"];
            $field7name = $row["teething"];
            $field8name = $row["grit"];
            $field9name = $row["notes"];

            $html_table .= '<tr> 
                                  <td>' . $field1name . '</td>
                                  <td>' . $field2name . '</td> 
                                  <td>' . $field3name . '</td> 
                                  <td>' . $field4name . '</td> 
                                  <td>' . $field5name . '</td> 
                                  <td>' . $field6name . '</td> 
                                  <td>' . $field7name . '</td> 
                                  <td>' . $field8name . '</td> 
                                  <td>' . $field9name . '</td> 
                              </tr>';
        }
        $result->free();
    }
    $html_table .= ' </table>';
    return $html_table;
}

function get_animal_species_dropdown($user_type = 'unset')
{
    if (!function_exists('get_db_connection')) {
        include "db.php";
    }
    $conn = get_db_connection();
    if ($user_type = 'unset') {
        $query = "SELECT cas.id, CONCAT(cas.species_name, ' | ', cs.title) as species
                    FROM core_animal_species cas 
                    INNER JOIN core_sources cs 
                        ON cas.core_source_id = cs.id
                        AND cs.deleted_dt IS NULL
                    WHERE cas.deleted_dt IS NULL";
    } else{
        $query = "SELECT cas.id, CONCAT(cas.species_name, ' | ', cs.title) as species
                    FROM core_animal_species cas 
                    INNER JOIN core_sources cs 
                        ON cas.core_source_id = cs.id
                        AND cs.deleted_dt IS NULL
                    INNER JOIN user_type ut 
                    	ON cas.user_id = ut.user_id
                        AND ut.deleted_dt IS NULL
                    WHERE cas.deleted_dt IS NULL
                    AND ut.type = '".$user_type."'";
    }
    $result = $conn->query($query);

    $html_dropdown = "<select name='species_id'>";
    if ($result !== false) {
        foreach ($result as $row) {
            $html_dropdown .= "<option value='" . $row['id'] . "'> " . $row['species'] . " </option>";
        }
    }
    $html_dropdown .= " </select>";
    return $html_dropdown;
}

function get_animal_breed_dropdown($user_type = 'unset')
{
    if (!function_exists('get_db_connection')) {
        include "db.php";
    }
    $conn = get_db_connection();
    if ($user_type = 'unset') {
        $query = "SELECT cab.id, CONCAT(cas.species_name, ' | ', cab.breed_name) as breeds
                    FROM core_animal_breed cab
                    INNER JOIN core_animal_species cas 
                        ON cab.species_id = cas.id 
                        AND cas.deleted_dt IS NULL
                    WHERE cab.deleted_dt IS NULL";
    } else {
        $query = "SELECT cab.id, CONCAT(cas.species_name, ' | ', cab.breed_name) as breeds
                    FROM core_animal_breed cab
                    INNER JOIN core_animal_species cas 
                        ON cab.species_id = cas.id 
                        AND cas.deleted_dt IS NULL
                    INNER JOIN user_type ut 
                        ON cab.user_id = ut.user_id
                        AND ut.deleted_dt IS NULL
                    WHERE cab.deleted_dt IS NULL
                    AND ut.type = '" . $user_type . "'";
    }
    $result = $conn->query($query);

    $html_dropdown = "<select name='breed_id'>";
    if ($result !== false) {
        foreach ($result as $row) {
            $html_dropdown .= "<option value='" . $row['id'] . "'> " . $row['breeds'] . " </option>";
        }
    }
    $html_dropdown .= " </select>";
    return $html_dropdown;
}

function get_animal_food_plants_dropdown($user_type = 'unset'){
    if (!function_exists('get_db_connection')) {
        include "db.php";
    }
    $conn = get_db_connection();
    if ($user_type = 'unset') {
        $query = "SELECT
                        cafp.`id`, 
                        CONCAT(cas.species_name, ' | ',  cps.species_name, ' | ', cs.title) as food_name
                    FROM `core_animal_food_plants` cafp
                    INNER JOIN core_sources cs 
                        ON cafp.core_source_id = cs.id
                        AND cs.deleted_dt IS NULL
                    INNER JOIN core_animal_species cas 
                        ON cafp.animal_species_id = cas.id
                        AND cas.deleted_dt IS NULL
                    INNER JOIN core_plant_species cps 
                        ON cafp.plant_species_id = cps.id
                        AND cps.deleted_dt IS NULL
                    WHERE cafp.deleted_dt IS NULL";
    } else {
        $query = "SELECT
                        cafp.`id`, 
                        CONCAT(cas.species_name, ' | ',  cps.species_name, ' | ', cs.title) as food_name
                    FROM `core_animal_food_plants` cafp
                    INNER JOIN core_sources cs 
                        ON cafp.core_source_id = cs.id
                        AND cs.deleted_dt IS NULL
                    INNER JOIN core_animal_species cas 
                        ON cafp.animal_species_id = cas.id
                        AND cas.deleted_dt IS NULL
                    INNER JOIN core_plant_species cps 
                        ON cafp.plant_species_id = cps.id
                        AND cps.deleted_dt IS NULL
                    INNER JOIN user_type ut 
                        ON cab.user_id = ut.user_id
                        AND ut.deleted_dt IS NULL
                    WHERE cafp.deleted_dt IS NULL
                    AND ut.type = '" . $user_type . "'";
    }
    $result = $conn->query($query);

    $html_dropdown = "<select name='animal_food_plants_id'>";
    if ($result !== false) {
        foreach ($result as $row) {
            $html_dropdown .= "<option value='" . $row['id'] . "'> " . $row['food_name'] . " </option>";
        }
    }
    $html_dropdown .= " </select>";
    return $html_dropdown;
}

?>

