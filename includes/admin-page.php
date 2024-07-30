<?php
function simple_booking_admin_menu() {
    add_menu_page(
        __('Rezervace', 'simple-booking'),
        __('Rezervace', 'simple-booking'),
        'manage_options',
        'simple-booking',
        'simple_booking_admin_page',
        'dashicons-calendar',
        6
    );
}
add_action('admin_menu', 'simple_booking_admin_menu');

function simple_booking_admin_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . "bookings";
    $bookings = $wpdb->get_results("SELECT * FROM $table_name");

    echo '<div class="wrap">';
    echo '<h1>' . __('Seznam rezervací', 'simple-booking') . '</h1>';
    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead><tr><th>' . __('ID', 'simple-booking') . '</th><th>' . __('Datum příjezdu', 'simple-booking') .'</th><th>' . __('Datum odjezdu', 'simple-booking') . '</th><th>' . __('Jméno', 'simple-booking') . '</th><th>' . __('Email', 'simple-booking') . '</th><th>' . __('Telefon', 'simple-booking') . '</th><th>' . __('Akce', 'simple-booking') . '</th></tr></thead>';
    echo '<tbody>';
    foreach ($bookings as $booking) {
        echo '<tr>';
        echo '<td>' . esc_html($booking->id) . '</td>';
        echo '<td>' . esc_html($booking->arrival_date) . '</td>';
        echo '<td>' . esc_html($booking->departure_date) . '</td>';
        echo '<td>' . esc_html($booking->name) . '</td>';
        echo '<td>' . esc_html($booking->email) . '</td>';
        echo '<td>' . esc_html($booking->phone) . '</td>';
        echo '<td><a href="' . admin_url('admin.php?page=simple-booking&action=edit&id=' . $booking->id) . '">' . __('Editovat', 'simple-booking') . '</a> | <a href="' . wp_nonce_url(admin_url('admin.php?page=simple-booking&action=delete&id=' . $booking->id), 'simple_booking_delete_' . $booking->id) . '">' . __('Smazat', 'simple-booking') . '</a></td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
    echo '</div>';

    if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
        simple_booking_edit_booking(intval($_GET['id']));
    }
}

function simple_booking_edit_booking($id) {
    global $wpdb;
    $table_name = $wpdb->prefix . "bookings";
    $booking = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id));

    if (!$booking) {
        echo '<div class="error"><p>' . __('Rezervace nenalezena.', 'simple-booking') . '</p></div>';
        return;
    }

    if (isset($_POST['simple_booking_update'])) {
        $arrival_date = sanitize_text_field($_POST['arrival_date']);
        $departure_date = sanitize_text_field($_POST['departure_date']);
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $phone = sanitize_text_field($_POST['phone']);

        $wpdb->update(
            $table_name,
            array(
                'arrival_date' => $arrival_date,
                'departure_date' => $departure_date,
                'name' => $name,
                'email' => $email,
                'phone' => $phone
            ),
            array('id' => $id)
        );

        echo '<div class="updated"><p>' . __('Rezervace byla aktualizována.', 'simple-booking') . '</p></div>';
        $booking = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id));
    }

    ?>
    <div class="wrap">
        <h1><?php echo __('Editace rezervace', 'simple-booking'); ?></h1>
        <form method="post">
            <label for="arrival_date"><?php echo __('Datum příjezdu:', 'simple-booking'); ?></label>
            <input type="text" id="arrival_date" name="arrival_date" value="<?php echo esc_attr($booking->arrival_date); ?>" required>

            <label for="departure_date"><?php echo __('Datum odjezdu:', 'simple-booking'); ?></label>
            <input type="text" id="departure_date" name="departure_date" value="<?php echo esc_attr($booking->departure_date); ?>" required>

            <label for="name"><?php echo __('Jméno:', 'simple-booking'); ?></label>
            <input type="text" id="name" name="name" value="<?php echo esc_attr($booking->name); ?>" required>

            <label for="email"><?php echo __('Email:', 'simple-booking'); ?></label>
            <input type="email" id="email" name="email" value="<?php echo esc_attr($booking->email); ?>" required>

            <label for="phone"><?php echo __('Telefon:', 'simple-booking'); ?></label>
            <input type="text" id="phone" name="phone" value="<?php echo esc_attr($booking->phone); ?>">

            <input type="submit" name="simple_booking_update" value="<?php echo __('Aktualizovat', 'simple-booking'); ?>">
        </form>
    </div>
    <?php
}

function simple_booking_delete_booking() {
    if (!isset($_GET['id']) || !isset($_GET['_wpnonce'])) {
        return;
    }

    $id = intval($_GET['id']);
    if (!wp_verify_nonce($_GET['_wpnonce'], 'simple_booking_delete_' . $id)) {
        return;
    }

    global $wpdb;
    $table_name = $wpdb->prefix . "bookings";
    $wpdb->delete($table_name, array('id' => $id));

    wp_redirect(admin_url('admin.php?page=simple-booking'));
    exit;
}
add_action('admin_init', 'simple_booking_delete_booking');
?>
