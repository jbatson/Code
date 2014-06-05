<?php
/*
 * Class Authorize 
 * @version 1.1
 * @date 12/10/09
 * @updated 05/04/10
 * @compatibility: PHP 4, PHP 5
 */

class Authorize
{
	var $paymentMode;
	var $authorizeLogin;
	var $authorizeKey;
	var $authorizeDelimData;
	var $authorizeType;
	var $authorizeRelayResponse;
	var $authorizeMethod;
	var $authorizeRelayUrl;		
	var $authorizeDelimiter;
	var $authorizeTest;	
	var	$x_response_code;
	var	$x_response_subcode;
	var	$x_response_reason_code;
	var	$x_response_reason_text;
	var	$x_trans_id;	
	
	/**
	 * @function Authorize
	 * @access public
	 * @return array
	 * @param array $var_data -> POST DATA
	 */
	
	function Send($var_post_data)
	{		
		$dbQuery = mysql_query("SELECT * FROM cms_config");
		
		while($dbResults = mysql_fetch_array($dbQuery, MYSQL_ASSOC))
		{
			$configKey = $dbResults['config_key'];
			$$configKey =  $dbResults['config_value'];
		}

		$this->setPaymentMode($payment_mode);
		$this->setAuthorizeLogin($authorize_login);
		$this->setAuthorizeKey($authorize_key);
		$this->setAuthorizeDelimData($authorize_delim_data);
		$this->setAuthorizeDelimiter($authorize_delimiter);
		$this->setAuthorizeMethod($authorize_method);
		$this->setAuthorizeRelayResponse($authorize_relay_response);
		$this->setAuthorizeRelayUrl($authorize_relay_url);
		$this->setAuthorizeType($authorize_type);
		$this->setAuthorizeTest($authorize_test_request);
	
		$authorizeString = $this->buildAuthorize($var_post_data);
		
		switch($this->getPaymentMode())
		{
			case "LIVE":
				$authorizeUrl = "https://secure.authorize.net/gateway/transact.dll";
			break;
			case "TEST":
				$authorizeUrl = "https://test.authorize.net/gateway/transact.dll";
			break;	
		}		

		$initCurl = curl_init($authorizeUrl);
		
		curl_setopt($initCurl, CURLOPT_HEADER, 0); 							// tells curl to include headers in response
		curl_setopt($initCurl, CURLOPT_RETURNTRANSFER, 1); 					// return into a variable
		curl_setopt($initCurl, CURLOPT_POSTFIELDS, $authorizeString); 		// adding POST data
		
		// =============================================================
		// SET OPTIONS FOR GODADDY ACCOUNT 
		// MUST USE TUNNELING
		// =============================================================
		
		/*
		 * Uncomment for go daddy
		 */
		
		/*
		curl_setopt($var_ch, CURLOPT_HTTPPROXYTUNNEL, true);
		curl_setopt($var_ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
		curl_setopt($var_ch, CURLOPT_PROXY, '64.202.165.130:3128');
		*/
		
		$initResult = curl_exec($initCurl);
		
		curl_close($initCurl);
						
		$finalResults = explode($this->getAuthorizeDelimiter(),$initResult);
				
		// ===========================================================
		// RETURNED FIELDS ARE NUMERIC
		// ===========================================================
		// 0 -> response field
		// 1 -> response sub field
		// 2 -> response reason code
		// 3 -> response reason text
		// 4 -> approval code
		// 5 -> avs result code
		// 6 -> transaction id
		// 9 -> amount
		// 13 -> first name
		// 14 -> last name
		// 37 -> md5 hash
		

		$this->x_response_code = $finalResults[0];
		$this->x_response_subcode = $finalResults[1];
		$this->x_response_reason_code = $finalResults[2];
		$this->x_response_reason_text = $finalResults[3];
		$this->x_trans_id = $finalResults[6];							
								
		return $finalResults;
	}
	
	/**
	 * @function buildAuthorize
	 * @access public
	 * @return authorize string
	 * @param array $var_data -> POST DATA
	 */	
	
	function buildAuthorize($var_data)
	{		
		$authnetValues				= array
		(
	        "x_login"				=> $this->getAuthorizeLogin(),
	        "x_version"				=> "3.1",
			'x_test_request'        => $this->getAuthorizeTest(),
	        "x_delim_char"			=> $this->getAuthorizeDelimiter(),
	        "x_delim_data"			=> $this->getAuthorizeDelimData(),
	        "x_url"					=> "FALSE",
	        "x_type"				=> $this->getAuthorizeType(),
	        "x_method"				=> $this->getAuthorizeMethod(),
	        "x_tran_key"			=> $this->getAuthorizeKey(),
	        "x_relay_response"		=> $this->getAuthorizeRelayResponse(),
	        "x_card_num"			=> $var_data['cc_number'],
	        "x_exp_date"			=> $var_data['cc_month'].'/'.$var_data['cc_year'],
	        "x_amount"				=> $var_data['payment'], 
	        "x_first_name"			=> $var_data['cc_first'],
	        "x_last_name"			=> $var_data['cc_last'],
	        "x_email"		        => $var_data['email_address'],
	        "x_card_code"           => $var_data['cc_ccv']
		);
		
		$authorizeString = '';
		
		foreach( $authnetValues as $key => $value )
		{
			$authorizeString .= $key."=" . urlencode( $value ) . "&";
		}		
		
		$authorizeString = rtrim($authorizeString, "&");
		
		return $authorizeString;
	}

