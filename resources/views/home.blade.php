@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __("Search doctor's schedule") }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif        
                    <table class="table table-bordered">
                        <thead>
                            <tr>             
                                <th>Code</th>                   
                                <th>Name</th>                                
                                <th>Position</th>                                
                                <th>Duration</th>
                                <th>Consultation Fee</th>                                
                                <th colspan="2"></th>                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($doctorList as $doctor)
                            <tr>
                                <td>{{ $doctor->userInfo->code }}</td>
                                <td>{{ $doctor->name }}</td>       
                                <td>{{ $doctor->userInfo->position }}</td>                                
                                <td>{{ $doctor->consultRule->duration }} Minutes</td>
                                <td>@money($doctor->consultRule->price)</td>                            
                                <td >
                                    <a href="/doctor/detail/{{$doctor->id}}" class="btn btn-primary">Make Appointment</a>
                                </td> 
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>                   
                    
                    {{-- <a href="{{ url('/chat') }}">Chat</a> --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
