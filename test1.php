<?php

class Paypla {

    var $apiUrl = "https://svcs.sandbox.paypal.com/AdaptivePayments/";
    var $paypalUrl = "https://www.paypal.com/webscr?cmd=_op-payment&paykey="; //send the user here after all the setup 

    // everytime we call this model
    function __construct() {
        $this->headers = array(
            "X-PAYPAL-SECURITY-USERID: ".API_USER,
            "X-PAYPAL-SECURITY-PASSWORD: ".API_PASS,
            "X-PAYPAL-SECURITY-SIGNATURE: ".API_SIG,
            "X-PAYPAL-REQUEST-DATA-FORMAT: JSON",
            "X-PAYPAL-RESPONSE-DATA-FORMAT: JSON",
            "X-PAYPAL-APPLICATION-ID: ".APP_ID,
        );
    }

    //wrapper for getting deatils
    function getPaymentOptions($paykey) {

    }

    //set payment Options 
    function setPaymentOptions() {

    }

    //create Pay call to paypal 
    function createPayRequest() {

    }

    //curl wrapper for sending things to paypal 
    function _paypalSend($data,$call) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl.$call);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);

        return json_decode($curl_exec($ch),TRUE);
    }

    //this is where we do work
    function splitPay() {

        // create the pay request 
        $createPacket = array(
            "actionType" => "PAY",
            "currencyCode" => "USD",
            "receiverList" => array(
                "receiver" => array(
                    "amount" => "1.00",
                    "email" => CHRIS_PAYPAL
                ),
                array(
                    "amount" => "2.00",
                    "email" => "developer@developer.developer"
                )
                ),
                "returnUrl" => "",
                "cancelUrl" => "",
                "requestEnvelope" => array(
                    "errorLanguage" => "es_US",
                    "detailLevel" => "ReturnAll"
                )
        );


        $response = $this->_paypalSend($createPacket, "Pay");
       debug($response);

       //Set payment details



    }

}