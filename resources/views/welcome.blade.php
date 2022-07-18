@extends('layouts.app')

@section('head')
    <title>Add Card</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <link rel="stylesheet" href="{{ URL::asset('/css/welcome.css') }}">
    <style>
        .paymentbtn {
            border-radius: 5px;
            background-color: transparent;
            border: 1px solid green;
            color: green;
            font-size: 16px;
            color: green;
            width: 80%;
            margin-left: 10%;
            height: 49px;
            font-weight: 800;
            text-decoration: none;
        }
        .btnicon{
         float: right !important;
        text-align: right;
        margin-top: 7px;
        text-decoration: none;
        }
        .paymentbtn:hover{
            width: 83%;
            height: 54px;
        }
    .anchor{
    text-decoration: none;
    }
    .btntext{
        float: left;
    }
    .panel-heading{
        font-weight: 900;
        color: green;
    }

    </style>
@endsection
@section('content')
    <div class="container">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="row text-center">
                                <h4 class="panel-heading">Sending Payment</h4>
                            </div>
                        </div>
                        <div class="panel-body">
                           <a class="anchor" href="{{route('paypal.view')}}"> <button class="custom-btn btn-15  paymentbtn"><span class="btntext">Pay with PayPal</span><span> <i class="fa-solid fa-arrow-right d-flex btnicon"> </i></span></button></a>
                           <a class="anchor" href="{{route('stripe.view')}}"> <button class="custom-btn btn-15  paymentbtn"><span class="btntext">Pay with Credit Card</span><span> <i class="fa-solid fa-arrow-right d-flex btnicon"> </i></span></button></a>
                           <a class="anchor" href="{{route('stripe.view')}}"> <button class="custom-btn btn-15  paymentbtn"><span class="btntext">Pay with Debit Card</span><span> <i class="fa-solid fa-arrow-right d-flex btnicon"> </i></span></button></a>
                           <a class="anchor" href="{{route('saved-banks')}}"> <button class="custom-btn btn-15  paymentbtn"><span class="btntext">Pay with Bank</span><span> <i class="fa-solid fa-arrow-right d-flex btnicon"> </i></span></button></a>

                        </div>
                        <div class="panel-heading">
                        <div class="row text-center">
                            <h4 class="panel-heading">Reciving Payment</h4>
                        </div>
                        </div>
                        <div class="panel-body">
                            @if (@auth()->user()->stripe_connect->is_enabled)
                                <a class="anchor" href="{{route('stripeConnect.user_login',['acc_id' => auth()->user()->stripe_connect->acc_id])}}"> <button class="custom-btn btn-15  paymentbtn"><span class="btntext">Stripe Login</span><span> <i class="fa-solid fa-arrow-right d-flex btnicon"> </i></span></button></a>

                            @else
                                <a class="anchor" href="{{route('stripeConnect.setup_connect')}}"> <button class="custom-btn btn-15  paymentbtn"><span class="btntext">Connect Stripe Account</span><span> <i class="fa-solid fa-arrow-right d-flex btnicon"> </i></span></button></a>
                            @endif
                            <a class="anchor" href="{{route('chaiend-transaction-view')}}"> <button class="custom-btn btn-15  paymentbtn"><span class="btntext">Paypal Chain</span><span> <i class="fa-solid fa-arrow-right d-flex btnicon"> </i></span></button></a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')

@endsection
