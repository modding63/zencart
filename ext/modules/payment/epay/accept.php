<?php
	$acceptParams = $_GET;
	
	$acceptParams["secrethash"] = $_GET["hash"];
	unset($_GET["hash"]);
	unset($acceptParams["hash"]);

	$acceptParams["epaycurrency"] = $_GET["currency"];
	unset($_GET["currency"]);
	unset($acceptParams["hash"]);

	$url = $_GET["accepturl"];
	unset($_GET["accepturl"]);
	unset($acceptParams["accepturl"]);
	
	$queryString = http_build_query($acceptParams);
	
	$query = parse_url($url, PHP_URL_QUERY);

	if($query) {
	    $url .= '&' . $queryString;
	}
	else {
	    $url .= '?' . $queryString;
	}
	
	chdir('../../../../');
	require('includes/application_top.php');

	//Empty cart
	unset($_SESSION["cart"]);

	header("Location: " . $url);
?>