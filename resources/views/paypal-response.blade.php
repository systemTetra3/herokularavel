@extends('layouts.app')

@section('content')
    <style>
        h3 {
            font-weight: 800;
        }

        #table_id_wrapper {
            padding-top: 10px;
        }
        .description{
            font-size: 1.2rem;
        }
    </style>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

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
                                <p>$500 has been captured from your giving account, Click on the Capture Button from Action to make actual transaction of
                                    $100 and the remaining amount ($400) will be refunded to the buyer account.
                                OR , Click on the "Do another Transaction to make more transaction" <span style="color: red;">
                                Status Column tell's us the status from paypal server., If its in pending mean's the
                                    transaction is not completed yet, It has just capture the amount from buyer's
                                    account and the completed show's that the transaction is completed now.</span>
                                </p>
                            </div>
                        </div>
                        <br><br>
                        <div class="mb-4 table-responsive mb-0 fixed-solution" data-pattern="priority-columns">
                            <h3 class="float-left">Saved Paypal Acccounts</h3>
                            <a href="{{ route('paypal.view') }}"><button class="btn btn-success float-right">Do another
                                    Transaction</button></a>
                            <br><br>
                            <table class="table table-centered table-nowrap mb-0 pt-2" id="table_id">
                                <thead>
                                    <tr>
                                        <th scope="col">Paypal ID</th>
                                        <th scope="col">Authorization ID</th>
                                        <th scope="col">Order ID</th>
                                        <th scope="col">Payer ID</th>
                                        <th scope="col">Payer Email</th>
                                        <th scope="col">Country</th>
                                        <th scope="col">Surname</th>
                                        {{-- <th scope="col">Name</th> --}}
                                        <th scope="col">Amount</th>
                                        <th scope="col">Currency</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $paypal)
                                        <tr>
                                            <td>{{ $paypal->paypal_id }}</td>
                                            <td>{{ $paypal->authorization_id }}</td>
                                            <td>{{ $paypal->order_id }}</td>
                                            <td>{{ $paypal->payer_id }}</td>
                                            <td>{{ $paypal->payer_email }}</td>
                                            <td>{{ $paypal->address }}</td>
                                            {{-- <td>{{$paypal->given_name}}<td> --}}
                                            <td>{{ $paypal->surname }}</td>
                                            <td>{{ $paypal->amount }}</td>
                                            <td>{{ $paypal->currency }}</td>
                                            <td>{{ $paypal->status == 0 ? 'Pending' : 'Completed' }}</td>
                                            <td><a href="{{ $paypal->status == 0 ? route('paypal/payment/process', ['id' => $paypal->id, 'paypal_id' => $paypal->paypal_id, 'authorization_id' => $paypal->authorization_id, 'order_id' => $paypal->order_id, 'payer_id' => $paypal->payer_id, 'amount' => '100']) : '' }}"
                                                    class="btn btn-success">{{ $paypal->status == 0 ? 'Capture $100' : 'Completed' }}</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4">
                                                <h4 class="text-center text-info">No Record Found</h4>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                        </div>
                        <br><br>
                        <div class="row">
                            <div class="card-header h3" style="color: red;">Paypal Transaction Flow</div>
                            <div class="col-md-12 card">
                            </div>
                                <video controls>
                                    <source src="{{asset('video/capturedamount.mp4')}}" type="video/mp4">
                                  </video>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection
