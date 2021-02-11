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

add_action('wp_ajax_new_data_parser', 'new_data_parser_callback');

function new_data_parser_callback()
{
    ob_start();
?>
<div class="paxs-modal-controller">
    <h2>Nuevo Registro</h2>
    <button class="close-modal"><span class="dashicons dashicons-plus-alt2"></span></button>
</div>
<div class="paxs-new-registry-container">
    <form id="newRegistryForm" class="form-controller">
        <div class="input-wrapper">
            <label for="apellidos">Apellidos</label>
            <input type="text" name="apellidos" class="form-control" />
        </div>
        <div class="input-wrapper">
            <label for="nombres">Nombres</label>
            <input type="text" name="nombres" class="form-control" />
        </div>
        <div class="input-wrapper">
            <label for="cedula">Cédula</label>
            <input type="text" name="cedula" class="form-control" />
        </div>
        <div class="input-wrapper">
            <label for="pasaporte">Pasaporte</label>
            <input type="text" name="pasaporte" class="form-control" />
        </div>
        <div class="input-wrapper">
            <label for="fecha_nac">Fecha de Nacimiento</label>
            <input type="date" name="fecha_nac" class="form-control" />
        </div>
        <div class="input-wrapper">
            <label for="fecha_ven">Fecha de Vencimiento</label>
            <input type="date" name="fecha_ven" class="form-control" />
        </div>

        <div class="submit-wrapper">
            <button id="newRegistrySubmit">Guardar Registro</button>
        </div>
    </form>
</div>
<?php
    $content = ob_get_clean();
    echo $content;
    wp_die();
}