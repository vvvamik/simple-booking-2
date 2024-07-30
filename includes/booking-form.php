<?php
function simple_booking_form() {
    ob_start();
    ?>
    <form id="booking-form" method="post">
        <label for="arrival_date"><?php echo __('Datum příjezdu:', 'simple-booking'); ?></label>
        <input type="text" id="arrival_date" name="arrival_date" required>

        <label for="departure_date"><?php echo __('Datum odjezdu:', 'simple-booking'); ?></label>
        <input type="text" id="departure_date" name="departure_date" required>

        <label for="name"><?php echo __('Jméno:', 'simple-booking'); ?></label>
        <input type="text" id="name" name="name" required>

        <label for="email"><?php echo __('Email:', 'simple-booking'); ?></label>
        <input type="email" id="email" name="email" required>

        <label for="phone"><?php echo __('Telefon:', 'simple-booking'); ?></label>
        <input type="text" id="phone" name="phone">

        <input type="submit" value="<?php echo __('Rezervovat', 'simple-booking'); ?>">
        <div id="booking-result"></div>
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode('simple_booking_form', 'simple_booking_form');
?>
