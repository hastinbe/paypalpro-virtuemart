<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );

class ps_paypal_wpp {
	
    var $classname = "ps_paypal_wpp";
    var $payment_code = "PP_WPP";

	/**
    * Show all configuration parameters for this payment method
    * @returns boolean False when the Payment method has no configration
    */
	function show_configuration() {

		global $VM_LANG, $sess;
		$db =& new ps_DB;
		$payment_method_id = vmGet( $_REQUEST, 'payment_method_id', null );
		/** Read current Configuration ***/
		require_once(CLASSPATH ."payment/".$this->classname.".cfg.php");
?>
	<table>
		<tr>
			<td><strong><?php echo PP_WPP_TEXT_ENABLE_SANDBOX ?></strong></td>
			<td><select name="PP_WPP_SANDBOX" class="inputbox" >
                	<option <?php if (PP_WPP_SANDBOX == '1') {echo "selected=\"selected\"";} ?> value="1"><?php echo PP_WPP_TEXT_YES ?></option>
                	<option <?php if (PP_WPP_SANDBOX != '1') {echo "selected=\"selected\"";} ?> value="0"><?php echo PP_WPP_TEXT_NO ?></option>
                </select>
            </td>
            <td><?php echo PP_WPP_TEXT_ENABLE_SANDBOX_EXPLAIN ?>
            </td>
        </tr>
		<tr><td><strong><?php echo PP_WPP_TEXT_USE_CERTIFICATE ?></strong></td>
            <td><select name="PP_WPP_USE_CERTIFICATE" class="inputbox" >
                	<option <?php if (PP_WPP_USE_CERTIFICATE == '1') {echo "selected=\"selected\"";} ?> value="1"><?php echo PP_WPP_TEXT_YES ?></option>
                	<option <?php if (PP_WPP_USE_CERTIFICATE != '1') {echo "selected=\"selected\"";} ?> value="0"><?php echo PP_WPP_TEXT_NO ?></option>
                </select>
                </select>
            </td>
            <td><?php echo PP_WPP_TEXT_USE_CERTIFICATE_EXPLAIN ?></td>
        </tr>
		<tr><td><strong><?php echo PP_WPP_ERRORS_TEXT ?></strong></td>
            <td><select name="PP_WPP_ERRORS" class="inputbox" >
                	<option <?php if (PP_WPP_ERRORS == '1') echo "selected=\"selected\""; ?> value="1">
					<?php echo PP_WPP_TEXT_YES ?></option>
                	<option <?php if (PP_WPP_ERRORS != '1') echo "selected=\"selected\""; ?> value="0">
					<?php echo PP_WPP_TEXT_NO ?></option>
                </select>
            </td>
            <td><?php echo PP_WPP_ERRORS_TEXT_EXPLAIN?></td>
        </tr>
		<tr><td><strong><?php echo PP_WPP_TEXT_EXPRESS_ENABLE ?></strong></td>
            <td><select name="PP_WPP_EXPRESS_ON" class="inputbox" >
                	<option <?php if (PP_WPP_EXPRESS_ON == '1') echo "selected=\"selected\""; ?> value="1">
					<?php echo PP_WPP_TEXT_YES ?></option>
                	<option <?php if (PP_WPP_EXPRESS_ON != '1') echo "selected=\"selected\""; ?> value="0">
					<?php echo PP_WPP_TEXT_NO ?></option>
                </select>
            </td>
            <td><?php echo PP_WPP_TEXT_EXPRESS_ENABLE_EXPLAIN?></td>
        </tr>
		<tr><td><strong><?php echo PP_WPP_CVV_TEXT ?></strong></td>
            <td><select name="PP_WPP_CHECK_CARD_CODE" class="inputbox" >
                	<option <?php if (PP_WPP_CHECK_CARD_CODE == 'YES') echo "selected=\"selected\""; ?> value="YES"><?php echo PP_WPP_TEXT_YES?></option>
                	<option <?php if (PP_WPP_CHECK_CARD_CODE == 'NO') echo "selected=\"selected\""; ?> value="NO">
					<?php echo PP_WPP_TEXT_NO?></option>
                </select>
            </td>
            <td><?php echo PP_WPP_CVV_TEXT_EXPLAIN ?></td>
        </tr>
		<tr><td><strong><?php echo PP_WPP_TEXT_REQCONFIRMSHIPPING ?></strong></td>
            <td><select name="PP_WPP_REQCONFIRMSHIPPING" class="inputbox" >
                	<option <?php if (PP_WPP_REQCONFIRMSHIPPING == '1') echo "selected=\"selected\""; ?> value="1">
					<?php echo PP_WPP_TEXT_YES ?></option>
                	<option <?php if (PP_WPP_REQCONFIRMSHIPPING != '1') echo "selected=\"selected\""; ?> value="0">
					<?php echo PP_WPP_TEXT_NO ?></option>
                </select>
            </td>
            <td><?php echo PP_WPP_TEXT_REQCONFIRMSHIPPING_EXPLAIN?></td>
        </tr>
		<tr>
            <td><strong><?php echo PP_WPP_TEXT_USERNAME ?></strong></td>
            <td>
                <input type="text" name="PP_WPP_USERNAME" class="inputbox" value="<?php echo PP_WPP_USERNAME ?>" size="40" />
            </td>
            <td><?php echo PP_WPP_TEXT_USERNAME_EXPLAIN ?>
            </td>
        </tr>
        <tr>
            <td><strong><?php echo PP_WPP_TEXT_PASSWORD ?></strong></td>
            <td>
                <input type="text" name="PP_WPP_PASSWORD" class="inputbox" value="<?php echo PP_WPP_PASSWORD ?>" size="40" />
            </td>
            <td><?php echo PP_WPP_TEXT_PASSWORD_EXPLAIN ?>
            </td>
        </tr>
        <tr>
            <td><strong><?php echo PP_WPP_TEXT_SIGNATURE ?></strong></td>
            <td>
                <input type="text" name="PP_WPP_SIGNATURE" class="inputbox" value="<?php echo PP_WPP_SIGNATURE ?>" size="40" />
            </td>
            <td><?php echo PP_WPP_TEXT_SIGNATURE_EXPLAIN ?>
            </td>
        </tr>
		<tr>
            <td><strong><?php echo PP_WPP_TEXT_CERTIFICATE ?></strong></td>
            <td>
                <input type="text" name="PP_WPP_CERTIFICATE" class="inputbox" value="<?php echo PP_WPP_CERTIFICATE ?>" size="40" />
            </td>
            <td><?php echo PP_WPP_TEXT_CERTIFICATE_EXPLAIN ?>
            </td>
        </tr>
		<tr>
            <td><strong><?php echo PP_WPP_TEXT_USE_PROXY ?></strong></td>
            <td>
                <select name="PP_WPP_USE_PROXY" class="inputbox">
                	<option <?php if (PP_WPP_USE_PROXY == '1') echo "selected=\"selected\""; ?> value="1">
					<?php echo PP_WPP_TEXT_YES ?></option>
					<option <?php if (PP_WPP_USE_PROXY != '1') echo "selected=\"selected\""; ?> value="0">
					<?php echo PP_WPP_TEXT_NO ?></option>
                </select>
            </td>
            <td><?php echo PP_WPP_TEXT_USE_PROXY_EXPLAIN ?></td>
        </tr>
        </tr>
		<tr><td><strong><?php echo PP_WPP_TEXT_PROXY_HOST ?></strong></td>
            <td><input type="text" name="PP_WPP_PROXY_HOST" class="inputbox" value="<?php  echo PP_WPP_PROXY_HOST ?>" /></td>
            <td><?php echo PP_WPP_TEXT_PROXY_HOST_EXPLAIN ?></td>
        </tr> 
		<tr><td><strong><?php echo PP_WPP_TEXT_PROXY_PORT ?></strong></td>
            <td><input type="text" name="PP_WPP_PROXY_PORT" class="inputbox" value="<?php  echo PP_WPP_PROXY_PORT ?>" /></td>
            <td><?php echo PP_WPP_TEXT_PROXY_PORT_EXPLAIN ?></td>
        </tr> 
        <tr><td><strong><?php echo PP_WPP_TEXT_STATUS_SUCCESS ?></strong></td>
            <td><select name="PP_WPP_SUCCESS_STATUS" class="inputbox" >
                <?php
                    $q = "SELECT order_status_name,order_status_code FROM #__{vm}_order_status ORDER BY list_order";
                    $db->query($q);
                    $order_status_code = Array();
                    $order_status_name = Array();
                    
                    while ($db->next_record()) {
                      $order_status_code[] = $db->f("order_status_code");
                      $order_status_name[] =  $db->f("order_status_name");
                    }
                    for ($i = 0; $i < sizeof($order_status_code); $i++) {
                      echo "<option value=\"" . $order_status_code[$i];
                      if (PP_WPP_SUCCESS_STATUS == $order_status_code[$i]) 
                         echo "\" selected=\"selected\">";
                      else
                         echo "\">";
                      echo $order_status_name[$i] . "</option>\n";
                    }?>
                    </select>
            </td>
            <td><?php echo PP_WPP_TEXT_STATUS_SUCCESS_EXPLAIN ?></td>
        </tr>
        <tr><td><strong><?php echo PP_WPP_TEXT_STATUS_PENDING?></strong></td>
            <td>
                <select name="PP_WPP_PENDING_STATUS" class="inputbox" >
                <?php
                    for ($i = 0; $i < sizeof($order_status_code); $i++) {
                      echo "<option value=\"" . $order_status_code[$i];
                      if (PP_WPP_PENDING_STATUS == $order_status_code[$i]) 
                         echo "\" selected=\"selected\">";
                      else
                         echo "\">";
                      echo $order_status_name[$i] . "</option>\n";
                    } ?>
                    </select>
            </td>
            <td><?php echo PP_WPP_TEXT_STATUS_PENDING_EXPLAIN?></td>
        </tr>
        <tr><td><strong><?php echo PP_WPP_TEXT_STATUS_FAILED ?></strong></td>
            <td>
                <select name="PP_WPP_FAILED_STATUS" class="inputbox" >
                <?php
                    for ($i = 0; $i < sizeof($order_status_code); $i++) {
                      echo "<option value=\"" . $order_status_code[$i];
                      if (PP_WPP_FAILED_STATUS == $order_status_code[$i]) 
                         echo "\" selected=\"selected\">";
                      else
                         echo "\">";
                      echo $order_status_name[$i] . "</option>\n";
                    } ?>
                    </select>
            </td>
            <td><?php echo PP_WPP_TEXT_STATUS_FAILED_EXPLAIN ?></td>
		</tr>
      </table>
<?php
      return true;
	}
	
