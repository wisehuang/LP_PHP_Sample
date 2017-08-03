# LINE Pay PHP Tutorial

### 準備
  **必須要有 LINE Pay 的商家帳戶**  
      申請方式請參考官方文件 - [LINE Pay 商家註冊指南](https://pay.line.me/tw/intro/techSupport)  

  **必須要有通路ID (ChannelId)和通路密鑰 (ChannelSecret)**  
      使用商家帳戶登入 LINE Pay 後台，即可取得通路ID和通路密鑰，兩者都是用來建立 API 請求的 Header

  **必須設定伺服器白名單**  
      要發送請求給 LINE Pay 伺服器的伺服器（例如你的開發環境，或佈署購物網站的伺服器），其 IP 位置都必須記錄在商家帳戶的伺服器白名單內，否則發出的請求會被 LINE Pay 拒絕。特別注意 Sandbox 和正式環境須分開設定。

### 教學文章
* [使用PHP Library for LINE Pay API (LINEPay.php，使用 php_curl 實作)] (/Tutorial/LibraryUsage.txt)
* [一般付款 (Reserve & Confirm)] (/Tutorial/Payment.txt)
* [付款記錄查詢] (/Tutorial/Get_payment_detail.txt)
* [退款 (Refund)] (Tutorial/Refund.txt)
* [授權紀錄查詢] (/Tutorial/Get_auth_detail.txt)
* [授權作廢] (Tutorial/Void.txt)
* [自動付款] (Tutorial/Preapproved_payment.txt)
