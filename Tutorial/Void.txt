# LINE Pay 授權作廢
使用 Void Authorization API 可以將已授權但未付款的交易作廢。呼叫時必須指定 LINE Pay 用戶的付款交易編號（`transactionId`）。

> 若是要已付款的項目，應使用「退款」而非「授權作廢」

#### Void Authorization API 規格  

項目 | 說明
---- | --- 
Method | POST
Required Request Header | `Content-Type:application/json; charset=UTF-8`<br>`X-LINE-ChannelId:{{channelId}}`<br>`X-LINE-ChannelSecret:{{channelSecretKey}}`
Sandbox 環境 API 地址 | `https://sandbox-api-pay.line.me/v2/payments/authorizations/{{transactionId}}/void`
Real 環境 API 地址 | `https://api-pay.line.me/v2/payments/authorizations/{{transactionId}}/void`


#### 授權作廢程式碼範例  

``` php
<?php 

include "LINEPay.php"; // 引用LinePay PHP Library

$channelId     = "...";  // 通路ID
$channelSecret = "...";  // 通路密鑰
$environ       = "...";  // 呼叫環境

$LINEPay = new LINEPay($environ, $channelId, $channelSecret); // 建立 LINEPay 物件

//// Call VoidAuthorization API

$txnId = "..."; // 要作廢授權的交易編碼

// 以授權作廢 API 要求的交易編碼呼叫同名函式
$response = $LinePay->voidAuthorization($txnId);

$ack = json_decode($response);
if ($ack->returnCode == "0000") {
//  Void Authorization 請求成功!
}
```


#### GetAuthDetail API 回應 (JSON 格式)

``` php
{
  "returnCode": "0000",        // 結果代碼
  "returnMessage": "success"   // 結果訊息或失敗理由
}
```
