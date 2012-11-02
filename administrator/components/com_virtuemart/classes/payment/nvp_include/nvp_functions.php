<?php 
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );

//Function used to find the PP_WPP payment module after it is installed.
function retrievePaymentModule()
{
		$db = new ps_DB;

		$q = "SELECT payment_method_id, payment_enabled, payment_method_name from #__{vm}_payment_method WHERE payment_method_code='PP_WPP' AND ";
		$q .= "(enable_processor='' OR enable_processor='Y')";
		
		$db->query($q);
		if($db->num_rows())
		{
			$db->next_record();
			$module = array('payment_id' => $db->f('payment_method_id'), 'payment_enabled' => $db->f('payment_enabled'), 'payment_name' => $db->f('payment_method_name'));
			
			return $module;
		}
		else
		{
			return false;
		}	
}

//Get Express Checkout
function NVP_GetExpressCheckout($token)
{
	if($token != "")
	{
		require_once( CLASSPATH . "payment/nvp_include/nvp_connection.php");
		require_once(CLASSPATH ."payment/ps_paypal_wpp.cfg.php");
		$ttoken = urlencode($token);
		//nvp request is simple as we only need to send the token that we got back from paypal.
		$nvpreq = "&TOKEN=".$ttoken;
		
		$API_UserName=PP_WPP_USERNAME;


		$API_Password=PP_WPP_PASSWORD;

		
		$API_Signature=PP_WPP_SIGNATURE;
		
		
		$API_Certificate=PP_WPP_CERTIFICATE;

		
		//Check to see if we are in sandbox mode. If we are we will use the beta sandbox nvp system, since it closely resembles the live paypal. Otherwise, we will use the live nvp system.
		
		if (PP_WPP_SANDBOX == '1')
		{
			if (PP_WPP_USE_CERTIFICATE == '1')
			{
				$API_Endpoint = 'https://api.sandbox.paypal.com/nvp'; 
			}
			else
			{
				$API_Endpoint = 'https://api-3t.sandbox.paypal.com/nvp';
			}
		}
		else
		{
			if (PP_WPP_USE_CERTIFICATE == '1')
			{
				$API_Endpoint = 'https://api.paypal.com/nvp';
			}
			else
			{
				$API_Endpoint = 'https://api-3t.paypal.com/nvp';
			}
		}	

			
		//API Version to use
		$version=PP_WPP_VERSION;
		
		//Send our npv request to paypal and get our array back.
		$nvpRES = hash_call("GetExpressCheckoutDetails",$nvpreq,$API_UserName,$API_Password,$API_Certificate,$API_Signature,$API_Endpoint,$version);
		
		//return the array so we can process it in request_paypal.tpl.php under the checkout folder in the templates.
		return $nvpRES;	
	}
	else
	{
		return false;
	}
}

