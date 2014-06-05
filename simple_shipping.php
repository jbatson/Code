<?php
class SimpleShipping
{
	public $simpleQuote = array();	
	private $costArray = array();
	private $simpleCombine = array();
	private $defaultCombine = array();
	private $defaultQuote = array();

	public function __construct($method_array)
	{
		if(is_array($method_array) && !empty($method_array))
		{
			foreach($method_array as $k => $v)
			{
				$sQuery = mysql_query("SELECT * FROM simple_shipping_rules WHERE method_code='".$k."'");
				
				if(mysql_num_rows($sQuery) > 0)
				{
					$sResults = mysql_fetch_array($sQuery, MYSQL_ASSOC);
					$this->simpleCombine[] = array('type' => $sResults['carrier_title'], 'method_code' => $k, 'title' => $sResults['ssn_title'], 'cost' => $v);
				}
				else 
				{
					$gQuery = mysql_query("SELECT * FROM shipping_methods WHERE method_code='".$k."'");
					
					if(mysql_num_rows($gQuery) > 0)
					{
						$gResults = mysql_fetch_array($gQuery, MYSQL_ASSOC);					
						$this->defaultCombine[] = array('type' => $gResults['carrier'], 'method_code' => $k, 'title' => $gResults['method_title'], 'cost' => $v);
					}	
				}		
			}

			$this->simpleQuote = $this->CombineShipping($this->simpleCombine);
			$this->defaultQuote = $this->CombineShipping($this->defaultCombine);

			if((is_array($this->simpleQuote) && !empty($this->simpleQuote)) && (is_array($this->defaultQuote) && !empty($this->defaultQuote)))
			{
				$this->simpleQuote = array_merge($this->simpleQuote,$this->defaultQuote);
			}
			else
			{
				if(is_array($this->simpleQuote) && !empty($this->simpleQuote))
				{
					$this->simpleQuote = $this->simpleQuote;
				}
				else 
				{
					if(is_array($this->defaultQuote) && !empty($this->defaultQuote))
					{
						$this->simpleQuote = $this->defaultQuote;
					}
				}
			}
		}
	}

	private function CombineShipping($dataArray)
	{
		$costArray = array();
		
		if(is_array($dataArray) && !empty($dataArray))
		{
			foreach($dataArray as $key => $row) 
			{
			    $type[$key]  = $row['type'];
			    $method_code[$key] = $row['method_code'];
				$title[$key] = $row['title'];
				$cost[$key] = $row['cost'];
			}

			array_multisort($title, SORT_ASC, $cost, SORT_ASC, $dataArray);
				
			foreach($dataArray as $k => $v)
			{
				if($this->inMultiarray($v['title'], $costArray) === false)
				{
					$costArray[] = $dataArray[$k];	
				}
			}

			return $this->RebuildArray($costArray);
		}	
	}

	private function RebuildArray($dataArray)
	{
		if(is_array($dataArray) && !empty($dataArray))
		{
			foreach($dataArray as $key => $row) 
			{
			    $type[$key]  = $row['type'];
			    $method_code[$key] = $row['method_code'];
				$title[$key] = $row['title'];
				$cost[$key] = $row['cost'];
			}

			array_multisort($title, SORT_ASC, $cost, SORT_ASC, $dataArray);

			return $dataArray;
		}	
	}
	
	private function ResetKeys($array = array(), $recurse = false) 
	{
	    $returnArray = array();
		
	    foreach($array as $key => $value) 
	    {
	        if($recurse && is_array($value)) 
	        {
	            $value = $this->ResetKeys($value, true);
	        }
			
	        if(gettype($key) == 'integer') 
	        {
	            $returnArray[] = $value;
	        } 
	        else 
	        {
	            $returnArray[$key] = $value;
	        }
	    }
	
	    return $returnArray;
	}	
	
	private function inMultiarray($elem, $array) 
	{
        foreach($array as $key => $value) 
        {
            if ($value==$elem)
            {
                return true;
            }
            elseif(is_array($value))
            {
                if($this->inMultiarray($elem, $value))
				{
                	return true;
				}
            }
        }
       
        return false;
    }	
}
?>