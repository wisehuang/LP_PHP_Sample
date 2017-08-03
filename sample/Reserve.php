<?php
//use include to insert the external file into script
include "LINEPay.php";

$channelId = $_REQUEST["channelID"];
$channelSecret = $_REQUEST["channelSecret"];
$environ = $_REQUEST["environ"];

session_start();

// construct a new LINEPay instance
$LINEPay = new LINEPay($environ, $channelId, $channelSecret);

//// Call Reserve API
$amt0 = $_REQUEST["AMT0"];
$amt1 = $_REQUEST["AMT1"];
$qty0 = $_REQUEST["QTY0"];
$qty1 = $_REQUEST["QTY1"];
$shipAmt = $_REQUEST["shippingAmt"];
$amt = $amt0 * $qty0 + $amt1 * $qty1 +$shipAmt;

// display some text if buy more than 1 product
$count = $qty0 + $qty1;	
$count==1? $productName = $_REQUEST["NAME0"]: $productName = $_REQUEST["NAME0"]. "...等兩項";

$_SESSION["amt"] = $amt;
$_SESSION["channelID"] = $channelId;
$_SESSION["channelSecret"] = $channelSecret;
$_SESSION["environ"] = $environ;

$serverName = $_SERVER["SERVER_NAME"];
$serverPort = $_SERVER["SERVER_PORT"];
$url = dirname("https://" . $serverName . ":" . $serverPort . $_SERVER["REQUEST_URI"]);
// confirmURL  (After confirm payment in LINE Pay will return to this URL)
$confirmURL = $url . "/Confirm.php";
// cancelURL  (If cancel payment from LINE Pay checkout page, will return to this URL)
$cancelURL = $url;

$rArray = array(
        "productName" => $productName,
        "productImageUrl" => $_REQUEST["logo"],
        "amount" => $amt,
        "currency" => "TWD",
        "confirmUrl" => $confirmURL,
        "cancelUrl" => $cancelURL,
        "orderId" => $_REQUEST["orderId"],
        "confirmUrlType" => $_REQUEST["confirmUrlType"],
        "checkConfirmUrlBrowser" => $_REQUEST["checkConfirmUrlBrowser"],
        "payType" => $_REQUEST["payType"],
        "capture" => $_REQUEST["capture"]
		);
		
// display the request which will be sent to LINE Pay server
$LINEPay->showRequest("POST", $rArray);
// pass everything to LINE Pay, response will be set in $response
$response = $LINEPay->reserve($rArray);
// display the response from LINE Pay server
$LINEPay->showResponse($response);

// show the label for next step if it success
$ack = json_decode($response);
if ($ack->returnCode == "0000") {
	// redirect to buyer auth process if success
	$paymentURL = $ack->info->paymentUrl->web; 

	echo "<a href=$paymentURL>Click to next step</a>";
} 

?>