//Set Express Checkout
function NVP_SetExpressCheckout($d, $dbst, $info)
{
	if($d && $dbst && $info)
	{
		require_once( CLASSPATH . "payment/nvp_include/nvp_connection.php");
		require_once(CLASSPATH ."payment/ps_paypal_wpp.cfg.php");
		
		//Gather info that we passed into the function from request_paypal.tpl.php in checkout under templates.
		$orderT = urlencode(round($d['order_total'],2));
		$taxT = urlencode($d['tax_total']);
		$email = urlencode($d['email']);
		
		//Get the country code
		$db_new = new ps_DB;
	    $query_str = "SELECT * FROM #__{vm}_country WHERE country_3_code='" . substr($dbst->f("country"), 0, 60) . "'";
	    $db_new->setQuery($query_str);
	    $db_new->query();
	    $db_new->next_record();
		
		$ship_name = urlencode(substr($dbst->f("first_name"), 0, 50).' '.substr($dbst->f("last_name"), 0, 50));
		$ship_street1 = urlencode(substr($dbst->f("address_1"), 0, 60));
		$ship_city = urlencode(substr($dbst->f("city"), 0, 40));
		$ship_state = urlencode(substr($dbst->f("state"), 0, 40));
		$ship_country = urlencode($db_new->f("country_2_code"));
		$ship_zip = urlencode(substr($dbst->f("zip"), 0, 20));
		
		//Gather the ids that VM creates so we can pass it to paypal and have it return to the checkout page properly.
		$shiptoID = $info['ship_to'];
		
		$shipRATE = $info['ship_rate'];
		
		$itemID = $info['item_id'];
		
		$paymentAction = PP_WPP_PAYMENT_ACTION;
		$paymentModule = retrievePaymentModule();
		
		//Retrieve our payment module and get the ID of it.
		if($paymentModule)
		{
			$paymentID = $paymentModule['payment_id'];
		}
		else
		{
			return false;
		}
		
		//Build the return and cancel url for paypal.
		$returnURL = urlencode(SECUREURL."index.php?page=checkout.paypal_ex&option=com_virtuemart&checkout_stage=3&payment_method_id=$paymentID&ship_to_info_id=$shiptoID&shipping_rate_id=$shipRATE&Itemid=$itemID");
		$cancelURL = urlencode(SECUREURL."index.php?page=checkout.paypal_ex&option=com_virtuemart&checkout_stage=1");
		
		$nvpreq = "&AMT=$orderT&PAYMENTACTION=$paymentAction&CURRENCYCODE=USD&RETURNURL=$returnURL&CANCELURL=$cancelURL&NOSHIPPING=1&ADDRESSOVERRIDE=1&EMAIL=$email&NAME=$ship_name&SHIPTOSTREET=$ship_street1&SHIPTOCITY=$ship_city&SHIPTOSTATE=$ship_state";
		$nvpreq .= "&SHIPTOZIP=$ship_zip&SHIPTOCOUNTRY=$ship_country";
		
		$REQADDRESS = PP_WPP_REQCONFIRMSHIPPING;
		
		if($REQADDRESS == '1')
		{
			$nvpreq .= "&REQCONFIRMSHIPPING=1";
		}
		else
		{
			$nvpreq .= "&REQCONFIRMSHIPPING=0";
		}
		
		$API_UserName=PP_WPP_USERNAME;


		$API_Password=PP_WPP_PASSWORD;


		$API_Signature=PP_WPP_SIGNATURE;
		
		
		$API_Certificate=PP_WPP_CERTIFICATE;

		
		//Check to see if we are in sandbox mode. If we are we will use the beta sandbox nvp system, since it closely resembles the live paypal. Otherwise, we will use the live nvp system.
		if (PP_WPP_SANDBOX == '1')
		{
			if (PP_WPP_USE_CERTIFICATE == '1')
			{
				$API_Endpoint = 'https://api.sandbox.paypal.com/nvp'; 
			}
			else
			{
				$API_Endpoint = 'https://api-3t.sandbox.paypal.com/nvp';
			}
		}
		else
		{
			if (PP_WPP_USE_CERTIFICATE == '1')
			{
				$API_Endpoint = 'https://api.paypal.com/nvp';
			}
			else
			{
				$API_Endpoint = 'https://api-3t.paypal.com/nvp';
			}
		}		

			
		//API Version to use
		$version=PP_WPP_VERSION;
		
		//Send nvp request to paypal and get the response array.
		$nvpRES = hash_call("SetExpressCheckout",$nvpreq,$API_UserName,$API_Password,$API_Certificate,$API_Signature,$API_Endpoint,$version);
		
		//return the response array so we can process it in request_paypal.tpl.php under checkout in templates.
		return $nvpRES;
		
	}
	else
	{
		return false;
	}
}

