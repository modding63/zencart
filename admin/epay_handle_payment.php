<?php
/*
  Copyright (c) 2012. All rights reserved ePay - www.epay.dk.

  This program is free software. You are allowed to use the software but NOT allowed to modify the software. 
  It is also not legal to do any changes to the software and distribute it in your own name / brand. 
*/

require('includes/application_top.php');

?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/cssjsmenuhover.css" media="all" id="hoverJS">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script type="text/javascript">
<!--
function init()
{
  cssjsmenu('navbar');
  if (document.getElementById)
  {
    var kill = document.getElementById('hoverJS');
    kill.disabled = true;
  }
  if (typeof _editor_url == "string") HTMLArea.replaceAll();
}
// -->
</script>
<?php if ($action != 'edit_category_meta_tags') { // bof: categories meta tags ?>
<?php if ($editor_handler != '') include ($editor_handler); ?>
<?php } // meta tags disable editor eof: categories meta tags?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="init();document.forms['search'].elements['search'].focus();">
<div id="spiffycalendar" class="text"></div>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->

<?php

function get_nb_code($code)
{
	switch($code)
	{
		case 208:
			return 'DKK';
			break;
		case 978:
			return 'EUR';
			break;
		case 840:
			return 'USD';
			break;
		case 578:
			return 'NOK';
			break;
		case 752:
			return 'SEK';
			break;
		case 826:
			return 'GBP';
			break;
		default:
			return 'DKK';
			break;
	}
}
 	@ini_set('display_errors', TRUE);
	@error_reporting(E_ALL | E_STRICT);

if((@$_POST['epay_capture'] OR @$_POST['epay_move_as_captured'] OR @$_POST['epay_credit'] OR @$_POST['epay_delete']) AND isset($_POST['epay_transaction_id']))
{
	$client = new SoapClient('https://ssl.ditonlinebetalingssystem.dk/remote/payment.asmx?WSDL');
	
	if(@$_POST['epay_capture'])
	{
		$result = $client->capture(array
		(
			"merchantnumber" => MODULE_PAYMENT_EPAYWINDOW_SHOPID,
			"transactionid" => $_POST["epay_transaction_id"],
			"amount" => (floatval($_POST["epay_amount"]) * 100),
			"pwd" => MODULE_PAYMENT_EPAYWINDOW_API_PASSWORD,
			"pbsResponse" => - 1,
			"epayresponse" => - 1,
			)
		);
	}
	elseif(@$_POST['epay_credit'])
	{
		$result = $client->credit(array
		(
			"merchantnumber" => MODULE_PAYMENT_EPAYWINDOW_SHOPID,
			"transactionid" => $_POST["epay_transaction_id"],
			"amount" => (floatval($_POST["epay_amount"]) * 100),
			"pwd" => MODULE_PAYMENT_EPAYWINDOW_API_PASSWORD,
			"pbsresponse" => - 1,
			"epayresponse" => - 1,
			)
		);
	}
	elseif(@$_POST['epay_delete'])
	{
		$result = $client->delete(array
		(
			"merchantnumber" => MODULE_PAYMENT_EPAYWINDOW_SHOPID,
			"transactionid" => $_POST["epay_transaction_id"],
			"pwd" => MODULE_PAYMENT_EPAYWINDOW_API_PASSWORD,
			"epayresponse" => - 1,
			)
		);
	}
	elseif(@$_POST['epay_move_as_captured'])
	{
		$result = $client->moveascaptured(array
		(
			"merchantnumber" => MODULE_PAYMENT_EPAYWINDOW_SHOPID,
			"transactionid" => $_POST["epay_transaction_id"],
			"pwd" => MODULE_PAYMENT_EPAYWINDOW_API_PASSWORD,
			"epayresponse" => - 1,
			)
		);
	}
	
	if(@$result->captureResult == "true")
	{
		$message = "Capture OK";
	}
	elseif(@$result->creditResult == "true")
	{
		$message = "Credit OK";
	}
	elseif(@$result->deleteResult == "true")
	{
		$message = "Delete OK";
	}
	elseif(@$result->move_as_capturedResult == "true")
	{
		$message = "Moved OK";
	}
	else
	{		
		if(@$_POST['epay_capture'])
		{
			$pbsresponse = $result->pbsResponse;
		}
		elseif(!@$_POST['epay_delete'] && !@$_POST['epay_move_as_captured'])
		{
			$pbsresponse = $result->pbsresponse;
		}
		
		$message = "Error: Acquirer: " . $pbsresponse . " ePay: " . $result->epayresponse;	
	}
	
	echo '<script type="text/javascript">alert("'. $message .'");</script>';;
}

