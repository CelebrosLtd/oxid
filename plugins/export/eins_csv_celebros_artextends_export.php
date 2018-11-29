<?php
class eins_csv_celebros_artextends_export extends \Celebros\Conversionpro\Core\CsvExport {

    protected static $_oDBConn;

    protected $_headerArray = [
        "OXID","OXLONGDESC","OXLONGDESC_1","OXLONGDESC_2","OXLONGDESC_3","OXTIMESTAMP"
    ];
    
    protected function _getDataMap($iLimit, $iOffset, $aParams)
    {
        $soxId = $this->getConfig()->getActiveShop()->getId();
        
        $aDataMap = array(
            0 => array(
                'select' => "SELECT * FROM oxartextends LIMIT " . $iLimit . " OFFSET " . $iOffset,
                'content' => array(
                    0 => array('field' => 'OXID'),
                    1 => array('field' => 'OXLONGDESC'),
                    2 => array('field' => 'OXLONGDESC_1'),
                    3 => array('field' => 'OXLONGDESC_2'),
                    4 => array('field' => 'OXLONGDESC_3'),
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
        if (!eins_csv_celebros_artextends_export::$_oDBConn)
            eins_csv_celebros_artextends_export::$_oDBConn = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

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

    public function _getPluginClass() {
        return 'eins_csv_celebros_artextends_export';
    }

    public function getRSSize($aParams) {
        $oDb = eins_csv_celebros_artextends_export::getDb();
        $oRs = $oDb->select("SELECT COUNT(oxid) FROM oxartextends");
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

}