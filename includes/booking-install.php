<?php
function simple_booking_install() {
    global $wpdb;
    $table_name = $wpdb->prefix . "bookings";
    
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        arrival_date date NOT NULL,
        departure_date date NOT NULL,
        name tinytext NOT NULL,
        email varchar(100) NOT NULL,
        phone varchar(15) DEFAULT '' NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'simple_booking_install');
?>
