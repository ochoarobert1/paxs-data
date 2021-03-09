<?php

use Dompdf\Dompdf;
use Dompdf\Options;

function paxs_reports_page_callback()
{
?>
    <div class="pass_custom_options_title">
        <h1><?php echo get_admin_page_title(); ?></h1>
    </div>

    <div class="paxs_custom_options-content">
        <div class="paxs-reports-wrapper">
            <div class="paxs-custom-reportes-container">
                <div class="paxs-report-item-selector">
                    <h2>Reportes por Viajes</h2>
                    <form id="reportsByDate">
                        <div class="input-group">
                            <input type="date" name="begin" class="form-control custom-form-control" />
                            <small class="begin-error hidden">Debe seleccionar una fecha</small>
                        </div>
                        <div class="input-group">
                            <input type="date" name="end" class="form-control custom-form-control" />
                            <small class="end-error hidden">Debe seleccionar una fecha</small>
                        </div>
                        <button id="reportsDateBtn" class="btn btn-submit">Generar Reporte</button>
                        <div class="modal-loader modal-hidden">
                            <div class="loader-css">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="paxs-report-item-selector">
                    <h2>Reportes por Numero de Vuelo</h2>
                    <form id="reportsByFlight">
                        <div class="input-group">
                            <input type="text" name="nro_vuelo" class="form-control custom-form-control" placeholder="Ingresa un número de vuelo" />
                            <small class="nro_vuelo-error hidden">Debe ingresar un número de vuelo</small>
                        </div>
                        <button id="reportsFlightBtn" class="btn btn-submit">Generar Reporte</button>
                        <div class="modal-loader modal-hidden">
                            <div class="loader-css">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="paxs-report-item-selector">
                    <h2>Reportes por Usuario</h2>
                    <form id="reportsByUser">
                        <div class="input-group">
                            <input type="text" name="nombre" class="form-control custom-form-control" placeholder="Ingresa un nombre o apellido" />
                            <small class="nombre-error hidden">Debe ingresar un nombre o apellido</small>
                        </div>
                        <div class="input-group">
                            <input type="text" name="ci_pass" class="form-control custom-form-control" placeholder="Ingresa un pasaporte o cédula" />
                            <small class="ci_pass-error hidden">Debe ingresar un pasaporte o cédula</small>
                        </div>
                        <button id="reportsUserBtn" class="btn btn-submit">Generar Reporte</button>
                        <div class="modal-loader modal-hidden">
                            <div class="loader-css">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="paxs-report-generator-container"></div>
        </div>
    </div>
    <div class="modal-container modal-hidden">
        <div class="modal-content modal-content-hidden"></div>
    </div>
<?php
}

add_action('wp_ajax_reports_date', 'reports_date_callback');
function reports_date_callback()
{
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }
    parse_str($_POST['data'], $info);

    $begin = $info['begin'];
    $end = $info['end'];

    global $wpdb;
    $maintable = $wpdb->prefix . 'paxs_pass_data';
    $secondtable = $wpdb->prefix . 'paxs_hist_data';

    $start_dt = new DateTime($begin . ' 00:00:00');
    $s = $start_dt->format('Y-m-d H:i:s');

    $end_dt = new DateTime($end . ' 23:59:59');
    $e = $end_dt->format('Y-m-d H:i:s');

    $sql = $wpdb->prepare("SELECT * FROM $secondtable LEFT JOIN $maintable ON $maintable.id = $secondtable.id_registro WHERE CAST(fecha_vuelo AS DATE) BETWEEN %s AND %s ORDER BY fecha_vuelo ASC", $s, $e);

    $queried_hist = $wpdb->get_results($sql);

    ob_start();
    //require_once('template-reports.php');
