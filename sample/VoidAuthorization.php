<?php
    
include "LINEPay.php";

$channelId = $_REQUEST["channelID"];
$channelSecret = $_REQUEST["channelSecret"];
$environ = $_REQUEST["environ"];

$LINEPay = new LINEPay($environ, $channelId, $channelSecret);

//// Call Void Authorization API

$txnId = $_REQUEST["vaTxnId"];
// display the request which will be sent to LINE Pay server
$LINEPay->showRequest("POST", null);
// pass everything to LINE Pay, response will be set in $response
$response = $LINEPay->voidAuthorization($txnId);
// display the response from LINE Pay server
$LINEPay->showResponse($response);

// label redirect back to index
$url = dirname("http://" . $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"]);
echo "<a href=$url>Click to back to index</a>";	
?>