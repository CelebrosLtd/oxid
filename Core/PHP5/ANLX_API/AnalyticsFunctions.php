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

include("LogRequest.php");

/**
 * Celebros analytics API
 */
class AnalyticsFunctions
{
	public  $DATA_COLLECTOR_ADDRESS;
	public  $CUSTOMER_ID;
	public  $CUSTOMER_NAME;
	public  $PUBLIC_KEY;

	// validate result
	public $ValidateResult;

	// Anlx object
	public $AnlxApi;

/**
 * Constructor, sets class members
 *
 * @param string $G_DATA_COLLECTOR_ADDRESS Celebros analytics service address
 * @param string $G_CUSTOMER_ID Celebros analytics customer id
 * @param string $G_CUSTOMER_NAME Celebros analytics customer name
 * @param string $G_PUBLIC_KEY
 * @param bool $bIsSSL whether to use the ssl when calling Celebros analytics service
 *
 * @return null
 */	
public function AnalyticsFunctions($G_DATA_COLLECTOR_ADDRESS, $G_CUSTOMER_ID, $G_CUSTOMER_NAME, $G_PUBLIC_KEY, $bIsSSL = false)
	{
		$this->DATA_COLLECTOR_ADDRESS=$G_DATA_COLLECTOR_ADDRESS;
		$this->CUSTOMER_ID=$G_CUSTOMER_ID;
		$this->CUSTOMER_NAME=$G_CUSTOMER_NAME;
		$this->PUBLIC_KEY=$G_PUBLIC_KEY;

		$this->AnlxApi = $this->GetAnlxObject($bIsSSL);
	}

	/**
	 * Ges Celebros analytics API object
	 *
	 * @param bool $IsSSL whether to use the ssl when calling Celebros analytics service
	 *
	 * @return object $AnlxApi analytics API object
	 */	
public function  GetAnlxObject( $IsSSL )
	{
		$AnlxApi = new LogRequest();

		if ($IsSSL)
			$AnlxApi->Mode="secured";
		else
			$AnlxApi->Mode="plainData";

		$this->ValidateResult = new sValidateResult();

		$AnlxApi->DataCollectorIP=$this->DATA_COLLECTOR_ADDRESS;
		$AnlxApi->CustomerID=$this->CUSTOMER_ID;
		$AnlxApi->CustomerName=$this->CUSTOMER_NAME;
		$AnlxApi->PublicKeyToken=$this->PUBLIC_KEY;

		return $AnlxApi;
	}

	/**
	* Call this function from any page on the site to have Qwiser Analytics log information about visits to this page.
	* 
	* @param sUserID A unique ID for the user. It could be any type of ID such as the Web Server ID, session ID, etc.
	* @param sGroupID The ID of the testing group to which this user belongs to, as defined in the Qwiser Analytics system.  If you do not know what to pass here, you may pass an empty ("") String.
	* @param sWebSessionID The ID of the current web server's session ID.
	* @param sReferrer The URL of the page which led the user to this page (the referrer). The URL should not contain any parameters.
	* @param bIsSSL Set to true if the calling page is on a Secure Socket Layer (SSL)
	* 
	* * @return string $sLogRequest Celebros analitics pixel snippet
	**/

public function Celebros_Analytics_Visit($sUserID,$sGroupID,$sWebSessionID,$sReferrer,$bIsSSL)
{
		$AnlxApi = $this->GetAnlxObject($bIsSSL);

		$value = trim($sGroupID);
		if(empty($value)) $sGroupID = "1";

		$AnlxApi->sGroupID=$sGroupID;
		$AnlxApi->sUserID=$sUserID;
		$AnlxApi->sWebSessionID=$sWebSessionID;

		// visit info
		$AnlxApi->sPreviousPageURL=$sReferrer;

		$sLogRequest = $AnlxApi->GetLogRequest("LogVisitInfo");
		$this->ValidateResult = $AnlxApi->GetValidateResult();

		return $sLogRequest;
}

