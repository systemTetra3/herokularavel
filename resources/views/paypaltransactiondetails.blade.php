@extends('layouts.app')

@section('content')
<style>
    .description{
            font-size: 1.2rem;
        }
</style>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Paypal Connect Details') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif
                        <div class="container">
                            <h5 class="font-weight-bold note">Note:</h5>
                            <div class="description">
                                <p>Below Table show's the transaction of given paykey from Paypal Server</p>
                                Emails from (Eamil) Column shows the involved emials in this transaction Where The status show the real transaction details from Paypal server and Tell's us about the primary reciever
                               <span style="color: red;"> Here the total transaction amount is $102 as shows in amount column of primary receiver and it will send the $11.00 to every secondary receiver(non-primary receiver) and the remaining amount will be transfered to the primary receiver account</span>,
                                We can make any type of transaction by using this that how amount will transfer to first user , second user and respectively all , by seeing the requirement of our site
                                Click on the (Go Back) Button to back to previous page</p>
                            </div>
                        </div>

                        <h3 class="float-left">Chained Transaction Details</h3>
                        <a href="{{ url()->previous() }}"><button class="btn btn-success float-right">Go Back</button></a>

                        <div class="mb-4 table-responsive mb-0 fixed-solution" data-pattern="priority-columns" style="margin-top: 70px;">
                            <h3>Paypal Payment Details(PayKey : {{$resp->payKey}})</h3>
                            <table class="table table-centered table-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">Paykey</a></th>
                                        <th scope="col">amount</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Accout ID</th>
                                        <th scope="col">Primary Reciver</th>
                                        <th scope="col">status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($resp->paymentInfoList->paymentInfo as $data)
                                    <tr>
                                        <td>{{$resp->payKey}}</td>
                                        <td>{{$data->receiver->amount}}</td>
                                        <td>{{$data->receiver->email}}</td>
                                        <td>{{$data->receiver->accountId}}</td>
                                        <td>{{$data->receiver->primary}}</td>
                                        <td>{{$resp->responseEnvelope->ack}}</td>
                                    </tr>
                                    @empty

                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <br><br>
                        <div class="row">
                            <div class="card-header h3" style="color: red;">Paypal Chained Transaction Details by paykey</div>
                            <div class="col-md-12 card">
                            </div>
                                <video controls>
                                    <source src="{{asset('video/details.mp4')}}" type="video/mp4">
                                  </video>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="display: none;">
        <form id="capture_amount_form" action="{{route('stripe.capture_amount')}}" method="POST">
            @csrf
            <input type="text" name="pi_id" id="pi_id">
            <input type="text" name="amount" id="amount_to_capture">
        </form>
    </div>
@endsection
@section('scripts')

@endsection
