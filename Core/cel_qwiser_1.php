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
  * Celebros search view
  */ 
class cel_Qwiser extends Search
{
	/**
	 * Current class template name.
	 * @public string
	 */
	public $sThisTemplate = "cel_qwiser.tpl";

	public $sThisAction = "cel_qwiser";

	protected $_blShowSorting = true;
	
	protected $oAPI = null;
	
	protected $_aBannerCampaigns = array();
	protected $_aAlternativeProductsCampaigns = array();
	protected $_aRedirectCampaigns = array();
	protected $_aCustomMsgs = array();
  
	protected $_aSortingOrderMapping = array("asc"=>"true", "desc"=>"false");
  
 	protected $_aMappingFields = null;

 	/**
 	 * Calls parent unit
 	 *
 	 * @return null
 	 */ 	
	public function init()
	{
		parent :: init();
	}
	
	
	/**
	 * Gets Celebros search API object
	 *
	 * @return object Celebros search API object
	 */	
	public function getCelebrosSearchApi()
	{
		if(is_null($this->oAPI)) {
			$this->oAPI = oxNew( "cel_qwiserapi");
		}
		return $this->oAPI;
	}
	
	/**
	 * Generates page navigation and returns the name of template file "search.tpl".
	 * 
	 * @return string
	 */
	public function render()
	{
		parent :: render();
		$myConfig = $this->getConfig();
		$oAPI = $this->getCelebrosSearchApi();
		
		//Default search values settings
		$iDefaultPageSize = (int)$myConfig->getConfigParam('iCel_DefaultPageSize');
		
		//Oxid settings
		$sInitialSearchStr = $sSearchStr = rawurldecode($myConfig->getParameter("searchparam", true));

		// convert extended chars
		$sSearchStr = rawurlencode($this->ReplaceExtendedChars( $sSearchStr));
		$sSearchHandle = $oAPI->SearchHandle_decode($myConfig->getParameter( "sQWSearchHandle"));
		
    	$qsr = $this->_executeQwiserAction($sSearchHandle, $sInitialSearchStr);
		
		$this->getSession()->setVar('cel_oCelebros', $qsr);

		//Check if we had any Errors
		if (!$oAPI->blLastOperationSucceeded)
		{
			$this->_aViewData['ErrorMessage'] = $oAPI->sLastOperationErrorMessage;
			return $this->sThisTemplate;
		}

		$sSearchHandle = $oAPI->SearchHandle_encode($qsr->SearchHandle);
		$this->_setSearchParameters($sInitialSearchStr);
		
    	$this->_setSearchLink ($sSearchStr, $sSearchHandle, $qsr->SearchInformation->CurrentPage);

		$this->_aViewData['SearchHandle'] = $sSearchHandle;

		$this->_setHiddenSid($sSearchHandle, $qsr->SearchInformation);

		// logout link
		$this->getViewConfig()->logoutlink.='&qwiser_parameters[iQWPage]='.$qsr->SearchInformation->CurrentPage.'&qwiser_parameters[iQWAction]=1&qwiser_parameters[sQWSearchHandle]='.$sSearchHandle;

		$this->parseDynamicProperties($qsr);
		//Redirect campaign execute
		if(count($this->_aRedirectCampaigns)) oxUtils::getInstance()->redirect( $this->_aRedirectCampaigns[0]->redirection_url);
		//Set data for banner campaign
		if(count($this->_aBannerCampaigns)) $this->_aViewData['aBannerCampaigns'] = $this->_aBannerCampaigns;
		
		$this->_setRecommendedMessage($qsr->RecommendedMessage);
		if($qsr->SpellerInformation->SpellingErrorDetected)
		{
			$this->_aViewData['aAdditionalSuggestions'] = $qsr->SpellerInformation->AddtionalSuggestions;
		}
		$this->_aViewData['aSearchPath'] = $qsr->SearchPath;
		 
		$qsr->Questions = $this->_setLeadQuestion($qsr->Questions);
		$qsr->Questions = $this->_setNonLeadQuestion($qsr->Questions);
		$this->_setArticlesList($qsr->Products, $qsr->RelevantProductsCount, $qsr->SearchInformation, $qsr->SearchHandle);
		$this->_setPageNavigation($qsr, $sSearchStr, $sSearchHandle, $myConfig);
		$this->_setSortingParams($qsr->SearchInformation->SortingOptions->FieldName, $qsr->SearchInformation->SortingOptions->Ascending);
			
		$this->_aViewData['additionalparams'] .= "&cl={$this->sThisAction}&searchparam={$sSearchStr}&sQWSearchHandle={$sSearchHandle}";
		return $this->sThisTemplate;
	}
	

