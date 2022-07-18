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
                            <h5 class="font-weight-bold note">Note:</h5>
                            <div class="description">
                                <p>Below table show's the recent added banks in this site by current user, Click on the (Add another Bank) to add another bank accout. At time of adding another bank account you will get the test account credentials under every step
                                       <span style="color: red;"> Click on the (Pay) Button from pay with Column to make a transaction of $10 from relevent bank account</span>
                                    </p>

                            </div>
                        </div>
                        <div class="mb-4 table-responsive mb-0 fixed-solution" data-pattern="priority-columns">

                            <h3 class="float-left">saved banks</h3>
                            <a href="{{route('stripe.add-bank')}}"><button class="btn btn-success float-right">Add another Bank</button></a>
                            <table class="table table-centered table-nowrap mb-0" style="margin-top:50px;">
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
                                            <td><a href="{{route('stripe.pay-with',['pm'=>$acss_debit->id,'amount'=>1000,'type' => 'acss_debit'])}}" class="btn btn-success">pay $1000</a></td>
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
                            <div class="card-header h3" style="color: red;">Adding Bank account Flow</div>
                            <div class="col-md-12 card">
                            </div>
                                <video controls>
                                    <source src="{{asset('video/stripebank.mp4')}}" type="video/mp4">
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
