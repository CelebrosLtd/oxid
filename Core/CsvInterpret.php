<?php
namespace Celebros\Conversionpro\Core;

class CsvInterpret
{
    protected static $aVarBuff;
    public static $oDb;

    public static function getValue($aLine = null, $oRs = null, $sTableName, $aValue, $iLineNr, $sPluginClass, $sLoopVar = null)
    {
        if (!is_array($aValue)) {
            return str_replace ("_LOOPVAR_", $sLoopVar, $aValue);
        }

        foreach ($aValue as $key => $aNextValue) {
            if (is_string($key) && $key == "ifisset") {
                $sValue = self::getValue($aLine, $oRs, $sTableName, $aNextValue['value'], $iLineNr, $sPluginClass, $sLoopVar);
                if($sValue && sizeof($sValue) > 0) {
                    $sThisValue = self::getValue($aLine, $oRs, $sTableName, $aNextValue['do'], $iLineNr, $sPluginClass, $sLoopVar);
                }
                else {
                    if($aNextValue['else']) {
                        $sThisValue = self::getValue($aLine, $oRs, $sTableName, $aNextValue['else'], $iLineNr, $sPluginClass, $sLoopVar);
                    }
                }
            }
            elseif (is_string($key) && $key == "call") {
                $sFunctionName = self::getValue($aLine, $oRs, $sTableName, $aNextValue['function'], $iLineNr, $sPluginClass, $sLoopVar);
                $aParams = self::getValue($aLine, $oRs, $sTableName, $aNextValue['params'], $iLineNr, $sPluginClass, $sLoopVar);

                $reflectionMethod = new \ReflectionMethod($sPluginClass, $sFunctionName);
                $sThisValue = $reflectionMethod->invokeArgs(null, $aParams);
            } elseif (is_string($key) && $key == 'field') {
                if (!$oRs)
                    $sThisValue = 'No ResultSet provided: Celebros\Conversionpro\Core\CsvInterpret.php';
                else {
                    $sFieldName = self::getValue($aLine, $oRs, $sTableName, $aNextValue, $iLineNr, $sPluginClass, $sLoopVar);

                    $sThisValue = str_replace("|", ",", preg_replace("/[\n\r]/"," ", $oRs->fields[$sFieldName]));
                }
            } elseif (is_string($key) && $key == 'col') {
                if (!$aLine)
                    $sThisValue = 'No file line provided: Celebros\Conversionpro\Core\CsvInterpret.php';
                else {
                    $iCol = self::getValue($aLine, $oRs, $sTableName, $aNextValue, $iLineNr, $sPluginClass, $sLoopVar);
                    $sThisValue = trim($aLine[$iCol]);
                }
            } elseif (is_string($key) && $key == 'findprime') {
                foreach($aNextValue as $oCondition) {
                    $sFieldName = self::getValue($aLine, $oRs, $sTableName, $oCondition['fieldname'], $iLineNr, $sPluginClass, $sLoopVar);
                    $sFieldValue = self::getValue($aLine, $oRs, $sTableName, $oCondition['fieldvalue'], $iLineNr, $sPluginClass, $sLoopVar);
                    
                    if($iNumConditions > 0) {
                        $sConditions .= ' AND ';
                    }
                    $sConditions .= $sFieldName . "='" . $sFieldValue . "'";
                    $iNumConditions++;
                }


                $oDb = eins_csv_interpret::getDb();
                $oRs = $oDb->select("SELECT oxid FROM " . $sTableName . " WHERE " . $sConditions);

                if (!$oRs->EOF) {
                    $sThisValue = $oRs->fields['oxid'];
                } else {
                    $sThisValue = oxUtilsObject::getInstance()->generateUID();
                }
            } elseif (is_string($key) && $key == 'set') {
                $sVarName = self::getValue($aLine, $oRs, $sTableName, $aNextValue['var'], $iLineNr, $sPluginClass, $sLoopVar);
                
                $sVarValue = self::getValue($aLine, $oRs, $sTableName, $aNextValue['value'], $iLineNr, $sPluginClass, $sLoopVar);

                eins_csv_interpret::$aVarBuff[$iLineNr][$sVarName] = $sVarValue;

                $sThisValue = $sVarValue;
            } elseif (is_string($key) && $key == 'get') {
                $sVarName = self::getValue($aLine, $oRs, $sTableName, $aNextValue, $iLineNr, $sPluginClass, $sLoopVar);
                $sThisValue = self::$aVarBuff[$iLineNr][$sVarName];
            } elseif (is_string($key) && $key == 'query') {
                $sQuery = self::getValue($aLine, $oRs, $sTableName, $aNextValue, $iLineNr, $sPluginClass, $sLoopVar);
                
                $oDb = eins_csv_interpret::getDb();
                $oRs = $oDb->select($sQuery);
                
                if(!$oRs->EOF) {
                    foreach($oRs->fields as $sField) {
                        $sThisValue .= $sField;
                    }
                }
                else {
                    $sThisValue = null;
                }
            } elseif (is_string($key) && $key == 'concat') {
                foreach($aNextValue as $oPart) {
                    $sPart = self::getValue($aLine, $oRs, $sTableName, $oPart, $iLineNr, $sPluginClass, $sLoopVar);
                    $sThisValue .= $sPart;
                }
            } else {
                $sThisValue[$key] = self::getValue($aLine, $oRs, $sTableName, $aNextValue, $iLineNr, $sPluginClass, $sLoopVar);
            }
        }

        return $sThisValue;
    }

    protected static function getDb() {
        if (!self::$oDb) {
            self::$oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
            self::$oDb->SetFetchMode(ADODB_FETCH_ASSOC);
        }

        return self::$oDb;
    }

}

?>
