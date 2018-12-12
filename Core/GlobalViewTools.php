<?php
namespace Celebros\Conversionpro\Core;

class GlobalViewTools extends GlobalViewTools_parent
{
    public function getSession()
    {
        return \OxidEsales\Eshop\Core\Registry::getSession();
    }
    
    public function isCelAnalyticsEnabled()
    {
        if ($this->getConfig()->getShopConfVar("sCel_AnalyticsUrl")
        && $this->getConfig()->getShopConfVar("sCel_AnalyticsCustomerId")
        && $this->getConfig()->getShopConfVar("sCel_AnalyticsCustomerName")) {
            return true;
        }
        
        return false;
    }
    
    public function isLiveSightEnabled()
    {
        return $this->getConfig()->getShopConfVar("sCel_liveSightEnabled");
    }
    
    public function getCelebrosCustomerId()
    {
        return $this->getConfig()->getShopConfVar("sCel_SiteKey");
    }
    
    public function getCelebrosServiceHost()
    {
        return $this->getConfig()->getShopConfVar("sCel_ServiceHost");
    }
    
    public function getCelebrosSearchSessionId()
    {
        return $this->getSession()->getVariable('sCelSearchSessionId');
    }
    
    public function getCelebrosSearchLogHandle()
    {
        return $this->getSession()->getVariable('sCelLogHandle');
    }
    
    public function getCelebrosAnalyticsHost()
    {
        return $this->getConfig()->getShopConfVar("sCel_AnalyticsUrl");
    }
    
    public function getHTTPReferer()
    {
        return $_SERVER['HTTP_REFERER'];
    }
    
    public function getWebsessionId()
    {
        return $this->getSession()->getId();
    }
    
    public function getMinPage($iActPage, $offset)
    {
        return $iActPage - $offset;
    }
    
    public function getMaxPage($iActPage, $offset)
    {
        return $iActPage + $offset;
    }
    
    public function getAnalyticsCustomerId()
    {
        return $this->getConfig()->getShopConfVar("sCel_AnalyticsCustomerId");
    }    
    
    public function getToolboxScript()
    {
        $sSearchServiceURL = $this->getCelebrosAnalyticsHost();
        return "https://{$sSearchServiceURL}/widgets/CelebrosToolbox.js";
    }
    
    public function getACScriptUrl()
    {
        $acHost = $this->getConfig()->getConfigParam('sCel_AutocompleteUrl');
        return "https://{$acHost}/AutoComplete/Scripts/CelebrosAutoCompleteV3e.js";   
    }
}
