<?php

/*
 *  Developed by webfrisch.de
 *  Author: Lukas Dierks <lukas.dierks at webfrisch.de>
 *  Date: Jun 5, 2013
 */

class eins_csv_celebros_manufacturer_export extends eins_csv_export {

    protected static $_oDBConn;

    protected function _getDataMap($iLimit, $iOffset, $aParams) {
        $soxId = oxConfig::getInstance()->getActiveShop()->getId();
        
        $aDataMap = array(
            0 => array(
                'select' => "SELECT * FROM oxmanufacturers WHERE oxshopid='" . $soxId . "' LIMIT " . $iLimit . " OFFSET " . $iOffset,
                'content' => array(
                    0 => array('field' => 'OXID'),
                    1 => array('field' => 'OXSHOPID'),
                    2 => array('field' => 'OXACTIVE'),
                    3 => array('field' => 'OXICON'),
                    4 => array('field' => 'OXTITLE'),
                    5 => array('field' => 'OXSHORTDESC'),
                    6 => array('field' => 'OXTITLE_1'),
                    7 => array('field' => 'OXSHORTDESC_1'),
                    8 => array('field' => 'OXTITLE_2'),
                    9 => array('field' => 'OXSHORTDESC_2'),
                    10 => array('field' => 'OXTITLE_3'),
                    11 => array('field' => 'OXSHORTDESC_3'),
                    12 => array('field' => 'OXSHOWSUFFIX'),
                    13 => array('field' => 'OXTIMESTAMP')
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
        if (!eins_csv_celebros_manufacturer_export::$_oDBConn)
            eins_csv_celebros_manufacturer_export::$_oDBConn = oxDb::getDb();

        return eins_csv_celebros_manufacturer_export::$_oDBConn;
    }

    public function getDescription() {
        return null;
    }

    public function getTitle() {
        return "Celebros Manufacturer Data Export";
    }

    public function getId() {
        return "celebros_manufacturer_export";
    }

    public function _getDelimiter() {
        return "|";
    }

    public function _getFieldWrapper() {
        return '';
    }

    public function _getPluginClass() {
        return 'eins_csv_celebros_manufacturer_export';
    }

    public function getRSSize($aParams) {
        $oDb = eins_csv_celebros_manufacturer_export::getDb();
        $oRs = $oDb->Execute("SELECT COUNT(oxid) FROM oxmanufacturers");
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
        return 'oxmanufacturers.csv';
    }

    public function getHeaderLine() {
        return "OXID|OXSHOPID|OXACTIVE|OXICON|OXTITLE|OXSHORTDESC|OXTITLE_1|OXSHORTDESC_1|OXTITLE_2|OXSHORTDESC_2|OXTITLE_3|OXSHORTDESC_3|OXSHOWSUFFIX|OXTIMESTAMP\n";
    }
}

?>
