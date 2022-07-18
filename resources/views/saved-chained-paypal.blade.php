@extends('layouts.app')

@section('content')
<style>
    h3{
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
                            <div class="question">
                                <div class="h4" style="color: red">What is Chained payments</div>

                                <div class="description">Enable a sender to send a single payment to a primary receiver. The primary receiver keeps part of the payment and pays the remainder to (up to nine) secondary receivers.</div>
                            </div>
                            <h5 class="font-weight-bold note">Note:</h5>
                            <div class="description">
                                <p>The Table shows the recently maked paypal chined transaction by current user, Status from (status) Column shows the real transaction status from the paypal server,
                                    Click on the (Show Transaction Details) from Show Transaction Detials Column to see all the details of relevent transaction
                                    Click on the Do another Transaction Button to make another Chained Transaction,

                                </p>
                            </div>

                        </div>
                       <p class="description" style="color: red;"> Use Below given paypal prmimary account credentials to make another transaction</p>
                        <table class="table table-centered table-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th>Email</th>
                                    <th>Password</th>
                                    <th>Account Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>sb-hv5ta15246315@business.example.com</td>
                                    <td>yghWWM4/</td>
                                    <td>Business</td>
                                </tr>
                            </tbody>
                        </table>
                        <br><br>
                        <div class="mb-4 table-responsive mb-0 fixed-solution" data-pattern="priority-columns">

                            <h3 class="float-left">Paypal Chained Transactions</h3>
                            <a href="{{route('paypal.connect')}}"><button class="btn btn-success float-right">Do another Transaction</button></a>
                            <br><br>
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
                        <br><br>
                        <div class="row">
                            <div class="card-header h3" style="color: red;">Paypal Chained Transaction Flow</div>
                            <div class="col-md-12 card">
                            </div>
                                <video controls>
                                    <source src="{{asset('video/paypalchain.mp4')}}" type="video/mp4">
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#table_id').DataTable();
        });
    </script>
@endsection
