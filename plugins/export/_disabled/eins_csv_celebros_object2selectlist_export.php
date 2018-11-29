<?php

/*
 *  Developed by webfrisch.de
 *  Author: Lukas Dierks <lukas.dierks at webfrisch.de>
 *  Date: Jun 5, 2013
 */

class eins_csv_celebros_object2selectlist_export extends \Celebros\Conversionpro\Core\CsvExport {

    protected static $_oDBConn;

    protected function _getDataMap($iLimit, $iOffset, $aParams) {
        $soxId = oxConfig::getInstance()->getActiveShop()->getId();
        
        $aDataMap = array(
            0 => array(
                'select' => "SELECT * FROM oxobject2selectlist LEFT JOIN oxselectlist ON oxselectlist.oxid=oxobject2selectlist.oxselnid WHERE oxselectlist.oxshopid='" . $soxId . "' LIMIT " . $iLimit . " OFFSET " . $iOffset,
                'content' => array(
                    0 => array('field' => 'OXID'),
                    1 => array('field' => 'OXOBJECTID'),
                    2 => array('field' => 'OXSELNID'),
                    3 => array('field' => 'OXSORT'),
                    5 => array('field' => 'OXTIMESTAMP')
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
        if (!eins_csv_celebros_object2selectlist_export::$_oDBConn)
            eins_csv_celebros_object2selectlist_export::$_oDBConn = oxDb::getDb();

        return eins_csv_celebros_object2selectlist_export::$_oDBConn;
    }

    public function getDescription() {
        return null;
    }

    public function getTitle() {
        return "Celebros O2SL Data Export";
    }

    public function getId() {
        return "celebros_object2selectlist_export";
    }

    public function _getDelimiter() {
        return "|";
    }

    public function _getFieldWrapper() {
        return '';
    }

    public function _getPluginClass() {
        return 'eins_csv_celebros_object2selectlist_export';
    }

    public function getRSSize($aParams) {
        $oDb = eins_csv_celebros_object2selectlist_export::getDb();
        $oRs = $oDb->Execute("SELECT COUNT(oxid) FROM oxobject2selectlist");
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
        return 'oxobject2selectlist.csv';
    }
    
    public function getHeaderLine() {
        return "OXID|OXOBJECTID|OXSELNID|OXSORT|OXTIMESTAMP\n";
    }
}

?>