	/**
	 * Sets sorting parameters for UI
	 *
	 * @param string $cel_FieldName sorting field name as defined on Celebros server and set in oxid admin, in Oxid=>Celebros Mapping
	 * @param string $cel_Ascending set ascending or descending
	 * 
	 * @return null
	 */	
    protected function _setSortingParams($cel_FieldName, $cel_Ascending) {
        $myConfig = $this->getConfig();
        $this->_aViewData['showsorting'] = (bool)$myConfig->getConfigParam('blShowSorting');
		$this->_aViewData['allsortcolumns'] = $this->_aSortColumns  = $myConfig->getConfigParam('aSortCols');
    
		if ($cel_FieldName )
		{
			$aMappingFields = $this->_getMappingFields();    		    
			$sListOrderBy = strtr($cel_FieldName, array_flip($aMappingFields));
			$this->_aViewData['listorderby'] = $sListOrderBy;
			$this->_sListOrderBy = $sListOrderBy;
			$this->getSession()->setVar("listorderby", $sListOrderBy );
    		      			
			$sListOrder = strtr($cel_Ascending, array_flip($this->_aSortingOrderMapping));
			$this->_aViewData['listorder'] = $sListOrder;
			$this->getSession()->setVar("listorder", $sListOrder );
          	$this->_sListOrderDir = $sListOrder;            
    	}
    }		
	
    /**
     * Sets recommended message for UI
     *
     * @param string $sRecommendedMessage merchandising campaign message recieved from Celebros search results
     *
     * @return null
     */    
    protected function _setRecommendedMessage($sRecommendedMessage) {
    		$sRecommendedMessage = html_entity_decode($sRecommendedMessage);
    		$sRecommendedMessage = str_replace( array( "#%", "%#"), array( "<b>", "</b>"), $sRecommendedMessage );
    		$this->_aViewData['RecommendedMessage'] = $sRecommendedMessage;    
    }	
	
    /**
     * Sets search link for UI
     *
     * @param string $sSearchStr search string
     * @param string $sSearchHandle Celebros search handler
     * @param string $currentPage Current search results page
     *
     * @return null
     */    
    protected function _setSearchLink ($sSearchStr, $sSearchHandle, $currentPage) {
    		$sSearchLink = "&listtype=qwiser";
    		$sSearchLink .= "&searchparam=".$sSearchStr;
    		$sSearchLink .= "&sQWSearchHandle=".$sSearchHandle;
    		$sSearchLink .= "&pgNr=".$currentPage;
    		$this->_aViewData['searchlink'] = $sSearchLink;
    		$this->_aViewData['sListType']  = "qwiser";        
    }	
	
    /**
     * Gets Oxid=>Celebros mapping array
     *
     * @return array Oxid=>Celebros mapping array
     */    
	protected function _getMappingFields(){
		$myConfig = $this->getConfig();
		if(!isset($this->_aMappingFields)) {
			$this->_aMappingFields = $myConfig->getConfigParam('mappingsalespersonfields');
		}
		return $this->_aMappingFields;
	} 
  
	/**
	 * Gets Celebros Search sort field name
	 *
	 * @return string $sSortFieldName Celebros Search sort field name
	 */	
  protected function _getSearchSortFieldName() {
    $myConfig = $this->getConfig();
    $aMappingFields = $this->_getMappingFields();
    $sSortFieldName = $myConfig->getConfigParam('sCel_DefaultSortingfield');
	$sListOrderBy = $myConfig->getParameter("listorderby");
	if($sListOrderBy == "")  $sListOrderBy = $this->getSession()->getVar("listorderby");
	if($sListOrderBy != "") $sSortFieldName = strtr(strtoupper($sListOrderBy), $aMappingFields);
	return $sSortFieldName;
  }
  
  /**
   * Gets Celebros Search numeric or alpanumeric sort parameter
   *
   * @return bool $bNumericSort Celebros Search numeric or alpanumeric sort parameter
   */  
	protected function _getSearchNumericSort() {
		$myConfig = $this->getConfig(); 
		$aSortingIsNumericMapping = $myConfig->getConfigParam('mappingisnumericsortfields');
		$sListOrderBy = $myConfig->getParameter("listorderby");
		$bNumericSort = $aSortingIsNumericMapping[strtoupper($sListOrderBy)] ? "true" : "false";   
		return $bNumericSort;
	}  
  
	/**
	 * Gets Celebros Search ascending or descending sort parameter
	 *
	 * @return integer $iSortAscending Celebros Search ascending or descending sort parameter
	 */	
  protected function _getSortAscending() {
    $myConfig = $this->getConfig();
  	$iSortAscending = $myConfig->getConfigParam('bCel_DefaultAscending');
	$sListOrder = $myConfig->getParameter("listorder");
	if($sListOrder == "") $sListOrder = $this->getSession()->getVar("listorder");
    if($sListOrder != "") $iSortAscending = strtr($sListOrder, $this->_aSortingOrderMapping);
    return $iSortAscending;
  }    
  
