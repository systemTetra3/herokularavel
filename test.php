<?php
namespace Payment;
use Omnipay\Omnipay;
class Payment
{
   /**
    * @return mixed
    */
   public function gateway()
   {
       $gateway = Omnipay::create('PayPal_Express');
       $gateway->setUsername("sb-7j4hl606677@personal.example.com");
       $gateway->setPassword("ARySNgUCvyU9tEBp-zsd0WbbNO_7Nxxxxoi3xxxxh2cTuDxRh7xxxxVu9W5ZkIBGYqjqfzHrjY3wta");
       $gateway->setSignature("EOEwezsNWMWQM63xxxxxknr8QLoAOoC6lD_-kFqjgKxxxxxwGWIvsJO6vP3syd10xspKbx7LgurYNt9");
       $gateway->setTestMode(true);
       return $gateway;
   }
   /**
    * @param array $parameters
    * @return mixed
    */
   public function purchase(array $parameters)
   {
       $response = $this->gateway()
           ->purchase($parameters)
           ->send();
       return $response;
   }
   /**
    * @param array $parameters
    */
   public function complete(array $parameters)
   {
       $response = $this->gateway()
           ->completePurchase($parameters)
           ->send();
       return $response;
   }
   /**
    * @param $amount
    */
   public function formatAmount($amount)
   {
       return number_format($amount, 2, '.', '');
   }
   /**
    * @param $order
    */
   public function getCancelUrl($order = "")
   {
       return $this->route('http://phpstack-275615-1077014.cloudwaysapps.com/cancel.php', $order);
   }
   /**
    * @param $order
    */
   public function getReturnUrl($order = "")
   {
       return $this->route('http://phpstack-275615-1077014.cloudwaysapps.com/return.php', $order);
   }
   public function route($name, $params)
   {
       return $name; // ya change hua hai
   }
}
?>
<?php

// include "vendor/autoload.php";
// include "src/Payment/payment.php";

use Payment\Payment;
$payment = new Payment;

// ?>

<!DOCTYPE html>
<html lang="en">


<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pay with PayPal</title>

    <!-- Latest compiled and minified CSS -->

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
        integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">


    <!-- Optional theme -->

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"
        integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous">
    </script>

</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-6">

                <form class="form-horizontal" method="POST" action="https://www.sandbox.PayPal.com/cgi-bin/webscr ">
                    <fieldset>

                        <!-- Form Name -->
                        <legend>Pay with PayPal</legend>
                        <!-- Text input-->

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="amount">Payment Amount</label>
                            <div class="col-md-4">
                                <input id="amount" name="amount" type="text" placeholder="amount to pay"
                                    class="form-control input-md" required="">
                                <span class="help-block">help</span>
                            </div>
                        </div>

                        <input type='hidden' name='business' value='sb-7j4hl606677@personal.example.com'>
                        <input type='hidden' name='item_name' value='Camera'>
                        <input type='hidden' name='item_number' value='CAM#N1'>
                        <!--<input type='hidden' name='amount' value='10'>-->
                        <input type='hidden' name='no_shipping' value='1'>
                        <input type='hidden' name='currency_code' value='USD'>
                        <input type='hidden' name='notify_url' value='<?php echo $payment->route("notify", "") ?>'>
                        <input type='hidden' name='cancel_return'
                            value='<?php echo $payment->route("http://phpstack-275615-1077014.cloudwaysapps.com/cancel.php", "") ?>'>
                        <input type='hidden' name='return'
                            value='<?php echo $payment->route("return", "http://phpstack-275615-1077014.cloudwaysapps.com/return.php") ?>'>
                        <input type="hidden" name="cmd" value="_xclick">

                        <!-- Button -->

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="submit"></label>
                            <div class="col-md-4">
                                <button id="submit" name="pay_now" class="btn btn-danger">Pay With PayPal</button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</body>


</html>

















<!-- {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);
        // dd($request->all());

        $clientId = "env('PAYPAL_CLIENT_ID')";
        $secret = "env('PAYPAL_CLIENT_SECRET')";
        $postdata = '{
            "intent": "CAPTURE",
            "purchase_units": [
              {
                "amount": {
                  "currency_code": "USD",
                  "value": "100.00"
                }
              }
            ]
          }';
          //checkout order
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => 'https://api-m.sandbox.paypal.com/v2/checkout/orders',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer <AT6zZ-m_3p1Nit3ZH--kvc5c9NW0U8iGVRYgcfLgy3RxbSD-Ho5zH5TBcBMb2wGHOCM5vY32tXFJHcrK>:<ECu7x9NB5K3FHvt_1y1tLMeJjm76vxZs8uW1pk_L8ncHwfg9tnkJb7bOb1DFEKjCPlzDNJQJtQPBlSsS>',
                'Content-Length: ' . strlen($postdata)
            ),
        ));

        $result = curl_exec($ch);
        echo "<h2>Checkout the order</h2><br>";
        var_dump($result, curl_errno($ch), curl_error($ch));
        echo "<br>";
        //authorize payment
        $ch1 = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => 'https://api-m.paypal.com/v2/checkout/orders/5O190127TN364715T/authorize',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'PayPal-Partner-Attribution-Id:&lt;BN-Code&gt',
                'Authorization: Bearer <AT6zZ-m_3p1Nit3ZH--kvc5c9NW0U8iGVRYgcfLgy3RxbSD-Ho5zH5TBcBMb2wGHOCM5vY32tXFJHcrK>:<ECu7x9NB5K3FHvt_1y1tLMeJjm76vxZs8uW1pk_L8ncHwfg9tnkJb7bOb1DFEKjCPlzDNJQJtQPBlSsS>',
                'Content-Length: ' . strlen($postdata)
            ),
        ));

        $result1 = curl_exec($ch1);
        echo "<h2>Authorize the order </h2><br>";
        var_dump($result1, curl_errno($ch1), curl_error($ch1));
        echo "<br>";


        //capture an authentication

    }












































    $data = '{
                "intent":"authorize",
                "redirect_urls":{
                  "return_url":"http://localhost/sbm/public/home",
                  "cancel_url":"http://localhost/sbm/public/home"
                },
                "payer":{
                  "payment_method":"paypal"
                },
                "transactions":[
                  {
                    "amount":{
                      "total":"7.47",
                      "currency":"USD"
                    },
                    "description":"This is the payment transaction description."
                  }
                ]
              }';
                $clientId = "env('PAYPAL_CLIENT_ID')";
                $secret = "env('PAYPAL_CLIENT_SECRET')";
            curl_setopt($ch, CURLOPT_URL, "https://api.sandbox.paypal.com/v1/payments/payment");
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, true); //send via post
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
            curl_setopt($ch, CURLOPT_USERPWD, 'AT6zZ-m_3p1Nit3ZH--kvc5c9NW0U8iGVRYgcfLgy3RxbSD-Ho5zH5TBcBMb2wGHOCM5vY32tXFJHcrK:ECu7x9NB5K3FHvt_1y1tLMeJjm76vxZs8uW1pk_L8ncHwfg9tnkJb7bOb1DFEKjCPlzDNJQJtQPBlSsS');
            curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
            $result = curl_exec($ch);
            curl_close($ch);      
            echo "<pre>";
            print_r($result);
            exit; -->
