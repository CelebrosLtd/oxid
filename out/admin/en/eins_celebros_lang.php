<?php
/**
 *    This file is part of OXID eShop Community Edition.
 *
 *    OXID eShop Community Edition is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    OXID eShop Community Edition is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with OXID eShop Community Edition.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      http://www.oxid-esales.com
 * @package   lang
 * @copyright (C) OXID eSales AG 2003-2011
 * @version OXID eShop CE
 * @version   SVN: $Id: cust_lang.php 34120 2011-04-01 09:40:35Z juergen.busch $
 */

$sLangName  = "English";
// -------------------------------
// RESOURCE IDENTITFIER = STRING
// -------------------------------
$aLang = array(
'charset'                                  => 'ISO-8859-15',		
'EMAIL_PRICEALARM_CUSTOMER_PRICEALARMIN'   => 'Price Alert in ',
'EMAIL_PRICEALARM_CUSTOMER_HY'             => 'Hallo,',
'EMAIL_PRICEALARM_CUSTOMER_HAVEPRICEALARM' => 'we have a Price Alert in',
'EMAIL_PRICEALARM_CUSTOMER_ITEM1'          => 'The Product',
'EMAIL_PRICEALARM_CUSTOMER_ITEM2'          => "you're interested in and you offered a price of",
'EMAIL_PRICEALARM_CUSTOMER_ITEM3'          => 'is now available for',
'EMAIL_PRICEALARM_CUSTOMER_ITEM4'          => '!',
'EMAIL_PRICEALARM_CUSTOMER_CLICKHERE1'     => 'To get directly to the product please click ',
'EMAIL_PRICEALARM_CUSTOMER_CLICKHERE2'     => 'here',
'EMAIL_PRICEALARM_CUSTOMER_TEAM1'          => 'Your',
'EMAIL_PRICEALARM_CUSTOMER_TEAM2'          => 'Team',
'EMAIL_SENDEDNOW_HTML_ORDERSHIPPEDTO'      => "The order is shipped to:",
'EMAIL_SENDEDNOW_HTML_ORDERNOMBER'         => "Order No.:",
'EMAIL_SENDEDNOW_HTML_QUANTITY'            => "Quantity",
'EMAIL_SENDEDNOW_HTML_PRODUCT'             => "Product",
'EMAIL_SENDEDNOW_HTML_PRODUCTRATING'       => "Product Rating",
'EMAIL_SENDEDNOW_HTML_ARTNOMBER'           => "Art.No.:",
'EMAIL_SENDEDNOW_HTML_REVIEW'              => "review",
'EMAIL_SENDEDNOW_HTML_YUORTEAM1'           => "Your",
'EMAIL_SENDEDNOW_HTML_YUORTEAM2'           => "Team",
// ########### START - change for celebros interface ##########################		
// cel_salesperson backend module
'cel_salesperson' => 'Celebros settings',
'cel_salesperson_menuitem' => 'Shop Settings', 'cel_salesperson_menusubitem' => 'Salesperson',
'cel_SHOP_OPTIONS_GROUP_SALESPERSON_CONNECTION_SETTINGS'		=> 'Search Results Connection Settings',
'cel_SHOP_SETTINGS_SALESPERSON_SERVICE_HOST'				=> 'Host',
'cel_SHOP_SETTINGS_SALESPERSON_SERVICE_PORT'				=> 'Port',
'cel_SHOP_SETTINGS_SALESPERSON_SITE_KEY'				=> 'Site Key',
'cel_SHOP_SETTINGS_SALESPERSON_CUSTOMER_NAME'				=> 'Customer Name',

'cel_SHOP_OPTIONS_GROUP_SALESPERSON_MAPPINGS'				=> 'Data mapping',
'cel_SHOP_SETTINGS_SALESPERSON_SORTING_FIELDS_MAPPINGS'				=> 'Allowed Sorting Fields',
'cel_SHOP_SETTINGS_SALESPERSON_SORTING_ISNUMERIC_MAPPINGS'				=> 'Fields Numeric Sorting',
'cel_SHOP_SETTINGS_SALESPERSON_OXID_FIELD_NAME'				=> 'Oxid Field Name',
'cel_SHOP_SETTINGS_SALESPERSON_SALESPERSON_FIELD_NAME'				=> 'Salesperson Field Name',
'cel_SHOP_SETTINGS_SALESPERSON_INCLUDE'				=> 'Include in export',
'cel_SHOP_SETTINGS_SALESPERSON_IS_NUMERIC_SORT'				=> 'Sort Numerically',

'cel_SHOP_OPTIONS_GROUP_SALESPERSON_DEFAULT_SEARCH_PARAMETERS'				=> 'API default parameters',
'cel_SHOP_SETTINGS_SALESPERSON_ANSWER_ID'				=> 'Default Answer Id',
'cel_SHOP_SETTINGS_SALESPERSON_EFFECT_ON_SEARCH_PATH'				=> 'Default Answer Type',
'cel_SHOP_SETTINGS_SALESPERSON_PRICE_COLUM'				=> 'Default Price Column',
'cel_SHOP_SETTINGS_SALESPERSON_PAGE_SIZE'				=> 'Default number of products per page',
'cel_SHOP_SETTINGS_SALESPERSON_SORTING_FIELD'				=> 'Default Sorting Field',
'cel_SHOP_SETTINGS_SALESPERSON_NUMERIC_SORT'				=> 'Sort Numerically by Default',
'cel_SHOP_SETTINGS_SALESPERSON_NUMERIC_ASCENDING'				=> 'Sort Ascendingly by Default ',
'cel_SHOP_SETTINGS_SALESPERSON_SEARCH_PROFILE'				=> 'Default Search Profile',

'cel_SHOP_OPTIONS_GROUP_SALESPERSON_DISPLAY_SEARCH_RESULTS_SETTINGS'				=> 'Search Results Display Settings',
'cel_SHOP_SETTINGS_SALESPERSON_MAX_NONE_LEAD_QUESTIONS'				=> 'Max Non Lead Questions',
'cel_SHOP_SETTINGS_SALESPERSON_MAX_NONE_LEAD_ANSWERS'				=> 'Max Non Lead Answers',
'cel_SHOP_SETTINGS_SALESPERSON_DISPLAY_LEAD_ANSWERS'				=> 'Display Full List of Lead Answers',
'cel_SHOP_SETTINGS_SALESPERSON_MAX_LEAD_ANSWERS'				=> 'Max Lead Answers',
'cel_SHOP_SETTINGS_SALESPERSON_ALTERNATIVE_PRODUCTS_MSG'				=> 'Alternative Products Message',

'cel_SHOP_OPTIONS_GROUP_SALESPERSON_AUTOCOMPLETE'				=> 'Autocomplete Settings',
'cel_SHOP_SETTINGS_SALESPERSON_AUTOCOMPLETE_URL'				=> 'Autocomplete Service Url',

'cel_SHOP_OPTIONS_GROUP_SALESPERSON_ANALYTICS'				=> 'Analytics Settings',
'cel_SHOP_SETTINGS_SALESPERSON_ANALYTICS_URL'				=> 'Analytics Service Url',
'cel_SHOP_SETTINGS_SALESPERSON_ANALYTICS_CUSTOMER_ID'				=> 'Analytics Customer Id',
'cel_SHOP_SETTINGS_SALESPERSON_ANALYTICS_CUSTOMER_NAME'				=> 'Analytics Customer Name',

'cel_SHOP_OPTIONS_GROUP_SALESPERSON_FTP'				=> 'FTP Settings',
'cel_SHOP_SETTINGS_SALESPERSON_FTP_SERVER'				=> 'FTP Server Url',
'cel_SHOP_SETTINGS_SALESPERSON_FTP_USER'				=> 'FTP User',
'cel_SHOP_SETTINGS_SALESPERSON_FTP_PASSWORD'				=> 'FTP Paswword',
    'cel_HELP_SHOP_SETTINGS_SALESPERSON_SEARCH_PROFILE' => 'The selected search profil. Check the manual for more information.',
    
    'eins_celebros' => 'Celebros',
    'celebros_export' => 'Celebros Export',
    'celebros_synchro' => 'Synchronize data',
    'cel_SHOP_OPTIONS_GROUP_SALESPERSON_SYNCHRO_SETTINGS' => 'Data synchro. settings',
    'cel_SHOP_SETTINGS_SALESPERSON_SYNCHRO_TIMEINTERVAL' => 'Interval of synchronization (in min.)',
    'cel_SHOP_SETTINGS_SALESPERSON_CATEGORY_SEARCH_SETTINGS' => 'Category search settings',
    'cel_SHOP_SETTINGS_SALESPERSON_CATEGORY_SEARCH_SETTINGS_OFF' => 'Deactivated',
    'cel_SHOP_SETTINGS_SALESPERSON_CATEGORY_SEARCH_SETTINGS_CATEGORY' => 'Category search',
    'cel_SHOP_SETTINGS_SALESPERSON_CATEGORY_SEARCH_SETTINGS_FULL_CATEGORY' => 'Full category search',
    'cel_SHOP_SETTINGS_SALESPERSON_CATEGORY_SEARCH_SETTINGS_CATEGORY_LEGEND' => 'Mode of category search',
    'cel_SHOP_OPTIONS_GROUP_SALESPERSON_LIVESIGHT_OPTIONS' => 'LiveSight settings',
    'cel_SHOP_SETTINGS_SALESPERSON_LIVESIGHT_ACTIVATE'  => 'Activate LiveSight',
    'cel_HELP_SHOP_SETTINGS_SALESPERSON_SEARCH_PROFILE' => 'Selected search profile. For more information have a look at the documentation.',
    'cel_SHOP_SETTINGS_SALESPERSON_SERVICE_PORT'				=> 'Port',
    'celebros_settings' => 'Celebros settings',
    'cel_SHOP_SETTINGS_SALESPERSON_ANALYTICS_ACTIVATE' => 'activate Celebros Analytics',
    'cel_RESET_MAPPING' => 'Reset settings'
// ########### END - change for celebros interface ##########################
);

/*
[{ oxmultilang ident="GENERAL_YOUWANTTODELETE" }]
*/
