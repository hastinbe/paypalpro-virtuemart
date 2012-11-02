<?php
ini_set('error_reporting', E_ALL);

if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
*
* @version $Id: shop.cart.tpl.php 1345 2008-04-03 20:26:21Z soeren_nb $
* @package VirtueMart
* @subpackage themes
* @copyright Copyright (C) 2004-2008 soeren - All rights reserved. Edited to function with Paypal Express Checkout method.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
*
* http://virtuemart.net
*/

//Retrieve our files for certain functions and variables.
require_once(CLASSPATH."payment/nvp_include/nvp_functions.php");
require_once(CLASSPATH ."payment/ps_paypal_wpp.cfg.php");
mm_showMyFileName( __FILE__ );
echo '<div style="float: left; position: relative; width: 95%; margin-left: 20px; margin-top: 20px; margin-bottom: 20px;">';
echo '<h2>'. $VM_LANG->_('PHPSHOP_CART_TITLE') .'</h2>
<!-- Cart Begins here -->
';
include(PAGEPATH. 'basket.php');

echo $basket_html;
echo '<!-- End Cart --><br />';
//check to see if the express checkout variable is set, is so we unset it.
if(isset($_SESSION['paypal_wpp_ex'])) {unset($_SESSION['paypal_wpp_ex']);}

if ($cart["idx"]) {
    ?>
    <div style="float: left position: relative; width: 100%;">
    <?php
    if( $continue_link != '') {
		?>
		<div style="float: left; position: relative; height: 25px; margin-left: 40%;">
		 <a href="<?php echo $continue_link ?>" class="continue_link">
		 	<?php echo $VM_LANG->_('PHPSHOP_CONTINUE_SHOPPING'); ?>
		 </a>
		 </div>
		<?php
    }  
   if (!defined('_MIN_POV_REACHED')) { ?>

       <span style="font-weight:bold;"><?php echo $VM_LANG->_('PHPSHOP_CHECKOUT_ERR_MIN_POV2') . " ".$CURRENCY_DISPLAY->getFullValue($_SESSION['minimum_pov']) ?></span>
       <?php
   }
   else {
   		$href = $sess->url( $_SERVER['PHP_SELF'].'?page=checkout.index&ssl_redirect=1', true);
   		$href2 = $sess->url( $mm_action_url . "/index2.php?page=checkout.index&ssl_redirect=1", true);
   		$class_att = 'class="checkout_link"';
   		$text = $VM_LANG->_('PHPSHOP_CHECKOUT_TITLE');
 		
   		if( $this->get_cfg('useGreyBoxOnCheckout', 1)) {
   			echo vmCommonHTML::getGreyBoxPopupLink( $href2, $text, '', $text, $class_att, 500, 600, $href );
   		}
   		else {
			echo '<div style="float: right; position: relative; height: 25px;">';
   			echo vmCommonHTML::hyperlink( $href, $text, '', $text, $class_att );
			echo '</div>';
   		}

		//Get our array that contain info about the paypal express checkout method.
		$payModule = retrievePaymentModule();
		
		//Check to see if we got the array properly and it is enabled.
		//If not enabled then we do not show the paypal express checkout button.
		if($payModule && PP_WPP_EXPRESS_ON == '1')
		{
			$payEnabled = $payModule['payment_enabled'];
			
			//Make sure the payment module is active
			//If not we happily continue on.
			if($payEnabled == 'Y')
			{
				echo '<div style="float: right; position: relative;">';
				echo '<a href="'.$_SERVER['PHP_SELF'].'?page=checkout.paypal_ex&ssl_redirect=1&option=com_virtuemart"><img src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif" alt="Checkout with Paypal Express" border="0" /></a>';
				echo '</div>';
			}
		}
 	}
	?>
	</div>
	
	<?php
	// End if statement
}
echo "</div>";
?>