?>
    <h1 style="font-weight: 300;">Reporte del Sistema</h1>
    <hr>
    <div class="paxs-reports-range">
        <h3>Rango de Búsqueda:</h3>
        <strong>Desde:</strong> <?php echo $start_dt->format('d-m-Y'); ?> | <strong>Hasta: </strong> <?php echo $end_dt->format('d-m-Y'); ?>
    </div>
    <hr>
    <table cellpadding="0" cellspacing="0" class="custom-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Ruta</th>
                <th>Fecha de Vuelo</th>
                <th>Aerolínea</th>
                <th>Nro. Vuelo</th>
                <th>Nro. Boleto</th>
                <th>Nombre</th>
                <th>Cédula</th>
                <th>Pasaporte</th>
            </tr>
        </thead>
        <tbody>

            <?php $i = 1; ?>
            <?php foreach ($queried_hist as $item) { ?>
                <?php $class = ($i % 2 == 0) ? 'even' : 'odd'; ?>
                <tr class="<?php echo $class; ?>">
                    <td><?php echo $i; ?></td>
                    <td><?php echo $item->ruta_vuelo; ?></td>
                    <td>
                        <?php $tempdate = new DateTime($item->fecha_vuelo . ' 00:00:00'); ?>
                        <?php echo $tempdate->format('d-m-Y'); ?>
                    </td>
                    <td><?php echo $item->aerolinea; ?></td>
                    <td><?php echo $item->nro_vuelo; ?></td>
                    <td><?php echo $item->nro_boleto; ?></td>
                    <td><?php echo $item->nombre; ?> <?php echo $item->apellido; ?></td>
                    <td><?php echo $item->cedula; ?></td>
                    <td><?php echo $item->pasaporte; ?></td>
                </tr>
            <?php $i++;
            } ?>

        </tbody>
    </table>

    <div class="paxs-reports-range-footer">
        <hr>
        <h6>Total Resultados: <?php echo count($queried_hist); ?></h6>
    </div>
<?php

    $content = ob_get_clean();

    echo $content;

    /* DOMPDF */
    /*
    require_once('vendor/autoload.php');

    $options = new Options();
    $options->set('defaultFont', 'Helvetica');
    $dompdf = new Dompdf($options);
    $dompdf->set_paper("Letter", "portrait");
    $dompdf->set_option('isHtml5ParserEnabled', true);

    ob_start();
    require_once('template-reports.php');
    $html = ob_get_clean();

    // Cargamos el contenido HTML.
    $dompdf->load_html(utf8_decode($html));

    

    // Renderizamos el documento PDF.
    $dompdf->render();

    // Enviamos el fichero PDF al navegador.
    $dompdf->stream("file.pdf");
    //$content = $dompdf->output();
    */



    wp_die();
}

add_action('wp_ajax_reports_user', 'reports_user_callback');
function reports_user_callback()
{
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }
    parse_str($_POST['data'], $info);

    if ($info['nombre'] != '') {
        $name = $info['nombre'];
        $search_name = 'nombre LIKE %' . $name . '% OR';
        $search_apellido = 'apellido LIKE %' . $name . '% OR';
    } else {
        $search_name = '';
        $search_apellido = '';
    }

    if ($info['ci_pass']) {
        $pass = $info['ci_pass'];
        $search_name = 'nombre LIKE %' . $name . '% OR';
        $search_apellido = 'apellido LIKE %' . $name . '% OR';
    }

    global $wpdb;
    $maintable = $wpdb->prefix . 'paxs_pass_data';
    $secondtable = $wpdb->prefix . 'paxs_hist_data';


    $queried_hist = $wpdb->get_results("SELECT * FROM $maintable LEFT JOIN $secondtable ON $maintable.id = $secondtable.id_registro WHERE $search_name $search_apellido OR cedula LIKE %$pass% OR pasaporte LIKE %$pass%");

    $sql = "SELECT * FROM $maintable LEFT JOIN $secondtable ON $maintable.id = $secondtable.id_registro WHERE nombre LIKE %$name% OR apellido LIKE %$name% OR cedula LIKE %$pass% OR pasaporte LIKE %$pass%";

    var_dump($sql);

    var_dump($queried_hist);


    wp_die();
}

add_action('wp_ajax_reports_flight', 'reports_flight_callback');
function reports_flight_callback()
{
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }
    parse_str($_POST['data'], $info);

    $nro_vuelo = $info['nro_vuelo'];

    global $wpdb;
    $maintable = $wpdb->prefix . 'paxs_pass_data';
    $secondtable = $wpdb->prefix . 'paxs_hist_data';

    $sql = $wpdb->prepare("SELECT * FROM $secondtable LEFT JOIN $maintable ON $maintable.id = $secondtable.id_registro WHERE nro_vuelo = %s ORDER BY fecha_vuelo ASC", $nro_vuelo);

    $queried_hist = $wpdb->get_results($sql);

    var_dump($queried_hist);


    wp_die();
}