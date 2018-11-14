<?php

/*
 *  Developed by webfrisch.de
 *  Author: Lukas Dierks <lukas.dierks at webfrisch.de>
 *  Date: Jun 5, 2013
 */

class eins_csv_celebros_category_export extends eins_csv_export {

    protected static $_oDBConn;

    protected function _getDataMap($iLimit, $iOffset, $aParams) {
        $soxId = oxConfig::getInstance()->getActiveShop()->getId();
        
        $aDataMap = array(
            0 => array(
                'select' => "SELECT * FROM oxcategories WHERE oxshopid='" . $soxId . "' LIMIT " . $iLimit . " OFFSET " . $iOffset,
                'content' => array(
                    0 => array('field' => 'OXID'),
                    1 => array('field' => 'OXPARENTID'),
                    2 => array('field' => 'OXLEFT'),
                    3 => array('field' => 'OXRIGHT'),
                    4 => array('field' => 'OXROOTID'),
                    5 => array('field' => 'OXSORT'),
                    6 => array('field' => 'OXACTIVE'),
                    7 => array('field' => 'OXHIDDEN'),
                    8 => array('field' => 'OXSHOPID'),
                    9 => array('field' => 'OXTITLE'),
                    10 => array('field' => 'OXDESC'),
                    12 => array('field' => 'OXLONGDESC'),
                    13 => array('field' => 'OXTHUMB'),
                    14 => array('field' => 'OXTHUMB_1'),
                    15 => array('field' => 'OXTHUMB_2'),
                    16 => array('field' => 'OXTHUMB_3'),
                    17 => array('field' => 'OXEXTLINK'),
                    18 => array('field' => 'OXTEMPLATE'),
                    19 => array('field' => 'OXDEFSORT'),
                    20 => array('field' => 'OXDEFSORTMODE'),
                    21 => array('field' => 'OXPRICEFROM'),
                    22 => array('field' => 'OXPRICETO'),
                    23 => array('field' => 'OXACTIVE_1'),
                    24 => array('field' => 'OXTITLE_1'),
                    25 => array('field' => 'OXDESC_1'),
                    26 => array('field' => 'OXLONGDESC_1'),
                    27 => array('field' => 'OXACTIVE_2'),
                    28 => array('field' => 'OXTITLE_2'),
                    29 => array('field' => 'OXDESC_2'),
                    30 => array('field' => 'OXLONGDESC_2'),
                    31 => array('field' => 'OXACTIVE_3'),
                    32 => array('field' => 'OXTITLE_3'),
                    33 => array('field' => 'OXDESC_3'),
                    34 => array('field' => 'OXLONGDESC_3'),
                    35 => array('field' => 'OXICON'),
                    36 => array('field' => 'OXPROMOICON'),
                    37 => array('field' => 'OXVAT'),
                    38 => array('field' => 'OXSKIPDISCOUNTS'),
                    39 => array('field' => 'OXSHOWSUFFIX'),
                    40 => array('field' => 'OXTIMESTAMP')
                )
            )
        );

        return $aDataMap;
    }

    public static function stripHTMLTags($sString) {
        return preg_replace("/<[^>]*>/", " ", $sString);
    }

    public static function replaceDelimiter($sString) {
        return str_replace(" ", "", str_replace("|", ",", $sString));
    }

    public static function getDb() {
        if (!eins_csv_celebros_category_export::$_oDBConn)
            eins_csv_celebros_category_export::$_oDBConn = oxDb::getDb();

        return eins_csv_celebros_category_export::$_oDBConn;
    }

    public function getDescription() {
        return null;
    }

    public function getTitle() {
        return "Celebros Category Data Export";
    }

    public function getId() {
        return "celebros_category_export";
    }

    public function _getDelimiter() {
        return "|";
    }

    public function _getFieldWrapper() {
        return '';
    }

    public function _getPluginClass() {
        return 'eins_csv_celebros_category_export';
    }

    public function getRSSize($aParams) {
        $oDb = eins_csv_celebros_category_export::getDb();
        $oRs = $oDb->Execute("SELECT COUNT(oxid) FROM oxcategory");
        return $oRs->fields[0];
    }

    public function getExportParamInfo() {
        return array();
    }

    public function getParentPlugin() {
        return 'celebros_export';
    }

    public function getFixedOutputFileName() {
//        $soxId = oxConfig::getInstance()->getActiveShop()->getId();
        return 'oxcategories.csv';
    }
    
    public function getHeaderLine() {
        return "OXID|OXPARENTID|OXLEFT|OXRIGHT|OXROOTID|OXSORT|OXACTIVE|OXHIDDEN|OXSHOPID|OXTITLE|OXDESC|OXLONGDESC|OXTHUMB|OXTHUMB_1|OXTHUMB_2|OXTHUMB_3|OXEXTLINK|OXTEMPLATE|OXDEFSORT|OXDEFSORTMODE|OXPRICEFROM|OXPRICETO|OXACTIVE_1|OXTITLE_1|OXDESC_1|OXLONGDESC_1|OXACTIVE_2|OXTITLE_2|OXDESC_2|OXLONGDESC_2|OXACTIVE_3|OXTITLE_3|OXDESC_3|OXLONGDESC_3|OXICON|OXPROMOICON|OXVAT|OXSKIPDISCOUNTS|OXSHOWSUFFIX|OXTIMESTAMP\n";
    }
}

?>