	/**
	* Call this function from a Qwiser search results page to	have Qwiser Analytics collect user search behavior information.
	* <b>This function is not intended for use with a search results page that does not use Qwiser.</b>
	* 
	* @param sSearchHandle The SearchHandle String of the current search state.
	* @param sLogHandle The Qwiser LogHandle String (additional information for Qwiser Analytics) generated by Qwiser for the current state of the search results.
	* @param sUserID A unique ID for the user. It could be any type of ID such as the Web Server ID, session ID, etc.
	* @param sGroupID The ID of the testing group which this user belongs to, as defined in the Qwiser Analytics system.  If you do not know what to pass here, you may pass an empty ("") String.
	* @param sWebSessionID The ID of the current web server's session ID.
	* @param sReferrer The URL of the page from which the user got to this page (the referrer). The URL should not contain any parameters.
	* @param bIsSSL Set to true if the calling page is on a Secure Socket Layer (SSL)
	* @param bFromQwiser Set to true if Qwiser Search is used
	* 
	* @return string $sLogRequest Celebros analitics pixel snippet
	* **/

public function Celebros_Analytics_SearchResults($sSearchSession,$sLogHandle,$sUserID,$sGroupID,$sWebSessionID,$sReferrer, $bIsSSL, $bFromQwiser)
{
		$AnlxApi = $this->GetAnlxObject($bIsSSL);

		$AnlxApi->SID=$sSearchSession;
		$AnlxApi->LH=$sLogHandle;
		$AnlxApi->FromQwiser=$bFromQwiser;

		$value = trim($sGroupID);
		if(empty($value)) $sGroupID = "1";

		$AnlxApi->sGroupID=$sGroupID;
		$AnlxApi->sUserID=$sUserID;
		$AnlxApi->sWebSessionID=$sWebSessionID;
		$AnlxApi->sPreviousPageURL=$sReferrer;

	    $sLogRequest = $AnlxApi->GetLogRequest("LogSearchResult");
	    $this->ValidateResult = $AnlxApi->ValidateResult;

		return $sLogRequest;
}

	/**
	* Call this function from any search results page to have Qwiser Analytics collect user search behavior information.
	* 
	* <b>This function intend for use with a search results page that does not use Qwiser.</b>
	* @param sSessionID A unique string to identify the current search state.
	* @param sQuery The query string that the user used for his search.
	* @param bFromBrowse Is the search is coming from a browser or not.
	* @param sUserID A unique ID for the user. It could be any type of ID such as the Web Server ID, session ID, etc.
	* @param sGroupID The ID of the testing group which this user belongs to, as defined in the Qwiser Analytics system.  If you do not know what to pass here, you may pass an empty ("") String.
	* @param sWebSessionID The ID of the current web server's session ID.
	* @param sReferrer The URL of the page from which the user got to this page (the referrer). The URL should not contain any parameters.
	* @param bIsSSL Set to true if the calling page is on a Secure Socket Layer (SSL)
	* 
	* @return string $sLogRequest Celebros analitics pixel snippet
	* **/

public function Celebros_Analytics_GenericSearchResults($sSessionID,$sQuery,$bFromBrowse,$sUserID,$sGroupID,$sWebSessionID,$sReferrer, $bIsSSL)
{
		$AnlxApi = $this->GetAnlxObject($bIsSSL);

		$AnlxApi->strSearchSessionID = $sSessionID;
		$AnlxApi->strQuery = $sQuery;
		$AnlxApi->bFromBrowse = $bFromBrowse;

		$value = trim($sGroupID);
		if(empty($value)) $sGroupID = "1";

		$AnlxApi->sGroupID=$sGroupID;
		$AnlxApi->sUserID=$sUserID;
		$AnlxApi->sWebSessionID=$sWebSessionID;
		$AnlxApi->sPreviousPageURL=$sReferrer;

	    $sLogRequest = $AnlxApi->GetLogRequest("LogGenericSearchResult");
	    $this->ValidateResult = $this->AnlxApi->ValidateResult;

		return $sLogRequest;

}


