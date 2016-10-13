<!DOCTYPE HTML>  
<html>
	<head>
	</head>
	<body>

		<?php

			$loader = require __DIR__ . "/vendor/autoload.php";
			$loader->add('GCS', "/lib/");
			$loader->add('GCS', "/src/");

			 
			//merchant stuff
			$merchantid = "2360";
			$base_uri = "https://api-sandbox.globalcollect.com";
			$api_key = "1bc92b529c597d85";
			$api_secret = "zuLdi5ASwvPiFmZ1UYbPn+8BcqEnzg/oN0Ax8Lssp10=";
			 
			$communicatorConfiguration = new GCS_CommunicatorConfiguration($api_key, $api_secret, $base_uri);
			$communicator = new GCS_Communicator(new GCS_DefaultConnection(), $communicatorConfiguration);
			$client = new GCS_Client($communicator);

		 

		?>


		<h1>SDK Payment Request</h1>
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
			Current Country Code: US
			<input type="submit" name="submit" value="Submit">  
		</form>

		<?php
			if(isset($_POST['submit'])) {
			$cvv = $_POST['cvv'];
			$cardNumber = $_POST['cardNumber'];
			$expiryDate = $_POST['expiryDate'];
			$cost = $_POST['cost'];
			// Create a new order

			$order = new GCS_payment_definitions_Order();

			$billingAddress = new GCS_fei_definitions_Address();
			$billingAddress->countryCode = "US";

			$customer = new GCS_payment_definitions_Customer();
			$customer->languageCode = "en";
			$customer->billingAddress = $billingAddress;
			$order->customer = $customer;

			$amountOfMoney = new GCS_fei_definitions_AmountOfMoney();
			$amountOfMoney->amount = $cost;
			$amountOfMoney->currencyCode = "EUR";
			$order->amountOfMoney = $amountOfMoney;

			$card = new GCS_fei_definitions_Card();
			//$card->cvv = '123';
			//$card->cardNumber = '4567350000427977';
			//$card->expiryDate = '1220';
			$card->cvv = $cvv;
			$card->cardNumber = $cardNumber;
			$card->expiryDate = $expiryDate;

			$cardPaymentMethodSpecificInput = new GCS_payment_definitions_CardPaymentMethodSpecificInput();
			$cardPaymentMethodSpecificInput->paymentProductId = 1;
			$cardPaymentMethodSpecificInput->card = $card;

			$body = new GCS_payment_CreatePaymentRequest();
			$body->order = $order;
			$body->cardPaymentMethodSpecificInput = $cardPaymentMethodSpecificInput;

			$response = $client->merchant($merchantid)->payments()->create($body);
			print_r($response);
			echo($response);
			return $response;
			}
		?>
	</body>
</html>

