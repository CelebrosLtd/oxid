<?php
class eins_csv_celebros_article_export extends \Celebros\Conversionpro\Core\CsvExport
{
    protected static $_oDBConn;
    
    protected function _getDataMap($iLimit, $iOffset, $aParams)
    {
        $blIsEEShop = $this->getConfig()->getEdition() == "EE";

        if ($blIsEEShop)
            return $this->_getDataMapEE($iLimit, $iOffset, $aParams);

        /*$aDataMap = array(
            0 => array(
                'select' => "SELECT oxarticles.* FROM oxarticles WHERE oxarticles.oxactive=1 GROUP BY oxarticles.oxid LIMIT " . $iLimit . " OFFSET " . $iOffset,
                'content' => array()
            )
        );

        $aMappingIncludeFields = $this->getConfig()->getShopConfVar('mappingincludefields');
        //var_dump($aMappingIncludeFields);*/

        /*$i = 0;
        foreach ($aMappingIncludeFields as $sIncludeField => $blEnabled) {
            if ($blEnabled) {
                if ($sIncludeField == 'OXPRICE') {
                    $aDataMap[0]['content'][$i++] = array(
                        'call' => array(
                            'function' => 'getNotNull',
                            'params' => array(
                                0 => array('field' => 'OXSHOPOXPRICE'),
                                1 => array('field' => 'OXPRICE')
                            )
                        )
                    );
                } elseif ($sIncludeField == 'OXPRICEA') {
                    $aDataMap[0]['content'][$i++] = array(
                        'call' => array(
                            'function' => 'getNotNull',
                            'params' => array(
                                0 => array('field' => 'OXSHOPOXPRICEA'),
                                1 => array('field' => 'OXPRICEA')
                            )
                        )
                    );
                } elseif ($sIncludeField == 'OXPRICEB') {
                    $aDataMap[0]['content'][$i++] = array(
                        'call' => array(
                            'function' => 'getNotNull',
                            'params' => array(
                                0 => array('field' => 'OXSHOPOXPRICEB'),
                                1 => array('field' => 'OXPRICEB')
                            )
                        )
                    );
                } elseif ($sIncludeField == 'OXPRICEC') {
                    $aDataMap[0]['content'][$i++] = array(
                        'call' => array(
                            'function' => 'getNotNull',
                            'params' => array(
                                0 => array('field' => 'OXSHOPOXPRICEC'),
                                1 => array('field' => 'OXPRICEC')
                            )
                        )
                    );
                } elseif ($sIncludeField == 'OXPRICEC') {
                    $aDataMap[0]['content'][$i++] = array(
                        'call' => array(
                            'function' => 'getNotNull',
                            'params' => array(
                                0 => array('field' => 'OXSHOPOXPRICEC'),
                                1 => array('field' => 'OXPRICEC')
                            )
                        )
                    );
                } else {
                    $aDataMap[0]['content'][$i++] = array('field' => $sIncludeField);
                }
            }
        }*/
        
        $aDataMap = array(
            0 => array(
                //'select' => "SELECT oxarticles.* FROM oxarticles WHERE oxarticles.oxactive=1 GROUP BY oxarticles.oxid LIMIT " . $iLimit . " OFFSET " . $iOffset,
                'select' => "SELECT * FROM oxarticles WHERE oxactive=1 GROUP BY oxid LIMIT " . $iLimit . " OFFSET " . $iOffset,
                'content' => array()
            )
        );
        
        $content = array();
        $this->getHeaderLine();
        foreach ($this->_headerArray as $column) {
            $content[] = array(
                'field' => $column
            );
        }
        
        $aDataMap[0]['content'] = $content;
        /*$i = 0;
        foreach ($aMappingIncludeFields as $sIncludeField => $blEnabled) {
            if ($blEnabled) {
                if ($sIncludeField == 'OXPRICE') {
                    $aDataMap[0]['content'][$i++] = array(
                        'call' => array(
                            'function' => 'getNotNull',
                            'params' => array(
                                0 => array('field' => 'OXSHOPOXPRICE'),
                                1 => array('field' => 'OXPRICE')
                            )
                        )
                    );
                } elseif ($sIncludeField == 'OXPRICEA') {
                    $aDataMap[0]['content'][$i++] = array(
                        'call' => array(
                            'function' => 'getNotNull',
                            'params' => array(
                                0 => array('field' => 'OXSHOPOXPRICEA'),
                                1 => array('field' => 'OXPRICEA')
                            )
                        )
                    );
                } elseif ($sIncludeField == 'OXPRICEB') {
                    $aDataMap[0]['content'][$i++] = array(
                        'call' => array(
                            'function' => 'getNotNull',
                            'params' => array(
                                0 => array('field' => 'OXSHOPOXPRICEB'),
                                1 => array('field' => 'OXPRICEB')
                            )
                        )
                    );
                } elseif ($sIncludeField == 'OXPRICEC') {
                    $aDataMap[0]['content'][$i++] = array(
                        'call' => array(
                            'function' => 'getNotNull',
                            'params' => array(
                                0 => array('field' => 'OXSHOPOXPRICEC'),
                                1 => array('field' => 'OXPRICEC')
                            )
                        )
                    );
                } elseif ($sIncludeField == 'OXPRICEC') {
                    $aDataMap[0]['content'][$i++] = array(
                        'call' => array(
                            'function' => 'getNotNull',
                            'params' => array(
                                0 => array('field' => 'OXSHOPOXPRICEC'),
                                1 => array('field' => 'OXPRICEC')
                            )
                        )
                    );
                } else {
                    $aDataMap[0]['content'][$i++] = array('field' => $sIncludeField);
                }
            }
        }*/

//print_r($aDataMap);die;
        return $aDataMap;
    }

