<?php
/*
Plugin Name: Paxs - Admin Passport
Plugin URI: http://robertochoa.com.ve/
Description: Plugin para registrar/visualizar la data de i2 en el admin de wordpress
Version: 2.0
Author: Robert Ochoa
Author URI: http://robertochoa.com.ve/
License: GPL2

    Copyright 2015 Robert Ochoa (email : ochoa.robert1@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/* --------------------------------------------------------------
    FUNCTIONS ON ACTIVATION
-------------------------------------------------------------- */
register_activation_hook(__FILE__, 'paxs_create_database');

function paxs_create_database()
{
    global $wpdb;
    $table_name = $wpdb->prefix . "paxs_pass_data";
    $paxs_db_version = '2.0.0';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
                ID mediumint(9) NOT NULL AUTO_INCREMENT,
                apellido varchar(100) NULL,
                nombre varchar(100) NULL,
                cedula varchar(100) NULL,
                pasaporte varchar(100) NULL,
                fecha_nac date DEFAULT '0000-00-00' NULL,
                fecha_ven date DEFAULT '0000-00-00' NULL,
                image_url varchar(100) NULL,
                PRIMARY KEY  (ID)
        ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    $table_name = $wpdb->prefix . "paxs_hist_data";
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
                ID mediumint(9) NOT NULL AUTO_INCREMENT,
                id_registro mediumint(9) NOT NULL,
                ruta_vuelo varchar(100) NULL,
                fecha_vuelo date DEFAULT '0000-00-00' NULL,
                aerolinea varchar(100) NULL,
                nro_vuelo varchar(100) NULL,
                nro_boleto varchar(100) NULL,
                reservacion varchar(100) NULL,
                PRIMARY KEY  (ID)
        ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    add_option('paxs_db_version', $paxs_db_version);
}

/* --------------------------------------------------------------
    ADDING CUSTOM STYLES / SCRIPTS
-------------------------------------------------------------- */
add_action('admin_enqueue_scripts', 'paxs_styles_scripts_callback', 99);

function paxs_styles_scripts_callback($hook)
{
    $allowed = array('toplevel_page_paxs_dashboard', 'paxs_page_paxs_main_data', 'paxs_page_paxs_reports_data');
    if (!in_array($hook, $allowed)) {
        return;
    }

    wp_enqueue_media();
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css?family=Titillium+Web:300,400,600&display=swap', null, '2.0.0', 'all');
    wp_enqueue_style('datatables-css', '//cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css', null, '2.0.0', 'all');
    wp_enqueue_style('sweetalert-css', plugins_url('css/sweetalert.css', __FILE__), null, '2.0.0', 'all');
    wp_enqueue_style('paxs_admin_style', plugins_url('css/paxs.css', __FILE__), null, '2.0.0', 'all');
    wp_enqueue_style('paxs_admin_responsive', plugins_url('css/responsive.css', __FILE__), null, '2.0.0', 'all');
    wp_register_script('datatables-js', '//cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js', array('jquery'), '2.0.0', true);
    wp_register_script('sweetalert-js', plugins_url('js/sweetalert.js', __FILE__), array('jquery'), '2.0.0', true);
    wp_register_script('paxs_admin_script', plugins_url('js/paxs.js', __FILE__), array('jquery', 'datatables-js', 'sweetalert-js'), '2.0.0', true);
    wp_enqueue_script('paxs_admin_script');
    wp_localize_script('paxs_admin_script', 'custom_admin_url', array(
        'paxs_db_version' => get_option('paxs_db_version')
    ));
}

/* --------------------------------------------------------------
    INCLUDE REQUIRED FILES
-------------------------------------------------------------- */
require_once('inc/admin.php');
require_once('inc/historical.php');
require_once('inc/main-data.php');
require_once('inc/dashboard.php');
require_once('inc/main-view.php');
require_once('inc/reports.php');
