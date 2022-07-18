@extends('layouts.app')

@section('content')
<style>
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


                        <div class="container">
                            <h5 class="font-weight-bold note">Note:</h5>
                            <div class="description">
                                <p>Below table shows the recent maked Transactions. Status from the (status) shows the real transaction status from stripe server, Click on the (Capture) Button from Action Column ,<span style="color: red;"> By Clicking the capture button the $100 amount will be conducted from buyer's account and 90% amount will be transafered to the connected stripe account (That is connected from the connect stripe from the HomePage of this site and the remaining 10% will be transfered to the website owner's stripe account)</span></p>
                                </p>
                            </div>
                        </div>
                        <div class="mb-4 table-responsive mb-0 fixed-solution" data-pattern="priority-columns">
                            <h3>Payments</h3>
                            <table class="table table-centered table-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">#</a></th>
                                        <th scope="col">amount</th>
                                        <th scope="col">Capturable</th>
                                        <th scope="col">amount received</th>
                                        <th scope="col">status</th>
                                        <th scope="col">Action</th>
                                        {{-- <th scope="col">exp_year</th>
                                        <th scope="col">funding</th>
                                        <th scope="col">Pay with</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($PIs as $i => $pi)
                                        <tr>
                                            <td>{{$i+1}}</td>
                                            <td>{{$pi->amount/100}} {{strtoupper($pi->currency)}}</td>
                                            <td>{{$pi->amount_capturable/100}} {{strtoupper($pi->currency)}}</td>
                                            <td>{{$pi->amount_received/100}} {{strtoupper($pi->currency)}}</td>
                                            <td>{{$pi->status}}</td>
                                            @if ($pi->status == 'requires_capture')
                                                <td><a class="btn btn-success" onclick="capture_dialog({{$pi->amount_capturable/100}},'{{$pi->id}}');">Capture</a></td>

                                            @endif
                                            {{-- <td><a href="{{route('stripe.pay-with',['pm'=>$card->id,'amount'=>100, 'type' => 'card'])}}" class="btn btn-success">Hold $100</a></td> --}}
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
                            <div class="card-header h3" style="color: red;">Stripe Capture amount Flow</div>
                            <div class="col-md-12 card">
                            </div>
                                <video controls>
                                    <source src="{{asset('video/refund.mp4')}}" type="video/mp4">
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

    <script>
        function capture_dialog(capturable, pi_id) {
            $('#pi_id').val(pi_id);
                var amount = prompt("Enter amount to capture, less then or equal to the holded amount");
                if(amount == null || amount == '') {
                    alert("Please enter amount");
                    return;
                }
            $('#amount_to_capture').val(amount);
            $('#capture_amount_form').submit();
            }
        $(document).ready(function() {
            $('#table_id').DataTable();
        });
    </script>
@endsection
