@extends('layouts.app')

@section('content')
    <style>

        #table_id_wrapper {
            padding-top: 10px;
        }
        h3 {
            font-weight: 800;
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
                        <div class="mb-4 table-responsive mb-0 fixed-solution" data-pattern="priority-columns">
                            <div class="container">
                                <h5 class="font-weight-bold note">Note:</h5>
                                <div class="description">
                                    <p>The Below table show the previous added stripe cards, Click on the (Add another Card)
                                        to add more stripe cards</p>
                                    <p>Click on the (Hold on) Button from (Action) Column to hold the $100 amount from the
                                        added stripe acount</p>
                                </div>
                            </div>
                            <h3 class="float-left">Saved Cards</h3>
                            <a href="{{ route('add-card-blade') }}"><button class="btn btn-success float-right">Add another
                                    Card</button></a>
                            <table class="table table-centered table-nowrap mb-0" style="margin-top: 52px;">
                                <thead>
                                    <tr>
                                        <th scope="col">card_id</th>
                                        <th scope="col">brand</th>
                                        <th scope="col">last4</th>
                                        <th scope="col">exp_month</th>
                                        <th scope="col">exp_year</th>
                                        <th scope="col">funding</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($cards as $card)
                                        <tr>
                                            <td>{{ $card->id }}</td>
                                            <td>{{ $card->card->brand }}</td>
                                            <td>{{ $card->card->last4 }}</td>
                                            <td>{{ $card->card->exp_month }}</td>
                                            <td>{{ $card->card->exp_year }}</td>
                                            <td>{{ $card->card->funding }}</td>
                                            <td><a href="{{ route('stripe.pay-with', ['pm' => $card->id, 'amount' => 100, 'type' => 'card']) }}"
                                                    class="btn btn-success">Hold on $100</a></td>
                                        </tr>

                                    @empty
                                        <tr>
                                            <td colspan="4">
                                                <h4 class="text-center text-info">No record Found</h4>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                        </div>
                        <br><br>
                        <div class="row">
                            <div class="card-header h3" style="color: red;">Stripe Add Account and Hold on Flow</div>
                            <div class="col-md-12 card">
                            </div>
                                <video controls>
                                    <source src="{{asset('video/holdamount.mp4')}}" type="video/mp4">
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
