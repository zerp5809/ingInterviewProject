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
        	
        $approvePaymentRequest = new GCS_payment_ApprovePaymentRequest();

        $directDebitPaymentMethodSpecificInput = new GCS_payment_definitions_ApprovePaymentNonSepaDirectDebitPaymentMethodSpecificInput();
        $directDebitPaymentMethodSpecificInput->dateCollect = "20161009";
        $directDebitPaymentMethodSpecificInput->token = "bfa8a7e4-4530-455a-858d-204ba2afb77e";
        $approvePaymentRequest->directDebitPaymentMethodSpecificInput = $directDebitPaymentMethodSpecificInput;

        $orderApprovePayment = new GCS_payment_definitions_OrderApprovePayment();
        $orderReferencesApprovePayment = new GCS_payment_definitions_OrderReferencesApprovePayment();
        //$orderReferencesApprovePayment->merchantReference = "AcmeOrder0001";
        $orderApprovePayment->references = $orderReferencesApprovePayment;


        $approvePaymentRequest->amount = 2980;
        $approvePaymentRequest->order = $orderApprovePayment;
        /** @var GCS_payment_PaymentApprovalResponse $paymentApprovalResponse */
        $paymentId='000000236000000000030000100001';
        $paymentApprovalResponse = $client->merchant($merchantid)->payments()->approve($paymentId, $approvePaymentRequest);

        print_r($paymentApprovalResponse);

?>
