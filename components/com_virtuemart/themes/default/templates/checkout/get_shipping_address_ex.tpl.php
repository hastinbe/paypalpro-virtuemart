<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
/**
*
* @version $Id: get_shipping_address.tpl.php 1140 2008-01-09 20:44:35Z soeren_nb $
* @package VirtueMart
* @subpackage templates
* @copyright Copyright (C) 2007 Soeren Eberhardt. All rights reserved. Edited by Aaron Klick to work with paypal express mod.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
*
* http://virtuemart.net
*/

ps_checkout::show_checkout_bar();

echo $basket_html;
   
echo '<br />';

// CHECK_OUT_GET_SHIPPING_ADDR
// let the user choose a shipto address
echo ps_checkout::display_address();

$varname = 'PHPSHOP_CHECKOUT_MSG_' . CHECK_OUT_GET_SHIPPING_ADDR;
echo '<h4>'. $VM_LANG->_($varname) . '</h4>';
?>

<!-- Customer Ship To -->
<table border="0" cellspacing="0" cellpadding="2" width="100%">
    <tr class="sectiontableheader">
        <th align="left" colspan="2"><?php echo $VM_LANG->_('PHPSHOP_ORDER_PRINT_CUST_SHIPPING_LBL') ?> :
        </th>
    </tr>
    <tr>
        <td colspan="2">
		<!-- Make sure that we are redirected back to paypal express checkout since that is where we came from originally. -->
        <?php echo $VM_LANG->_('PHPSHOP_ADD_SHIPTO_1') ?>
        <a href="<?php $sess->purl(SECUREURL .basename($_SERVER['PHP_SELF']). "?page=account.shipto&next_page=checkout.paypal_ex");?>">
        <?php echo $VM_LANG->_('PHPSHOP_ADD_SHIPTO_2') ?></a>.
        </td>
    </tr>
    <tr>
        <td colspan="2">
        <?php $ps_checkout->ship_to_addresses_radio($auth["user_id"], "ship_to_info_id", $ship_to_info_id);
        ?>
        </td>
    </tr>
</table>
<!-- END Customer Ship To -->
<br />