?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
	<?php
	$epay_query = $db->Execute("select cc_transactionid from " . TABLE_ORDERS . " where orders_id = '" . $_GET["oID"] . "'");	
	$epay_transaction = $epay_query->fields;
	?>
	<tr>
		<td width="100%">
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td class="pageHeading"><?php echo $epay_transaction["cc_transactionid"]; ?></td>
					<td class="pageHeading" align="right"><?php echo zen_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
					<td class="smallText" align="right"><?php echo zen_image_button(IMAGE_BACK, 'triangle-1-w', 'orders.php?oID='. $_GET["oID"] .'&action=edit'); ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="main">
			<table>
				<tr>
					<td align="right">
						ePay Control Panel:
					</td>
					<td>
						<a href="https://ssl.ditonlinebetalingssystem.dk/admin/login.asp" title="ePay login" target="_blank">.../admin/login.asp</a>
					</td>
				</tr>
				<tr>
					<td align="right">
						Transaction ID:
					</td>
					<td>
						<b><?php echo $epay_transaction["cc_transactionid"] ?></b>
					</td>
				</tr>
				<?php
				if(MODULE_PAYMENT_EPAYWINDOW_USE_API == "1")
				{
				?>
				<tr>
					<td colspan="2" class="main">
						<br />
						<?php
						
						$client = new SoapClient('https://ssl.ditonlinebetalingssystem.dk/remote/payment.asmx?WSDL');
						
						$epay_params = array();
						$epay_params['merchantnumber'] = MODULE_PAYMENT_EPAYWINDOW_SHOPID;
						$epay_params['transactionid'] = $epay_transaction["cc_transactionid"];
						$epay_params["epayresponse"] = "-1";
						$epay_params["pwd"] = MODULE_PAYMENT_EPAYWINDOW_API_PASSWORD;
						$soap_result = $client->gettransaction($epay_params);
						
						if(!$soap_result->transactionInformation->capturedamount or $soap_result->transactionInformation->capturedamount == $soap_result->transactionInformation->authamount)
						{
							$epay_amount = number_format($soap_result->transactionInformation->authamount / 100, 2, ".", "");
						}
						elseif($soap_result->transactionInformation->status == 'PAYMENT_CAPTURED')
						{
							$epay_amount = number_format(($soap_result->transactionInformation->capturedamount) / 100, 2, ".", "");
						}
						else
						{
							$epay_amount = number_format(($soap_result->transactionInformation->authamount - $soap_result->transactionInformation->capturedamount) / 100, 2, ".", "");
						}
						
						if($soap_result->transactionInformation->status != 'PAYMENT_DELETED' AND !$soap_result->transactionInformation->creditedamount)
						{
							?>
							<form name="epay_remote" action="<?php echo $_SERVER["REQUEST_URI"] ?>" method="post" style="display:inline">
								<input type="hidden" name="epay_transaction_id" value="<?php echo $epay_transaction["cc_transactionid"]; ?>" />
								<?php
									echo get_nb_code($soap_result->transactionInformation->currency);
									echo ' <input type="text" id="epay_amount" name="epay_amount" value="' . $epay_amount . '" size="' . strlen($epay_amount) . '" />';
								?>
								<?php
								if(!$soap_result->transactionInformation->capturedamount or ($soap_result->transactionInformation->splitpayment and $soap_result->transactionInformation->status != 'PAYMENT_CAPTURED' and ($soap_result->transactionInformation->capturedamount != $soap_result->transactionInformation->authamount)))
								{
									echo ' <input class="button" name="epay_capture" type="submit" value="Capture" />';
									echo '<input class="button" name="epay_delete" type="submit" value="Delete" onclick="return confirm(\'Really want to delete?\');" />';
									
									if($soap_result->transactionInformation->splitpayment)
									{
										echo '<br /><input class="button" name="epay_move_as_captured" type="submit" value="Close transaction" /> ';
									}
									
								}
								elseif($soap_result->transactionInformation->status == 'PAYMENT_CAPTURED' OR $soap_result->transactionInformation->acquirer == 'EUROLINE')
								{
									echo ' <input class="button" name="epay_credit" type="submit" value="Credit" onclick="return confirm(\'Do you want to credit: ' . get_nb_code($soap_result->transactionInformation->currency) . ' \'+getE(\'epay_amount\').value);" />';
								}
								?>
							</form>
						<?php
						}
						else
						{
							echo get_nb_code($soap_result->transactionInformation->currency) . ' ' . $epay_amount;
							echo ($soap_result->transactionInformation->status == 'PAYMENT_DELETED' ? ' <span style="color:red;font-weight:bold;">Deleted</span>' : '');
						}
						
						?>
						<br /><br />
						<table class="table" cellspacing="0" cellpadding="2">
							<tr class="dataTableHeadingRow">
								<td class="dataTableHeadingContent">Date</td>
								<td class="dataTableHeadingContent">Event</td>
							</tr>
						
							<?php
							
							$historyArray = $soap_result->transactionInformation->history->TransactionHistoryInfo;
							
							if(!array_key_exists(0, $soap_result->transactionInformation->history->TransactionHistoryInfo))
							{
								$historyArray = array($soap_result->transactionInformation->history->TransactionHistoryInfo);
								// convert to array
							}
							
							for($i = 0; $i < count($historyArray); $i++)
							{
								echo "<tr class=\"dataTableRow\"><td class=\"dataTableContent\"><b>" . str_replace("T", " ", $historyArray[$i]->created) . "</b></td>";
								echo "<td class=\"dataTableContent\">";
								if(strlen($historyArray[$i]->username) > 0)
								{
									echo ($historyArray[$i]->username . ": ");
								}
								echo $historyArray[$i]->eventMsg . "</td></tr>";
							}
							
							?>
						</table>
					</td>
				</tr>
				<?php
				}
				?>
			</table>
		</td>
	</tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br />
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>