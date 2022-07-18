@extends('layouts.app')

@section('content')
<style>
    h3{
        font-weight: 800;
    }
</style>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
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

                        {{ __('You are logged in!') }}
                        <div class="mb-4 table-responsive mb-0 fixed-solution" data-pattern="priority-columns">
                            <h3>Saved Cards</h3>
                            <table class="table table-centered table-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">card_id</th>
                                        <th scope="col">brand</th>
                                        <th scope="col">last4</th>
                                        <th scope="col">exp_month</th>
                                        <th scope="col">exp_year</th>
                                        <th scope="col">funding</th>
                                        <th scope="col">Pay with</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($cards as $card)
                                        <tr>
                                            <td>{{$card->id}}</td>
                                            <td>{{$card->card->brand}}</td>
                                            <td>{{$card->card->last4}}</td>
                                            <td>{{$card->card->exp_month}}</td>
                                            <td>{{$card->card->exp_year}}</td>
                                            <td>{{$card->card->funding}}</td>
                                            <td><a href="{{route('stripe.pay-with',['pm'=>$card->id,'amount'=>100, 'type' => 'card'])}}" class="btn btn-success">Hold on $100</a></td>
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
                            <h3>saved banks</h3>
                            <table class="table table-centered table-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">Bank Name</th>
                                        <th scope="col">Institution Number</th>
                                        <th scope="col">Last4</th>
                                        <th scope="col">Transit number</th>
                                        <th scope="col">Fingerprint</th>
                                        <th scope="col">Pay with</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($acss_debits as $acss_debit)
                                        <tr>
                                            <td>{{$acss_debit->acss_debit->bank_name}}</td>
                                            <td>{{$acss_debit->acss_debit->institution_number}}</td>
                                            <td>{{$acss_debit->acss_debit->last4}}</td>
                                            <td>{{$acss_debit->acss_debit->transit_number}}</td>
                                            <td>{{$acss_debit->acss_debit->fingerprint}}</td>
                                            <td><a href="{{route('stripe.pay-with',['pm'=>$acss_debit->id,'amount'=>10,'type' => 'acss_debit'])}}" class="btn btn-success">pay $10</a></td>
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
                            <h3>Saved Paypal Acccounts</h3>
                            <table class="table table-centered table-nowrap mb-0" id="table_id">
                                <thead>
                                    <tr>
                                        <th scope="col">Paypal ID</th>
                                        <th scope="col">Authorization ID</th>
                                        <th scope="col">Order ID</th>
                                        <th scope="col">Payer ID</th>
                                        <th scope="col">Payer Email</th>
                                        <th scope="col">Country</th>
                                        <th scope="col">Surname</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Currency</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($paypal as $paypal)

                                        <tr>
                                            <td>{{$paypal->paypal_id}}</td>
                                            <td>{{$paypal->authorization_id}}</td>
                                            <td>{{$paypal->order_id}}</td>
                                            <td>{{$paypal->payer_id}}</td>
                                            <td>{{$paypal->payer_email}}</td>
                                            <td>{{$paypal->address}}</td>
                                            {{-- <td>{{$paypal->given_name}}<td> --}}
                                            <td>{{$paypal->surname}}</td>
                                            <td>{{$paypal->amount}}</td>
                                            <td>{{$paypal->currency}}</td>
                                            <td>{{$paypal->status == 0 ? 'Pending' : 'Completed' }}</td>
                                            <td><a href="{{$paypal->status == 0 ? route('paypal/payment/process',['id'=>$paypal->id,'paypal_id'=>$paypal->paypal_id,'authorization_id'=>$paypal->authorization_id,'order_id'=>$paypal->order_id,'payer_id'=>$paypal->payer_id,'amount'=>'100']) : ''}}" class="btn btn-success">{{$paypal->status == 0 ? 'Capture $100' : 'Completed'}}</a></td>
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
                            <h3>Paypal Connect</h3>
                            <table class="table table-centered table-nowrap mb-0" id="table_id">
                                <thead>
                                    <tr>
                                        <th scope="col">Pay key</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Show Transaction Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($connect as $connect)

                                        <tr>
                                            <td>{{$connect->payKey}}</td>
                                            <td>{{$connect->status}}</td>
                                            <td><a class="btn btn-success" href="{{route('paypal.detaisl',['paykey'=>$connect->payKey])}}"</a>Show Transaction Details</td>
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
                        {{-- <div class="row mt-4">
                            <div class="col-sm-6">
                                <div>
                                    <p class="mb-sm-0">Showing  {{($cards->perPage()*($cards->currentPage()-1))+1}} to {{($cards->perPage()*($cards->currentPage()-1))+$ln}}of  {{$cards->total()}} entries </p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="float-sm-end">


                                    @if ($cards->lastPage() > 1)
                                        <ul class="pagination mb-sm-0">
                                            <li class="{{ $cards->currentPage() == 1 ? ' disabled' : '' }} page-item">
                                                <a class=" page-link " href="{{ $cards->url($cards->currentPage() - 1) }}"
                                                    aria-label="Previous">
                                                    <span aria-hidden="true"><i class="mdi mdi-chevron-left"></i></span>
                                                    <span class="sr-only">entries</span>
                                                </a>
                                            </li>
                                            @for ($i = 1; $i <= $cards->lastPage(); $i++)
                                                <li class="{{ $cards->currentPage() == $i ? ' active' : '' }} page-item">
                                                    <a class=" page-link "
                                                        href="{{ $cards->url($i) }}">{{ $i }}</a>
                                                </li>
                                            @endfor
                                            <li
                                                class="{{ $cards->currentPage() == $cards->lastPage() ? ' disabled' : '' }} page-item">
                                                <a href="{{ $cards->url($cards->currentPage() + 1) }}" class="page-link"
                                                    aria-label="Next">
                                                    <span aria-hidden="true"><i class="mdi mdi-chevron-right"></i></span>
                                                    <span class="sr-only">Next</span>
                                                </a>
                                            </li>
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#table_id').DataTable();
        });
    </script>
@endsection
