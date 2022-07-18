<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\StripeConnectController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::controller(HomeController::class)->prefix('home')->group(function () {
    Route::get('/', 'index')->name('home');
    // stripe
    Route::get('/stripe/add-card', 'add_card_blade')->name('add-card-blade');
    Route::post('/stripe/save-card', 'save_card')->name('stripe.save-card');
    Route::get('/stripe/pay-with/{pm}/{amount?}/{type?}', 'pay_with_card')->name('stripe.pay-with');
    Route::post('/stripe/payment-done', 'pay_done')->name('stripe.payment-done');
    Route::get('stripe/cards','stripecards')->name('stripe.view');
    // stipe bank
    Route::get('/stripe/add-bank', 'add_bank')->name('stripe.add-bank');
    Route::post('/stripe/saved-bank', 'bank_saved')->name('stripe.bank-saved');
    Route::get('/saved/banks','saved_banks_list')->name('saved-banks');
    // payment intents
    Route::get('/stripe-intents', 'stripe_intents')->name('stripe.intents');
    // camput amount
    Route::post('/stripe-capture-amount', 'stripe_capture_amount')->name('stripe.capture_amount');
    // paypal routes
    Route::get('/paypal/account', 'paypal_view')->name('paypal.view');
    Route::get('paypal/payment', 'paypalPayment')->name('paypal/payment');
    Route::get('paypal/response','paypalresponse')->name('paypalresponse');
    Route::get('payment/proccess','paymentprocess')->name('paypal/payment/process');
    Route::get('saved/chained/transaction','saved_chained_account')->name('chaiend-transaction-view');
    Route::get('paypal/chain','paypalconection')->name('paypal.connect');
    Route::get('paypal/transaction/details','transactiondetails')->name('paypal.detaisl');
    Route::get('paypal/chain/complete','chainedtransactioncompleted')->name('chained-transaction-completed');
});
Route::controller(StripeConnectController::class)->prefix('stripe')->middleware('auth')->group(function () {
    Route::get('/payment/stripe_connect', 'setup_stripe_connect')->name('stripeConnect.setup_connect');
    Route::get('/stripe-login/{acc_id}', 'stripe_user_login')->name('stripeConnect.user_login');
    // stripe connect refresh url
    Route::get('/create-link/{acc_id}', 'creat_link')->name('stripeConnect.create_link');
    Route::get('/stripe-verify/{acc_id}', 'stripe_verify_is_enabled')->name('stripeConnect.is_enabled_verify_link');
});
