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
                                <h4 class="panel-heading">Save Bank</h4>
                            </div>
                        </div>
                        <div class="panel-body">

                            @if (Session::has('success'))
                                <div class="alert alert-success text-center">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                                    <p>{{ Session::get('success') }}</p>
                                </div>
                            @endif
                            <form id="payment-form" data-secret="{{ $client_secret }}">
                                <div id="payment-element">
                                    <!-- Elements will create form elements here -->
                                </div>

                                <button class="btn btn-success hidden" id="submit">Submit</button>
                            </form>
                            {{-- <form action="stripe" id="stripe_payment_form">
                                <div id="card-element" class="field"></div>
                                <button class="mt-4 btn btn-success">save card</button>
                            </form> --}}
                            <form id="token" method="POST" action="{{ route('stripe.bank-saved') }}">
                                <input type="hidden" id="stripe_token" name="token">
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
            stripe.confirmAcssDebitSetup(
                '{{ $client_secret }}',
            {
                payment_method: {
                billing_details: {
                    name: '{{auth()->user()->name}}',
                    email: '{{auth()->user()->email}}'
                },
                },
            }
            ).then(function(result) {
            if (result.error) {
                // Inform the customer that there was an error.
                console.log(result.error.message);
            } else {
                // Handle next step based on SetupIntent's status.
                $('#token').submit();
                console.log("SetupIntent ID: " + result.setupIntent.id);
                console.log("SetupIntent status: " + result.setupIntent.status);
            }
            });
            //
            var form = document.getElementById('payment-form');
            var accountholderName = document.getElementById('accountholder-name');
            var email = document.getElementById('email');
            var submitButton = document.getElementById('submit-button');
            var clientSecret = submitButton.dataset.secret;
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                stripe.confirmAcssDebitSetup(clientSecret, {
                    payment_method: {
                    billing_details: {
                        name: accountholderName.value,
                        email: email.value,
                    },
                    },
                }).then(function(result) {
                    if (result.error) {
                    // Inform the customer that there was an error.
                    console.log(result.error.message);
                    } else {
                    // Handle next step based on SetupIntent's status.
                    console.log("SetupIntent ID: " + result.setupIntent.id);
                    console.log("SetupIntent status: " + result.setupIntent.status);
                    }
                });
            });
        });
    </script>
@endsection