  /**
   * Executes the requests to Celebros server according to the action selected by the user
   *
   * @param string $sSearchHandle Celebros search handle
   * @param string $sInitialSearchStr user entered search string
   *
   * @return object $qsr Celebros response object
   */  
	protected function _executeQwiserAction($sSearchHandle, $sInitialSearchStr) {
	    $myConfig = $this->getConfig();
	    $oAPI =	$this->getCelebrosSearchApi();
	    
	    //Set sorting params for celebros request: oxid fields<=>salesperson fields
		$sSortFieldName = $this->_getSearchSortFieldName();
		$bNumericSort = $this->_getSearchNumericSort();
		$iSortAscending = $this->_getSortAscending();
	    
	    $iPageSize = $this->getViewConfig()->getArtPerPageCount();
		$iAction = $this->getQzAction();
		
		switch ($iAction)
		{
			case "1" : // Set page
				$iPage = $myConfig->getParameter( "iQWPage");
				$qsr = $oAPI->MoveToPage($sSearchHandle, $iPage);
				break;
			case "2" : // Answer
				$sAnswerId = $myConfig->getParameter( "sQWAnswerId");
				$qsr = $oAPI->AnswerQuestion($sSearchHandle, $sAnswerId, '1');
				break;
			case "3" : // Remove answers
				$iStartIndex = $myConfig->getParameter( "iQWStartIndex");
				$qsr = $oAPI->RemoveAnswersFrom($sSearchHandle, $iStartIndex);
				break;
			case "4" : // First question
				$sQuestionId = $myConfig->getParameter( "sQWQuestionId");
				$qsr = $oAPI->ForceQuestionAsFirst($sSearchHandle, $sQuestionId);
				break;
			case "5" : //Set Page Size
				$qsr = $oAPI->ChangePageSize($sSearchHandle, $iPageSize);
				break;
			case "6" : //Simple Search
				$qsr = $oAPI->Search($sInitialSearchStr);
				break;
			case "7" : //Custom Results
				$sNewSearch = $myConfig->getParameter( "sQWNewSearch");
				$sPreviousSearchHandle = $myConfig->getParameter( "sQWPreviousSearchHandle");
				$qsr = $oAPI->GetCustomResults($sSearchHandle, $sNewSearch, $sPreviousSearchHandle);
				break;
			case "8" : //Change Price Colum
				$sPriceColum = $myConfig->getParameter( "sQWPriceColum");
				$qsr = $oAPI->ChangePriceColumn($sSearchHandle, $sPriceColum);
				break;
			case "9" : //Activate Profile
				$sSearchProfile = $myConfig->getParameter( "sQWSearchProfile");
				$qsr = $oAPI->ActivateProfile($sSearchHandle, $sSearchProfile);
				break;
			case "10" : //set sort by
				$this->_MakeSortRequest($sSearchHandle, $sSortFieldName, $bNumericSort, $iSortAscending);
				break;
			default :
            $qsr = $this->_defaultSearch($sInitialSearchStr, $iPageSize, $sSortFieldName, $bNumericSort, $iSortAscending);		
		}
		return $qsr;
	}

	/**
	 * Makes sort request requests to Celebros server
	 *
	 * @param string $sSearchHandle Celebros search handle
	 * @param string $sSortFieldName Celebros sorting field name
	 * @param bool $bNumericSort Celebros sorting numeric or alphanumeric parameter
	 * @param integer $iSortAscending Celebros sorting ascending or descending
	 *
	 * @return object $qsr Celebros response object
	 */	
	protected function _MakeSortRequest($sSearchHandle, $sSortFieldName, $bNumericSort, $iSortAscending){

		$oAPI = $this->getCelebrosSearchApi();
		
		switch ($sSortFieldName)
		{
			case "Relevancy" : //SortByRelevancy
				$qsr = $oAPI->SortByRelevancy($sSearchHandle);
				break;
			case "Price" : //SortByPrice
				$qsr = $oAPI->SortByPrice($sSearchHandle, $iSortAscending);
				break;
			default : //SortByField
				$qsr = $oAPI->SortByField($sSearchHandle, $sSortFieldName, $bNumericSort, $iSortAscending);
			break;
		}
	}	
	
	/**
	 * Sets products list for UI
	 *
	 * @param array $products products
	 * @param integer $relevantProductsCount counter of relevant products
	 * @param object $searchInformation Celebros search response object
	 *  @param string $sSearchHandle Celebros search handle
	 *
	 * @return null
	 */	
	protected function _setArticlesList($products, $relevantProductsCount, $searchInformation, $searchHandle) {
		$this->setNrOfCatArticles($relevantProductsCount);

		/* load articles */
		$aID = array();
		$aQwiserID = array();
		if( $products->Count > 0)
		{
			$i = 1;
			foreach( $products->Items as $oProduct)
			{
				$aID[] = "'".$oProduct->Sku."'";
				$aQwiserID[$i] = $oProduct->Sku;
				$i++;
			}
		}
		
		// Cache ID's to session
		$aQwiserCachedIDs = array( "articlecount"=> $relevantProductsCount, "pagecount"=> $searchInformation->NumberOfPages,"pagesize"=> $searchInformation->PageSize, $searchInformation->CurrentPage => array("SearchHandle" => $searchHandle , "aID" =>$aQwiserID ));
		$this->getSession()-> setVar("aQwiserCachedIDs",$aQwiserCachedIDs);
		 
		$sID = implode(",",$aID);


		$sArticleViewName = getViewName('oxarticles');
		$oArticle = oxNew("oxarticle");

		$sSelect = "SELECT $sArticleViewName.*
                    FROM $sArticleViewName
                    WHERE " . $oArticle->getSqlActiveSnippet()."
                        AND $sArticleViewName.oxissearch = 1
                        AND $sArticleViewName.oxparentid = '' 
                        AND oxid IN ( $sID )
						ORDER BY FIELD(oxid, $sID)";

		//dumpVar($sSelect); exit();
		
		$oArtList = oxNew( "oxarticlelist");
		$oArtList->selectString( $sSelect);

		if( $this->_hasArticlesInList($oArtList)  ) {
			$this->_aViewData['cel_CelebrosArticleList']  = $oArtList;
			//Article list params
			$this->_aArticleList = $oArtList;
		}	    
	}
	
