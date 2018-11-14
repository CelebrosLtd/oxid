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
  * Celebros analytics pixel parameters validator
  */
 class ValidateResult {
 	
 	/**
 	 * Validate parameters for SR (search) pixel
 	 *
 	 * @param string $searchHandle Celebros search handler
 	 * @param string $logHandle Celebros log handler
 	 *
	 * @return string validation error
 	 */ 	
	public function GetSRValidateResult($searchHandle, $logHandle)
	{	
		$valueSearch = trim($searchHandle);
		$valueLog = trim($logHandle);
		
		if (empty($valueSearch)) return "evrErrSearchSessionIDIsMissing"; 
		if (empty($valueLog)) return "evrErrLogHandleIsMissing";

		return "";
	}
	
	/**
	 * Validate parameters for GR (general search) pixel
	 *
	 * @param string $sessionID web session id
	 *
	 * @return string validation error
	 */	
	public function GetGRValidateResult($sessionID)
	{
		$value = trim($sessionID);
		if (empty($value)) return "evrErrSearchSessionIDIsMissing"; 
		return "";
	}
	
	/**
	 * Validate parameters for PD (product click) pixel
	 *
	 * @param string $sessionID web session id
	 * @param string $sku product sku
	 *
	 * @return string validation error
	 */		
	public function GetPDValidateResult($sessionID,$sku)
	{
		$valueSessionID = trim($sessionID);
		$valueSku = trim($sku);
		if (empty($valueSessionID)) return "evrErrSearchSessionIDIsMissing"; 
		elseif (empty($valueSku)) return "evrErrSKUIsMissing"; 	
		return "";
	}
	
	/**
	 * Validate parameters for ATC (add to cart) pixel
	 *
	 * @param string $sessionID web session id
	 * @param string $sku product sku
	 * @param string $cartID cart id
	 *
	 * @return string validation error
	 */	
	public function GetATCValidateResult($sessionID, $sku, $cartID)
	{
		$valueSessionID = trim($sessionID);
		$valueSku = trim($sku);
		$valueCartID = trim($cartID);
		if (empty($valueSessionID)) return "evrErrSearchSessionIDIsMissing";
		elseif (empty($valueSku)) return "evrErrSKUIsMissing"; 	
		elseif (empty($valueCartID)) return "evrErrCartIDIsMissing"; 
		return "";
		
	}
	
	/**
	 * Validate parameters for CO (cart info) pixel
	 *
	 * @param string $cartID cart id
	 * @param string $orderID cart id
	 *
	 * @return string validation error
	 */	
	public function GetCOValidateResult($cartID, $orderID)
	{
		$valueCartID = trim($cartID);
		$valueOrderID = trim($orderID);
		if (empty($valueCartID)) return "evrErrCartIDIsMissing"; 
		if (empty($valueOrderID)) return "evrErrOrderIDIsMissing"; 	
		return "";
	}	
}