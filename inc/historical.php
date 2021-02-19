<?php
add_action('wp_ajax_historical_data_parser', 'historical_data_parser_callback');

function historical_data_parser_callback()
{
    global $wpdb;
    $select_id = $_POST['id'];
    $maintable = $wpdb->prefix . 'paxs_pass_data';
    $secondtable = $wpdb->prefix . 'paxs_hist_data';
    $queried_data = $wpdb->get_row("SELECT * FROM $maintable WHERE id = $select_id");
    $queried_hist = $wpdb->get_results("SELECT * FROM $secondtable WHERE id_registro =  $queried_data->ID ORDER BY fecha_vuelo ASC");
    ob_start();
?>
<div class="paxs-modal-controller">
    <h2>Registro Historico</h2>
    <button class="close-modal"><span class="dashicons dashicons-plus-alt2"></span></button>
</div>
<div class="paxs-new-registry-container">
    <div class="paxs-new-registry-info">
        <div class="paxs-hist-new-wrapper">
            <div class="label-wrapper">
                <strong>Apellidos:</strong> <span><?php echo $queried_data->apellido; ?></span>
            </div>
            <div class="label-wrapper">
                <strong>Nombres:</strong> <span><?php echo $queried_data->nombre; ?></span>
            </div>
            <div class="label-wrapper">
                <strong>Cédula:</strong> <span><?php echo $queried_data->cedula; ?></span>
            </div>
            <div class="label-wrapper">
                <strong>Pasaporte:</strong> <span><?php echo $queried_data->pasaporte; ?></span>
            </div>
            <div class="label-wrapper">
                <?php $fecha_temp = explode('-', $queried_data->fecha_nac); ?>
                <strong>Fecha de Nacimiento:</strong> <span><?php echo $fecha_temp[2] . '-' . $fecha_temp[1] . '-' . $fecha_temp[0]; ?></span>
            </div>
            <div class="label-wrapper">
            <?php $fecha_temp = explode('-', $queried_data->fecha_ven); ?>
                <strong>Fecha de Vencimiento:</strong> <span><?php echo $fecha_temp[2] . '-' . $fecha_temp[1] . '-' . $fecha_temp[0]; ?></span>
            </div>
        </div>
        <div class="paxs-hist-image-wrapper">
            <div class="label-wrapper">
                <strong>Imagen del Pasaporte:</strong>
                <?php $image_id = $queried_data->image_url; ?>
                <?php if (intval($image_id) > 0) { ?>
                <?php $image = wp_get_attachment_image($image_id, 'medium', false, array('id' => 'paxs-preview-image')); ?>
                <?php } ?>
                <?php echo $image; ?>
            </div>
        </div>
    </div>

    <form id="historicalForm" class="historical-form-controller">
        <h2>Registro de Vuelos</h2>
        <input type="hidden" name="id_registro" value="<?php echo $queried_data->ID; ?>">

        <table cellspacing="0" cellpadding="0" id="historicalTable" class="table table-responsive">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Ruta de Vuelo</th>
                    <th>Fecha de Vuelo</th>
                    <th>Aerolínea</th>
                    <th>Nro. de Vuelo</th>
                    <th>Nro. de Boleto</th>
                    <th>Reservación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody class="historical-body">
                <?php $i = 1; ?>
                <?php foreach ($queried_hist as $item) { ?>
                <?php $number = ($i % 2 == 0) ? 'even' : 'odd'; ?>
                <tr class="historical-item <?php echo $number; ?>">
                    <td><?php echo $i; ?></td>
                    <td><?php echo $item->ruta_vuelo; ?></td>
                    <?php $fecha_vuelo = explode('-', $item->fecha_vuelo); ?>
                    <td><?php echo $fecha_vuelo[2] . '-' . $fecha_vuelo[1] . '-' . $fecha_vuelo[0]; ?></td>
                    <td><?php echo $item->aerolinea; ?></td>
                    <td><?php echo $item->nro_vuelo; ?></td>
                    <td><?php echo $item->nro_boleto; ?></td>
                    <td><?php echo $item->reservacion; ?></td>
                    <td>
                        <a onclick="editHistorical(<?php echo $item->ID; ?>)" title="Editar Detalle"><span class="dashicons dashicons-edit-page"></span></a>
                        <a onclick="eraseHistorical(<?php echo $item->ID; ?>)" title="Eliminar Detalle"><span class="dashicons dashicons-trash"></span></a>
                    </td>
                </tr>
                <?php $i++;
                    } ?>
            </tbody>
            <tfoot>
                <tr class="new-row">
                    <td colspan="8">
                        <a onclick="newHistorical()" title="Agregar Detalle"><span class="dashicons dashicons-plus"></span></a>
                    </td>
                </tr>
            </tfoot>
        </table>
    </form>
</div>

<?php
    $content = ob_get_clean();
    echo $content;
    wp_die();
}

add_action('wp_ajax_new_historical_data_parser', 'new_historical_data_parser_callback');

