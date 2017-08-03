<?php

include "LINEPay.php";

// get header and environ info from Session
session_start();
$channelId = $_SESSION["channelID"];
$channelSecret = $_SESSION["channelSecret"];
$environ = $_SESSION["environ"];

$LINEPay = new LINEPay($environ, $channelId, $channelSecret);

//// Call Confirm API

$txnId = $_REQUEST["transactionId"];
$cArray = array(
		"amount" => $_SESSION["amt"],
		"currency" => "TWD"
		);
		
// display the request which will be sent to LINE Pay server
$LINEPay->showRequest("POST", $cArray);
// pass everything to LINE Pay, response will be set in $response
$response = $LINEPay->confirm($txnId, $cArray);
// display the response from LINE Pay server
$LINEPay->showResponse($response);

// label redirect back to index
$url = dirname("http://" . $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"]);
echo "<a href=$url>Click to back to index</a>";

?>
