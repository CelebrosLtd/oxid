<?php
class eins_csv_celebros_object2category_export extends \Celebros\Conversionpro\Core\CsvExport
{
    protected static $_oDBConn;

    protected $_headerArray = [
        "OXID","OXOBJECTID","OXCATNID","OXPOS","OXTIME","OXTIMESTAMP"
    ];      
    
    protected function _getDataMap($iLimit, $iOffset, $aParams)
    {
        $soxId = $this->getConfig()->getActiveShop()->getId();
        $aDataMap = array(
            0 => array(
                'select' => "SELECT * FROM oxobject2category LEFT JOIN oxcategories ON oxcategories.oxid=oxobject2category.oxcatnid WHERE oxcategories.oxshopid='" . $soxId . "' LIMIT " . $iLimit . " OFFSET " . $iOffset,
                'content' => array(
                    0 => array('field' => 'OXID'),
                    1 => array('field' => 'OXOBJECTID'),
                    2 => array('field' => 'OXCATNID'),
                    3 => array('field' => 'OXPOS'),
                    4 => array('field' => 'OXTIME'),
                    5 => array('field' => 'OXTIMESTAMP')
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
        return "Celebros O2C Data Export";
    }

    public function getId()
    {
        return "celebros_object2category_export";
    }

    public function _getPluginClass()
    {
        return 'eins_csv_celebros_object2category_export';
    }

    public function getRSSize($aParams)
    {
        $oDb = self::getDb();
        $oRs = $oDb->select("SELECT COUNT(oxid) FROM oxobject2category");
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
        return 'oxobject2category.csv';
    }
}
