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
ADDING CUSTOM STYLES / SCRIPTS
-------------------------------------------------------------- */
add_action('admin_enqueue_scripts', 'paxs_styles_scripts_callback', 99);

function paxs_styles_scripts_callback()
{
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css?family=Titillium+Web:300,400,600&display=swap', null, '2.0.0', 'all');
    wp_enqueue_style('paxs_admin_style', plugins_url('css/paxs.css', __FILE__), null, '2.0.0', 'all');
    wp_register_script('paxs_admin_script', plugins_url('js/paxs.js', __FILE__), array('jquery'), '2.0.0', true);
    wp_enqueue_script('paxs_admin_script');
}
