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

/**
 * Celebros PHP API for connecting and getting the search results for the shop.
 */
 namespace Celebros\Conversionpro\Core\Celebros;
 
class QwiserAPI extends \OxidEsales\EshopCommunity\Core\Base
{
	// Qwiser v4 webservice data
	public $sWebServiceUrl;
	public $sSiteKey;

	public $sSearchHandle;
	public $oSearchResponce;

	// Qwiser state
	public $sLastOperationErrorMessage;
	public $blLastOperationSucceeded;

	/**
	 * Constructor, sets class members
	 *
	 * @return null
	 */	
	function __construct()
	{     
	    $this->sSiteKey = $this->getConfig()->getConfigParam('sCel_SiteKey');
	    $this->host = $this->getConfig()->getConfigParam('sCel_ServiceHost');
	    $this->port = $this->getConfig()->getConfigParam('sCel_ServicePort');
		$this->sWebServiceUrl = "http://{$this->host}:{$this->port}";
		
		$this->blLastOperationSucceeded = 1;
	    $this->LastOperationErrorMessage = '';
	}


	/**
	 * Gets the results for the specified search term.
	 *
	 * @param string $sQuery search query
	 *
	 * @return object $oResult - search result object
	 */    
	function Search($sQuery)
	{
		$sQuery = rawurlencode(utf8_encode($sQuery));
		$sRequestUrl = "Query=" . $sQuery;
		return $this->GetResult($sRequestUrl,__FUNCTION__);
	}

	/**
	 * Gets the results for the specified search term under the specified search profile and the answer which Id was specified.
	 *
	 * @param string $sQuery search query
	 * @param string $sSearchProfile search profile as it defined on Celebros server
	 * @param integer $iAnswerId answer id as it defined on Celebros server
	 * @param string $sEffectOnSearchPath effect on search path as it defined on Celebros server
	 * @param string $sPriceColumn search query price field name as it defined on Celebros server
	 * @param integer $iPageSize search results page size
	 * @param string $sSortingfield sort field name as it defined on Celebros server
	 * @param bool $bNumericsort is to perform numeric search rather than alpanumeric
	 * @param bool $bAscending search query is to sort ascendingly
	 *       
	 * @return object $oResult - search result object
	 */	
    function SearchAdvance($sQuery,$sSearchProfile,$iAnswerId,$sEffectOnSearchPath,$sPriceColumn,$iPageSize,$sSortingfield,$bNumericsort,$bAscending)
    {
        $sQuery = rawurlencode($sQuery);
        $sSearchProfile = urlencode($sSearchProfile);
        $sSortingfield = urlencode($sSortingfield);
        $sPriceColumn = urlencode($sPriceColumn);
        $sRequestUrl = "Query=".$sQuery."&SearchProfile=".$sSearchProfile."&AnswerId=".$iAnswerId."&EffectOnSearchPath=".$sEffectOnSearchPath."&PriceColumn=".$sPriceColumn."&PageSize=".$iPageSize."&Sortingfield=".$sSortingfield."&Numericsort=".$bNumericsort."&Ascending=".$bAscending;
        return $this->GetResult($sRequestUrl,__FUNCTION__);
    }

    /**
     * Activate search Profile
     * 
     * @param string $sSearchHandle Celebros search handler
	 * @param string $sSearchProfile search profile as it defined on Celebros server
     *
     * @return object $oResult - search result object
     */    
    function ActivateProfile($sSearchHandle,$sSearchProfile)
    {
        $sSearchProfile = urlencode($sSearchProfile);
        $sRequestUrl = "SearchHandle=".$sSearchHandle."&SearchProfile=".$sSearchProfile;
        return $this->GetResult($sRequestUrl,__FUNCTION__);
    }

    /**
     * Answer Question
     * 
     * @param string $sSearchHandle Celebros search handler
     * @param integer $iAnswerId answer id as it defined on Celebros server
     * @param string $sEffectOnSearchPath effect on search path as it defined on Celebros server
     *
     * @return object $oResult - search result object
     */    
    function AnswerQuestion($sSearchHandle,$iAnswerId,$sEffectOnSearchPath)
    {
        $sRequestUrl = "SearchHandle=".$sSearchHandle."&answerId=".$iAnswerId."&EffectOnSearchPath=".$sEffectOnSearchPath;
        return $this->GetResult($sRequestUrl,__FUNCTION__);
    }
    
