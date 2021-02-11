<?php

function paxs_data_page_callback()
{ ?>

    <div class="paxs_custom_options-header">
        <img src="<?php echo plugins_url('img/logo.png', __DIR__); ?>" alt="Bandes" class="logo-header" />
    </div>
    <div class="pass_custom_options_title">
        <h1><?php echo get_admin_page_title(); ?></h1>
    </div>
    <div class="paxs_custom_options-content">
        <table id="paxsTables">
            <thead>
                <tr>
                    <th>Apellidos</th>
                    <th>Nombres</th>
                    <th>Cédula</th>
                    <th>Pasaporte</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Fecha de Vencimiento</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
<?php }
