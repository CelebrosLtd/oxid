<?php
class eins_csv_celebros_vendor_export extends \Celebros\Conversionpro\Core\CsvExport
{
    protected static $_oDBConn;

    protected $_headerArray = [
        "OXID","OXSHOPID","OXACTIVE","OXICON","OXTITLE","OXSHORTDESC","OXTITLE_1","OXSHORTDESC_1","OXTITLE_2","OXSHORTDESC_2","OXTITLE_3","OXSHORTDESC_3","OXSHOWSUFFIX","OXTIMESTAMP"
    ];    
    
    protected function _getDataMap($iLimit, $iOffset, $aParams)
    {
        $soxId = $this->getConfig()->getActiveShop()->getId();
        $aDataMap = array(
            0 => array(
                'select' => "SELECT * FROM oxvendor WHERE oxvendor.oxshopid='" . $soxId . "' LIMIT " . $iLimit . " OFFSET " . $iOffset,
                'content' => array(
                    0 => array('field' => 'OXID'),
                    1 => array('field' => 'OXSHOPID'),
                    2 => array('field' => 'OXACTIVE'),
                    3 => array('field' => 'OXICON'),
                    4 => array('field' => 'OXTITLE'),
                    5 => array('field' => 'OXSHORTDESC'),
                    6 => array('field' => 'OXTITLE_1'),
                    7 => array('field' => 'OXSHORTDESC_1'),
                    8 => array('field' => 'OXTITLE_2'),
                    9 => array('field' => 'OXSHORTDESC_2'),
                    10 => array('field' => 'OXTITLE_3'),
                    11 => array('field' => 'OXSHORTDESC_3'),
                    12 => array('field' => 'OXSHOWSUFFIX'),
                    13 => array('field' => 'OXTIMESTAMP')
                )
            )
        );

        return $aDataMap;
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
        return "Celebros Vendor Data Export";
    }

    public function getId()
    {
        return "celebros_vendor_export";
    }

    public function _getPluginClass()
    {
        return 'eins_csv_celebros_vendor_export';
    }

    public function getRSSize($aParams)
    {
        $oDb = self::getDb();
        $oRs = $oDb->select("SELECT COUNT(oxid) FROM oxvendor");
        return $oRs->fields[0];
    }

    public function getExportParamInfo()
    {
        return array();
    }
    
    public function getParentPlugin() {
        return 'celebros_export';
    }
    
    public function getFixedOutputFileName()
    {
        return 'oxvendor.csv';
    }
}