    /**
     * Change Number of Products in Page
     *
     * @param string $sSearchHandle Celebros search handler
	 * @param integer $iPageSize search results page size
     *
     * @return object $oResult - search result object
     */    
    function ChangePageSize($sSearchHandle,$iPageSize)
    {
        $sRequestUrl = "SearchHandle=".$sSearchHandle."&pageSize=".$iPageSize;
        return $this->GetResult($sRequestUrl,__FUNCTION__);
    }

    /**
     * Change the search default price
     *
     * @param string $sSearchHandle Celebros search handler
     * @param string $sPriceColumn search query price field name as it defined on Celebros server
     *
     * @return object $oResult - search result object
     */    
    function ChangePriceColumn($sSearchHandle,$sPriceColumn)
    {
        $sRequestUrl = "SearchHandle=".$sSearchHandle."&PriceColumn=".$sPriceColumn;
        return $this->GetResult($sRequestUrl,__FUNCTION__); 
    }
    
    /**
     * Deactivate Search Profile
     *
     * @param string $sSearchHandle Celebros search handler
     *
     * @return object $oResult - search result object
     */
    function DeactivateProfile($sSearchHandle)
    {
        $sRequestUrl = "SearchHandle=".$sSearchHandle;
        return $this->GetResult($sRequestUrl,__FUNCTION__);
    }

    /**
     * Moves to the first page of the results
     *
     * @param string $sSearchHandle Celebros search handler
     *
     * @return object $oResult - search result object
     */    
    function FirstPage($sSearchHandle)
    {
        $sRequestUrl = "SearchHandle=".$sSearchHandle;
        return $this->GetResult($sRequestUrl,__FUNCTION__);
    }
    
    /**
     * Forces the BQF to allow the specified question to appear first
     *
     * @param string $sSearchHandle Celebros search handler
     * @param string $iQuestionId question id as it defined on Celebros server
     *
     * @return object $oResult - search result object
     */    
    function ForceQuestionAsFirst($sSearchHandle,$iQuestionId)
    {
        $sRequestUrl = "SearchHandle=".$sSearchHandle."&QuestionId=".$iQuestionId;
        return $this->GetResult($sRequestUrl,__FUNCTION__); 
    }

    /**
     * Get all the product fields
     *
     * @return object $oResult - search result object
     */    
    function GetAllProductFields()
    {
        return $this->GetResult("",__FUNCTION__); 
    }

    /**
     * Return all the questions
     *
     * @return object $oResult - search result object
     */    
    function GetAllQuestions()
    {
        return $this->GetResult("",__FUNCTION__); 
    }

    /**
     * Return all search profiles
     *
     * @return object $oResult - search result object
     */     
    function GetAllSearchProfiles()
    {
        return $this->GetResult("",__FUNCTION__);    
    }
    
    /**
     * Gets the results for the specified search handle
     *
     * @param string $sSearchHandle Celebros current search handler
     * @param bool $bNewSearch is it a new search or not
     * @param string $sPreviousSearchHandle Celebros previous search handler
     *
     * @return object $oResult - search result object
     */    
    function GetCustomResults($sSearchHandle,$bNewSearch,$sPreviousSearchHandle)
    {
        $sRequestUrl = "SearchHandle=".$sSearchHandle."&NewSearch=".$bNewSearch."&PreviousSearchHandle=".$sPreviousSearchHandle;
        return $this->GetResult($sRequestUrl,__FUNCTION__); 
    }
    
    /**
     * Gets Engine Status
     *
     * @return object $oResult - search result object
     */    
    function GetEngineStatus()
    {
        return $this->GetResult("",__FUNCTION__);  
    }
    
    /**
     * Gets all the answers that a product exists in
     *
     * @param string $sSku sku of product
     *
     * @return object $oResult - search result object
     */    
    function GetProductAnswers($sSku)
    {
        $sSku = urlencode($sSku);
        $sRequestUrl = "Sku=".$sSku;
        return $this->GetResult($sRequestUrl,__FUNCTION__);
    }
    