	/**
	 * Check whether number of articles passed is more than 0
	 *
	 * @param array $oArtList
	 *
	 * @return bool whether number of articles passed is more than 0
	 */	
	protected function _hasArticlesInList($oArtList) {
		return (count( $oArtList->aList) > 0);
	}
	
	/**
	 * Uses Celebros Search API to get response with the search results from Celebros server
	 *
	 * @param string $sInitialSearchStr
	 * @param integer $iPageSize
	 * @param string $sSortFieldName
	 * @param bool $bNumericSort
	 * @param integer $iSortAscending
	 *
	 * @return object $qsr holds the response with the search results from the Celebros server
	 */	
	protected function _defaultSearch($sInitialSearchStr, $iPageSize, $sSortFieldName, $bNumericSort, $iSortAscending) {
    $myConfig = $this->getConfig();
    $oAPI = $this->getCelebrosSearchApi();
    
    $sQWSearchProfile = $myConfig->getParameter("sQWSearchProfile");
    if($sQWSearchProfile == "") $myConfig->getConfigParam('sCel_DefaultSearchProfile');
    
    $sQWAnswerId = $myConfig->getParameter("sQWAnswerId");
    if($sQWAnswerId == "") $myConfig->getConfigParam('iCel_DefaultAnswerId');
    	
    $sQWEffectOnSearchPath = $myConfig->getParameter("sQWEffectOnSearchPath");
    if($sQWEffectOnSearchPath == "") $myConfig->getConfigParam('iCel_DefaultEffectOnSearchPath');
    
    $sQWPriceColum = $myConfig->getParameter("sQWPriceColum");
    if($sQWPriceColum == "") $myConfig->getConfigParam('sCel_DefaultPriceColum');
    		
    $qsr = $oAPI->SearchAdvance(  $sInitialSearchStr,
                                				$sQWSearchProfile,
                                				$sQWAnswerId,
                                				$sQWEffectOnSearchPath,
                                				$sQWPriceColum,
                                				$iPageSize,
                                				$sSortFieldName,
                                				$bNumericSort,
                                				$iSortAscending);
    return $qsr;
	}

	/**
	 * Sets hidden sid for UI
	 *
	 * @param string $sSearchHandle Celebros search handle
	 * @param object $oSearchInformation search information Celebros response object
	 *
	 * @return null
	 */	
	protected function _setHiddenSid($sSearchHandle, $oSearchInformation) {
    	$sHiddenSid = $this->getViewConfig()->hiddensid;

		// additional parameters (first check and remove existing)
		if (strstr($sHiddenSid,'qwiser_parameters')){
			$sHiddenSid = preg_replace('/<input.*name="qwiser_parameters.*\/>/', '', $sHiddenSid);
		}
		$sHiddenSid.= '<input type="hidden" name="qwiser_parameters[sQWSearchHandle]" value="'.$sSearchHandle.'" />';
		$sHiddenSid.= '<input type="hidden" name="qwiser_parameters[iQWPage]" value="' . $oSearchInformation->CurrentPage.'" />';
		$sHiddenSid.= '<input type="hidden" name="qwiser_parameters[iQWAction]" value="1" />';
		$this->getViewConfig()->hiddensid=$sHiddenSid;	    
	}
	
	/**
	 * Sets search parameters for UI
	 *
	 * @param string $sInitialSearchStr initial search string
	 * @param string $sQuery search string
	 * @param string $sOriginalQuery original search string
	 *
	 * @return null
	 */	
	protected function _setSearchParameters($sInitialSearchStr) {
		#$this->_aViewData['searchparam'] = htmlentities($sInitialSearchStr);
		$this->_aViewData['searchparam']         = $sInitialSearchStr;
		$this->_aViewData['searchparamforhtml']  = $sInitialSearchStr;
	}
	
