<?php

/*
 *  Developed by webfrisch.de
 *  Author: Lukas Dierks <lukas.dierks at webfrisch.de>
 *  Date: Jun 5, 2013
 */

class eins_csv_celebros_seo_export extends eins_csv_export {

    protected static $_oDBConn;

    protected function _getDataMap($iLimit, $iOffset, $aParams) {
        $soxId = oxConfig::getInstance()->getActiveShop()->getId();
        
        $aDataMap = array(
            0 => array(
                'select' => "SELECT * FROM oxseo WHERE oxshopid='" . $soxId . "' AND oxexpired=0 LIMIT " . $iLimit . " OFFSET " . $iOffset,
                'content' => array(
                    0 => array('field' => 'OXOBJECTID'),
                    1 => array('field' => 'OXIDENT'),
                    2 => array('field' => 'OXSHOPID'),
                    3 => array('field' => 'OXLANG'),
                    4 => array('field' => 'OXSTDURL'),
                    5 => array('field' => 'OXSEOURL'),
                    6 => array('field' => 'OXTYPE'),
                    7 => array('field' => 'OXFIXED'),
                    8 => array('field' => 'OXEXPIRED'),
                    9 => array('field' => 'OXPARAMS'),
                    10 => array('field' => 'OXTIMESTAMP')
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
        if (!eins_csv_celebros_seo_export::$_oDBConn)
            eins_csv_celebros_seo_export::$_oDBConn = oxDb::getDb();

        return eins_csv_celebros_seo_export::$_oDBConn;
    }

    public function getDescription() {
        return null;
    }

    public function getTitle() {
        return "Celebros SEO Data Export";
    }

    public function getId() {
        return "celebros_seo_export";
    }

    public function _getDelimiter() {
        return "|";
    }

    public function _getFieldWrapper() {
        return '';
    }

    public function _getPluginClass() {
        return 'eins_csv_celebros_seo_export';
    }

    public function getRSSize($aParams) {
        $oDb = eins_csv_celebros_seo_export::getDb();
        $oRs = $oDb->Execute("SELECT COUNT(oxobjectid) FROM oxseo WHERE oxshopid='" . $soxId . "' AND oxexpired=0");
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
        return 'oxseo.csv';
    }
    
    public function getHeaderLine() {
        return "OXOBJECTID|OXIDENT|OXSHOPID|OXLANG|OXSTDURL|OXSEOURL|OXTYPE|OXFIXED|OXEXPIRED|OXPARAMS|OXTIMESTAMP\n";
    }
}

?>