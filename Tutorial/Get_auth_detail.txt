# LINE Pay 查看授權紀錄
使用 Get Authorization Detail API 可以取得已授權但尚未付款的項目詳細資料。查看時必須指定 LINE Pay 用戶的交易編號（transactionId）或是商家自行管理的訂單編號（orderId）。兩者擇一即可，但**同時只能選擇一種查詢參數**

若要一次查看多筆授權記錄，可同時傳遞多個參數，最多可以同時查看 100 筆記錄。

> 若是要查看已授權且已付款的項目記錄，應使用「查看付款紀錄」而非「查看授權紀錄」。<br>
> 以下流程僅使用交易編號（transactionId）進行記錄查詢為例，也建議商家均採用交易編號進行查詢。

#### GetAuthDetail API 規格  

項目 | 說明
---- | --- 
Method | GET
Required Request Header | `Content-Type:application/json; charset=UTF-8`<br>`X-LINE-ChannelId:{{channelId}}`<br>`X-LINE-ChannelSecret:{{channelSecretKey}}`
Sandbox 環境 API 地址 | `https://sandbox-api-pay.line.me/v2/payments/authorizations`
Real 環境 API 地址 | `https://api-pay.line.me/v2/payments/authorizations`

#### GetAuthDetail API 請求的參數  

名稱 | 資料型別 | 說明
---- | ------- | ---
transactionId | Number | 由 LINE Pay 核發的交易編號，用於付款或退款的交易編號
orderId | String | 商家的訂單編號

> 若要查看多筆可直接傳遞多組 transactionId / orderId 作為參數。

#### 查看授權記錄程式碼範例  

``` php
<?php 

include "LINEPay.php"; // 引用LinePay PHP Library

$channelId     = "...";  // 通路ID
$channelSecret = "...";  // 通路密鑰
$environ       = "...";  // 呼叫環境

$LINEPay = new LINEPay($environ, $channelId, $channelSecret); // 建立 LINEPay 物件

//// Call Get Authorization Detail API

$txnIds = array(
	"transactionId" => "..."　// 查詢多筆則使用另一筆 "transactionId" => "..." 進行宣告
);

// 呼叫同名函式發送請求
$response = $LinePay->getAuthDetail($txnIds);

$ack = json_decode($response);
if ($ack->returnCode == "0000") {
//  Get Authorization Detail 請求成功!
}
```

#### GetAuthDetail API 回應 (JSON 格式)

``` php
{
  "returnCode": "0000",        // 結果代碼，例如 `0000` 表示成功
  "returnMessage": "success",  // 結果訊息或失敗理由
  "info":[
    {
      "transactionId": ...,         // 交易編號 (19 位數)
      "transactionDate": "...",     // 交易日期與時間 (ISO 8601)
      "transactionType": "...",     // 交易類型（PAYMENT:付款、PAYMENT_REFUND:退款、PARTIAL_REFUND:部分退款）
      "payInfo":[
        {
          "method": "CREDIT_CARD",  // 付款方式（信用卡：CREDIT_CARD、餘額：BALANCE、折扣: DISCOUNT）　 
          "amount": 10              // 交易金額
        } 
      ],
      "productName": "...",         // 產品名稱
      "currency": "TWD",            // 貨幣
      "orderId": "...",             // 商家的訂單編號
      "payStatus" :"AUTHORIZATION", // 付款狀態（AUTHORIZATION:已授權、VOIDED_AUTHORIZATION:授權已作廢、
                                    //           EXPIRED_AUTHORIZATION:過期的授權）
      "authorizationExpireDate" :"2014-07-28T09:48:43Z" // 授權過期日期與時間 (ISO 8601)
    }
  ]
}
```
