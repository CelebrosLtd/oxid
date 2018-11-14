<?php

/*
 *  Developed by webfrisch.de
 *  Author: Lukas Dierks <lukas.dierks at webfrisch.de>
 *  Date: Sep 20, 2013
 */

class celebros_global_view_tools extends celebros_global_view_tools_parent {
    public function isCelAnalyticsEnabled() {
        return oxConfig::getInstance()->getShopConfVar("sCel_analyticsEnabled");
    }
    
    public function isLiveSightEnabled() {
        return oxConfig::getInstance()->getShopConfVar("sCel_liveSightEnabled");
    }
    
    public function getCelebrosCustomerId() {
        return oxConfig::getInstance()->getShopConfVar("sCel_SiteKey");
    }
    
    public function getCelebrosServiceHost() {
        return oxConfig::getInstance()->getShopConfVar("sCel_ServiceHost");
    }
    
    public function getCelebrosSearchSessionId() {
        return oxSession::getInstance()->getVar('sCelSearchSessionId');
    }
    
    public function getCelebrosSearchLogHandle() {
        return oxSession::getInstance()->getVar('sCelLogHandle');
    }
    
    public function getHTTPReferer() {
        return $_SERVER['HTTP_REFERER'];
    }
    
    public function getWebsessionId() {
        return oxSession::getInstance()->getId();
    }
    
    public function getMinPage($iActPage, $offset) {
        return $iActPage - $offset;
    }
    
    public function getMaxPage($iActPage, $offset) {
        return $iActPage + $offset;
    }
    
    public function getToolboxScript() {
        $sSearchServiceURL = $this->getCelebrosServiceHost();
        
        return "http://{$sSearchServiceURL}/UITemplate/ScriptsJ/JQuery/CelebrosToolbox.js";
    }
}
?>
