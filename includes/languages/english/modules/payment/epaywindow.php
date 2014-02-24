<?php
/*
  $Id: includes\languages\english\modules\payment\epaywindow.php

	Copyright ePay / Payment solutions, (c) 2010.
	This program is free software. You are allowed to use the software but NOT allowed to modify the software. 
	It is also not legal to do any changes to the software and distribute it in your own name / brand. 

*/


define('MODULE_PAYMENT_EPAYWINDOW_TEXT_TITLE_CHECKOUT', 'Pay with your credit card (ePay)');
define('MODULE_PAYMENT_EPAYWINDOW_TEXT_TITLE', 'ePay Payment Solutions');
define('MODULE_PAYMENT_EPAYWINDOW_TEXT_DESCRIPTION', 'Online payment with credit card');
define('MODULE_PAYMENT_EPAYWINDOW_TEXT_EMAIL_FOOTER', 'Payment is now reserved. When the order is handled, the amount is transferetd to ' . STORE_NAME); 
define('MODULE_PAYMENT_EPAYWINDOW_TEXT_OPEN_WINDOW', 'Open the ePay Payment Window');
define('MODULE_PAYMENT_EPAYWINDOW_TEXT_OPEN_WINDOW_HELP', 'You have chosen to pay by use of <a href="http://www.epay.dk" target="_new">ePay</a>. However if the ePay Payment Window does not occur please click on the button below.<br>If you use a pop-up blocker you must hold down <b>CTRL</b> as you click the button.');
define('MODULE_PAYMENT_EPAYWINDOW_TEXT_APPROVED_HEADER', 'Your payment has been approved by ePay!');
define('MODULE_PAYMENT_EPAYWINDOW_TEXT_APPROVED_ORDERID', 'We have successfully verified your order by orderid:');
define('MODULE_PAYMENT_EPAYWINDOW_TEXT_APPROVED_TID', 'The payment has been approved by ePay transaction id:');
define('MODULE_PAYMENT_EPAYWINDOW_TEXT_APPROVED_CARDNO', 'The transaction was made by the following card number:');
define('MODULE_PAYMENT_EPAYWINDOW_ORDERSTATUS_CHANGED_APPROVED', 'The payment has been approved by ePay transaction id: %s');
define('MODULE_PAYMENT_EPAYWINDOW_ORDERSTATUS_CHANGED_CALLBACK_INFO', '(The order was updated by callback)');
define('MODULE_PAYMENT_EPAYWINDOW_TEXT_APPROVED_HEADER_DESCRIPTION', '<BR>Your order will be processed within 1-2 days.<BR><BR>Kind regards<BR><BR>' . STORE_NAME);

define('MODULE_PAYMENT_EPAYWINDOW_SECURE_PAYMENT_HEAD', 'SECURE PAYMENT');
define('MODULE_PAYMENT_EPAYWINDOW_SECURE_PAYMENT_TEXT', 'ePay / Payment Solutions is PCI certified by VISA / Mastercard and all communication is encrypted.');

define('HEADING_TITLE', 'Step 4 of 4 - Online payment');

define('DECLINE_TEXT', 'Unable to process credit card transaction');
define('DECLINE_REASON', 'Reason for error: ');

define('ERROR_MERCHANT_UNKNOWN', 'Unknown Merchant Number');
define('ERROR_CARDNO_NOT_VALID', 'Illegal cardnumber');
define('ERROR_CVC_NOT_VALID', 'Illegal controldigits');
define('ERROR_ORDERID', 'OrderID illegal or missing');
define('ERROR_TRANSACTION_DECLINED', 'The transaction was declined');
define('ERROR_WRONG_NUMBER_FORMAT', 'Wrong format for the amount');
define('ERROR_ILLEGAL_TRANSACTION', 'Illegal transaction');
define('ERROR_TRANSACTION_EXPIRED', 'Transaction has expired');
define('ERROR_NO_ANSWER', 'No answer');
define('ERROR_SYSTEM_FAILURE', 'System failure');
define('ERROR_CARD_EXPIRED', 'Card expired');
define('ERROR_COMMUNICATION_FAILURE', 'Communication failure');
define('ERROR_INTERNAL_FAILURE', 'Internal failure');
define('ERROR_CARD_NOT_REGISTERED', 'Customer not created in system');
define('ERROR_RETRY_FAILURE', 'The system do not allows processing same transaction more times');
define('ERROR_UNKNOWN', 'Errors in entered information');
define('ERROR_NO_FUNDS', 'Not enough funds for amount'); 

define('MODULE_PAYMENT_EPAYWINDOW_TEXT_ONLINE_PAYMENT', 'Online Payment');
define('MODULE_PAYMENT_EPAYWINDOW_TEXT_PAYMENT', 'Payment');
define('MODULE_PAYMENT_EPAYWINDOW_TEXT_PAYMENT_REJECTED', 'The payment was rejected - error code: ');
define('MODULE_PAYMENT_EPAYWINDOW_TEXT_CARDHOLDER', 'Kortholder:');
define('MODULE_PAYMENT_EPAYWINDOW_TEXT_COMMENT', 'Kommentar:');
define('MODULE_PAYMENT_EPAYWINDOW_TEXT_TRANSACTIONFEE', 'Transaction fee: ');
define('MODULE_PAYMENT_EPAYWINDOW_TEXT_ORDER_NUMBER', 'Order number');
define('MODULE_PAYMENT_EPAYWINDOW_TEXT_AMOUNT', 'Amount: ');
define('MODULE_PAYMENT_EPAYWINDOW_TEXT_CARDNUMBER', 'Cardnumber: ');
define('MODULE_PAYMENT_EPAYWINDOW_TEXT_EXPDATE', 'Expire date: ');
define('MODULE_PAYMENT_EPAYWINDOW_TEXT_CVC', 'CVC: ');
define('MODULE_PAYMENT_EPAYWINDOW_TEXT_MONTH_YEAR', ' (Month / Year)');
define('MODULE_PAYMENT_EPAYWINDOW_TEXT_COMPLETE_TRANSACTION', '<b>Complete transaction for online payment</b><br>to complete the order');
define('EPAY_FEE_PLEASE_WAIT', 'Please wait while the transaction fee is calculated..');
define('EPAY_FEE_NOT_CALCULATED_YET', 'Transaction fee not calculated yet!');
define('EPAY_FEE_ERROR', 'An error occured - cardtype and transaction fee could not be calculated! ePay errorcode: ');

