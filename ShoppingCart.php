<?php
/*
 * Class ShoppingCart 
 * @version 1.0
 * @author joseph batson <jbatson@wantabetterwebsite.com>
 * @company Want a Better Website, Inc.
 * @date 05/04/10
 * @compatibility: PHP 4, PHP 5
 */

class ShoppingCart
{
	var $itemName;
	var $itemDescription;
	var $itemNumber;
	var $itemQuantity;
	var $itemPrice;
	var $itemCart;
	var $itemArray;
	var $prductCount;
	var $productUpdate;
	var $subTotal;
	var $orderSubTotal;
	var $taxRate;
	var $paymentProcessor;
	var $paymentMode;
	var $paypalBusiness;
	var $paypalIpn;
	var $authorizeLogin;
	var $authorizeKey;
	var $authorizeDelimData;
	var $authorizeType;
	var $authorizeRelayResponse;
	var $authorizeMethod;
	var $authorizeRelayUrl;		
	var $authorizeDelimiter;

	/**
	 * @function ShoppingCart
	 * @access public
	 * @return void
	 */
	
	function ShoppingCart()
	{
		$dbQuery = mysql_query("SELECT * FROM `payment_data` WHERE active=1");
		$dbResults = mysql_fetch_array($dbQuery);
		
		$this->setTaxRate($dbResults['tax_rate']);
		$this->setPaymentMode($dbResults['payment_mode']);
		$this->setPaymentProcessor($dbResults['payment_processor']);
		
		switch($this->getPaymentProcessor())
		{
			case "PAYPAL":
				$this->setPaypalBusiness($paypalBusiness);
				$this->setPaypalIpn($paypalIpn);
			break;
			case "AUTHORIZE":
				$this->setAuthorizeLogin($dbResults['authorize_login']);
				$this->setAuthorizeKey($dbResults['authorize_key']);
				$this->setAuthorizeDelimData($dbResults['authorize_delim_data']);
				$this->setAuthorizeDelimiter($dbResults['authorize_delimiter']);
				$this->setAuthorizeMethod($dbResults['authorize_method']);
				$this->setAuthorizeRelayResponse($dbResults['authorize_relay_response']);
				$this->setAuthorizeRelayUrl($dbResults['authorize_relay_url']);
				$this->setAuthorizeType($dbResults['authorize_type']);
			break;
		}
	}
	
	/**
	 * @function addProduct
	 * @access public
	 * @return boolean
	 * @param object $item_name
	 * @param object $item_description
	 * @param object $item_number
	 * @param object $item_quantity
	 * @param object $item_price
	 */

	function addProduct($item_name, $item_description, $item_number, $item_quantity, $item_price)
	{
		$this->setItemName($item_name);
		$this->setItemDescription($item_description);
		$this->setItemNumber($item_number);
		$this->setItemQuantity($item_quantity);
		$this->setItemPrice($item_price);

		if(empty($_SESSION['CART']))
		{
			$itemArray = array($this->getItemName(), $this->getItemDescription(), $this->getItemNumber(), $this->getItemQuantity(), $this->getItemPrice());

			$_SESSION['CART'][0] = $itemArray;
		}
		else
		{
			$prductCount   = 0;
			$productUpdate = 0;

			while(isset($_SESSION['CART'][$prductCount][0]))
			{
				if(($_SESSION['CART'][$prductCount][2]) == ($this->getItemNumber()))
				{
					$_SESSION['CART'][$prductCount][0] = $this->getItemName();
					$_SESSION['CART'][$prductCount][1] = $this->getItemDescription();
					$_SESSION['CART'][$prductCount][2] = $this->getItemNumber();
					$_SESSION['CART'][$prductCount][3] = ($_SESSION['CART'][$prductCount][3] + $this->getItemQuantity());	
					$_SESSION['CART'][$prductCount][4] = ($this->getItemPrice());	

					$productUpdate = 1;
				}

				$prductCount++;
			}

			if($productUpdate == 0)
			{
				$itemArray = array($this->getItemName(), $this->getItemDescription(), $this->getItemNumber(), $this->getItemQuantity(), $this->getItemPrice());
				
				$_SESSION['CART'][$prductCount] = $itemArray;
			}				
		}

		return true;
	}

