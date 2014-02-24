<?php
/*
  Copyright (c) 2012. All rights reserved ePay - www.epay.dk.

  This program is free software. You are allowed to use the software but NOT allowed to modify the software. 
  It is also not legal to do any changes to the software and distribute it in your own name / brand. 
*/
class epaywindow
{
	var $code, $title, $description, $enabled, $order_id;
	var $merchantnumber;
	var $currencies;
	
	function epaywindow()
	{
		global $order;
		
		$this->merchantnumber = MODULE_PAYMENT_EPAYWINDOW_SHOPID;
		$this->code = 'epaywindow';
		$this->title = MODULE_PAYMENT_EPAYWINDOW_TEXT_TITLE;
		$this->description = MODULE_PAYMENT_EPAYWINDOW_TEXT_DESCRIPTION;
		$this->sort_order = MODULE_PAYMENT_EPAYWINDOW_SORT_ORDER;
		$this->email_footer = MODULE_PAYMENT_EPAYWINDOW_TEXT_EMAIL_FOOTER;
		$this->enabled = true;
		$this->form_action_url = 'javascript: paymentwindow.open();';

		if(is_object($order))
			$this->update_status();
	}
	
	function update_status()
	{
		global $order, $db;
		
		if(($this->enabled == true) && ((int)MODULE_PAYMENT_EPAYWINDOW_ZONE > 0))
		{
			$check_flag = false;
			$check_query = $db->Execute("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_EPAYWINDOW_ZONE . "' and zone_country_id = '" . $order->billing["country"]["id"] . "' order by zone_id");
			while($check = zen_db_fetch_array($check_query))
			{
				if($check["zone_id"] < 1)
				{
					$check_flag = true;
					break;
				}
				elseif($check["zone_id"] == $order->billing["zone_id"])
				{
					$check_flag = true;
					break;
				}
			}
			
			if($check_flag == false)
			{
				$this->enabled = false;
			}
		}
	}
	
	function javascript_validation()
	{
		return false;
	}
	
	function selection()
	{
		return array('id' => $this->code, 'module' => $this->title);
	}
	
	function pre_confirmation_check()
	{
		return false;
	}
	
	function confirmation()
	{
		return array('title' => MODULE_PAYMENT_EPAYWINDOW_TEXT_DESCRIPTION);
	}
	
	function process_button()
	{
		global $customer_id, $order, $currencies, $currency, $languages_id, $language, $cart, $db;
		
		switch(strtolower($language))
		{
			case 'danish':
			case 'dansk':
				$epay_language = 1;
				break;
			case 'swedish':
			case 'svensk':
				$epay_language = 3;
				break;
			case 'norwegian':
			case 'norsk':
				$epay_language = 4;
				break;
			case 'grønlandsk':
				$epay_language = 5;
				break;
			case 'islandsk':
				$epay_language = 4;
				break;
			case 'german':
				$epay_language = 7;
				break;
			case 'finnish':
				$epay_language = 8;
				break;
			default:
				$epay_language = 2;
				break;
		}
		
		$order_id = $_SESSION['cartID'];
		
		$params = array
		(
			'merchantnumber' => MODULE_PAYMENT_EPAYWINDOW_SHOPID,
			'orderid' => $order_id,
			'amount' => (100 * $order->info["total"]),
			'currency' => $order->info["currency"],
			'accepturl' => zen_href_link('ext/modules/payment/epay/accept.php', 'accepturl=' . urlencode(zen_href_link(FILENAME_CHECKOUT_SUCCESS, 'SSL')), 'SSL', true, true, true),
			'callbackurl' => zen_href_link('ext/modules/payment/epay/callback.php', 'customerid=' . $_SESSION["customer_id"], 'SSL', true, true, true),
			'cancelurl' => zen_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=' . $this->code, 'SSL'),
			'language' => $epay_language,
			'instantcapture' => MODULE_PAYMENT_EPAYWINDOW_INSTANT_CAPTURE,
			'mailreceipt' => MODULE_PAYMENT_EPAYWINDOW_EMAIL,
			'smsreceipt' => MODULE_PAYMENT_EPAYWINDOW_SMS,
			'group' => MODULE_PAYMENT_EPAYWINDOW_GROUP,
			'windowstate' => MODULE_PAYMENT_EPAYWINDOW_WINDOW_STYLE
		);
		
		$hash = md5(implode("", array_values($params)) . MODULE_PAYMENT_EPAYWINDOW_MD5WORD);
		
		//Save/update session
		$db->Execute("UPDATE " . TABLE_CUSTOMERS_BASKET . " SET `customers_session` = '" . base64_encode(session_encode()) . "' WHERE customers_id = " . $_SESSION["customer_id"]);
		
		$process_button_string = "
		<script type=\"text/javascript\">
			function PaymentWindowReady()
			{
		            paymentwindow = new PaymentWindow({";
					foreach ($params as $key => $value)
					{
						$process_button_string .= "'". $key . "':	\"" . $value . "\",";
					}
					$process_button_string .= "'hash': \"" . $hash . "\"";
		            $process_button_string .= "});
					paymentwindow.open(); };
		</script>
		<script type=\"text/javascript\" src=\"https://ssl.ditonlinebetalingssystem.dk/integration/ewindow/paymentwindow.js\" charset=\"UTF-8\"></script>";
		
		return $process_button_string;
	}
	
	function before_process()
	{		
		global $cart, $db;
		
		if(strlen(MODULE_PAYMENT_EPAYWINDOW_MD5WORD) > 0)
		{
			$params = $_GET;
			$var = "";

			foreach ($params as $key => $value)
			{
			    if($key != "secretkey")
			    	$var .= $value;
			}

			$genstamp = md5($var . MODULE_PAYMENT_EPAYWINDOW_MD5WORD);

			if($genstamp != $_GET["secretkey"])
			{
			    echo "Hash is not valid";
			    exit();
			}
		}
	}
	
	function after_process()
	{
		global $db, $insert_id, $cart;
		
		$epay_transaction = $db->Execute("select orders_id from " . TABLE_ORDERS . " where cc_transactionid = '" . $_GET["txnid"] . "'");
		if ($epay_transaction->RecordCount() > 0)
		{
			$cart->reset(true);
			exit();
		}
		else
		{
			$cc_number = $_GET["cardno"] . " - <a href=\"epay_handle_payment.php?oID=" . $insert_id . "\">Handle payment</a>";
			
			$db->Execute("update " . TABLE_ORDERS . " set cc_number = '" . $cc_number . "', cc_transactionid = '" . $_GET["txnid"] . "' where orders_id = " . $insert_id);
			
			$message = 'ePay transaction ID: ' . $_GET["txnid"];
			$message .= "\n" . 'ePay "order ID": ' . $_GET["orderid"];
			
			$sql_data_array = array
			(
				'orders_id' => $insert_id,
				'orders_status_id' => MODULE_PAYMENT_EPAYWINDOW_ORDER_STATUS_ID,
				'date_added' => 'now()',
				'customer_notified' => 1,
				'comments' => $message
			);
			
			zen_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
			$db->Execute("update " . TABLE_ORDERS . " set orders_status = '" . MODULE_PAYMENT_EPAYWINDOW_ORDER_STATUS_ID . "', last_modified = now() where orders_id = '" . (int)$insert_id . "'");
		}
		
		return false;
	}
	
	function check()
	{
		global $db;
		if (!isset($this->_check)) {
			$check_query = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_EPAYWINDOW_SHOPID'");
			$this->_check = $check_query->RecordCount();
		}
      	return $this->_check;
	}
	
	function install()
	{	
		global $db;
		
		$db->Execute("ALTER TABLE " . TABLE_ORDERS . " MODIFY COLUMN `cc_number` VARCHAR(400) NULL DEFAULT NULL;");
		
		$db->Execute("ALTER TABLE " . TABLE_CUSTOMERS_BASKET . " ADD COLUMN `customers_session` TEXT NULL AFTER `customers_basket_date_added`;");
		
		//
		// Enabled status
		//
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enabled', 'MODULE_PAYMENT_EPAYWINDOW_STATUS', 'True', 'To enable and disable this payment method.', '6', '3', 'zen_cfg_select_option(array(\'True\', \'False\'), ', now())");
		
		//
		// Zones
		//
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_EPAYWINDOW_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '2', 'zen_get_zone_class_title', 'zen_cfg_pull_down_zone_classes(', now())");
		
		//
		// Order status after payment
		//
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('New order status (When the payment is made)', 'MODULE_PAYMENT_EPAYWINDOW_ORDER_STATUS_ID', '2', 'Set the status of the order after the payment is made. This is used to distinguish between paid and not paid orders!', '6', '0', 'zen_cfg_pull_down_order_statuses(', 'zen_get_order_status_name', now())");

		//
		// Merchant ID
		//
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Merchantnumber', 'MODULE_PAYMENT_EPAYWINDOW_SHOPID', '', 'The unique merchantnumber received from the payment system. If you don\'t know your merchantnumber please log into your account <a href=\'https://ssl.ditonlinebetalingssystem.dk/admin/login.asp?contentpage=1\' target=\'_blank\'><b>here</b></a>. You can then find your merchantnumber from the menu <b>Settings</b> -> <b>Payment System</b>.', '6', '6', now())");
		
		//
		// MD5 settings
		//
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('MD5 Key', 'MODULE_PAYMENT_EPAYWINDOW_MD5WORD', '', 'This is the secret password you must enter both here and within the payment system if you want to enable MD5. Notice that the secret password must be 100% the same. Otherwise the customer will be declined as payments are made. You can log into your account on the payment system <a target=\"_blank\" href=\"https://ssl.ditonlinebetalingssystem.dk/admin/login.asp?contentpage=1\"><b>here</b></a>. The MD5 settings are to be found from the menu <b>Settings</b> -> <b>Payment System</b>.', '6', '0', now())");
		
		//
		// Use API
		//
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Process payments from within the order administration of osCommerce (Remote API):', 'MODULE_PAYMENT_EPAYWINDOW_USE_API', '0', 'Enable this option in order to capture, credit and delete payments directly from the osCommerce administration on the order page. You then don\'t need to enter the administration area of the payment system in order to process payments. <b>Notice!</b> This feature require the ePay Business subscription. <br>0 - Disabled<br>1 - Enabled', '99', '0', 'zen_cfg_select_option(array(\'0\',\'1\'), ', now())");

		//
		// API Password
		//
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Remote API Password:', 'MODULE_PAYMENT_EPAYWINDOW_API_PASSWORD', '', 'If payments is processed from within the order administration and a password is added in the ePay administration is should also be added here.', '99', '0', now())");

		//
		// Payment sort order
		//
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_EPAYWINDOW_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
		
		//
		// Use ePay in popup or in full screen mode
		//
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Windows state', 'MODULE_PAYMENT_EPAYWINDOW_WINDOW_STYLE', '1', 'If the value is <b>Overlay</b> the Payment Window will open in a overlay window. If the value is <b>Full Screen</b> the payment window will be displayed in the same window but in full-screen.<br>1 - Overlay<br>3 - Full Screen', '93', '0', 'zen_cfg_select_option(array(\'1\', \'3\'), ', now())");
		
		//
		// Recieve emails on payment approval
		//
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Auth Mail', 'MODULE_PAYMENT_EPAYWINDOW_EMAIL', '', 'The auth mail setting is used if you need to receive an e-mail with payment information from the payment system as the payment is made. If you have multiple receivers you can separate the list by semicolon (;).', '94', '0', now())");
		
		//
		// Recieve SMS on payment approval
		//
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Auth SMS number', 'MODULE_PAYMENT_EPAYWINDOW_SMS', '', 'This setting is used if you need to receive a SMS with payment information as the payment is made. Just enter you mobile number (e.g. +45xxxxxxxx). If you have multiple receivers you can separate the list by semicolon (;). <br /><br /><b>Notice!</b><br /> This service is not free.', '95', '0', now())");
		
		//
		// Instant capture
		//
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Instant capture', 'MODULE_PAYMENT_EPAYWINDOW_INSTANT_CAPTURE', '0', 'This is used if you wish to capture the payments at the same time as it is authorized. This option may only be used if the cardholder receives the goods at once.<br>0 - Disabled<br>1 - Enabled', '97', '0', 'zen_cfg_select_option(array(\'0\', \'1\'), ', now())");
		
		//
		// Add fee to shipping
		//
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Add payment fee to the \"order and invoice\":', 'MODULE_PAYMENT_EPAYWINDOW_ADDFEE_TO_SHIPPING', '0', 'If the customer pays for the payment fee the amount will not be displayed on the order confirmation. However it is possible to add the amount to the order and invoice. The Amount displayed at the bottom of the order confirmation.<br>0 - Disabled<br>1 - Enabled<br><br><b>Notice!</b><br>For this feature to work you need to enable <b>Transaction fee</b> in <b>Modules > Order Total</b>!', '99', '0', 'zen_cfg_select_option(array(\'0\',\'1\'), ', now())");
		
		//
		// Group options
		//
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Group', 'MODULE_PAYMENT_EPAYWINDOW_GROUP', '', 'This setting is used to assign the payment to a specific group within the payment system.', '95', '0', now())");

		if(!$this->colExists('cc_transactionid'))
			$db->Execute("ALTER TABLE " . TABLE_ORDERS . " ADD cc_transactionid VARCHAR( 64 ) NULL default 'NULL';");
	}
	
	function remove()
	{
		global $db;
		
		$db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "') OR configuration_key LIKE '%MODULE_PAYMENT_EPAYWINDOW_LOGOS%' OR configuration_key LIKE '%MODULE_ORDER_TOTAL_EPAY%'");
	}
	
	function keys()
	{
		return array
		(
			'MODULE_PAYMENT_EPAYWINDOW_STATUS',
			'MODULE_PAYMENT_EPAYWINDOW_SORT_ORDER',
			'MODULE_PAYMENT_EPAYWINDOW_ORDER_STATUS_ID',
			'MODULE_PAYMENT_EPAYWINDOW_ZONE',
			'MODULE_PAYMENT_EPAYWINDOW_SHOPID',
			'MODULE_PAYMENT_EPAYWINDOW_INSTANT_CAPTURE',
			'MODULE_PAYMENT_EPAYWINDOW_GROUP',
			'MODULE_PAYMENT_EPAYWINDOW_MD5WORD',
			'MODULE_PAYMENT_EPAYWINDOW_USE_API',
			'MODULE_PAYMENT_EPAYWINDOW_API_PASSWORD',
			'MODULE_PAYMENT_EPAYWINDOW_EMAIL',
			'MODULE_PAYMENT_EPAYWINDOW_WINDOW_STYLE',
			'MODULE_PAYMENT_EPAYWINDOW_ADDFEE_TO_SHIPPING'
		);
	}
	
	function colExists($name)
	{
		global $db;
		
		$res = $db->Execute("SHOW COLUMNS FROM " . TABLE_ORDERS);

		while (!$res->EOF) {
		  if ($res->fields['Field'] == $name) return true;
		  $res->MoveNext();
		}
		
		return false;
	}
}
