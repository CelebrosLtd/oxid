<?php

/**
 * Celebros Salesperson - Oxid Extension
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish correct extension functionality. 
 * If you wish to customize it, please contact Celebros.
 *
 * @category    Celebros
 * @package     Celebros_Salesperson
 * @author		Celebros, Inc (email: OxidSupport@celebros.com)
 *
 */
require_once(dirname(__FILE__) . '/PHP5/ANLX_API/AnalyticsFunctions.php');

/**
 * Adds some Celebros functionality to the shop
 */
class eins_celebros_oxshop extends eins_celebros_oxshop_parent {

    /**
     * @public object celebros analytics class
     */
    protected $_oAnalytics = null;
    public $cel_is_active = "true";

    /**
     * Constructor, sets class members
     *
     * @return null
     */
    public function __construct() {

        parent::__construct(null);
        $this->_cel_GetSourceType();
        // add testing stuff here
    }

    /**
     * analytics object getter
     * 
     * @return object
     */
    public function cel_GetAnalytics() {

        if ($this->_oAnalytics == null) {
            $this->_oAnalytics = new AnalyticsFunctions(
                    $this->getConfig()->getConfigParam('sCel_AnalyticsUrl'), $this->getConfig()->getConfigParam('sCel_AnalyticsCustomerId'), $this->getConfig()->getConfigParam('sCel_AnalyticsCustomerName'), "", $this->getConfig()->getConfigParam('bIsSSL')
            );
        }

        return $this->_oAnalytics;
    }

    /**
     * returns celebros analytics code for detail page
     * 
     * @param object $oArticle oxarticle object
     * @return string $sSnippet Celebros analytics oixel snippet 
     */
    public function cel_GetCADetailsLink($oArticle = null) {

        // no article
        if (!($oArticle instanceof oxarticle)) {
            return '';
        }

        // getting current category
        if ($oView = $this->getConfig()->getActiveView()) {
            $oCurrenctCategory = $oView->getActCategory();
        }
        // fallback if view or category not available
        if (!$oCurrenctCategory) {
            $oCurrenctCategory = $oArticle->getCategory();
        }

        // TODO: source has to be different for different actions
        $sSnippet = $this->cel_GetAnalytics()->Celebros_Analytics_ProductDetails(
                $oArticle->getId(), $oArticle->oxarticles__oxvarselect->value, $oArticle->oxarticles__oxtitle->value, $oArticle->getPrice()->getBruttoPrice(), $oCurrenctCategory->oxcategories__oxtitle->value, $this->_cel_GetSearchSessionId(), $this->_cel_GetUserId(), $this->_cel_GetGroupId(), $this->getSession()->getId(), $_SERVER['HTTP_REFERER'], $this->_cel_GetSourceType(), $this->_cel_GetSourceName(), (int) $this->getConfig()->isSsl()
        );

        return $sSnippet;
    }

    /**
     * returns sourcetype that linked to the current page
     * 
     * return values can be:
     * 0 -> Qwiser Search 
     * 1 -> Banner/Ad on web page 
     * 2 -> Browse (the shopper did not use the search) 
     * 3 -> E-Mail promotion 
     * 4 -> Direct Mailing (Hard copy catalog, etc.) 
     * 5 -> Print (Newspapers, periodicals, in-flight magcel_ines, etc.) 
     * 6 -> Television 
     * 100 -> other sources
     * 
     * @NOTE: referer is not available when using ssl
     * 
     * @return integer $iType
     */
    protected function _cel_GetSourceType() {

        $iType = 2;

        if (!$_SERVER['HTTP_REFERER']) {
            return $iType;
        }

        $aReferrer = parse_url($_SERVER['HTTP_REFERER']);

        if ($aReferrer['host'] != $_SERVER['HTTP_HOST']) {
            // other sources
            $iType = 100;
        } elseif (strpos($aReferrer['query'], 'cel_qwiser') !== false) {
            // Qwiser Search 
            $iType = 0;
        } else {
            // add more cases here if needed
        }

        return $iType;
    }

