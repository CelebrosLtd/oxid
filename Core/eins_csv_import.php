<?php

/*
 *  Developed by webfrisch.de
 *  Author: Lukas Dierks <lukas.dierks at webfrisch.de>
 *  Date: Jun 5, 2013
 */

abstract class eins_csv_import {

    protected $_oDb;

    public function importFile($sFileName, $iOffset, $iAmount) {
        $this->_oDb = oxDb::getDb();
        $this->_setup($sFileName, $this->_oDb);

        $this->_oDb = oxDb::getDb();

        $aDataMap = $this->_getDataMap();

        foreach ($aDataMap as $aSeq) {
            $i = 0;

            $oFile = fopen($sFileName, "r");

            while ($sLine = fgets($oFile)) {
                if ($i >= $iOffset && $i < $iOffset + $iAmount) {
                    $aLine = explode($this->_getDelimiter(), $sLine);
                    $this->_importLine($aLine, $aSeq, $i);
                }
                
                $i++;
            }

            fclose($oFile);
            
//            $this->_afterImport($sFileName, $this->_oDb);
        }
    }

    protected function _importLine($aLine, $aDataMap, $iLineNr) {
        $this->_processDataMap($aLine, $aDataMap, $iLineNr, null);
    }

    protected function _processDataMap($aLine, $aDataMap, $iLineNr = null, $sLoopVar) {
        if ($aDataMap['operation'] == 'delete') {
            $sDelete = 'DELETE FROM ' . $aDataMap['table'] . ' WHERE ' .
                    eins_csv_interpret::getValue($aLine, null, $aDataMap['table'], $aDataMap['where'], $iLineNr, $this->_getPluginClass(), $sLoopVar);

            $this->_oDb->execute($sDelete);
        } else {
            foreach ($aDataMap['content'] as $sTableName => $aTable) {
                if ($aDataMap['operation'] == 'arrayloop') {
                    $aArray = eins_csv_interpret::getValue($aLine, null, $sTableName, $aDataMap['array'], $iLineNr, $this->_getPluginClass(), $sLoopVar);
                    foreach ($aArray as $sVar) {
                        foreach ($aDataMap['content'] as $aChildSeq) {
                            $this->_processDataMap($aLine, $aChildSeq, $iLineNr, $sVar);
                        }
                    }
                }
                if ($aDataMap['operation'] == 'doifnotset') {
                    $sValue = eins_csv_interpret::getValue($aLine, null, $sTableName, $aDataMap['value'], $iLineNr, $this->_getPluginClass(), $sLoopVar);

                    if (!$sValue || strlen(trim($sValue)) == 0) {
                        $this->_processDataMap($aLine, $aDataMap['content'], $iLineNr, $sVar);
                    }
                }
                if ($aDataMap['operation'] == 'doifset') {
                    $sValue = eins_csv_interpret::getValue($aLine, null, $sTableName, $aDataMap['value'], $iLineNr, $this->_getPluginClass(), $sLoopVar);

                    if ($sValue && strlen(trim($sValue)) > 0) {
                        $this->_processDataMap($aLine, $aDataMap['content'], $iLineNr, $sVar);
                    }
                }
                if ($aDataMap['operation'] == 'insert') {
                    foreach ($aTable as $aSeq) {
                        $sInsert = 'INSERT INTO ' . $sTableName . ' (';
                        $sValues = '';
                        $i = 0;

                        foreach ($aSeq as $sFieldName => $sValue) {
                            $i++;
                            $sInsert .= $sFieldName;
                            if ($i < sizeof($aSeq))
                                $sInsert .= ',';
                            $sValues .= "'" . eins_csv_interpret::getValue($aLine, null, $sTableName, $sValue, $iLineNr, $this->_getPluginClass(), $sLoopVar);
                            if ($i < sizeof($aSeq))
                                $sValues .= ',';
                        }

                        $sInsert .= ') VALUES (' . $sValues . ")";
                        $this->_oDb->execute($sInsert);
                    }
                } elseif ($aDataMap['operation'] == 'update') {
                    foreach ($aTable as $aSeq) {
                        $sUpdate = 'UPDATE ' . $sTableName . ' SET ';
                        $i = 0;
                        foreach ($aSeq as $sFieldName => $sValue) {
                            $i++;
                            $sValue = eins_csv_interpret::getValue($aLine, null, $sTableName, $sValue, $iLineNr, $this->_getPluginClass(), $sLoopVar);
                            $sUpdate .= $sFieldName . "='" . $sValue . "'";
                            if ($i < sizeof($aSeq))
                                $sUpdate .= ', ';
                        }
                        $sUpdate .= ' WHERE ' . eins_csv_interpret::getValue($aLine, null, $sTableName, $aDataMap['where'], $iLineNr, $this->_getPluginClass(), $sLoopVar);

                        $this->_oDb->execute($sUpdate);
                    }
                } elseif ($aDataMap['operation'] == 'replace') {
                    foreach ($aTable as $aSeq) {
                        $sReplace = 'REPLACE INTO ' . $sTableName . ' (';
                        $sValues = '';
                        $i = 0;

                        foreach ($aSeq as $sFieldName => $sValue) {
                            $i++;
                            $sReplace .= $sFieldName;
                            if ($i < sizeof($aSeq))
                                $sReplace .= ',';
                            $sValues .= "'" . eins_csv_interpret::getValue($aLine, null, $sTableName, $sValue, $iLineNr, $this->_getPluginClass(), $sLoopVar) . "'";
                            if ($i < sizeof($aSeq))
                                $sValues .= ',';
                        }
                        $sReplace .= ') VALUES (' . $sValues . ")";
//                    die();
                        $this->_oDb->execute($sReplace);
                    }
                }
            }
        }
    }

    protected abstract function _getDataMap();

    public abstract function getTitle();

    public abstract function getDescription();

    public abstract function getId();

    public abstract function _getDelimiter();

    public abstract function _getPluginClass();
    
    public abstract function afterImport($sFileName);
    
    protected function _setup() {
        
    }
    
    public function isActive() {
        return true;
    }
    
    public function getChunkSize() {
        return 100;
    }
}
?>