//Request Transaction Details
function NVP_TransactionDetails($transID)
{
	require_once( CLASSPATH . "payment/nvp_include/nvp_connection.php");
	require_once(CLASSPATH ."payment/ps_paypal_wpp.cfg.php");
	
	//very simple nvp request to get the transaction details of a previous purchase.
	$nvpreq = "&TRANSACTIONID=$transID";
	
	$API_UserName=PP_WPP_USERNAME;


	$API_Password=PP_WPP_PASSWORD;


	$API_Signature=PP_WPP_SIGNATURE;
	
	
	$API_Certificate=PP_WPP_CERTIFICATE;
	

	//Check to see if we are in sandbox mode. If we are we will use the beta sandbox nvp system, since it closely resembles the live paypal. Otherwise, we will use the live nvp system.
	if (PP_WPP_SANDBOX == '1')
	{
		if (PP_WPP_USE_CERTIFICATE == '1')
		{
			$API_Endpoint = 'https://api.sandbox.paypal.com/nvp'; 
		}
		else
		{
			$API_Endpoint = 'https://api-3t.sandbox.paypal.com/nvp';
		}
	}
	else
	{
		if (PP_WPP_USE_CERTIFICATE == '1')
		{
			$API_Endpoint = 'https://api.paypal.com/nvp';
		}
		else
		{
			$API_Endpoint = 'https://api-3t.paypal.com/nvp';
		}
	}	

		
	//API Version to use
	$version=PP_WPP_VERSION;
	
	$nvpRES = hash_call("GetTransactionDetails",$nvpreq,$API_UserName,$API_Password,$API_Certificate,$API_Signature,$API_Endpoint,$version);
	
	$ack = strtoupper($nvpRES["ACK"]);
	
	//Return our response array if the command was succesful.
	if($ack!="SUCCESS" && $ack!="SUCCESSWITHWARNING")  
	{
		return false;
	}
	else
	{
		return $nvpRES;
	}
	
}