    /*protected function _getDataMapEE($iLimit, $iOffset, $aParams)
    {
        $soxId = $this->getConfig()->getActiveShop()->getId();

        if ($this->getConfig()->getActiveShop()->oxshops__oxisinherited->getRawValue() == 0) {
            $aDataMap = array(
                0 => array(
                    'select' => "SELECT oxarticles.*, q.oxprice as OXSHOPOXPRICE, q.oxpricea as OXSHOPOXPRICEA, q.oxpriceb as OXSHOPOXPRICEB, q.oxpricec as OXSHOPOXPRICEC FROM oxarticles LEFT JOIN (SELECT * FROM oxfield2shop WHERE oxshopid='{$soxId}') as q ON q.oxartid=oxarticles.oxid WHERE oxarticles.oxactive=1 AND oxarticles.oxshopid='{$soxId}' GROUP BY oxarticles.oxid LIMIT " . $iLimit . " OFFSET " . $iOffset,
                    'content' => array()
                )
            );
        } else {
            $aDataMap = array(
                0 => array(
                    'select' => "SELECT oxarticles.*, q.oxpriceb as OXSHOPOXPRICEB, q.oxpricec as OXSHOPOXPRICEC FROM oxarticles LEFT JOIN (SELECT * FROM oxfield2shop WHERE oxshopid='{$soxId}') as q ON q.oxartid=oxarticles.oxid WHERE oxarticles.oxactive=1 GROUP BY oxarticles.oxid LIMIT " . $iLimit . " OFFSET " . $iOffset,
                    'content' => array()
                )
            );
        }

        $aMappingIncludeFields = $this->getConfig()->getShopConfVar('mappingincludefields');

        $i = 0;
        foreach ($aMappingIncludeFields as $sIncludeField => $blEnabled) {
            if ($blEnabled) {
                if ($sIncludeField == 'OXPRICE') {
                    $aDataMap[0]['content'][$i++] = array(
                        'call' => array(
                            'function' => 'getNotNull',
                            'params' => array(
                                0 => array('field' => 'OXSHOPOXPRICE'),
                                1 => array('field' => 'OXPRICE')
                            )
                        )
                    );
                } elseif ($sIncludeField == 'OXPRICEA') {
                    $aDataMap[0]['content'][$i++] = array(
                        'call' => array(
                            'function' => 'getNotNull',
                            'params' => array(
                                0 => array('field' => 'OXSHOPOXPRICEA'),
                                1 => array('field' => 'OXPRICEA')
                            )
                        )
                    );
                } elseif ($sIncludeField == 'OXPRICEB') {
                    $aDataMap[0]['content'][$i++] = array(
                        'call' => array(
                            'function' => 'getNotNull',
                            'params' => array(
                                0 => array('field' => 'OXSHOPOXPRICEB'),
                                1 => array('field' => 'OXPRICEB')
                            )
                        )
                    );
                } elseif ($sIncludeField == 'OXPRICEC') {
                    $aDataMap[0]['content'][$i++] = array(
                        'call' => array(
                            'function' => 'getNotNull',
                            'params' => array(
                                0 => array('field' => 'OXSHOPOXPRICEC'),
                                1 => array('field' => 'OXPRICEC')
                            )
                        )
                    );
                } elseif ($sIncludeField == 'OXPRICEC') {
                    $aDataMap[0]['content'][$i++] = array(
                        'call' => array(
                            'function' => 'getNotNull',
                            'params' => array(
                                0 => array('field' => 'OXSHOPOXPRICEC'),
                                1 => array('field' => 'OXPRICEC')
                            )
                        )
                    );
                } else {
                    $aDataMap[0]['content'][$i++] = array('field' => $sIncludeField);
                }
            }
        }

        return $aDataMap;
    }*/