	/**
	 * @function removeProduct
	 * @access public
	 * @return boolean
	 * @param object $item_number
	 */

	function removeProduct($item_number)
	{
		$this->setItemNumber($item_number);
		
		for($i = 0; $i < count($_SESSION['CART']); $i++)
		{
			if($this->getItemNumber() == $_SESSION['CART'][$i][2])
			{
				unset($_SESSION['CART'][$i]);
				sort($_SESSION['CART']);
			}
		}
		
		return true;
	}

	/**
	 * @function updateQuantity
	 * @access public
	 * @return boolean
	 * @param object $item_number
	 * @param object $item_quantity
	 */

	function updateQuantity($item_number,$item_quantity)
	{
		$this->setItemNumber($item_number);
		$this->setItemQuantity($item_quantity);
		
		for($i = 0; $i <= count($_SESSION['CART']); $i++)
		{
			if($this->getItemNumber() == $_SESSION['CART'][$i][2])
			{
				$_SESSION['CART'][$i][3] = $this->getItemQuantity();
			}
		}
		
		return true;
	}
	
	/**
	 * @function cancelOrder
	 * @access public
	 * @return void
	 */
	
	function cancelOrder()
	{
		unset($_SESSION['CART']);
	}
	
	/*
	 * TODO
	 * addShipping needs work
	 */
	
	/**
	 * @function addShipping
	 * @access public
	 * @return float
	 * @param object $var_shipping
	 */
	
	function addShipping($var_shipping = 0)
	{
		$_SESSION['SHIPPING'] = $var_shipping;
		
		return $var_shipping_price;		
	}

	/**
	 * @function addTax
	 * @access public
	 * @return float
	 * @param object $order_sub_total
	 */
	
	function addTax($order_sub_total)
	{
		$this->setOrderSubTotal($order_sub_total);
		
		return ($this->getOrderSubTotal() * $this->getTaxRate());
	}

	/**
	 * @function subTotal
	 * @access public
	 * @return float || int
	 * @param object $var_cart
	 */

	function subTotal($var_cart)
	{
		$this->setItemCart($var_cart);
		
		$itemCart = $this->getItemCart();
		$subTotal = 0;
		
		for($i = 0; $i < count($itemCart); $i++)	
		{
			$subTotal += ($itemCart[$i][3] * $itemCart[$i][4]);
		}
					
		return $subTotal;
	}

	/**
	 * @function totalPrice
	 * @access public
	 * @return float
	 * @param object $var_cart
	 */

	function totalPrice($var_cart)
	{
		$this->setItemCart($var_cart);
		
		$itemCart      = $this->getItemCart();
		$subTotalPrice = $this->subTotal($itemCart);
		$totalPrice    = 0;
		$totalPrice    = number_format(($this->addTax($subTotalPrice) + $subTotalPrice), 2);

		return $totalPrice;
	}

	function checkOut($var_cart, $var_post_data)
	{
		$this->setItemCart($var_cart);
		
		switch($this->getPaymentProcessor())
		{
			case "PAYPAL":
				require_once('Paypal.php');
				$pp = new Paypal($var_post_data);
				
				return $pp;
			break;
			case "AUTHORIZE":
				require_once('Authorize.php');
				$ap = new Authorize($var_post_data);
				
				return $ap;
			break;			
		}
	} // end function checkOut()

    /**
     * Returns $itemDescription.
     * @see ShoppingCart::$itemDescription
     */
    function getItemDescription()
    {
        return $this->itemDescription;
    }
    
    /**
     * Sets $itemDescription.
     * @param object $itemDescription
     * @see ShoppingCart::$itemDescription
     */
    function setItemDescription($itemDescription)
    {
        $this->itemDescription = $itemDescription;
    }
    
    /**
     * Returns $itemName.
     * @see ShoppingCart::$itemName
     */
    function getItemName()
    {
        return $this->itemName;
    }
    
    /**
     * Sets $itemName.
     * @param object $itemName
     * @see ShoppingCart::$itemName
     */
    function setItemName($itemName)
    {
        $this->itemName = $itemName;
    }
    
