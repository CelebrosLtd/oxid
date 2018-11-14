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
  * Packetizer for celebros analytics pixel
  */ 
class packetizer
{
	public $SplitMethod;
	public $PacketSize;
	public $SplitID;
	public $TotalPackets;
	
	/**
	 * Constructor, sets class members
	 *
	 * @param string $SplitMethod pixel split method
	 * @param string $PacketSize packet size
	 *
	 * @return null
	 */	
	public function packetizer($SplitMethod,$PacketSize)
	{
		$this->SplitMethod=$SplitMethod;
		$this->PacketSize=$PacketSize;
		$this->SplitID=uniqid(rand(), true);
	}

	/**
	 * Gets splitted pixel
	 *
	 * @param string $Data pixel data
	 *
	 * @return array splitted pixel
	 */	
	public function Split($Data)
	{
	 if($this->PacketSize<strlen($Data))
	 {
		$len=strlen($Data)/$this->PacketSize;
		$len=ceil($len);
		$this->TotalPackets=$len;
		for($i=0;$i<$len;$i++)
		{
			$splitArray[$i]=substr($Data,$i*$this->PacketSize,$this->PacketSize);
		}
		
	 }
	 else 
	 {
	 	$splitArray[0]=$Data;
	 	$this->TotalPackets=1;
	 }
	 
	 return $splitArray;
		 
	}
	
	/**
	 * Gets split method
	 *
	 * @return string split method
	 */	
	public function GetSplitMethod()
	{
		switch ($this->SplitMethod)
		{
			CASE "bySize":
				return "2";
			default:
				return "-1";//TODO	
		}
	}
	
}