    /**
     * Returns $authorizeDelimData.
     *
     * @see Authorize::$authorizeDelimData
     */
    public function getAuthorizeDelimData()
    {
        return $this->authorizeDelimData;
    }
    
    /**
     * Sets $authorizeDelimData.
     *
     * @param object $authorizeDelimData
     * @see Authorize::$authorizeDelimData
     */
    public function setAuthorizeDelimData($authorizeDelimData)
    {
        $this->authorizeDelimData = $authorizeDelimData;
    }
    
    /**
     * Returns $authorizeDelimiter.
     *
     * @see Authorize::$authorizeDelimiter
     */
    public function getAuthorizeDelimiter()
    {
        return $this->authorizeDelimiter;
    }
    
    /**
     * Sets $authorizeDelimiter.
     *
     * @param object $authorizeDelimiter
     * @see Authorize::$authorizeDelimiter
     */
    public function setAuthorizeDelimiter($authorizeDelimiter)
    {
        $this->authorizeDelimiter = $authorizeDelimiter;
    }
    
    /**
     * Returns $authorizeKey.
     *
     * @see Authorize::$authorizeKey
     */
    public function getAuthorizeKey()
    {
        return $this->authorizeKey;
    }
    
    /**
     * Sets $authorizeKey.
     *
     * @param object $authorizeKey
     * @see Authorize::$authorizeKey
     */
    public function setAuthorizeKey($authorizeKey)
    {
        $this->authorizeKey = $authorizeKey;
    }
    
    /**
     * Returns $authorizeLogin.
     *
     * @see Authorize::$authorizeLogin
     */
    public function getAuthorizeLogin()
    {
        return $this->authorizeLogin;
    }
    
    /**
     * Sets $authorizeLogin.
     *
     * @param object $authorizeLogin
     * @see Authorize::$authorizeLogin
     */
    public function setAuthorizeLogin($authorizeLogin)
    {
        $this->authorizeLogin = $authorizeLogin;
    }
    
    /**
     * Returns $authorizeMethod.
     *
     * @see Authorize::$authorizeMethod
     */
    public function getAuthorizeMethod()
    {
        return $this->authorizeMethod;
    }
    
    /**
     * Sets $authorizeMethod.
     *
     * @param object $authorizeMethod
     * @see Authorize::$authorizeMethod
     */
    public function setAuthorizeMethod($authorizeMethod)
    {
        $this->authorizeMethod = $authorizeMethod;
    }
    
    /**
     * Returns $authorizeRelayResponse.
     *
     * @see Authorize::$authorizeRelayResponse
     */
    public function getAuthorizeRelayResponse()
    {
        return $this->authorizeRelayResponse;
    }
    
    /**
     * Sets $authorizeRelayResponse.
     *
     * @param object $authorizeRelayResponse
     * @see Authorize::$authorizeRelayResponse
     */
    public function setAuthorizeRelayResponse($authorizeRelayResponse)
    {
        $this->authorizeRelayResponse = $authorizeRelayResponse;
    }
    
    /**
     * Returns $authorizeRelayUrl.
     *
     * @see Authorize::$authorizeRelayUrl
     */
    public function getAuthorizeRelayUrl()
    {
        return $this->authorizeRelayUrl;
    }
    
    /**
     * Sets $authorizeRelayUrl.
     *
     * @param object $authorizeRelayUrl
     * @see Authorize::$authorizeRelayUrl
     */
    public function setAuthorizeRelayUrl($authorizeRelayUrl)
    {
        $this->authorizeRelayUrl = $authorizeRelayUrl;
    }
    
    /**
     * Returns $authorizeType.
     *
     * @see Authorize::$authorizeType
     */
    public function getAuthorizeType()
    {
        return $this->authorizeType;
    }
    
    /**
     * Sets $authorizeType.
     *
     * @param object $authorizeType
     * @see Authorize::$authorizeType
     */
    public function setAuthorizeType($authorizeType)
    {
        $this->authorizeType = $authorizeType;
    }
    
    /**
     * Returns $paymentMode.
     *
     * @see Authorize::$paymentMode
     */
    public function getPaymentMode()
    {
        return $this->paymentMode;
    }
    
    /**
     * Sets $paymentMode.
     *
     * @param object $paymentMode
     * @see Authorize::$paymentMode
     */
    public function setPaymentMode($paymentMode)
    {
        $this->paymentMode = $paymentMode;
    }
	
    public function getAuthorizeTest()
    {
        return $this->authorizeTest;
    }
    
    public function setAuthorizeTest($authorizeTest)
    {
        $this->authorizeTest = $authorizeTest;
    }	
}
?>