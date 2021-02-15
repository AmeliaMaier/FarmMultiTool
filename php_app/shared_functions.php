<?php
return;

function get_page_name($full_path){
    $page_name_full = explode('/', $full_path);
    $page_name = explode('.',end($page_name_full))[0];
    return $page_name;
}


?>