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

