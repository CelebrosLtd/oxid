<?php

/*
 *  Developed by webfrisch.de
 *  Author: Lukas Dierks <lukas.dierks at webfrisch.de>
 *  Date: Aug 27, 2013
 */

class celebros_maintenance extends celebros_maintenance_parent {

    public function execute() {
        parent::execute();

        $sCelebrosLastUpdate = oxConfig::getInstance()->getShopConfVar('sCelebrosLastUpdate');
        $iTimeDiff = time() - $sCelebrosLastUpdate;
        $sUpdateInterval = oxConfig::getInstance()->getShopConfVar('sCel_synchroTimeInterval');
        if ($iTimeDiff > $sUpdateInterval * 60) {
            echo "Synchonizing Celebros data...";
            $aPlugins = $this->_loadPlugins();
            $iOffset = 0;
            $iAmount = PHP_INT_MAX;
            $sOutputFileHash = "cronJob";

            foreach ($aPlugins as $oPlugin) {
                var_dump($oPlugin);
                $oPlugin->export($sOutputFileHash, $iOffset, $iAmount, $aParams);
                $oPlugin->afterExport();
            }
            
            celebros_upload::uploadExportFiles();

            oxConfig::getInstance()->saveShopConfVar("String", "sCelebrosLastUpdate", time());
            
            echo "done. <br/>";
        }
    }
    
    

    protected function _loadPlugins() {
        $files = glob(oxConfig::getInstance()->getShopConfVar('sShopDir') . 'modules/eins_celebros/plugins/export/*.php');

        foreach ($files as $file) {

            require_once($file);
            $oPluginClass = new ReflectionClass(basename($file, '.php'));
            $oPlugin = $oPluginClass->newInstance();
            if (!$oPlugin->getParentPlugin()) {
                $aPlugins[$oPlugin->getId()] = $oPlugin;
            } else {
                $aChildPlugins[] = $oPlugin;
            }
        }

        foreach ($aChildPlugins as $oPlugin) {
            $aPlugins[$oPlugin->getParentPlugin()]->addChildPlugin($oPlugin);
        }

        return $aPlugins;
    }

}

?>
