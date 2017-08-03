<?php

include "LINEPay.php";

$channelId = $_REQUEST["channelID"];
$channelSecret = $_REQUEST["channelSecret"];
$environ = $_REQUEST["environ"];

$LINEPay = new LINEPay($environ, $channelId, $channelSecret);

//// Call Get Payment Detail API

$txnIds = array(
	"transactionId" => $_REQUEST["gpdTranId"]
);

// display the request which will be sent to LINE Pay server
$LINEPay->showRequest("GET", $txnIds);
// pass everything to LINE Pay, response will be set in $response
$response = $LINEPay->getPaymentDetail($txnIds);
// display the response from LINE Pay server
$LINEPay->showResponse($response);

// label redirect back to index
$url = dirname("http://" . $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"]);
echo "<a href=$url>Click to back to index</a>";

?>