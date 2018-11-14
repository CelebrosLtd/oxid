<?php

/*
 *  Developed by webfrisch.de
 *  Author: Lukas Dierks <lukas.dierks at webfrisch.de>
 *  Date: Jun 5, 2013
 */

abstract class eins_csv_export {

    protected $_oDb;
    protected $_aOutput;
    protected $_aChildPlugins;

    public function export($sOutputFileHash, $iOffset, $iAmount, $aParams) {
        if (!$this->_aChildPlugins) {
            $this->_oDb = oxDb::getDb();
            $this->_oDb->SetFetchMode(ADODB_FETCH_ASSOC);

            $aDataMap = $this->_getDataMap($iAmount, $iOffset, $aParams);

            foreach ($aDataMap as $aSeq) {
                $oRs = $this->_oDb->execute($aSeq['select']);

                while (!$oRs->EOF) {
                    $this->_processDataMap($oRs, $aSeq, $iOffset + $oRs->_currentRow);
                    $oRs->moveNext();
                }
            }

            if ($this->getFixedOutputFileName()) {
                $sOutputFile = oxConfig::getInstance()->getConfigParam('sShopDir') . 'modules/eins_celebros/export/' .
                        $this->getFixedOutputFileName();
            } else {
                $sOutputFile = oxConfig::getInstance()->getConfigParam('sShopDir') . 'modules/eins_celebros/export/' .
                        $this->getId() . "-" . $sOutputFileHash . ".csv";
            }

            if ($iOffset == 0 && $this->getHeaderLine()) {
                file_put_contents($sOutputFile, $this->getHeaderLine());
            }

            $sFieldWrapper = $this->_getFieldWrapper();
            $sDelimiter = $this->_getDelimiter();
            if ($this->_aOutput && sizeof($this->_aOutput) > 0) {
                foreach ($this->_aOutput as $aLine) {
                    $sLine = $sFieldWrapper .
                            implode($sFieldWrapper . $sDelimiter . $sFieldWrapper, $aLine) .
                            $sFieldWrapper . "\n";
                    file_put_contents($sOutputFile, $sLine, FILE_APPEND);
                }

                return true;
            }
            else
                return false;
        }
        else {
            foreach ($this->_aChildPlugins as $oPlugin) {
                $oPlugin->export($sOutputFileHash, $iOffset, $iAmount, $aParams);
            }
        }
    }

    public abstract function getRSSize($aParams);

    protected function _processDataMap($oRs, $aDataMap, $iLineNr) {
        foreach ($aDataMap['content'] as $key => $aValue) {
            $this->_aOutput[$iLineNr][$key] = eins_csv_interpret::getValue(null, $oRs, null, $aValue, $iLineNr, $this->_getPluginClass(), null);
        }
    }

    protected abstract function _getDataMap($iLimit, $iOffset, $aParams);

    public abstract function getExportParamInfo();

    public abstract function getTitle();

    public abstract function getDescription();

    public abstract function getId();

    public abstract function _getDelimiter();

    public abstract function _getFieldWrapper();

    public abstract function _getPluginClass();

    public function getChunkSize() {
        return 100;
    }

    public function getParentPlugin() {
        return null;
    }

    public function getChildPlugins() {
        return $this->_aChildPlugins;
    }

    public function addChildPlugin($oChildPlugin) {
        $this->_aChildPlugins[] = $oChildPlugin;
    }

    public function getFixedOutputFileName() {
        return false;
    }

    public function afterExport() {
        
    }

    public function getHeaderLine() {
        return null;
    }

}

?>
