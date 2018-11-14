<?php

/*
 *  Developed by webfrisch.de
 *  Author: Lukas Dierks <lukas.dierks at webfrisch.de>
 *  Date: Jun 5, 2013
 */

class eins_csv_celebros_selectlist_export extends eins_csv_export {

    protected static $_oDBConn;

    protected function _getDataMap($iLimit, $iOffset, $aParams) {
        $soxId = oxConfig::getInstance()->getActiveShop()->getId();
        
        $aDataMap = array(
            0 => array(
                'select' => "SELECT * FROM oxselectlist WHERE oxselectlist.oxshopid='" . $soxId . "' LIMIT " . $iLimit . " OFFSET " . $iOffset,
                'content' => array(
                    0 => array('field' => 'OXID'),
                    1 => array('field' => 'OXSHOPID'),
                    2 => array('field' => 'OXTITLE'),
                    3 => array('field' => 'OXIDENT'),
                    4 => array('field' => 'OXVALDESC'),
                    5 => array('field' => 'OXTITLE_1'),
                    6 => array('field' => 'OXVALDESC_1'),
                    7 => array('field' => 'OXTITLE_2'),
                    8 => array('field' => 'OXVALDESC_2'),
                    9 => array('field' => 'OXTITLE_3'),
                    10 => array('field' => 'OXVALDESC_3'),
                    11 => array('field' => 'OXTIMESTAMP')
                )
            )
        );

        return $aDataMap;
    }

    public static function getDb() {
        if (!eins_csv_celebros_selectlist_export::$_oDBConn)
            eins_csv_celebros_selectlist_export::$_oDBConn = oxDb::getDb();

        return eins_csv_celebros_selectlist_export::$_oDBConn;
    }

    public function getDescription() {
        return null;
    }

    public function getTitle() {
        return "Celebros Selectlist Data Export";
    }

    public function getId() {
        return "celebros_selectlist_export";
    }

    public function _getDelimiter() {
        return "|";
    }

    public function _getFieldWrapper() {
        return '';
    }

    public function _getPluginClass() {
        return 'eins_csv_celebros_selectlist_export';
    }

    public function getRSSize($aParams) {
        $oDb = eins_csv_celebros_selectlist_export::getDb();
        $oRs = $oDb->Execute("SELECT COUNT(oxid) FROM oxselectlist");
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
        return 'oxselectlist.csv';
    }

    public function getHeaderLine() {
        return "OXID|OXSHOPID|OXTITLE|OXIDENT|OXVALDESC|OXTITLE_1|OXVALDESC_1|OXTITLE_2|OXVALDESC_2|OXTITLE_3|OXVALDESC_3|OXTIMESTAMP\n";
    }
}

?>
