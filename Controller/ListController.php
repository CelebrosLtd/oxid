<?php
namespace Celebros\Conversionpro\Controller;

class ListController extends ListController_parent
{
    protected $_aBannerCampaigns = array();
    
    public function render()
    {
        //Set data for banner campaign
        if (count($this->_aBannerCampaigns)) {
            $this->_aViewData['aBannerCampaigns'] = $this->_aBannerCampaigns;
        }
        
        $iCategorySearchMode = $this->getConfig()->getShopConfVar("sCel_CategorySearchMode");
        if ($iCategorySearchMode == 0) {
            return parent::render();
        } elseif ($iCategorySearchMode == 1) {
            parent::render();
            
            $oCategory = $this->getActiveCategory();
            $oCelebrosSearch = oxNew('search');
            
            $this->_aViewData['oView'] = $oCelebrosSearch;
            $sReturnValue = $oCelebrosSearch->render($oCategory->oxcategories__oxtitle->getRawValue());
            foreach($oCelebrosSearch->_aViewData as $key => $oViewData) {
                $this->_aViewData[$key] = $oViewData;
            }
            return $sReturnValue;
        } elseif ($iCategorySearchMode == 2) {
            parent::render();
            
            $oCategory = $this->getActiveCategory();
            $sSearchStr = $oCategory->oxcategories__oxtitle->getRawValue();
            while (($oCategory = $oCategory->getParentCategory()) != NULL ) {
                $sSearchStr = $oCategory->oxcategories__oxtitle->getRawValue() . "+" . $sSearchStr;
            }
            
            $oCelebrosSearch = oxNew('\Celebros\Conversionpro\Controller\SearchController');
            
            $this->_aViewData['oView'] = $oCelebrosSearch;
            $sReturnValue = $oCelebrosSearch->render($sSearchStr);
            foreach ($oCelebrosSearch->_aViewData as $key => $oViewData) {
                $this->_aViewData[$key] = $oViewData;
            }
            
            return $sReturnValue;
        }
    }
    
    /**
     * adds dynamic property to campaigns class member collection
     *
     * @param object $propertiesBag dynamic property
     *
     * @return bool $bCampaignAdded is camaign was added
     */
    protected function _addCampaign($propertiesBag)
    {
        $bCampaignAdded = false;
        if (isset($propertiesBag->banner_landing_page) || isset($propertiesBag->banner_image)) {
            $this->_aBannerCampaigns[] = $propertiesBag;
            $bCampaignAdded = true;
        } elseif (isset($propertiesBag->alternative_products)) {
            $this->_aAlternativeProductsCampaigns[] = $propertiesBag;
            $bCampaignAdded = true;
        } elseif (isset($propertiesBag->redirection_url)) {
            $this->_aRedirectCampaigns[] = $propertiesBag;
            $bCampaignAdded = true;
        } elseif (isset($propertiesBag->custom_message)) {
            $this->_aCustomMsgs[] = $propertiesBag;
            $bCampaignAdded = true;
        }
        
        return $bCampaignAdded;
    }
    
    /**
     * gets banner campaign array
     *
     * @return array banner campaign array
     */
    public function getBannerCampaigns() {
        return $this->_aBannerCampaigns;
    }
}
