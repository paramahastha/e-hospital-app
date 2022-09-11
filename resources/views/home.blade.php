@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __("Cari Jadwal Dokter") }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif        
                    <table class="table table-bordered">
                        <thead>
                            <tr>             
                                <th>Kode</th>                   
                                <th>Nama</th>                                
                                <th>Posisi</th>                                
                                <th>Durasi</th>
                                <th>Harga</th>                                
                                <th colspan="2"></th>
                                {{-- <th>Published At</th>
                                <th>Created at</th>
                                <th colspan="2">Action</th> --}}
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
                                {{-- @foreach ($doctor->consultRule->schedules as $schedule)
                                    <td>{{$schedule->day}} : {{$schedule->active ? "Available" : "Unavailable"}}</td>
                                @endforeach --}}

                                {{-- <td>{{ date('Y-m-d', strtotime($post->published_at)) }}</td>
                                <td>{{ date('Y-m-d', strtotime($post->created_at)) }}</td>
                                <td>
                                --}}
                                <td >
                                    <a href="/doctor/{{$doctor->id}}/detail" class="btn btn-primary">Buat Janji</a>
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
