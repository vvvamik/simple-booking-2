<?php
function simple_booking_enqueue_scripts() {
    wp_enqueue_style('jquery-ui-css', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css');
    wp_enqueue_style('simple-booking-style', plugin_dir_url(__FILE__) . '../css/style.css');
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_script('simple-booking-script', plugin_dir_url(__FILE__) . '../js/script.js', array('jquery', 'jquery-ui-datepicker'), null, true);
    wp_localize_script('simple-booking-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'simple_booking_enqueue_scripts');
?>