    /**
     * Returns $itemNumber.
     * @see ShoppingCart::$itemNumber
     */
    function getItemNumber()
    {
        return $this->itemNumber;
    }
    
    /**
     * Sets $itemNumber.
     * @param object $itemNumber
     * @see ShoppingCart::$itemNumber
     */
    function setItemNumber($itemNumber)
    {
        $this->itemNumber = $itemNumber;
    }
    
    /**
     * Returns $itemPrice.
     * @see ShoppingCart::$itemPrice
     */
    function getItemPrice()
    {
        return $this->itemPrice;
    }
    
    /**
     * Sets $itemPrice.
     * @param object $itemPrice
     * @see ShoppingCart::$itemPrice
     */
    function setItemPrice($itemPrice)
    {
        $this->itemPrice = $itemPrice;
    }
    
    /**
     * Returns $itemQuantity.
     * @see ShoppingCart::$itemQuantity
     */
    function getItemQuantity()
    {
        return $this->itemQuantity;
    }
    
    /**
     * Sets $itemQuantity.
     * @param object $itemQuantity
     * @see ShoppingCart::$itemQuantity
     */
    function setItemQuantity($itemQuantity)
    {
        $this->itemQuantity = $itemQuantity;
    }

    /**
     * Returns $itemCart.
     * @see ShoppingCart::$itemCart
     */
    function getItemCart()
    {
        return $this->itemCart;
    }
    
    /**
     * Sets $itemCart.
     * @param object $itemCart
     * @see ShoppingCart::$itemCart
     */
    function setItemCart($itemCart)
    {
        $this->itemCart = $itemCart;
    }

    /**
     * Returns $orderSubTotal.
     * @see ShoppingCart::$orderSubTotal
     */
    function getOrderSubTotal()
    {
        return $this->orderSubTotal;
    }
    
    /**
     * Sets $orderSubTotal.
     * @param object $orderSubTotal
     * @see ShoppingCart::$orderSubTotal
     */
    function setOrderSubTotal($orderSubTotal)
    {
        $this->orderSubTotal = $orderSubTotal;
    }
    
    /**
     * Returns $taxRate.
     * @see ShoppingCart::$taxRate
     */
    function getTaxRate()
    {
        return $this->taxRate;
    }
    
    /**
     * Sets $taxRate.
     * @param object $taxRate
     * @see ShoppingCart::$taxRate
     */
    function setTaxRate($taxRate)
    {
        $this->taxRate = $taxRate;
    }

    /**
     * Returns $paymentMode.
     * @see ShoppingCart::$paymentMode
     */
    function getPaymentMode()
    {
        return $this->paymentMode;
    }
    
    /**
     * Sets $paymentMode.
     * @param object $paymentMode
     * @see ShoppingCart::$paymentMode
     */
    function setPaymentMode($paymentMode)
    {
        $this->paymentMode = $paymentMode;
    }
    
    /**
     * Returns $paymentProcessor.
     * @see ShoppingCart::$paymentProcessor
     */
    function getPaymentProcessor()
    {
        return $this->paymentProcessor;
    }
    
    /**
     * Sets $paymentProcessor.
     * @param object $paymentProcessor
     * @see ShoppingCart::$paymentProcessor
     */
    function setPaymentProcessor($paymentProcessor)
    {
        $this->paymentProcessor = $paymentProcessor;
    }
    
    /**
     * Returns $paypalBusiness.
     * @see ShoppingCart::$paypalBusiness
     */
    function getPaypalBusiness()
    {
        return $this->paypalBusiness;
    }
    
    /**
     * Sets $paypalBusiness.
     * @param object $paypalBusiness
     * @see ShoppingCart::$paypalBusiness
     */
    function setPaypalBusiness($paypalBusiness)
    {
        $this->paypalBusiness = $paypalBusiness;
    }

    /**
     * Returns $authorizeKey.
     * @see ShoppingCart::$authorizeKey
     */
    function getAuthorizeKey()
    {
        return $this->authorizeKey;
    }
    
