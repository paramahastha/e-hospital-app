@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6 mt-3">
                           <div class="d-flex align-items-center">
                           {{ __('Transaction Detail') }}
                           </div>
                        </div>
                        <div class="col-md-6 mt-2 mb-2">
                           <a href="{{ route('transaction.history') }}" class="btn btn-secondary float-end">
                              Back
                           </a>
                        </div>  
                     </div>                        
                </div>
                <div class="card-body">
                    <div class="col-md-12">                        
                        <div class="col-md-6">
                            <h4>No. {{$transaction->code}}</h4>
                            <h4>Status : {{$transaction->status}}</h4>
                        </div>
                        <div class="col-md-6 ">
                            <h4 class="float-end">Date : {{$transaction->created_at->format("d-m-Y  H:i A")}}</h4>
                        </div>
                    </div>
                    <div class="col-md-12 mt-4 mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">                            
                                    <div class="card-header">
                                        Doctor Information
                                    </div>
                                    <div class="card-body">  
                                        <div class="text-center">
                                            <img src={{$doctor->userInfo->photo}} width="150" class="rounded-circle mb-5"/>                                            
                                        </div>           
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p>Code : {{$doctor->userInfo->code}}</p>
                                                <p>Name : {{$doctor->name}}</p></div>    
                                            <div class="col-md-6">
                                                <p>Position : {{$doctor->userInfo->position}}</p>                                                     
                                                <a href="" class="btn btn-primary 
                                                    {{(strtotime(date("Y-m-d H:i:s")) > strtotime($consult->session_start)
                                                    && strtotime(date("Y-m-d H:i:s")) < strtotime($consult->session_end)) && $transaction->payment_status == 'approve' ? '' : 'disabled'}}" >
                                                    Consultation
                                                </a>
                                            </div>    
                                        </div>                                                                                
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">                            
                                    <div class="card-header">
                                        Patient Information
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p>Code : {{$patient->userInfo->code}}</p>                                     
                                                <p>Name : {{$patient->name}}</p>
                                                <p>Email : {{$patient->email}}</p>
                                                <p>Identity Number : {{$patient->userInfo->identity_number}}</p>
                                            </div>    
                                            <div class="col-md-6">                                                
                                                <p>Date of Birth : {{$patient->userInfo->date_of_birth}}</p>
                                                <p>Gender : {{$patient->userInfo->gender}}</p>
                                                <p>Phone : {{$patient->userInfo->phone_number}}</p>
                                                <p>Address : {{$patient->userInfo->address}}</p>
                                                <a href="" class="btn btn-primary">
                                                    Medical Record
                                                </a>
                                            </div>    
                                        </div>                                                                                                              
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">

                                    </div>
                                    <div class="card-body">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>                      
                    </div>
                </div>
            </div>
        </div>
    </div>     
</div>

@endsection