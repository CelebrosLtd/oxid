<?php
use Celebros\Conversionpro\Controller\Admin\Settings;
use Celebros\Conversionpro\Controller\Admin\Export;
use Celebros\Conversionpro\Controller\Admin\Synchro;

/**
 * Metadata version
 */
$sMetadataVersion = '2.0';

/**
 * Module information
 */
$aModule = array(
    'id' => 'celebros_conversionpro',
    'title' => 'Celebros ConversionPro',
    'description' => '',
    'version' => '3.0.0',
    'author' => 'Celebros',
    'email'       => 'support@celebros.com',
    'url'         => 'www.celebros.com',

    'controllers' => array(
        // klarna admin
        'celebros_conversionpro_admin_settings' => Settings::class,
        'celebros_conversionpro_admin_export' => Export::class,
        'celebros_conversionpro_admin_synchro' => Synchro::class //,
        // controllers
        //'KlarnaExpress'          => KlarnaExpressController::class,
    ),    
    
    'extend' => array(
        /*'oxmaintenance' => 'celebros/conversionpro/maintenance/celebros_maintenance',
        'search' => 'celebros/conversionpro/controllers/celebros_search',
        'details' => 'celebros/conversionpro/controllers/celebros_details',
        'alist' => 'celebros/conversionpro/controllers/celebros_list',
        'oxviewconfig' => 'celebros/conversionpro/controllers/celebros_global_view_tools',
        'oxshop' => 'celebros/conversionpro/core/eins_celebros_oxshop'*/
    ),
    /*'files' => array(
        'celebros_settings' => 'celebros/conversionpro/controllers/admin/celebros_settings.php',
        'celebros_synchro' => 'celebros/conversionpro/controllers/admin/celebros_synchro.php',
        'eins_csv_interpret' => 'celebros/conversionpro/core/eins_csv_interpret.php',
        'eins_import_overview' => 'celebros/conversionpro/controllers/admin/eins_import_overview.php',
        'eins_export_overview' => 'celebros/conversionpro/controllers/admin/eins_export_overview.php',
        'eins_csv_import' => 'celebros/conversionpro/core/eins_csv_import.php',
        'eins_csv_export' => 'celebros/conversionpro/core/eins_csv_export.php',
        'celebros_upload' => 'celebros/conversionpro/core/celebros_upload.php',
        'eins_csv_celebros_export' => 'celebros/conversionpro/plugins/export/eins_csv_celebros_export.php',
        'cel_qwiserapi' => 'celebros/conversionpro/core/cel_qwiserapi.php',
        'cel_qwiserparser' => 'celebros/conversionpro/core/cel_qwiserparser.php',
        'pclzip' => 'celebros/conversionpro/lib/pclzip.lib.php',
        'eins_celebros_uninstall' => 'celebros/conversionpro/install/eins_celebros_uninstall.php'
    ),*/
    'templates' => array(
        'celebros_settings.tpl' => 'celebros/conversionpro/views/admin/tpl/celebros_settings.tpl',
        'eins_import_overview.tpl' => 'celebros/conversionpro/views/admin/tpl/eins_import_overview.tpl',
        'eins_export_overview.tpl' => 'celebros/conversionpro/views/admin/tpl/eins_export_overview.tpl',
        'celebros_synchro.tpl' => 'celebros/conversionpro/views/admin/tpl/celebros_synchro.tpl',
        'celebros_qwiser.tpl' => 'celebros/conversionpro/views/azure/tpl/celebros_qwiser.tpl',
        'cel_refinmentstree.tpl' => 'celebros/conversionpro/views/azure/tpl/cel_refinmentstree.tpl',
        'eins_celebros_listlocator.tpl' => 'celebros/conversionpro/views/azure/tpl/eins_celebros_listlocator.tpl'
    ),
    'blocks' => array(
        array('template' => 'page/checkout/thankyou.tpl', 'block' => 'checkout_thankyou_partners', 'file' => 'livesight/livesight_success.tpl'),
        array('template' => 'layout/base.tpl', 'block' => 'base_js', 'file' => 'livesight/livesight_page.tpl'),
        array('template' => 'layout/base.tpl', 'block' => 'head_css', 'file' => 'views/azure/tpl/blocks/head_css.tpl'),
        array('template' => 'layout/base.tpl', 'block' => 'base_js', 'file' => 'analytics/celebros_analytics_search.tpl'),
        array('template' => 'layout/sidebar.tpl', 'block' => 'sidebar_categoriestree', 'file' => 'views/azure/tpl/blocks/eins_celebros_sidebar.tpl'),
        array('template' => 'widget/header/search.tpl', 'block' => 'header_search_field', 'file' => 'views/azure/tpl/blocks/eins_celebros_search_field.tpl'),
    ),
    /*'events' => array(
        'onDeactivate' => 'eins_celebros_uninstall::onDeactivate'
    )*/
);