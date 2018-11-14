<?php

/*
 *  Developed by webfrisch.de
 *  Author: Lukas Dierks <lukas.dierks at webfrisch.de>
 *  Date: Jun 5, 2013
 */
class eins_csv_celebros_export extends eins_csv_export {

    protected static $_oDBConn;

    protected function _getDataMap($iLimit, $iOffset, $aParams) {
        
    }

    public static function stripHTMLTags($sString) {
        return preg_replace("/<[^>]*>/", " ", $sString);
    }

    public static function replaceDelimiter($sString) {
        return str_replace(" ", "", str_replace("|", ",", $sString));
    }

    public static function getDb() {
        if (!eins_csv_celebros_export::$_oDBConn)
            eins_csv_celebros_export::$_oDBConn = oxDb::getDb();

        return eins_csv_celebros_export::$_oDBConn;
    }

    public function getDescription() {
        return null;
    }

    public function getTitle() {
        return "Celebros Data Export";
    }

    public function getId() {
        return "celebros_export";
    }

    public function _getDelimiter() {
        return "|";
    }

    public function _getFieldWrapper() {
        return '';
    }

    public function _getPluginClass() {
        return 'eins_csv_celebros_export';
    }

    public function getRSSize($aParams) {
        $oDb = eins_csv_celebros_export::getDb();
        $oRs = $oDb->Execute("SELECT COUNT(oxid) FROM oxarticles");
        return $oRs->fields[0];
    }

    public function getExportParamInfo() {
        return array();
    }
    
    public function getParent() {
        return null;
    }
    
    public function afterExport() {
        parent::afterExport();
        
        $sExportPath = oxConfig::getInstance()->getShopConfVar("sShopDir") .
                "modules/eins_celebros/export/";
        
        $aFiles = scandir($sExportPath);
        $sShopId = oxConfig::getInstance()->getShopId();

        unlink($sExportPath . $sShopId . "_products" . ".zip");
        $oZipArchive = new PclZip($sExportPath . $sShopId . "_products" . ".zip");
        
        foreach($aFiles as $sFile) {
            if(preg_match("/[.]*.csv/", $sFile)) {
                var_dump($sExportPath . $sFile);
                $oZipArchive->add($sExportPath . $sFile, PCLZIP_OPT_REMOVE_ALL_PATH);
                unlink($sExportPath . $sFile);
            }
        }
    }
    
    public function getFixedOutputFileName() {
        $soxId = oxConfig::getInstance()->getActiveShop()->getId();
        return $soxId . '_products' .  '.zip';
    }

}

?>
