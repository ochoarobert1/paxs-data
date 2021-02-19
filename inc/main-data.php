<?php
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
            <div class="form-controller-wrapper">
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
            </div>
            <div class="form-controller-image-wrapper">
                <div class="input-wrapper">
                    <div class="button-image-wrapper">
                        <label for="image_url">Imagen del Pasaporte</label>
                        <button type='button' class="upload-file-btn" id="paxs_media_manager"><span class="dashicons dashicons-cloud-upload"></span></button>
                    </div>
                    <img id="paxs-preview-image" src="https://placehold.it/200x200" />
                    <input type="hidden" name="image_url" id="image_url" value="" />
                </div>
            </div>

            <div class="submit-wrapper">
                <button id="newRegistrySubmit" class="button-primary">Guardar Registro</button>
                <div class="modal-loader modal-hidden">
                    <div class="loader-css">
                    </div>
                </div>
            </div>
        </form>
    </div>
<?php
    $content = ob_get_clean();
    echo $content;
    wp_die();
}

add_action('wp_ajax_add_new_data_parser', 'add_new_data_parser_callback');

function add_new_data_parser_callback()
{
    parse_str($_POST['data'], $info);

    global $wpdb;

    $wpdb->insert(
        $wpdb->prefix . 'paxs_pass_data',
        array(
            'apellido' => $info['apellidos'],
            'nombre' => $info['nombres'],
            'cedula' => $info['cedula'],
            'pasaporte' => $info['pasaporte'],
            'fecha_nac' => $info['fecha_nac'],
            'fecha_ven' => $info['fecha_ven'],
            'image_url' => $info['image_url'],
        ),
        array(
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s'
        )
    );

    wp_die();
}

add_action('wp_ajax_erase_data_parser', 'erase_data_parser_callback');

function erase_data_parser_callback()
{
    $id = $_POST['id'];

    global $wpdb;
    $wpdb->delete($wpdb->prefix . 'paxs_pass_data',  array('ID' => $id));

    wp_die();
}


add_action('wp_ajax_edit_data_parser', 'edit_data_parser_callback');

function edit_data_parser_callback()
{
    global $wpdb;
    $select_id = $_POST['id'];
    $maintable = $wpdb->prefix . 'paxs_pass_data';
    $queried_data = $wpdb->get_row("SELECT * FROM $maintable WHERE id = $select_id");
    ob_start();
?>
    <div class="paxs-modal-controller">
        <h2>Editar Registro</h2>
        <button class="close-modal"><span class="dashicons dashicons-plus-alt2"></span></button>
    </div>
    <div class="paxs-new-registry-container">
        <form id="editRegistryForm" class="form-controller">
            <div class="form-controller-wrapper">
                <input type="hidden" name="ID" value="<?php echo $queried_data->ID; ?>">
                <div class="input-wrapper">
                    <label for="apellidos">Apellidos</label>
                    <input type="text" name="apellidos" class="form-control" value="<?php echo $queried_data->apellido; ?>" />
                </div>
                <div class="input-wrapper">
                    <label for="nombres">Nombres</label>
                    <input type="text" name="nombres" class="form-control" value="<?php echo $queried_data->nombre; ?>" />
                </div>
                <div class="input-wrapper">
                    <label for="cedula">Cédula</label>
                    <input type="text" name="cedula" class="form-control" value="<?php echo $queried_data->cedula; ?>" />
                </div>
                <div class="input-wrapper">
                    <label for="pasaporte">Pasaporte</label>
                    <input type="text" name="pasaporte" class="form-control" value="<?php echo $queried_data->pasaporte; ?>" />
                </div>
                <div class="input-wrapper">
                    <label for="fecha_nac">Fecha de Nacimiento</label>
                    <input type="date" name="fecha_nac" class="form-control" value="<?php echo $queried_data->fecha_nac; ?>" />
                </div>
                <div class="input-wrapper">
                    <label for="fecha_ven">Fecha de Vencimiento</label>
                    <input type="date" name="fecha_ven" class="form-control" value="<?php echo $queried_data->fecha_ven; ?>" />
                </div>
            </div>
            <div class="form-controller-image-wrapper">
                <div class="input-wrapper">
                    <div class="button-image-wrapper">
                        <label for="image_url">Imagen del Pasaporte</label>
                        <button type='button' class="upload-file-btn" id="paxs_media_manager"><span class="dashicons dashicons-cloud-upload"></span></button>
                    </div>
                    <?php $image_id = $queried_data->image_url; ?>
                    <?php if (intval($image_id) > 0) { ?>
                        <?php $image = wp_get_attachment_image($image_id, 'medium', false, array('id' => 'paxs-preview-image')); ?>
                    <?php } else { ?>
                        <?php $image = '<img id="paxs-preview-image" src="https://placehold.it/200x200" />'; ?>
                    <?php } ?>
                    <?php echo $image; ?>
                    <input type="hidden" name="image_url" id="image_url" value="<?php echo $queried_data->image_url; ?>" class="regular-text" />
                </div>
            </div>
            <div class="submit-wrapper">
                <button id="editRegistrySubmit">Actualizar Registro</button>
                <div class="modal-loader modal-hidden">
                    <div class="loader-css">
                    </div>
                </div>
            </div>
        </form>
    </div>
<?php
    $content = ob_get_clean();
    echo $content;
    wp_die();
}

add_action('wp_ajax_add_edit_data_parser', 'add_edit_data_parser_callback');

function add_edit_data_parser_callback()
{
    parse_str($_POST['data'], $info);

    global $wpdb;

    $wpdb->update(
        $wpdb->prefix . 'paxs_pass_data',
        array(
            'apellido' => $info['apellidos'],
            'nombre' => $info['nombres'],
            'cedula' => $info['cedula'],
            'pasaporte' => $info['pasaporte'],
            'fecha_nac' => $info['fecha_nac'],
            'fecha_ven' => $info['fecha_ven'],
            'image_url' => $info['image_url'],
        ),
        array('ID' => $info['ID']),
        array(
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s'
        ),
        array('%d')
    );

    wp_die();
}

// Ajax action to refresh the user image
add_action('wp_ajax_paxs_get_image', 'paxs_get_image_callback');
function paxs_get_image_callback()
{
    if (isset($_GET['id'])) {
        $image = wp_get_attachment_image(filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT), 'medium', false, array('id' => 'myprefix-preview-image'));
        $data = array(
            'image'    => $image,
        );
        wp_send_json_success($data);
    } else {
        wp_send_json_error();
    }
}
