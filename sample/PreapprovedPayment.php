<?php
    
include "LINEPay.php";

$channelId = $_REQUEST["channelID"];
$channelSecret = $_REQUEST["channelSecret"];
$environ = $_REQUEST["environ"];

$LINEPay = new LINEPay($environ, $channelId, $channelSecret);

//// Call Preapproved Payment API

$regkey = $_REQUEST["regKey"];
$pArray = array(
	"productName" => $_REQUEST["pNAME"],
	"amount" => $_REQUEST["pAMT"],
	"currency" => "TWD",
	"orderId" => $_REQUEST["pOrderId"],
	"capture" => $_REQUEST["pCapture"]
);

// display the request which will be sent to LINE Pay server
$LINEPay->showRequest("POST", $pArray);
// pass everything to LINE Pay, response will be set in $response
$response = $LINEPay->preapprovedPayment($regkey, $pArray);
// display the response from LINE Pay server
$LINEPay->showResponse($response);

// label redirect back to index
$url = dirname("http://" . $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"]);
echo "<a href=$url>Click to back to index</a>";
?>