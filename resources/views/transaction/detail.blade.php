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
                                                <p>Name : {{$doctor->name}}</p>
                                                <p>Position : {{$doctor->userInfo->position}}</p>
                                            </div>    
                                            <div class="col-md-6">                                                
                                                <p>Consult Date : {{date("Y-m-d", strtotime($consult->session_start))}}</p>
                                                @if ($consult->status == 'confirm')
                                                    <p>Consult Duration : {{$doctor->consultRule->duration}} minute</p>                                                
                                                    <p>Start at : {{date("H:i", strtotime($consult->session_start))}}</p>                                                    
                                                @endif  
                                            </div>    
                                        </div>       
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <a href="{{ route('consult.detail.chat', ['consult' => $consult->id]) }}" class="btn btn-primary 
                                                    {{(strtotime(date("Y-m-d H:i:s")) > strtotime($consult->session_start)
                                                    && strtotime(date("Y-m-d H:i:s")) < strtotime($consult->session_end)) && $transaction->payment_status == 'approve' 
                                                    && $transaction->status == 'consult_process' ? '' : 'disabled'}}" >
                                                    Consultation
                                                </a>
                                            </div>    
                                            <div class="col-md-6">
                                                @if($transaction->payment_status == 'approve' && $transaction->status == 'done')
                                                    <a onclick="window.print()"class="btn btn-primary float-end" >
                                                        E-Recipe
                                                    </a>
                                                @endif
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
                                                {{-- <a href="" class="btn btn-primary">
                                                    Medical Record
                                                </a> --}}
                                            </div>    
                                        </div>                                                                                                              
                                    </div>
                                </div>
                                @if(!is_null($consult->medical_record) && $consult->medical_record != "")                                
                                <div class="card mt-5">                            
                                    <div class="card-header">
                                        Medical Record
                                    </div>
                                    <div class="card-body">               
                                       <p>{{strip_tags($consult->medical_record)}}</p>
                                    </div>
                                </div>
                                @endif  

                                @if(!is_null($consult->medical_record) && $consult->medical_record != "")                                
                                <div class="card mt-5">                            
                                    <div class="card-header">
                                        Receipt Record
                                    </div>
                                    <div class="card-body">                                        
                                       <p>{{   strip_tags($consult->receipe_record)}}</p>
                                    </div>
                                </div>
                                @endif  
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        Payment
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p>Status : {{$transaction->payment_status}}</p>                                                
                                            </div>
                                            <div class="col-md-6">     
                                                
                                                    @if($transaction->payment_status != 'approve')    
                                                        <form action="{{route('transaction.history.detail.upload.payment', 
                                                            ['transaction' => $transaction->id])}}" 
                                                            method="post" enctype="multipart/form-data">
                                                            @csrf     
                                                            <p>Please upload your proof of payment.</p>
                                                            <input type="file" name="proof_of_payment"
                                                            {{$transaction->status == 'payment_process' ||
                                                            $transaction->consultation->status == 'confirm' &&
                                                            ($transaction->payment_status == 'init' ||
                                                            $transaction->payment_status == 'reject') ? '' : 'disabled'}}>
                                                            <button type="submit" name="submit" class="btn btn-primary mt-4" 
                                                            {{$transaction->status == 'payment_process' ||
                                                                $transaction->consultation->status == 'confirm' &&
                                                                ($transaction->payment_status == 'init' ||
                                                                $transaction->payment_status == 'reject') ? '' : 'disabled'}}>
                                                                Upload Files
                                                            </button>
                                                        </form>
                                                    @else
                                                        <p>Your proof of payment has been uploaded.</p>
                                                        <form action="{{route('transaction.history.detail.download.payment', 
                                                            ['transaction' => $transaction->id])}}" 
                                                            method="post" enctype="multipart/form-data">
                                                            @csrf  
                                                            <button type="submit" name="submit" class="btn btn-primary">
                                                                Proof of Payment
                                                            </button>                                                           
                                                        </form>
                                                    @endif                                                
                                                    @if($transaction->payment_status == 'reject')         
                                                        <p class="mt-4">Reject Reason : {{$transaction->payment_reject_reason}}</p>                                                    
                                                    @endif                                                                                                
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
    </div>     
</div>

@endsection