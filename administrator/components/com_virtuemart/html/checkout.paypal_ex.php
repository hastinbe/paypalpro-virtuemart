<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
/**
*
* @version $Id: checkout.index.php 1374 2008-04-19 15:02:04Z gregdev $
* @package VirtueMart
* @subpackage html
* @copyright Copyright (C) 2004-2008 soeren - All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
*
* http://virtuemart.net
*/
mm_showMyFileName( __FILE__ );

require_once( CLASSPATH . "ps_checkout.php" );
//We set this to let other parts know that we are indeed checking out via paypal express.
$_SESSION['paypal_wpp_ex'] = TRUE;
echo '<div style="float: left; position: relative; width: 95%; margin-left: 20px; margin-top: 20px; margin-bottom: 20px;">';

$ship_to_info_id = vmGet( $_REQUEST, 'ship_to_info_id');
$shipping_rate_id = urldecode(vmGet( $_REQUEST, "shipping_rate_id", null ));
$payment_method_id = vmGet( $_REQUEST, 'payment_method_id');
$Itemid = $sess->getShopItemid();

/* Decide, which Checkout Step is the next one 
* $checkout_this_step controls the step thru the checkout process
* we have the following steps

* -CHECK_OUT_GET_SHIPPING_ADDR
* let the user choose a shipto address

* -CHECK_OUT_GET_SHIPPING_METHOD
* let the user choose a shipto metho for the ship to address

* -CHECK_OUT_GET_PAYMENT_METHOD
* let the user choose a payment method

* -CHECK_OUT_GET_FINAL_CONFIRMATION
* shows a total summary including all payments, taxes, fees etc. and let the user confirm
*/
if( $auth['user_id'] > 0 ) {
	$show_basket = true;
} else {
	$show_basket = false;
}
$current_stage = ps_checkout::get_current_stage();

$checkout_steps = ps_checkout::get_checkout_steps();

if( in_array('CHECK_OUT_GET_FINAL_CONFIRMATION', $checkout_steps[$current_stage]) ) {
    $next_page = 'checkout.thankyou';
    if( sizeof($checkout_steps[$current_stage]) > 1 ) {
    	include_once( PAGEPATH . 'basket.php' );
    } else {
    	include_once( PAGEPATH . 'ro_basket.php' );
    }
} else {
	$next_page = 'checkout.paypal_ex';	
	include_once( PAGEPATH . 'basket.php' );
}

// Get the zone quantity after it has been calculated in the basket 
$zone_qty = vmGet( $_REQUEST, 'zone_qty');

$theme = new $GLOBALS['VM_THEMECLASS']();

$theme->set_vars( // Import these values into the template files
	array( 'zone_qty' => $zone_qty,
			'ship_to_info_id' => $ship_to_info_id,
			'shipping_rate_id' => $shipping_rate_id,
			'payment_method_id' => $payment_method_id,
			'weight_total' => $weight_total,
			'Itemid' => $Itemid
			)
	);
	
