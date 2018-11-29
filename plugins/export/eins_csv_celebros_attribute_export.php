<?php
class eins_csv_celebros_attribute_export extends \Celebros\Conversionpro\Core\CsvExport
{
    protected static $_oDBConn;

    protected $_headerArray = [
        "OXID","OXSHOPID","OXTITLE","OXTITLE_1","OXTITLE_2","OXTITLE_3","OXPOS","OXTIMESTAMP","OXDISPLAYINBASKET"
    ];      
    
    protected function _getDataMap($iLimit, $iOffset, $aParams)
    {
        $soxId = $this->getConfig()->getActiveShop()->getId();
        $aDataMap = array(
            0 => array(
                'select' => "SELECT * FROM oxattribute WHERE oxshopid='" . $soxId . "' LIMIT " . $iLimit . " OFFSET " . $iOffset,
                'content' => array(
                    0 => array('field' => 'OXID'),
                    1 => array('field' => 'OXSHOPID'),
                    2 => array('field' => 'OXTITLE'),
                    3 => array('field' => 'OXTITLE_1'),
                    4 => array('field' => 'OXTITLE_2'),
                    5 => array('field' => 'OXTITLE_3'),
                    6 => array('field' => 'OXPOS'),
                    7 => array('field' => 'OXTIMESTAMP'),
                    8 => array('field' => 'OXDISPLAYINBASKET')
                )
            )
        );

        return $aDataMap;
    }

    public static function stripHTMLTags($sString)
    {
        return preg_replace("/<[^>]*>/", " ", $sString);
    }

    public static function replaceDelimiter($sString)
    {
        return str_replace(" ", "", str_replace("|", ",", $sString));
    }

    public static function getDb()
    {
        if (!self::$_oDBConn)
            self::$_oDBConn = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

        return self::$_oDBConn;
    }

    public function getDescription()
    {
        return null;
    }

    public function getTitle()
    {
        return "Celebros Attribute Data Export";
    }

    public function getId() {
        return "celebros_attribute_export";
    }

    public function _getPluginClass()
    {
        return 'eins_csv_celebros_attribute_export';
    }

    public function getRSSize($aParams)
    {
        $oDb = self::getDb();
        $oRs = $oDb->select("SELECT COUNT(oxid) FROM oxattribute");
        return $oRs->fields[0];
    }

    public function getExportParamInfo()
    {
        return array();
    }

    public function getParentPlugin()
    {
        return 'celebros_export';
    }
    
    public function getFixedOutputFileName()
    {
        return 'oxattribute.csv';
    }
}
