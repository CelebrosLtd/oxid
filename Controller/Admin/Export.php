<?php
namespace Celebros\Conversionpro\Controller\Admin;

use OxidEsales\Eshop\Application\Controller\Admin\ShopConfiguration;

class Export extends ShopConfiguration //oxAdminView
{
    protected $_aPlugins = array();
    protected $_sOutputFileHash;

    public function render()
    {
        $this->_loadPlugins();

        $this->_aViewData['sSessionId'] = $this->getConfig()->getConfigParam('force_admin_sid');
        $this->_aViewData['sToken'] = $this->getConfig()->getConfigParam('stoken');
        $this->_aViewData['sOutputFileHash'] = md5(microtime());
        $this->_aViewData['aExportedFiles'] = $this->_getExportFileList();
        $this->_aViewData['sShopId'] = $this->getConfig()->getActiveShop()->getId();
        
        return "celebros_export_overview.tpl";
    }

    protected function _loadPlugins()
    {
        $files = glob($this->getViewConfig()->getModulePath('celebros_conversionpro') . 'plugins/export/*.php');
        foreach ($files as $file) {
            require_once($file);
            $oPluginClass = new \ReflectionClass(basename($file, '.php'));
            $oPlugin = $oPluginClass->newInstance();
            if (!$oPlugin->getParentPlugin()) {
                $this->_aPlugins[$oPlugin->getId()] = $oPlugin;
            } else {
                $aChildPlugins[] = $oPlugin;
            }
        }

        foreach ($aChildPlugins as $oPlugin) {
            $this->_aPlugins[$oPlugin->getParentPlugin()]->addChildPlugin($oPlugin);
        }

        $this->_aViewData['aPlugins'] = $this->_aPlugins;
    }

    public function export()
    {
        $this->_loadPlugins();
        $sPluginId = $this->getConfig()->getRequestParameter('pluginId');
        $iOffset = $this->getConfig()->getRequestParameter('iOffset');
        $iAmount = $this->getConfig()->getRequestParameter('iAmount');
        $sOutputFileHash = $this->getConfig()->getRequestParameter('sOutputFileHash');

        foreach ($this->_aPlugins[$sPluginId]->getExportParamInfo() as $sParamName => $sParam) {
            $aParams[$sParamName] = $this->getConfig()->getRequestParameter($sParamName);
        }

        $blResult = $this->_aPlugins[$sPluginId]->export($sOutputFileHash, $iOffset, $iAmount, $aParams);

        die();
    }

    protected function _getExportFileList()
    {
        foreach ($this->_aPlugins as $oPlugin) {

            $aExportedFiles[$oPlugin->getId()] = array();
            $handle = opendir($this->getConfig()->getConfigParam('sShopDir') . 'modules/celebros/conversionpro/export/');
            while (false !== ($file = readdir($handle))) {

                if (!strncmp($file, $oPlugin->getId(), strlen($oPlugin->getId())) ||
                        $file == $oPlugin->getFixedOutputFileName()) {
                    $sFullPath = $this->getConfig()->getConfigParam('sShopDir') . 'modules/celebros/conversionpro/export/' . $file;
                    $sLastModified = date('d.m.Y H:i:s', filemtime($sFullPath));
                    $aExportedFiles[$oPlugin->getId()][$sLastModified] =
                            array('fileName' => $file,
                                'lastModified' => $sLastModified);
                }
                
                if ($oPlugin->getChildPlugins()) {
                    foreach ($oPlugin->getChildPlugins() as $oChildPlugin) {
                        if (!strncmp($file, $oChildPlugin->getId(), strlen($oChildPlugin->getId())) ||
                                $file == $oChildPlugin->getFixedOutputFileName()) {
                            $sFullPath = $this->getConfig()->getConfigParam('sShopDir') . 'modules/celebros/conversionpro/export/' . $file;
                            $sLastModified = date('d.m.Y H:i', filemtime($sFullPath));
                            $aExportedFiles[$oPlugin->getId()][$sLastModified . "-" . $oChildPlugin->getId()] =
                                    array('fileName' => $file,
                                        'lastModified' => $sLastModified);
                        }
                    }
                }
            }
            ksort($aExportedFiles[$oPlugin->getId()], SORT_DESC);
        }

        return $aExportedFiles;
    }

    public function getRSSize()
    {
        $this->_loadPlugins();

        $aParams = array();
        $sPluginId = $this->getConfig()->getRequestParameter('pluginId');
        foreach ($this->_aPlugins[$sPluginId]->getExportParamInfo() as $sParamName => $sParam) {
            $aParams[$sParamName] = $this->getConfig()->getRequestParameter($sParamName);
        }

        $blResult = $this->_aPlugins[$sPluginId]->getRSSize($aParams);

        die($blResult);
    }

    public function finalizeExport()
    {
        $this->_loadPlugins();

        $sPluginId = $this->getConfig()->getRequestParameter('pluginId');

        $this->_aPlugins[$sPluginId]->afterExport();
        die();
    }

}