    /**
     * Sets $authorizeKey.
     * @param object $authorizeKey
     * @see ShoppingCart::$authorizeKey
     */
    function setAuthorizeKey($authorizeKey)
    {
        $this->authorizeKey = $authorizeKey;
    }
    
    /**
     * Returns $authorizeLogin.
     * @see ShoppingCart::$authorizeLogin
     */
    function getAuthorizeLogin()
    {
        return $this->authorizeLogin;
    }
    
    /**
     * Sets $authorizeLogin.
     * @param object $authorizeLogin
     * @see ShoppingCart::$authorizeLogin
     */
    function setAuthorizeLogin($authorizeLogin)
    {
        $this->authorizeLogin = $authorizeLogin;
    }

    /**
     * Returns $authorizeDelimData.
     * @see ShoppingCart::$authorizeDelimData
     */
    function getAuthorizeDelimData()
    {
        return $this->authorizeDelimData;
    }
    
    /**
     * Sets $authorizeDelimData.
     * @param object $authorizeDelimData
     * @see ShoppingCart::$authorizeDelimData
     */
    function setAuthorizeDelimData($authorizeDelimData)
    {
        $this->authorizeDelimData = $authorizeDelimData;
    }
    
    /**
     * Returns $authorizeDelimiter.
     * @see ShoppingCart::$authorizeDelimiter
     */
    function getAuthorizeDelimiter()
    {
        return $this->authorizeDelimiter;
    }
    
    /**
     * Sets $authorizeDelimiter.
     * @param object $authorizeDelimiter
     * @see ShoppingCart::$authorizeDelimiter
     */
    function setAuthorizeDelimiter($authorizeDelimiter)
    {
        $this->authorizeDelimiter = $authorizeDelimiter;
    }
    
    /**
     * Returns $authorizeMethod.
     * @see ShoppingCart::$authorizeMethod
     */
    function getAuthorizeMethod()
    {
        return $this->authorizeMethod;
    }
    
    /**
     * Sets $authorizeMethod.
     * @param object $authorizeMethod
     * @see ShoppingCart::$authorizeMethod
     */
    function setAuthorizeMethod($authorizeMethod)
    {
        $this->authorizeMethod = $authorizeMethod;
    }
    
    /**
     * Returns $authorizeRelayResponse.
     * @see ShoppingCart::$authorizeRelayResponse
     */
    function getAuthorizeRelayResponse()
    {
        return $this->authorizeRelayResponse;
    }
    
    /**
     * Sets $authorizeRelayResponse.
     * @param object $authorizeRelayResponse
     * @see ShoppingCart::$authorizeRelayResponse
     */
    function setAuthorizeRelayResponse($authorizeRelayResponse)
    {
        $this->authorizeRelayResponse = $authorizeRelayResponse;
    }
    
    /**
     * Returns $authorizeRelayUrl.
     * @see ShoppingCart::$authorizeRelayUrl
     */
    function getAuthorizeRelayUrl()
    {
        return $this->authorizeRelayUrl;
    }
    
    /**
     * Sets $authorizeRelayUrl.
     * @param object $authorizeRelayUrl
     * @see ShoppingCart::$authorizeRelayUrl
     */
    function setAuthorizeRelayUrl($authorizeRelayUrl)
    {
        $this->authorizeRelayUrl = $authorizeRelayUrl;
    }
    
    /**
     * Returns $authorizeType.
     * @see ShoppingCart::$authorizeType
     */
    function getAuthorizeType()
    {
        return $this->authorizeType;
    }
    
    /**
     * Sets $authorizeType.
     * @param object $authorizeType
     * @see ShoppingCart::$authorizeType
     */
    function setAuthorizeType($authorizeType)
    {
        $this->authorizeType = $authorizeType;
    }

    /**
     * Returns $paypalIpn.
     * @see ShoppingCart::$paypalIpn
     */
    function getPaypalIpn()
    {
        return $this->paypalIpn;
    }
    
    /**
     * Sets $paypalIpn.
     * @param object $paypalIpn
     * @see ShoppingCart::$paypalIpn
     */
    function setPaypalIpn($paypalIpn)
    {
        $this->paypalIpn = $paypalIpn;
    }

} // END CLASS Shopping_Cart_Handler
?>