//NVP DoExpressCheckout
function NVP_DoExpressCheckout(&$d, $dbbt, $dbst, $REMOTE_ADDR, $payment_action, $ordernum)
{
	global $vendor_mail, $vendor_currency, $VM_LANG, $database;
	require_once( CLASSPATH . "ps_checkout.php" );
	require_once( CLASSPATH . "payment/nvp_include/nvp_connection.php");
	$checkout =& new ps_checkout();	
	
	//Check to make sure that we have the token from paypal and the paypal payer ID. Otherwise we return false.
	if(isset($_SESSION['paypal_ex_token']) && isset($_SESSION['paypal_ex_payerID']))
	{
		$token = urlencode($_SESSION['paypal_ex_token']);
		$payerID = urlencode($_SESSION['paypal_ex_payerID']);
		
		//Gather all needed info to build the nvp request.
		$subject = urlencode('');
		$payer = urlencode($dbbt->f("user_email"));
		$first_name = urlencode(substr($dbbt->f("first_name"),0,50));
		$last_name = urlencode(substr($dbbt->f("last_name"), 0, 50));
		$currency_type = "USD";
		$tax_total = round($d['order_tax'],2);
		$shipping = round($d['shipping_total'],2);
		$shipping_tax = round($d['shipping_tax'],2);
	    $ship_total = round($shipping + $shipping_tax,2);
		if(isset($_SESSION['coupon_discount']))
		{
			$discount_total = round($_SESSION['coupon_discount'],2);
		}
		else
		{
			$discount_total = 0;
		}
		$item_total = 0;


	    $db_new = new ps_DB;
	    $query_str = "SELECT * FROM #__{vm}_country WHERE country_3_code='" . substr($dbbt->f("country"), 0, 60) . "'";
	    $db_new->setQuery($query_str);
	    $db_new->query();
	    $db_new->next_record();

		$address_street1 = urlencode(substr($dbbt->f("address_1"), 0, 60));
		$address_city = urlencode(substr($dbbt->f("city"), 0, 40));
		$address_state = urlencode(substr($dbbt->f("state"), 0, 40));
		$address_country = urlencode($db_new->f("country_2_code"));
		$address_zip = urlencode(substr($dbbt->f("zip"), 0, 20));

	    $query_str = "SELECT * FROM #__{vm}_country WHERE country_3_code='" . substr($dbst->f("country"), 0, 60) . "'";
	    $db_new->setQuery($query_str);
	    $db_new->query();
	    $db_new->next_record();
		
		$ship_name = urlencode(substr($dbst->f("first_name"), 0, 50).' '.substr($dbst->f("last_name"), 0, 50));
		$ship_street1 = urlencode(substr($dbst->f("address_1"), 0, 60));
		$ship_street2 = urlencode(substr($dbst->f("address_2"), 0, 60));
		$ship_city = urlencode(substr($dbst->f("city"), 0, 40));
		$ship_state = urlencode(substr($dbst->f("state"), 0, 40));
		$ship_country = urlencode($db_new->f("country_2_code"));
		$ship_zip = urlencode(substr($dbst->f("zip"), 0, 20));
		
		//build the nvp request with all the data we have gathered.
		$nvpreq = "&TOKEN=$token&PAYERID=$payerID&PAYMENTACTION=$payment_action&IPADDRESS=$REMOTE_ADDR";
		
		$nvpreq .= "&CURRENCYCODE=$currency_type&SHIPPINGAMT=$ship_total&TAXAMT=$tax_total&DESC=$subject&INVNUM=$ordernum";
		
		$auth = $_SESSION['auth'];
		$cart = $_SESSION['cart'];
		$order_subtotal = 0;
		$t_quantity = 0;
		$disc_perItem = 0;
		
		require_once(CLASSPATH.'ps_product.php');
		$ps_product= new ps_product;	
		
		for($i = 0; $i < $cart["idx"]; $i++)
		{
			$t_quantity = $t_quantity + $cart[$i]["quantity"];
		}
		
		if($t_quantity > 0)
		{
			if($discount_total > 0)
			{
				$disc_perItem = round($discount_total / $t_quantity, 2);
			}
			else
			{
				$disc_perItem = 0;
			}
		}
		else
		{
			$disc_perItem = 0;
		}
		
		//Cycle through items in cart and list each one in the NVP Request
		for($i = 0; $i < $cart["idx"]; $i++) {
			$price = $ps_product->get_adjusted_attribute_price($cart[$i]["product_id"], $cart[$i]["description"]);
			$product_price = urlencode(round(($price["product_price"] - $disc_perItem),2));
			$product_name = urlencode($ps_product->get_field($cart[$i]["product_id"], "product_name"));
			$product_number = urlencode($cart[$i]["product_id"]);
			$product_quantity = urlencode($cart[$i]["quantity"]);
			if( $auth["show_price_including_tax"] == 1 ) {
				if( empty( $_SESSION['product_info'][$cart[$i]["product_id"]]['tax_rate'] )){
					$my_taxrate = $ps_product->get_product_taxrate($cart[$i]["product_id"] );
				}else{
	                $my_taxrate = $_SESSION['product_info'][$cart[$i]["product_id"]]['tax_rate'];
					$product_tax = urlencode(round( ($product_price * $my_taxrate), 2 ));
				}
			} else {
				$product_tax = urlencode('0.00');
			}
				$currentCartTotal = round(($product_price * $product_quantity),2);
				$item_total = $item_total + $currentCartTotal;
				//put together the nvp request for each item found in the cart.
				$nvpreq .= "&L_NAME$i=$product_name&L_NUMBER$i=$product_number&L_QTY$i=$product_quantity&L_AMT$i=$product_price";
		}
		
		$order_total = round(($item_total + $ship_total + $tax_total), 2);
		//Put together Shipping NVP request
		$nvpreq .= "&AMT=$order_total&ITEMAMT=$item_total&NAME=$ship_name&SHIPTOSTREET=$ship_street1&SHIPTOSTREET2=$ship_street2&SHIPTOCITY=$ship_city&SHIPTOSTATE=$ship_state&SHIPTOZIP=$ship_zip&SHIPTOCOUNTRYCODE=$ship_country";
		
		//return the response to ps_paypal_wpp.php
		return $nvpreq;
	}
	else
	{
		return false;
	}
}