if ($cart["idx"] > 0) {
	
	echo '<h3>'. $VM_LANG->_('PHPSHOP_CHECKOUT_TITLE') .'</h3>';
	
    if (!defined('_MIN_POV_REACHED')) {
    	echo $basket_html;
    	?>
        <div align="center">
            <script type="text/javascript">alert('<?php echo $VM_LANG->_('PHPSHOP_CHECKOUT_ERR_MIN_POV',false) ?>');</script>
            <strong><?php echo $VM_LANG->_('PHPSHOP_CHECKOUT_ERR_MIN_POV') ?></strong><br />
            <strong><?php echo $VM_LANG->_('PHPSHOP_CHECKOUT_ERR_MIN_POV2') . " ".$CURRENCY_DISPLAY->getFullValue($_SESSION['minimum_pov']) ?></strong>
        </div><?php
        return;
    }
    
    // We have something in the Card so move on
    if ($perm->is_registered_customer($auth['user_id'])) { // user is logged in and a registered customer
		$basket_html .= '<form action="'. SECUREURL.basename($_SERVER['PHP_SELF']) .'" method="post" name="adminForm">
		
	<input type="hidden" name="option" value="com_virtuemart" />
	<input type="hidden" name="Itemid" value="'. $Itemid .'" />
	<input type="hidden" name="user_id" value="'. $auth['user_id'] .'" />
	<input type="hidden" name="page" value="'. $next_page .'" />
	<input type="hidden" name="func" value="checkoutProcess" />
		
	<input type="hidden" name="zone_qty" value="'. $zone_qty .'" />
        <input type="hidden" name="ship_to_info_id" value="'. $ship_to_info_id .'" />
        <input type="hidden" name="shipping_rate_id" value="'. urlencode($shipping_rate_id) .'" />
        <input type="hidden" name="payment_method_id" value="'. $payment_method_id .'" />
        <input type="hidden" name="checkout_last_step" value="'. $current_stage .'" />';
		
		$theme->set( 'basket_html', $basket_html );
		
	    // Set Dynamic Page Title: "Checkout: Step x of x"
	    $mainframe->setPageTitle( sprintf( $VM_LANG->_('VM_CHECKOUT_TITLE_TAG'), $current_stage, count($checkout_steps) ));
	    
	    // CHECK_OUT_GET_SHIPPING_ADDR
	    // Lets the user pick or add an alternative Shipping Address
	    if( in_array('CHECK_OUT_GET_SHIPPING_ADDR', $checkout_steps[$current_stage]) ) {
			//set this to false so we continue like normal
			$_SESSION['paypal_ex_request'] = FALSE;
			
			echo '<a name="CHECK_OUT_GET_SHIPPING_ADDR"></a>';
			echo $theme->fetch( 'checkout/get_shipping_address_ex.tpl.php');
			$theme->set('basket_html', '');
        }
        // CHECK_OUT_GET_SHIPPING_METHOD
        // Let the user pick a shipping method
        if( in_array('CHECK_OUT_GET_SHIPPING_METHOD', $checkout_steps[$current_stage]) ) {
			//set this to false so we continue like normal
			$_SESSION['paypal_ex_request'] = FALSE;
			
        	echo '<a name="CHECK_OUT_GET_SHIPPING_METHOD"></a>';
        	echo $theme->fetch( 'checkout/get_shipping_method.tpl.php');
			$theme->set('basket_html', '');
        }
        
        // -CHECK_OUT_GET_PAYMENT_METHOD
        // let the user choose a payment method
        if( in_array('CHECK_OUT_GET_PAYMENT_METHOD', $checkout_steps[$current_stage]) ) {   
        	echo '<a name="CHECK_OUT_GET_PAYMENT_METHOD"></a>';
        	echo $theme->fetch( 'checkout/request_paypal.tpl.php');
			$theme->set('basket_html', '');
        } 
        // -CHECK_OUT_GET_FINAL_CONFIRMATION
        // shows a total summary including all payments, taxes, fees etc. 
        if( in_array('CHECK_OUT_GET_FINAL_CONFIRMATION', $checkout_steps[$current_stage]) ) {
			//set this to false so we continue like normal
			$_SESSION['paypal_ex_request'] = FALSE;
			
        	echo '<a name="CHECK_OUT_GET_FINAL_CONFIRMATION"></a>';
			// Now let the user confirm
			echo $theme->fetch( 'checkout/get_paypal_confirmation.tpl.php');
			$theme->set('basket_html', '');
        }
        ?>
    <br /><?php 
		foreach( $checkout_steps[$current_stage] as $this_step ) {	
			echo '<input type="hidden" name="checkout_this_step[]" value="'.$this_step.'" />';
		}
		
			//Check to make sure that if we are not on the payment select screen.
			//If we are then we do not include the next button until we can verify the payment via paypal
			if($_SESSION['paypal_ex_request'] == FALSE)
			{
		        if( !in_array('CHECK_OUT_GET_FINAL_CONFIRMATION', $checkout_steps[$current_stage]) ) {
		         	?>
		                <div align="center">
		                <input type="submit" class="button" name="formSubmit" value="<?php echo $VM_LANG->_('PHPSHOP_CHECKOUT_NEXT');?> &gt;&gt;" />
		                </div>
		            <?php 
				}
				// Close the Checkout Form, which was opened in the first checkout template using the variable $basket_html
				echo '</form>';

		         if( !in_array('CHECK_OUT_GET_FINAL_CONFIRMATION', $checkout_steps[$current_stage]) ) {
		                echo "<script type=\"text/javascript\"><!--
		                    function submit_order( form ) { return true; }
		                    --></script>";
		            }
	        }
		}
        else {
			
          if (!empty($auth['user_id'])) {
            // USER IS LOGGED IN, BUT NO REGISTERED CUSTOMER
            // WE NEED SOME ADDITIONAL INFORMATION HERE,
            // SO REDIRECT HIM TO shop/shopper_add
      		$vmLogger->info( $VM_LANG->_('PHPSHOP_NO_CUSTOMER',false) );
      
            include(PAGEPATH. 'checkout_register_form.php');
          }
      
          else { 
          	// user is not logged in
			echo $theme->fetch( 'checkout/login_registration.tpl.php' );
          }
    }
}
else {
	vmRedirect( $sess->url( 'index.php?page=shop.cart', false, false ) );
}
echo '</div>';
?>