	/**
	 * Sets page navigation for UI
	 *
	 * @param object $qsr holds the response with the search results from the Celebros server
	 * @param string $sSearchStr search string
	 * @param string $sSearchHandle Celebros search handle
	 * @param object $myConfig oxid config object
	 *
	 * @return null
	 */	
	protected function _setPageNavigation($qsr, $sSearchStr, $sSearchHandle, $myConfig){
	    		// generate the page navigation
		$pageNavigation = new stdClass();
		$pageNavigation->iArtCnt    = $qsr->RelevantProductsCount;//$qsr->SearchInformation->PageSize;

		$pageNavigation->NrOfPages  = $qsr->SearchInformation->NumberOfPages;
		$pageNavigation->actPage    = $qsr->SearchInformation->CurrentPage+1;

		$pageNavigation->previousPage = null;
		if( $pageNavigation->actPage > 1)
			$pageNavigation->previousPage = $myConfig->getShopHomeURL()."cl=".$this->sThisAction."&iQWPage=".($pageNavigation->actPage-2)."&searchparam=$sSearchStr&sQWSearchHandle={$sSearchHandle}&iQWAction=1";

		$pageNavigation->nextPage = null;
		if( $pageNavigation->actPage < $pageNavigation->NrOfPages)
			$pageNavigation->nextPage = $myConfig->getShopHomeURL()."cl=".$this->sThisAction."&iQWPage=".($pageNavigation->actPage)."&searchparam=$sSearchStr&sQWSearchHandle={$sSearchHandle}&iQWAction=1";

		if( $pageNavigation->NrOfPages > 1)
		{
			$pageNavigation = $this->_addNavigationPages($pageNavigation, $myConfig, $sSearchStr, $sSearchHandle);
			// first/last one
			$pageNavigation->firstpage = $myConfig->getShopHomeURL()."cl=".$this->sThisAction."&iQWPage=0&searchparam=$sSearchStr&sQWSearchHandle={$sSearchHandle}&iQWAction=1";
			$iLast =  $pageNavigation->NrOfPages - 1;
			$pageNavigation->lastpage = $myConfig->getShopHomeURL()."cl=".$this->sThisAction."&iQWPage=".$iLast."&searchparam=$sSearchStr&sQWSearchHandle={$sSearchHandle}&iQWAction=1";
		}

		$this->_aViewData['pageNavigation'] = $pageNavigation;
		//DumpVar($pageNavigation);
	}
	
	/**
	 * Builds navigation pages for UI
	 *
	 * @param object $pageNavigation navigation pages for UI
	 * @param object $myConfig oxid config object
	 * @param string $sSearchStr search string
	 * @param string $sSearchHandle Celebros search handle
	 *
	 * @return object $pageNavigation navigation pages for UI
	 */	
	protected function _addNavigationPages($pageNavigation, $myConfig, $sSearchStr, $sSearchHandle){
		for ($i=1; $i < $pageNavigation->NrOfPages + 1; $i++)
		{
			$page = new stdClass();
			$page->url = $myConfig->getShopHomeURL()."cl=".$this->sThisAction."&iQWPage=".($i-1)."&searchparam=$sSearchStr&sQWSearchHandle={$sSearchHandle}&iQWAction=1";
			$page->selected = 0;
			if( $i == $pageNavigation->actPage) $page->selected = 1;
			$pageNavigation->changePage[$i] = $page;
		}
		return $pageNavigation;
	}

	/**
	 * Sets lead question for UI
	 *
	 * @param object $questions Celebros search response questions object
	 *
	 * @return object $questions Celebros search response questions object
	 */	
	protected function _setLeadQuestion($questions) {
	    
		if($questions->Count == 0)
		{
			$this->_aViewData['LeadQuestion'] = false;
        	return $questions;
    	}

	    $myConfig = $this->getConfig();
	    $iQuiser_max_lead_answers = (int)$myConfig->getConfigParam('iCel_MaxLeadAnswers');
      	$blQuiser_show_full_lead_answers = ($iQuiser_max_lead_answers != "");
      
		$oLeadQuestion = $questions->Items[0];

		if(!is_array ($oLeadQuestion->ExtraAnswers->Items))
		{
			$oLeadQuestion->ExtraAnswers->Items = array();
			$oLeadQuestion->ExtraAnswers->Count = 0;
		}
		
		//Transforming Answers if needed
		$oLeadQuestion = $this->_transformLeadQuestionAnswers($oLeadQuestion, $iQuiser_max_lead_answers);

		if( !$blQuiser_show_full_lead_answers && $oLeadQuestion->HasExtraAnswers)
		{
			$oLeadQuestion->HasExtraAnswers = 0;
			$oLeadQuestion->ExtraAnswers->Items = array();
			$oLeadQuestion->ExtraAnswers->Count = 0;
		}

		$this->_aViewData['LeadQuestion'] = $oLeadQuestion;

		unset($questions->Items[0]);
		$questions->Count --;
		return $questions;
	}
	
	/**
	 * Transforms lead question answers for UI
	 *
	 * @param object $oLeadQuestion Celebros search response question object
	 * @param integer $iQuiser_max_lead_answers Celebros lead 
	 *
	 * @return object $oLeadQuestion Celebros search response question object
	 */	
	protected function _transformLeadQuestionAnswers($oLeadQuestion, $iQuiser_max_lead_answers) {
		if( ($oLeadQuestion->Answers->Count + $oLeadQuestion->ExtraAnswers->Count) > $iQuiser_max_lead_answers )
		{
			$aAnswers = array();
			$aAnswers = array_merge($oLeadQuestion->Answers->Items,$oLeadQuestion->ExtraAnswers->Items);
		
			$oLeadQuestion->Answers->Items = array_slice($aAnswers, 0, $iQuiser_max_lead_answers);
			$oLeadQuestion->Answers->Count = count($oLeadQuestion->Answers->Items);
		
			$oLeadQuestion->ExtraAnswers->Items = array_slice($aAnswers, $iQuiser_max_lead_answers);
			$oLeadQuestion->ExtraAnswers->Count = count($oLeadQuestion->ExtraAnswers->Items);
		
			$oLeadQuestion->HasExtraAnswers = 0;
			if($oLeadQuestion->ExtraAnswers->Count > 0 ) $oLeadQuestion->HasExtraAnswers = 1;
		}
		return $oLeadQuestion;	
	}
	