    /**
     * Gets the full path to the best answer for this product under the selected question for the "View All" feature (in the SPD).
     *
     * @param string $sSku sku of product
     *
     * @return object $oResult - search result object
     */
    function GetProductSearchPath($sSku)
    {
        $sSku = urlencode($sSku);
        $sRequestUrl = "Sku=".$sSku;
        return $this->GetResult($sRequestUrl,__FUNCTION__);
    }
    
    /**
     * Returns the answers for a specific question
     *
     * @param string $iQuestionId question id as it defined on Celebros server
     * 
     * @return object $oResult - search result object
     */    
    function GetQuestionAnswers($iQuestionId)
    {
        $sRequestUrl = "QuestionId=".$iQuestionId;
        return $this->GetResult($sRequestUrl,__FUNCTION__);   
    }
    
    /**
     * return all the question ampped to the given search profile
     *
     * @param string $sSearchProfile search profile as it defined on Celebros server
     * 
     * @return object $oResult - search result object
     */    
    function GetSearchProfileQuestions($sSearchProfile)
    {
        $sSearchProfile = urlencode($sSearchProfile);
        $sRequestUrl = "SearchProfile=".$sSearchProfile;
        return $this->GetResult($sRequestUrl,__FUNCTION__);
    }

    /**
     * Gets all the answers a collection of products exist in.
     *
     * @param string $sSkus skus of products
     *
     * @return object $oResult - search result object
     */    
    function GetSeveralProductsAnswers($sSkus)
    {
        $sRequestUrl = "Skus=".$sSkus;
        return $this->GetResult($sRequestUrl,__FUNCTION__);
    }

    /**
     * Return the LastPage.
     *
     * @param string $sSearchHandle Celebros search handler
     *
     * @return object $oResult - search result object
     */    
    function LastPage($sSearchHandle)
    {
        $sRequestUrl = "SearchHandle=".$sSearchHandle;
        return $this->GetResult($sRequestUrl,__FUNCTION__);
    }

    /**
     * Moves to the specified page of the results
     *
     * @param string $sSearchHandle Celebros search handler
     * @param integer $iPage search results page number
     *
     * @return object $oResult - search result object
     */    
    function MoveToPage($sSearchHandle,$iPage)
    {
        $sRequestUrl = "SearchHandle=".$sSearchHandle."&Page=".$iPage;
        return $this->GetResult($sRequestUrl,__FUNCTION__);
    }
    
    /**
     * Moves to the previous page of the results 
     *
     * @param string $sSearchHandle Celebros search handler
     *
     * @return object $oResult - search result object
     */    
    function PreviousPage($sSearchHandle)
    {
        $sRequestUrl = "SearchHandle=".$sSearchHandle;
        return $this->GetResult($sRequestUrl,__FUNCTION__);
    }
    
    /**
     * Moves to the next page of the results 
     *
     * @param string $sSearchHandle Celebros search handler
     *
     * @return object $oResult - search result object
     */    
    function NextPage($sSearchHandle)
    {
        $sRequestUrl = "SearchHandle=".$sSearchHandle;
        return $this->GetResult($sRequestUrl,__FUNCTION__);
    }
    
    /**
     * Removes the specified answer from the list of answered answers in this session.
     *
     * @param string $sSearchHandle Celebros search handler
     * @param integer $iAnswerId answer id as it defined on Celebros server
     *
     * @return object $oResult - search result object
     */    
    function RemoveAnswer($sSearchHandle,$iAnswerId)
    {
        $sRequestUrl = "SearchHandle=".$sSearchHandle."&AnswerId=".$iAnswerId;
        return $this->GetResult($sRequestUrl,__FUNCTION__);
    }
    
    /**
     * Removes the specified answers from the list of answered answers in this session. 
     *
     * @param string $sSearchHandle Celebros search handler
     * @param integer $iAnswerIndex answer index as it returned by Celebros server
     *
     * @return object $oResult - search result object
     */    
    function RemoveAnswerAt($sSearchHandle,$iAnswerIndex)
    {
        $sRequestUrl = "SearchHandle=".$sSearchHandle."&AnswerIndex=".$iAnswerIndex;
        return $this->GetResult($sRequestUrl,__FUNCTION__);
    }

