
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __("Transaction History") }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif        
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>             
                                <th>Code</th>
                                <th>Description</th>                                                                                                                   
                                <th>Payment Status</th>                                
                                <th>Status</th>      
                                <th>Total Price</th>    
                                <th>Date</th>                                                      
                                <th colspan="2"></th>                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactionList as $transaction)
                            <tr>
                                <td>{{ $transaction->id }}</td>      
                                <td>{{ $transaction->code }}</td>         
                                <td>{{ $transaction->description }}</td>                                                                
                                <td>{{ $transaction->payment_status }}</td>
                                <td>{{ $transaction->status }}</td>
                                <td>@money($transaction->total_price)</td>
                                <td>{{ $transaction->created_at->format("d-m-Y  H:i A") }}</td>
                                <td >
                                    <a href="/transaction-history/detail/{{$transaction->id}}" class="btn btn-primary">Detail</a>
                                </td> 
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>                                        
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