define('EPAY_CVC_WHAT_IS', 'What is CVC');
define('EPAY_CVC_PICTURE_ONE', 'Above is shown where the card verification code is located at the back of a Dankort');
define('EPAY_CVC_PICTURE_TWO', 'For credit cards without a picture of the card holder, the card verification code is to be found on the back just above the signature');


define('ENTRY_EPAY_TRANSACTION', 'ePay transaction number:');
define('ENTRY_EPAY_HANDLE_TRANSACTION', 'Click here to go to ePay and handle the transaction');
define('ENTRY_EPAY_INVALID_CHARACTERS', 'Invalid characters found in the amount field - only numbers are allowed!');
define('ENTRY_EPAY_PAYMENT_CAPTURED_1', 'The payment has been captured ');
define('ENTRY_EPAY_PAYMENT_CAPTURED_2', ' with the amount:');
define('ENTRY_EPAY_PAYMENT_NOT_CAPTURED_1', 'The payment has not yet been captured. ');

define('ENTRY_EPAY_CARDTYPE', 'Card type');
define('ENTRY_EPAY_FRAUDSTATUS', 'Fraud status');
define('ENTRY_EPAY_TRANSACTIONFEE', 'Transaction fee');
define('ENTRY_EPAY_TRANSACTIONSTATUS', 'Transaction status');
define('ENTRY_EPAY_ACQUIRER', 'Acquirer');
define('ENTRY_EPAY_CURRENCYCODE', 'Currency code');
define('ENTRY_EPAY_SPLITPAYMENT', 'Splitpayment');
define('ENTRY_EPAY_DESCRIPTION', 'Description');
define('ENTRY_EPAY_CARDHOLDER', 'Card holder');
define('ENTRY_EPAY_AUTHAMOUNT', 'Auth amount');
define('ENTRY_EPAY_CAPTUREDAMOUNT', 'Captured amount');
define('ENTRY_EPAY_CREDITEDAMOUNT', 'Credited amount');
define('ENTRY_EPAY_DELETED', 'Deleted');
define('ENTRY_EPAY_HANDLEORDER', 'Handle order');

define('ENTRY_EPAY_CAPTURE', 'Capture');
define('ENTRY_EPAY_DELETE', 'Delete');
define('ENTRY_EPAY_CREDIT', 'Credit');


define('PAYMENT_UNDEFINED', 'Payment undefined');
define('PAYMENT_NEW', 'New');
define('PAYMENT_CAPTURED', 'Payment captured');
define('PAYMENT_DELETED', 'Deleted');
define('PAYMENT_SUBSCRIPTION_INI', 'Subscription');
define('PAYMENT_RENEW', 'Renew');

define('PAYMENT_EUROLINE_WAIT_CAPTURE', 'Wait capture');
define('PAYMENT_EUROLINE_WAIT_CREDIT', 'Wait credit');

define('YES', 'Yes');
define('NO', 'No');	

define('MODULE_PAYMENT_EPAYWINDOW_PAYMENTTYPE_LABEL', 'Payment type');
define('MODULE_PAYMENT_EPAYWINDOW_PAYMENTTYPE_0_LABEL', 'Payment by credit card');
define('MODULE_PAYMENT_EPAYWINDOW_PAYMENTTYPE_17_LABEL', 'EWIRE');
define('MODULE_PAYMENT_EPAYWINDOW_PAYMENTTYPE_20_LABEL', 'eDankort');
define('MODULE_PAYMENT_EPAYWINDOW_PAYMENTTYPE_21_LABEL', 'Nordea e-betaling');
define('MODULE_PAYMENT_EPAYWINDOW_PAYMENTTYPE_22_LABEL', 'Danske Netbetaling');

define('TITLE_CONTINUE_CHECKOUT_PROCEDURE', '<strong>Go to Step 2</strong>');
define('TEXT_CONTINUE_CHECKOUT_PROCEDURE', '- choose your payment method.');

define('NOTICE', 'Notice');
define('MODULE_PAYMENT_EPAYWINDOW_TEXT_PAYMENTTYPE_EWIRE_CONTINUE', 'Click "Continue" to complete your payment with ewire.');
define('MODULE_PAYMENT_EPAYWINDOW_TEXT_PAYMENTTYPE_EWIRE_NOTICE', 'You will be sent to ewire payment window, where you should go ahead and process your payment');

define('MODULE_PAYMENT_EPAYWINDOW_TEXT_PAYMENTTYPE_NETBANK', 'You will be sent to your bank where you have to go ahead and process your payment.');

define('MODULE_PAYMENT_EPAYWINDOW_TEXT_PAYMENTTYPE_CONTINUE','Click "Continue" to proceed with your payment with '); 
define('MODULE_PAYMENT_EPAYWINDOW_TEXT_PAYMENTTYPE_CONTINUE_2', ' as form of payment.'); 

define('MODULE_PAYMENT_EPAYWINDOW_TEXT_PAYMENT_PLEASE_WAIT', 'Please wait while communicating with payment server');

?>
