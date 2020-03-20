<?php  

add_action("init", "wmcf_scripts_style");

function wmcf_scripts_style(){
    wp_enqueue_script("init-testing", MCFEURL."js/index.js",["jquery"], time(), true);
    wp_enqueue_style("front_style",  MCFEURL."assets/css/style.css");
    // echo MCFEURL;
}

function missing_get_users(){
    
    $option = get_option("wmcf_data");

    print_r( $option );
    $data = array( "one"=> "One", "two"=> "TWo");
    $default = array( "three"=>"Three", "two"=>"TWo");
   $data = wp_parse_args($data, $default);

    update_option("wmcf_data", $data);
 

}
//add_action("init", "missing_get_users");
// missing_get_users();