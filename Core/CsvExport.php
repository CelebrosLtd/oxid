<?php
namespace Celebros\Conversionpro\Core; 
 
abstract class CsvExport
{
    protected $_oDb;
    protected $_aOutput;
    protected $_aChildPlugins;
    protected $_headerArray;

    public function getConfig()
    {
        if (!$this->config) {
            $this->config = new \OxidEsales\EshopCommunity\Core\Config();
        }
        
        return $this->config;
    }
    
    public function export($sOutputFileHash, $iOffset, $iAmount, $aParams) {
        if (!$this->_aChildPlugins) {
            $this->_oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
            //$this->_oDb->SetFetchMode(ADODB_FETCH_ASSOC);

            $aDataMap = $this->_getDataMap($iAmount, $iOffset, $aParams);
            
            $i = 0;
            foreach ($aDataMap as $aSeq) {
                $oRs = $this->_oDb->select($aSeq['select']);
                while (!$oRs->EOF) {
                    $this->_processDataMap($oRs, $aSeq, $iOffset + $i++);
                    $oRs->fetchRow();
                }
            }

            if ($this->getFixedOutputFileName()) {
                $sOutputFile = $this->getConfig()->getConfigParam('sShopDir') . 'modules/celebros/conversionpro/export/' . $this->getFixedOutputFileName();
            } else {
                $sOutputFile = $this->getConfig()->getConfigParam('sShopDir') . 'modules/celebros/conversionpro/export/' . $this->getId() . "-" . $sOutputFileHash . ".csv";
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
                            $sFieldWrapper . PHP_EOL;
                    file_put_contents($sOutputFile, $sLine, FILE_APPEND);
                }

                return true;
            }
            else
                return false;
        } else {
            foreach ($this->_aChildPlugins as $oPlugin) {
                $oPlugin->export($sOutputFileHash, $iOffset, $iAmount, $aParams);
            }
        }
    }

    public abstract function getRSSize($aParams);

    protected function _processDataMap($oRs, $aDataMap, $iLineNr) {
        foreach ($aDataMap['content'] as $key => $aValue) {
            $this->_aOutput[$iLineNr][$key] = \Celebros\Conversionpro\Core\CsvInterpret::getValue(null, $oRs, null, $aValue, $iLineNr, $this->_getPluginClass(), null);
        }
    }

    protected abstract function _getDataMap($iLimit, $iOffset, $aParams);

    public abstract function getExportParamInfo();

    public abstract function getTitle();

    public abstract function getDescription();

    public abstract function getId();

    public function _getDelimiter()
    {
        $delimiter = $this->config->getConfigParam('sCel_Export_Delimiter');
        //if ($delimiter == "\t") {
            $delimiter = chr(9);
        //}
        
        return $delimiter ? : "|";
    }

    public function _getFieldWrapper()
    {
        return $this->config->getConfigParam('sCel_Export_FieldWrapper') ? : '';
    }

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

    public function getHeaderLine()
    {
        $header = $this->_getFieldWrapper();
        $header .= implode($this->_getFieldWrapper() . $this->_getDelimiter()  . $this->_getFieldWrapper(), $this->_headerArray);
        $header .= $this->_getFieldWrapper() . PHP_EOL;
        return $header;
    }
}