	/**
	 * Sets non lead questions for UI
	 *
	 * @param object $questions Celebros search response questions object
	 *
	 * @return object $questions Celebros search response questions object
	 */	
	protected function _setNonLeadQuestion($questions) {
		$myConfig = $this->getConfig();
		$iQuiser_max_non_lead_questions = (int)$myConfig->getConfigParam('iCel_MaxNoneLeadQuestions');		
		$iQuiser_max_non_lead_answers = (int)$myConfig->getConfigParam('iCel_MaxNoneLeadAnswers');
	    
				// remove questions if needed
			if( ($iQuiser_max_non_lead_questions !="") && ($questions->Count > $iQuiser_max_non_lead_questions) )
			{
				$questions->Items = array_slice($questions->Items, 0, $iQuiser_max_non_lead_questions);
				$questions->Count = count($questions->Items);
			}
			if(isset($questions->Items)) {
				foreach( $questions->Items as $index => $Question)
				{
					if(!is_array ($Question->ExtraAnswers->Items))
					{
						$Question->ExtraAnswers->Items = array();
						$Question->ExtraAnswers->Count = 0;
					}

					// Transforming Answers if needed
					$questions->Items[$index] = $this->_transformNonLeadQuestionAnswers($Question, $iQuiser_max_non_lead_answers);
				}
			}
			
			$this->_aViewData['aMoreQuestions'] = $questions;
			
			return $questions;	    
	}
	
	/**
	 * Transforms non lead question answer for UI
	 *
	 * @param object $question Celebros search response question object
	 * @param integer $iQuiser_max_non_lead_answers max non lead answers allowed
	 *
	 * @return object $question Celebros search response question object
	 */	
	protected function _transformNonLeadQuestionAnswers($Question, $iQuiser_max_non_lead_answers) {
		if( ($iQuiser_max_non_lead_answers!="") && ($Question->Answers->Count + $Question->ExtraAnswers->Count > $iQuiser_max_non_lead_answers) )
		{
			$aAnswers = array();
			$aAnswers = array_merge($Question->Answers->Items,$Question->ExtraAnswers->Items);
		
			$Question->Answers->Items = array_slice($aAnswers, 0, $iQuiser_max_non_lead_answers);
			$Question->Answers->Count = count($Question->Answers->Items);
		
			$Question->ExtraAnswers->Items = array_slice($aAnswers, $iQuiser_max_non_lead_answers);
			$Question->ExtraAnswers->Count = count($Question->ExtraAnswers->Items);
		
			if($Question->ExtraAnswers->Count > 0 )
				$Question->HasExtraAnswers = 1;
			//else
				//$oLeadQuestion->HasExtraAnswers = 0;
		
			
		}
		return $Question;
	}

	/**
	 * Replaces special characters
	 *
	 * @param string $sValue value to process 
	 * @param string $blReverse reverse
	 *
	 * @return string $sValue processed string
	 */	
	public function ReplaceExtendedChars( $sValue, $blReverse = false)
	{   // we need to replace this for compatibility with XHTML
		// as this function causes a lot of trouble with editor
		// we switch it off, even if this means that fields do not validate through xhtml
		// return $sValue;

		// we need to replace this for compatibility with XHTML
		//$aReplace = array( "&" => "&amp;", "ֳ₪" => "&auml;", "ֳ¶" => "&ouml;", "ֳ¼" => "&uuml;", "ֳ�" => "&Uuml;", "ֳ„" => "&Auml;", "ֳ–" => "&Ouml;", "ֳ�" => "&szlig;", "ֲ©" => "&copy", "ג‚¬" => "&euro;");
		//(experiment if we don't)
		$aReplace = array( "ֲ©" => "&copy", "ג‚¬" => "&euro;", "\"" => "&quot;", "'" => "&#039;");

		/*
		 if( !$blReverse)
		 {   // check if we do have already htmlentities inside
		 foreach( $aReplace as $key => $sReplace)
		 if( strpos( $sValue, $sReplace) !== false)
		 return $sValue;

		 // replace now
		 foreach( $aReplace as $key => $sReplace)
		 $sValue = str_replace( $key, $sReplace, $sValue);
		 }
		 */

		// #899C reverse html entities and references transformation is used in invoicepdf module
		// so this part must be enabled. Now it works with html references like &#123;
		if($blReverse)
		{   // replace now
			$aTransTbl = get_html_translation_table (HTML_ENTITIES);
			$aTransTbl = array_flip ($aTransTbl) + array_flip ($aReplace);
			$sValue = strtr ($sValue, $aTransTbl);
			$sValue = preg_replace('/\&\#([0-9]+)\;/me', "chr('\\1')",$sValue);
        }

        return $sValue;
    }
    
    /**
     * gets user's action parameter
     *
     * @return string $iRes user's action parameter
     */    
    public function getQzAction()
    {
    	$iRes = 0;
    	$sQWAction = $this->getConfig()->getParameter( "iQWAction");

    	if(isset($sQWAction)) {
    		$iRes = (int)$this->getConfig()->getParameter( "iQWAction");
    	}
    	
    	return $iRes;
    }
    
    /**
     * gets addition parameters for UI
     *
     * @return string addition parameters for UI
     */    
    public function getAdditionalParams()
    {
    	return $this->_aViewData['additionalparams'];
    }