	/**
	* Call this function from a product details page to have Qwiser Analytics collect information about traffic in the product details page.
	* 
	* @param sSKU The SKU or product code of the current product being displayed.
	* @param sVariant Any String definition that would distinguish this product from other products in a certain product group. e.g. "Blue" or "GS23" (a child SKU). The combination of the sSKU parameter and this one should be unique for every product variant.  If this information is not available or not applicable, you may pass an empty ("") String.
	* @param sName The name or title of the product being displayed.
	* @param fPrice The price of the product being displayed.
	* @param sCategory The category to which this product belongs, if available.  If not, you may pass an empty ("") String.
	* @param sSearchSession The SearchHandle String of the current search state, if available.  If not, you may pass an empty ("") String.
	* @param sUserID A unique ID for the user. It could be any type of ID such as the Web Server ID, session ID, etc.
	* @param sGroupID The ID of the testing group to which this user belongs to, as defined in the Qwiser Analytics system.  If you do not know what to pass here, you may pass an empty ("") String.
	* @param sWebSessionID The ID of the current web server's session ID.
	* @param sReferrer The URL of the page which led the user to this page (the referrer). The URL should not contain any parameters.
	* @param iSourceType The type of location that linked to this page:<ul><li><b>Value -> </b><b>Description</b></li><li>0 -> Qwiser Search</li><li>1 -> Banner&#47;Ad on web page</li><li>2 -> Browse (the shopper did not use the search)</li><li>3 -> E-Mail promotion</li><li>4 -> Direct Mailing (Hard copy catalog, etc.)</li><li>5 -> Print (Newspapers, periodicals, in-flight magazines, etc.)</li><li>6 -> Television</li><li>100 -> Other sources</li></ul>
	* @param sSourceName If the source type is "Other", provide a name for this source to distinguish between custom sources.  Otherwise, you may pass an empty ("") String.
	* @param bIsSSL Set to true if the calling page is on a Secure Socket Layer (SSL)
	* 
	* @return string $sLogRequest Celebros analitics pixel snippet
	* **/

public function Celebros_Analytics_ProductDetails($sSKU,$sVariant,$sName,$fPrice,$sCategory,$sSearchSession,$sUserID,$sGroupID,$sWebSessionID,$sReferrer,$iSourceType,$sSourceTypeName, $bIsSSL)
{
		$AnlxApi = $this->GetAnlxObject($bIsSSL);

		$value = trim($sGroupID);
		if(empty($value)) $sGroupID = "1";

		$AnlxApi->sGroupID=$sGroupID;
		$AnlxApi->sUserID=$sUserID;
		$AnlxApi->sWebSessionID=$sWebSessionID;

		if($iSourceType==0)
		  $AnlxApi->bUsingQwiserSearch=true;
		else
		  $AnlxApi->bUsingQwiserSearch=false;

		$AnlxApi->SID=$sSearchSession;

		// visit info
		$AnlxApi->sPreviousPageURL=$sReferrer;
		$AnlxApi->iSourceType=$iSourceType;
		$AnlxApi->sSourceTypeName=$sSourceTypeName;

		//product info
		$AnlxApi->sProductSKU=$sSKU;
		$AnlxApi->sProductVariant=$sVariant;
		$AnlxApi->sProductName=$sName;
		$AnlxApi->fProductPrice=$fPrice;
		$AnlxApi->sProductCategory=$sCategory;

		$sLogRequest = $AnlxApi->GetLogRequest("LogProductDetails");
		$this->ValidateResult = $AnlxApi->ValidateResult;

		return $sLogRequest;
}
	/**
	* Call this function from the Add-To-Cart page to have Qwiser Analytics collect information about user activity with their shopping cart.
	* 
	* This function is not intended for use when editing items in the cart or removing items from the cart.
	* When the user purchases the items in the cart, those cart changes will be picked up.
	* @param sSKU The SKU or product code of the current product being added.
	* @param sVariant Any String definition that would distinguish this product from other products in a certain product group. e.g. "Blue" or "GS23" (a child SKU). The combination of the sSKU parameter and this one should be unique for every product variant.  If this information is not available or not applicable, you may pass an empty ("") String.
	* @param sName The name or title of the product being added.
	* @param iQuantity The number of individual units of this specific product that are added to the cart.
	* @param fPrice The price of the product being displayed.
	* @param sCategory The category to which this product belongs, if available.  If not, you may pass an empty ("") String.
	* @param sCartID The ID of the user's cart, as defined in your cart management system, or another ID that can uniquely identify the specific cart.
	* @param iCartProductCount The number of products in the cart after the new addition.
	* @param fCartSubTotal The Subtotal price of all the items in the cart after the new addition.
	* @param sCartCoupon A String representing a coupon used in this cart (which applies to the entire cart), if any.  If no coupon was used, you may pass an empty ("") String.
	* @param fCartDiscount The discount factor for this cart. e.g. If the entire cart is discounted by 25%, use 0.25.  If there is no discount, use 0.
	* @param sSearchSession The SearchHandle String of the current search state.
	* @param sUserID A unique ID for the user. It could be any type of ID such as the Web Server ID, session ID, etc.
	* @param sGroupID The ID of the testing group to which this user belongs to, as defined in the Qwiser Analytics system.  If you do not know what to pass here, you may pass an empty ("") String.
	* @param sWebSessionID The ID of the current web server's session ID.
	* @param sReferrer The URL of the page which led the user to this page (the referrer). The URL should not contain any parameters.
	* @param bIsSSL Set to true if your Add-To-Cart page is on a Secure Socket Layer (SSL).
	* 
	* @return string $sLogRequest Celebros analitics pixel snippet
	**/

public function Celebros_Analytics_AddToCart($sSKU,$sVariant,$sName,$iQuantity,$fPrice,$sCategory,$sCartID,$iCartProductCount,$fCartSubTotal, $sCartCoupon,$fCartDiscount,$sSearchSession,$sUserID,$sGroupID,$sWebSessionID,$sReferrer,$bIsSSL)
{
		$AnlxApi = $this->GetAnlxObject($bIsSSL);

		$value = trim($sGroupID);
		if(empty($value)) $sGroupID = "1";

		$AnlxApi->sGroupID=$sGroupID;
		$AnlxApi->sUserID=$sUserID;
		$AnlxApi->sWebSessionID=$sWebSessionID;

		//search handle
		$value = trim($sSearchSession);
		if(!empty($value))
		{
			$AnlxApi->SID=$sSearchSession;
			$AnlxApi->bUsingQwiserSearch=true;
		}
		else
		{
			$AnlxApi->bUsingQwiserSearch=false;
			$AnlxApi->SID=$sWebSessionID;
		}

		// visit info
		$AnlxApi->sPreviousPageURL=$sReferrer;

		//'cart info
		$AnlxApi->sCartID=$sCartID;
		$AnlxApi->sCartCoupon=$sCartCoupon;
		$AnlxApi->fCartDiscount=$fCartDiscount;
		$AnlxApi->iCartProductCount=$iCartProductCount;
		$AnlxApi->fCartSubTotal=$fCartSubTotal;

		//product info
		$AnlxApi->sProductSKU=$sSKU;
		$AnlxApi->sProductVariant=$sVariant;
		$AnlxApi->sProductName=$sName;
		$AnlxApi->fProductPrice=$fPrice;
		$AnlxApi->sProductCategory=$sCategory;
		$AnlxApi->iProductQuantity=$iQuantity;

		$sLogRequest = $AnlxApi->GetLogRequest("LogAddToCart");
		$this->ValidateResult = $AnlxApi->ValidateResult;

		return $sLogRequest;
}

