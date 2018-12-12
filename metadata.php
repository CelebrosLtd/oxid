<?php
use Celebros\Conversionpro\Controller\Admin\Settings;
use Celebros\Conversionpro\Controller\Admin\Export;
use Celebros\Conversionpro\Controller\Admin\Synchro;
use Celebros\Conversionpro\Controller\SearchController;
use Celebros\Conversionpro\Controller\ListController;
use Celebros\Conversionpro\Controller\DetailsController;
use Celebros\Conversionpro\Core\OxShop;
use Celebros\Conversionpro\Core\GlobalViewTools;

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
    'version' => '3.0.2',
    'author' => 'Celebros',
    'thumbnail'   => 'logo.png',
    'email'       => 'support@celebros.com',
    'url'         => 'www.celebros.com',

    'extend' => array(
        'search' => SearchController::class,
        'alist' => ListController::class,
        'oxshop' => Oxshop::class,
        'details' => DetailsController::class,
        'oxviewconfig' => GlobalViewTools::class
    ),
    
    'controllers' => array(
        'celebros_conversionpro_admin_settings' => Settings::class,
        'celebros_conversionpro_admin_export' => Export::class,
        'celebros_conversionpro_admin_synchro' => Synchro::class
    ),    

    'templates' => array(
        'celebros_settings.tpl' => 'celebros/conversionpro/views/admin/tpl/celebros_settings.tpl',
        'celebros_synchro.tpl'  => 'celebros/conversionpro/views/admin/tpl/celebros_synchro.tpl',
        'celebros_export_overview.tpl' => 'celebros/conversionpro/views/admin/tpl/export_overview.tpl',
        'celebros_import_overview.tpl' => 'celebros/conversionpro/views/admin/tpl/import_overview.tpl',
        'celebros_qwiser.tpl' => 'celebros/conversionpro/views/azure/tpl/celebros_qwiser.tpl',
        'cel_refinmentstree.tpl' => 'celebros/conversionpro/views/azure/tpl/cel_refinmentstree.tpl',
        'celebros_listlocator.tpl' => 'celebros/conversionpro/views/azure/tpl/celebros_listlocator.tpl'        
    ),
    
    'blocks' => array(
        array('template' => 'page/checkout/thankyou.tpl', 'block' => 'checkout_thankyou_partners', 'file' => 'livesight/livesight_success.tpl'),
        array('template' => 'layout/base.tpl', 'block' => 'base_js', 'file' => 'livesight/livesight_page.tpl'),
        array('template' => 'layout/base.tpl', 'block' => 'head_css', 'file' => 'views/azure/tpl/blocks/head_css.tpl'),
        array('template' => 'layout/base.tpl', 'block' => 'base_js', 'file' => 'analytics/celebros_analytics_search.tpl'),
        array('template' => 'layout/sidebar.tpl', 'block' => 'sidebar_categoriestree', 'file' => 'views/azure/tpl/blocks/eins_celebros_sidebar.tpl'),
        array('template' => 'widget/header/search.tpl', 'block' => 'header_search_field', 'file' => 'views/azure/tpl/blocks/celebros_ac.tpl')
    )
);