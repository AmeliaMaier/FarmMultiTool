<?php
return;

function get_navbar($username, $user_type, $current_page_name)
{
    $page_names = ['logout'=>'Logout', 'dashboard'=>'Home', 'source_table'=>'Data Source',
        'source_archive_table'=>'Archived Data Source', 'animal_species_table'=>'Animal Species',
        'animal_breed_table'=>'Animal Breed', 'plant_species_table'=>'Plant Species',
        'animal_food_plants_table'=>'Animal Food: Plants', 'animal_events_table'=>'Animal Events',
        'animal_event_links_table'=>'Animal Event Links'];

    $navbar_html = '<ul>
                        <p class="text">Username : '.$username.'</p>
                        <p class="text">Type : '.$user_type.'</p>';
    foreach ($page_names as $page_name => $display_name){
        $navbar_html .= '<li> <a ';
        if ($page_name == $current_page_name) {$navbar_html .= 'class="active" ';}
        $navbar_html .= 'href="'.$page_name.'.php">'.$display_name.'</a> </li>';
    }
    $navbar_html .= '</ul>';
    return $navbar_html;
}

?>