    public static function getNotNull($sParam1, $sParam2)
    {
        return $sParam1 ? $sParam1 : $sParam2;
    }

    public function getFieldName($sFieldName)
    {
        if ($sFieldName == "OXID")
            return "sku";
        if ($sFieldName == "OXTITLE")
            return "name";
        if ($sFieldName == "OXSEOURL")
            return "url";
        if ($sFieldName == "OXPRICE")
            return "price";
        if ($sFieldName == "OXTPRICE")
            return "oldprice";
        return $sFieldName;
    }

    public function getOxidDefaultLanguage($sShopId)
    {
        $oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $oRs = $oDb->select("SELECT OXDEFLANGUAGE FROM oxshops WHERE OXID = '" . $sShopId . "'");

        if (!$oRs->EOF) {
            return $oRs->fields[0];
        }
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

    public function getDescription() {
        return null;
    }

    public function getTitle()
    {
        return "Celebros Article Data Export";
    }

    public function getId()
    {
        return "celebros_article_export";
    }

    public function _getPluginClass()
    {
        return 'eins_csv_celebros_article_export';
    }

    public function getRSSize($aParams)
    {
        $soxId = $this->getConfig()->getActiveShop()->getId();
        $oDb = self::getDb();
        if ($this->getConfig()->getActiveShop()->oxshops__oxisinherited->getRawValue() == 0) {
            $oRs = $oDb->select("SELECT COUNT(oxid) FROM oxarticles WHERE oxarticles.oxactive=1 AND oxarticles.oxissearch=1 AND oxarticles.oxshopid='" . $soxId . "'");
        } else {
            $oRs = $oDb->select("SELECT COUNT(oxid) FROM oxarticles WHERE oxarticles.oxactive=1 AND oxarticles.oxissearch=1");
        }
        
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
        return 'oxarticles.csv';
    }

    public function getHeaderLine()
    {   
        $result = [];
        $oDb = self::getDb();
        $oRs = $this->_oDb->select('describe oxarticles');
        while (!$oRs->EOF) {
            $result[] = $oRs->fields[0];
            $oRs->fetchRow();
        }
        
        $this->_headerArray = $result;
        
        return parent::getHeaderLine();
        /*$aMappingIncludeFields = $this->getConfig()->getShopConfVar('mappingincludefields');
        $i = 0;
        foreach ($aMappingIncludeFields as $sIncludeField => $blEnabled) {
            $sIncludeField = $this->getFieldName($sIncludeField);
            if ($blEnabled) {
                if ($i == 0) {
                    $sHeaderLine .= $sIncludeField;
                } else {
                    $sHeaderLine .= $this->_getDelimiter() . $sIncludeField;
                }
                $i++;
            }
        }

        $sHeaderLine .= "\n";

        return $sHeaderLine;*/
    }

    protected function _processDataMap($oRs, $aDataMap, $iLineNr)
    {
        foreach ($aDataMap['content'] as $key => $aValue) {
            $this->_aOutput[$iLineNr][$key] = $oRs->fields[$key];
        }
    }    
    
    public static function getArticleTitle($sTitle1, $sTitle2, $sTitle3)
    {
        if ($sTitle1 && $sTitle1 != "") {
            return $sTitle1;
        }
        if ($sTitle2 && $sTitle2 != "") {
            return $sTitle2;
        }
        if ($sTitle3 && $sTitle3 != "") {
            return $sTitle3;
        }
    }
}
