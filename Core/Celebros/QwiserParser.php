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
  * Celebros search response parser.
  */ 
namespace Celebros\Conversionpro\Core\Celebros;  
  
class QwiserParser
{
	// Qwiser XMLservice data
	public $oParser;
	public $sRequest;

	public $oResult;

	public $sCurTag = '$this->oResult';
    public $aCurTags = array();
	public $sCurAttr = '';
	public $sCurCdata = '';

    //encoding related
	public $sSourceEnconing;
	public $sTargetEncoding;
    public $sEncodingMethod; 

	public $aCounters = array ();
	public $aCounterReseters = array ();
	public $aAttributesAsAssocArray = array ();
	public $aIgnoredTags = array ();
	public $sReplaceArrayNamesWith = '';
	public $sArrayCountPropertyName = '';

	// Error state
	public $sLastOperationErrorMessage;
	public $blLastOperationSucceeded;
    
    // HtmlEntities specific var
    public $aRestoreHtmlEntities = array();

    
    /**
     * Constructor, sets class members
     *
     * @param string $sRequest Celebros request url
     *
     * @return null
     */    
	function __construct($sRequest)
	{
		$this->sRequest = $sRequest;
		$this->blLastOperationSucceeded = 1;
    	$this->sLastOperationErrorMessage = '';

		$this->aCounters = array (
			"Question" => 0,
			"Answer" => 0,
			"Product" => 0,
			"Value" => 0,
			"Concept" => 0,
            "Entry" =>0,
            "Value" => 0,
            "SiteStatus" => 0,
            "QwiserError" => 0
		);
        
		$this->aCounterReseters = array (
			"Questios" => "Question",
			"Answers" => "Answer",
            "ExtraAnswers" => "Answer",
			"Products" => "Product",
			"AddtionalSuggestions" => "Value",
			"SpecialCasesDetectedInThisSearch" => "Value",
			"QueryConcepts" => "Concept",
            "SearchPath" => "Entry",
            "AddtionalSuggestions" => "Value",
            "SpecialCasesDetectedInThisSession" => "Value",
            "SearchEngineStatus" => "SiteStatus",
            "Last5Errors" => "QwiserError"
		);
        
		$this->aAttributesAsAssocArray = array (
			"name",
			"value"
		);
        
		$this->sReplaceArrayNamesWith = "Items";
		$this->sArrayCountPropertyName = "Count";
        
        $this->oResult = new \stdClass();
        
        $this->aCurTags = array();
        
        $this->iEncodingMethod = 0;
        $this->sSourceEnconing = 0;
        $this->sTargetEncoding = 0;
	}
    
	/**
	 * Sets parser encodig converter 
	 *
	 * @param string $sSourceEnconing source encoding
	 * @param string $sTargetEncoding target encoding
	 * @param integer $iEncodingMethod encoding method 0=disabled, 1=xml_parser, 2=htmlentities, 3=iconv, 4=mb, 2=recode
	 *
	 * @return null
	 */    
    function set_enconig_converter($sSourceEnconing, $sTargetEncoding, $iEncodingMethod)
    {
        $this->sSourceEnconing = $sSourceEnconing;
        $this->sTargetEncoding = $sTargetEncoding;
        $this->iEncodingMethod = $iEncodingMethod;
        
        switch ($this->iEncodingMethod) 
        {
            case 2:
            {
				$this->set_htmlentities_converter();
                break;
            }
            
            case 3:
            {
				$this->set_iconv_converter();
                break;
            }
            
            case 4:
            {
				$this->set_mb_convert_encoding_converter();
                break;
            }  
            
            case 5:
            {
            	$this->set_recode_string_converter();
                break;
            }
        }
    }
    
    /**
     * Sets htmlentities converter
     *
     * @return null
     */    
    function set_htmlentities_converter()
    {
    	if (!$this->isPhpFunctionExist("htmlentities")) {
    		$this->sLastOperationErrorMessage = "Could not set encoding converter method ".$this->iEncodingMethod;
    		$this->blLastOperationSucceeded = 0;
    	} else {
    		// we need this to restore HTML tags from entities in strings ;
    		$this->aRestoreHtmlEntities = array_flip(get_html_translation_table(HTML_SPECIALCHARS,ENT_COMPAT));
    	}
    }
    
    /**
     * Sets iconv converter
     *
     * @return null
     */    
    function set_iconv_converter()
    {
		if (!$this->isPhpFunctionExist("iconv")) {
			$this->sLastOperationErrorMessage = "Could not set encoding converter method ".$this->iEncodingMethod;
			$this->blLastOperationSucceeded = 0;
		}
    }