//NVP DoDirectPayment Request
function NVP_DoDirectPaymentRequest(&$d, $dbbt, $dbst, $REMOTE_ADDR, $payment_action, $ordernum, $requireCVV){
	global $vendor_mail, $vendor_currency, $VM_LANG, $database;
	require_once( CLASSPATH . "ps_checkout.php" );
	require_once( CLASSPATH . "payment/nvp_include/nvp_connection.php");
	$checkout =& new ps_checkout();
	
	if(isset($_SESSION['ccdata']['order_payment_number']))
	{
	
		$cc_first_digit = substr($_SESSION['ccdata']['order_payment_number'], 0, 1);
		$cc_first_2_digits = substr($_SESSION['ccdata']['order_payment_number'], 0, 2);

			// Figure out the card type.
			switch ($cc_first_digit) {
			 case "4" : $cc_type = urlencode("Visa");
						break;
			 case "5" : $cc_type = urlencode("MasterCard");
						break;
			 case "3" :
			 	switch ($cc_first_2_digits) {
					case "34" : $cc_type = urlencode("Amex");
								break;
					case "37" : $cc_type = urlencode("Amex");
								break;
					case "30" : $cc_type = urlencode("Discover");
								break;
					case "36" : $cc_type = urlencode("Discover");
								break;
					case "38" : $cc_type = urlencode("Discover");
								break;
					default : return false;
								break;
				}
				break;
			 case "6" : $cc_type = urlencode("Discover");
				break;
			 default : return false;
						break;
			}

		//Gather all required data	
		$cc_number = urlencode($_SESSION['ccdata']['order_payment_number']);
		if(isset($_SESSION['ccdata']['credit_card_code']))
		{
			$cc_cvv2 = urlencode($_SESSION['ccdata']['credit_card_code']);
		}
		else
		{
			if($requireCVV == 'YES')
			{
				return false;
			}
		}
		$cc_expires_month = $_SESSION['ccdata']['order_payment_expire_month'];
		$cc_expires_year = $_SESSION['ccdata']['order_payment_expire_year'];
		//$cc_owner = ($_SESSION['ccdata']['order_payment_name']);

		//$cc_first = urlencode(substr($cc_owner, 0,(strrpos($cc_owner, " "))));
		//$cc_last = urlencode(substr($cc_owner,(strrpos($cc_owner, ' ') + 1),strlen($cc_owner)));
		$cc_expDate = urlencode($cc_expires_month.$cc_expires_year);
		
		$subject = urlencode('');
		$payer = urlencode($dbbt->f("user_email"));
		$first_name = urlencode(substr($dbbt->f("first_name"),0,50));
		$last_name = urlencode(substr($dbbt->f("last_name"), 0, 50));
		$currency_type = "USD";
		$tax_total = round($d['order_tax'],2);
		$shipping = round($d['shipping_total'],2);
		$shipping_tax = round($d['shipping_tax'],2);
		$ship_total = round($shipping + $shipping_tax,2);
		if(isset($_SESSION['coupon_discount']))
		{
			$discount_total = round($_SESSION['coupon_discount'],2);
		}
		else
		{
			$discount_total = 0;
		}
		$item_total = 0;


	    $db_new = new ps_DB;
	    $query_str = "SELECT * FROM #__{vm}_country WHERE country_3_code='" . substr($dbbt->f("country"), 0, 60) . "'";
	    $db_new->setQuery($query_str);
	    $db_new->query();
	    $db_new->next_record();

		$address_street1 = urlencode(substr($dbbt->f("address_1"), 0, 60));
		$address_city = urlencode(substr($dbbt->f("city"), 0, 40));
		$address_state = urlencode(substr($dbbt->f("state"), 0, 40));
		$address_country = urlencode($db_new->f("country_2_code"));
		$address_zip = urlencode(substr($dbbt->f("zip"), 0, 20));

	    $query_str = "SELECT * FROM #__{vm}_country WHERE country_3_code='" . substr($dbst->f("country"), 0, 60) . "'";
	    $db_new->setQuery($query_str);
	    $db_new->query();
	    $db_new->next_record();

		$ship_name = urlencode(substr($dbst->f("first_name"), 0, 50).' '.substr($dbst->f("last_name"), 0, 50));
		$ship_street1 = urlencode(substr($dbst->f("address_1"), 0, 60));
		$ship_street2 = urlencode(substr($dbst->f("address_2"), 0, 60));
		$ship_city = urlencode(substr($dbst->f("city"), 0, 40));
		$ship_state = urlencode(substr($dbst->f("state"), 0, 40));
		$ship_country = urlencode($db_new->f("country_2_code"));
		$ship_zip = urlencode(substr($dbst->f("zip"), 0, 20));
		
		
		//Begin putting together our NVP Request
		$nvpreq = "&PAYMENTACTION=$payment_action&IPADDRESS=$REMOTE_ADDR&CREDITCARDTYPE=$cc_type&ACCT=$cc_number&EXPDATE=$cc_expDate&EMAIL=$payer&FIRSTNAME=$first_name&LASTNAME=$last_name";
		
		if($requireCVV == 'YES')
		{
			if(isset($cc_cvv2))
			{
				$nvpreq .= "&CVV2=$cc_cvv2";
			}
			else
			{
				return false;
			}
		}
		
		$nvpreq .= "&STREET=$address_street1&CITY=$address_city&STATE=$address_state&COUNTRYCODE=$address_country&ZIP=$address_zip";
		
		$nvpreq .= "&CURRENCYCODE=$currency_type&SHIPPINGAMT=$ship_total&TAXAMT=$tax_total&DESC=$subject&INVNUM=$ordernum";
		
		$auth = $_SESSION['auth'];
		$cart = $_SESSION['cart'];
		$order_subtotal = 0;
		$t_quantity = 0;
		$disc_perItem = 0;
		
		
		require_once(CLASSPATH.'ps_product.php');
		$ps_product= new ps_product;	
		
		for($i = 0; $i < $cart["idx"]; $i++)
		{
			$t_quantity = $t_quantity + $cart[$i]["quantity"];
		}
		
		if($t_quantity > 0)
		{
			if($discount_total > 0)
			{
				$disc_perItem = round($discount_total / $t_quantity, 2);
			}
			else
			{
				$disc_perItem = 0;
			}
		}
		else
		{
			$disc_perItem = 0;
		}
		
		//Cycle through items in cart and list each one in the NVP Request
		for($i = 0; $i < $cart["idx"]; $i++) {
			$price = $ps_product->get_adjusted_attribute_price($cart[$i]["product_id"], $cart[$i]["description"]);
			$product_price = urlencode(round(($price["product_price"] - $disc_perItem),2));
			$product_name = urlencode($ps_product->get_field($cart[$i]["product_id"], "product_name"));
			$product_number = urlencode($cart[$i]["product_id"]);
			$product_quantity = urlencode($cart[$i]["quantity"]);
			if( $auth["show_price_including_tax"] == 1 ) {
				if( empty( $_SESSION['product_info'][$cart[$i]["product_id"]]['tax_rate'] )){
					$my_taxrate = $ps_product->get_product_taxrate($cart[$i]["product_id"] );
				}else{
	                $my_taxrate = $_SESSION['product_info'][$cart[$i]["product_id"]]['tax_rate'];
					$product_tax = urlencode(round( ($product_price * $my_taxrate), 2 ));
				}
			} else {
				$product_tax = urlencode('0.00');
			}
			
				$currentCartTotal = round(($product_price * $product_quantity),2);
				$item_total = $item_total + $currentCartTotal;
			
				$nvpreq .= "&L_NAME$i=$product_name&L_NUMBER$i=$product_number&L_QTY$i=$product_quantity&L_AMT$i=$product_price";
		}
		
		$order_total = round(($item_total + $ship_total + $tax_total), 2);

		//Put together Shipping NVP request
		$nvpreq .= "&AMT=$order_total&ITEMAMT=$item_total&SHIPTONAME=$ship_name&SHIPTOSTREET=$ship_street1&SHIPTOSTREET2=$ship_street2&SHIPTOCITY=$ship_city&SHIPTOSTATE=$ship_state&SHIPTOZIP=$ship_zip&SHIPTOCOUNTRYCODE=$ship_country";
		
		//return response to ps_paypal_wpp.php
		return $nvpreq;
	}
	else
	{
		return false;
	}
}

