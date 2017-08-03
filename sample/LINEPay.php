<?php

header('Content-type: text/html; charset=utf-8');

class LINEPay {
	const API_ENDPOINT_SANDBOX = 'https://sandbox-api-pay.line.me/v2/payments';
	const API_ENDPOINT = 'https://api-pay.line.me/v2/payments';
		
	var $headers;
	var $apiEndpoint;
	var $requestUrl;
	
	function __construct($environ, $channelId, $channelSecret) {
		// Generate header content by channelId & channelSecret
		$this->headers = [
							'Content-Type:application/json; charset=UTF-8',
							'X-LINE-ChannelId:'. $channelId,
							'X-LINE-ChannelSecret:'. $channelSecret
						 ];
		if ($environ == "sandbox") {
			$this->apiEndpoint = self::API_ENDPOINT_SANDBOX;
		}
		else {
			$this->apiEndpoint = self::API_ENDPOINT;
		}
	}
	
	function reserve($data) {
		$url = $this->apiEndpoint . '/request';
		$this->requestUrl = $url;
		return $this->sendByPost($url, $data);
	}
	
	function confirm($transactionId, $data) {
		$url = $this->apiEndpoint . '/' . $transactionId. '/confirm';
		$this->requestUrl = $url;
		return $this->sendByPost($url, $data);
	}
	
	function capture($transactionId, $data) {
		$url = $this->apiEndpoint . '/authorizations/' . $transactionId . '/capture';
		$this->requestUrl = $url;
		return $this->sendByPost($url, $data);
	}
	
	function voidAuthorization($transactionId) {
		$url = $this->apiEndpoint . '/authorizations/'. $transactionId . '/void';
		$this->requestUrl = $url;
		return $this->sendByPost($url, null);
	}
	
	function refund($transactionId, $data) {
		$url = $this->apiEndpoint . '/' . $transactionId . '/refund';
		$this->requestUrl = $url;
		return $this->sendByPost($url, $data);
	}
	
	function preapprovedPayment($regKey, $data) {
		$url = $this->apiEndpoint . '/preapprovedPay/' . $regKey . '/payment';
		$this->requestUrl = $url;
		return $this->sendByPost($url, $data);
	}
	
	function expireRegKey($regKey) {
		$url = $this->apiEndpoint . '/preapprovedPay/' . $regKey . '/expire';
		$this->requestUrl = $url;
		return $this->sendByPost($url, null);
	}
	
	function checkRegKey ($regKey, $ccAuth) {
		$url = $this->apiEndpoint . '/preapprovedPay/' . $regKey . '/check?' . http_build_query($ccAuth);
		$this->requestUrl = $url;
		return $this ->sendByGet($url);
	}
	
	function getAuthDetail($txnIds) {
		$url = $this->apiEndpoint . '/authorizations?' . http_build_query($txnIds);
		$this->requestUrl = $url;
		return $this->sendByGet($url);
	}
	
	function getPaymentDetail($txnIds) {
		$url = $this->apiEndpoint . '?' . http_build_query($txnIds);
		$this->requestUrl = $url;
		return $this->sendByGet($url);
	}
	
	function sendByPost($url, $data) {
		// setting the curl parameters
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_SSLVERSION, 'CURL_SSLVERSION_TLSv1');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
		
		// setting curl to use POST request, and set the data as POST FIELD to curl if data is not empty
		curl_setopt($ch, CURLOPT_POST, 1);
		if (!empty($data)) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		}
		
		return curl_exec($ch);
	}
	
	function sendByGet($url) {
		// setting the curl parameters
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_SSLVERSION, 'CURL_SSLVERSION_TLSv1');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
		
		return curl_exec($ch);
	}
	
	// display Request and make it more readable
	function showRequest($method, $data) {
		echo "Send Request Type: " . $method . "</br>";
		echo "Request Header: " . "<pre>" . json_encode($this->headers,JSON_PRETTY_PRINT) . "</pre>";
		$content = null;
		if (!empty($data)) {
			if (!empty($data["productName"])) {
				$data["productName"] = urlencode($data["productName"]); // 若含中文內容直接json_encode會產生亂碼，先做urlencode轉碼
				$content = urldecode(json_encode($data, JSON_PRETTY_PRINT)); // json_encode後再用urldecode轉回中文顯示
			}
			else {
				$content = json_encode($data,JSON_PRETTY_PRINT);	
			}
		}
		
		echo "Request Content: ". "<pre>" . $content . "</pre>";
	}
	
	// display response and make it more readable
	function showResponse($response) {
		echo "Send Request Url: " . $this->requestUrl . "</br>";
		$res = json_decode($response);
		if (isset($res->info)) {
			if (gettype($res->info) == "array") {
				$res->info[0]->productName = urlencode($res->info[0]->productName); // 中文內容直接json_encode會產生亂碼，先做urlencode轉碼
				$res = urldecode(json_encode($res, JSON_PRETTY_PRINT)); // json_encode後再用urldecode轉回中文顯示
			}
			else {
				$res = json_encode($res, JSON_PRETTY_PRINT);
			}
		}
		else {
			$res = json_encode($res, JSON_PRETTY_PRINT);
		}
				
		echo "Message Return From LINE Pay Server: ". "<pre>" . $res . "</pre>";
	}

}

?>