    /**
     * sets number of articles for UI
     *
     * @param integer $articlesCount  number of articles
     *
     * @return null
     */    
    public function setNrOfCatArticles($articlesCount) 
    {
    	$res = array();
    	$arrNrOfCatArticles = $this->getConfig()->getConfigParam( 'aNrofCatArticles' );
    	
    	for($i=0; $i< count($arrNrOfCatArticles); $i++) {
    		$iPageSize = (int)$arrNrOfCatArticles[$i];
    		if($iPageSize > $articlesCount && $i!=0) break;
    		$res[] = $iPageSize;
    	}
    	$this->getConfig()->setConfigParam( 'aNrofCatArticles', $res);
    	$this->_iAllArtCnt = $articlesCount;
    }
    
    /**
     * gets page navigation for UI
     *
     * @param integer $iPositionCount page position
     *
     * @return object page navigation for UI
     */    
    public function generatePageNavigation( $iPositionCount = 0 )
    {
        startProfile('generatePageNavigation');

        stopProfile('generatePageNavigation');

        return $this->_aViewData['pageNavigation'];
    }
    
    /**
     * gets bread crumbs for UI
     *
     * @return array $aPaths bread crumbs for UI
     */    
    public function getBreadCrumb()
    {
		$selfLink = $this->getViewConfig()->getSelfLink();
		$searchlink = $this->_aViewData['searchlink'];
		$searchparam = $this->_aViewData['searchparam'];
		$SearchHandle = $this->_aViewData['SearchHandle'];
    		
        $aPaths = array();
        $aPath = array();
        
        $aPath['title'] = "<a href='{$selfLink}cl=cel_qwiser{$searchlink}'>{$searchparam}</a>";
        
        
        $aSearchPath = $this->_aViewData['aSearchPath'];
				$link = "";
        
				$iStartIndex = 1;
				if ($aSearchPath->Count > 0) {
					foreach($aSearchPath->Items as $oSearchPath){
						$link.= "<a href='{$selfLink}cl=cel_qwiser&iQWAction=3&iQWStartIndex={$iStartIndex}$searchlink'>{$oSearchPath->Answers->Items[0]->Text}</a>";
						$iStartIndex++;
					}
				}
				
        $aPath['link']  = $link;
        
/*[{ assign var="template_location" value=" <a href=\""|cat:$shop->selflink|cat:"cl=cel_qwiser"|cat:$searchlink|cat:"\">"|cat:$searchparam|cat:"</a>" }]

[{if $aSearchPath->Count > 0 }]
  [{assign var="iStartIndex" value="1"}]
  [{foreach from=$aSearchPath->Items item=oSearchPath}]
    [{ assign var="template_location" value=$template_location|cat:" / <a href=\""|cat:$shop->selflink|cat:"cl=cel_qwiser&iQWAction=3&sQWSearchHandle="|cat:$SearchHandle|cat:"&iQWStartIndex="|cat:$iStartIndex|cat:$searchlink|cat:"\">"|cat:$oSearchPath->Answers->Items[0]->Text|cat:"</a>"}]
    [{assign var="iStartIndex" value=$iStartIndex+1}]
  [{/foreach}]
[{/if}] */       
        
        //$aPath['title'] = oxLang::getInstance()->translateString( 'SEARCH_TITLE', oxLang::getInstance()->getBaseLanguage(), false );
        
        $aPaths[] = $aPath;

        return $aPaths;
    }
    
    /**
     * Sets number of articles per page to config value
     *
     * @return null
     */
    protected function _setNrOfArtPerPage()
    {
        $myConfig  = $this->getConfig();

        //setting default values to avoid possible errors showing article list
        $iNrofCatArticles = $myConfig->getConfigParam( 'iNrofCatArticles' );
        $iCel_DefaultPageSize = (int)$myConfig->getConfigParam('iCel_DefaultPageSize');
        $iNrofCatArticles = ( $iNrofCatArticles ) ? $iNrofCatArticles : $iCel_DefaultPageSize;

        // checking if all needed data is set
        switch ( $this->getListDisplayType() ) {
            case 'grid':
                $aNrofCatArticles = $myConfig->getConfigParam( 'aNrofCatArticlesInGrid' );
                break;
            case 'line':
            case 'infogrid':
            default:
                $aNrofCatArticles = $myConfig->getConfigParam( 'aNrofCatArticles' );
        }

        $aNrofCatArticles = isset( $aNrofCatArticles[0]) ? $aNrofCatArticles : array( $iNrofCatArticles );
        $myConfig->setConfigParam( 'aNrofCatArticles',  $aNrofCatArticles);

        $iNrofCatArticles = $this->_setViewConfigWithArtPerPage($myConfig, $aNrofCatArticles);
        
        //setting number of articles per page to config value
        $myConfig->setConfigParam( 'iNrofCatArticles', $iNrofCatArticles );
    } 

