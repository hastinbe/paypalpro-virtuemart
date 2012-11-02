<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );

define('PP_WPP_TEXT_CREDIT_CARD_TYPE', 'Credit Card Type:');
define('PP_WPP_TEXT_CREDIT_CARD_FIRSTNAME', 'Owner First Name:');
define('PP_WPP_TEXT_CREDIT_CARD_LASTNAME', 'Owner Last Name:');
define('PP_WPP_TEXT_CREDIT_CARD_NUMBER', 'Card Number:');
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

define ('PP_WPP_TEXT_EXPRESS_ENABLE', 'Enable Paypal Express Checkout?');
define ('PP_WPP_TEXT_EXPRESS_ENABLE_EXPLAIN', 'Check to use Paypal Express Checkout.');

define ('PP_WPP_TEXT_USE_PROXY','Use Proxy?');
define ('PP_WPP_TEXT_USE_PROXY_EXPLAIN','Should this request be sent through a proxy server? (Some hosting accounts, like GoDaddy, require the use of a proxy.)');
define ('PP_WPP_TEXT_PROXY_HOST','Proxy Host');
define ('PP_WPP_TEXT_PROXY_HOST_EXPLAIN','Enter the host IP of your proxy server.');
define ('PP_WPP_TEXT_PROXY_PORT','Proxy Port');
define ('PP_WPP_TEXT_PROXY_PORT_EXPLAIN','Enter the port number of your proxy server.');

define('PP_WPP_CHECK_CARD_CODE', 'YES');
define('PP_WPP_CVV_TEXT', 'Check for CVV?');
define('PP_WPP_CVV_TEXT_EXPLAIN', 'Only disable if you have your merchant account setup to not request the CVV code.');

define ('PP_WPP_VERSION', '52.1');

define ('PP_WPP_SANDBOX', '0');
define ('PP_WPP_USE_CERTIFICATE', '0');
define ('PP_WPP_USERNAME', '');
define ('PP_WPP_PASSWORD', '');
define ('PP_WPP_SIGNATURE', '');
define ('PP_WPP_CERTIFICATE', '');
define ('PP_WPP_SUCCESS_STATUS', 'C');
define ('PP_WPP_PENDING_STATUS', 'P');
define ('PP_WPP_FAILED_STATUS', 'X');
define ('PP_WPP_USE_PROXY', '0');
define ('PP_WPP_PROXY_HOST', '127.0.0.1');
define ('PP_WPP_PROXY_PORT', '808');
define ('PP_WPP_EXPRESS_ON', '1');

define('PP_WPP_ERRORS', '0');
define('PP_WPP_ERRORS_TEXT', 'Show PayPal Errors');
define('PP_WPP_ERRORS_TEXT_EXPLAIN', 'Shows all PayPal errors at checkout. Recommended off when your site goes Live.');

define('PP_WPP_EX_SANDBOX_URL', 'https://beta-sandbox.paypal.com/webscr&cmd=_express-checkout&token=');
define('PP_WPP_EX_LIVE_URL', 'https://www.paypal.com/webscr&cmd=_express-checkout&token=');
define('PP_WPP_PAYMENT_ACTION', 'Sale');
define('PP_WPP_REQCONFIRMSHIPPING', '1');
define('PP_WPP_TEXT_REQCONFIRMSHIPPING', 'Require Confirmed Shipping Address?');
define('PP_WPP_TEXT_REQCONFIRMSHIPPING_EXPLAIN', 'Setting this to YES will make PayPal check for a confirmed address on file when using Express Checkout. This overrides your merchant account settings.');
?>