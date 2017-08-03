# LINE Pay 自動付款使用說明及範例
LINE Pay 也支援自動付款的使用模式，在使用者完成第一次付款之後，商家便獲得使用者的授權，未來商家僅需使用此授權便能直接完成付款。LINE Pay 用戶不必介入付款流程，也不需要使用 LINE 應用程式，但是在付款完成時會接到通知。適用於每月扣繳的消費模式。

## 目錄
* [使用說明] (#使用說明)
* [regKey 管理與使用] (#regkey-管理與使用)
	* [查看 regKey 狀態] (#查看-regkey-狀態)
	* [使用 regKey 進行自動付款] (#使用-regkey-進行自動付款)
	* [註銷 regKey] (#註銷-regkey)


## 使用說明
**付款請求 - 付款畫面**	<br>
除了付款 Reserve API 的付款類型必須設定為 "PREAPPROVED" 之外，整個流程與一般付款相同。	

**付款畫面 - 付款完成與核發 regKey (用於自動付款)**	
regKey 會隨付款結果傳回，商家必須儲存此金鑰，稍後才能使用自動付款。


## regKey 管理與使用
自動付款的操作均透過系統傳遞的 regKey 金鑰來進行，商家可以透過呼叫 LINE Pay API 進行<br>`使用 regKey 自動付款`、`查看 regKey 狀態`以及`註銷 regKey`的動作。

### 查看 regKey 狀態
使用自動付款 API 前，檢查 regKey 是否存在。


#### 查看 regKey 狀態 API 規格  

  項目 | 說明
  ---- | --- 
  Method | GET
  Required Request Header | `Content-Type:application/json; charset=UTF-8`<br>`X-LINE-ChannelId:{{channelId}}`<br>`X-LINE-ChannelSecret:{{channelSecretKey}}`
  Sandbox 環境 API 地址 | https://sandbox-api-pay.line.me/v2/payments/preapprovedPay/{regKey}/check
  Real 環境 API 地址 | https://api-pay.line.me/v2/payments/preapprovedPay/{regKey}/check

    
#### 查看 regKey 狀態 API 請求可提供的參數  

  名稱 | 資料型別 | 說明
  ---- | ------- | ---
  creditCardAuth | Boolean | 非必要。試圖驗證買家在 regKey 設定之信用卡之最少金額與否


  > 當傳遞 creditCardAuth 的值 
  > * true:
     - 驗證 LINE Pay 內部檔案之有效性
     - 驗證信用卡最少金額付款
     - 在商家後台需開啟「最少金額驗證功能」才可適用，且需要 LINE Pay 管理者之檢驗。
  > * false（預設）:
     - 只驗證 LINE Pay 內部檔案之有效性  


#### 查看 regKey 狀態程式碼範例  

  ``` php
<?php 

include "LINEPay.php"; // 引用LinePay PHP Library

$channelId     = "...";  // 通路ID
$channelSecret = "...";  // 通路密鑰
$environ       = "...";  // 呼叫環境

$LINEPay = new LINEPay($environ, $channelId, $channelSecret); // 建立 LINEPay 物件

//// Call Check regKey API

$regkey = "..."; // 要查詢的 regKey
// 設定 creditCardAuth 的值並包裝成呼叫同名函式的參數（沒有指定預設為false）
$ccAuth = array(
	"creditCardAuth" => "..." 
);

  // 透過建立的 LINEPay 物件，以 regKey、creditCardAuth 參數呼叫同名函式
$response = $LINEPay->checkRegKey($regkey, $ccAuth);

$ack = json_decode($response);
if ($ack->returnCode == "0000") {
//  請求成功!
}

?>
  ```


#### 查看 regKey 狀態 API 回應 (JSON 格式)

``` php
{
  "returnCode": "0000",        // 結果代碼
  "returnMessage": "success"   // 結果訊息或失敗理由
}
```


### 使用 regKey 進行自動付款
附帶付款確認的回應資訊使用 Preapproved Payment API，會使用 regKey 直接完成付款。
#### 自動付款 API 規格  

  項目 | 說明
  ---- | --- 
  Method | POST
  Required Request Header | `Content-Type:application/json; charset=UTF-8`<br>`X-LINE-ChannelId:{{channelId}}`<br>`X-LINE-ChannelSecret:{{channelSecretKey}}`
  Sandbox 環境 API 地址 | https://sandbox-api-pay.line.me/v2/payments/preapprovedPay/{regKey}/payment
  Real 環境 API 地址 | https://api-pay.line.me/v2/payments/preapprovedPay/{regKey}/payment
  
  
#### 自動付款 API 請求的必要參數  

  名稱 | 資料型別 | 說明
  ---- | ------- | ---
  productName | String | 訂單名稱，例如：`商品XXX..等oo項`（單一商品則顯示單項名稱即可）
  amount | Number | 付款金額
  currency | String | 付款貨幣 (ISO 4217)，例如 `TWD`、`JPY`、`USD`、`THB`
  orderId | String | 商家與該筆付款請求對應的訂單編號（這是商家自行管理的唯一編號）

> 如有需要也能設定並傳遞 capture 參數，功能及使用方式同一般付款情況

#### 自動付款 API 請求範例
``` php
{
  "productName" :"LINE Music 每月定期",
  "amount" :10000,
  "currency" :"JPY",
  "orderId" :"testOrd2014121200000001"
}
```

#### 自動付款程式碼範例  

``` php
<?php 
include "LINEPay.php"; // 引用LinePay PHP Library

$channelId     = "...";  // 通路ID
$channelSecret = "...";  // 通路密鑰
$environ       = "...";  // 呼叫環境

$LINEPay = new LINEPay($environ, $channelId, $channelSecret); // 建立 LINEPay 物件

//// Call Preapproved Payment API
$regkey = "..."; // 要進行自動付款的 regKey
$pArray = array( // 建立資訊作為 POST 的參數
	"productName" => "...",
	"amount" => "...",
	"currency" => "...",
	"orderId" => "...",
	"capture" => "..."
);

// 透過建立的 LINEPay 物件，以 regKey 及所需要的參數呼叫同名函式
$response = $LINEPay->preapprovedPayment($regkey, $pArray);

$ack = json_decode($response);
if ($ack->returnCode == "0000") {
//  自動付款請求成功!
}

?>
```


#### 自動付款 API 回應 (JSON 格式)

``` php
{
  "returnCode" :"0000",
  "returnMessage" :"OK",
  "info" :{
    "transactionId" :123123123123,
    "transactionDate" :"2014-01-01T06:17:41Z"
  }
}
```


### 註銷 regKey
註銷為自動付款登錄的 regKey 資訊。一旦呼叫此 API，現有的 regKey 將無法繼續用於自動付款。

#### 註銷 regKey API 規格  

  項目 | 說明
  ---- | --- 
  Method | POST
  Required Request Header | `Content-Type:application/json; charset=UTF-8`<br>`X-LINE-ChannelId:{{channelId}}`<br>`X-LINE-ChannelSecret:{{channelSecretKey}}`
  Sandbox 環境 API 地址 | https://sandbox-api-pay.line.me/v2/payments/preapprovedPay/{regKey}/expire
  Real 環境 API 地址 | https://api-pay.line.me/v2/payments/preapprovedPay/{regKey}/expire
  
  
#### 註銷 regKey 程式碼範例  

  ``` php
<?php 

include "LINEPay.php"; // 引用LinePay PHP Library

$channelId     = "...";  // 通路ID
$channelSecret = "...";  // 通路密鑰
$environ       = "...";  // 呼叫環境

$LINEPay = new LINEPay($environ, $channelId, $channelSecret); // 建立 LINEPay 物件

//// Call Expire regKey API

$regkey = "..."; // 要註銷的 regKey

  // 透過建立的 LINEPay 物件，以 regKey 呼叫同名函式
$response = $LINEPay->expireRegKey($regkey);

$ack = json_decode($response);
if ($ack->returnCode == "0000") {
//  請求成功!
}

?>
  ```


#### 註銷 regKey API 回應 (JSON 格式)

``` php
{
  "returnCode": "0000",        // 結果代碼
  "returnMessage": "success"   // 結果訊息或失敗理由
}
```
