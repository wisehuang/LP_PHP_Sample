<?php
    
include "LINEPay.php";

$channelId = $_REQUEST["channelID"];
$channelSecret = $_REQUEST["channelSecret"];
$environ = $_REQUEST["environ"];

$LINEPay = new LINEPay($environ, $channelId, $channelSecret);

//// Call Capture API

$txnId = $_REQUEST["cTxnId"];	
$captureArray = array(
	"amount" => $_REQUEST["captureAmount"],
	"currency" => "TWD"
);

// display the request which will be sent to LINE Pay server
$LINEPay->showRequest("POST", $captureArray);
// pass everything to LINE Pay, response will be set in $response
$response = $LINEPay->capture($txnId, $captureArray);
// display the response from LINE Pay server
$LINEPay->showResponse($response);

// label redirect back to index
$url = dirname("http://" . $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"]);
echo "<a href=$url>Click to back to index</a>";
	
?>