<?php
namespace Celebros\Conversionpro\Controller\Admin;

use OxidEsales\Eshop\Application\Controller\Admin\ShopConfiguration;
use OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController;
use OxidEsales\Eshop\Core\Module\Module;
use OxidEsales\Eshop\Core\Registry;

class Settings extends ShopConfiguration //oxAdminView
{
    protected $_sThisTemplate = 'celebros_settings.tpl';
    
    protected $_fieldsTable = null;
    
    public function init()
    {
        parent::init();
        //$this->setShopId();
        //$this->setInitialConfigValues();
    }
    
    public function getIncludedSortingFields() {
        $aResult = array("" => "");
        $fieldsTable = $this->_getMappingTable();
        foreach ($fieldsTable as $record) {
            if ($record["include"]) {
                $salesperson_field_name = $record["salesperson_field_name"];
                $aResult[$salesperson_field_name] = $salesperson_field_name;
            }
        }
        return $aResult;
    }

    /**
     * Gets list of none lead questions
     *
     * @return array $aResult
     */
    public function getMaxNoneLeadQuestions() {
        $aResult = array("" => "Display full list");
        for ($i = 1; $i <= 9; $i++) {
            $aResult[$i] = $i;
        }
        return $aResult;
    }

    /**
     * Gets list of none lead answers
     *
     * @return array $aResult
     */
    public function getMaxNoneLeadAnswers() {
        $aResult = array("" => "Display full list");
        for ($i = 1; $i <= 9; $i++) {
            $aResult[$i] = $i;
        }
        return $aResult;
    }

    /**
     * Gets list of lead answers
     *
     * @return array $aResult
     */
    public function getMaxLeadAnswers() {
        $aResult = array("" => "Display full list");
        for ($i = 1; $i <= 9; $i++) {
            $aResult[$i] = $i;
        }
        return $aResult;
    }

    /**
     * Gets oxarticle table fields
     *
     * @return array $fields
     */
    protected function _getOxarticlesFields() {
        $fields = array();

        $oDb = oxDb::getDb();
        $rs = $oDb->Execute("show columns from oxarticles");
        if ($rs) {
            //$oStr = getStr();
            while (!$rs->EOF) {
                $fieldName = $rs->fields[0];
                $fields[$fieldName] = $fieldName;
                $rs->moveNext();
            }
        }

//        $rs = $oDb->Execute("show columns from oxartextends");
//        if ($rs) {
//            //$oStr = getStr();
//            while (!$rs->EOF) {
//                $fieldName = $rs->fields[0];
//                if ($fieldName != "OXID")
//                    $fields[$fieldName] = $fieldName;
//                $rs->moveNext();
//            }
//        }

        return $fields;
    }

    /**
     * Sets shop id
     *
     * @return null
     */
    public function setShopId() {

        $myConfig = $this->getConfig();

        $soxId = oxConfig::getParameter("oxid");
        if (!$soxId)
            $soxId = $myConfig->getShopId();

        $soxId = $this->setSavedOxidId($soxId);

        //default shop setting
        //if( ($soxId == "-1" || !isset( $soxId))) $soxId = "oxbaseshop";
        $_GET["oxid"] = $soxId;
    }

    /**
     * Sets saved shop id
     *
     * @param string $soxId shop id
     *
     * @return string $soxId
     */
    public function setSavedOxidId($soxId) {
        $sSavedID = oxConfig::getParameter("saved_oxid");
        if (($soxId == "-1" || !isset($soxId)) && isset($sSavedID)) {
            $soxId = $sSavedID;
            oxSession::deleteVar("saved_oxid");
            $this->_aViewData["oxid"] = $soxId;
            // for reloading upper frame
            $this->_aViewData["updatelist"] = "1";
        }
        return $soxId;
    }

    /**
     * Sets initial config value
     *
     * @param string $sVarType config variable type
     * @param string $sVarName config variable name
     * @param string $sVarVal config variable value
     * @param string $sOxId shop id
     *
     * @return null
     */
    public function setInitialConfigValue($sVarType, $sVarName, $sVarVal, $sOxId) {
        $myConfig = $this->getConfig();
        if (is_null($myConfig->getConfigParam($sVarName)) || $myConfig->getParameter("reset_mapping"))
            $myConfig->saveShopConfVar($sVarType, $sVarName, $sVarVal, $sOxId);
    }

   /**
     * Gets Oxid => Celebros mapping table
     *
     * @return array $fieldsTable
     */
    protected function _getMappingTable() {
        if (isset($this->_fieldsTable))
            return $this->_fieldsTable;

        $myConfig = $this->getConfig();

        $aMappingsalespersonfields = $myConfig->getConfigParam('mappingsalespersonfields');
        $aMappingrequiredfields = $myConfig->getConfigParam('mappingrequiredfields');
        $aMappingincludefields = $myConfig->getConfigParam('mappingincludefields');
        $aMappingisnumericsortfields = $myConfig->getConfigParam('mappingisnumericsortfields');

        $fieldsTable = array();

        if (is_null($aMappingsalespersonfields))
            return $fieldsTable;

        foreach ($aMappingsalespersonfields as $key => $value) {
            $record = array();
            $record["oxid_field_name"] = $key;
            $record["salesperson_field_name"] = $value;
            $record["salesperson_field_required"] = isset($aMappingrequiredfields[$key]) ? $aMappingrequiredfields[$key] : false;
            $record["include"] = $aMappingincludefields[$key];
            $record["is_numeric_sort"] = $aMappingisnumericsortfields[$key];
            $fieldsTable[] = $record;
        }

        //Add unmapped fields to $fieldsTable
        $fieldsTable = $this->_addUnmapedFields($fieldsTable, $aMappingsalespersonfields);
        return $fieldsTable;
    }    
    
}
