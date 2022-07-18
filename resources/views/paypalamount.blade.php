@extends('layouts.app')
@section('head')
    <style>
        .paypal-button-number-1 {
            display: none;
        }
        .h3 {
            font-weight: 800;
        }
        .description{
            font-size: 1.2rem;
        }
        .hightlight{
            color: red;
        }
    </style>
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="col-md-12">
                    <div class="card" style="margin-top: 10px">
                        <div class="card-header h3">Sandbox test accounts</div>
                        <div class="card-body">
                            <div class="container">
                                <div class="question">
                                    <div class="h4" style="color: red">What is auth and capture paypal payments Api</div>

                                    <div class="description">PayPal authorization and capture is a settlement solution that provides increased flexibility in obtaining payments from buyers. During a traditional sale, the authorization and capture occurs at the same time as the sale.</div>
                                </div>
                                <h5 class="font-weight-bold note">Note:</h5>
                                <div class="description">
                                    <p>Here when user click on checkout button, He will redirect to paypal for authentication and their he will capture the required amount <span class="hightlight"> Use Below account for testing purpose</span></p>
                                </div>
                            </div>
                            <table class="table table-centered table-nowrap mb-0 pt-2">
                                <thead>
                                    <tr>
                                        <th>Email</th>
                                        <th>Password</th>
                                        <th>Account Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>hybreathe@hybreathe.hybeathe</td>
                                        <td>hybreathe</td>
                                        <td>Personal</td>
                                    </tr>
                                    <tr>
                                        <td>hbdeveloper@hybreathe.com</td>
                                        <td>12121212</td>
                                        <td>Personal</td>
                                    </tr>
                                    <tr>
                                        <td>sb-hv5ta15246315@business.example.com</td>
                                        <td>yghWWM4/</td>
                                        <td>Business</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <br><br>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="item">
                            <div id="smart-button-container">
                                <div style="text-align: center; margin-left:10px;">
                                    <div id="paypal-button-container"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br><br>
                <div class="row">
                    <div class="card-header h3" style="color: red;">Paypal Checkout Flow</div>
                    <div class="col-md-12 card">
                    </div>
                        <video controls>
                            <source src="{{asset('video/paywithpaypal.mp4')}}" type="video/mp4">
                          </video>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    {{-- jquery cdn --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script
        src="https://www.paypal.com/sdk/js?client-id={{ $clientId }}&enable-funding=card&currency=USD&disable-funding=credit&intent=authorize"
        data-sdk-integration-source="button-factory"></script>
    {{-- venmo paypal btn --}}
    {{-- <script src="https://www.paypal.com/sdk/js?client-id={{$clientId}}&enable-funding=venmo&currency=USD" data-sdk-integration-source="button-factory"></script> --}}
    <script>
        function initPayPalButton() {
            paypal.Buttons({
                style: {
                    size: 'small',
                    shape: 'pill',
                    color: 'silver',
                    layout: 'horizontal',
                    label: 'checkout',
                    tagline: false
                },

                createOrder: function(data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            "amount": {
                                "currency_code": "USD",
                                "value": {{ $amount }}
                            }
                        }]
                    });
                },
                onApprove: function(data, actions) {
                    // Authorize the transaction
                    actions.order.authorize().then(function(authorization) {
                        console.log(authorization);
                        // Get the authorization id
                        var authorizationID = authorization.purchase_units[0]
                            .payments.authorizations[0].id;
                        var orderID = authorization.id;
                        var capturedData = JSON.stringify(authorization, null, 2);
                        console.log("Authorization data of user" + capturedData);
                        // alert('Authorization ID is '+authorizationID);
                        // window.location = "{{ route('paypal/payment') }}?data="+JSON.stringify(authorization);
                        // Full available details
                        // Call your server to validate and capture the transaction


                        //ajax post request

                        $.ajax({
                            type: "GET",
                            url: "{{ route('paypal/payment') }}",
                            dataType: 'JSON',
                            data: {
                                'orderID': orderID,
                                'authorizationID': authorizationID,
                                'capturedData': capturedData
                            },
                            beforeSend: function() {
                                $(".preloader").removeClass("d-none");
                            },
                            success: function(response) {
                                console.log(response);
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Your payment has been captured successfully',
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                                window.location = "{{ route('paypalresponse') }}";
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Oops! Something went wrong, Please try again later',
                                    showConfirmButton: false,
                                    timer: 1500
                                })

                            },
                        });
                    });
                },
            }).render('#paypal-button-container');
        }
        initPayPalButton();
    </script>
    <script></script>
@endsection