function new_historical_data_parser_callback()
{
    parse_str($_POST['data'], $info);

    global $wpdb;

    $wpdb->insert(
        $wpdb->prefix . 'paxs_hist_data',
        array(
            'id_registro' => $info['id_registro'],
            'ruta_vuelo' => $info['ruta_vuelo'],
            'fecha_vuelo' => $info['fecha_vuelo'],
            'aerolinea' => $info['aerolinea'],
            'nro_vuelo' => $info['nro_vuelo'],
            'nro_boleto' => $info['nro_boleto'],
            'reservacion' => $info['reservacion'],
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

    $content = replicateHistoricaltable($info['id_registro']);
    echo $content;
    wp_die();
}


add_action('wp_ajax_erase_historical_data_parser', 'erase_historical_data_parser_callback');

function erase_historical_data_parser_callback()
{
    $id = $_POST['id'];

    global $wpdb;

    $secondtable = $wpdb->prefix . 'paxs_hist_data';

    $queried_hist = (int) $wpdb->get_var("SELECT id_registro FROM $secondtable WHERE ID =  $id");
    $wpdb->delete($wpdb->prefix . 'paxs_hist_data',  array('ID' => $id));


    $content = replicateHistoricaltable($queried_hist);
    echo $content;

    wp_die();
}

function replicateHistoricaltable($id)
{
    global $wpdb;

    $secondtable = $wpdb->prefix . 'paxs_hist_data';
    $queried_hist = $wpdb->get_results("SELECT * FROM $secondtable WHERE id_registro = $id ORDER BY fecha_vuelo ASC");
    ob_start();
    $i = 1;
    foreach ($queried_hist as $item) {
        $number = ($i % 2 == 0) ? 'even' : 'odd'; ?>
<tr class="historical-item <?php echo $number; ?>">
    <td><?php echo $i; ?></td>
    <td><?php echo $item->ruta_vuelo; ?></td>
    <?php $fecha_vuelo = explode('-', $item->fecha_vuelo); ?>
    <td><?php echo $fecha_vuelo[2] . '-' . $fecha_vuelo[1] . '-' . $fecha_vuelo[0]; ?></td>
    <td><?php echo $item->aerolinea; ?></td>
    <td><?php echo $item->nro_vuelo; ?></td>
    <td><?php echo $item->nro_boleto; ?></td>
    <td><?php echo $item->reservacion; ?></td>
    <td>
        <a onclick="editHistorical(<?php echo $item->ID; ?>)" title="Editar Detalle"><span class="dashicons dashicons-edit-page"></span></a>
        <a onclick="eraseHistorical(<?php echo $item->ID; ?>)" title="Eliminar Detalle"><span class="dashicons dashicons-trash"></span></a>
    </td>
</tr>
<?php $i++;
    }

    $content = ob_get_clean();
    return $content;
}

add_action('wp_ajax_edit_historical_data_parser', 'edit_historical_data_parser_callback');

function edit_historical_data_parser_callback()
{
    global $wpdb;

    $id = $_POST['id'];

    $secondtable = $wpdb->prefix . 'paxs_hist_data';
    $queried_temp = $wpdb->get_results("SELECT * FROM $secondtable WHERE ID = $id");
    ob_start();
    $queried_hist = array_shift($queried_temp);
    ?>
<tr class="add-row">
    <td><input type="hidden" name="ID" value="<?php echo $queried_hist->ID; ?>"></td>
    <td><input type="text" name="ruta_vuelo" class="form-control" value="<?php echo $queried_hist->ruta_vuelo; ?>"></td>
    <td><input type="date" class="date-dynamic" name="fecha_vuelo" class="form-control" value="<?php echo $queried_hist->fecha_vuelo; ?>"></td>
    <td><input type="text" name="aerolinea" class="form-control" value="<?php echo $queried_hist->aerolinea; ?>"></td>
    <td><input type="text" name="nro_vuelo" class="form-control" value="<?php echo $queried_hist->nro_vuelo; ?>"></td>
    <td><input type="text" name="nro_boleto" class="form-control" value="<?php echo $queried_hist->nro_boleto; ?>"></td>
    <td><input type="text" name="reservacion" class="form-control" value="<?php echo $queried_hist->reservacion; ?>"></td>
    <td><button class="edit-btn"><span class="dashicons dashicons-yes"></span></button> <button class="cancel-btn"><span class="dashicons dashicons-no"></span></button></td>
</tr>

<?php
    $content = ob_get_clean();
    echo $content;
    wp_die();
}

add_action('wp_ajax_edit_new_historical_data_parser', 'edit_new_historical_data_parser_callback');
function edit_new_historical_data_parser_callback()
{
    parse_str($_POST['data'], $info);

    global $wpdb;

    $wpdb->update(
        $wpdb->prefix . 'paxs_hist_data',
        array(
            'ruta_vuelo' => $info['ruta_vuelo'],
            'fecha_vuelo' => $info['fecha_vuelo'],
            'aerolinea' => $info['aerolinea'],
            'nro_vuelo' => $info['nro_vuelo'],
            'nro_boleto' => $info['nro_boleto'],
            'reservacion' => $info['reservacion'],
        ),
        array('ID' => $info['ID']),
        array(
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s'
        ),
        array('%s')
    );

    $content = replicateHistoricaltable($info['id_registro']);
    echo $content;
    wp_die();
}