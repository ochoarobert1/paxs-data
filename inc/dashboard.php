<?php
/* --------------------------------------------------------------
CUSTOM AREA FOR DASHBOARD
-------------------------------------------------------------- */
function paxs_dashboard_page_callback()
{ ?>

    <div class="paxs_custom_options-header">
        <img src="<?php echo plugins_url('img/logo.png', __DIR__); ?>" alt="Bandes" class="logo-header" />
    </div>
    <div class="pass_custom_options_title">
        <h1><?php echo get_admin_page_title(); ?></h1>
    </div>
    <div class="paxs_custom_options-content">

    </div>
<?php }
