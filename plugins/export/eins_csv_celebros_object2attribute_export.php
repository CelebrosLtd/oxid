<?php
class eins_csv_celebros_object2attribute_export extends \Celebros\Conversionpro\Core\CsvExport
{
    protected static $_oDBConn;

    protected $_headerArray = [
        "OXID","OXOBJECTID","OXATTRID","OXVALUE","OXPOS","OXVALUE_1","OXVALUE_2","OXVALUE_3","OXTIMESTAMP"
    ];
    
    protected function _getDataMap($iLimit, $iOffset, $aParams)
    {
        $soxId = $this->getConfig()->getActiveShop()->getId();
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
        return "Celebros O2A Data Export";
    }

    public function getId()
    {
        return "celebros_object2attribute_export";
    }

    public function _getPluginClass()
    {
        return 'eins_csv_celebros_object2attribute_export';
    }

    public function getRSSize($aParams)
    {
        $oDb = self::getDb();
        $oRs = $oDb->Execute("SELECT COUNT(oxid) FROM oxobject2attribute");
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
        return 'oxobject2attribute.csv';
    }
}
