<!DOCTYPE HTML>  
<html>
	<head>
	</head>
	<body>  

		<?php
			class formatHeaders{
			protected $merchantid = "2360";
			protected $base_uri = "https://api-sandbox.globalcollect.com";
			protected $api_key = '1bc92b529c597d85';
			protected $api_secret = 'zuLdi5ASwvPiFmZ1UYbPn+8BcqEnzg/oN0Ax8Lssp10=';
			
			
			
			public function format($body, $apiType){
				//$requestText = "Host: api-sandbox.globalcollect.com\nContent-Type: application/json\nDate: " . $formattedDate . "\nX-GCS-ServerMetaInfo:" . getServerMetaInfoValue();
				$uriPath = '/v1/2360/' . $apiType;
				$headerInfo = array('Content-Type' => 'application/json', 'Date' => $this->getDate(), 'X-GCS-ServerMetaInfo' => $this->getServerInfo());
				$formatData = 'POST' . "\n" . 'application/json' . "\n" .  $this->getDate() . "\n" . 'x-gcs-servermetainfo:' . $this->getServerInfo() . "\n" . $uriPath . "\n";
				$headerInfo['Authorization'] = $this->authHeader($formatData);
				$rawHeader = array('Content-Type: ' . 'application/json', 'Date: ' . $this->getDate(), 'X-GCS-ServerMetaInfo: ' . $this->getServerInfo(), 'Authorization: ' . $headerInfo['Authorization']);
				//print_r("rawHeader: " . implode(", ", $rawHeader) . "\n");
				//print_r("Authorization: " . $headerInfo['Authorization'] . "\n");
				$this->post($rawHeader,$uriPath, $body);
			}
			public function authHeader($headerInfo){
				//$j = base64_encode(hash_hmac('sha256', $this->sign($headerInfo), $this->api_secret,true));
				$authHead = 'GCS v1HMAC:' . $this->api_key . ":" .base64_encode(hash_hmac('sha256', $headerInfo, $this->api_secret,true));
				//print_r($headerInfo);
				//print_r($authHead);
				return $authHead;
			}
			public function payment($cvv, $cardNumber, $expiryDate, $amountOfMoney, $currencyCode, $countryCode){
				$paymentBody = array('cardPaymentMethodSpecificInput' => array('card' => array('cvv' => $cvv, 'cardNumber' => $cardNumber, 'expiryDate' => $expiryDate), 'paymentProductId' => 1), 'order' => array('amountOfMoney' => array('amount' => $amountOfMoney, 'currencyCode' => $currencyCode), 'customer' => array('billingAddress' => array('countryCode' => $countryCode), "languageCode" => 'en'))); 
				//print_r(json_encode($paymentBody));
				$this->format(json_encode($paymentBody) , "payments");
			}
			protected function getDate()
			{
			    $dateTime = new DateTime('now');
			    return $dateTime->format('D, d M Y H:i:s T');
			}
			public function getServerInfo()
			{
			    $serverMetaInfo = array('platformIdentifier' => sprintf('%s; php version %s', php_uname(), phpversion()), 'sdkIdentifier' => 'v1.0');
			    //print_r($serverMetaInfo);
			    return base64_encode(json_encode($serverMetaInfo));
			}
			/*public function sign($headerinfo){
				$signData = 'POST' . "\n";
			        if (isset($headerinfo['Content-Type'])) {
			            $signData .= $headerinfo['Content-Type'] . "\n";
			        } else {
			            $signData .= "\n";
			        }
			        if (isset($headerinfo['Date'])) {
			            $signData .= $headerinfo['Date'] . "\n";
			        } else {
			            $signData .= "\n";
			        }
			       //server meta data
			        $signData .= 'x-gcs-servermetainfo:' . $this->getServerInfo() . "\n";
			       //otherheaders

			       //this will be /v1/id/sessons
			       $signData .= $this->uriPath . "\n";
			       print_r($signData);
			       return $signData;
			}*/
			public function post($postData, $uripath, $body){
				$url = $this->base_uri . $uripath;
				print_r("url: " . $url . "\n");
				$curl= curl_init();
				curl_setopt($curl, CURLOPT_HEADER, true);
		        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
		        curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
				curl_setopt($curl, CURLOPT_HTTPHEADER, $postData);
				$response = curl_exec($curl); 
				$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
				print_r("error: " . curl_error($curl) . "\n");
				
				print_r("response: " . $response . "\n");
				return $response;
			}
		}
		//$t = new formatHeaders();
		//$t->format("{}", "sessions");
		//$t -> payment('123', '4567350000427977', '1220', 2980, 'EUR', 'US')




		?>
		<h1>NoSDK Payment Request</h1>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
			Card Number: <input type="text" name="cardNumber" value="<?php echo $cardNumber;?>">
			<br><br>
			cvv: <input type="text" name="cvv" value="<?php echo $cvv;?>">
			<br><br>
			Expiry Date: <input type="text" name="expiryDate" value="<?php echo $expiryDate;?>">
			<br><br>
			Total Cost: <input type="text" name="cost" value="<?php echo $cost;?>">
			<br><br>
			Currency Code: EUR
			<br><br>
			Payment Type: Visa
			<br><br>
			Current Country Code: US
			<input type="submit" name="submit" value="Submit">  
		</form>

		<?php
			if(isset($_POST['submit'])) {
				$cvv = $_POST['cvv'];
				$cardNumber = $_POST['cardNumber'];
				$expiryDate = $_POST['expiryDate'];
				$cost = $_POST['cost'];
				$t = new formatHeaders();
				$t -> payment($cvv, $cardNumber, $expiryDate, $cost, 'EUR', 'US');
			}
		?>
	</body>
</html>