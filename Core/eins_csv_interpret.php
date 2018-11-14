<?php

/*
 *  Developed by webfrisch.de
 *  Author: Lukas Dierks <lukas.dierks at webfrisch.de>
 *  Date: Jun 26, 2013
 */

class eins_csv_interpret {

    protected static $aVarBuff;
    public static $oDb;

    public static function getValue($aLine = null, $oRs = null, $sTableName, $aValue, $iLineNr, $sPluginClass, $sLoopVar = null) {
        if (!is_array($aValue)) {
            return str_replace ("_LOOPVAR_", $sLoopVar, $aValue);
        }

        foreach ($aValue as $key => $aNextValue) {
            if (is_string($key) && $key == "ifisset") {
                $sValue = eins_csv_interpret::getValue($aLine, $oRs, $sTableName, $aNextValue['value'], $iLineNr, $sPluginClass, $sLoopVar);
                if($sValue && sizeof($sValue) > 0) {
                    $sThisValue = eins_csv_interpret::getValue($aLine, $oRs, $sTableName, $aNextValue['do'], $iLineNr, $sPluginClass, $sLoopVar);
                }
                else {
                    if($aNextValue['else']) {
                        $sThisValue = eins_csv_interpret::getValue($aLine, $oRs, $sTableName, $aNextValue['else'], $iLineNr, $sPluginClass, $sLoopVar);
                    }
                }
            }
            elseif (is_string($key) && $key == "call") {
                $sFunctionName = eins_csv_interpret::getValue($aLine, $oRs, $sTableName, $aNextValue['function'], $iLineNr, $sPluginClass, $sLoopVar);
                $aParams = eins_csv_interpret::getValue($aLine, $oRs, $sTableName, $aNextValue['params'], $iLineNr, $sPluginClass, $sLoopVar);

                $reflectionMethod = new ReflectionMethod($sPluginClass, $sFunctionName);
                $sThisValue = $reflectionMethod->invokeArgs(null, $aParams);
            } elseif (is_string($key) && $key == 'field') {
                if (!$oRs)
                    $sThisValue = 'No ResultSet provided: eins_csv_interpret.php';
                else {
                    $sFieldName = eins_csv_interpret::getValue($aLine, $oRs, $sTableName, $aNextValue, $iLineNr, $sPluginClass, $sLoopVar);

                    $sThisValue = str_replace("|", ",", preg_replace("/[\n\r]/"," ", $oRs->fields[$sFieldName]));
                }
            } elseif (is_string($key) && $key == 'col') {
                if (!$aLine)
                    $sThisValue = 'No file line provided: eins_csv_interpret.php';
                else {
                    $iCol = eins_csv_interpret::getValue($aLine, $oRs, $sTableName, $aNextValue, $iLineNr, $sPluginClass, $sLoopVar);
                    $sThisValue = trim($aLine[$iCol]);
                }
            } elseif (is_string($key) && $key == 'findprime') {
                foreach($aNextValue as $oCondition) {
                    $sFieldName = eins_csv_interpret::getValue($aLine, $oRs, $sTableName, $oCondition['fieldname'], $iLineNr, $sPluginClass, $sLoopVar);
                    $sFieldValue = eins_csv_interpret::getValue($aLine, $oRs, $sTableName, $oCondition['fieldvalue'], $iLineNr, $sPluginClass, $sLoopVar);
                    
                    if($iNumConditions > 0) {
                        $sConditions .= ' AND ';
                    }
                    $sConditions .= $sFieldName . "='" . $sFieldValue . "'";
                    $iNumConditions++;
                }


                $oDb = eins_csv_interpret::getDb();
                $oRs = $oDb->execute("SELECT oxid FROM " . $sTableName . " WHERE " . $sConditions);

                if (!$oRs->EOF) {
                    $sThisValue = $oRs->fields['oxid'];
                } else {
                    $sThisValue = oxUtilsObject::getInstance()->generateUID();
                }
            } elseif (is_string($key) && $key == 'set') {
                $sVarName = eins_csv_interpret::getValue($aLine, $oRs, $sTableName, $aNextValue['var'], $iLineNr, $sPluginClass, $sLoopVar);
                
                $sVarValue = eins_csv_interpret::getValue($aLine, $oRs, $sTableName, $aNextValue['value'], $iLineNr, $sPluginClass, $sLoopVar);

                eins_csv_interpret::$aVarBuff[$iLineNr][$sVarName] = $sVarValue;

                $sThisValue = $sVarValue;
            } elseif (is_string($key) && $key == 'get') {
                $sVarName = eins_csv_interpret::getValue($aLine, $oRs, $sTableName, $aNextValue, $iLineNr, $sPluginClass, $sLoopVar);
                $sThisValue = eins_csv_interpret::$aVarBuff[$iLineNr][$sVarName];
            } elseif (is_string($key) && $key == 'query') {
                $sQuery = eins_csv_interpret::getValue($aLine, $oRs, $sTableName, $aNextValue, $iLineNr, $sPluginClass, $sLoopVar);
                
                $oDb = eins_csv_interpret::getDb();
                $oRs = $oDb->execute($sQuery);
                
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
                    $sPart = eins_csv_interpret::getValue($aLine, $oRs, $sTableName, $oPart, $iLineNr, $sPluginClass, $sLoopVar);
                    $sThisValue .= $sPart;
                }
            } else {
                $sThisValue[$key] = eins_csv_interpret::getValue($aLine, $oRs, $sTableName, $aNextValue, $iLineNr, $sPluginClass, $sLoopVar);
            }
        }

        return $sThisValue;
    }

    protected static function getDb() {
        if (!eins_csv_interpret::$oDb) {
            eins_csv_interpret::$oDb = oxDb::getDb();
            eins_csv_interpret::$oDb->SetFetchMode(ADODB_FETCH_ASSOC);
        }

        return eins_csv_interpret::$oDb;
    }

}

?>
