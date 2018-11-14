<?php

/*
 *  Developed by webfrisch.de
 *  Author: Lukas Dierks <lukas.dierks at webfrisch.de>
 *  Date: Jun 5, 2013
 */

class eins_csv_celebros_object2attribute_export extends eins_csv_export {

    protected static $_oDBConn;

    protected function _getDataMap($iLimit, $iOffset, $aParams) {
        $soxId = oxConfig::getInstance()->getActiveShop()->getId();
        
        $aDataMap = array(
            0 => array(
                'select' => "SELECT * FROM oxobject2attribute LEFT JOIN oxattribute ON oxattribute.oxid=oxobject2attribute.oxattrid WHERE oxattribute.oxshopid='" . $soxId . "' LIMIT " . $iLimit . " OFFSET " . $iOffset,
                'content' => array(
                    0 => array('field' => 'OXID'),
                    1 => array('field' => 'OXOBJECTID'),
                    2 => array('field' => 'OXATTRID'),
                    3 => array('field' => 'OXVALUE'),
                    4 => array('field' => 'OXPOS'),
                    5 => array('field' => 'OXVALUE_1'),
                    6 => array('field' => 'OXVALUE_2'),
                    7 => array('field' => 'OXVALUE_3'),
                    8 => array('field' => 'OXTIMESTAMP')
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
        if (!eins_csv_celebros_object2attribute_export::$_oDBConn)
            eins_csv_celebros_object2attribute_export::$_oDBConn = oxDb::getDb();

        return eins_csv_celebros_object2attribute_export::$_oDBConn;
    }

    public function getDescription() {
        return null;
    }

    public function getTitle() {
        return "Celebros O2A Data Export";
    }

    public function getId() {
        return "celebros_object2attribute_export";
    }

    public function _getDelimiter() {
        return "|";
    }

    public function _getFieldWrapper() {
        return '';
    }

    public function _getPluginClass() {
        return 'eins_csv_celebros_object2attribute_export';
    }

    public function getRSSize($aParams) {
        $oDb = eins_csv_celebros_object2attribute_export::getDb();
        $oRs = $oDb->Execute("SELECT COUNT(oxid) FROM oxobject2attribute");
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
        return 'oxobject2attribute.csv';
    }

    public function getHeaderLine() {
        return "OXID|OXOBJECTID|OXATTRID|OXVALUE|OXPOS|OXVALUE_1|OXVALUE_2|OXVALUE_3|OXTIMESTAMP\n";
    }
}

?>
