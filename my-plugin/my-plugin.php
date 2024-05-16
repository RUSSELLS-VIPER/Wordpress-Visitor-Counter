<?php
/*
Plugin Name: Custom Visitor Counter
Description: A plugin to count visitors on your WordPress website
Version: 1.0.0
Author: ARIJIT
Author URI: http://arijit.info
*/

// Register the visitor count option
register_activation_hook(__FILE__, 'visitor_counter_activation');
function visitor_counter_activation()
{
    add_option('visitor_count', 0);
    add_option('visitor_ips', array());
}



add_action('init', 'visitor_counter_increment');
function visitor_counter_increment()
{
    if (!is_admin() && !is_user_logged_in()) {
        $visitor_ips = get_option('visitor_ips');
        $visitor_ip = $_SERVER['REMOTE_ADDR'];

        // Check if the visitor is unique based on IP address
        if (!in_array($visitor_ip, $visitor_ips)) {
            $visitor_ips[] = $visitor_ip;
            update_option('visitor_ips', $visitor_ips);

            $visitor_count = get_option('visitor_count');
            $visitor_count++;
            update_option('visitor_count', $visitor_count);
        }
    }
}




// Custom page to display the visitor count
add_action('admin_menu', 'visitor_counter_menu');
function visitor_counter_menu()
{
    add_menu_page(
        'Visitor Counter', // Title of the menu page (displayed in the menu and on the page itself)
        'Visitor Counter',   // Title of the menu item (displayed in the admin menu)
        'manage_options',  // User capability required to access this menu item
        'visitor-counter',
        'visitor_counter_page',
        'dashicons-welcome-view-site',   
        5  // Position of the menu item in the admin menu
    );
}

function visitor_counter_page()
{
    $visitor_count = get_option('visitor_count');

    // Reset the visitor count and visitor IPs if the reset button is clicked
    if (isset($_POST['reset_counter'])) {
        update_option('visitor_count', 0);
        update_option('visitor_ips', array());
        $visitor_count = 0;
    }

    // Refresh the page to update visitor count
    if (isset($_POST['refresh_views'])) {
        echo '<meta http-equiv="refresh" content="0">';
    }

    echo '<div class="visitor-counter-wrap">';
    echo '<h2 style="font-family: Arial, sans-serif; 
            color: #333; font-size: 28px;
             margin-bottom: 20px;">Visitor Counter</h2>';
    echo '<div style="background-color: #f5f5f5; 
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);">';
    echo '<p style="font-size: 24px;
            font-weight: bold;
            color: #0073aa;
            margin-bottom: 10px;">Total Visitors</p>';
    echo '<p style="font-size: 48px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;">' . $visitor_count . '</p>';
    echo '<form method="post" action="">';
    echo '<button type="submit" name="reset_counter" class="button button-primary" style="padding: 10px 20px; 
            font-size: 16px; 
            background-color: blue;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;">Reset Counter</button>';
    echo '<button type="submit" name="refresh_views" class="button button-secondary" style="margin-left: 10px;
            padding: 10px 20px;
            font-size: 16px;
            background-color: blue; 
            color: #fff; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
            transition: background-color 0.3s;
            ">Refresh Views</button>';
    echo '</form>';
    echo '</div>';
    echo '</div>';

    // // Automatic page refresh after resetting the counter
    // if (isset($_POST['reset_counter'])) {
    //     echo '<meta http-equiv="refresh" content="0">';
    // }

   
    echo '<style>';
    echo '.visitor-counter-wrap {';
    echo '  max-width: 400px;';
    echo '  margin: 30px auto;';
    echo '  text-align: center;';
    echo '}';
    echo '</style>';
}