	function has_configuration() {
		return true;
	}
   
// Check to see if the config file is writeable
   function configfile_writeable() {
      return is_writeable( CLASSPATH."payment/".$this->classname.".cfg.php" );
   }
   
// Check to see if the config file is readable
   function configfile_readable() {
      return is_readable( CLASSPATH."payment/".$this->classname.".cfg.php" );
   }   

	/**
	* Writes the configuration file for this payment method
	* @param array An array of objects
	* @returns boolean True when writing was successful
	*/
	function write_configuration( &$d ) {
		$my_config_array = array("PP_WPP_SANDBOX" => $d['PP_WPP_SANDBOX'],
								 "PP_WPP_USE_CERTIFICATE" => $d['PP_WPP_USE_CERTIFICATE'],
								 "PP_WPP_USERNAME" => $d['PP_WPP_USERNAME'],
								 "PP_WPP_PASSWORD" => $d['PP_WPP_PASSWORD'],
								 "PP_WPP_SIGNATURE" => $d['PP_WPP_SIGNATURE'],
								 "PP_WPP_CERTIFICATE" => $d['PP_WPP_CERTIFICATE'],
								 "PP_WPP_CHECK_CARD_CODE" => $d['PP_WPP_CHECK_CARD_CODE'],
								 "PP_WPP_SUCCESS_STATUS" => $d['PP_WPP_SUCCESS_STATUS'],
								 "PP_WPP_PENDING_STATUS" => $d['PP_WPP_PENDING_STATUS'],
								 "PP_WPP_FAILED_STATUS" => $d['PP_WPP_FAILED_STATUS'],
								 "PP_WPP_USE_PROXY" => $d['PP_WPP_USE_PROXY'],
								 "PP_WPP_PROXY_HOST" => $d['PP_WPP_PROXY_HOST'],
								 "PP_WPP_PROXY_PORT" => $d['PP_WPP_PROXY_PORT'],
								 "PP_WPP_EXPRESS_ON" => $d['PP_WPP_EXPRESS_ON'],
								 "PP_WPP_REQCONFIRMSHIPPING" => $d['PP_WPP_REQCONFIRMSHIPPING'],
								 "PP_WPP_ERRORS" => $d['PP_WPP_ERRORS']
                            );
      $config = "<?php\n";
      $config .= "if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); \n";
	  $config .= "
define('PP_WPP_TEXT_CREDIT_CARD_TYPE', 'Credit Card Type:');
define('PP_WPP_TEXT_CREDIT_CARD_FIRSTNAME', 'Owner First Name:');
define('PP_WPP_TEXT_CREDIT_CARD_LASTNAME', 'Owner Last Name:');
define('PP_WPP_TEXT_CREDIT_CARD_NUMBER', 'Card Number:');
define('PP_WPP_TEXT_CREDIT_CARD_CVV', 'CVV/CVV2 Number');
define('PP_WPP_TEXT_CREDIT_CARD_EXPIRES', 'Expiration Date:');
define ('PP_WPP_TEXT_ERROR', 'Credit Card Error:');
define ('PP_WPP_TEXT_DECLINED_MESSAGE', 'Your credit card was declined. Please try another card or contact your bank for more info.');
define ('PP_WPP_TEXT_PROCESS_ERROR', 'There was an error processing your card.');

define ('PP_WPP_TEXT_USERNAME', 'API account name:');
define ('PP_WPP_TEXT_USERNAME_EXPLAIN', 'This is your API username. (This will differ between Sandbox and Live)');
define ('PP_WPP_TEXT_PASSWORD', 'API password:');
define ('PP_WPP_TEXT_PASSWORD_EXPLAIN', 'This is your API password. (This will differ between Sandbox and Live)');
define ('PP_WPP_TEXT_SIGNATURE', 'API Signature:');
define ('PP_WPP_TEXT_SIGNATURE_EXPLAIN', 'This is the API signature generated for you. (This will differ between Sandbox and Live)');
define ('PP_WPP_TEXT_CERTIFICATE', 'API Certificate:');
define ('PP_WPP_TEXT_CERTIFICATE_EXPLAIN', 'This is the API certificate generated for you. (This will differ between Sandbox and Live)');

define ('PP_WPP_TEXT_STATUS_SUCCESS', 'Order status for successful transactions');
define ('PP_WPP_TEXT_STATUS_SUCCESS_EXPLAIN', 'Select the status you want the order set to for successful transactions.');
define ('PP_WPP_TEXT_STATUS_PENDING', 'Order status for pending transactions');
define ('PP_WPP_TEXT_STATUS_PENDING_EXPLAIN', 'Select the status you want the order set to for pending transactions.');
define ('PP_WPP_TEXT_STATUS_FAILED', 'Order status for failed transactions');
define ('PP_WPP_TEXT_STATUS_FAILED_EXPLAIN', 'Select the status you want the order set to for failed transactions.');

define ('PP_WPP_TEXT_YES', 'Yes');
define ('PP_WPP_TEXT_NO', 'No');

define ('PP_WPP_TEXT_ENABLE_SANDBOX', 'Sandbox Mode?');
define ('PP_WPP_TEXT_ENABLE_SANDBOX_EXPLAIN', 'Use sandbox account? (For development)');

define ('PP_WPP_TEXT_USE_CERTIFICATE', 'Use Certificate?');
define ('PP_WPP_TEXT_USE_CERTIFICATE_EXPLAIN', 'Use API certificate securty method?');

define ('PP_WPP_TEXT_CHECK_CVV2', 'Check CVV2 code?');
define ('PP_WPP_TEXT_CHECK_CVV2_EXPLAIN', 'Select whether the processor will require and use the CVV2 code.');

define ('PP_WPP_TEXT_EXPRESS_ENABLE', 'Enable Paypal Express Checkout?');
define ('PP_WPP_TEXT_EXPRESS_ENABLE_EXPLAIN', 'Check to use Paypal Express Checkout.');

define ('PP_WPP_TEXT_USE_PROXY','Use Proxy?');
define ('PP_WPP_TEXT_USE_PROXY_EXPLAIN','Should this request be sent through a proxy server? (Some hosting accounts, like GoDaddy, require the use of a proxy.)');
define ('PP_WPP_TEXT_PROXY_HOST','Proxy Host');
define ('PP_WPP_TEXT_PROXY_HOST_EXPLAIN','Enter the host IP of your proxy server.');
define ('PP_WPP_TEXT_PROXY_PORT','Proxy Port');
define ('PP_WPP_TEXT_PROXY_PORT_EXPLAIN','Enter the port number of your proxy server.');

define ('PP_WPP_VERSION', '52.0');

define('PP_WPP_EX_SANDBOX_URL', 'https://beta-sandbox.paypal.com/webscr&cmd=_express-checkout&token=');
define('PP_WPP_EX_LIVE_URL', 'https://www.paypal.com/webscr&cmd=_express-checkout&token=');
define ('PP_WPP_PAYMENT_ACTION', 'Sale');
define('PP_WPP_TEXT_REQCONFIRMSHIPPING', 'Require Confirmed Shipping Address?');
define('PP_WPP_TEXT_REQCONFIRMSHIPPING_EXPLAIN', 'Setting this to YES will make PayPal check for a confirmed address on file when using Express Checkout. This overrides your merchant account settings.');
define('PP_WPP_ERRORS_TEXT', 'Show PayPal Errors');
define('PP_WPP_ERRORS_TEXT_EXPLAIN', 'Shows all PayPal errors at checkout. Recommended off when your site goes Live.');

define('PP_WPP_CVV_TEXT', 'Check for CVV?');
define('PP_WPP_CVV_TEXT_EXPLAIN', 'Only disable if you have your merchant account setup to not request the CVV code.');
";
      foreach( $my_config_array as $key => $value ) {
        $config .= "define ('$key', '$value');\n";
      }
      
      $config .= "?>";
  
      if ($fp = fopen(CLASSPATH ."payment/".$this->classname.".cfg.php", "w")) {
          fputs($fp, $config, strlen($config));
          fclose ($fp);
          return true;
     }
     else
        return false;
   }
   