function NVP_ErrorToText($errorCode, $type)
{
	$errorText = '';
	if($errorCode != "" && $type != "")
	{
		switch($type)
		{
			case 'setexpress':
				
				switch($errorCode)
				{
					case '10411':
						$errorText = 'Express Checkout Session has expired. Please, restart the checkout process.';
						return $errorText;
						break;
					case '10415':
						$errorText = 'The Express Checkout Session has already been used. Please, restart the checkout process.';
						return $errorText;
						break;
					default:
						return false;
						break;
				}
				break;
			case 'getexpress':
				switch($errorCode)
				{
					case '10411':
						$errorText = 'Express Checkout Session has expired. Please, restart the checkout process.';
						return $errorText;
						break;
					case '10415':
						$errorText = 'The Express Checkout Session has already been used. Please, restart the checkout process.';
						return $errorText;
						break;
					case '10416':
						$errorText = 'Exceeded maximum number of processing attempts for Session. Please, restart the checkout process.';
						return $errorText;
						break;
					default:
						return false;
						break;					
				}
				break;
			case 'doexpress':
				switch($errorCode)
				{
					case '10411':
						$errorText = 'Express Checkout Session has expired. Please, restart the checkout process.';
						return $errorText;
						break;
					case '10415':
						$errorText = 'The Express Checkout Session has already been used. Please, restart the checkout process.';
						return $errorText;
						break;
					case '10416':
						$errorText = 'Exceeded maximum number of processing attempts for the Session. Please, restart the checkout process.';
						return $errorText;
						break;
					case '10417':
						$errorText = 'PayPal cannot process the payment. Please, restart the checkout process and select a different method of payment.';
						return $errorText;
						break;
					case '10422':
						$errorText = 'Invalid funding through PayPal. Please, restart the checkout process and select a different PayPal funding method.';
						return $errorText;
						break;
					case '10445':
						$errorText = 'Cannot process the payment. Please, try again later.';
						return $errorText;
						break;
					default:
						return false;
						break;					
				}
				break;
			case 'dodirect':
				switch($errorCode)
				{
					case '10502':
						$errorText = 'Payment could not be processed due to an Invalid Credit Card';
						return $errorText;
						break;
					case '10504':
						$errorText = 'Payment could not be processed due to an Invalid Credit Card Verification Code';
						return $errorText;
						break;
					case '10508':
						$errorText = 'Payment could not be processed due to an Invalid Expiration Date';
						return $errorText;
						break;
					case '10510':
						$errorText = 'Payment could not be processed due to Credit Card Type Not Supported.';
						return $errorText;
						break;
					case '10519':
						$errorText = 'Payment could not be processed due to an Invalid Credit Card';
						return $errorText;
						break;
					case '10521':
						$errorText = 'Payment could not be processed due to an Invalid Credit Card';
						return $errorText;
						break;
					case '10527':
						$errorText = 'Payment could not be processed due to an Invalid Credit Card';
						return $errorText;
						break;
					case '10534':
						$errorText = 'Payment could not be processed. Credit Card has been restricted. Please contact PayPal.';
						return $errorText;
						break;
					case '10535':
						$errorText = 'Payment could not be processed due to an Invalid Credit Card';
						return $errorText;
						break;
					case '10541':
						$errorText = 'Payment could not be processed. Credit Card has been restricted. Please contact PayPal.';
						return $errorText;
						break;
					case '10562':
						$errorText = 'Payment could not be processed due to an Invalid Credit Card Expiration Date';
						return $errorText;
						break;
					case '10563':
						$errorText = 'Payment could not be processed due to an Invalid Credit Card Expiration Date';
						return $errorText;
						break;
					case '10566':
						$errorText = 'Payment could not be processed due to Credit Card Type Not Supported.';
						return $errorText;
						break;
					case '10567':
						$errorText = 'Payment could not be processed due to an Invalid Credit Card';
						return $errorText;
						break;
					case '10748':
						$errorText = 'Payment could not be processed due to an Invalid Credit Card Verifcation Code';
						return $errorText;
						break;
					case '10756':
						$errorText = 'Payment could not be processed. Billing Address does not Match Credit Card Billing Address.';
						return $errorText;
						break;
					case '10759':
						$errorText = 'Payment could not be processed due to an Invalid Credit Card.';
						return $errorText;
						break;
					case '15001':
						$errorText = 'Payment could not be processed due to Excessive Failuers with Invalid Credit Card.';
						return $errorText;
						break;
					case '15004':
						$errorText = 'Payment could not be processed. Credit Card Verification Code Does Not Match Credit Card';
						return $errorText;
						break;
					case '15006':
						$errorText = 'Payment could not be processed. Credit Card was Declined by Issuing Bank';
						return $errorText;
						break;
					case '15005':
						$errorText = 'Payment could not be processed. Credit Card was Declined by Issuing Bank';
						return $errorText;
						break;
					case '15007':
						$errorText = 'Payment could not be processed. Credit Card was Declined by Issuing Bank';
						return $errorText;
						break;
					default:
						return false;
						break;
				}
				break;
			default:
				return false;
				break;
		}
	}
	else
	{
		return false;
	}
}
?>