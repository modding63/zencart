<?php
//Change get-parameters to avoid zen cart redirect
$_GET["secrethash"] = $_GET["hash"];
unset($_GET["hash"]);

$_GET["epaycurrency"] = $_GET["currency"];
unset($_GET["currency"]);

chdir('../../../../');
require('includes/application_top.php');

	@ini_set('display_errors', 'on');
	@error_reporting(E_ALL | E_STRICT);

//Restore session
$session = $db->Execute("SELECT customers_session FROM " . TABLE_CUSTOMERS_BASKET . " WHERE customers_id = " . $_GET["customerid"]);

session_decode(base64_decode($session->fields["customers_session"]));

$zco_notifier->notify('NOTIFY_CHECKOUT_PROCESS_BEGIN');

require(DIR_WS_LANGUAGES . $_SESSION["language"]. '/' . FILENAME_CHECKOUT_PROCESS . '.php');

$zco_notifier->notify('NOTIFY_HEADER_START_CHECKOUT_PROCESS');

require(DIR_WS_MODULES . zen_get_module_directory('checkout_process.php'));

// load the after_process function from the payment modules
$payment_modules->after_process();

$_SESSION['cart']->reset(true);

// unregister session variables used during checkout
unset($_SESSION['sendto']);
unset($_SESSION['billto']);
unset($_SESSION['shipping']);
unset($_SESSION['payment']);
unset($_SESSION['comments']);
$order_total_modules->clear_posts();//ICW ADDED FOR CREDIT CLASS SYSTEM

// This should be before the zen_redirect:
$zco_notifier->notify('NOTIFY_HEADER_END_CHECKOUT_PROCESS');

zen_redirect(zen_href_link(FILENAME_CHECKOUT_SUCCESS, (isset($_GET['action']) && $_GET['action'] == 'confirm' ? 'action=confirm' : ''), 'SSL'));

require(DIR_WS_INCLUDES . 'application_bottom.php');
?>