    /**
     * sets number of products per page for UI
     *
     * @param object $myConfig oxid config object
     * @param integer $aNrofCatArticles number of articles
     *
     * @return integer $iNrofCatArticles number of products per page for UI
     */    
    protected function _setViewConfigWithArtPerPage($myConfig, $aNrofCatArticles){
    	$oViewConf = $this->getViewConfig();
    	//value from user input
    	if ( ( $iNrofArticles = (int) $myConfig->getParameter( '_artperpage' ) ) ) {
    		// M45 Possibility to push any "Show articles per page" number parameter
    		$iNrofCatArticles = ( in_array( $iNrofArticles, $aNrofCatArticles ) ) ? $iNrofArticles : $iNrofCatArticles;
    		oxSession::setVar( '_artperpage', $iNrofCatArticles );
    	} elseif ( ( $iSessArtPerPage = oxSession::getVar( '_artperpage' ) )&& is_numeric( $iSessArtPerPage ) ) {
    		// M45 Possibility to push any "Show articles per page" number parameter
    		$iNrofCatArticles = ( in_array( $iSessArtPerPage, $aNrofCatArticles ) ) ? $iSessArtPerPage : $iNrofCatArticles;
    	} 
    	$oViewConf->setViewConfigParam( 'iartPerPage', $iNrofCatArticles );
    	return $iNrofCatArticles;
    }

    /**
     * parses dynamic properties and adds them to campaigns class member collection
     *
     * @param object $results Celebros search results object
     *
     * @return null
     */    
    public function parseDynamicProperties($results)
    {
		if($results->QueryConcepts->Count > 0){
			foreach ($results->QueryConcepts->Items as $queryConcept) {
				if(isset($queryConcept->DynamicProperties)) {
					$bCampaignAdded = false;
					foreach ($queryConcept->DynamicProperties as $dynamicProperty) {
						//Convert to object and replaces the spaces in the keys with the underscore
						$propertiesBag = $this->_arrayToObject($dynamicProperty);
						$bActiveCampain = $this->_isCampaignActive($propertiesBag);
						if($bActiveCampain && !$bCampaignAdded) {
							$bCampaignAdded = $this->_addCampaign($propertiesBag);
	                	}
					}
				}
			}
		}    		
    }
    
    /**
     * adds dynamic property to campaigns class member collection
     *
     * @param object $propertiesBag dynamic property
     *
     * @return bool $bCampaignAdded is camaign was added
     */    
    protected function _addCampaign ($propertiesBag) {
    	$bCampaignAdded = false;
    	if(isset($propertiesBag->banner_landing_page) || isset($propertiesBag->banner_image)) {
    		$this->_aBannerCampaigns[] = $propertiesBag;
    		$bCampaignAdded = true;
    	}
    	else if(isset($propertiesBag->alternative_products)) {
    		$this->_aAlternativeProductsCampaigns[] = $propertiesBag;
    		$bCampaignAdded = true;
    	}
    	else if(isset($propertiesBag->redirection_url)) {
    		$this->_aRedirectCampaigns[] = $propertiesBag;
    		$bCampaignAdded = true;
    	}
    	else if(isset($propertiesBag->custom_message)) {
    		$this->_aCustomMsgs[] = $propertiesBag;
    		$bCampaignAdded = true;
    	}
    	return $bCampaignAdded;
    }
    
    /**
     * checks if campaing is active
     *
     * @param object $propertiesBag dynamic property
     *
     * @return bool $bActiveCampain if campaing is active
     */    
    protected function _isCampaignActive ($propertiesBag) {
    	$bActiveCampain = true;
    	if(isset($propertiesBag->start_datetime) && strtotime($propertiesBag->start_datetime) > time()) $bActiveCampain = false;
    	if(isset($propertiesBag->end_datetime) && $propertiesBag->end_datetime != "9999/12/31 11:59:59 PM" && strtotime($propertiesBag->end_datetime) < time()) $bActiveCampain = false;
    	return $bActiveCampain;
    }
    
    /**
     * gets alternative products message
     *
     * @return string alternative products message
     */    
    public function getAlternativeProductsMsg () {
        $campaigns = $this->getAlternativeProductsCampaigns();
        return (count($campaigns) ? $campaigns[0]->alternative_products : "");
    }    
    
     /**
     * converts associative array to object 
     *
     * @param array $array array to convert
     *
     * @return object conversion result
     */
    protected function _arrayToObject($array) {

    	if (!(is_array($array) && count($array) > 0)) return false;
    	
        $object = new stdClass();
		foreach ($array as $name=>$value) {
             $name = strtolower(trim($name));
             $name = str_replace(" ", "_", $name);
             if (!empty($name)) {
                $object->$name = $value;
             }
		}
		return $object;
    }
    
    /**
     * gets banner campaign array
     *
     * @return array banner campaign array
     */    
    public function getBannerCampaigns(){
        return $this->_aBannerCampaigns;
    }
    
    /**
     * gets alternative products campaign array
     *
     * @return array alternative products campaign array
     */    
    public function getAlternativeProductsCampaigns(){
        $this->_aAlternativeProductsCampaigns;
    }
    
    /**
     * gets redirect campaign array
     *
     * @return array redirect campaign array
     */    
    public function getRedirectCampaigns(){
        return $this->_aRedirectCampaigns;
    } 

    /**
     * gets custom message array
     *
     * @return array custom message array
     */    
    public function getCustomMsgs(){
        return $this->_aCustomMsgs;
    }      
    
}

?>