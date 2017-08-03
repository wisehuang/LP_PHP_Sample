<?php
    
include "LINEPay.php";

$channelId = $_REQUEST["channelID"];
$channelSecret = $_REQUEST["channelSecret"];
$environ = $_REQUEST["environ"];

$LINEPay = new LINEPay($environ, $channelId, $channelSecret);

//// Call Refund API

$txnId = $_REQUEST["refTxnId"];
$refAmt = $_REQUEST["refundAmount"];
$rfArray = array();

// Determine if refundAmount is given
if (!empty($refAmt)) { 
	$rfArray = array(
		"refundAmount" => $refAmt 
		);
}

// display the request which will be sent to LINE Pay server
$LINEPay->showRequest("POST", $rfArray);
// pass everything to LINE Pay, response will be set in $response
$response = $LINEPay->refund($txnId, $rfArray);
// display the response from LINE Pay server
$LINEPay->showResponse($response);

// label redirect back to index
$url = dirname("http://" . $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"]);
echo "<a href=$url>Click to back to index</a>";	

?>