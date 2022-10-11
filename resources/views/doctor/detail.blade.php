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
                           {{ __('Make Appointment') }}
                           </div>
                        </div>
                        <div class="col-md-6 mt-2 mb-2">
                           <a href="{{ route('home') }}" class="btn btn-secondary float-end">
                              Back
                           </a>
                        </div>  
                     </div>                        
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <p><strong>Opps Something went wrong</strong></p>
                            <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <br>
                    <div class="col-md-12">   
                        <form method="POST" action="{{route('consult.create', ['user' => $user->id])}}">
                            @csrf                                                                       
                            <div class="col-md-12"> 
                                <div class="row">
                                    <div class="col-md-3 offset-3">
                                        <img src={{$user->userInfo->photo}} width="150" class="rounded-circle" >
                                    </div>
                                    <div class="col-md-4">
                                        <h4 class="font-weight-bold">{{$user->name}}</h4>
                                        <h5 class="text-secondary">{{$user->userInfo->position}}</h5>
                                        <hr>   
                                        <br>                                                                                                                   
                                        <button type="submit" class="btn btn-primary">
                                            Make Consultation Appointment
                                        </button>                  
                                    </div>
                                </div>                                                                            
                            </div>                                                                               
                            <br/>        
                            <div class="col-md-12 mt-4">
                                <h4 class="font-weight-bold">Doctor Profile</h4>
                                <p>
                                    {{$user->userInfo->description}}
                                </p>
                            </div>            
                            <div class="col-md-12">                                      
                                <h4 class="font-weight-bold">Schedules</h4>                                        
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="text-primary">
                                        Consultation Fee : @money($user->consultRule->price)
                                        </h5>                                                                                    
                                        <div class='input-group date' id='datetimepicker'>
                                            <input type='text' name='consultDate' 
                                                class="form-control" required placeholder="Select Date"/>
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>                                    
                                        <br>
                                        @foreach($user->consultRule->schedules as $schedule)      
                                            @if ($schedule->active == '1')
                                            <div class="card">
                                                <div class="card-header">
                                                    {{ucwords($schedule->day)}}
                                                </div>
                                                <div class="card-body">                                                              
                                                    {{date('H:i',strtotime($schedule->start_time))}} - 
                                                    {{date('H:i',strtotime($schedule->end_time))}}                                                                
                                                </div>
                                            </div>      
                                            <br>  
                                            @endif                                     
                                        @endforeach                                                                                           
                                    </div>
                                    <br>                                      
                                </div>                                                                               
                            </div>                                                    
                            <br/>    
                        </form>   
                    </div>                                                     
                </div>
            </div>
        </div>
    </div>     
</div>

@endsection