    /**
     * Sets mb_convert_encoding converter
     *
     * @return null
     */    
    function set_mb_convert_encoding_converter()
    {
		if (!$this->isPhpFunctionExist("mb_convert_encoding")) {
			$this->sLastOperationErrorMessage = "Could not set encoding converter method ".$this->iEncodingMethod;
			$this->blLastOperationSucceeded = 0;
		} 
    } 

    /**
     * Sets recode_string converter
     *
     * @return null
     */    
    function set_recode_string_converter()
    {
    	if (!$this->isPhpFunctionExist("recode_string")) {
    		$this->sLastOperationErrorMessage = "Could not set encoding converter method ".$this->iEncodingMethod;
    		$this->blLastOperationSucceeded = 0;
    	}
    }    
    
    /**
     * Checks if the php function exist
     *
     * @param string $name php function name
     *
     * @return null
     */    
    function isPhpFunctionExist($name)
    {
    	return function_exists($name);
    }
    
    /**
     * Encode string
     *
     * @param string $sInput string to encode
     *
     * @return string $sInput encoded string
     */    
    function convert_encoding($sInput)
    {
        switch ($this->iEncodingMethod) {
            case 2:
            {
				return strtr(htmlentities($sInput, ENT_COMPAT, $this->sSourceEnconing),$this->aRestoreHtmlEntities);
				break;
            }
            case 3:
            {
                return iconv($this->sSourceEnconing, $this->sTargetEncoding, $sInput); 
                break;
            }
            case 4:
            {
                if($this->sSourceEnconing) return mb_convert_encoding ( $sInput, $this->sTargetEncoding , $this->sSourceEnconing );
                else return mb_convert_encoding ( $sInput, $this->sTargetEncoding ); 
                break;
            }  
            case 5:
            {
            	if($this->isPhpFunctionExist("recode_string")) return recode_string($this->sSourceEnconing."..".$this->sTargetEncoding , $sInput);
                break;
            } 
            default:
            {
                return $sInput; 
                break;
            }
		}
    }

