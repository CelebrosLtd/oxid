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
  * Place holder functionality general class.
  */ 
class Holder
{
	
	/**
	 * Gets paded parameters string
	 *
	 * @param array $arr parameters to pad
	 *
	 * @return string $res paded parameters string
	 */	
	public static function GetStream($arr)
	{
		$strStream;
		$res;
		$sb;
		$i;

		for($i=0;$i<count($arr);$i++)
		{
			$strStream = $arr[$i];
			$strStream = Holder::MakeStreamItem($strStream);
			$sb.=$strStream;
		}
		$res = Holder::MakeStreamItem($sb);
		return $res;
	}

	/**
	 * Gets paded item string
	 *
	 * @param string $strItem item to pad
	 *
	 * @return string $res paded item string
	 */	
	public static function MakeStreamItem($strItem)
	{
		define("LENGTH_LENGTH",4);
		$strLength;
		$res;
		$i;
	
		$strLength = strlen($strItem);
		for($i=strlen($strLength);$i<LENGTH_LENGTH;$i++)
			$res.="0";
		$res.=$strLength;
		$res.=$strItem;
	
		return $res;
	}
	
	/**
	 * Sets zero value if empty
	 *
	 * @param string $value value to check
	 *
	 * @return string original or zero value
	 */	
	public static function SetZeroValueIfNeeded($value) {
		return (empty($value)) ? 0 : $value;
	}
}

/**
 * Specific place holder
 */
class ReferrerInfoHolder
{
	public $ReferrerUrl;
	public $CampaignName;
	public $CampaignType;
	
	/**
	 * Converts object to paded string
	 *
	 * @return string $strStream paded string
	 */	
	public function ToString()
	{
		$strStream = "";
		$sc; 
		
		$value = trim($this->CampaignType);
		$this->CampaignType=Holder::SetZeroValueIfNeeded($value);

		$sc[]=$this->ReferrerUrl;
		$sc[]=$this->CampaignName;
		$sc[]=$this->CampaignType;
		$sc[]=$this->SearchPhrase;
			
		$strStream = Holder::GetStream($sc);
		$strStream = StringEncoder::UUEncode($strStream);
		return $strStream;
	}
}

/**
 * Specific place holder
 */
class GenericSRHolder
{
	public $SearchSession;
	public $Query;
	public $FromBrowse;
	
	/**
	 * Converts object to paded string
	 *
	 * @return string $strStream paded string
	 */	
	public function ToString()
	{
		$strStream = "";
		$sc; 
		
		$sc[]=$this->SearchSession;
		$sc[]=$this->Query;
		$sc[]=$this->FromBrowse;
			
		$strStream = Holder::GetStream($sc);
		$strStream = StringEncoder::UUEncode($strStream);
		return $strStream;
	}
}

/**
 * Specific place holder
 */
class SRAdditionalHolder
{
	public $SpellingCorrectionDriven;
	public $CorrectedFrom;
	public $FromBrowse;
	
	/**
	 * Converts object to paded string
	 *
	 * @return string $strStream paded string
	 */	
	public function ToString()
	{
		$strStream = "";
		$sc; 
		
		$sc[]=$this->SpellingCorrectionDriven;
		$sc[]=$this->CorrectedFrom;
		$sc[]=$this->FromBrowse;
			
		$strStream = Holder::GetStream($sc);
		$strStream = StringEncoder::UUEncode($strStream);
		return $strStream;
	}
}

/**
 * Specific place holder
 */
class CartInfoHolder
{
	public $CartID;
	public $Coupon;
	public $Discount;
	public $ProductCount;
	public $SubTotal;

		/**
		 * Converts object to paded string
		 *
		 * @return string $strStream paded string
		 */	
		public function ToString()
		{
			$strStream = "";
			$sc=array();
			
			$value = trim($this->ProductCount);
			$this->ProductCount=Holder::SetZeroValueIfNeeded($value);

			$value = trim($this->Discount);
			$this->Discount=Holder::SetZeroValueIfNeeded($value);
			
			$value = trim($this->SubTotal);
			$this->SubTotal=Holder::SetZeroValueIfNeeded($value);
				
			$sc[]=$this->CartID;
			$sc[]=$this->ProductCount;
			$sc[]=$this->Coupon;
			$sc[]=$this->Discount;
			$sc[]=$this->SubTotal;

			$strStream = Holder::GetStream($sc);
			$strStream = StringEncoder::UUEncode($strStream);
			return $strStream;
		}
}

/**
 * Specific place holder
 */
class ProductDetailsHolder
{
	public $ProductList;
	
	/**
	 * Converts object to paded string
	 *
	 * @return string $strStream paded string
	 */	
		public function  ToString()
		{
			$strStream = "";
			$sc=array();

			// add the number of products in array.
			$sc[]=count($this->ProductList);

			// add products
			foreach ($this->ProductList as $p)
			{
			
				$value = trim($p["Price"]);
				$p["Price"] = Holder::SetZeroValueIfNeeded($value);
			
				$value = trim($p["Quantity"]);
				$p["Quantity"] = Holder::SetZeroValueIfNeeded($value);	
									
				$scProduct[]=$p["SKU"];
				$scProduct[]=$p["Variant"];
				$scProduct[]=$p["Price"];
				$scProduct[]=$p["Quantity"];
				$scProduct[]=$p["Category"];
				$scProduct[]=$p["Name"];

				$sc[]=Holder::GetStream($scProduct);
			}
			
			$strStream = Holder::GetStream($sc);
			$strStream = StringEncoder::UUEncode($strStream);
			//$strStream = StringEncoder::UUEncode($sc);
			return $strStream;
		}
}

/**
 * Specific place holder
 */
class ProductCOHolder
{
	public $ProductList;
	
	/**
	 * Converts object to paded string
	 *
	 * @return string $strStream paded string
	 */	
		public function  ToString()
		{
			$strStream = "";
			$sc=array();

			// add the number of products in array.
			$sc[]=count($this->ProductList);
			if (count($this->ProductList)>0)
			{
				// add products
				foreach ($this->ProductList as $p)
				{
					$value = trim($p["Price"]);
					$p["Price"] = Holder::SetZeroValueIfNeeded($value);
					$value = trim($p["Quantity"]);
					$p["Quantity"] = Holder::SetZeroValueIfNeeded($value);
					$value = trim($p["Discount"]);
					$p["Discount"] = Holder::SetZeroValueIfNeeded($value);
														
					$scProduct[]=$p["SKU"];
					$scProduct[]=$p["Variant"];
					$scProduct[]=$p["Price"];
					$scProduct[]=$p["Quantity"];
					$scProduct[]=$p["Coupon"];
					$scProduct[]=$p["Discount"];

					$sc[]=Holder::GetStream($scProduct);
					$scProduct=array();	
				}
			}
			$strStream = Holder::GetStream($sc);
			$strStream = StringEncoder::UUEncode($strStream);
			return $strStream;
		}
}

/**
 * Specific place holder
 */
class PageInfoHolder
{
	public $Name;
	public $Url;
	public $Category;

	/**
	 * Converts object to paded string
	 *
	 * @return string $strStream paded string
	 */	
	public function ToString()
	{
		$sc[]=$this->Name;
		$sc[]=$this->Url;
		$sc[]=$this->Category;
		$str=Holder::GetStream($sc);
		return StringEncoder::UUEncode($str);
	}
}