	/**
	* Call this function from the Checkout page to have Qwiser Analytics collect purchase activity information.
	* 
	* @param ProductsArray A 2-dimensional array containing information about all products being purchased. Each element in the first dimension represents a product. Elements in the second dimension contain product information as follows:<br><br><table border=1><tr><td><b>Index</b></td><td><b>Description</b></td></tr><tr><td>0</td><td>sSKU: The SKU or product code of the current product being purchased.</td></tr><tr><td>1</td><td>sVariant: Any string definition that would distinguish this product from other products in a certain products group. e.g. "Blue" or "GS23" (a child SKU). The combination of the sSKU parameter and this one should be unique for every product variant.  If this information is not available or not applicable, you may pass an empty ("") string.</td></tr><tr><td>2</td><td>fPrice: The price of the product being purchased.</td></tr><tr><td>3</td><td>iQuantity: The number of units of this specific product being purchased.</td></tr><tr><td>4</td><td>fProductDiscount: The discount factor applied to this specific product ot the entire cart), e.g. If there is a 25% discount on this product, use 0.25. If there is no discount, use 0.</td></tr><tr><td>5</td><td>sProductCoupon: A String representing a coupon used to purchase this product (i.e. A coupon that applies to this specific product, as opposed to one applied to the entire cart), if any. (optional)</td></tr></table><hr>
	* @param sCartID The ID of the user's cart, as defined in your cart management system, or another ID that can uniquely identify the specific cart.
	* @param iCartProductCount The number of products in the cart.
	* @param fCartTotal The total price of all items in the cart being purchased.
	* @param sCartCoupon A String representing a coupon used in this cart (which applies to the entire cart), if any.  If no coupon was used, you may pass an empty ("") String.
	* @param fCartDiscount The discount factor for this cart. e.g. If the entire cart is discounted by 25%, use 0.25.  If there is no discount, use 0.
	* @param sUserID A unique ID for the user. It could be any type of ID such as the Web Server ID, session ID, etc.
	* @param sGroupID The ID of the testing group to which this user belongs to, as defined in the Qwiser Analytics system.  If you do not know what to pass here, you may pass an empty ("") String.
	* @param sWebSessionID The ID of the current web server's session ID.
	* @param sReferrer The URL of the page which led the user to this page (the referrer). The URL should not contain any parameters.
	* @param bIsSSL Set to true if the calling page is on a Secure Socket Layer (SSL) .
	* 
	* @return string $sLogRequest Celebros analitics pixel snippet
	**/

public function Celebros_Analytics_CheckOut($sOrderID, $ProductsArray,$sCartID,$iCartProductCount,$fCartTotal,$sCartCoupon,$fCartDiscount,$sUserID,$sGroupID,$sWebSessionID,$sReferrer,$bIsSSL)
{
		$AnlxApi = $this->GetAnlxObject($bIsSSL);
		
		$value = trim($sGroupID);
		if(empty($value)) $sGroupID = "1";

		$AnlxApi->sGroupID=$sGroupID;
		$AnlxApi->sUserID=$sUserID;
		$AnlxApi->sWebSessionID=$sWebSessionID;
		$AnlxApi->sPreviousPageURL =$sReferrer;

		//products
		$AnlxApi->ProductsArray=$ProductsArray;
		//'cart info
		$AnlxApi->sCartID=$sCartID;
		$AnlxApi->sCartCoupon=$sCartCoupon;
		$AnlxApi->fCartDiscount=$fCartDiscount;
		$AnlxApi->iCartProductCount=$iCartProductCount;
		$AnlxApi->fCartSubTotal=$fCartTotal;

		// order info
		$AnlxApi->sOrderID=$sOrderID;

		$sLogRequest = $AnlxApi->GetLogRequest("LogCheckOut");
		$this->ValidateResult = $AnlxApi->ValidateResult;

		return $sLogRequest;
 }

 /**
  * Gets last error message
  *
  * @return string error message
  **/ 
 public function GetLastErrorMessage()
 {
 	return $this->ValidateResult->strMessage;
 }

 /**
  * Gets last error severity
  *
  * @return integer error severity
  **/ 
 public function GetLastErrorSeverity()
 {
 	return $this->ValidateResult->iSeverity;
 }
}