    /**
     * Removes the specified answers from the list of answered answers in this session. 
     *
     * @param string $sSearchHandle Celebros search handler
     * @param integer $iAnswerIds answer ids as it defined on Celebros server
     *
     * @return object $oResult - search result object
     */    
    function RemoveAnswers($sSearchHandle,$sAnswerIds)
    {
        $sRequestUrl = "SearchHandle=".$sSearchHandle."&AnswerIds=".$sAnswerIds;
        return $this->GetResult($sRequestUrl,__FUNCTION__);
    }
    
    /**
     * Remove the all the answer from the search information form the given index
     *
     * @param string $sSearchHandle Celebros search handler
     * @param integer $iStartIndex starting index of the answer as it returned from Celebros server
     *
     * @return object $oResult - search result object
     */    
    function RemoveAnswersFrom($sSearchHandle,$iStartIndex)
    {
        $sRequestUrl = "SearchHandle=".$sSearchHandle."&StartIndex=".$iStartIndex;
        return $this->GetResult($sRequestUrl,__FUNCTION__);
    }
    
    /**
     * Marks a product as out of stock.
     *
     * @param string $sSku sku of product
     *
     * @return object $oResult - search result object
     */    
    function RemoveProductFromStock($sSku)
    {
        $sSku = urlencode($sSku);
        $sRequestUrl = "Sku=".$sSku;
        return $this->GetResult($sRequestUrl,__FUNCTION__);
    }
    
    /**
     * Marks a product as in stock.
     *
     * @param string $sSku sku of product
     *
     * @return object $oResult - search result object
     */    
    function RestoreProductToStock($sSku)
    {
        $sSku = urlencode($sSku);
        $sRequestUrl = "Sku=".$sSku;
        return $this->GetResult($sRequestUrl,__FUNCTION__);
    }
    
    /**
     * Changes the sorting of the results to display products by the value of the specified field, and whether to perform a numeric sort on that field, in the specified sorting direction.
     *
     * @param string $sSearchHandle Celebros search handler
	 * @param string $sFieldName sorting field name as it defined on Celebros server
	 * @param bool $bNumericsort is to perform numeric search rather than alpanumeric
	 * @param bool $bAscending search query is to sort ascendingly
     *
     * @return object $oResult - search result object
     */
    function SortByField($sSearchHandle,$sFieldName,$bNumericSort,$bAscending)
    {
        $sFieldName = urlencode($sFieldName);
        $sRequestUrl = "SearchHandle=".$sSearchHandle."&FieldName=".$sFieldName."&NumericSort=".$bNumericSort."&Ascending=".$bAscending;
        return $this->GetResult($sRequestUrl,__FUNCTION__);
    }
    
    /**
     * Changes the sorting of the results to display products by their price in the specified sorting direction
     *
     * @param string $sSearchHandle Celebros search handler
     * @param bool $bAscending search query is to sort ascendingly
     *
     * @return object $oResult - search result object
     */
    function SortByPrice($sSearchHandle,$bAscending)
    {
        $sRequestUrl = "SearchHandle=".$sSearchHandle."&Ascending=".$bAscending;
        return $this->GetResult($sRequestUrl,__FUNCTION__);
    }
    
    /**
     * Changes the sorting of the results to display products by relevancy in descending order.
     *
     * @param string $sSearchHandle Celebros search handler
     *
     * @return object $oResult - search result object
     */    
    function SortByRelevancy($sSearchHandle)
    {
        $sRequestUrl = "SearchHandle=".$sSearchHandle;
        return $this->GetResult($sRequestUrl,__FUNCTION__);
    }
    
