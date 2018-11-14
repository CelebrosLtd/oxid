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
  * Celebros search results dynamic property representation.
  */
class DynamicProperty
{
	public $m_properties;
	public $DEFAULT_DELIMITER = "&";

	/**
	 * Enumerate Hashtable and create report server access specific string.
	 *
	 * @param array $properties hash table of all properties
	 * @param string $strDelimiter string use to delimiter between pairs. (empty string will become ampersand)
	 *
	 * @return null
	 */	
	public function EmumProperties($properties,$strDelimiter)
	{
		$paramsString = "";
		if ($strDelimiter == "")
			$strDelimiter = $this->DEFAULT_DELIMITER;
		
		// Enumerate properties and create report server specific string.
		//IDictionaryEnumerator customPropEnumerator = properties.GetEnumerator();
		//$customPropEnumerator = $properties.GetEnumerator();
		
		if ($properties != null)
		{
			$key = array_keys($properties);
			foreach ($key as $value)
			{
				$paramsString .= $strDelimiter
				. $value . "=" . $properties[$value];
			}
		}
		return $paramsString;
	}

	/**
	 * Add or remove url access string properties.
	 *
	 * @param string $name property name
	 * @param string $value property value
	 *
	 * @return null
	 */	
	public function SetProperty($name, $value)
	{
		$tmp = trim($name);
		if(empty($tmp)) return;
		
		$tmp = trim($value);
		if(!empty($tmp)) $this->m_properties[$name] = $value;
		elseif (isset($this->m_properties[$name])) unset($this->m_properties[$name]);
	
		// Build a new string with all the parameters as pairs.
		//$this->BuildString();
	}

	/**
	 * create string that hold all parameters delimit by ampersand
	 *
	 * @return string $strParameters delimited parameters
	 */	
	public function BuildString()
	{
		$strParameters = $this->EmumProperties($this->m_properties,"");
		return $strParameters;
	}
}