    /**
     * Run parser
     *
     * @return object $this->oResult parsed response object
     */    
	function run()
	{       
        // this aproach is used, because functio fopen throws 2 warnings if url not fount
        $fp = $this->getSearchXmlHandle();
        if ( $fp === false )
		{
			$this->sLastOperationErrorMessage = "could not open XML input";
			$this->blLastOperationSucceeded = 0;

			return;
		}

		$this->createParser();

		//setting Target Encoding
		if ($this->iEncodingMethod === 1 && $this->sTargetEncoding)
		{
			xml_parser_set_option($this->oParser, XML_OPTION_TARGET_ENCODING, $this->sTargetEncoding);
		}

		xml_parser_set_option($this->oParser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($this->oParser, XML_OPTION_SKIP_WHITE, 1);

		xml_set_object($this->oParser, $this);
		xml_set_element_handler($this->oParser, 'startElementHandler', 'endElementHandler');
		xml_set_character_data_handler($this->oParser, 'characterDataHandler');

		$this->parse($fp);
        return $this->oResult;
	}
	
	/**
	 * Creates parser object
	 *
	 * @return null
	 */	
	function createParser()
    {
		if ($this->iEncodingMethod === 1 && $this->sSourceEnconing)
		{
			$this->oParser = xml_parser_create($this->sSourceEnconing);
		}
		else
		{
			$this->oParser = xml_parser_create();
		}		
	}
	
	/**
	 * Creates parse file
	 *
	 * @return null
	 */	
	function parse($fp)
    {
		while ($data = fread($fp, 4096))
		{
			if (!xml_parse($this->oParser, $data, feof($fp)))
			{
				$this->sLastOperationErrorMessage = sprintf("XML error: %s at line %d", xml_error_string(xml_get_error_code($this->oParser)), xml_get_current_line_number($this->oParser));
				$this->blLastOperationSucceeded = 0;
				return;
			}
		}
		xml_parser_free($this->oParser);
	}

	/**
	 * Opens file
	 *
	 * @return resource @fopen pointer to response
	 */	
	function getSearchXmlHandle()
    {
		return @fopen($this->sRequest, "r");
	}
	
	/**
	 * Starts xml element handler
	 * 
	 * @param object $oParser parser
	 * @param string $sName xml element name
	 * @param string $aAttributes xml attributes
	 *
	 * @return null
	 */	
	function startElementHandler($oParser, $sName, $aAttributes)
	{
		//array_push($this->aCurTags,$sName);
        if (array_key_exists($sName, $this->aCounterReseters)) {
            $this->aCounters[$this->aCounterReseters[$sName]] = 0;
        }

		if (array_key_exists($sName, $this->aCounters)) {
			if (!empty($this->sArrayCountPropertyName)) {
				$sCounterNode = $this->sCurTag . '->' . $this->sArrayCountPropertyName . '=' . ($this->aCounters[$sName] + 1) . ';';
                $prop =& $this->_getPropFromString($this->sCurTag);
                $propName = $this->sArrayCountPropertyName;
                $value = $this->aCounters[$sName] + 1;
                $prop->$propName = $value;
			}

			if ($this->sReplaceArrayNamesWith) {
				$this->sCurTag .= '->' . $this->sReplaceArrayNamesWith;
			} else {
				$this->sCurTag .= '->' . $sName;
			}

			$this->sCurTag .= '[' . $this->aCounters[$sName] . ']';
			$this->aCounters[$sName]++;
		} else {
			$this->sCurTag .= '->' . $sName;
		}

		$this->evaluateNode($aAttributes, $sName);
	}
	
	/**
	 * Evaluates xml node
	 *
	 * @param string $aAttributes xml attributes
	 *
	 * @return null
	 */	
	function evaluateNode($aAttributes, $sName=null)
    {
		$iAttributesCount = count($aAttributes);
		if ($iAttributesCount) {
			$blAttributerAsAssocArray = ($iAttributesCount == 2 && count(array_diff(array_keys($aAttributes), $this->aAttributesAsAssocArray)) == 0) ? true : false;
			if ($blAttributerAsAssocArray) {
				//$sNode = $this->sCurTag . '["' . $aAttributes[$this->aAttributesAsAssocArray[0]] . '"]="' . addslashes($this->convert_encoding($aAttributes[$this->aAttributesAsAssocArray[1]])) . '";';
                $prop =& $this->_getPropFromString(
                    $this->sCurTag . '[' . $aAttributes[$this->aAttributesAsAssocArray[0]] . ']', $this->_prepareData($this->convert_encoding($aAttributes[$this->aAttributesAsAssocArray[1]]))
                );
			} else {
				foreach ($aAttributes as $name => $value) {
					//$sNode = $this->sCurTag . '->' . $name . '="' . addslashes($this->convert_encoding($value)) . '";';
                    $prop =& $this->_getPropFromString($this->sCurTag);
                    $prop->$name = $this->_prepareData($this->convert_encoding($value));
				}
			}
		}		
	}

	/**
	 * Ends xml element handler
	 *
	 * @param object $oParser parser
	 * @param string $sName xml element name
	 *
	 * @return null
	 */	
	function endElementHandler($oParser, $sName)
	{
		//array_pop($this->aCurTags);
        if (!empty ($this->sCurCdata)) {
			$sNode = $this->sCurTag . "='" . $this->_prepareData($this->convert_encoding($this->sCurCdata)) . "';";
			eval($sNode);
		}
        
		$this->sCurTag = substr($this->sCurTag, 0, strrpos($this->sCurTag, '->'));
		$this->sCurCdata = '';
	}

	/**
	 * Formats Celebros data handler
	 *
	 * @param object $oParser parser
	 * @param string $sData data handler
	 *
	 * @return null
	 */	
	function characterDataHandler($oParser, $sData)
	{
		$sData = str_replace(rawurldecode("%09"), "", $sData);
		if (($this->sCurCdata == '') && (str_replace("\n", '', $sData) == '')) {
			$sData = str_replace("\n", '', $sData);
		}
        
		if ($sData != '') {
			$this->sCurCdata .= $sData;
		}
	}

    protected function _prepareData(String $string)
    {
        return /*addslashes(*/$string/*)*/;
    }
    
    protected function _getPropFromString(String $string, $value=null)
    {
        $prop = null;
        $ev = explode('->', $string);
        $num = count($ev);
        $i = 0;
        foreach ($ev as $item) {
            $i++;
            if ($item == '$this') {
                $prop =& $this;
            } else {
                if (strpos($item, "[") !== false) {
                    $tmp = explode("[", $item);
                    $tmp[1] = str_replace("]", "", $tmp[1]);
                    $p = $tmp[0];
                    $k = $tmp[1];
                    if ($i === $num && $value) {
                        $prop->$p[$k] = $value;
                        $prop =& $prop->$p[$k];
                        return $prop;    
                    }
                    
                    if (!isset($prop->$p[$k])) {
                        $prop->$p[$k] = new \stdClass();
                    }
                    
                    $prop =& $prop->$p[$k];
                } else {
                    if (!isset($prop->$item)) {
                        $prop->$item = new \stdClass();
                    }
                    
                    $prop =& $prop->$item;
                }
            }
        }
        
        return $prop;    
    }    
}