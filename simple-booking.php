<?php
/*
Plugin Name: Simple Booking 2.0
Description: A simple booking plugin for WordPress.
Version: 2.0.5
Author: vvvamik
*/


// Načtení textové domény pro překlady
function simple_booking_load_textdomain() {
    load_plugin_textdomain('simple-booking', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'simple_booking_load_textdomain');

// Aktivace pluginu
require_once plugin_dir_path(__FILE__) . 'includes/booking-install.php';

// Skripty a styly
require_once plugin_dir_path(__FILE__) . 'includes/booking-scripts.php';

// Shortcode pro rezervační formulář
require_once plugin_dir_path(__FILE__) . 'includes/booking-form.php';

// Ajax pro kontrolu dostupnosti a uložení rezervace
require_once plugin_dir_path(__FILE__) . 'includes/booking-functions.php';

// Přidání administrátorského menu
require_once plugin_dir_path(__FILE__) . 'includes/admin-page.php';

// Přidání widgetu na nástěnku
require_once plugin_dir_path(__FILE__) . 'includes/booking-dashboard-widget.php';

// Aktivace pluginu
register_activation_hook(__FILE__, 'simple_booking_install');
?>
