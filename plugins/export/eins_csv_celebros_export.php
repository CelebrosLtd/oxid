<?php

/*
 *  Developed by webfrisch.de
 *  Author: Lukas Dierks <lukas.dierks at webfrisch.de>
 *  Date: Jun 5, 2013
 */
require_once(__DIR__ . "/../../lib/pclzip.lib.php");
 
class eins_csv_celebros_export extends \Celebros\Conversionpro\Core\CsvExport {

    protected static $_oDBConn;
    public $config;

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
            eins_csv_celebros_export::$_oDBConn = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

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

    public function getRSSize($aParams)
    {
        $oDb = eins_csv_celebros_export::getDb();
        $oRs = $oDb->select("SELECT COUNT(oxid) FROM oxarticles");
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
        
        $sExportPath = $this->getConfig()->getShopConfVar("sShopDir") .
                "modules/celebros/conversionpro/export/";
        
        $aFiles = scandir($sExportPath);
        $sShopId = $this->getConfig()->getShopId();
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
        $soxId = $this->getConfig()->getActiveShop()->getId();
        return $soxId . '_products' .  '.zip';
    }

}

?>
