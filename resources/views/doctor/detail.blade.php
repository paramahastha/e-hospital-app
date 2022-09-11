@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('Detail Janji Konsultasi') }}</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <br>
                    <div class="col-md-12">   
                        <form method="POST" action="{{route('consult.create', ['user' => $user->id])}}">
                            @csrf                           
                            <div class="row">                   
                                <div class="col-md-9">      
                                    <div class="row">
                                        <div class="col-md-3">
                                            <img src={{$user->userInfo->photo}} width="150" >
                                        </div>
                                        <div class="col-md-9">
                                            <h4 class="font-weight-bold">{{$user->name}}</h4>
                                            <h5 class="text-secondary">{{$user->userInfo->position}}</h5>
                                            <hr>   
                                            <br>                                                                                                                   
                                                <button type="submit" class="btn btn-primary">
                                                    Buat Janji Konsultasi
                                                </button>                  
                                        </div>
                                    </div>                                                
                                </div>                                                   
                            </div>
                            <br/>        
                            <div class="col-md-12">
                                <h4 class="font-weight-bold">Profil Dokter</h4>
                                <p>
                                    {{$user->userInfo->description}}
                                </p>
                            </div>            
                            <div class="col-md-12">      
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4 class="font-weight-bold">Jadwal Praktik</h4>                                        
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="text-primary">
                                                Biaya Konsultasi : @money($user->consultRule->price)
                                                </h5>                                            
                                                {{-- <div class="form-group"> --}}
                                                    <div class='input-group date' id='datetimepicker'>
                                                    <input type='text' name='consultDate' 
                                                        class="form-control" required placeholder="Pilih Tanggal"/>
                                                    <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                    </span>
                                                    </div>
                                                {{-- </div> --}}
                                                <br>
                                                @foreach($user->consultRule->schedules as $schedule)                                                
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
                                                @endforeach                                                                                           
                                            </div>
                                            <br>                                      
                                        </div>                                                                                                     
                                    </div>     
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