   function process_payment($order_number, $order_total, &$d) {
        global $vendor_mail, $vendor_currency, $VM_LANG, $vmLogger;
		$_SESSION['CURL_ERROR'] = false;
		$_SESSION['CURL_ERROR_TXT'] = "";
        $ps_vendor_id = $_SESSION["ps_vendor_id"];
        $auth = $_SESSION['auth'];

        $ps_checkout = new ps_checkout;
		require_once(CLASSPATH ."payment/".$this->classname.".cfg.php");
        require_once(CLASSPATH . "payment/nvp_include/nvp_functions.php");

        // Get user billing information from the database
        $dbbt = new ps_DB;
        $qt = "SELECT * FROM #__{vm}_user_info WHERE user_id=".$auth["user_id"]." AND address_type='BT'";
        $dbbt->query($qt);
        $dbbt->next_record();
        $user_info_id = $dbbt->f("user_info_id");
        if( $user_info_id != $d["ship_to_info_id"]) {
		// There is a different shipping address than the billing address, get the shipping information
            $dbst = new ps_DB;
            $qt = "SELECT * FROM #__{vm}_user_info WHERE user_info_id='".$d["ship_to_info_id"]."' AND address_type='ST'";
            $dbst->query($qt);
            $dbst->next_record();
        }
        else {
			// Shipping address is the same as the billing address
            $dbst = $dbbt;
        }
		
		$ip_address = urlencode($_SERVER['REMOTE_ADDR']);
		
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

			$payment_action = PP_WPP_PAYMENT_ACTION;

		$ordernum = urlencode(substr($order_number, 0, 20));
		
		$requireCVV = PP_WPP_CHECK_CARD_CODE;
		//API Version to use
		$version=PP_WPP_VERSION;
		
		//initiate our error out variables.
		$count=0;
		$errorOut = FALSE;
		$errorOut2 = FALSE;
		$displayMsg = "";
		
		//Check to see if we are coming from paypal express checkout.
		//If not we do a directpaymentrequest, otherwise we try express checkout request.
		if(!isset($_SESSION['paypal_wpp_ex']))
		{
			$nvpreq = NVP_DoDirectPaymentRequest($d, $dbbt, $dbst, $ip_address, $payment_action, $ordernum, $requireCVV);
			
			if($nvpreq)
			{
				$nvpLS = $nvpreq;
				$nvpRES = hash_call("DoDirectPayment",$nvpreq,$API_UserName,$API_Password,$API_Certificate,$API_Signature,$API_Endpoint,$version);
			}
			else
			{
				$displayMsg .= "Invalid Credit Card Number or Credit Card Verification Code.";
				$d["error"] = $displayMsg;
				$vmLogger->err($displayMsg);
				return false;
			}
		}
		else
		{
			$nvpreq = NVP_DoExpressCheckout($d, $dbbt, $dbst, $ip_address, $payment_action, $ordernum);
			
			if($nvpreq)
			{
				$nvpLS = $nvpreq;
				$nvpRES = hash_call("DoExpressCheckoutPayment",$nvpreq,$API_UserName,$API_Password,$API_Certificate,$API_Signature,$API_Endpoint,$version);
				
			}
			else
			{
				//We failed to gather the proper array, most likely do to with not having certain parameters properly filled.
				$errorOut = TRUE;
				$errorOut2 = TRUE;
				$displayMsg .= "Unable to Connect to Paypal";
			}
		}
		// Parse out all the data\
		
		if(isset($nvpRES))
		{
			$ack = strtoupper($nvpRES["ACK"]);
			
			//check to see if it was succesful or not. If not error out, otherwise retrieve the transaction status from paypal.
			if($ack!="SUCCESS" && $ack!="SUCCESSWITHWARNING")  
			{
				$displayMsg .= "Error - Paypal did not complete the transaction - ".$ack." - ";
				$errorOut2 = TRUE;
			}
			else
			{		
				if(isset($nvpRES['AVSCODE'])) {$avsCode = $nvpRES['AVSCODE'];}
				if(isset($nvpRES['CVV2MATCH'])) {$cvv2Code = $nvpRES['CVV2MATCH'];}
				$transactionID = $nvpRES['TRANSACTIONID'];
				//get the transaction details array that paypal returned.
				$nvpDETAILS = NVP_TransactionDetails($transactionID);
				if($nvpDETAILS)
				{
					if(isset($nvpDETAILS['PAYMENTSTATUS']))
					{
						$status = $nvpDETAILS['PAYMENTSTATUS'];
						
						if($status == "Completed")
						{
							$_SESSION['ps_paypal_wpp_paystatus'] = PP_WPP_SUCCESS_STATUS;
						}
						elseif($status == "Pending")
						{
							$_SESSION['ps_paypal_wpp_paystatus'] = PP_WPP_PENDING_STATUS;
						}
						elseif($status == "Processed")
						{
							$_SESSION['ps_paypal_wpp_paystatus'] = PP_WPP_SUCCESS_STATUS;
						}
						elseif($status == "Failed")
						{
							$_SESSION['ps_paypal_wpp_paystatus'] = PP_WPP_FAILED_STATUS;
						}
						else
						{
							$_SESSION['ps_paypal_wpp_paystatus'] = PP_WPP_PENDING_STATUS;
						}
					}
					else
					{
						$_SESSION['ps_paypal_wpp_paystatus'] = PP_WPP_PENDING_STATUS;
					}
				}
				else
				{
					$_SESSION['ps_paypal_wpp_paystatus'] = PP_WPP_PENDING_STATUS;
				}
			}
			
			//if paypal sent back an error check for it and add it to our error buffer.
			while (isset($nvpRES["L_SHORTMESSAGE".$count])) 
			{		
				  $errorCODE    = $nvpRES["L_ERRORCODE".$count];
				  $shortMESSAGE = $nvpRES["L_SHORTMESSAGE".$count];
				  $longMESSAGE  = $nvpRES["L_LONGMESSAGE".$count]; 
				  
				if (isset($shortMESSAGE))
				{
					$displayMsg .= 'SHORTMESSAGE ='.$shortMESSAGE.' - ';
					$errorOut = TRUE;
				}
				if (isset($errorCODE))
				{
					$displayMsg .= 'ERRORCODE ='.$errorCODE.' - ';
					$errorOut = TRUE;
				}
				if (isset($longMESSAGE))
				{
					$displayMsg .= 'LONGMESSAGE ='.$longMESSAGE.' - ';
					$errorOut = TRUE;
				}
				
				if(isset($errorCODE))
				{
					if(!isset($_SESSION['paypal_wpp_ex']))
					{
						$errorText = NVP_ErrorToText($errorCODE, 'dodirect');
						
						if($errorText)
						{
							$d["error"] = $errorText;
							$vmLogger->err($errorText);
							return false;
						}
					}
					else
					{
						$errorText = NVP_ErrorToText($errorCODE, 'doexpress');
						
						if($errorText)
						{
							$d["error"] = $errorText;
							$vmLogger->err($errorText);
							return false;
						}
					}
				}
				
				$count++;
			}
			
			//Check the AVS code for faulty address issues.
			if(isset($avsCode))
			{
				if (($avsCode == "P") || ($avsCode == "W") || ($avsCode == "X") || ($avsCode == "Y") || ($avsCode == "Z"))
				{
					$displayMsg .= "Your order has been processed.";
				}
				else
				{
					$displayMsg .= "There was a problem with your address.";
					$errorOut = TRUE;
				}
			}
			
			//Check the CVV code to make sure paypal could properly use it. If not we error out.
			if($requireCVV == '1')
			{
				if (isset($cvv2Code))
				{
					if(strtoupper($cvv2Code) == "N")
					{
						$displayMsg .= "The CVV Number was invalid.";
						$errorOut = TRUE;
					}
				}
			}
		}
		
		//Check to see if we display errors or not. 
		//If not set to 1 we only display errors in the debug file and not on screen
		if(PP_WPP_ERRORS == '1')
		{
			//If we have an error we add it to the log. We return false since we had an error.
			if ($errorOut || $errorOut2) {
		        $d["error"] = $displayMsg;
		        $d["order_payment_log"] = $displayMsg;
		        // Catch Transaction ID
				if(isset($transactionID))
				{
					$d["order_payment_trans_id"] = $transactionID;
		        }
				$html = "<br/><span class=\"message\">".$VM_LANG->_('PHPSHOP_PAYMENT_INTERNAL_ERROR')." Paypal Pro Direct Payment Error - " . $displayMsg . "</span>";
					if ($_SESSION['CURL_ERROR'] == true) { 
				        $d["error"] .= "-CURL ERROR: " . $_SESSION['CURL_ERROR_TXT'];
				        $d["order_payment_log"] .= "-CURL ERROR: " . $_SESSION['CURL_ERROR_TXT'];
				        $html .= "<br/><span class=\"message\">-CURL ERROR: " . $_SESSION['CURL_ERROR_TXT']."</span>";
					}
					
					$displayMsg .= $nvpLS;
		        $vmLogger->err( $displayMsg );
		        return true;
			}
			if ($_SESSION['CURL_ERROR'] == true) { 
				echo "<br />" . $displayMsg . "PAYPAL ERROR: " . $_SESSION['CURL_ERROR_TXT'] . "<br /><br />" . $response; $d["error"] = "PAYPAL ERROR: " . $_SESSION['CURL_ERROR_TXT'];
			}
		}
		else
		{
			if ($errorOut || $errorOut2) {
				if ($_SESSION['CURL_ERROR'] == true) {
					$displayMsg .= $_SESSION['CURL_ERROR_TXT'];
				}
				$vmLogger->debug($displayMsg);
			}
		}
	
		//if we are down this far that means the order has completed succesfully.
		$d["order_payment_log"] = "Success: " . $order_number;
		// Catch Transaction ID
		$d["order_payment_trans_id"] = $transactionID;
		$vmLogger->debug( $d['order_payment_log']);

		return True;
	} 

}

?>