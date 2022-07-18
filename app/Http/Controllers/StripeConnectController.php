<?php

namespace App\Http\Controllers;

use App\Models\StripeConnect;
use Illuminate\Http\Request;

class StripeConnectController extends Controller
{
    public function payment_method(Request $request)
    {
        return view('supplier.payment');
    }
    public function setup_stripe_connect(Request $request)
    {
        $user = auth()->user();
        if (!$user->stripe_connect) {
            \Stripe\Stripe::setApiKey($this->get_stripe_secret_key());

            $account = \Stripe\Account::create([
                'country' => 'US',
                'type' => 'express',
            ]);
            $stripe_acc = new StripeConnect();
            $stripe_acc->user_id = $user->id;
            $stripe_acc->acc_id = $account->id;
            $stripe_acc->save();
            return $this->creat_link($account->id);
        }
        if ($user->stripe_connect && $user->stripe_connect->is_enabled == false)
            return $this->creat_link($user->stripe_connect->acc_id);
    }
    public function creat_link($acc_id, Request $request = null)
    {
        \Stripe\Stripe::setApiKey($this->get_stripe_secret_key());
        $info = array(
            'account' => $acc_id,
            'refresh_url' => route('stripeConnect.create_link', ['acc_id' => $acc_id]),
            'return_url' => route('stripeConnect.is_enabled_verify_link', ['acc_id' => $acc_id]),
            'type' => 'account_onboarding'
        );
        $account_links = \Stripe\AccountLink::create($info);
        // dd($account_links);
                       /**
         * Saving Log
         */
            // $act_user = $stripe_acc->acc_id;
            // $user = Auth::user();
            // $name = Auth::user()->name;
            // $description = $name.' Setup Stripe Connection <b>'.$act_user.'</b>';
            // $event_name = 'Stripe Connection';
            // $user['description']  = $description;
            // $user['event_name']  = $event_name;
            // event(new LogHistory($user));
        return redirect($account_links->url);
    }
    public function stripe_verify_is_enabled($acc_id, Request $request = null)
    {
        $stripe_acc = StripeConnect::where('acc_id', $acc_id)->first();
        if ($stripe_acc->is_enabled)
            return $this->stripe_user_login($acc_id);
        $stripe = new \Stripe\StripeClient($this->get_stripe_secret_key());
        $r = $stripe->accounts->retrieve(
            $acc_id,
            []
        );
        if (@$r->charges_enabled) {
            $stripe_acc = StripeConnect::where('acc_id', $acc_id)->first();
            $stripe_acc->is_enabled = true;
            $stripe_acc->save();
            return $this->stripe_user_login($acc_id);
        } else
            if($r->details_submitted){
                return redirect()->route('home')->with(['success' => 'Your account is not yet ready to use. Please wait for your account to be ready.']);
            }
            return $this->creat_link($acc_id);
    }
    public function stripe_user_login($acc_id, Request $request = null)
    {
        $stripe = new \Stripe\StripeClient($this->get_stripe_secret_key());
        $r =  $stripe->accounts->createLoginLink(
            $acc_id,
            []
        );
        if (@$r->url)
            return redirect($r->url);
    }
    public function get_stripe_secret_key()
    {
        return env('STRIPE_SECRET');
    }
}
