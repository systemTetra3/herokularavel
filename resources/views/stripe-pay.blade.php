@extends('layouts.app')

@section('head')
    <title>Add Card</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <style type="text/css">
        .container {
            margin-top: 40px;
        }

        .panel-heading {
            display: inline;
            font-weight: bold;
        }

        .flex-table {
            display: table;
        }

        .display-tr {
            display: table-row;
        }

        .display-td {
            display: table-cell;
            vertical-align: middle;
            width: 55%;
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
                                <h4 class="panel-heading">Payment Details</h4>
                            </div>
                        </div>
                        <div class="panel-body">

                            @if (Session::has('success'))
                                <div class="alert alert-success text-center">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                                    <p>{{ Session::get('success') }}</p>
                                </div>
                            @endif
                            <form id="payment-form" data-secret="{{ @$client_secret }}">
                                <div id="payment-element">
                                    <!-- Elements will create form elements here -->
                                </div>

                                <button class="btn btn-success" id="submit">Submit</button>
                            </form>
                            {{-- <form action="stripe" id="stripe_payment_form">
                                <div id="card-element" class="field"></div>
                                <button class="mt-4 btn btn-success">save card</button>
                            </form> --}}
                            <form id="payment-done" method="POST" action="{{ route('stripe.payment-done') }}">
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script type="text/javascript">
        $(function() {
            var stripe = Stripe("{{ env('STRIPE_KEY') }}");
            // var elements = stripe.elements({
            //     // clientSecret = "seti_1LAIzLHbjvHPVPR3oWRCaNfG_secret_Ls35gKL0sCHiF0jKR8L6QvAYsRp1kun",
            // });
            stripe.confirmCardPayment("{{$intent->client_secret}}", {
                payment_method: "{{@$intent->last_payment_error->payment_method->id}}"
            }).then(function(result) {
                if (result.error) {
                    // Show error to your customer
                    console.log(result.error.message);
                } else {
                    if (result.paymentIntent.status === 'succeeded') {
                        $('#payment-done').submit();
                        console.log('payment_done');// The payment is complete!
                    }
                }
            });
            //
            // const options = {
            //     // Fully customizable with appearance API.
            //     appearance: {
            //         /*...*/
            //     },
            // };
            // const elements = stripe.elements(options);

            // // Create and mount the Payment Element
            // const paymentElement = elements.create('payment');
            // paymentElement.mount('#payment-element');

            // // var card = elements.create('card', {
            // //     hidePostalCode: true
            // // });
            // // card.mount('#card-element');
            // console.log('mounted');
            // const form = document.getElementById('payment-form');

            // form.addEventListener('submit', async (event) => {
            //     event.preventDefault();

            //     const {
            //         error
            //     } = await stripe.confirmSetup({
            //         //`Elements` instance that was used to create the Payment Element
            //         elements,
            //         confirmParams: {
            //             return_url: "{{ route('home') }}",
            //         }
            //     });

            //     if (error) {
            //         // This point will only be reached if there is an immediate error when
            //         // confirming the payment. Show error to your customer (for example, payment
            //         // details incomplete)
            //         const messageContainer = document.querySelector('#error-message');
            //         messageContainer.textContent = error.message;
            //     } else {
            //         // Your customer will be redirected to your `return_url`. For some payment
            //         // methods like iDEAL, your customer will be redirected to an intermediate
            //         // site first to authorize the payment, then redirected to the `return_url`.
            //     }
            // });

            // function setOutcome(result) {
            //     var successElement = document.querySelector('.success');
            //     var errorElement = document.querySelector('.error');
            //     console.log(result);

            //     if (result.token) {
            //         $('#stripe_token').val(result.token.id);
            //         $('#token').submit();
            //     } else if (result.error) {
            //         console.log('error', result.error);
            //     }
            // }
            // paymentElement.on('change', function(event) {
            //     setOutcome(event);
            // });
            // $('#payment-form').submit(function(e) {
            //     e.preventDefault();
            //     stripe.createToken(paymentElement).then(setOutcome);
            // });
        });
    </script>
@endsection
