<?php

/*
 *  Developed by webfrisch.de
 *  Author: Lukas Dierks <lukas.dierks at webfrisch.de>
 *  Date: Jun 5, 2013
 */

class eins_csv_celebros_artextends_export extends eins_csv_export {

    protected static $_oDBConn;

    protected function _getDataMap($iLimit, $iOffset, $aParams) {
        $soxId = oxConfig::getInstance()->getActiveShop()->getId();
        
        $aDataMap = array(
            0 => array(
                'select' => "SELECT * FROM oxartextends LIMIT " . $iLimit . " OFFSET " . $iOffset,
                'content' => array(
                    0 => array('field' => 'OXID'),
                    1 => array('field' => 'OXLONGDESC'),
                    2 => array('field' => 'OXLONGDESC_1'),
                    3 => array('field' => 'OXLONGDESC_2'),
                    4 => array('field' => 'OXLONGDESC_3'),
                    5 => array('field' => 'OXTAGS'),
                    6 => array('field' => 'OXTAGS_1'),
                    7 => array('field' => 'OXTAGS_2'),
                    8 => array('field' => 'OXTAGS_3'),
                    9 => array('field' => 'OXTIMESTAMP')
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
        if (!eins_csv_celebros_artextends_export::$_oDBConn)
            eins_csv_celebros_artextends_export::$_oDBConn = oxDb::getDb();

        return eins_csv_celebros_artextends_export::$_oDBConn;
    }

    public function getDescription() {
        return null;
    }

    public function getTitle() {
        return "Celebros Article Extension Data Export";
    }

    public function getId() {
        return "celebros_artextends_export";
    }

    public function _getDelimiter() {
        return "|";
    }

    public function _getFieldWrapper() {
        return '';
    }

    public function _getPluginClass() {
        return 'eins_csv_celebros_artextends_export';
    }

    public function getRSSize($aParams) {
        $oDb = eins_csv_celebros_artextends_export::getDb();
        $oRs = $oDb->Execute("SELECT COUNT(oxid) FROM oxartextends");
        return $oRs->fields[0];
    }

    public function getExportParamInfo() {
        return array();
    }

    public function getParentPlugin() {
        return 'celebros_export';
    }
    
    public function getFixedOutputFileName() {
        return 'oxartextends.csv';
    }
    
    public function getHeaderLine() {
        return "OXID|OXLONGDESC|OXLONGDESC_1|OXLONGDESC_2|OXLONGDESC_3|OXTAGS|OXTAGS_1|OXTAGS_2|OXTAGS_3|OXTIMESTAMP\n";
    }
}

?>