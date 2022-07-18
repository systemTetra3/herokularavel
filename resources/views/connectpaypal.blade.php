@extends('layouts.app')
@section('head')
  <style>
    .paypal-button-number-1
    {
      display: none;
    }
  </style>
@endsection
@section('content')
    <div class="container">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="item">
                        <div id="smart-button-container">
                            <div style="text-align: center;">
                              <div id="paypal-button-container"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
{{-- jquery cdn  --}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://www.paypal.com/sdk/js?client-id={{$clientId}}&enable-funding=card&currency=USD&disable-funding=credit&intent=authorize" data-sdk-integration-source="button-factory"></script>
    {{-- venmo paypal btn  --}}
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
              purchase_units: [
                {
                    "amount":
                    {
                        "currency_code":"USD",
                        "value":{{$amount}}
                    }
                }
            ]
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
              console.log("Authorization data of user"+capturedData);
          // alert('Authorization ID is '+authorizationID);
          // window.location = "{{ route('paypal/payment') }}?data="+JSON.stringify(authorization);
              // Full available details
              // Call your server to validate and capture the transaction
            

              //ajax post request
         
            $.ajax({
              type: "GET",
              url: "{{route('paypal/payment')}}",
              dataType: 'JSON',
              data: {
                 'orderID': orderID,
                 'authorizationID': authorizationID,
                 'capturedData': capturedData
                },
              success: function (response) {
                console.log(response);
                alert("Yout payment has been captured successfully");
                window.location = "{{ route('paypalresponse') }}";
              }
            });
            });
          },
        }).render('#paypal-button-container');
      }
      initPayPalButton();
    </script>
@endsection