    /**
     * Gets search results from Celebros servers
     *
     * @param string $sRequestUrl Celebros request url
     * @param string $sReturnValue Celebros API method name
     *
     * @return object $oResult - search result object
     */
	function GetResult($sRequestUrl,$sReturnValue)
	{

		$sRequest = $this->sWebServiceUrl . '/' . $sReturnValue.'?';
        if(!empty($sRequestUrl)) $sRequest .= $sRequestUrl.'&';
        $sRequest .= 'Sitekey=' . $this->sSiteKey;
//print_r($sRequest);die;        
        $oQwiserParser = $this->GetQwiserParser($sRequest); 
        $oQwiserParser->set_enconig_converter('UTF-8','ISO-8859-1',2);
	 $this->oSearchResponce = $oQwiserParser->run();

        // $sReturnValue = __FUNCTION__: The function name. (Added in PHP 4.3.0) As of PHP 5 this constant returns the function name as it was declared (case-sensitive). In PHP 4 its value is always lowercased. 
        $sReturnValue = strtolower($sReturnValue);
        $oResult  = false;
        
        $dictResult["getallproductfields"] =  $this->oSearchResponce->QwiserSearchFacadeWrapper->ReturnValue->ProductFields;       
		$dictResult["getproductanswers"] = $dictResult["getseveralproductsanswers"] = $this->oSearchResponce->QwiserSearchFacadeWrapper->ReturnValue->ProductAnswers;
        $dictResult["getallquestions"] = $this->oSearchResponce->QwiserSearchFacadeWrapper->ReturnValue->Questions;
        $dictResult["getproductsearchpath"] = $this->oSearchResponce->QwiserSearchFacadeWrapper->ReturnValue->SearchPath;
        $dictResult["getquestionanswers"] = $this->oSearchResponce->QwiserSearchFacadeWrapper->ReturnValue->Answers;
     	$dictResult["getsearchprofilequestions"]= $this->oSearchResponce->QwiserSearchFacadeWrapper->ReturnValue->Questions;
        $dictResult["getenginestatus"] = $this->oSearchResponce->QwiserSearchFacadeWrapper->ReturnValue->SearchEngineStatus;
        $dictResult["removeproductfromstock"] = $dictResult["restoreproducttostock"]= $this->oSearchResponce->QwiserSearchFacadeWrapper;
        $dictResult["getallsearchprofiles"] = $this->oSearchResponce->QwiserSearchFacadeWrapper->ReturnValue->QwiserSimpleStringCollection;
        $dictResult["default"] = $this->oSearchResponce->QwiserSearchFacadeWrapper->ReturnValue->QwiserSearchResults;
       	if(!isset($dictResult[$sReturnValue])) $sReturnValue = "default";
       	$oResult = $dictResult[$sReturnValue];
       	
        $this->blLastOperationSucceeded = $oQwiserParser->blLastOperationSucceeded;
        $this->sLastOperationErrorMessage = $oQwiserParser->sLastOperationErrorMessage;

        return $oResult;
	}
    
	/**
	 * Gets Celebros response parser
	 *
	 * @param string $sRequest Celebros request url
	 *
	 * @return object $oQwiserParser response parser
	 */	
	function GetQwiserParser($sRequest) {
		$oQwiserParser = oxNew("\Celebros\Conversionpro\Core\Celebros\QwiserParser", $sRequest);
		return $oQwiserParser;
	}	
	
	/**
	 * Encodes Celebros search handler
	 *
	 * @param string $sSearchHandle Celebros search handler
	 *
	 * @return object $sEncodedSearchHandle Celebros encoded search handler
	 */	
    function SearchHandle_encode( $sSearchHandle )
    {
    	$sEncodedSearchHandle = base64_encode($sSearchHandle);
        $sEncodedSearchHandle = str_replace("=","-",$sEncodedSearchHandle);
        return $sEncodedSearchHandle; 
    }
    
    /**
     * Decodes Celebros search handler
     *
     * @param string $sEncodedSearchHandle Celebros search handler
     *
     * @return object $sSearchHandle Celebros decoded search handler
     */    
    function SearchHandle_decode( $sEncodedSearchHandle )
    {
        $sEncodedSearchHandle = str_replace("-","=",$sEncodedSearchHandle);
        $sSearchHandle = base64_decode($sEncodedSearchHandle);
        return $sSearchHandle; 
    }
}
?>