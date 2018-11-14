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

include_once("packetizer.php");
include_once("StringEncoder.php");
include_once("Holders.php");
include_once("DataStructure.php");
include_once("ValidateResult.php");
include_once("DynamicProperty.php");

/**
 * Builds analytics pixel for various analytics actions logging
 */
class LogRequest
		{
		public $DataCollectorIP;
		public $dpCustomProperties;
		public $PublicKeyToken;
		public $Referrer;

		public $RequestID;
		public $CustomerID;
		public $CustomerName;

		// Product members

		public $bUsingQwiserSearch;
		public $sProductName;
	    public $sProductSKU;
		public $sProductVariant;
		public $fProductPrice;
		public $sProductCategory;
		public $iProductQuantity;

		//General memebers

		public $sUserID;
		public $sGroupID;
		public $sWebSessionID;
		public $Mode;
		public $SID;
		public $LH;
		public $sCurrentPageURL;
		public $sPreviousPageURL;
		public $iSourceType;
		public $sSourceTypeName;

		// QWACart memebers

		public $sCartID;
		public $sCartCoupon;
		public $fCartDiscount;
		public $iCartProductCount;
		public $fCartSubTotal;

		// checkout members
		public $sOrderID;

		// Product array
		public $ProductsArray;

		// Search result memebers
		public $SpellingCorrectionDriven = false;
		public $CorrectedFrom;

		// Generic search members
		public $strQuery;
		public $strSearchSessionID;
		public $bFromBrowse = false;
		public $bFromQwiser = true;

		// QWAValidateResult memebers
		public $ValidateResult;

		// custom property
		public $m_customProperty;
		
		private $MAX_PACKET_SIZE = 800;
		private $UNSECURED_PORT = 80;
		private $SECURED_PORT = 443;		

	/**
	 * Gets analytics pixel string for specific action
	 *
	 * @param string $LogRequestName action name to log
	 *
	 * @return string $strLogReq pixel string
	 */		
	public function GetLogRequest($LogRequestName)
	{
			$strHttp     = "";
			$iCurrentPort= 0;
			$strLogReq	= "";			// return string with img tags.
			$strRqtID =uniqid(rand(), true);       //????? id of the log Request.
			$RequestData = $this->GetData($LogRequestName);		   // actual data.
			$DataLength	= strlen($RequestData);		// total length of the data.
			$dTimeStamp	= $this->GetTimeStamp();
			$strAsmVer	= "3.0";
			if ( $this->ValidateLogRequest($LogRequestName) == "Error") return "";

			$oRequestDetails = $this->getHttpAndPort();
			$strHttp = $oRequestDetails->strHttp;
			$iCurrentPort = $oRequestDetails->iCurrentPort;
			
			// packetizer
			 $p = $this->GetPacketizer("bySize",$this->MAX_PACKET_SIZE);
			 $splitData = $p->Split($RequestData);

			 $nSplitIdx = 0;

			foreach ($splitData as $packet) // the split method.
			{
				$strLogReq .= "<IMG BORDER='0' NAME='QWISERIMG' WIDTH='1' HEIGHT='1' SRC='" . $strHttp . "://" . $this->DataCollectorIP . ":" . $iCurrentPort . "/QwiserDataCollector/EventListener.aspx";
				// concatenate additional attributes:
				// source identifier
				$strLogReq	.=	"?";

				$strLogReq	.=	"rqt_t=" .   $dTimeStamp		 			//Time when the event was fire
					.	"&amp;sys_sm="	. $p->GetSplitMethod()   //$p->SplitMethod			//split method - byPair | bySize
					.	"&amp;sys_sid="	. $p->SplitID					//split guid
					.	"&amp;sys_sx="	. $nSplitIdx					//Split indeX - index number of the packet
					.	"&amp;sys_stp="	. $p->TotalPackets;			//split total - total number of packets in this split

				$strLogReq	.= "&amp;sys_dz="	. $DataLength;					//request size (data only.)

				$strLogReq	.=	"&amp;ctm_id="	. urlencode($this->CustomerID)	//celebros customer ID
					.   "&amp;ctm_n="	. urlencode($this->CustomerName) // name of the customer
					.	"&amp;ctm_pkt="	. $this->PublicKeyToken 	//Public Key Token for encryption
					.	"&amp;rqt_id="	. $this->GetLogRequestID($LogRequestName) //the id of the request predefine / custom //TODO: add GetId mechanizm
					.	"&amp;rqt_g="	. $this->sGroupID				//Group ID
					.	"&amp;rqt_s="	. $this->sWebSessionID			//Web Session ID
					.	"&amp;rqt_n="	. $this->RqtNmae($LogRequestName)  	//Name of the request. significant only in customProperty
					.	"&amp;rqt_u="	. urlencode($this->sUserID)	//User ID
					.	"&amp;rqt_v="	. $strAsmVer
					.	"&amp;rqt_m="	. $this->iMode($this->Mode) ;				//Mode of the request compressed | unprocessed | enctypted

				//check sum number
				// data section
				// d_dat will hold only split encoded data
				$strLogReq	.=	"&amp;d_dat=" . $packet;					// actual data of the request

				// close tag
				$strLogReq	.= "'>";

				$strLogReq = str_replace("?&","?",$strLogReq);

				$nSplitIdx++;
			}
			return $strLogReq;
		}
		
		/**
		 * Gets object with http or https and port
		 *
		 * @return object $oResult object with http or https and port
		 */		
		public function getHttpAndPort() {
			$oResult = new stdClass();
			switch ($this->Mode)
			{
				case "plainData":
					$oResult->strHttp = "http";
					$oResult->iCurrentPort  = $this->UNSECURED_PORT;
					break;
				case "encrypted":
					break;
				case "secured":
					$oResult->strHttp = "https";
					$oResult->iCurrentPort = $this->SECURED_PORT;
					break;
				default:
					$oResult->strHttp = "http";
					$oResult->iCurrentPort = $this->UNSECURED_PORT;
					break;
			}
			return $oResult;			
		}
		
		/**
		 * Gets timestamp
		 *
		 * @return integer timestamp
		 */		
		public function GetTimeStamp() {
			return gmdate(U) - 946684800;  // mktime()-946677600; //946684800 <- time from 01/01/1970 till 01/01/2000; 946080000;946634400
		}		
		
		/**
		 * Gets Packetizer object for spliting long pixel to multiple pixels
		 *
		 * @param string $SplitMethod split method
		 * @param string $PacketSize packet size
		 *
		 * @return object Packetizer object
		 */		
		public function GetPacketizer($SplitMethod,$PacketSize) {
			return new Packetizer($SplitMethod,$PacketSize);
		}

		/**
		 * Gets Celebros pixel data string
		 *
		 * @param string $LogRequestName action to log to Celebros analytics
		 *
		 * @return string Celebros pixel data string
		 */		
		public function GetData($LogRequestName)
		{
			switch ($LogRequestName)
			{
				case "LogSearchResult":
					return $this->GetLogSearchResult();
				break;
				case "LogProductDetails":
					return $this->GetLogProductDetails();
				break;
				case "LogAddToCart":
					return $this->GetLogAddToCart();
				break;
				case "LogCheckOut":
					return $this->GetLogCheckOut();
				break;
				case "LogVisitInfo":
					return $this->GetLogVisitInfo();
				break;
				case "LogGenericSearchResult":
					return $this->GetLogGenericSearch();
				break;
				default:
					return $this->GetLogVisitInfo();
			}
		}

		/**
		 * Add Custom Properties to the pixel.
		 *
		 * @param string $strName property name
		 * @param string $strValue property value
		 *
		 * @return string Celebros pixel data string
		 */		
		public function AddCustomProperty($strName, $strValue)
		{
			if ($this->m_customProperty == null )
				$this->m_customProperty = new DynamicProperty();

			$name = "cd_" . $strName;
			$this->m_customProperty->SetProperty($name, $strValue);
		}

		/**
		 * Gets string of Custom Properties data for the pixel.
		 *
		 * @return string Custom Properties data for the pixel
		 */		
		public function GetCustomData()
		{
			// data in override implementation should be serialized with the string encoder to
			// prevent char problems and security issues.
			//... for additional data don't forget the & delimiter between pairs.

			if (!isset($this->m_customProperty)) return "";

			$strProperties = $this->m_customProperty->BuildString();
			return StringEncoder::UUEncode($strProperties);
		}

		/**
		 * Gets string of product click pixel.
		 *
		 * @return string $strData product click pixel
		 */
		public function GetLogProductDetails()
		{
			$strCustomData = $this->GetCustomData();
			$strData = "";
			$strProductDetails ="";
	

			//sProduct p = new sProduct();
			$Prod = new ProductDetailsHolder();

			$value = trim($this->iProductQuantity);
			if(empty($value)) $this->iProductQuantity=1;
			
			$value = trim($this->fProductPrice);
			if(empty($value)) $this->fProductPrice=0;

			$Prod->ProductList[0]["SKU"]		= $this->sProductSKU;
			$Prod->ProductList[0]["Variant"]	= $this->sProductVariant;
			$Prod->ProductList[0]["Name"]		= $this->sProductName;
			$Prod->ProductList[0]["Price"]		= $this->fProductPrice;
			$Prod->ProductList[0]["Category"]	= $this->sProductCategory;
			$Prod->ProductList[0]["Quantity"]	= $this->iProductQuantity;

			$strData .= $this->getEncodedProductDetails($Prod);
			$strData .= $this->getEncodedSessionId();
			
			$strData .= $this->getEncodedRef();

			$strData .= $this->getEncodedCustomData();

			return $strData;
		}

		/**
		 * Gets string of search occured pixel.
		 *
		 * @return string $strData search occured pixel
		 */		
		public function GetLogSearchResult()
		{
			$strData = "";

			$strData .= $this->getEncodedRef();

			$value = trim($this->SID);
			if (!empty($value)) $strData	.= "0d_ssid=" . StringEncoder::UUEncode($this->SID);

			$value = trim($this->LH);
			if (!empty($value)) $strData	.= "0d_lh=" . $this->LH;

			$strData	.= "0d_fq=" . $this->BoolToString($this->bFromQwiser);

			$ADSearchHolder = new SRAdditionalHolder();
			$ADSearchHolder->SpellingCorrectionDriven	= $this->BoolToString($this->SpellingCorrectionDriven);
			$ADSearchHolder->CorrectedFrom				= $this->CorrectedFrom;
			$ADSearchHolder->FromBrowse					= $this->BoolToString($this->bFromBrowse);

			$strADSearchInfo = $ADSearchHolder->ToString();
			$value = trim($strADSearchInfo);
			if (!empty($value))	$strData	.= "0d_sr=" . $strADSearchInfo;

			//			SRAdditionalHolder	SRHolder = new SRAdditionalHolder();
			//			SRHolder.SpellingCorrectionDriven = m_bSpellingCorrectionDriven;
			//			SRHolder.CorrectedFrom = m_strCorrectedFrom;
			//			SRHolder.FromBrowse = m_bFromBrowse;
			//			string strSRAdditional = SRHolder.ToString();
			//
			//			if (!String.Empty.Equals(strSRAdditional))
			//				strData += "0d_sr=" + strSRAdditional;
			//
			$strData .= $this->getEncodedCustomData();
	
			return $strData;
		}

		/**
		 * Gets string of generic search occured pixel.
		 *
		 * @return string $strData generic search occured pixel
		 */		
		public function GetLogGenericSearch()
		{
			$strData = "";
			$strGenericInfo = "";

			$strData .= $this->getEncodedRef();

			$GenericHolder = new GenericSRHolder();
			$GenericHolder->SearchSession = $this->strSearchSessionID;
			$GenericHolder->Query			= $this->strQuery;
			$GenericHolder->FromBrowse		= $this->BoolToString($this->bFromBrowse);

			$strGenericInfo = $GenericHolder->ToString();
			$value = trim($strGenericInfo);
			if (!empty($value))	$strData	.= "0d_gsr=" . $strGenericInfo;

			$strData .= $this->getEncodedCustomData();

			return $strData;
		}

		/**
		 * Gets string of add to cart occured pixel.
		 *
		 * @return string $strData add to cart occured pixel
		 */
		public function GetLogAddToCart()
		{
			$strData	= "";
			$strCartInfo = "";
			$strProductDetails = "";

			$Prod = new ProductDetailsHolder();
			
			$value = trim($this->iProductQuantity);
			if (empty($value)) $this->iProductQuantity=1;

			$Prod->ProductList[0]["SKU"]		= $this->sProductSKU;
			$Prod->ProductList[0]["Variant"]	= $this->sProductVariant;
			$Prod->ProductList[0]["Name"]		= $this->sProductName;
			$Prod->ProductList[0]["Price"]		= $this->fProductPrice;
			$Prod->ProductList[0]["Category"]	= $this->sProductCategory;
			$Prod->ProductList[0]["Quantity"]	= $this->iProductQuantity;

			$strData .= $this->getEncodedProductDetails($Prod);

			$cartHolder = new CartInfoHolder();
			$cartHolder->CartID		= $this->sCartID;
			$cartHolder->Coupon		= $this->sCartCoupon;
			$cartHolder->Discount		= $this->fCartDiscount;
			$cartHolder->ProductCount = $this->iCartProductCount;
			$cartHolder->SubTotal		= $this->fCartSubTotal;

			$strData	.= $this->getEncodedCartInfo($cartHolder);
			$strData .= $this->getEncodedSessionId();
			
			//new code: Ignoring the bUsingQwiserSearch parameter
			/*
			if (!( $this->SID == null || $this->SID=="" || (trim($this->SID)) == "" ))
				$strData	.= "0d_sid=" . StringEncoder::UUEncode($this->SID);
			else
				$strData	.= "0d_sid=" . StringEncoder::UUEncode($this->sWebSessionID);
			*/
			$strData .= $this->getEncodedRef();
			$strData .= $this->getEncodedCustomData();

			return $strData;
		}
		
		/**
		 * Gets string of encoded cart info for the  pixel.
		 *
		 * @return string $sResult encoded cart info for the  pixel
		 */		
		public function getEncodedCartInfo($cartHolder) {
			$sResult = "";
			$strCartInfo = $cartHolder->ToString();
			$value = trim($strCartInfo);
			if (!empty($value))	$sResult = "0d_ci=" . $strCartInfo;
			return $sResult;
		}		
		
		/**
		 * Gets string of encoded product info for the pixel
		 *
		 * @return string $sResult encoded product info for the pixel
		 */		
		public function getEncodedProductDetails($Prod) {
			$sResult = "";
			$strProductDetails = $Prod->ToString();
			$value = trim($strProductDetails);
			if (!empty($value)) $sResult = "0d_pd=" . $strProductDetails;
			return $sResult;
		}

		/**
		 * Gets string of encoded product checkout details for the pixel
		 *
		 * @return string $sResult encoded product checkout details for the pixel
		 */		
		public function getEncodedProductDetailsCheckOut($Prod) {
			$sResult = "";
			$strProductDetails = $Prod->ToString();
			$value = trim($strProductDetails);
			if (!empty($value)) $sResult = "0d_pdco=" . $strProductDetails;
			return $sResult;
		}		
		
		/**
		 * Gets string of encoded custom data for the pixel
		 *
		 * @return string $sResult encoded custom data for the pixel
		 */		
		public function getEncodedCustomData() {
			$sResult = "";
			$strCustomData = $this->GetCustomData();
			$value = trim($strCustomData);
			if (!empty($value)) $sResult = "0d_cd=" . $strCustomData;
			return $sResult;
		}		
		
		/**
		 * Gets string of encoded ref param for the pixel
		 *
		 * @return string $sResult encoded ref param for the pixel
		 */		
		public function getEncodedRef() {
			$sResult = "";
			$strReferrerData = $this->GetReferrerData();
			$value = trim($strReferrerData);
			if (!empty($value))	$sResult = "0d_ref=" . $strReferrerData;
			return $sResult;
		}		
		
		/**
		 * Gets string of encoded session id param for the pixel
		 *
		 * @return string $sResult encoded session id param for the pixel
		 */		
		public function getEncodedSessionId() {
			$sResult = "";
			if ( $this->bUsingQwiserSearch == true )
			{
				$value = trim($this->SID);
				if (!empty($value)) $sResult = "0d_sid=" . StringEncoder::UUEncode($this->SID);
			}
			else
			{
				$sResult = "0d_sid=" . StringEncoder::UUEncode($this->sWebSessionID);
			}
			return $sResult;
		}

		/**
		 * Gets string of checkout occured pixel.
		 *
		 * @return string $strData checkout occured pixel
		 */		
		public function GetLogCheckOut()
		{
			$strData	= "";

			// fill all products
			$Prod = new ProductCOHolder();			
			if(count($this->ProductsArray)>0)
			{
				for($i=0;$i<count($this->ProductsArray);$i++)
				{
					$Prod->ProductList[$i]["SKU"]		= $this->ProductsArray[$i]["SKU"];
					$Prod->ProductList[$i]["Variant"]	= $this->ProductsArray[$i]["Variant"];
					$Prod->ProductList[$i]["Discount"]	= $this->ProductsArray[$i]["Discount"];
					$Prod->ProductList[$i]["Price"]		= $this->ProductsArray[$i]["Price"];
					$Prod->ProductList[$i]["Quantity"]	= $this->ProductsArray[$i]["Quantity"];
					$Prod->ProductList[$i]["Coupon"]	= $this->ProductsArray[$i]["Coupon"];
				}
			}
			$strData .= $this->getEncodedProductDetailsCheckOut($Prod);

			// fill cart info
			$cartHolder = new CartInfoHolder();
			$cartHolder->CartID		= $this->sCartID;
			$cartHolder->Coupon		= $this->sCartCoupon;
			$cartHolder->Discount		= $this->fCartDiscount;
			$cartHolder->ProductCount = $this->iCartProductCount;
			$cartHolder->SubTotal		= $this->fCartSubTotal;

			$strData .= $this->getEncodedCartInfo($cartHolder);

			$value = trim($this->sOrderID);
			if (!empty($value))	$strData	.= "0d_oid=" . StringEncoder::UUEncode($this->sOrderID);

			$strData .= $this->getEncodedRef();
			$strData .= $this->getEncodedCustomData();

			return $strData;
		}

		/**
		 * Gets string of visit occured pixel.
		 *
		 * @return string of visit occured pixel
		 */
		public function GetLogVisitInfo()
		{
			$strData = "";
			$PageInfo = new PageInfoHolder();

			$strData .= $this->getEncodedRef();

			$PageInfo->Name  = $this->Name;
			$PageInfo->Url  =  $this->sCurrentPageURL;
			$PageInfo->Category = $this->Category;

			$strPage = $PageInfo->ToString();

			$value = trim($strPage);
			if (!empty($value)) $strData	.= "0d_page=" . $strPage;

			//... for additional data  don't forget the & between pairs.*/

			$strData .= $this->getEncodedCustomData();

			return $strData;
		}

		/**
		 * Validates pixel parameters
		 *
		 * @param string $LogRequestName action name
		 * 
		 * @return integer $iSeverity error severity
		 */		
		public function ValidateLogRequest($LogRequestName)
		{
			$Message = "";

			$this->ValidateResult = new sValidateResult();

			// error message
			$iSeverity = "Error";

			
			$custValue = trim($this->CustomerID);
			$dcValue = trim($this->DataCollectorIP);
			// customer error
			if (empty($custValue)) $Message = "evrErrCustomerIDIsMissing";
			// data collector errors
			elseif (empty($dcValue))$Message = "evrErrServerNameIsMissing";

			if ( $Message == "" )
			{
				$oValidateResult = $this->CreateValidateResult();
				switch ($LogRequestName)
				{
					case "LogSearchResult":
						$Message = $oValidateResult->GetSRValidateResult($this->SID, $this->LH);
						break;
					case "LogGenericSearchResult":
						$Message = $oValidateResult->GetGRValidateResult($this->strSearchSessionID);
						break;
					case "LogProductDetails":
						$Message = $oValidateResult->GetPDValidateResult($this->SID, $this->sProductSKU);
						break;
					case "LogAddToCart":
						$Message = $oValidateResult->GetATCValidateResult($this->SID, $this->sProductSKU, $this->sCartID);
						break;
					case "LogCheckOut":
						$Message = $oValidateResult->GetCOValidateResult($this->sCartID, $this->sOrderID);
						break;
					default:
						$Message = "";
				}
			}

			if ($Message == "")
			{

				$iSeverity = "Warning";

				/*if ( (DataCollector.Port == $this->UNSECURED_PORT) && (m_Mode == enumRequestMode.secured) )
				{
					sCurrValidateRes.MessageID = enumValidateResult.evrWrnConflictInDCParam;
					return sCurrValidateRes;
				}*/
				
				$value = trim($this->CustomerName);
				if (empty($value)) $Message = "evrWrnCustomerNameISMissing";
			}

			if ($Message == "" ) $iSeverity = "RequestOK";

			$this->ValidateResult->iSeverity  = $iSeverity;
			$this->ValidateResult->strMessage = $Message;

			return $iSeverity;

		}

	/**
	 * Gets ValidateResult object
	 *
	 * @return object ValidateResult
	 */		
	public function CreateValidateResult(){		
		return new ValidateResult();
	}
		
	/**
	 * Gets pixel mode
	 *
	 * @param string $Mode pixel mode name
	 *
	 * @return integer pixel mode
	 */	
		public function iMode($Mode)
		{
			$aDictionary = array();
			$aDictionary["plainData"] = 0;
			$aDictionary["encrypted"] = 1;
			$aDictionary["secured"] = 2;
			
			return isset($aDictionary[$Mode]) ? $aDictionary[$Mode] : -1;

			/*switch ($Mode)
			{
				case "plainData":
						return 0;
				case "encrypted":
						return 1;
				case "secured":
					    return 2;
				default:
						return -1;
			}*/
		}

		/**
		 * Gets pixel request type parameter
		 *
		 * @param string $LogRequestName action name
		 *
		 * @return string pixel request type parameter
		 */		
		public function RqtNmae($LogRequestName)
		{
			
			$aDictionary["LogSearchResult"] = "sr";
			$aDictionary["LogProductDetails"] = "PD";	
			$aDictionary["LogAddToCart"] = "ToCRT";
			$aDictionary["LogCheckOut"] = "CkOut";
			$aDictionary["LogVisitInfo"] = "VisitInfo";
			$aDictionary["LogGenericSearchResult"] = "GSR";
			
			return isset($aDictionary[$LogRequestName]) ? $aDictionary[$LogRequestName] : -1;			
			
			/*switch ($LogRequestName)
			{
				CASE "LogSearchResult":
						return "sr";
				CASE "LogProductDetails":
						return "PD";
				CASE "LogAddToCart":
						return "ToCRT";
				CASE "LogCheckOut":
						return "CkOut";
				CASE "LogVisitInfo":
						return "VisitInfo";
				case "LogGenericSearchResult":
						return "GSR";
				default:
						return -1;//TODO
			}*/
		}

		/**
		 * Gets pixel request id
		 *
		 * @param string $LogRequestName action name
		 *
		 * @return string pixel request id
		 */		
		public function GetLogRequestID($LogRequestName)
		{
			$aDictionary["LogSearchResult"] = "1";
			$aDictionary["LogProductDetails"] = "2";
			$aDictionary["LogAddToCart"] = "3";
			$aDictionary["LogCheckOut"] = "4";
			$aDictionary["LogVisitInfo"] = "5";
			$aDictionary["LogGenericSearchResult"] = "6";
			
			return isset($aDictionary[$LogRequestName]) ? $aDictionary[$LogRequestName] : -1;
			
				/*switch ($LogRequestName)
			{
				CASE "LogSearchResult":
						return "1";
				CASE "LogProductDetails":
						return "2";
				CASE "LogAddToCart":
						return "3";
				CASE "LogCheckOut":
						return "4";
				CASE "LogVisitInfo":
						return "5";
				CASE "LogGenericSearchResult":
						return "6";
				default:
						return -1;//TODO
			}*/
		}

		/**
		 * Gets referal parameters for the pixel
		 *
		 * @return string referal parameters for the pixel
		 */
		public function  GetReferrerData()
		{
			$RefInfo = new ReferrerInfoHolder();

			$RefInfo->ReferrerUrl = $this->sPreviousPageURL;
			$RefInfo->CampaignName = $this->sSourceTypeName;
			$RefInfo->SearchPhrase = $this->SearchPhrase;
			$RefInfo->CampaignType = $this->iSourceType;

			return $RefInfo->ToString();
		}

		/**
		 * Gets ValidateResult object
		 *
		 * @return object ValidateResult
		 */		
		public function GetValidateResult()
		{
			return $this->ValidateResult;
		}

		public function BoolToString($bool)
		{
			if ($bool == 1 )
				return "True";
			else
				return "False";
		}
    }


