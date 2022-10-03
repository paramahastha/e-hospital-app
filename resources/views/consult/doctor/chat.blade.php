<!-- resources/views/consult/chat.blade.php -->
@extends('layouts.app')
@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6 mt-3">
                   <div class="d-flex align-items-center">
                   {{ __('Consultation') }}
                   </div>
                </div>   
                <div class="col-md-6 mt-2 mb-2">
                    <a href="{{ route('platform.consultation.management.detail', ['consultation' => $consult->id]) }}" 
                        class="btn btn-secondary float-end">
                       Back
                    </a>
                 </div>               
             </div>                        
        </div>
        <div class="card-body">
            <chat-messages v-on:fetchmessage="fetchmessage"  :messages="messages" :consult="{{ $consult }}"></chat-messages>
        </div>
        <div class="card-footer">
            <chat-form v-on:addmessage="addmessage" :messages="messages" :user="{{ Auth::user() }}" :consult="{{ $consult }}"></chat-form>
        </div>
    </div>
    <script>
        CountDownTimer('{{$consult->session_start}}', 'countdown', '{{$consult->id}}');
        function CountDownTimer(dt, id, consultId)
        {
            var end = new Date('{{$consult->session_end}}');    
            var _second = 1000;
            var _minute = _second * 60;
            var _hour = _minute * 60;
            var _day = _hour * 24;
            var timer;
            function showRemaining() {
                var now = new Date();
                var distance = end - now;                
                if (distance < 0) {
                    clearInterval(timer);                    
                    alert('Your session has end.');                    
                    window.location.href = `/admin/consultation-management/${consultId}/detail`;
                    return;
                } 
                var days = Math.floor(distance / _day);
                var hours = Math.floor((distance % _day) / _hour);
                var minutes = Math.floor((distance % _hour) / _minute);
                var seconds = Math.floor((distance % _minute) / _second);

                document.getElementById(id).innerHTML = days + 'days ';
                document.getElementById(id).innerHTML += hours + 'hrs ';
                document.getElementById(id).innerHTML += minutes + 'mins ';
                document.getElementById(id).innerHTML += seconds + 'secs';
                document.getElementById(id).innerHTML +='<h2>Session On Going</h2>';
            }
            timer = setInterval(showRemaining, 1000);
        }
        $("#main-navbar").addClass("hide");
    </script>
    <div id="countdown"> 
</div>
@endsection