<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
/**
*
* @version $Id: checkout_bar.tpl.php 1451 2008-07-04 19:55:31Z soeren_nb $
* @package VirtueMart
* @subpackage templates
* @copyright Copyright (C) 2007 Soeren Eberhardt. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
*
* http://virtuemart.net
*/

?>
<?php
// CSS style for the <td> tag of the cell which is actually highlighted
$highlighted_style = 'style="font-weight: bold;"';
$i = 1;
foreach ($steps_to_do as $step ) {
	if( $highlighted_step==$step[0]['step_order'] ) {
		$current_step = $i;
		break;
	}
	$i++;
}
echo '<table border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>';

foreach ($steps_to_do as $step ) {

	echo '<td '.(($highlighted_step==$step[0]['step_order']) ? $highlighted_style : '') .' width="119" align="center" valign="bottom">';
	if ($highlighted_step > $step[0]['step_order'] ) {
		//See if we are checking out via paypal express.
		//If so we need to make sure we are going to the paypal express url instead.
		if(isset($_SESSION['paypal_wpp_ex']))
		{
			echo '<a href="'. $sess->url(SECUREURL."index.php?page=checkout.paypal_ex&amp;option=com_virtuemart&amp;ship_to_info_id=$ship_to_info_id&amp;shipping_rate_id=".@$shipping_rate_id."&amp;checkout_stage=".$step[0]['step_order'] ).'">';
		}
		else
		{
			echo '<a href="'. $sess->url(SECUREURL."index.php?page=checkout.index&amp;option=com_virtuemart&amp;ship_to_info_id=$ship_to_info_id&amp;shipping_rate_id=".@$shipping_rate_id."&amp;checkout_stage=".$step[0]['step_order'] ).'">';
		}
		foreach( $step as $substep ) {
			echo $substep['step_msg'].'<br />';
		}
		echo '</a>';
	}
	else {
		foreach( $step as $substep ) {
			if( $substep['step_order'] > $highlighted_step ) {
				echo $substep['step_msg'];
			} else {
				echo '<a href="#'.$substep['step_name'].'">'.$substep['step_msg'].'</a>';
			}
			echo '<br />';
		}
	}
	echo '</td>';
}
echo '
  </tr>
</table>
<br />';
