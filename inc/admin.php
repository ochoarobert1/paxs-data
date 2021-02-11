<?php
/* CUSTOM MENU PAGE AND FUNCTIONS IN ADMIN */
function register_paxs_settings()
{
    //register our settings
    register_setting('diy-settings-group', 'prices_table_1');
    register_setting('diy-settings-group', 'prices_table_2');
    register_setting('diy-settings-group', 'prices_table_3');
}
add_action('admin_init', 'register_paxs_settings');

function paxs_admin_menu()
{
    add_menu_page('Escritorio', 'Paxs', 'manage_options', 'paxs_dashboard', 'paxs_dashboard_page_callback', 'dashicons-welcome-widgets-menus', 3);
    add_submenu_page('paxs_dashboard', 'Registros del Sistema', 'Registros', 'manage_options', 'paxs_main_data', 'paxs_data_page_callback', null);
}

add_action('admin_menu', 'paxs_admin_menu');

add_action('wp_ajax_main_data_parser', 'main_data_parser_callback');

function main_data_parser_callback()
{
    global $wpdb;

    $start = (int) $_GET['start'];
    $length = (int) $_GET['length'];
    $draw = (int) $_GET['draw'];

    $maintable = $wpdb->prefix . 'paxs_pass_data';

    $data_count = $wpdb->get_results(
        "SELECT * FROM $maintable ORDER BY ID ASC"
    );

    foreach ($data_count as $item) {
        $row = array(
            $item->apellido, $item->nombre, $item->cedula, $item->pasaporte, $item->fecha_nac, $item->fecha_ven, $item->ID
        );
        $return_json[] = $row;
    }

    echo json_encode($return_json);
    wp_die();
}
