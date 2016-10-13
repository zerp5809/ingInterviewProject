<?php
 
	$loader = require __DIR__ . "/vendor/autoload.php";
	$loader->add('GCS', "/lib/");
	$loader->add('GCS', "/src/");
	 
	$merchantid = "2360";
	$base_uri = "https://api-sandbox.globalcollect.com";
	$api_key = "1bc92b529c597d85";
	$api_secret = "zuLdi5ASwvPiFmZ1UYbPn+8BcqEnzg/oN0Ax8Lssp10=";


	$communicatorConfiguration = new GCS_CommunicatorConfiguration($api_key, $api_secret, $base_uri);
	$communicator = new GCS_Communicator(new GCS_DefaultConnection(), $communicatorConfiguration);

	$client = new GCS_Client($communicator);



	 
	$sessionRequest = new GCS_sessions_SessionRequest();
	$response = $client->merchant($merchantid)->sessions()->create($sessionRequest);
	 
	print_r($response);

?>


