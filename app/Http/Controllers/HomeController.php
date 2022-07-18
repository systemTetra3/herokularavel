<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\CurrentDeviceLogout;
use Illuminate\Http\Request;
use Laravel\Ui\Presets\React;
use Illuminate\Support\Facades\Redirect;
use PhpParser\Node\Expr\FuncCall;
use ReturnTypeWillChange;
use App\Models\PaypalPayment;
use App\Models\paypalDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return redirect('/');
        $user_id = Auth::id();
        if (auth()->user()->stripe_customer_id == null) {
            $data['cards'] = [];
            $data['acss_debits'] = [];
            $data['paypal'] = PaypalPayment::where('user_id', $user_id)->latest()->paginate(10);
            $data['connect'] = paypalDetail::latest()->paginate(10);
            // dd($data['connect']);
            return view('home', $data);
        }
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        $data['cards'] = $stripe->paymentMethods->all(
            ['customer' => auth()->user()->stripe_customer_id, 'type' => 'card']
        )->data;
        $data['acss_debits'] = $stripe->paymentMethods->all(
            ['customer' => auth()->user()->stripe_customer_id, 'type' => 'acss_debit']
        )->data;
        $data['paypal'] = PaypalPayment::where('user_id', $user_id)->latest()->paginate(10);
        $data['connect'] = paypalDetail::latest()->paginate(10);
        return view('home', $data);
    }

    public function add_card_blade()
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $customer_id = auth()->user()->stripe_customer_id;
        if (!$customer_id) {
            $customer = \Stripe\Customer::create([
                'email' => auth()->user()->email,
            ]);
            auth()->user()->stripe_customer_id = $customer->id;
            auth()->user()->save();
            $customer_id = $customer->id;
        }

        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        $data['client_secret'] = $stripe->setupIntents->create(
            [
                'customer' => auth()->user()->stripe_customer_id,
                'payment_method_types' => ['card'],
            ]
        )->client_secret;
        return view('add-card', $data);
    }

    public function save_card(Request $request)
    {
        // \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        // $customer_id = auth()->user()->stripe_customer_id;
        // if (!$customer_id) {
        //     $customer = \Stripe\Customer::create([
        //         'email' => auth()->user()->email,
        //     ]);
        //     auth()->user()->stripe_customer_id = $customer->id;
        //     auth()->user()->save();
        //     $customer_id = $customer->id;
        // }
        // $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'),);
        // $card_response = $stripe->customers->createSource(
        //     $customer_id,
        //     [
        //         'source' => $request->token
        //     ]
        // );
        // auth()->user()->stripe_cards()->create([
        //     'card_id' => $card_response->id,
        //     'brand' => $card_response->brand,
        //     'last4' => $card_response->last4,
        //     'exp_month' => $card_response->exp_month,
        //     'exp_year' => $card_response->exp_year,
        //     'funding' => $card_response->funding,
        // ]);

        return redirect()->route('home')->with('success', 'Card added successfully');
    }
    public function stripecards()
    {
        $user_id = Auth::id();
        if (auth()->user()->stripe_customer_id == null) {
            $data['cards'] = [];
            $data['acss_debits'] = [];
            $data['paypal'] = PaypalPayment::where('user_id', $user_id)->latest()->paginate(10);
            $data['connect'] = paypalDetail::latest()->paginate(10);
            // dd($data['connect']);
            return view('saved-stripe-cards', $data);
        }
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        $data['cards'] = $stripe->paymentMethods->all(
            ['customer' => auth()->user()->stripe_customer_id, 'type' => 'card']
        )->data;
        $data['acss_debits'] = $stripe->paymentMethods->all(
            ['customer' => auth()->user()->stripe_customer_id, 'type' => 'acss_debit']
        )->data;
        $data['paypal'] = PaypalPayment::where('user_id', $user_id)->latest()->paginate(10);
        $data['connect'] = paypalDetail::latest()->paginate(10);
        return view('saved-stripe-cards', $data);
    }
    public function saved_banks_list()
    {
        $user_id = Auth::id();
        if (auth()->user()->stripe_customer_id == null) {
            $data['acss_debits'] = [];
            // dd($data['connect']);
            return view('saved-banks', $data);
        }
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        $data['cards'] = $stripe->paymentMethods->all(
            ['customer' => auth()->user()->stripe_customer_id, 'type' => 'card']
        )->data;
        $data['acss_debits'] = $stripe->paymentMethods->all(
            ['customer' => auth()->user()->stripe_customer_id, 'type' => 'acss_debit']
        )->data;
        return view('saved-banks', $data);
    }
    public function bank_saved()
    {
        return redirect()->route('saved-banks')->with('success', 'Bank saved successfully');
    }

    public function pay_with_card(Request $request, $pm, $amount = 100, $type = '')
    {
        if (@auth()->user()->stripe_connect->acc_id && auth()->user()->stripe_connect->is_enabled) {
            $connect = [
                'application_fee_amount' => $amount * 10,
                'transfer_data' => [
                    'destination' => auth()->user()->stripe_connect->acc_id,
                ],
                'on_behalf_of' => auth()->user()->stripe_connect->acc_id,
            ];
        } else {
            $connect = [];
        }
        // dd($request->header('User-Agent'), $request->ip());
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        try {
            $data['intent'] = \Stripe\PaymentIntent::create([
                'amount' => $amount * 100,
                'currency' => $type == 'acss_debit' ? 'cad' : 'usd',
                'customer' => auth()->user()->stripe_customer_id,
                'payment_method' => $pm,
                'off_session' => true,
                'confirm' => true,
                'payment_method_types' => [$type],
                'mandate_data' => [
                    'customer_acceptance' => [
                        'type' => 'online',
                        'online' => ['ip_address' => '124.29.253.109', 'user_agent' => $request->header('User-Agent')]
                    ]
                ],
                'payment_method_options' => [
                    'acss_debit' => [
                        'mandate_options' => [
                            'payment_schedule' => 'interval',
                            'interval_description' => 'First day of every month',
                            'transaction_type' => 'personal',
                        ],
                    ],
                    'card' => [
                        'capture_method' => 'manual',
                    ]
                ],
                $connect,
            ]);
            if ($type != 'card') {
                return redirect()->route('stripe.intents')->with('success', 'Payment will be processed shortly');
            } else {
                return redirect()->route('stripe.intents')->with('success', 'Payment Hold successful');
            }
        } catch (\Stripe\Exception\CardException $e) {
            // Error code will be authentication_required if authentication is needed
            echo 'Error code is:' . $e->getError()->code;
            $payment_intent_id = $e->getError()->payment_intent->id;
            $payment_intent = \Stripe\PaymentIntent::retrieve($payment_intent_id);
        }
        // dd($data);
        return view('stripe-pay', $data);
        // \Stripe\Stripe::setApiKey('sk_test_26PHem9AhJZvU623DfE1x4sd');
    }

    public function pay_done()
    {
        return redirect()->route('home')->with('success', 'Payment done successfully');
    }

    public function add_bank()
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $customer_id = auth()->user()->stripe_customer_id;
        if (!$customer_id) {
            $customer = \Stripe\Customer::create([
                'email' => auth()->user()->email,
            ]);
            auth()->user()->stripe_customer_id = $customer->id;
            auth()->user()->save();
            $customer_id = $customer->id;
        }

        $data['client_secret'] = $stripe->setupIntents->create(
            [
                'payment_method_types' => ['acss_debit'],
                'customer' => $customer_id,
                'payment_method_options' => [
                    'acss_debit' => [
                        'currency' => 'cad',
                        'mandate_options' => [
                            'payment_schedule' => 'interval',
                            'interval_description' => 'First day of every month',
                            'transaction_type' => 'personal',
                        ],
                        'verification_method' => 'instant',
                    ],
                ],
            ]
        )->client_secret;
        return view('add-bank', $data);
    }

    public function stripe_intents()
    {
        $stripe = new \Stripe\StripeClient(
            env('STRIPE_SECRET'),
        );
        $data['PIs'] = $stripe->paymentIntents->all(['limit' => 100]);
        // dd($data);
        return view('payment-intents', $data);
    }

    // public function stripe_capture_amount(Request $request)
    // {
    //     $request->validate(['pi_id' => 'required', 'amount' => 'required']);
    //     $stripe = new \Stripe\StripeClient(
    //         env('STRIPE_SECRET'),
    //       );
    //       $stripe->paymentIntents->capture(
    //         $request->pi_id,
    //         ['amount_to_capture' => $request->amount*100]
    //       );
    //     return redirect()->route('saved-stripe-cards')->with('success','Amount Captured');
    // }
    public function stripe_capture_amount(Request $request)
    {
        $request->validate(['pi_id' => 'required', 'amount' => 'required']);
        $stripe = new \Stripe\StripeClient(
            env('STRIPE_SECRET'),
        );
        $stripe->paymentIntents->capture(
            $request->pi_id,
            ['amount_to_capture' => $request->amount * 100]
        );
        return redirect()->route('stripe.view')->with('success', 'Amount Captured');
    }
    public function paypal_view()
    {
        // client id from paypal account
        $clientId = env('PAYPAL_CLIENT_ID');
        //secret id from paypal account
        $secret = env('PAYPAL_CLIENT_SECRET');
        //amount to capture
        $amount = 500;
        return view('paypalamount', compact('clientId', 'secret', 'amount'));
    }


    public function paypalPayment(Request $request)
    {
        $user_id = Auth::id();
        $captureData = json_decode($request->capturedData);
        $orderID = $request->orderID;
        $authorizationID = $request->authorizationID;
        $clientId = env('PAYPAL_CLIENT_ID');
        $secret = env('PAYPAL_CLIENT_SECRET');
        $uri = 'https://api.sandbox.paypal.com/v1/oauth2/token';
        $ch1 = curl_init();
        curl_setopt($ch1, CURLOPT_URL, $uri);
        curl_setopt($ch1, CURLOPT_HEADER, false);
        curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch1, CURLOPT_SSLVERSION, 6); //NEW ADDITION
        curl_setopt($ch1, CURLOPT_POST, true);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch1, CURLOPT_USERPWD, $clientId . ":" . $secret);
        curl_setopt($ch1, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
        $result1 = curl_exec($ch1);
        curl_close($ch1);
        $finalR1 =  json_decode($result1);
        if ($finalR1) {
            $details = new PaypalPayment();
            $details->user_id = $user_id;
            $details->paypal_id = $captureData->id;
            $details->authorization_id = $authorizationID;
            $details->order_id = $orderID;
            $details->payer_id = $captureData->payer->payer_id;
            $details->payer_email = $captureData->payer->email_address;
            $details->address = $captureData->payer->address->country_code;
            $details->given_name = $captureData->payer->name->given_name;
            $details->surname = $captureData->payer->name->surname;
            $details->amount = $captureData->purchase_units[0]->amount->value;
            $details->currency = $captureData->purchase_units[0]->amount->currency_code;
            $details->status = 0;
            $details->save();
            if ($details) {
                $id = $details->id;
                $data = PaypalPayment::find($id);
                $accesToken = $finalR1->access_token;
                $response = [
                    'success' => true,
                    'message' => 'Payment is added successfully'
                ];

                return response()->json($response, 200);
            }
        } else {
            return back()->with('error', 'SOmething went wrong. Try again later');
        }
    }

    public function paypalresponse(Request $request)
    {
        $user_id = Auth::id();
        $data = PaypalPayment::where('user_id', $user_id)->latest()->paginate(10);
        return view('paypal-response', compact('data'))->with('success', 'Paypal account is saved');
    }

    public function paymentprocess(Request $request)
    {
        $authorizationID = $request->authorization_id;
        $amount = $request->amount;
        $invoice_id = "INVOICE-23" . mt_rand(1000000, 9999999) . "4234456345234523423453123" . mt_rand(1000000, 9999999);
        $id = $request->id;
        $paypal_request_id = "123e422345" . mt_rand(1000000, 9999999) . "3425567-e89b-12d3-a456-4266554" . mt_rand(1000000, 9999999) . "40010";
        // Clint ID from paypal account
        $clientId = env('PAYPAL_CLIENT_ID');
        // Client secret id from paypal account
        $secret = env('PAYPAL_CLIENT_SECRET');
        //dd($request->all());
        //completing the payment process and refunding the remaining payment

        $url = "https://api-m.sandbox.paypal.com/v2/payments/authorizations/" . $authorizationID . "/capture";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $sec = base64_encode($clientId . ":" . $secret);
        $headers = array(
            "Content-Type: application/json",
            "Authorization: Basic " . $sec,
            "PayPal-Request-Id: " . $paypal_request_id,
        );
        // dd($headers);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $data = <<<DATA
       {
       "amount": {
           "value": {$amount},
           "currency_code": "USD"
       },
       "invoice_id": ".{$invoice_id}.",
       "final_capture": true,
       "note_to_payer": "If the ordered color is not available, we will substitute with a different color free of charge.",
       "soft_descriptor": "Bobs Custom Sweaters"
       }
       DATA;

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        $resultt = json_decode($resp);
        if ($resultt) {
            DB::table('paypal_payments')
                ->where('id', $id)
                ->update(['status' => 1]);
            return back()->with('success', 'Paypal Payment captured successfully');
        }
    }

    public function saved_chained_account()
    {
        $user_id = Auth::id();
        $connect = paypalDetail::where('user_id', $user_id)->latest()->get();
        return view('saved-chained-paypal', compact('connect'));
    }

    //connect with paypal
    public function paypalconection()
    {
        $user_id = Auth::id();
        $url = "https://svcs.sandbox.paypal.com/AdaptivePayments/Pay";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "X-PAYPAL-SECURITY-USERID: sb-upzgw17418663_api1.business.example.com",
            "X-PAYPAL-SECURITY-PASSWORD: MQ4ADY9JM284BAAU",
            "X-PAYPAL-SECURITY-SIGNATURE: AI1aW9Zsl12B20Lzjup2irDGUdm0APBUDX58QKiH0CBovVuvLPuQHiGO",
            "X-PAYPAL-REQUEST-DATA-FORMAT: JSON",
            "X-PAYPAL-RESPONSE-DATA-FORMAT: JSON",
            "X-PAYPAL-APPLICATION-ID: APP-80W284485P519543T",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $data = array(
            "actionType" => "PAY",
            "clientDetails" => array(
                "applicationId" => "APP-80W284485P519543T",
                "ipAddress" => "127.0.0.1",
            ),
            "currencyCode" => "USD",
            "feesPayer" => "EACHRECEIVER",
            "memo" => "Test paypal account",
            "receiverList" => array(
                "receiver" => array(
                    array(
                        "amount" => "102.00",
                        "email" => "developer@developer.developer",
                        "primary" => true
                    ),
                    array(
                        "amount" => "11.00",
                        "email" => "hybreathe@hybreathe.hybeathe",
                        "primary" => false
                    ),
                    array(
                        "amount" => "11.00",
                        "email" => "hbdeveloper@hybreathe.com",
                        "primary" => false
                    ),
                    array(
                        "amount" => "11.00",
                        "email" => "waqasnasir0064@gmail.com",
                        "primary" => false
                    ),
                    array(
                        "amount" => "11.00",
                        "email" => "admindinga@admin.admin",
                        "primary" => false
                    )
                )
            ),
            "requestEnvelope" => array(
                "errorLanguage" => "en_US"
            ),
            "returnUrl" => route('chained-transaction-completed'),
            "cancelUrl" => route('chaiend-transaction-view'),
        );


        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        $resp = json_decode($resp);
        curl_close($curl);
        // echo"<pre>";
        // print_r($resp);
        // exit;


        // getting paykey from above curl request
        $payKey = $resp->payKey;
        $authorizationurl = "https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_ap-payment&paykey=" . $payKey;
        $paypaldetails = new paypalDetail();
        $paypaldetails->user_id = $user_id;
        $paypaldetails->payKey = $payKey;
        $paypaldetails->status = "Success";
        $is_saved = $paypaldetails->save();
        // dd($authorizationurl,$is_saved);
        if ($is_saved) {
            return Redirect::to($authorizationurl);
        }
    }



    function transactiondetails(Request $request)
    {
        // dd($request->all());
        $url = "https://svcs.sandbox.paypal.com/AdaptivePayments/PaymentDetails";
        $paykey = $request->paykey;

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "X-PAYPAL-SECURITY-USERID: sb-upzgw17418663_api1.business.example.com",
            "X-PAYPAL-SECURITY-PASSWORD: MQ4ADY9JM284BAAU",
            "X-PAYPAL-SECURITY-SIGNATURE: AI1aW9Zsl12B20Lzjup2irDGUdm0APBUDX58QKiH0CBovVuvLPuQHiGO",
            "X-PAYPAL-REQUEST-DATA-FORMAT: JSON",
            "X-PAYPAL-RESPONSE-DATA-FORMAT: JSON",
            "X-PAYPAL-APPLICATION-ID: APP-80W284485P519543T",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $data = array(
            "payKey" => $paykey,
            "requestEnvelope" => array(
                "errorLanguage" => "en_US",
            ),
        );


        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        $resp = json_decode($resp);
        curl_close($curl);
        // echo "<pre>";
        // print_r($resp);
        // exit;
        return view('paypaltransactiondetails', compact('resp'));
    }
    public function chainedtransactioncompleted()
    {
        return redirect()->route('chaiend-transaction-view')->with('success', 'Chained Payment done successfully');
    }
}
