<?php
/*
  ot_epay_transfee.php

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 ePay

  Released under the GNU General Public License
*/

class ot_epay_transfee
{
	var $title, $output;
	
	function ot_epay_transfee()
	{
		$this->code = 'ot_epay_transfee';
		$this->title = MODULE_ORDER_TOTAL_EPAY_TITLE;
		$this->description = MODULE_ORDER_TOTAL_EPAY_DESCRIPTION;
		$this->enabled = ((MODULE_ORDER_TOTAL_EPAY_STATUS == 'true') ? true : false);
		$this->sort_order = MODULE_ORDER_TOTAL_EPAY_SORT_ORDER;
		
		if($_SERVER["PHP_SELF"] == "/checkout_confirmation.php")
		{
			$this->enabled = false;
		}
		
		if(MODULE_PAYMENT_EPAYWINDOW_ADDFEE_TO_SHIPPING == 0)
		{
			$this->enabled = false;
		}
		
		$this->output = array();
	}
	
	function process()
	{
		global $order, $currencies;
		
		$order->info["total"] = $order->info["total"] + ($_GET["txnfee"] / 100);
		$this->output[] = array('title' => $this->title . ':', 'text' => $currencies->format($_GET["txnfee"] / 100, true, $order->info["currency"], $order->info["currency_value"]), 'value' => $_GET["txnfee"] / 100);
	}
	
	function check()
	{
		global $db;
		
		if(!isset($this->_check))
		{
			$check_query = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ORDER_TOTAL_EPAY_STATUS'");
			$this->_check = $check_query->RecordCount();
		}
		
		return $this->_check;
	}
	
	function keys()
	{
		return array('MODULE_ORDER_TOTAL_EPAY_STATUS', 'MODULE_ORDER_TOTAL_EPAY_SORT_ORDER');
	}
	
	function install()
	{
		global $db;
		
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Display Total', 'MODULE_ORDER_TOTAL_EPAY_STATUS', 'true', 'Do you want to display the transaction fee value?', '6', '1','zen_cfg_select_option(array(\'true\', \'false\'), ', now())");
		$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ORDER_TOTAL_EPAY_SORT_ORDER', '4', 'Sort order of display.', '6', '2', now())");
	}
	
	function remove()
	{
		global $db;
		
		$db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
	}
}
?>
