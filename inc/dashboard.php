<?php
/* --------------------------------------------------------------
CUSTOM AREA FOR DASHBOARD
-------------------------------------------------------------- */
function paxs_dashboard_page_callback()
{ /*?>

<div class="paxs_custom_options-header">
    <img src="<?php echo plugins_url('img/logo.png', __DIR__); ?>" alt="Bandes" class="logo-header" />
</div>
*/ ?>
<div class="pass_custom_options_title">
    <h1><?php echo get_admin_page_title(); ?></h1>
</div>

<div class="paxs_custom_options-content">
    <div class="dashboard-container">
        <div class="dashboard-item">
            <a href="<?php echo admin_url('admin.php?page=paxs_main_data'); ?>" class="dashboard-item-wrapper">
                <div class="paxs-image-wrapper">
                    <img src="<?php echo plugins_url('img/registries.png', __DIR__); ?>" alt="Ver Registros" class="img-dashboard" />
                </div>
                <h3>Registros</h3>
            </a>
        </div>
        <div class="dashboard-item">
            <a href="<?php echo admin_url('admin.php?page=paxs_reports_data'); ?>" class="dashboard-item-wrapper">
                <div class="paxs-image-wrapper">
                    <img src="<?php echo plugins_url('img/reports.png', __DIR__); ?>" alt="Ver Registros" class="img-dashboard" />
                </div>
                <h3>Reportes</h3>
            </a>
        </div>
    </div>
</div>
<?php }