    /**
     * returns the name of the source that linked to the current page
     * 
     * @return string
     */
    protected function _cel_GetSourceName() {

        $sName = '';

        // type 100 means "other" source, a source that is not predefined
        if ($this->_cel_GetSourceType() == 100) {
            $aReferrer = parse_url($_SERVER['HTTP_REFERER']);
            $sName = $aReferrer['host'];
        }

        return (string) $sName;
    }

    /**
     * returns celebros analytics code for celebros result page
     * 
     * @return string $sSnippet
     */
    public function cel_GetCASearchResultsLink() {

        $sSnippet = $this->cel_GetAnalytics()->Celebros_Analytics_SearchResults(
                $this->_cel_GetSearchSessionId(), $this->_cel_GetLogHandle(), $this->_cel_GetUserId(), $this->_cel_GetGroupId(), $this->getSession()->getId(), $_SERVER['HTTP_REFERER'], (int) $this->getConfig()->isSsl(), true
        );

        return $sSnippet;
    }

    /**
     * returns default link. just to track the visit of this page
     * 
     * @return string $sSnippet
     */
    public function cel_GetCAVisitLink() {

        $sSnippet = $this->cel_GetAnalytics()->Celebros_Analytics_Visit(
                $this->_cel_GetUserId(), $this->_cel_GetGroupId(), $this->getSession()->getId(), $_SERVER['HTTP_REFERER'], (int) $this->getConfig()->isSsl()
        );

        return $sSnippet;
    }

    /**
     * returns celebros log handle
     * 
     * @return string $sLogHandle
     */
    protected function _cel_GetLogHandle() {

        $sLogHandle = '';

        if ($oCelebros = $this->_cel_GetCelebrosSearchObject()) {
            $sLogHandle = $oCelebros->LogHandle;
        }

        // TODO: remove this code after celebros fixed the bug, currently no empty string can be used
        if (!$sLogHandle) {
            // this aint no joke, see documentation of celebros ;) citation: you may pass an empty ("") String.
            $sLogHandle = '""';
        }

        return (string) $sLogHandle;
    }

    /**
     * returns celebros user group
     * @NOTE currently this feature is not implemented, so returning default value 1
     * 
     * @return string
     */
    protected function _cel_GetGroupId() {
        return (string) 1;
    }

    /**
     * gets celebros search session id if available. if not returns empty string
     * 
     * @return string
     */
    protected function _cel_GetSearchSessionId() {

        $sSearchSessionId = '';

        if ($oCelebros = $this->_cel_GetCelebrosSearchObject()) {
            $sSearchSessionId = $oCelebros->SearchInformation->SessionId;
        }

        // TODO: remove this code after celebros fixed the bug, currently no empty string can be used
        if (!$sSearchSessionId) {
            // this aint no joke, see documentation of celebros ;) citation: you may pass an empty ("") String.
            $sSearchSessionId = '""';
        }

        return (string) $sSearchSessionId;
    }

    /**
     * returns last celebros search object if available
     * 
     * @return stdclass $oCelebros
     */
    protected function _cel_GetCelebrosSearchObject() {

        $oCelebros = null;

        if ($this->getSession()->hasVar('cel_oCelebros')) {
            $oCelebros = $this->getSession()->getVar('cel_oCelebros');
        }

        return $oCelebros;
    }

    /**
     * gets userid for celebros. if user is not logged in, the sessionid will be returned
     * 
     * @return string
     */
    protected function _cel_GetUserId() {

        $sId = $this->getSession()->getId();

        if ($this->getUser()) {
            $sId = $this->getUser()->getId();
        }

        return (string) $sId;
    }

    /**
     * gets link to Celebros Autocomplete service
     *
     * @return string
     */
    public function cel_GetAutocompleteLink() {
        return $this->getConfig()->getConfigParam('sCel_AutocompleteUrl');
    }

    /**
     * gets customer name to pass to Celebros Autocomplete service
     *
     * @return string
     */
    public function cel_GetAutocompleteCustomerName() {
        return $this->getConfig()->getConfigParam('sCel_CustomerName');
    }

}
