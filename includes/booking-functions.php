<?php
function simple_booking_ajax_handler() {
    global $wpdb;
    $table_name = $wpdb->prefix . "bookings";

    $arrival_date = sanitize_text_field($_POST['arrival_date']);
    $departure_date = sanitize_text_field($_POST['departure_date']);
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);

    // Kontrola dostupnosti
    $bookings = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $table_name WHERE (arrival_date <= %s AND departure_date >= %s) OR (arrival_date <= %s AND departure_date >= %s)",
        $departure_date, $arrival_date, $departure_date, $arrival_date
    ));

    if (count($bookings) > 0) {
        echo __('Termín je obsazený. Vyberte prosím jiný termín.', 'simple-booking');
    } else {
        // Uložení rezervace
        $wpdb->insert($table_name, array(
            'arrival_date' => $arrival_date,
            'departure_date' => $departure_date,
            'name' => $name,
            'email' => $email,
            'phone' => $phone
        ));

        // Odeslání emailu uživateli
        $user_subject = __('Potvrzení rezervace', 'simple-booking');
        $user_message = "
        <html>
        <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f4f4f4;
            }

            .email-container {
                max-width: 600px;
                margin: 20px auto;
                background-color: #ffffff;
                border: 1px solid #dddddd;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                overflow: hidden;
            }

            .email-header {
                background-color: #4CAF50;
                color: #ffffff;
                padding: 20px;
                text-align: center;
            }

            .email-header h1 {
                margin: 0;
            }

            .email-body {
                padding: 20px;
            }

            .email-body p {
                font-size: 16px;
                line-height: 1.5;
                color: #333333;
            }

            .email-footer {
                background-color: #f4f4f4;
                padding: 10px;
                text-align: center;
                color: #888888;
                font-size: 12px;
            }

            .button {
                display: inline-block;
                padding: 10px 20px;
                margin: 10px 0;
                background-color: #4CAF50;
                color: #ffffff;
                text-decoration: none;
                border-radius: 5px;
            }

            .button:hover {
                background-color: #45a049;
            }
        </style>
        </head>
        <body>
            <div class='email-container'>
                <div class='email-header'>
                    <h1>". __('Potvrzení rezervace', 'simple-booking') ."</h1>
                </div>
                <div class='email-body'>
                    <p>". sprintf(__('Dobrý den, %s', 'simple-booking'), $name) . "</p>
                    <p>". __('Vaše rezervace byla přijata.', 'simple-booking') ."</p>
                    <p>". __('Podrobnosti o rezervaci:', 'simple-booking') ."</p>
                    <p><strong>". __('Datum příjezdu:', 'simple-booking') ."</strong> $arrival_date</p>
                    <p><strong>". __('Datum odjezdu:', 'simple-booking') ."</strong> $departure_date</p>
                    <p><strong>". __('Jméno:', 'simple-booking') ."</strong> $name</p>
                    <p><strong>". __('Email:', 'simple-booking') ."</strong> $email</p>
                    <p><strong>". __('Telefon:', 'simple-booking') ."</strong> $phone</p>
                    <p>". __('Děkujeme za vaši rezervaci a brzy se vám ozveme.', 'simple-booking') ."</p>
                </div>
                <div class='email-footer'>
                    <p>". __('Tento e-mail byl vygenerován automaticky, prosím neodpovídejte na něj.', 'simple-booking') ."</p>
                </div>
            </div>
        </body>
        </html>
        ";

        $headers = array('Content-Type: text/html; charset=UTF-8');

        wp_mail($email, $user_subject, $user_message, $headers);

        // Odeslání emailu administrátorovi
        $admin_email = get_option('admin_email');
        $admin_subject = __('Nová rezervace', 'simple-booking');
        $admin_message = "
        <html>
        <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f4f4f4;
            }

            .email-container {
                max-width: 600px;
                margin: 20px auto;
                background-color: #ffffff;
                border: 1px solid #dddddd;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                overflow: hidden;
            }

            .email-header {
                background-color: #4CAF50;
                color: #ffffff;
                padding: 20px;
                text-align: center;
            }

            .email-header h1 {
                margin: 0;
            }

            .email-body {
                padding: 20px;
            }

            .email-body p {
                font-size: 16px;
                line-height: 1.5;
                color: #333333;
            }

            .email-footer {
                background-color: #f4f4f4;
                padding: 10px;
                text-align: center;
                color: #888888;
                font-size: 12px;
            }

            .button {
                display: inline-block;
                padding: 10px 20px;
                margin: 10px 0;
                background-color: #4CAF50;
                color: #ffffff;
                text-decoration: none;
                border-radius: 5px;
            }

            .button:hover {
                background-color: #45a049;
            }
        </style>
        </head>
        <body>
            <div class='email-container'>
                <div class='email-header'>
                    <h1>". __('Nová rezervace', 'simple-booking') ."</h1>
                </div>
                <div class='email-body'>
                    <p>". sprintf(__('Byla vytvořena nová rezervace.', 'simple-booking')) ."</p>
                    <p><strong>". __('Datum příjezdu:', 'simple-booking') ."</strong> $arrival_date</p>
                    <p><strong>". __('Datum odjezdu:', 'simple-booking') ."</strong> $departure_date</p>
                    <p><strong>". __('Jméno:', 'simple-booking') ."</strong> $name</p>
                    <p><strong>". __('Email:', 'simple-booking') ."</strong> $email</p>
                    <p><strong>". __('Telefon:', 'simple-booking') ."</strong> $phone</p>
                </div>
                <div class='email-footer'>
                    <p>". __('Tento e-mail byl vygenerován automaticky, prosím neodpovídejte na něj.', 'simple-booking') ."</p>
                </div>
            </div>
        </body>
        </html>
        ";

        wp_mail($admin_email, $admin_subject, $admin_message, $headers);

        echo __('Rezervace byla úspěšně vytvořena.<br>Brzy se vám ozveme. Děkujeme.','simple-booking');
    }

    wp_die();
}
add_action('wp_ajax_nopriv_simple_booking', 'simple_booking_ajax_handler');
add_action('wp_ajax_simple_booking', 'simple_booking_ajax_handler');

function simple_booking_get_booked_dates() {
    global $wpdb;
    $table_name = $wpdb->prefix . "bookings";
    
    $bookings = $wpdb->get_results("SELECT arrival_date, departure_date FROM $table_name");

    $booked_dates = array();

    foreach ($bookings as $booking) {
        $current_date = $booking->arrival_date;
        while (strtotime($current_date) <= strtotime($booking->departure_date)) {
            $booked_dates[] = $current_date;
            $current_date = date('Y-m-d', strtotime($current_date . ' +1 day'));
        }
    }

    echo json_encode($booked_dates);
    wp_die();
}
add_action('wp_ajax_nopriv_simple_booking_get_booked_dates', 'simple_booking_get_booked_dates');
add_action('wp_ajax_simple_booking_get_booked_dates', 'simple_booking_get_booked_dates');
?>