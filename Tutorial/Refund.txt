# LINE Pay 退款
使用 Refund API 可以將已付款完成的項目進行退款。退款時必須指定 LINE Pay 用戶的交易編號（transactionId）。除此之外，退款時可以指定退款的金額，**若未指定退款金額則視為全額退款**。

若退款成功， LIEN Pay 伺服器會回傳新的交易編號`refundTransactionId`（19 位數），以及退款的交易日期/時間`refundTransactionDate`（`2014-01-01T06:17:41Z`）

> 若是要取消已授權但未付款的項目，應使用「授權作廢」而非「退款」

#### Refund API 規格  

項目 | 說明
---- | --- 
Method | POST
Required Request Header | `Content-Type:application/json; charset=UTF-8`<br>`X-LINE-ChannelId:{{channelId}}`<br>`X-LINE-ChannelSecret:{{channelSecretKey}}`
Sandbox 環境 API 地址 | `https://sandbox-api-pay.line.me/v2/payments/{{transactionId}}/refund`
Real 環境 API 地址 | `https://api-pay.line.me/v2/payments/{{transactionId}}/refund`


#### Refund API 請求的參數  

名稱 | 資料型別 | 說明
---- | ------- | ---
refundAmount | Number | 退款金額。非必要，如果未傳遞此參數，則全額退款


#### Refund API 請求範例
``` php
// 若部份退款時
{
  "refundAmount" : 1000
}

// 若全額退款時
{}
```


#### 退款程式碼範例  

``` php
<?php 
include "LINEPay.php"; // 引用LinePay PHP Library

$channelId     = "...";  // 通路ID
$channelSecret = "...";  // 通路密鑰
$environ       = "...";  // 呼叫環境

$LINEPay = new LINEPay($environ, $channelId, $channelSecret); // 建立 LINEPay 物件

//// Call Refund API

$txnId = "..."; // 要退款的交易編碼
$refAmt = "..."; // 要退款的金額
$rfArray = array(); 
// 如果有指定金額，依照退款 API 指定的參數名稱包裝成呼叫函式的參數 (若無指定則全額退費)
if (!empty($refAmt)) { 
    $rfArray = array(
        "refundAmount" => $refAmt 
        );
}

// 透過建立的 LINEPay 物件，以退款 API 要求的交易編碼、退款金額參數呼叫同名函式
$response = $LINEPay->refund($txnId, $rfArray);

$ack = json_decode($response);
if ($ack->returnCode == "0000") {
//  Refund 請求成功!
}
```


#### Refund API 回應 (JSON 格式)

``` php
{
  "returnCode": "0000",        // 結果代碼
  "returnMessage": "success",  // 結果訊息或失敗理由
  "info": {
    "refundTransactionId": ...,      // 退款的交易編號 (新核發的編號 - 19 位數)
    "refundTransactionDate": "...",  // 退款的交易日期與時間 (ISO 8601)
  }
}
```
