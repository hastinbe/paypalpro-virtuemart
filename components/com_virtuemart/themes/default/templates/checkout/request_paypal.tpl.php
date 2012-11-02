<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
/*
* version 1.0 of Paypal Pro Module.
* Author: Aaron Klick (Metric/Metricton)
* @Copyright (C) 2008 Aaron Klick. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/
//declare global variables that are used in the basket.
global $order_total, $shipping_tax, $shipping_total, $tax_total;

ps_checkout::show_checkout_bar();
require_once( CLASSPATH . "payment/nvp_include/nvp_functions.php");
require_once(CLASSPATH ."payment/ps_paypal_wpp.cfg.php");
//Setting this stops VM from showing the continue button until we know the user has payed.
$_SESSION['paypal_ex_request'] = TRUE;
echo $basket_html;

echo '<br />';

$varname = 'PHPSHOP_CHECKOUT_MSG_' . CHECK_OUT_GET_PAYMENT_METHOD;
echo '<h4>'. $VM_LANG->_($varname) . '</h4>';

//Get required variables
if(isset($_REQUEST['token']))
{
	$token = $_REQUEST['token'];
}
$ps_vendor_id = $_SESSION["ps_vendor_id"];
$auth = $_SESSION['auth'];
$ship_to_info_id = strip_tags($_REQUEST['ship_to_info_id']);
$ship_rate = strip_tags($_REQUEST['shipping_rate_id']);
$itemID = strip_tags($_REQUEST['Itemid']);
$payment_method_id = strip_tags($_REQUEST['payment_method_id']);

//If we do not have a token we start a new SetExpressCheckout
if(!isset($token))
{
	//Check to make sure we have an order total greater than 0.00
	if($order_total > 0.00)
	{	
		$info = array('ship_to' => $ship_to_info_id, 'ship_rate' => $ship_rate, 'item_id' => $itemID);
		$dt['order_total'] = round($order_total,2);
		$dt['shipping_total'] = round($shipping_tax,2) + round($shipping_total,2);
		$dt['tax_total'] = round($tax_total,2);

		// Get user billing information from the database
		$dbbt = new ps_DB;
		$qt = "SELECT * FROM #__{vm}_user_info WHERE user_id=".$auth["user_id"]." AND address_type='BT'";
		$dbbt->query($qt);
		$dbbt->next_record();
		$user_info_id = $dbbt->f("user_info_id");
		if( $user_info_id != $ship_to_info_id) {
		// There is a different shipping address than the billing address, get the shipping information
			$dbst = new ps_DB;
			$qt = "SELECT * FROM #__{vm}_user_info WHERE user_info_id='".$ship_to_info_id."' AND address_type='ST'";
			$dbst->query($qt);
			$dbst->next_record();
		}
		else {
			// Shipping address is the same as the billing address
			$dbst = $dbbt;
		}
		
		$dt['email'] = $dbbt->f('user_email');
		
		//Pass our collected info to SetExpressCheckout
		$nvpRES = NVP_SetExpressCheckout($dt,$dbst,$info);
		
		//Check to make sure we actually retrieved a response from paypal.
		if($nvpRES)
		{
			$ack = strtoupper($nvpRES["ACK"]);
			
			if($ack == "SUCCESS")
			{
				//Success! Build paypal url with token.
				$token = urldecode($nvpRES["TOKEN"]);
				//check to see if we are in sandbox mode or not, and apply correct URL accordingly
				if(PP_WPP_SANDBOX == '1')
				{
					$payPalURL = PP_WPP_EX_SANDBOX_URL.$token;
				}				
				elseif(PP_WPP_SANDBOX == '0')
				{
					$payPalURL = PP_WPP_EX_LIVE_URL.$token;
				}
				else
				{
					//default to sandbox incase we get something besides 0 or 1.
					$payPalURL = PP_WPP_EX_SANDBOX_URL.$token;
				}
				
				echo '<a href="'.$payPalURL.'"><img src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif" alt="Checkout with Paypal Express" border="0" /></a>';
			}
			else
			{
				$count=0;
				$errorMESSAGE = "";
				while (isset($nvpRES["L_SHORTMESSAGE".$count])) 
				{		
					$errorCODE  = $nvpRES["L_ERRORCODE".$count];
					$shortMESSAGE = $nvpRES["L_SHORTMESSAGE".$count];
					$longMESSAGE  = $nvpRES["L_LONGMESSAGE".$count]; 
					
					$errorMESSAGE .= "Error: ".$errorCODE." Short Message: ".$shortMESSAGE." Long Message: ".$longMESSAGE."<br />";
					$count++;
				}
				
				if(isset($errorCODE))
				{
					$errorText = NVP_ErrorToText($errorCODE, 'setexpress');
					
					if($errorText)
					{
						echo $errorText;
					}
					else
					{
						if(PP_WPP_ERRORS == '0')
						{
							echo 'We could not access PayPal for the one of the following reasons:<br />';
							echo '1. Merchant account settings are not setup properly.<br />';
							echo '2. Administrator has not configured the backend properly.<br /><br />';
							
							echo 'Please, contact the website administrator with the information above.';
						}
						elseif(PP_WPP_ERRORS == '1')
						{
							echo $errorMESSAGE;
						}
					}
				}
			}
		}
		else
		{
			echo "We could not connect to PayPal. Please go back a step and try again. If the problem persist, please contact the website administrator.";
		}
	}
	else
	{
		echo "There is no need to pay as your total is: $0.00 - Please make sure you have something in the cart and then try again";
	}
}
//We have a token so lets try and get the payer info we need.
else
{
	$token = urlencode($_REQUEST['token']);
	
	//Try and get our information from paypal
	$nvpRES = NVP_GetExpressCheckout($token);
	
	if($nvpRES)
	{
		$ack = strtoupper($nvpRES['ACK']);
		
		if($ack == "SUCCESS")
		{
			//Success! Establish session variables and make sure VM knows we have selected a Payment Method.
			$_SESSION['paypal_ex_token'] = urlencode($nvpRES['TOKEN']);
			$_SESSION['paypal_ex_payerID'] = urlencode($nvpRES['PAYERID']);
			
			//Set to false so we get the next button like usual
			$_SESSION['paypal_ex_request'] = FALSE;
			
			$_SESSION['paypal_ex_Hash'] = $nvpRES;
			
			//Must be true to let VM know we have a payment selected.
			$GLOBALS['payment_selected'] = true;
			echo "Click the next button to go to the confirm your order page.<br />";
		}
		else
		{
			$count=0;
			$errorMESSAGE = "";
			while (isset($nvpRES["L_SHORTMESSAGE".$count])) 
			{		
				$errorCODE  = $nvpRES["L_ERRORCODE".$count];
				$shortMESSAGE = $nvpRES["L_SHORTMESSAGE".$count];
				$longMESSAGE  = $nvpRES["L_LONGMESSAGE".$count]; 
				
				$errorMESSAGE .= "Error: ".$errorCODE." Short Message: ".$shortMESSAGE." Long Message: ".$longMESSAGE."<br />";
				$count++;
			}
			
			if(isset($errorCODE))
			{
				$errorText = NVP_ErrorToText($errorCODE, 'getexpress');
				
				if($errorText)
				{
					echo $errorText;
				}
				else
				{
					if(PP_WPP_ERRORS == '0')
					{
						echo "Unable to verify PayPal data. Contact website administrator with the following: Unable to get PayPal purchase info.";
					}
					elseif(PP_WPP_ERRORS == '1')
					{
						echo $errorMESSAGE;
					}
				}
			}
		}
	}
	else
	{
		echo "We could not connect to PayPal. Please go back a step and try again. If the problem persist, please contact the website administrator.";
	}
}


?>